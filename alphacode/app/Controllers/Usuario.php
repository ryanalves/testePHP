<?php

namespace App\Controllers;


class Usuario extends BaseController
{

    public function buscarUsuario($id)
    {
        $usuarioModel = model('UsuarioModel');
        $usuario = $usuarioModel->find($id);
        if (!$usuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario não encontrada!',
            ])->setStatusCode(404);
        }
        return $this->response->setJSON([
            'success' => true,
            'data' => $usuario,
        ]);
    }

    public function listarUsuarios()
    {
        $usuarioModel = model('UsuarioModel');
        $usuarios = $usuarioModel->findAll();
        return $this->response->setJSON([
            'success' => true,
            'data' => $usuarios,
        ]);
    }

    public function criarUsuario()
    {
        $usuarioModel = model('UsuarioModel');

        $regras = [
            'user' => 'required|max_length[255]',
            'email' => 'required|valid_email',
            'senha' => 'required|min_length[6]',
        ];

        $dados = [
            'user' => $this->request->getVar('user'),
            'email' => $this->request->getVar('email'),
            'senha' => $this->request->getVar('senha')
        ];

        if (!$this->validateData($dados, $regras)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao criar usuario!',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        // Criptografa a senha
        if ($dados['senha']) {
            $dados['senha'] = md5($this->request->getVar('senha'));
        }

        $usuario_id = $usuarioModel->insert($dados);
        $usuario = $usuarioModel->find($usuario_id);
        if (!$usuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao criar usuario!',
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Usuario criada com sucesso!',
            'data' => $usuario,
        ]);
    }

    public function editarUsuario($usuario_id)
    {
        $usuarioModel = model('UsuarioModel');
        $candidatoModel = model('CandidatoModel');

        $usuario = $usuarioModel->find($usuario_id);
        $candidato =  null;
        if ($usuario['candidato_id'] != null) {
            $candidato = $candidatoModel->find($usuario['candidato_id']);
        }

        if (!$usuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario não encontrada!',
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

            if (!$usuarioModel->update($usuario_id, $dadosUsuario)) {
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

        $usuario = $usuarioModel->find($usuario_id);
        if ($usuario['candidato_id'] != null) {
            $candidato = $candidatoModel->find($usuario['candidato_id']);
            $usuario['candidato'] = $candidato;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Usuario editada com sucesso!',
            'data' => $usuario
        ]);
    }

    public function deletarUsuarios()
    {
        $usuarioModel = model('UsuarioModel');
        $candidatoModel = model('CandidatoModel');

        $ids = $this->request->getVar('id');
        if (!$ids) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao deletar usuario!',
                'errors' => [
                    'id' => 'O campo id é obrigatório!'
                ],
            ])->setStatusCode(400);
        }

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        $usuarios = $usuarioModel->find($ids);
        $candidatosIds = [];
        foreach ($usuarios as $usuario) {
            if ($usuario['candidato_id'] != null) {
                $candidatosIds[] = $usuario['candidato_id'];
            }
        }

        $result = $usuarioModel->delete($ids);
        if (sizeof($candidatosIds) > 0) {
            $resultCandidato = $candidatoModel->delete($candidatosIds);
        }

        if (!$result) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao deletar usuario!',
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Usuario deletada com sucesso!',
        ]);
    }
}
