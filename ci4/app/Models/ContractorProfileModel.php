<?php

namespace App\Models;

use CodeIgniter\Model;

class ContractorProfileModel extends Model
{
    protected $table = 'contractor_profiles';
    protected $primaryKey = 'contractor_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'contractor_id',
        'address',
        'city',
        'province',
        'postal_code',
        'approval_status',
        'created_at',
        'updated_at'
    ];


    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
