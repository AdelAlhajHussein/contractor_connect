<?php

namespace App\Models;

use CodeIgniter\Model;

class BidTaskModel extends Model
{
    protected $table         = 'bid_tasks';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'bid_id',
        'task_order',
        'description',
        'est_minutes',
        'materials_cost',
        'labour_cost',
        'hst_cost',
    ];
}
