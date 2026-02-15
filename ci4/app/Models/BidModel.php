<?php

namespace App\Models;

use CodeIgniter\Model;

class BidModel extends Model
{
    protected $table            = 'bids';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'project_id',
        'contractor_id',
        'details',
        'bid_amount',
        'est_total_minutes',
        'materials_cost',
        'labour_cost',
        'hst_cost',
        'total_cost',
        'status',
    ];
}
