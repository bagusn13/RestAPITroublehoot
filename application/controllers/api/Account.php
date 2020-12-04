<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Account extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Account_model');
    $this->load->model('Log_model');
  }

  public function index_get()
  {
    $account_id = $this->get('account_id');
    if ($account_id == null) {
      $account = $this->Account_model->getAccount();
    } else {
      $account = $this->Account_model->getAccount($account_id);
    }

    if ($account) {
      $this->response([
        'status'  => true,
        'data'    => $account
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status'  => false,
        'data'    => 'accounts id not found'
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function index_delete()
  {
    $account_id = $this->delete('accounts_id');
    if ($account_id == null) {
      $this->response([
        'status'  => false,
        'message' => 'provide an accounts id!'
      ], REST_Controller::HTTP_BAD_REQUEST);
    } else {
      if ($this->Account_model->deleteAccount($account_id) > 0) {
        //ok
        $this->response([
          'status'      => true,
          'account_id'  => $account_id,
          'message'     => 'deleted'
        ], REST_Controller::HTTP_OK);
      } else {
        //id not found
        $this->response([
          'status'     => false,
          'message'    => 'accounts id not found'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }
  }

  public function index_post()
  {
    date_default_timezone_set('Asia/Jakarta');

    $data = [
      'oauth_provider' => 'apps',
      'first_name'     => $this->post('first_name'),
      'last_name'      => $this->post('last_name'),
      'email'          => $this->post('email'),
      'password'       => base64_encode($this->post('password')),
      'role'           => 'user',
      'locale'         => 'id',
      'created_at'     => date("Y-m-d H:i:s"),
      'modified_at'    => date("Y-m-d H:i:s"),
      'active'         => 1,
    ];

    $data_log = [
      'email'      => $this->post('email'),
      'action'     => 'Daftar akun',
      'created_at' => date("Y-m-d H:i:s"),
    ];

    $create = $this->Account_model->createAccount($data);

    if ($create == 0) {
      $this->response([
        'status'  => false,
        'message' => 'Email sudah digunakan'
      ], REST_Controller::HTTP_OK);
    } else {
      $this->Log_model->createLog($data_log);

      $this->response([
        'status'    => true,
        'message'   => 'Berhasil didaftarkan'
      ], REST_Controller::HTTP_CREATED);
    }
  }

  public function index_put()
  {
    $account_id = $this->put('accounts_id');
    $data = [
      'oauth_provider' => $this->put('first_name'),
      'first_name'     => $this->put('first_name'),
      'last_name'      => $this->put('last_name'),
      'email'          => $this->put('email'),
      'password'       => $this->put('password'),
      'role'           => $this->put('role'),
      'locale'         => $this->put('locale'),
      'picture'        => $this->put('picture'),
      'active'         => $this->put('active'),
    ];

    if ($this->Account_model->updateAccount($data, $account_id) > 0) {
      //ok
      $this->response([
        'status'      => true,
        'message'     => 'account has been modified'
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status'  => false,
        'data'    => 'failed to modified data'
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
  }
}
