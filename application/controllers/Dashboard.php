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
    }

    /**
     * Tampilkan dashboard sesuai peran.
     */
    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $role = $this->session->userdata('role');
        $view = '';
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
                $view = 'dashboard/customer';
                break;
        }
        $this->load->view($view);
    }
}
