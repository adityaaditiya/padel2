<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel bookings.
 */
class Booking_model extends CI_Model
{
    protected $table = 'bookings';

    public function get_by_date($date, $sort = 'jam_mulai', $order = 'asc')
    {
        $allowed = [
            'id_court'       => 'bookings.id_court',
            'kode_member'    => 'm.kode_member',
            'jam_mulai'      => 'bookings.jam_mulai',
            'jam_selesai'    => 'bookings.jam_selesai',
            'status_booking' => 'bookings.status_booking',
            'keterangan'     => 'bookings.keterangan'
        ];
        $sort_field = isset($allowed[$sort]) ? $allowed[$sort] : $allowed['jam_mulai'];
        $order      = strtolower($order) === 'desc' ? 'desc' : 'asc';
        return $this->db->select('bookings.*, m.kode_member')
                        ->from($this->table)
                        ->join('member_data m', 'm.user_id = bookings.id_user', 'left')
                        ->where('bookings.tanggal_booking', $date)
                        ->where('bookings.status_booking !=', 'batal')
                        ->order_by($sort_field, $order)
                        ->get()
                        ->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Ambil semua booking milik pengguna tertentu.
     */
    public function get_by_user($id_user)
    {
        return $this->db->select('bookings.*, courts.nama_lapangan')
                        ->from($this->table)
                        ->join('courts', 'courts.id = bookings.id_court')
                        ->where('bookings.id_user', $id_user)
                        ->order_by('tanggal_booking', 'desc')
                        ->get()
                        ->result();
    }

    /**
     * Ambil daftar booking yang dibatalkan.
     */
    public function get_cancelled($date = null)
    {
        $this->db->where('status_booking', 'batal');
        if (!empty($date)) {
            $this->db->where('tanggal_booking', $date);
        }
        return $this->db->order_by('tanggal_booking', 'desc')
                        ->get($this->table)
                        ->result();
    }

    /**
     * Periksa apakah lapangan tersedia pada tanggal dan jam tertentu.
     * Mengembalikan TRUE jika tersedia, FALSE jika ada bentrok.
     */
    public function is_available($id_court, $date, $start, $end)
    {
        /*
         * Cek ketersediaan jadwal. Bentrok jika rentang waktu overlap:
         * tidak bentrok jika (jam_selesai <= start) OR (jam_mulai >= end)
         * maka kondisi bentrok adalah negasi dari kondisi tersebut.
         * Abaikan booking yang sudah dibatalkan.
         */
        $this->db->where('id_court', $id_court);
        $this->db->where('tanggal_booking', $date);
        $this->db->where('status_booking !=', 'batal');
        $this->db->group_start();
        $this->db->where('jam_selesai >', $start);
        $this->db->where('jam_mulai <', $end);
        $this->db->group_end();
        return $this->db->get($this->table)->num_rows() === 0;
    }

    /**
     * Update booking record by ID.
     *
     * Supports updating status_booking and other fields.
     */
    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }
}
