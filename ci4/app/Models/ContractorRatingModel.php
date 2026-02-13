<?php

namespace App\Models;

use CodeIgniter\Model;

class ContractorRatingModel extends Model
{
    protected $table         = 'contractor_ratings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'project_id',
        'home_owner_id',
        'contractor_id',
        'quality',
        'timeliness',
        'communication',
        'pricing',
        'recommend',
        'notes',
    ];
}
