<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Points extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url','form']);
        $this->load->model('Point_rule_model');

    }

    private function authorize()
    {
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'owner') {
            show_error('Forbidden', 403);
        }
    }

    public function index()
    {
        $this->authorize();
        if ($this->input->method() === 'post') {
            $product = (int) $this->input->post('product_rate');
            $booking = (int) $this->input->post('booking_rate');
            if ($product > 0 && $booking > 0) {
                $this->Point_rule_model->update($product, $booking);
                $this->session->set_flashdata('success', 'Ketentuan poin diperbarui.');
            }
            redirect('points');
            return;
        }
        $data['rules'] = $this->Point_rule_model->get();
        $this->load->view('points/rules', $data);
    }
}
?>
