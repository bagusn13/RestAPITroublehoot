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
    $this->load->model('Log_model');
  }

  public function index_post()
  {
    $email    = $this->post('email');
    $password = $this->post('password');
    date_default_timezone_set('Asia/Jakarta');
    $check = $this->Account_model->login($email, $password);

    if ($check) {
      $data_log = [
        'email'      => $check->email,
        'action'     => 'Login',
        'created_at' => date("Y-m-d H:i:s"),
      ];

      $this->Log_model->createLog($data_log);

      $this->response([
        'status'  => true,
        'message' => 'Berhasil login',
        'data'    => $check
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status'  => false,
        'message' => 'Email atau kata sandi salah'
      ], REST_Controller::HTTP_OK);
    }
  }
}
