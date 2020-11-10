<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Headerorder extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Header_order_model');
  }

  // get riwayat belanja per user($id_user)

  public function index_get()
  {
    $account_id = $this->get('account_id');
    $tracking_key = $this->get('tracking_key');
    if ($account_id != null) {
      $listOrder = $this->Header_order_model->getHeader_Order($account_id);
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
      $detailOrder = $this->Header_order_model->getDetailOrder($tracking_key);
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
    $account_id          = $this->post('account_id');
    $nama                = $this->post('nama');
    $email               = $this->post('email');
    $merk_laptop         = $this->post('merk_laptop');
    $keterangan          = $this->post('keterangan');
    $nomor_hp            = $this->post('nomor_hp');
    $tanggal_pengambilan = $this->post('tanggal_pengambilan');
    $jam_pengambilan     = $this->post('jam_pengambilan');
    $tempat_bertemu      = $this->post('tempat_bertemu');
    $tipe_laptop         = $this->post('tipe_laptop');
    $biaya_total         = $this->post('biaya_total');
    $tracking_key        = $this->post('tracking_key');

    if ($tempat_bertemu == '') {
      $tempat_bertemu = 'UNJ';
    } else {
      $tempat_bertemu = $tempat_bertemu;
    }

    // GENERATE ID PEMESANAN

    $data = [
      'account_id'            => $account_id,
      'nama'                  => $nama,
      'email'                 => $email,
      'tracking_id '          => 1,
      'tracking_key'          => $tracking_key,
      'nomor_hp'              => $nomor_hp,
      'merk_laptop'           => $merk_laptop,
      'keterangan'            => $keterangan,
      'tanggal_pengambilan'   => date('Y-m-d', strtotime($tanggal_pengambilan)),
      'jam_pengambilan'       => $jam_pengambilan,
      'tempat_bertemu'        => $tempat_bertemu,
      'created_at'            => date("Y-m-d H:i:s"),
      'modified_at'           => date("Y-m-d H:i:s"),
      'biaya_total'           => $biaya_total,
      'status_payment'        => 1,
      'tipe_laptop'           => $tipe_laptop,
      'teknisi'               => 1,
    ];

    if ($this->Header_order_model->createHeaderOrder($data) > 0) {
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


  // untuk batal order ?
  // public function index_delete()
  // {
  //   $order_id = $this->delete('order_id');
  //   if ($order_id == null) {
  //     $this->response([
  //       'status'  => false,
  //       'message' => 'provide an order id!'
  //     ], REST_Controller::HTTP_BAD_REQUEST);
  //   } else {
  //     if ($this->Order_model->deleteOrder($order_id) > 0) {
  //       //ok
  //       $this->response([
  //         'status'      => true,
  //         'account_id'  => $order_id,
  //         'message'     => 'deleted'
  //       ], REST_Controller::HTTP_OK);
  //     } else {
  //       //id not found
  //       $this->response([
  //         'status'  => false,
  //         'message'    => 'order id not found'
  //       ], REST_Controller::HTTP_BAD_REQUEST);
  //     }
  //   }
  // }
}
