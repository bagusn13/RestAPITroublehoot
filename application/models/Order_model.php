<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{

  //Ngambil data order sesuai user
  public function getOrderHistory($account_id = null)
  {
    if ($account_id == null) {
      return $this->db->get('orders')->result_array();
    } else {
      return $this->db->get_where('orders', ['account_id' => $account_id])->result_array();
    }
  }

  //Ngambil detail order
  public function getDetailOrder($tracking_key)
  {
    $this->db->select('orders.*,
    kerusakan.nama_kerusakan,
    kerusakan.jenis');

    $this->db->from('orders');
    //join dengan layanan
    $this->db->join('kerusakan', 'kerusakan.kerusakan_id = orders.kerusakan_id', 'left');
    //end join
    $this->db->where('tracking_key', $tracking_key);
    $this->db->order_by('order_id', 'asc');
    $q = $this->db->get();
    return $q->result();
  }

  //Ngambil detail order
  public function getListKerusakan($tracking_key)
  {
    $this->db->select('orders.*,
    kerusakan.nama_kerusakan,
    kerusakan.jenis');

    $this->db->from('orders');
    //join dengan layanan
    $this->db->join('kerusakan', 'kerusakan.kerusakan_id = orders.kerusakan_id', 'left');
    //end join
    $this->db->where('tracking_key', $tracking_key);
    $this->db->order_by('order_id', 'asc');
    $q = $this->db->get();
    return $q->row();
  }

  //Tambah Order
  public function createOrder($data)
  {
    $this->db->insert('orders', $data);
    return $this->db->affected_rows();
  }

  //Edit Order
  public function EditOrder($data, $order_id)
  {
    $this->db->update('orders', $data, ['order_id' => $order_id]);
    return $this->db->affected_rows();
  }

  //delete Order
  public function deleteOrder($order_id)
  {
    $this->db->delete('orders', ['order_id' => $order_id]);
    return $this->db->affected_rows();
  }
}
