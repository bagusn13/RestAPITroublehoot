<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Konfirmasibayar extends REST_Controller
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

    $data_log = [
      'email'      => $email,
      'action'     => 'Melakukan konfirmasi pembayaran',
      'created_at' => date("Y-m-d H:i:s"),
    ];

    if ($tracking_key != null) {
      $config['upload_path']   = './assets/image/buktibayar/';
      $config['allowed_types'] = 'gif|jpg|png|jpeg';
      $config['max_size']      = '2400';
      $this->upload->initialize($config);
      $field_name = "image_order";

      if ($this->upload->do_upload($field_name)) {
        $upload_gambar = array('upload_data' => $this->upload->data());

        $data = array(
          'status_payment' => 2,
          'image_order'    => $upload_gambar['upload_data']['file_name']
        );

        $q = $this->Header_order_model->EditHeaderOrder($data, $tracking_key);

        if ($q) {
          $this->Log_model->createLog($data_log);

          $this->response([
            'status'     => true,
            'message'    => "Konfirmasi pembayaran sukses"
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
}
