<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_status_model extends CI_Model
{
    protected $table = 'store_status';

    /**
     * Ambil tanggal toko terakhir.
     */
    public function get_store_date()
    {
        $row = $this->db->select('store_date')
                        ->order_by('store_date', 'DESC')
                        ->get($this->table, 1)
                        ->row();
        return $row ? $row->store_date : NULL;
    }
    /**
     * Advance the store date by one day and persist it.
     */
    public function close_store()
    {
        $current = $this->get_store_date();
        $next = date('Y-m-d', strtotime(($current ?: date('Y-m-d')) . ' +1 day'));
        return $this->db->insert($this->table, ['store_date' => $next]);
    }
}
