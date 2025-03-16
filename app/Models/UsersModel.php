<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'user_id',
        'name',
        'email',
        'address',
        'phone',
        'password',
        'created_at',
        'updated_at',
        'status'
    ];
}
