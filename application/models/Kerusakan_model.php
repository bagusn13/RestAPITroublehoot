<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kerusakan_model extends CI_Model
{
  public function getKerusakan($kerusakan_id = null)
  {
    if ($kerusakan_id == null) {
      return $this->db->get('kerusakan')->result_array();
    } else {
      return $this->db->get_where('kerusakan', ['kerusakan_id' => $kerusakan_id])->result_array();
    }
  }

  public function getBiaya($kerusakan_id)
  {
    $this->db->select('biaya');
    $this->db->from('kerusakan');
    $this->db->where('kerusakan_id', $kerusakan_id);
    $q = $this->db->get();
    return $q->row();
  }
}
