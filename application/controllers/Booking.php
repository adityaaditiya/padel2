<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;

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
     * Tampilkan jadwal ketersediaan lapangan untuk rentang tanggal tertentu.
     */
    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $start  = $this->input->get('start_date');
        $end    = $this->input->get('end_date');
        if (!$start) {
            $start = date('Y-m-d');
        }
        if (!$end) {
            $end = $start;
        }
        $status = $this->input->get('status');
        $sort   = $this->input->get('sort') ?: 'jam_mulai';
        $order  = $this->input->get('order') ?: 'asc';
        $data['start_date'] = $start;
        $data['end_date']   = $end;
        $data['sort']       = $sort;
        $data['order']      = $order;
        $data['status']     = $status;
        $data['courts']     = $this->Court_model->get_all();
        if ($status === 'pending') {
            $data['bookings'] = $this->Booking_model->get_pending($sort, $order);
        } else {
            $data['bookings'] = $this->Booking_model->get_by_date_range($start, $end, $sort, $order);
        }
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

        // Prefill form when coming from dashboard with selected slot
        $data['selected_court'] = $this->input->get('id_court');
        $data['selected_date']  = $this->input->get('date');
        $slot                   = $this->input->get('slot');
        if ($slot) {
            $parts = explode('-', $slot);
            if (count($parts) === 2) {
                $data['selected_start'] = $parts[0];
                $data['selected_end']   = $parts[1];
            }
        }

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
            $maxDate = date('Y-m-d', strtotime('+2 months'));
            if (strtotime($date) > strtotime($maxDate)) {
                $this->session->set_flashdata('error', 'Tanggal booking tidak boleh lebih dari dua bulan dari hari ini.');
                redirect('booking/create');
                return;
            }
            $start  = $this->input->post('jam_mulai');
            $end    = $this->input->post('jam_selesai');
            $durasi = (strtotime($end) - strtotime($start)) / 60; // minutes
            if ($durasi <= 0) {
                $this->session->set_flashdata('error', 'Jam selesai harus lebih besar dari jam mulai.');
                redirect('booking/create');
            }
            // Cek ketersediaan
            if (!$this->Booking_model->is_available($id_court, $date, $start, $end)) {
                $this->session->set_flashdata('error', 'Lapangan sudah terbooking pada jam tersebut.');
                redirect('booking/create');
            }
            $court         = $this->Court_model->get_by_id($id_court);
            $harga_booking = ($court->harga_per_jam / 60) * $durasi;
            $diskon_persen = (float) $this->input->post('diskon_persen');
            $diskon_rupiah = (float) $this->input->post('diskon_rupiah');
            if ($diskon_persen > 0 && $diskon_rupiah <= 0) {
                $diskon_rupiah = $harga_booking * ($diskon_persen / 100);
            } elseif ($diskon_rupiah > 0 && $diskon_persen <= 0) {
                $diskon_persen = $harga_booking > 0 ? ($diskon_rupiah / $harga_booking) * 100 : 0;
            }
            $total   = $harga_booking - $diskon_rupiah;
            if ($total < 0) {
                $total = 0;
            }
            $id_user = $this->session->userdata('id');
            if ($this->session->userdata('role') === 'kasir') {
                $type = $this->input->post('customer_type');
                if ($type === 'member') {
                    $cust = (int) $this->input->post('customer_id');
                    if (!$cust) {
                        $this->session->set_flashdata('error', 'Nomor member tidak valid.');
                        redirect('booking/create');
                        return;
                    }
                    $id_user = $cust;
                }
            }

            $bukti_file = null;
            if ($this->session->userdata('role') === 'pelanggan') {
                if (empty($_FILES['bukti_pembayaran']['name'])) {
                    $this->session->set_flashdata('error', 'Bukti pembayaran wajib diunggah.');
                    redirect('booking/create');
                    return;
                }
                $config = [
                    'upload_path'   => './uploads/payment_proofs/',
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size'      => 2048,
                    'encrypt_name'  => TRUE,
                ];
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('bukti_pembayaran')) {
                    $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                    redirect('booking/create');
                    return;
                }
                $upload_data = $this->upload->data();
                $bukti_file  = $upload_data['file_name'];
            }

            $data = [
                'id_user'          => $id_user,
                'id_court'         => $id_court,
                'tanggal_booking'  => $date,
                'jam_mulai'        => $start,
                'jam_selesai'      => $end,
                'durasi'           => $durasi,
                'harga_booking'    => $harga_booking,
                'diskon'           => $diskon_rupiah,
                'total_harga'      => $total,
                'status_booking'   => 'pending',
                'status_pembayaran'=> 'belum_bayar'
            ];
            if ($bukti_file) {
                $data['bukti_pembayaran'] = $bukti_file;
            }
            $booking_id = $this->Booking_model->insert($data);
            $this->session->set_flashdata('success', 'Booking berhasil disimpan, silakan lakukan pembayaran.');
            if ($this->session->userdata('role') === 'kasir') {
                $this->print_receipt($booking_id);
                return;
            }
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
        if ($role === 'pelanggan') {
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

    public function print_receipt($id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['kasir', 'admin_keuangan', 'owner'])) {
            show_error('Forbidden', 403);
        }

        $booking = $this->Booking_model->find_with_court($id);
        if (!$booking) {
            show_404();
        }
        $member = $this->Member_model->get_by_id($booking->id_user);

        try {
            $profile = CapabilityProfile::load('T82');
        } catch (\Exception $e) {
            $profile = CapabilityProfile::load('default');
        }

        $connector = new WindowsPrintConnector('T82');
        $printer   = new Printer($connector, $profile);
        $printer->setPrintLeftMargin(80);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Padel Store\n");
        $printer->text(date('d-m-Y H:i') . "\n");
        if ($member && !empty($member->kode_member)) {
            $printer->text('Nomor Member: ' . $member->kode_member . "\n");
        } else {
            $printer->text("-Non Member-\n");
        }
        $printer->text(str_repeat('-', 32) . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text('ID Booking : ' . $booking->booking_code . "\n");
        $printer->text('Tanggal    : ' . $booking->tanggal_booking . "\n");
        $printer->text('Lapangan   : ' . $booking->nama_lapangan . "\n");
        $printer->text('Mulai      : ' . $booking->jam_mulai . "\n");
        $printer->text('Selesai    : ' . $booking->jam_selesai . "\n");
        $printer->text('Durasi     : ' . $booking->durasi . " menit\n");
        $printer->text('Harga      : Rp ' . number_format($booking->harga_booking,0,',','.') . "\n");
        $printer->text('Diskon     : Rp ' . number_format($booking->diskon,0,',','.') . "\n");
        $printer->text('Total      : Rp ' . number_format($booking->total_harga,0,',','.') . "\n");
        $printer->feed(2);
        $printer->cut();
        $printer->close();
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
