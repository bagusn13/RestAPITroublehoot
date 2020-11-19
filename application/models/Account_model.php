<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Account_model extends CI_Model
{
  public function getAccount($accounts_id = null)
  {
    if ($accounts_id == null) {
      return $this->db->get('accounts')->result_array();
    } else {
      return $this->db->get_where('accounts', ['accounts_id' => $accounts_id])->result_array();
    }
  }

  public function deleteAccount($accounts_id)
  {
    $this->db->delete('accounts', ['accounts_id' => $accounts_id]);
    return $this->db->affected_rows();
  }

  public function createAccount($data)
  {
    $email = $data['email'];
    $q = $this->db->get_where('accounts', ['email' => $email])->result();
    //var_dump($q);
    if ($q) {
      return false;
    } else {
      $this->db->insert('accounts', $data);
      return true;
    }
  }

  public function updateAccount($data, $accounts_id)
  {
    $this->db->update('accounts', $data, ['accounts_id' => $accounts_id]);
    $this->db->select('*');
    $this->db->from('accounts');
    $this->db->where(array(
      'accounts_id'    => $accounts_id,
    ));
    $q = $this->db->get();
    return $q->row();
  }

  public function login($email, $password)
  {
    $this->db->select('*');
    $this->db->from('accounts');
    $this->db->where(array(
      'email'    => $email,
      'password' => base64_encode($password),
    ));
    $q = $this->db->get();
    return $q->row();
  }

  public function loginGoogle($email, $oauth_id)
  {
    $this->db->select('*');
    $this->db->from('accounts');
    $this->db->where(array(
      'email'    => $email,
      'oauth_id' => $oauth_id,
    ));
    $q = $this->db->get();
    return $q->row();
  }
}
