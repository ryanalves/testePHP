<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BootstrapSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'user' => 'admin',
                'email' => 'admin@email.com',
                'senha' => '123456',
            ],
        ];
        foreach ($data as $row) {
            $this->db->query('INSERT INTO usuarios (id, user, email, senha) VALUES(:id:, :user:, :email:, MD5(:senha:))', $row);
        }

        $data = [
            [
                'id' => 2,
                'user' => 'user1',
                'email' => 'user1@email.com',
                'senha' => '123456',
                'candidato_id' => 1
            ],
            [
                'id' => 3,
                'user' => 'user2',
                'email' => 'user2@email.com',
                'senha' => '123456',
                'candidato_id' => 2
            ],
        ];
        foreach ($data as $row) {
            $this->db->query('INSERT INTO usuarios (id, user, email, senha, candidato_id) VALUES(:id:, :user:, :email:, MD5(:senha:), :candidato_id:)', $row);
        }
        
        $data = [
            [
                'id' => 1,
                'nome' => 'Jose da Silva',
                'data_nascimento' => '1990-02-04',
                'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
            [
                'id' => 2,
                'nome' => 'João de Souza',
                'data_nascimento' => '1995-05-12',
                'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        ];
        foreach ($data as $row) {
            $this->db->query('INSERT INTO candidatos (id, nome, data_nascimento, descricao) VALUES(:id:, :nome:, :data_nascimento:, :descricao:)', $row);
        }

        $data = [
            [
                'nome' => 'Vaga 1',
                'tipo' => 'PJ',
                'status' => 'DISPONIVEL',
                'area' => 'Desenvolvimento',
                'pretensao' => 5000,
                'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
            [
                'nome' => 'Vaga 2',
                'tipo' => 'CLT',
                'status' => 'PAUSADO',
                'area' => 'Design',
                'pretensao' => 4000,
                'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
            [
                'nome' => 'Vaga 3',
                'tipo' => 'FREELANCER',
                'status' => 'DISPONIVEL',
                'area' => 'Marketing',
                'pretensao' => 3000,
                'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
            [
                'nome' => 'Vaga 4',
                'tipo' => 'PJ',
                'status' => 'ENCERRADO',
                'area' => 'Desenvolvimento',
                'pretensao' => 6000,
                'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
            [
                'nome' => 'Vaga 5',
                'tipo' => 'CLT',
                'status' => 'DISPONIVEL',
                'area' => 'Design',
                'pretensao' => 4500,
                'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        ];
        foreach ($data as $row) {
            $this->db->query('INSERT INTO vagas (nome, tipo, status, area, pretensao, descricao) VALUES(:nome:, :tipo:, :status:, :area:, :pretensao:, :descricao:)', $row);
        }

        $data = [
            [
                'candidato_id' => 1,
                'vaga_id' => 1,
            ],
            [
                'candidato_id' => 2,
                'vaga_id' => 1,
            ],
            [
                'candidato_id' => 1,
                'vaga_id' => 2,
            ],
            [
                'candidato_id' => 1,
                'vaga_id' => 3,
            ],
            [
                'candidato_id' => 2,
                'vaga_id' => 4,
            ],
            [
                'candidato_id' => 2,
                'vaga_id' => 5,
            ],
        ];
        foreach ($data as $row) {
            $this->db->query('INSERT INTO candidatos_vagas (candidato_id, vaga_id) VALUES(:candidato_id:, :vaga_id:)', $row);
        }
    }
}
