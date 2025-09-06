<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reward_product_model extends CI_Model
{
    protected $table = 'reward_products';
    protected $log_table = 'reward_redemptions';

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete($this->table);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
    }

    public function reduce_stock($id, $qty = 1)
    {
        $this->db->set('stok', 'stok - ' . (int)$qty, false)
                 ->where('id', $id)
                 ->where('stok >=', $qty)
                 ->update($this->table);
    }

    public function log_redemption($user_id, $reward_id, $point_awal, $point_akhir)
    {
        $this->db->insert($this->log_table, [
            'user_id'     => $user_id,
            'reward_id'   => $reward_id,
            'point_awal'  => (int) $point_awal,
            'point_akhir' => (int) $point_akhir,
        ]);
    }
}
?>
