<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Log_model extends CI_Model
{
  //create log
  public function createLog($data)
  {
    $this->db->insert('log', $data);
    return $this->db->affected_rows();
  }

  public function viewLog()
  {
    return $this->db->get('log')->result_array();
  }
}
