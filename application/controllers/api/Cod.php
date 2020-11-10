<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Cod extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Header_order_model');
  }

  public function index_post()
  {
    $tracking_key = $this->post('tracking_key');

    if ($tracking_key != null) {

      $data = array(
        'status_payment' => 4,
      );

      $q = $this->Header_order_model->EditHeaderOrder($data, $tracking_key);

      if ($q) {
        $this->response([
          'status'  => true,
          'message'    => "Konfirmasi pembayaran sukses"
        ], REST_Controller::HTTP_OK);
      } else {
        $this->response([
          'status'     => false,
          'message'    => 'Konfirmasi pembayaran gagal'
        ], REST_Controller::HTTP_OK);
      }
    }
  }
}
