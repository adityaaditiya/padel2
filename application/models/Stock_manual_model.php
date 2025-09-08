<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk mencatat transaksi stok manual.
 */
class Stock_manual_model extends CI_Model
{
    protected $table = 'manual_stock_logs';

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_report($start = null, $end = null, $keyword = null)
    {
        $this->db->select('l.*, p.nama_produk');
        $this->db->from($this->table . ' l');
        $this->db->join('products p', 'p.id = l.product_id');
        if ($start) {
            $this->db->where('DATE(l.created_at) >=', $start);
        }
        if ($end) {
            $this->db->where('DATE(l.created_at) <=', $end);
        }
        if ($keyword) {
            $this->db->like('p.nama_produk', $keyword);
        }
        $this->db->order_by('l.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
