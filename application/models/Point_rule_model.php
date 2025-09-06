<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Point_rule_model extends CI_Model
{
    protected $table = 'point_rules';

    public function get()
    {
        return $this->db->get($this->table)->row();
    }

    public function update($product_rate, $booking_rate)
    {
        $data = [
            'product_rate' => (int)$product_rate,
            'booking_rate' => (int)$booking_rate
        ];
        $exists = $this->db->get($this->table)->row();
        if ($exists) {
            $this->db->update($this->table, $data, ['id' => $exists->id]);
        } else {
            $this->db->insert($this->table, $data);
        }
    }
}
?>
