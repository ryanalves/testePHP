<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidatoVagaModel extends Model
{
    protected $table = 'canditatos_vagas';

    protected $useSoftDeletes = true;
    protected $useTimestamps = true;

    protected $allowedFields = [
        'vaga_id',
        'candidato_id'
    ];
}
