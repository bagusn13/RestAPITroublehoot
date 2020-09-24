<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tracking_model extends CI_Model
{
  public function getStatusTracking($tracking_id = null)
  {
    if ($tracking_id == null) {
      return $this->db->get('tracking')->result_array();
    } else {
      return $this->db->get_where('tracking', ['tracking_id' => $tracking_id])->result_array();
    }
  }
}
