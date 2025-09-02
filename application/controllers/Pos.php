<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;

/**
 * Controller untuk Point of Sale (kasir) penjualan F&B.
 */
class Pos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Product_model','Sale_model','Sale_detail_model','Payment_model','Store_model','Member_model']);
        $this->load->library('session');
        $this->load->helper(['url']);
    }

    private function authorize()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['kasir','admin_keuangan','owner'])) {
            redirect('dashboard');
        }
    }

    /**
     * Tampilkan produk dan keranjang belanja.
     */
    public function index()
    {
        $this->authorize();
        $kategori = $this->input->get('kategori');
        $keyword  = $this->input->get('q');
        $data['products'] = $this->Product_model->get_filtered($kategori, $keyword);
        $data['categories'] = $this->Product_model->get_categories();
        $data['selected_category'] = $kategori;
        $data['search_query'] = $keyword;
        $data['cart'] = $this->session->userdata('cart') ?: [];
        $data['total'] = 0;
        foreach ($data['cart'] as $item) {
            $data['total'] += $item['harga_jual'] * $item['qty'];
        }
        $data['store'] = $this->Store_model->get_current();
        $data['nota'] = $this->Payment_model->get_next_sale_id();
        $this->load->view('pos/index', $data);
    }

    /**
     * Endpoint AJAX untuk mengambil detail member berdasarkan kode.
     */
    public function member_lookup()
    {
        $this->authorize();
        $kode = $this->input->get('kode');
        $member = $this->Member_model->get_by_kode($kode);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($member));
    }

    /**
     * Endpoint AJAX untuk mengambil daftar produk terfilter.
     */
    public function search()
    {
        $this->authorize();
        $kategori = $this->input->get('kategori');
        $keyword  = $this->input->get('q');
        $products = $this->Product_model->get_filtered($kategori, $keyword);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($products));
    }
    public function transactions()
    {
        $this->authorize();
        $start   = $this->input->get('start');
        $end     = $this->input->get('end');
        $keyword = $this->input->get('q');

        $per_page = (int) $this->input->get('per_page');
        $allowed_per_page = [10, 25, 50, 100];
        if (!in_array($per_page, $allowed_per_page, true)) {
            $per_page = 10;
        }
        $page = max(1, (int) $this->input->get('page'));

        $start_index = ($page - 1) * $per_page;
        if ($start && $end) {
            $total_rows = $this->Sale_model->count_filtered($start, $end, $keyword);
            $sales      = $this->Sale_model->get_paginated($start, $end, $per_page, $start_index, $keyword);
        } else {
            $total_rows = 0;
            $sales = [];
        }

        $page_total = 0;
        foreach ($sales as $sale) {
            $page_total += $sale->total_belanja;
        }

        $data['filter_start'] = $start;
        $data['filter_end']   = $end;
        $data['sales']        = $sales;
        $data['page_total']   = $page_total;
        $data['page']         = $page;
        $data['total_pages']  = (int) ceil($total_rows / $per_page);
        $data['per_page']     = $per_page;
        $data['search_query'] = $keyword;
        $this->load->view('pos/transactions', $data);
    }

    /**
     * Cetak ulang nota untuk transaksi yang sudah ada.
     */
    public function reprint($id)
    {
        $this->authorize();
        if (!is_numeric($id)) {
            redirect('pos/transactions');
            return;
        }
        $sale = $this->Sale_model->get_by_id($id);
        if (!$sale) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan.');
            redirect('pos/transactions');
            return;
        }
        $this->print_receipt($id);
        $this->session->set_flashdata('success', 'Nota berhasil dicetak ulang.');
        redirect('pos/transactions');
    }
    /**
     * Tambah produk ke keranjang.
     */
    public function add($id)
    {
        $this->authorize();
        $product = $this->Product_model->get_by_id($id);
        if (!$product) {
            redirect('pos');
        }
        $qty = (int) $this->input->post('qty');
        if (!$qty) {
            $qty = (int) $this->input->get('qty');
        }
        if ($qty < 1) {
            $qty = 1;
        }
        $cart = $this->session->userdata('cart') ?: [];
        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                'id'         => $product->id,
                'nama_produk'=> $product->nama_produk,
                'harga_jual' => $product->harga_jual,
                'qty'        => $qty
            ];
        }
        $this->session->set_userdata('cart', $cart);
        redirect('pos');
    }
    /**
     * Perbarui jumlah masing-masing item di keranjang.
     */
    public function update_cart()
    {
        $this->authorize();
        if ($this->input->method() !== 'post') {
            redirect('pos');
        }
        $qtys = $this->input->post('qty');
        $cart = $this->session->userdata('cart') ?: [];
        if (is_array($qtys)) {
            foreach ($qtys as $id => $qty) {
                if (isset($cart[$id])) {
                    $qty = (int) $qty;
                    if ($qty > 0) {
                        $cart[$id]['qty'] = $qty;
                    } else {
                        unset($cart[$id]);
                    }
                }
            }
            $this->session->set_userdata('cart', $cart);
        }
        redirect('pos');
    }

    /**
     * Hapus produk dari keranjang.
     */
    public function remove($id)
    {
        $this->authorize();
        $cart = $this->session->userdata('cart') ?: [];
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $this->session->set_userdata('cart', $cart);
        }
        redirect('pos');
    }

    /**
     * Simpan transaksi penjualan dan kosongkan keranjang.
     */
    public function checkout()
    {
        $this->authorize();
        if ($this->input->method() !== 'post') {
            redirect('pos');
        }
        $error = $this->Store_model->validate_device_date($this->input->post('device_date'));
        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect('pos');
            return;
        }
        $customerId = $this->input->post('customer_id');
        if (!is_numeric($customerId)) {
            $customerId = null;
        }
        $cart = $this->session->userdata('cart') ?: [];
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Keranjang kosong.');
            redirect('pos');
            return;
        }
        // Hitung total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga_jual'] * $item['qty'];
        }
        // Ambil jumlah bayar dari input
        $bayar = (float) $this->input->post('bayar');
        if ($bayar < $total) {
            $this->session->set_flashdata('error', 'Jumlah bayar kurang.');
            redirect('pos');
            return;
        }
        // Buat nomor nota sederhana
        $nomor_nota = 'INV-' . time();
        $saleData = [
            'id_kasir'      => $this->session->userdata('id'),
            'nomor_nota'    => $nomor_nota,
            'total_belanja' => $total,
            'customer_id'   => $customerId
        ];
        $sale_id = $this->Sale_model->insert($saleData);
        // Simpan detail dan update stok
        foreach ($cart as $item) {
            $detail = [
                'id_sale'   => $sale_id,
                'id_product'=> $item['id'],
                'jumlah'    => $item['qty'],
                'subtotal'  => $item['harga_jual'] * $item['qty']
            ];
            $this->Sale_detail_model->insert($detail);
            // Kurangi stok
            $this->Product_model->decrease_stock($item['id'], $item['qty']);
        }
        // Buat pembayaran (tunai default)
        $payment = [
            'id_sale'        => $sale_id,
            'jumlah_bayar'   => $bayar,
            'metode_pembayaran' => 'tunai',
            'id_kasir'       => $this->session->userdata('id')
        ];
        $this->Payment_model->insert($payment);
        $this->print_receipt($sale_id);
        // Kosongkan keranjang
        $this->session->unset_userdata('cart');
        $this->session->set_flashdata('success', 'Transaksi berhasil disimpan.');
        redirect('pos');
    }

    private function print_receipt($sale_id)
    {
        $sale = $this->Sale_model->get_by_id($sale_id);
        $details = $this->Sale_detail_model->get_with_product($sale_id);
        $payments = $this->Payment_model->get_by_sale($sale_id);
        try {
            $profile = CapabilityProfile::load('T82');
        } catch (Exception $e) {
            $profile = CapabilityProfile::load('default');
        }
        $connector = new WindowsPrintConnector('T82');
        $printer = new Printer($connector, $profile);
        // Geser margin ke kanan agar cetakan sedikit lebih ke kanan
        $printer->setPrintLeftMargin(80);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Padel Store\n");
        $printer->text(date("d-m-Y H:i") . "\n");
        $printer->text("Nota: {$sale->nomor_nota}\n");
        $member = null;
        if (!empty($sale->customer_id)) {
            $member = $this->Member_model->get_by_id($sale->customer_id);
        }
        if ($member) {
            $printer->text("Nomor Member: {$member->kode_member}\n");
            $printer->text("Nama: {$member->nama_lengkap}\n");
        } else {
            $printer->text("-Non Member-\n");
        }
        $printer->text(str_repeat('-', 32) . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        foreach ($details as $d) {
            $line = sprintf("%s\n%dx %s\n", $d->nama_produk, $d->jumlah, number_format($d->harga_jual,0,',','.'));
            $printer->text($line);
        }
        $printer->text(str_repeat('-', 37) . "\n");
        $printer->text('Total: Rp ' . number_format($sale->total_belanja,0,',','.') . "\n");
        if (!empty($payments)) {
            $bayar = $payments[0]->jumlah_bayar;
            $kembali = $bayar - $sale->total_belanja;
            $printer->text('Bayar: Rp ' . number_format($bayar,0,',','.') . "\n");
            $printer->text('Kembali: Rp ' . number_format($kembali,0,',','.') . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text(str_repeat('-', 32) . "\n");
            $printer->text("Kritik & Saran\n");
            $printer->text("WA 0877-3383-6235\n");
        }
        $printer->feed(2);
        $printer->cut();
        $printer->close();
    }

//     public function print_receipt()
// {
//     // pastikan autoload composer sudah jalan (lihat langkah 2)
//     $profile   = CapabilityProfile::load("simple"); // atau "default" / "SP2000"
//     $connector = new WindowsPrintConnector("T82"); // nama printer di Windows
//     $printer   = new Printer($connector, $profile);

//     $printer->setJustification(Printer::JUSTIFY_CENTER);
//     $printer->text("Nota PadelPro\n");
//     $printer->feed();

//     // ... isi struk kamu di sini ...

//     $printer->cut();
//     $printer->close();
// }

}
