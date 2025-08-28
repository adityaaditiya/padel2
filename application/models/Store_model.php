<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_model extends CI_Model
{
    protected $table = 'store_status';

    public function get_current()
    {
        return $this->db->order_by('id', 'DESC')->get($this->table, 1)->row();
    }

    public function open($date)
    {
        $current = $this->get_current();
        if ($current) {
            return $this->db->where('id', $current->id)
                            ->update($this->table, [
                                'store_date' => $date,
                                'is_open'    => 1,
                                'closed_at'  => NULL
                            ]);
        }
        return $this->db->insert($this->table, [
            'store_date' => $date,
            'is_open'    => 1
        ]);
    }

    public function close()
    {
        $current = $this->get_current();
        if ($current && $current->is_open) {
            $next_date = date('Y-m-d', strtotime($current->store_date . ' +1 day'));
            $this->db->where('id', $current->id)
                     ->update($this->table, [
                         'is_open'    => 0,
                         'closed_at'  => date('Y-m-d H:i:s'),
                         'store_date' => $next_date
                     ]);
            return $next_date;
        }
        return NULL;
    }

    public function validate_device_date($device_date)
    {
        $current = $this->get_current();
        if (!$current) {
            return 'Toko belum dibuka';
        }
        if ($current->is_open && $current->store_date < $device_date) {
            return 'Toko belum ditutup';
        }
        if (!$current->is_open) {
            return 'Toko belum dibuka';
        }
        if ($current->store_date !== $device_date) {
            return 'Tanggal perangkat tidak sesuai dengan tanggal toko';
        }
        return null;
    }
}
