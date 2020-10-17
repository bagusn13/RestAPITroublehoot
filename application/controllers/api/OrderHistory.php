<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class OrderHistory extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Header_order_model');
  }

  public function index_post()
  {
    $account_id = $this->post('account_id');
    $tracking_key = $this->post('tracking_key');

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
          'data'    => 'Order history for this account not found'
        ], REST_Controller::HTTP_OK);
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
          'data'    => 'Order history not found'
        ], REST_Controller::HTTP_OK);
      }
    }
  }
}
