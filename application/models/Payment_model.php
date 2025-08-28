<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel payments.
 */
class Payment_model extends CI_Model
{
    protected $table = 'payments';

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_by_booking($booking_id)
    {
        return $this->db->get_where($this->table, ['id_booking' => $booking_id])->result();
    }

    public function get_by_sale($sale_id)
    {
        return $this->db->get_where($this->table, ['id_sale' => $sale_id])->result();
    }

    /**
     * Ambil ID penjualan berikutnya sebagai nomor nota sederhana.
     */
    public function get_next_sale_id()
    {
        $row = $this->db->select_max('id_sale')->get($this->table)->row();
        return $row && $row->id_sale ? $row->id_sale + 1 : 1;
    }
}
