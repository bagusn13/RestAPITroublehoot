<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Log extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Log_model');
  }

  public function index_get()
  {
    $log = $this->Log_model->viewLog();

    if ($log) {
      $this->response([
        'status'  => true,
        'data'    => $log
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status'  => false,
        'data'    => 'Log not found'
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }
}
