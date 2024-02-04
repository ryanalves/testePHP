<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';

    protected $allowedFields = [
        'user',
        'email',
        'senha',
        'candidato_id'
    ];

    function autenticar($user, $senha)
    {
        $sql = "SELECT * FROM usuarios WHERE (user = :user: OR email = :user:) AND senha = :senha:";
        $user = $this->db->query($sql, [
            'user' => $user,
            'senha' => md5($senha)
        ])->getRow();
        return $user;
    }
}