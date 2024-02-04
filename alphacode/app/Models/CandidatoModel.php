<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidatoModel extends Model
{
    protected $table = 'candidatos';

    protected $allowedFields = [
        'nome',
        'data_nascimento',
        'descricao'
    ];
}
