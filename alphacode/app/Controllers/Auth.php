<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use Firebase\JWT\JWT;

class Auth extends BaseController
{
    public function index()
    {
        $usuario = $this->request->usuario ?? null;
        return $this->response->setJSON([
            "success" => true,
            "data" => $usuario,
        ]);
    }

    function login()
    {
        $usuarioModel = new UsuarioModel();

        $email = $this->request->getVar('email');
        $senha = $this->request->getVar('senha');

        $dados = [
            "email" => $email,
            "senha" => $senha,
        ];
        $regras =  [
            "email" => "required|valid_email",
            "senha" => "required",
        ];

        if (!$this->validateData($dados, $regras)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao logar!',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $usuario = $usuarioModel->autenticar($email, $senha);
        if (!$usuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuário ou senha inválidos!',
            ])->setStatusCode(401);
        }

        $iat = time();
        $exp = $iat + 60 * 60 * 4;
        $payload = [
            "iat" => $iat,
            "exp" => $exp,
            "id" => $usuario->id,
            "email" => $usuario->email,
        ];
        $token = JWT::encode($payload, getenv('JWT_SECRET'), 'HS256');

        helper('cookie');
        set_cookie('token', $token, $exp);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login efetuado com sucesso!',
            'token' => $token,
        ]);
    }

    function logout()
    {
        helper('cookie');
        delete_cookie('token');
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Logout efetuado com sucesso!',
        ]);
    }
}
