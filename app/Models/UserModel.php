<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'tbluser';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['first_name', 'last_name', 'middle_initial', 'email', 'password', 'role', 'status'];
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $hidden = ['password'];
}
