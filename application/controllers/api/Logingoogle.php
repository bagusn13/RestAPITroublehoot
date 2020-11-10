<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Logingoogle extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Account_model');
  }

  public function index_post()
  {
    $email    = $this->post('email');
    $oauth_id = $this->post('oauth_id');

    $check = $this->Account_model->loginGoogle($email, $oauth_id);

    if ($check) {
      $this->response([
        'status'  => true,
        'message' => 'Login Success',
        'data'    => $check
      ], REST_Controller::HTTP_OK);
    } else {
      $data = [
        'oauth_provider' => 'google',
        'oauth_id'       => $this->post("oauth_id"),
        'first_name'     => $this->post('first_name'),
        'last_name'      => $this->post('last_name'),
        'email'          => $this->post('email'),
        'role'           => 'user',
        'locale'         => 'id',
        'picture'        => $this->post("picture"),
        'created_at'     => date("Y-m-d H:i:s"),
        'modified_at'    => date("Y-m-d H:i:s"),
        'active'         => 1,
      ];

      if ($this->Account_model->createAccount($data) > 0) {
        //ok
        if ($check) {
          $this->response([
            'status'    => true,
            'data'      => $check,
            'message'   => 'new account has been created'
          ], REST_Controller::HTTP_CREATED);
        }
      } else {
        $this->response([
          'status'  => false,
          'message' => 'failed to create new data'
        ], REST_Controller::HTTP_OK);
      }
    }




    // else {
    //   $this->response([
    //     'status'  => false,
    //     'message' => 'Email or oauth not found'
    //   ], REST_Controller::HTTP_OK);
    // }
  }
}
