<?php

namespace App\Controllers;


class Candidato extends BaseController
{

    public function buscarCandidato($id)
    {
        $candidatoModel = model('CandidatoModel');
        $candidato = $candidatoModel->find($id);
        if (!$candidato) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Candidato não encontrado!',
            ])->setStatusCode(404);
        }
        return $this->response->setJSON([
            'success' => true,
            'data' => $candidato,
        ]);
    }

    public function listarCandidatos()
    {
        $candidatoModel = model('CandidatoModel');
        $candidatos = $candidatoModel->findAll();
        return $this->response->setJSON([
            'success' => true,
            'data' => $candidatos,
        ]);
    }

    public function criarCandidato()
    {
        $usuarioModel = model('UsuarioModel');
        $candidatoModel = model('CandidatoModel');

        $regras = [
            'user' => 'required|max_length[255]',
            'email' => 'required|valid_email',
            'senha' => 'required|min_length[6]',
            'nome' => 'required',
            'data_nascimento' => 'required|valid_date',
            'descricao' => 'required'
        ];

        $dados = [
            'user' => $this->request->getVar('user'),
            'email' => $this->request->getVar('email'),
            'senha' => $this->request->getVar('senha'),
            'nome' => $this->request->getVar('nome'),
            'data_nascimento' => $this->request->getVar('data_nascimento'),
            'descricao' => $this->request->getVar('descricao')
        ];

        if (!$this->validateData($dados, $regras)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao criar candidato!',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        // Criptografa a senha
        if ($dados['senha']) {
            $dados['senha'] = md5($this->request->getVar('senha'));
        }

        $candidato_id = $candidatoModel->insert($dados);
        $dados['candidato_id'] = $candidato_id;
        $usuario_id = $usuarioModel->insert($dados);
        $candidato = $candidatoModel->find($candidato_id);
        $candidato['usuario'] = $usuarioModel->find($usuario_id);

        if (!$candidato) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao criar candidato!',
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Candidato criada com sucesso!',
            'data' => $candidato,
        ]);
    }

    public function editarCandidato()
    {
        $usuario = $this->request->usuario;
        if ($usuario['candidato_id'] != null) {
            return $this->editarCandidatoPorId($usuario['candidato_id']);
        }
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Candidato não encontrado!',
        ])->setStatusCode(404);
    }

    public function editarCandidatoPorId($candidato_id)
    {
        $usuarioModel = model('UsuarioModel');
        $candidatoModel = model('CandidatoModel');

        $candidato = $candidatoModel->find($candidato_id);
        $usuario = $usuarioModel->where('candidato_id', $candidato_id)->first();

        if (!$candidato || !$usuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Candidato não encontrado!',
            ])->setStatusCode(404);
        }

        $dadosUsuario = [];
        $regrasUsuario = [];

        // Campos e regras de validação (Usuario)
        $camposRegrasUsuario =  [
            'user' => 'required|max_length[255]',
            'email' => 'required|valid_email',
            'senha' => 'required|min_length[6]',
        ];

        // Regras dinamicas de acordo com os campos enviados
        foreach ($camposRegrasUsuario as $key => $regra) {
            $valor = $this->request->getVar($key);
            if ($valor) {
                $regrasUsuario[$key] = $regra;
                $dadosUsuario[$key] = $valor;
            }
        }

        // Campos e regras de validação (Candidato)
        $camposRegrasCandidato =  [
            'nome' => 'required',
            'data_nascimento' => 'required|valid_date',
            'descricao' => 'required'
        ];

        $dadosCandidato = [];
        $regrasCandidato = [];

        // Regras dinamicas de acordo com os campos enviados
        foreach ($camposRegrasCandidato as $key => $regra) {
            $valor = $this->request->getVar($key);
            if ($valor) {
                $regrasCandidato[$key] = $regra;
                $dadosCandidato[$key] = $valor;
            }
        }

        if (sizeof($dadosUsuario) > 0) {
            if (!$this->validateData($dadosUsuario, $regrasUsuario)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao editar usuario!',
                    'errors' => $this->validator->getErrors(),
                ])->setStatusCode(400);
            }

            // Se a senha foi enviada, criptografa a senha
            if (isset($dadosUsuario['senha'])) {
                $dadosUsuario['senha'] = md5($this->request->getVar('senha'));
            }

            if (!$usuarioModel->update($usuario["id"], $dadosUsuario)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao editar usuario!',
                ])->setStatusCode(500);
            }
        }

        if ($candidato != null && sizeof($dadosCandidato) > 0) { // Se o usuario for um candidato
            if (!$this->validateData($dadosCandidato, $regrasCandidato)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao editar candidato!',
                    'errors' => $this->validator->getErrors(),
                ])->setStatusCode(400);
            }
            if (!$candidatoModel->update($candidato["id"], $dadosCandidato)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao editar candidato!',
                ])->setStatusCode(500);
            }
        }

        if (sizeof($dadosUsuario) == 0 && sizeof($dadosCandidato) == 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nenhum dado enviado para edição!',
            ])->setStatusCode(400);
        }

        $candidato = $candidatoModel->find($candidato_id);
        $usuario = $usuarioModel->where("candidato_id", $candidato['id'])->first();
        $candidato['usuario'] = $usuario;

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Candidato editada com sucesso!',
            'data' => $candidato
        ]);
    }

    public function deletarCandidatos()
    {
        $candidatoModel = model('CandidatoModel');
        $usuarioModel = model('UsuarioModel');

        $ids = $this->request->getVar('id');
        if (!$ids) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao deletar candidato(s)!',
                'errors' => [
                    'id' => 'O campo id é obrigatório!'
                ],
            ])->setStatusCode(400);
        }

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $usuarios = $usuarioModel->whereIn("candidato_id", $ids)->find();
        $usuariosIds = [];
        foreach ($usuarios as $usuario) {
            $usuariosIds[] = $usuario['id'];
        }

        $result = true;
        $resultUsuarios = true;
        if (sizeof($ids)) {
            $result = $candidatoModel->delete($ids);
        }
        if (sizeof($usuariosIds)) {
            $resultUsuarios = $usuarioModel->delete($usuariosIds);
        }

        if (!$result || !$resultUsuarios) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao deletar candidato(s)!',
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Candidato(s) deletado(s) com sucesso!',
        ]);
    }
}
