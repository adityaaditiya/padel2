<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model laporan keuangan dan ringkasan bisnis.
 */
class Report_model extends CI_Model
{
    /**
     * Mengambil ringkasan pendapatan booking dan penjualan F&B pada rentang tanggal.
     *
     * @param string $start Tanggal awal (YYYY-MM-DD)
     * @param string $end   Tanggal akhir (YYYY-MM-DD)
     * @return array        Associative array dengan total_booking dan total_sales
     */
    public function get_financial_summary($start, $end)
    {
        // total booking
        $this->db->select_sum('total_harga', 'total_booking');
        $this->db->where('tanggal_booking >=', $start);
        $this->db->where('tanggal_booking <=', $end);
        $booking = $this->db->get('bookings')->row()->total_booking ?: 0;

        // total sales
        $this->db->select_sum('total_belanja', 'total_sales');
        $this->db->where('tanggal_transaksi >=', $start);
        $this->db->where('tanggal_transaksi <=', $end . ' 23:59:59');
        $sales = $this->db->get('sales')->row()->total_sales ?: 0;

        return [
            'total_booking' => $booking,
            'total_sales'   => $sales,
            'grand_total'   => $booking + $sales
        ];
    }

    /**
     * Mengambil detail pemasukan dan pengeluaran berdasarkan kategori.
     *
     * @param string $start    Tanggal awal (YYYY-MM-DD)
     * @param string $end      Tanggal akhir (YYYY-MM-DD)
     * @param string $category booking|batal|product|cash_in|cash_out|semua
     * @return array           Detail transaksi dan total uang masuk/keluar
     */
    public function get_financial_report($start, $end, $category = 'booking')
    {
        $details = [];

        if ($category === 'semua') {
            $categories = ['booking', 'product', 'cash_in', 'cash_out'];
            foreach ($categories as $cat) {
                $res = $this->get_financial_report($start, $end, $cat);
                $details = array_merge($details, $res['details']);
            }

            usort($details, function ($a, $b) {
                return strcmp($a['tanggal'], $b['tanggal']);
            });

            $total_masuk  = array_sum(array_column($details, 'uang_masuk'));
            $total_keluar = array_sum(array_column($details, 'uang_keluar'));

            return [
                'details'      => $details,
                'total_masuk'  => $total_masuk,
                'total_keluar' => $total_keluar,
                'saldo'        => $total_masuk - $total_keluar,
            ];
        }
        if ($category === 'booking') {
            $this->db->select('id, tanggal_booking, total_harga');
            $this->db->from('bookings');
            $this->db->where('tanggal_booking >=', $start);
            $this->db->where('tanggal_booking <=', $end);
            $this->db->where_in('status_booking', ['confirmed', 'selesai']);
            $rows = $this->db->get()->result();
            foreach ($rows as $b) {
                $details[] = [
                    'tanggal'     => $b->tanggal_booking,
                    'keterangan'  => 'Booking #' . $b->id,
                    'uang_masuk'  => (float) $b->total_harga,
                    'uang_keluar' => 0,
                ];
            }
        } elseif ($category === 'batal') {
            $this->db->select('id, tanggal_booking, total_harga');
            $this->db->from('bookings');
            $this->db->where('tanggal_booking >=', $start);
            $this->db->where('tanggal_booking <=', $end);
            $this->db->where('status_booking', 'batal');
            $rows = $this->db->get()->result();
            foreach ($rows as $b) {
                $details[] = [
                    'tanggal'     => $b->tanggal_booking,
                    'keterangan'  => 'Booking batal #' . $b->id,
                    'uang_masuk'  => 0,
                    'uang_keluar' => (float) $b->total_harga,
                ];
            }
        } elseif ($category === 'product') {
            $this->db->select('id, total_belanja, tanggal_transaksi');
            $this->db->from('sales');
            $this->db->where('tanggal_transaksi >=', $start);
            $this->db->where('tanggal_transaksi <=', $end . ' 23:59:59');
            $rows = $this->db->get()->result();
            foreach ($rows as $s) {
                $details[] = [
                    'tanggal'     => date('Y-m-d', strtotime($s->tanggal_transaksi)),
                    'keterangan'  => 'Penjualan #' . $s->id,
                    'uang_masuk'  => (float) $s->total_belanja,
                    'uang_keluar' => 0,
                ];
            }
        } elseif ($category === 'cash_in') {
            $this->db->select('tanggal, amount, note, category');
            $this->db->from('cash_transactions');
            $this->db->where('tanggal >=', $start . ' 00:00:00');
            $this->db->where('tanggal <=', $end . ' 23:59:59');
            $this->db->where('type', 'in');
            $rows = $this->db->get()->result();
            foreach ($rows as $c) {
                $details[] = [
                    'tanggal'     => date('Y-m-d', strtotime($c->tanggal)),
                    'keterangan'  => $c->note ?: $c->category,
                    'uang_masuk'  => (float) $c->amount,
                    'uang_keluar' => 0,
                ];
            }
        } elseif ($category === 'cash_out') {
            $this->db->select('tanggal, amount, note, category');
            $this->db->from('cash_transactions');
            $this->db->where('tanggal >=', $start . ' 00:00:00');
            $this->db->where('tanggal <=', $end . ' 23:59:59');
            $this->db->where('type', 'out');
            $rows = $this->db->get()->result();
            foreach ($rows as $c) {
                $details[] = [
                    'tanggal'     => date('Y-m-d', strtotime($c->tanggal)),
                    'keterangan'  => $c->note ?: $c->category,
                    'uang_masuk'  => 0,
                    'uang_keluar' => (float) $c->amount,
                ];
            }
        }

        // Urutkan berdasarkan tanggal
        usort($details, function ($a, $b) {
            return strcmp($a['tanggal'], $b['tanggal']);
        });

        $total_masuk  = array_sum(array_column($details, 'uang_masuk'));
        $total_keluar = array_sum(array_column($details, 'uang_keluar'));

        return [
            'details'      => $details,
            'total_masuk'  => $total_masuk,
            'total_keluar' => $total_keluar,
            'saldo'        => $total_masuk - $total_keluar,
        ];
    }

    /**
     * Ringkasan bisnis untuk owner: jumlah booking, jumlah pelanggan, dan produk terlaris.
     */
    public function get_business_summary($start, $end)
    {
        // Jumlah booking
        $this->db->where('tanggal_booking >=', $start);
        $this->db->where('tanggal_booking <=', $end);
        $total_bookings = $this->db->count_all_results('bookings');

        // Jumlah pelanggan unik
        $this->db->select('id_user');
        $this->db->where('tanggal_booking >=', $start);
        $this->db->where('tanggal_booking <=', $end);
        $this->db->group_by('id_user');
        $customers = $this->db->get('bookings')->num_rows();

        // Produk terlaris (banyak terjual)
        $this->db->select('products.nama_produk, SUM(sale_details.jumlah) as qty');
        $this->db->from('sale_details');
        $this->db->join('products', 'products.id = sale_details.id_product');
        $this->db->join('sales', 'sales.id = sale_details.id_sale');
        $this->db->where('sales.tanggal_transaksi >=', $start);
        $this->db->where('sales.tanggal_transaksi <=', $end . ' 23:59:59');
        $this->db->group_by('sale_details.id_product');
        $this->db->order_by('qty', 'DESC');
        $this->db->limit(5);
        $best_products = $this->db->get()->result();

        return [
            'total_bookings'   => $total_bookings,
            'total_customers'  => $customers,
            'best_products'    => $best_products
        ];
    }
}
