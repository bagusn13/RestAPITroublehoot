<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Kerusakan extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Kerusakan_model');
  }

  public function index_get()
  {
    $kerusakan_id = $this->get('kerusakan_id');
    if ($kerusakan_id == null) {
      $kerusakan = $this->Kerusakan_model->getKerusakan();
    } else {
      $kerusakan = $this->Kerusakan_model->getKerusakan($kerusakan_id);
    }


    if ($kerusakan) {
      $this->response([
        'status'  => true,
        'data'    => $kerusakan
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status'  => false,
        'data'    => 'Category Kerusakan not found'
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }
}
