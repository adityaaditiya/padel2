<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel bookings.
 */
class Booking_model extends CI_Model
{
    protected $table = 'bookings';

    /**
     * Generate a booking code with format YYMMDD-XXXX where XXXX
     * is the incremental number of bookings for the current day.
     */
    private function generate_booking_code()
    {
        $prefix = date('ymd') . '-';
        $this->db->like('booking_code', $prefix, 'after');
        $this->db->select('booking_code');
        $this->db->order_by('booking_code', 'desc');
        $this->db->limit(1);
        $last = $this->db->get($this->table)->row();
        $num  = $last ? (int) substr($last->booking_code, 7) : 0;
        return $prefix . sprintf('%04d', $num + 1);
    }

    public function get_by_date($date, $sort = 'jam_mulai', $order = 'asc')
    {
        $allowed = [
            'id_court'       => 'courts.nama_lapangan',
            'kode_member'    => 'm.kode_member',
            'tanggal_booking'=> 'bookings.tanggal_booking',
            'jam_mulai'      => 'bookings.jam_mulai',
            'jam_selesai'    => 'bookings.jam_selesai',
            'status_pembayaran' => 'bookings.status_pembayaran',
            'status_booking' => 'bookings.status_booking',
            'keterangan'     => 'bookings.keterangan'
        ];
        $sort_field = isset($allowed[$sort]) ? $allowed[$sort] : $allowed['jam_mulai'];
        $order      = strtolower($order) === 'desc' ? 'desc' : 'asc';
        return $this->db->select('bookings.*, m.kode_member, courts.nama_lapangan')
                        ->from($this->table)
                        ->join('member_data m', 'm.user_id = bookings.id_user', 'left')
                        ->join('courts', 'courts.id = bookings.id_court', 'left')
                        ->where('bookings.tanggal_booking', $date)
                        ->where('bookings.status_booking !=', 'batal')
                        ->order_by($sort_field, $order)
                        ->get()
                        ->result();
    }

    public function get_by_date_range($start, $end, $sort = 'jam_mulai', $order = 'asc')
    {
        $allowed = [
            'id_court'       => 'courts.nama_lapangan',
            'kode_member'    => 'm.kode_member',
            'tanggal_booking'=> 'bookings.tanggal_booking',
            'jam_mulai'      => 'bookings.jam_mulai',
            'jam_selesai'    => 'bookings.jam_selesai',
            'status_pembayaran' => 'bookings.status_pembayaran',
            'status_booking' => 'bookings.status_booking',
            'keterangan'     => 'bookings.keterangan'
        ];
        $sort_field = isset($allowed[$sort]) ? $allowed[$sort] : $allowed['jam_mulai'];
        $order      = strtolower($order) === 'desc' ? 'desc' : 'asc';
        return $this->db->select('bookings.*, m.kode_member, courts.nama_lapangan')
                        ->from($this->table)
                        ->join('member_data m', 'm.user_id = bookings.id_user', 'left')
                        ->join('courts', 'courts.id = bookings.id_court', 'left')
                        ->where('bookings.tanggal_booking >=', $start)
                        ->where('bookings.tanggal_booking <=', $end)
                        ->where('bookings.status_booking !=', 'batal')
                        ->order_by($sort_field, $order)
                        ->get()
                        ->result();
    }

    public function get_pending($sort = 'jam_mulai', $order = 'asc')
    {
        $allowed = [
            'id_court'       => 'courts.nama_lapangan',
            'kode_member'    => 'm.kode_member',
            'tanggal_booking'=> 'bookings.tanggal_booking',
            'jam_mulai'      => 'bookings.jam_mulai',
            'jam_selesai'    => 'bookings.jam_selesai',
            'status_pembayaran' => 'bookings.status_pembayaran',
            'status_booking' => 'bookings.status_booking',
            'keterangan'     => 'bookings.keterangan'
        ];
        $sort_field = isset($allowed[$sort]) ? $allowed[$sort] : $allowed['jam_mulai'];
        $order      = strtolower($order) === 'desc' ? 'desc' : 'asc';
        return $this->db->select('bookings.*, m.kode_member, courts.nama_lapangan')
                        ->from($this->table)
                        ->join('member_data m', 'm.user_id = bookings.id_user', 'left')
                        ->join('courts', 'courts.id = bookings.id_court', 'left')
                        ->where('bookings.status_booking', 'pending')
                        ->order_by($sort_field, $order)
                        ->get()
                        ->result();
    }

    /**
     * Ambil booking suatu lapangan pada tanggal tertentu.
     */
    public function get_by_court_and_date($id_court, $date)
    {
        return $this->db->where('id_court', $id_court)
                        ->where('tanggal_booking', $date)
                        ->where('status_booking !=', 'batal')
                        ->order_by('jam_mulai', 'asc')
                        ->get($this->table)
                        ->result();
    }

    public function insert($data)
    {
        $data['booking_code'] = $this->generate_booking_code();
        if (!isset($data['poin_member'])) {
            $data['poin_member'] = 0;
        }
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Ambil satu booking beserta nama lapangan.
     */
    public function find_with_court($id)
    {
        return $this->db->select('b.*, c.nama_lapangan')
                        ->from($this->table . ' b')
                        ->join('courts c', 'c.id = b.id_court', 'left')
                        ->where('b.id', $id)
                        ->get()
                        ->row();
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
        $this->db->select('bookings.*, m.kode_member');
        $this->db->from($this->table);
        $this->db->join('member_data m', 'm.user_id = bookings.id_user', 'left');
        $this->db->where('bookings.status_booking', 'batal');
        if (!empty($date)) {
            $this->db->where('bookings.tanggal_booking', $date);
        }
        return $this->db->order_by('bookings.tanggal_booking', 'desc')
                        ->get()
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

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
}
