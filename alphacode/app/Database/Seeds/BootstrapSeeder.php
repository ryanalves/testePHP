<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BootstrapSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'user' => 'admin',
            'email' => 'admin@email.com',
            'senha' => '123456',
        ];

        // Simple Queries
        $this->db->query('INSERT INTO usuarios (user, email, senha) VALUES(:user:, :email:, MD5(:senha:))', $data);
    }
}
