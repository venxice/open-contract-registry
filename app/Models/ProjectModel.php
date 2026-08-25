<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table = 'tblbidding_project';
    protected $primaryKey = 'project_id';
    protected $allowedFields = ['bidding_id', 'project_title'];
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
}
