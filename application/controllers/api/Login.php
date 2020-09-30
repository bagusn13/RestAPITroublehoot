<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Login extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Account_model');
  }

  public function index_post()
  {
    $email    = $this->post('email');
    $password = $this->post('password');

    $check = $this->Account_model->login($email, $password);

    if ($check) {
      $this->response([
        'status'  => true,
        'message' => 'Login Success',
        'data'    => $check
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status'  => false,
        'message' => 'Email or password is incorrect'
      ], REST_Controller::HTTP_OK);
    }
  }
}
