<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Laptop extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Laptop_model');
  }

  public function index_get()
  {
    $laptop_id = $this->get('laptop_id');
    if ($laptop_id == null) {
      $laptop = $this->Laptop_model->getLaptop();
    } else {
      $laptop = $this->Laptop_model->getLaptop($laptop_id);
    }


    if ($laptop) {
      $this->response([
        'status'  => true,
        'data'    => $laptop
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status'  => false,
        'data'    => 'Laptop model not found'
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }
}
