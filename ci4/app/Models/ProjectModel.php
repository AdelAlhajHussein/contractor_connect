<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'home_owner_id',
        'category_id',
        'title',
        'description',
        'address',
        'contact_phone',
        'start_date',
        'end_date',
        'deadline_date',
        'budget_min',
        'budget_max',
        'status',
        'completed_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
