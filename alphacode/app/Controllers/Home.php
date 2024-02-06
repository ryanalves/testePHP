<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use Firebase\JWT\JWT;

class Home extends BaseController
{
    public function index()
    {
        return view('vaga/index');
    }
    
    public function candidaturas()
    {
        return view('vaga/candidaturas');
    }
    
    public function login()
    {
        return view('login');
    }

    public function logout()
    {
        helper('cookie');
        delete_cookie('token');
        return redirect('/');
    }

    public function usuarios()
    {
        return view('usuario/index');
    }
}
