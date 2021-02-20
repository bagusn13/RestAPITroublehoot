<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Headerorder extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Header_order_model');
    $this->load->model('Log_model');

    $config['mailtype'] = 'text';
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = "ssl://smtp.gmail.com";
    $config['smtp_user'] = 'troubleshootdotid@gmail.com';
    $config['smtp_pass'] = 'troubleshootinaja';
    $config['smtp_port'] = "465";
    $config['newline'] = "\r\n";

    $this->load->library('email', $config);
  }

  // get riwayat belanja per user($id_user)

  public function index_get()
  {
    $account_id = $this->get('account_id');
    $tracking_key = $this->get('tracking_key');
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
          'data'    => 'order history for this account not found'
        ], REST_Controller::HTTP_NOT_FOUND);
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
          'data'    => 'order id not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }
  }


  public function index_post()
  {
    // Mengambil value dari form dengan method POST
    $account_id          = $this->post('account_id');
    $nama                = $this->post('nama');
    $email               = $this->post('email');
    $merk_laptop         = $this->post('merk_laptop');
    $keterangan          = $this->post('keterangan');
    $nomor_hp            = $this->post('nomor_hp');
    $tanggal_pengambilan = $this->post('tanggal_pengambilan');
    $jam_pengambilan     = $this->post('jam_pengambilan');
    $tempat_bertemu      = $this->post('tempat_bertemu');
    $tipe_laptop         = $this->post('tipe_laptop');
    $biaya_total         = $this->post('biaya_total');
    $tracking_key        = $this->post('tracking_key');
    $ongkir              = $this->post('ongkir');
    date_default_timezone_set('Asia/Jakarta');

    if ($tempat_bertemu == '') {
      $tempat_bertemu = 'UNJ';
    } else {
      $tempat_bertemu = $tempat_bertemu;
    }

    // GENERATE ID PEMESANAN

    $data = [
      'account_id'            => $account_id,
      'nama'                  => $nama,
      'email'                 => $email,
      'tracking_id '          => 1,
      'tracking_key'          => $tracking_key,
      'nomor_hp'              => $nomor_hp,
      'merk_laptop'           => $merk_laptop,
      'keterangan'            => $keterangan,
      'tanggal_pengambilan'   => date('Y-m-d', strtotime($tanggal_pengambilan)),
      'jam_pengambilan'       => $jam_pengambilan,
      'tempat_bertemu'        => $tempat_bertemu,
      'created_at'            => date("Y-m-d H:i:s"),
      'modified_at'           => date("Y-m-d H:i:s"),
      'biaya_total'           => $biaya_total,
      'status_payment'        => 1,
      'tipe_laptop'           => $tipe_laptop,
      'teknisi'               => 1,
      'ongkir'                => $ongkir,
    ];

    $data_log = [
      'email'      => $email,
      'action'     => 'Melakukan Order',
      'created_at' => date("Y-m-d H:i:s"),
    ];

    if ($this->Header_order_model->createHeaderOrder($data) > 0) {
      $this->Log_model->createLog($data_log);

      $this->response([
        'status'    => true,
        'message'   => 'Sukses melakukan order'
      ], REST_Controller::HTTP_CREATED);

      //send notif email
      $this->email->from('troubleshootdotid@gmail.com', 'Troubleshoot.id');
      //$this->email->to('order.troubleshoot@gmail.com');
      $this->email->to('bagusn1367@gmail.com');
      $subject = "Pesanan Baru [#" . $tracking_key . "]";
      $this->email->subject($subject);

      $message = "Nama : " . $nama . "\n"; //ambil nama
      $message .= "No.Hp : " . $nomor_hp . "\n"; //ambil no hp
      $message .= "Laptop : " . $tipe_laptop . "\n";

      $message .= "Total Harga: Rp " . number_format($biaya_total, 2, ',', '.');  //ambil harga
      $this->email->message($message);
      $this->email->send();
      // end send notif email
    } else {
      $this->response([
        'status'  => false,
        'data'    => 'Maaf terjadi masalah'
      ], REST_Controller::HTTP_OK);
    }
  }
}
