<?php

namespace App\Controllers;


class Vaga extends BaseController
{

    public function buscarVaga($id)
    {
        $vagaModel = model('VagaModel');
        $vaga = $vagaModel->find($id);
        if (!$vaga) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vaga não encontrada!',
            ])->setStatusCode(404);
        }
        return $this->response->setJSON([
            'success' => true,
            'data' => $vaga,
        ]);
    }

    public function listarVagas()
    {
        $vagaModel = model('VagaModel');
        $vagas = $vagaModel->findAll();
        return $this->response->setJSON([
            'success' => true,
            'data' => $vagas,
        ]);
    }

    public function criarVaga()
    {
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
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao criar vaga!',
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Vaga criada com sucesso!',
            'data' => $vaga,
        ]);
    }

    public function editarVaga($vaga_id)
    {
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
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao editar vaga!',
            ])->setStatusCode(500);
        }

        $vaga = $vagaModel->find($vaga_id);
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
}
