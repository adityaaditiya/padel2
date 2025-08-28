<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cash_model extends CI_Model
{
    protected $table = 'cash_transactions';

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }
}
