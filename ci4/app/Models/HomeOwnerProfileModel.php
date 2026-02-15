<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeOwnerProfileModel extends Model
{
    protected $table = 'home_owner_profiles';
    protected $primaryKey = 'home_owner_id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'home_owner_id',
        'address',
        'city',
        'province',
        'postal_code',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
