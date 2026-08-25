<?php

namespace App\Models;

use CodeIgniter\Model;

class BiddingModel extends Model
{
    protected $table = 'tblbidding';
    protected $primaryKey = 'bidding_id';
    protected $allowedFields = ['contractor', 'amount', 'notice_date', 'contract_number', 'contract_date', 'notice_proceed'];
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
}
