<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Tracking extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Tracking_model');
  }

  public function index_get()
  {
    $tracking_id = $this->get('tracking_id');
    if ($tracking_id == null) {
      $statusTracking = $this->Tracking_model->getStatusTracking();
    } else {
      $statusTracking = $this->Tracking_model->getStatusTracking($tracking_id);
    }


    if ($statusTracking) {
      $this->response([
        'status'  => true,
        'data'    => $statusTracking
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status'  => false,
        'data'    => 'status tracking not found'
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }
}
