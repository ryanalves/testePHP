<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidatoVagaModel extends Model
{
    protected $table = 'canditatos_vagas';

    protected $allowedFields = [
        'vaga_id',
        'candidato_id'
    ];
}
