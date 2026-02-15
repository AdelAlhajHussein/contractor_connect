<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'is_visible',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}
