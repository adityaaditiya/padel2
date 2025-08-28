<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel sales (penjualan F&B).
 */
class Sale_model extends CI_Model
{
    protected $table = 'sales';

    public function insert($data)
    {
        $insertData = [
            'id_kasir'      => $data['id_kasir'],
            'customer_id'   => isset($data['customer_id']) ? $data['customer_id'] : null,
            'nomor_nota'    => $data['nomor_nota'],
            'total_belanja' => $data['total_belanja']
        ];

        $this->db->insert($this->table, $insertData);
        return $this->db->insert_id();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_all($start_date = null, $end_date = null)
    {
        $this->db->select('s.*, u.nama_lengkap AS customer_name');
        $this->db->from($this->table . ' s');
        $this->db->join('users u', 'u.id = s.customer_id', 'left');
        if ($start_date) {
            $this->db->where('DATE(s.tanggal_transaksi) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(s.tanggal_transaksi) <=', $end_date);
        }
        $this->db->order_by('s.tanggal_transaksi', 'DESC');
        return $this->db->get()->result();
    }
}
