<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username','first_name','last_name','email','phone',
        'role','is_active','created_at','updated_at'
    ];

    protected $useTimestamps = true;
}
