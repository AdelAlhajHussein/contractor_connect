<?php

namespace App\Models;

use CodeIgniter\Model;

class ContractorSpecialtyModel extends Model
{
    protected $table = 'contractor_specialties';
    protected $primaryKey = null;
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $allowedFields = ['contractor_id', 'specialty_id'];
}
