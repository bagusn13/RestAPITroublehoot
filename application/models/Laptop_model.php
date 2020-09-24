<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Laptop_model extends CI_Model
{
  public function getLaptop($laptop_id = null)
  {
    if ($laptop_id == null) {
      return $this->db->get('laptop')->result_array();
    } else {
      return $this->db->get_where('laptop', ['laptop_id' => $laptop_id])->result_array();
    }
  }
}
