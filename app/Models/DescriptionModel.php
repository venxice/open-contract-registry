<?php

namespace App\Models;

use CodeIgniter\Model;

class DescriptionModel extends Model
{
    protected $table = 'tblbidding_project_description';
    protected $primaryKey = 'description_id';
    protected $allowedFields = ['project_id', 'project_description', 'date_posted', 'project_attachment'];
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
}
