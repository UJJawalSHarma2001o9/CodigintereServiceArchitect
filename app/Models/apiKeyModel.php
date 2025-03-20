<?php
namespace App\Models;
use CodeIgniter\Model;

class ApiKeyModel extends Model
{
    protected $table = 'user_login';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'uid',
        'user_id',
        'email',
        'password',
        'prefix',
        'hash',
        'expire',
        'created_at',
        'updated_at',
        'status'
    ];

}