<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard controller menampilkan ringkasan untuk masing-masing peran.
 *
 * Halaman yang ditampilkan tergantung peran pengguna yang login.
 */
class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url']);
        $this->load->model(['Court_model','Booking_model']);

    }

    /**
     * Tampilkan dashboard sesuai peran.
     */
    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $role  = $this->session->userdata('role');
        $data  = [];
        $view  = '';
        switch ($role) {
            case 'kasir':
                $view = 'dashboard/kasir';
                break;
            case 'admin_keuangan':
                $view = 'dashboard/finance';
                break;
            case 'owner':
                $view = 'dashboard/owner';
                break;
            default:
                $view           = 'dashboard/customer';
                $today          = date('Y-m-d');
                $courts         = $this->Court_model->get_all();
                $start_hour     = 8;
                $end_hour       = 23;
                foreach ($courts as $court) {
                    $bookings = $this->Booking_model->get_by_court_and_date($court->id, $today);
                    $available = [];
                    for ($h = $start_hour; $h < $end_hour; $h++) {
                        $slot_start = sprintf('%02d:00:00', $h);
                        $slot_end   = sprintf('%02d:00:00', $h + 1);
                        $occupied   = false;
                        foreach ($bookings as $b) {
                            if ($slot_start < $b->jam_selesai && $slot_end > $b->jam_mulai) {
                                $occupied = true;
                                break;
                            }
                        }
                        if (!$occupied) {
                            $available[] = sprintf('%s - %s', substr($slot_start, 0, 5), substr($slot_end, 0, 5));
                        }
                    }
                    $court->available_slots = $available;
                }
                $data['courts'] = $courts;

                break;
        }
        $this->load->view($view, $data);
    }
}
