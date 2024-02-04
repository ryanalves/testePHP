<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthorizateFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        helper('authentication');
        $header = $request->getHeader("Authorization");
        $token = extract_bearer_token($header);
        $payload = decode_token($token);

        $usuario =  null;
        if ($payload) {
            $usuarioModel = new \App\Models\UsuarioModel();
            $usuario = $usuarioModel->find($payload->id);
            $request->usuario = $usuario;
        }

        if (!$usuario) {
            $response = service('response');
            $response->setJSON(['success' => false, 'message' => 'Acesso negado']);
            $response->setStatusCode(401);
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
