<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk data member tambahan.
 */
class Member_model extends CI_Model
{
    protected $table = 'member_data';

    /**
     * Ambil semua data member beserta info user pelanggan.
     */
    public function get_all()
    {
        $this->db->select('u.id, u.nama_lengkap, u.email, u.no_telepon, m.kode_member, m.alamat, m.kecamatan, m.kota, m.provinsi');
        $this->db->from('users u');
        $this->db->join('member_data m', 'm.user_id = u.id', 'left');
        $this->db->where('u.role', 'pelanggan');
        return $this->db->get()->result();
    }

    /**
     * Cari member berdasarkan keyword sederhana.
     */
    public function search($keyword)
    {
        $this->db->select('u.id, u.nama_lengkap, u.no_telepon, m.kode_member');
        $this->db->from('users u');
        $this->db->join('member_data m', 'm.user_id = u.id', 'left');
        $this->db->where('u.role', 'pelanggan');
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('u.nama_lengkap', $keyword);
            $this->db->or_like('m.kode_member', $keyword);
            $this->db->or_like('u.no_telepon', $keyword);
            $this->db->group_end();
        }
        return $this->db->get()->result();
    }

    /**
     * Ambil satu member berdasarkan ID user.
     */
    public function get_by_id($id)
    {
        $this->db->select('u.id, u.nama_lengkap, u.email, u.no_telepon, u.password, m.kode_member, m.alamat, m.kecamatan, m.kota, m.provinsi');
        $this->db->from('users u');
        $this->db->join('member_data m', 'm.user_id = u.id', 'left');
        $this->db->where(['u.id' => $id, 'u.role' => 'pelanggan']);
        return $this->db->get()->row();
    }

    /**
     * Insert user dan data member.
     */
    public function insert($user_data, $member_data)
    {
        $this->db->trans_start();
        $this->db->insert('users', $user_data);
        $member_data['user_id'] = $this->db->insert_id();
        unset($member_data['kode_member']);
        $member_data['kode_member'] = str_pad($member_data['user_id'], 10, '0', STR_PAD_LEFT);
        $this->db->insert($this->table, $member_data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Update user dan data member.
     */
    public function update($id, $user_data, $member_data)
    {
        $this->db->trans_start();
        $this->db->where('id', $id)->update('users', $user_data);
        unset($member_data['kode_member']);
        $exists = $this->db->get_where($this->table, ['user_id' => $id])->row();
        if ($exists) {
            $this->db->where('user_id', $id)->update($this->table, $member_data);
        } else {
            $member_data['user_id'] = $id;
            $member_data['kode_member'] = str_pad($id, 10, '0', STR_PAD_LEFT);
            $this->db->insert($this->table, $member_data);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
?>
