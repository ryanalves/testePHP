<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class DataBootstrap extends Migration
{
    public function up()
    {
        // Tabela de usuários
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'senha' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'candidato_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],

        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('candidato_id', false);
        $this->forge->createTable('usuarios');

        // Tabela de candidatos
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'data_nascimento' => [
                'type' => 'DATE'
            ],
            'descricao' => [
                'type' => 'TEXT'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('candidatos');

        // Tabela de candidatos
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'tipo' => [
                'type' => 'ENUM',
                'constraint' => ['CLT', 'PJ', 'FREELANCER'],
                'default' => 'CLT ',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['DISPONIVEL', 'PAUSADO', 'ENCERRADO'],
                'default' => 'DISPONIVEL',
            ],
            'area' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'pretensao' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ],
            'descricao' => [
                'type' => 'TEXT'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('vagas');

        // Tabela de candidatos
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'vaga_id' => [
                'type' => 'INT',
                'constraint' => 5,
            ],
            'candidato_id' => [
                'type' => 'INT',
                'constraint' => 5,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],

        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['vaga_id', 'candidato_id'], false);
        $this->forge->createTable('canditatos_vagas');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
        $this->forge->dropTable('candidatos');
        $this->forge->dropTable('vagas');
        $this->forge->dropTable('canditatos_vagas');
    }
}
