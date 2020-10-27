<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Orderdetail extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Order_model');
  }

  public function index_post()
  {
    $tracking_key = $this->post('tracking_key');

    if ($tracking_key != null) {
      $detailOrder = $this->Order_model->getDetailOrder($tracking_key);
      if ($detailOrder) {
        $this->response([
          'status'  => true,
          'data'    => $detailOrder
        ], REST_Controller::HTTP_OK);
      } else {
        $this->response([
          'status'  => false,
          'data'    => 'Order history not found'
        ], REST_Controller::HTTP_OK);
      }
    }
  }
}
