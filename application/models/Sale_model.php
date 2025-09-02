<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel sales (penjualan F&B).
 */
class Sale_model extends CI_Model
{
    protected $table = 'sales';

    public function insert($data)
    {
        $insertData = [
            'id_kasir'      => $data['id_kasir'],
            'customer_id'   => isset($data['customer_id']) ? $data['customer_id'] : null,
            'nomor_nota'    => $data['nomor_nota'],
            'total_belanja' => $data['total_belanja']
        ];

        $this->db->insert($this->table, $insertData);
        return $this->db->insert_id();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_all($start_date = null, $end_date = null)
    {
        $this->db->select('s.*, u.nama_lengkap AS customer_name');
        $this->db->from($this->table . ' s');
        $this->db->join('users u', 'u.id = s.customer_id', 'left');
        if ($start_date) {
            $this->db->where('DATE(s.tanggal_transaksi) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(s.tanggal_transaksi) <=', $end_date);
        }
        $this->db->order_by('s.tanggal_transaksi', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Hitung total baris untuk filter tertentu.
     */
    public function count_filtered($start_date = null, $end_date = null, $keyword = null)
    {
        $this->db->from($this->table . ' s');
        $this->db->join('users u', 'u.id = s.customer_id', 'left');
        if ($start_date) {
            $this->db->where('DATE(s.tanggal_transaksi) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(s.tanggal_transaksi) <=', $end_date);
        }
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('s.nomor_nota', $keyword);
            $this->db->or_like('u.nama_lengkap', $keyword);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    /**
     * Ambil data dengan batasan (pagination) untuk mencegah load seluruh dataset.
     */
    public function get_paginated($start_date = null, $end_date = null, $limit = 10, $offset = 0, $keyword = null)
    {
        $this->db->select('s.*, u.nama_lengkap AS customer_name');
        $this->db->from($this->table . ' s');
        $this->db->join('users u', 'u.id = s.customer_id', 'left');
        if ($start_date) {
            $this->db->where('DATE(s.tanggal_transaksi) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(s.tanggal_transaksi) <=', $end_date);
        }
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('s.nomor_nota', $keyword);
            $this->db->or_like('u.nama_lengkap', $keyword);
            $this->db->group_end();
        }
        $this->db->order_by('s.tanggal_transaksi', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }
}
