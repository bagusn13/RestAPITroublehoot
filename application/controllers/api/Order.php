<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Order extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Order_model');
  }

  // get riwayat belanja per user($id_user)
  // 
  public function index_get()
  {
    $account_id   = $this->get('account_id');
    $tracking_key = $this->get('tracking_key');
    if ($account_id != null) {
      $listOrder = $this->Order_model->getOrderHistory($account_id);
      if ($listOrder) {
        $this->response([
          'status'  => true,
          'data'    => $listOrder
        ], REST_Controller::HTTP_OK);
      } else {
        $this->response([
          'status'  => false,
          'data'    => 'order history for this account not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    } else {
      $detailOrder = $this->Order_model->getDetailOrder($tracking_key);
      if ($detailOrder) {
        $this->response([
          'status'  => true,
          'data'    => $detailOrder
        ], REST_Controller::HTTP_OK);
      } else {
        $this->response([
          'status'  => false,
          'data'    => 'order id not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }
  }

  public function index_post()
  {
    // Mengambil value dari form dengan method POST
    $account_id   = $this->post('account_id');
    $tracking_key = $this->post('tracking_key');
    $kerusakan_id = $this->post('kerusakan_id');
    $harga        = $this->post('harga');
    $jumlah       = $this->post('jumlah');
    $total_harga  = $this->post('total_harga');

    $data = [
      'account_id'   => $account_id,
      'tracking_key' => $tracking_key,
      'kerusakan_id' => $kerusakan_id,
      'harga'        => $harga,
      'jumlah'       => $jumlah,
      'total_harga'  => $total_harga,
    ];

    if ($this->Order_model->createOrder($data) > 0) {
      //ok
      $this->response([
        'status'    => true,
        'message'   => 'new order has been created'
      ], REST_Controller::HTTP_CREATED);
    } else {
      $this->response([
        'status'  => false,
        'data'    => 'failed to create new order'
      ], REST_Controller::HTTP_OK);
    }
  }
}
