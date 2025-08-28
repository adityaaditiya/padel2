<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel users.
 */
class User_model extends CI_Model
{
    protected $table = 'users';

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        $user_id = $this->db->insert_id();
        if (isset($data['role']) && $data['role'] === 'pelanggan') {
            $kode_member = str_pad($user_id, 10, '0', STR_PAD_LEFT);
            $this->db->insert('member_data', [
                'user_id'   => $user_id,
                'kode_member' => $kode_member,
                'alamat'    => '',
                'kecamatan' => '',
                'kota'      => '',
                'provinsi'  => ''
            ]);
        }
        return $user_id;
    }

    /**
     * Proses login, mengembalikan objek user jika berhasil.
     */
    public function login($email, $password)
    {
        $user = $this->db->get_where($this->table, ['email' => $email])->row();
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return NULL;
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
