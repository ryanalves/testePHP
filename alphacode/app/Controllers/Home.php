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
        $candidatura = null;
        if ($usuario['candidato_id'] != null) {
            $candidatoModel = model('CandidatoModel');
            $candidato = $candidatoModel->find($usuario['candidato_id']);
            $CandidatoVagaModel = model('CandidatoVagaModel');
            $candidatura = $CandidatoVagaModel->where('candidato_id', $candidato['id'])->where('vaga_id', $vaga['id'])->first();
        }
        return view('vaga/form', [
            'usuario' => $usuario,
            'vaga' => $vaga,
            'candidatura' => $candidatura,
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

    public function criarUsuario()
    {
        $usuario = $this->request->usuario ?? null;
        return view('usuario/form', [
            'usuario' => $usuario,
        ]);
    }

    public function visualizarUsuario($id)
    {
        $usuario = $this->request->usuario ?? null;
        $usuarioModel = model('UsuarioModel');
        $candidatoModel = model('CandidatoModel');
        $model = $usuarioModel->find($id);
        if ($model['candidato_id'] != null) {
            $candidato = $candidatoModel->find($model['candidato_id']);
            $model['nome'] = $candidato['nome'];
            $model['data_nascimento'] = $candidato['data_nascimento'];
            $model['descricao'] = $candidato['descricao'];
        }
        return view('usuario/form', [
            'usuario' => $usuario,
            'model' => $model,
            'visualizar' => true
        ]);
    }

    public function editarUsuario($id)
    {
        $usuario = $this->request->usuario ?? null;
        $usuarioModel = model('UsuarioModel');
        $candidatoModel = model('CandidatoModel');
        $model = $usuarioModel->find($id);
        if ($model['candidato_id'] != null) {
            $candidato = $candidatoModel->find($model['candidato_id']);
            $model['nome'] = $candidato['nome'];
            $model['data_nascimento'] = $candidato['data_nascimento'];
            $model['descricao'] = $candidato['descricao'];
        }
        return view('usuario/form', [
            'usuario' => $usuario,
            'model' => $model,
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
