<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk menyimpan hasil stock opname.
 */
class Stock_opname_model extends CI_Model
{
    protected $table = 'stock_opnames';

    public function insert_batch($data)
    {
        return $this->db->insert_batch($this->table, $data);
    }

    public function get_report()
    {
        $this->db->select('s.*, p.nama_produk');
        $this->db->from($this->table . ' s');
        $this->db->join('products p', 'p.id = s.product_id');
        return $this->db->get()->result();
    }

    public function delete_except($timestamp)
    {
        $this->db->where('opname_at !=', $timestamp);
        return $this->db->delete($this->table);
    }
}
