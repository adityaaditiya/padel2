<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel products.
 */
class Product_model extends CI_Model
{
    protected $table = 'products';
    /**
     * Daftar kategori yang digunakan di seluruh aplikasi.
     *
     * Menggunakan satu sumber kebenaran agar halaman lain
     * (seperti POS) dapat menampilkan semua kategori meski
     * belum ada produk di dalamnya.
     */
    public $categories = ['makanan','snack','cofee','non cofee','tea','perlengkapan padel'];

    /**
     * Ambil semua kategori yang diizinkan.
     */
    public function get_categories()
    {
        return $this->categories;
    }

    public function get_all($start_date = null, $end_date = null, $limit = null, $offset = null, $keyword = null)
    {
        if ($start_date) {
            $this->db->where('DATE(created_at) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(created_at) <=', $end_date);
        }
        if ($keyword) {
            $this->db->like('nama_produk', $keyword);
        }
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get($this->table)->result();
    }

    public function count_all($start_date = null, $end_date = null, $keyword = null)
    {
        if ($start_date) {
            $this->db->where('DATE(created_at) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(created_at) <=', $end_date);
        }
        if ($keyword) {
            $this->db->like('nama_produk', $keyword);
        }
        return $this->db->count_all_results($this->table);
    }

    /**
     * Ambil produk dengan filter kategori dan pencarian nama.
     */
    public function get_filtered($kategori = null, $keyword = null)
    {
        if ($kategori) {
            $this->db->where('kategori', $kategori);
        }
        if ($keyword) {
            $this->db->like('nama_produk', $keyword);
        }
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     * Kurangi stok produk setelah penjualan.
     */
    public function decrease_stock($id, $qty)
    {
        $product = $this->get_by_id($id);
        if ($product) {
            $newStock = max(0, $product->stok - $qty);
            $this->db->where('id', $id)->update($this->table, ['stok' => $newStock]);
        }
    }
}
