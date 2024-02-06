<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use Firebase\JWT\JWT;

class Home extends BaseController
{

    public function login()
    {
        return view('login');
    }

    public function logout()
    {
        helper('cookie');
        delete_cookie('token');
        return view('logout');
    }

    public function vagas()
    {
        $usuario = $this->request->usuario ?? null;
        return view('vaga/index', ['usuario' => $usuario, 'route' => 'vagas']);
    }

    public function criarVaga()
    {
        $usuario = $this->request->usuario ?? null;
        return view('vaga/form', [
            'usuario' => $usuario,

        ]);
    }

    public function visualizarVaga($id)
    {
        $usuario = $this->request->usuario ?? null;
        $vagaModel = model('VagaModel');
        $vaga = $vagaModel->find($id);
        return view('vaga/form', [
            'usuario' => $usuario,
            'vaga' => $vaga,
            'visualizar' => true
        ]);
    }

    public function editarVaga($id)
    {
        $usuario = $this->request->usuario ?? null;
        $vagaModel = model('VagaModel');
        $vaga = $vagaModel->find($id);
        return view('vaga/form', [
            'usuario' => $usuario,
            'vaga' => $vaga,
        ]);
    }


    public function candidaturas()
    {
        $usuario = $this->request->usuario ?? null;
        return view('vaga/candidaturas', ['usuario' => $usuario, 'route' => 'candidaturas']);
    }

    public function usuarios()
    {
        $usuario = $this->request->usuario ?? null;
        return view('usuario/index', ['usuario' => $usuario, 'route' => 'usuarios']);
    }
}
