<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use Firebase\JWT\JWT;

class Auth extends BaseController
{
    public function index(): string
    {
        return 'Hello World';
    }

    function login()
    {
        $usuarioModel = new UsuarioModel();

        $email = $this->request->getVar('email');
        $senha = $this->request->getVar('senha');

        $usuario = $usuarioModel->autenticar(
            $email,
            $senha
        );

        $iat = time();
        $exp = $iat + 60 * 60 * 4;
        $payload = [
            "iat" => $iat,
            "exp" => $exp,
            "id" => $usuario->id,
            "email" => $usuario->email,
        ];
        $token = JWT::encode($payload, getenv('JWT_SECRET'), 'HS256');

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login efetuado com sucesso!',
            'token' => $token,
        ]);
    }
}
