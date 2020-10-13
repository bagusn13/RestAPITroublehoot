<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Header_order_model extends CI_Model
{

  //Ngambil data header order sesuai user
  public function getHeader_Order($account_id)
  {
    $this->db->select('header_order.*,
                            SUM(orders.jumlah) AS total_item');
    $this->db->from('header_order');
    $this->db->where('header_order.account_id', $account_id);
    // join
    $this->db->join('orders', 'orders.tracking_key = header_order.tracking_key', 'left');
    // end join
    $this->db->group_by('header_order.header_order_id');
    $this->db->order_by('header_order_id', 'asc');
    $q = $this->db->get();
    return $q->result();
  }

  //Ngambil detail order
  public function getDetailOrder($tracking_key)
  {
    $this->db->select('header_order.*,
                      SUM(orders.jumlah) AS total_item');
    $this->db->from('header_order');
    //join
    $this->db->join('orders', 'orders.tracking_key = header_order.tracking_key', 'left');
    $this->db->join('accounts', 'accounts.accounts_id = header_order.account_id', 'left');
    //end join
    $this->db->group_by('header_order.header_order_id');
    $this->db->where('orders.tracking_key', $tracking_key);
    $this->db->order_by('header_order_id', 'asc');
    $q = $this->db->get();
    return $q->row();
  }

  //Tambah Order
  public function createHeaderOrder($data)
  {
    $this->db->insert('header_order', $data);
    return $this->db->affected_rows();
  }

  //Edit Order
  public function EditOrder($data, $header_order_id)
  {
    $this->db->update('header_order', $data, ['header_order_id' => $header_order_id]);
    return $this->db->affected_rows();
  }

  //delete Order
  public function deleteOrder($header_order_id)
  {
    $this->db->delete('header_order', ['header_order_id' => $header_order_id]);
    return $this->db->affected_rows();
  }
}