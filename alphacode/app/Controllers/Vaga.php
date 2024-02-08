<?php

namespace App\Controllers;


class Vaga extends BaseController
{

    public function buscarVaga($id)
    {
        $vagaModel = model('VagaModel');
        $candidatoVagaModel = model('CandidatoVagaModel');
        $candidatoModel = model('CandidatoModel');

        $vaga = $vagaModel->find($id);
        if (!$vaga) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vaga não encontrada!',
            ])->setStatusCode(404);
        }

        $candidatosVaga = $candidatoVagaModel->where('vaga_id', $id)->findAll();
        $candidatosIds = [];
        foreach ($candidatosVaga as $candidatoVaga) {
            $candidatosIds[] = $candidatoVaga['candidato_id'];
        }
        $candidatos = [];
        if (sizeof($candidatosIds) > 0) {
            $candidatos = $candidatoModel->find($candidatosIds);
        }
        $vaga['candidatos'] = $candidatos;

        return $this->response->setJSON([
            'success' => true,
            'data' => $vaga,
        ]);
    }

    public function listarVagas()
    {
        $vagaModel = model('VagaModel');

        $skip = $this->request->getVar('start') ?? 0;
        $limit = $this->request->getVar('length') ?? 20;
        $search = $this->request->getVar('search');
        if (isset($search)) {
            $searchTerm = $this->request->getVar('search')['value'] ?? '';
        }

        $db = \Config\Database::connect();
        $builder = $db->table('vagas');
        $builder->where('deleted_at', null);
        $vagasTotal = $builder->get()->getNumRows();

        $builder = $db->table('vagas');
        $builder->where('deleted_at', null);
        if (!empty($searchTerm)) {
            $builder->orHavingLike('nome', $searchTerm);
            $builder->orHavingLike('status', $searchTerm);
            $builder->orHavingLike('tipo', $searchTerm);
            $builder->orHavingLike('area', $searchTerm);
            $builder->orHavingLike('descricao', $searchTerm);
        }
        if ($this->request->getVar('order')) {
            $columns = [
                'id',
                'nome',
                'tipo',
                'status',
                'area',
                'pretensao',
            ];
            $columnId = $this->request->getVar('order')[0]['column'];
            $column = $columns[$columnId];
            $dir = $this->request->getVar('order')[0]['dir'];
            $builder->orderBy($column, $dir);
        }

        $builder->limit($limit, $skip);
        $query = $builder->get()->getResult();

        return $this->response->setJSON([
            'recordsTotal' => $vagasTotal,
            'recordsFiltered' => $vagasTotal,
            'success' => true,
            'data' => $query,
        ]);
    }

    public function listarCandidaturas()
    {
        $candidatoVagaModel = model('CandidatoVagaModel');
        $vagasModel = model('VagaModel');

        $usuario = $this->request->usuario;

        $candidatoVagas = $candidatoVagaModel->where('candidato_id', $usuario['candidato_id'])->findAll();
        $vagasIds = [];
        foreach ($candidatoVagas as $candidatoVaga) {
            $vagasIds[] = $candidatoVaga['vaga_id'];
        }
        if (sizeof($vagasIds) > 0) {
            $vagas = $vagasModel->find($vagasIds);
        } else {
            $vagas = [];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $vagas,
        ]);
    }

    public function criarVaga()
    {
        helper('toast');
        $vagaModel = model('VagaModel');

        $regras = [
            'nome' => 'required|max_length[255]',
            'tipo' => 'required|in_list[CLT,PJ,FREELANCER]',
            'status' => 'required|in_list[DISPONIVEL,PAUSADO,ENCERRADO]',
            'area' => 'required|max_length[255]',
            "pretensao" => "required|numeric|greater_than_equal_to[0]",
            'descricao' => 'required',
        ];

        $dados = [];
        foreach ($regras as $key => $value) {
            $dados[$key] = $this->request->getVar($key);
        }

        if (!$this->validateData($dados, $regras)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao criar vaga!',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $vaga_id = $vagaModel->insert($dados);
        $vaga = $vagaModel->find($vaga_id);
        if (!$vaga) {
            set_toast('danger', 'Erro', 'Erro ao criar vaga!');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao criar vaga!',
            ])->setStatusCode(500);
        }

        set_toast('success', 'Sucesso', 'Vaga criada com sucesso!');
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Vaga criada com sucesso!',
            'data' => $vaga,
        ]);
    }

    public function candidatar($vaga_id)
    {
        helper('toast');
        $vagaModel = model('VagaModel');
        $candidatoVagaModel = model('CandidatoVagaModel');

        $vaga = $vagaModel->find($vaga_id);
        if (!$vaga) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vaga não encontrada!',
            ])->setStatusCode(404);
        }

        $usuario = $this->request->usuario;
        $vaga = $vagaModel->find($vaga_id);
        $candidatoVaga = $candidatoVagaModel->where('candidato_id', $usuario['candidato_id'])->where('vaga_id', $vaga_id)->first();

        if ($vaga['status'] != 'DISPONIVEL') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vaga não está disponível para candidatura!',
            ])->setStatusCode(400);
        }

        if ($candidatoVaga) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Candidato já está cadastrado na vaga!',
            ])->setStatusCode(400);
        }

        $dados = [
            'candidato_id' => $usuario['candidato_id'],
            'vaga_id' => $vaga_id,
        ];
        $candidatoVaga_id = $candidatoVagaModel->insert($dados);
        $candidatoVaga = $candidatoVagaModel->find($candidatoVaga_id);
        if (!$candidatoVaga) {
            set_toast('danger', 'Erro', 'Erro ao candidatar-se a vaga!');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao candidatar-se a vaga!',
            ])->setStatusCode(500);
        }

        set_toast('success', 'Sucesso', 'Candidatura cadastrada com sucesso!');
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Candidato cadastrado na vaga com sucesso!',
            'data' => $candidatoVaga,
        ]);
    }

    public function editarVaga($vaga_id)
    {
        helper('toast');
        $vagaModel = model('VagaModel');

        $vaga = $vagaModel->find($vaga_id);
        if (!$vaga) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vaga não encontrada!',
            ])->setStatusCode(404);
        }

        $dados = [];
        $regras = [];

        $camposRegras =  [
            "nome" => "required|max_length[255]",
            "tipo" => "required|in_list[CLT,PJ,FREELANCER]",
            "status" => "required|in_list[DISPONIVEL,PAUSADO,ENCERRADO]",
            "area" => "required|max_length[255]",
            "pretensao" => "required|numeric|greater_than_equal_to[0]",
            "descricao" => "required",
        ];

        // Regras dinamicas de acordo com os campos enviados
        foreach ($camposRegras as $key => $regra) {
            $valor = $this->request->getVar($key);
            if ($valor) {
                $regras[$key] = $regra;
                $dados[$key] = $valor;
            }
        }

        if (!$this->validateData($dados, $regras)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao editar vaga!',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        if (sizeof($dados) == 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nenhum dado enviado para edição!',
            ])->setStatusCode(400);
        }

        if (!$vagaModel->update($vaga_id, $dados)) {
            set_toast('danger', 'Erro', 'Erro ao editar vaga!');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao editar vaga!',
            ])->setStatusCode(500);
        }

        $vaga = $vagaModel->find($vaga_id);
        set_toast('success', 'Sucesso', 'Vaga editada com sucesso!');
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Vaga editada com sucesso!',
            'data' => $vaga
        ]);
    }

    public function deletarVagas()
    {
        $vagaModel = model('VagaModel');

        $ids = $this->request->getVar('id');
        if (!$ids) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao deletar vaga!',
                'errors' => [
                    'id' => 'O campo id é obrigatório!'
                ],
            ])->setStatusCode(400);
        }

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        if (is_numeric($ids)) {
            $ids = [$ids];
        }

        $result = $vagaModel->delete($ids);

        if (!$result) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao deletar vaga!',
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Vaga deletada com sucesso!',
        ]);
    }


    public function cancelarCandidatura($vaga_id)
    {
        helper('toast');
        $candidatoVagaModel = model('CandidatoVagaModel');

        $usuario = $this->request->usuario;
        $candidatoVaga = $candidatoVagaModel->where('candidato_id', $usuario['candidato_id'])->where('vaga_id', $vaga_id)->first();

        if ($candidatoVaga == null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Candidatura não encontrada!',
            ])->setStatusCode(404);
        }


        $result = $candidatoVagaModel->delete($candidatoVaga['id']);
        if (!$result) {
            set_toast('danger', 'Erro', 'Erro ao cancelar candidatura!');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao cancelar candidatura!',
            ])->setStatusCode(500);
        }

        set_toast('success', 'Sucesso', 'Candidatura cancelada com sucesso!');
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Candidatura cancelada com sucesso!',
        ]);
    }
}
