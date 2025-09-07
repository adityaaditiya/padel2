<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk mencatat penggunaan poin selain penukaran hadiah.
 */
class Point_usage_model extends CI_Model
{
    protected $table = 'point_usages';

    /**
     * Simpan log penggunaan poin.
     */
    public function log($user_id, $description, $point_awal, $point_used, $point_akhir)
    {
        $this->db->insert($this->table, [
            'user_id'    => $user_id,
            'description'=> $description,
            'point_awal' => (int) $point_awal,
            'point_used' => (int) $point_used,
            'point_akhir'=> (int) $point_akhir,
        ]);
    }
}
?>
