<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk data member tambahan.
 */
class Member_model extends CI_Model
{
    protected $table = 'member_data';

    /**
     * Ambil data member beserta info user pelanggan dengan opsi pagination dan pencarian.
     */
    public function get_all($limit = null, $offset = null, $keyword = null)
    {
        $this->db->select('u.id, u.nama_lengkap, u.email, u.no_telepon, m.kode_member, m.nomor_ktp, m.alamat, m.kecamatan, m.kota, m.provinsi, m.poin');
        $this->db->from('users u');
        $this->db->join('member_data m', 'm.user_id = u.id', 'left');
        $this->db->where('u.role', 'pelanggan');
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('u.nama_lengkap', $keyword);
            $this->db->or_like('m.kode_member', $keyword);
            $this->db->or_like('m.nomor_ktp', $keyword);
            $this->db->or_like('u.no_telepon', $keyword);
            $this->db->or_like('m.alamat', $keyword);
            $this->db->or_like('m.kecamatan', $keyword);
            $this->db->or_like('m.kota', $keyword);
            $this->db->or_like('m.provinsi', $keyword);
            $this->db->group_end();
        }
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result();
    }


    public function count_all($keyword = null)
    {
        $this->db->from('users u');
        $this->db->join('member_data m', 'm.user_id = u.id', 'left');
        $this->db->where('u.role', 'pelanggan');
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('u.nama_lengkap', $keyword);
            $this->db->or_like('m.kode_member', $keyword);
            $this->db->or_like('m.nomor_ktp', $keyword);
            $this->db->or_like('u.no_telepon', $keyword);
            $this->db->or_like('m.alamat', $keyword);
            $this->db->or_like('m.kecamatan', $keyword);
            $this->db->or_like('m.kota', $keyword);
            $this->db->or_like('m.provinsi', $keyword);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    /**
     * Ambil satu member berdasarkan kode member.
     */
    public function get_by_kode($kode)
    {
        $this->db->select('u.id, u.nama_lengkap, u.no_telepon, m.nomor_ktp, m.alamat, m.poin');
        $this->db->from('users u');
        $this->db->join('member_data m', 'm.user_id = u.id', 'left');
        $this->db->where(['m.kode_member' => $kode, 'u.role' => 'pelanggan']);
        return $this->db->get()->row();
    }

    /**
     * Ambil satu member berdasarkan ID user.
     */
    public function get_by_id($id)
    {
        $this->db->select('u.id, u.nama_lengkap, u.email, u.no_telepon, u.password, m.kode_member, m.nomor_ktp, m.alamat, m.kecamatan, m.kota, m.provinsi, m.poin');
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
        if (!isset($member_data['poin'])) {
            $member_data['poin'] = 0;
        }
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
            if (!isset($member_data['poin'])) {
                $member_data['poin'] = 0;
            }
            $this->db->insert($this->table, $member_data);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function add_points($user_id, $points)
    {
        if ($points <= 0) {
            return;
        }
        $this->db->set('poin', 'poin + '.(int)$points, false)
                 ->where('user_id', $user_id)
                 ->update($this->table);
    }

    public function deduct_points($user_id, $points)
    {
        if ($points <= 0) {
            return;
        }
        $this->db->set('poin', 'GREATEST(poin - '.(int)$points.',0)', false)
                 ->where('user_id', $user_id)
                 ->update($this->table);
    }

    public function ktp_exists($nomor_ktp, $exclude_user_id = NULL)
    {
        $this->db->where('nomor_ktp', $nomor_ktp);
        if ($exclude_user_id !== NULL) {
            $this->db->where('user_id !=', $exclude_user_id);
        }
        return $this->db->get($this->table)->num_rows() > 0;
    }
}
?>
