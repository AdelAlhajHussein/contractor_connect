<?php

namespace App\Models;

use CodeIgniter\Model;

class ContractorCertificationModel extends Model
{
    protected $table = 'contractor_certifications';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'contractor_id', 'title', 'issued_by', 'date_issued', 'attachment_path',
        'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
