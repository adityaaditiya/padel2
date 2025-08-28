<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk modul booking pelanggan.
 *
 * Menyediakan daftar jadwal, form booking, dan penyimpanan booking baru.
 */
class Booking extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Court_model','Booking_model','Store_model','Member_model']);
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form']);
    }

    /**
     * Tampilkan jadwal ketersediaan lapangan untuk tanggal tertentu.
     */
    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $date = $this->input->get('date');
        if (!$date) {
            $date = date('Y-m-d');
        }
        $sort  = $this->input->get('sort') ?: 'jam_mulai';
        $order = $this->input->get('order') ?: 'asc';
        $data['date']  = $date;
        $data['sort']  = $sort;
        $data['order'] = $order;
        $data['courts']   = $this->Court_model->get_all();
        $data['bookings'] = $this->Booking_model->get_by_date($date, $sort, $order);
        $this->load->view('booking/index', $data);
    }

    /**
     * Form booking baru.
     */
    public function create()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role === 'pelanggan') {
            $user_id = $this->session->userdata('id');
            $member  = $this->Member_model->get_by_id($user_id);
            if (
                !$member ||
                empty($member->alamat) ||
                empty($member->kecamatan) ||
                empty($member->kota) ||
                empty($member->provinsi)
            ) {
                $this->session->set_flashdata('error', 'Lengkapi data member dulu untuk melanjutkan booking.');
                redirect('members/profile');
                return;
            }
        }
        $data['courts'] = $this->Court_model->get_all();
        $data['store']  = $this->Store_model->get_current();
        $this->load->view('booking/create', $data);
    }

    /**
     * Simpan booking baru. Memeriksa bentrok jadwal.
     */
    public function store()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role === 'pelanggan') {
            $user_id = $this->session->userdata('id');
            $member  = $this->Member_model->get_by_id($user_id);
            if (
                !$member ||
                empty($member->alamat) ||
                empty($member->kecamatan) ||
                empty($member->kota) ||
                empty($member->provinsi)
            ) {
                $this->session->set_flashdata('error', 'Lengkapi data member pada menu setting data member untuk melanjutkan booking.');
                redirect('members/profile');
                return;
            }
        }
        $error = $this->Store_model->validate_device_date($this->input->post('device_date'));
        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect('booking/create');
            return;
        }
        $this->form_validation->set_rules('id_court', 'Lapangan', 'required');
        $this->form_validation->set_rules('tanggal_booking', 'Tanggal', 'required');
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
        $this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');

        if ($this->form_validation->run() === TRUE) {
            $id_court = $this->input->post('id_court');
            $date     = $this->input->post('tanggal_booking');
            if (strtotime($date) < strtotime(date('Y-m-d'))) {
                $this->session->set_flashdata('error', 'Tanggal booking tidak boleh sebelum hari ini.');
                redirect('booking/create');
                return;
            }
            $start    = $this->input->post('jam_mulai');
            $end      = $this->input->post('jam_selesai');
            $durasi   = (strtotime($end) - strtotime($start)) / 3600;
            if ($durasi <= 0) {
                $this->session->set_flashdata('error', 'Jam selesai harus lebih besar dari jam mulai.');
                redirect('booking/create');
            }
            // Cek ketersediaan
            if (!$this->Booking_model->is_available($id_court, $date, $start, $end)) {
                $this->session->set_flashdata('error', 'Lapangan sudah terbooking pada jam tersebut.');
                redirect('booking/create');
            }
            $court = $this->Court_model->get_by_id($id_court);
            $total = $court->harga_per_jam * $durasi;
            $data = [
                'id_user'          => $this->session->userdata('id'),
                'id_court'         => $id_court,
                'tanggal_booking'  => $date,
                'jam_mulai'        => $start,
                'jam_selesai'      => $end,
                'durasi'           => $durasi,
                'total_harga'      => $total,
                'status_booking'   => 'pending',
                'status_pembayaran'=> 'belum_bayar'
            ];
        $this->Booking_model->insert($data);
        $this->session->set_flashdata('success', 'Booking berhasil disimpan, silakan lakukan pembayaran.');
        redirect('booking');
        return;
    }
        $this->create();
    }

    /**
     * Tampilkan daftar booking milik user yang sedang login.
     */
    public function my()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $user_id = $this->session->userdata('id');
        $data['bookings'] = $this->Booking_model->get_by_user($user_id);
        $this->load->view('booking/my', $data);
    }

    /**
     * Update status booking (hanya kasir).
     */
    public function update_status($id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role !== 'kasir') {
            redirect('dashboard');
        }
        $status     = $this->input->post('status');
        $keterangan = $this->input->post('keterangan');
        // Izinkan baik istilah bahasa Inggris maupun Indonesia
        $allowed = [
            'confirmed' => 'confirmed',
            'cancelled' => 'batal',
            'completed' => 'selesai',
            'batal'     => 'batal',
            'selesai'   => 'selesai'
        ];
        if (!array_key_exists($status, $allowed)) {
            show_error('Status tidak valid', 400);
        }
        $normalized = $allowed[$status];
        $data       = ['status_booking' => $normalized];
        if ($normalized === 'confirmed') {
            $data['keterangan'] = 'pembayaran sudah di konfirmasi';
        } elseif ($keterangan !== null) {
            $data['keterangan'] = $keterangan;
        }
        $this->Booking_model->update($id, $data);
        $this->session->set_flashdata('success', 'Status booking diperbarui.');
        redirect('booking');
    }

    /**
     * Tampilkan daftar booking yang dibatalkan.
     */
    public function cancelled()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['kasir', 'admin_keuangan', 'owner'])) {
            show_error('Forbidden', 403);
        }
        $date = $this->input->get('date');
        if (!$date) {
            $date = $this->input->get('tanggal');
        }

        $data['date'] = $date;
        $data['bookings'] = !empty($date) ? $this->Booking_model->get_cancelled($date) : [];

        $this->load->view('booking/cancelled', $data);
    }
}
