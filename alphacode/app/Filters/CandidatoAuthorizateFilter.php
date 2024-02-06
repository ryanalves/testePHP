<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CandidatoAuthorizateFilter extends AuthenticateFilter
{

    public function before(RequestInterface $request, $arguments = null)
    {
        parent::before($request, $arguments);
        $usuario =  $request->usuario ?? null;

        if (!$usuario) {
            $response = service('response');
            $response->setJSON(['success' => false, 'message' => 'Acesso negado']);
            $response->setStatusCode(401);
            return $response;
        }

        if ($usuario["candidato_id"] == null) {
            $response = service('response');
            $response->setJSON(['success' => false, 'message' => 'Acesso restrito a candidatos!']);
            $response->setStatusCode(401);
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
