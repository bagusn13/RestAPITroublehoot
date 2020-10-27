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
  }

  public function index_post()
  {
    $tracking_key = $this->post('tracking_key');

    if ($tracking_key != null) {
      $config['upload_path']   = './assets/image/';
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
}
