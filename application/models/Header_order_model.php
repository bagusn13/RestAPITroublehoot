<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Header_order_model extends CI_Model
{

  //Ngambil data header order sesuai user
  public function getHeader_Order($account_id)
  {
    $this->db->select('header_order.*,
                      tracking.status_tracking AS status_tracking,
                      laptop.merk AS merk,
                      status_payment.payment_status,
                      teknisi.nama_teknisi As teknisi,
                      SUM(orders.jumlah) AS total_item');
    $this->db->from('header_order');
    $this->db->where('header_order.account_id', $account_id);
    // join
    $this->db->join('orders', 'orders.tracking_key = header_order.tracking_key', 'left');
    $this->db->join('tracking', 'tracking.tracking_id = header_order.tracking_id', 'left');
    $this->db->join('laptop', 'laptop.laptop_id = header_order.merk_laptop', 'left');
    $this->db->join('status_payment', 'status_payment.payment_id = header_order.status_payment', 'left');
    $this->db->join('teknisi', 'teknisi.teknisi_id = header_order.teknisi', 'left');
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
  public function EditHeaderOrder($data, $tracking_key)
  {
    $this->db->update('header_order', $data, ['tracking_key ' => $tracking_key]);
    return $this->db->affected_rows();
  }

  //delete Order
  public function deleteOrder($header_order_id)
  {
    $this->db->delete('header_order', ['header_order_id' => $header_order_id]);
    return $this->db->affected_rows();
  }
}
