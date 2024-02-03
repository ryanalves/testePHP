<?php

namespace App\Models;

use CodeIgniter\Model;

class VagaModel extends Model
{
    protected $table = 'vagas';

    protected $allowedFields = [
        'nome',
        'tipo',
        'status',
        'area',
        'pretensao',
        'descricao'
    ];
}
