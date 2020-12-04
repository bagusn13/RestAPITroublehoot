<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Batalpesan extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Header_order_model');
    $this->load->model('Log_model');
  }

  public function index_post()
  {
    $tracking_key = $this->post('tracking_key');
    $email        = $this->post('email');
    date_default_timezone_set('Asia/Jakarta');

    if ($tracking_key != null) {

      $data = array(
        'tracking_id' => 5,
        'status_payment' => 5,
      );

      $data_log = [
        'email'      => $email,
        'action'     => 'Membatalkan pesanan',
        'created_at' => date("Y-m-d H:i:s"),
      ];

      $q = $this->Header_order_model->EditHeaderOrder($data, $tracking_key);

      if ($q) {
        $this->Log_model->createLog($data_log);

        $this->response([
          'status'  => true,
          'message'    => "Pemesanan Dibatalkan"
        ], REST_Controller::HTTP_OK);
      } else {
        $this->response([
          'status'     => false,
          'message'    => 'Maaf terjadi masalah'
        ], REST_Controller::HTTP_OK);
      }
    }
  }
}
