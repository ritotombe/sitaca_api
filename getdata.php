<?php

include "connect.php";

$data = array();
$data['success'] = 0;
$data['posts'] =  array();

switch ($_POST['tag']) {
  case 'pengumuman':
    pengumuman($db, $data);
    break;
  case 'login':
    login($db, $data);
    break;
  case 'taman_baca':
    taman_baca($db, $data);
    break;
  case 'spinner_user':
    spinner_user($db, $data);
    break;
  case 'summary':
    summary($db, $data);
    break;
  case 'buku':
    buku($db, $data);
    break;
  case 'log_harian':
    log_harian($db, $data);
    break;
  case 'log_kegiatan':
    log_kegiatan($db, $data);
    break;
  case 'user':
    user($db, $data);
    break;
  case 'admin':
    admin($db, $data);
    break;
}

$db = null;

function pengumuman($db, $data){
  $sth = $db->prepare("SELECT *, PENGUMUMAN.id AS id, PENGGUNA.nama as nama FROM PENGUMUMAN, PENGGUNA WHERE id_admin = PENGGUNA.id");
  $sth->execute();
  $f = $sth->fetchAll();
  if($f){
    $data['success'] = 1;
    $data['posts'] = $f;
    echo json_encode($data);
  }
}

function login($db, $data){
  if($_POST['email'] ==  null || $_POST['pass'] ==  null){
    echo "1";
  }

  $f = get1Row($db, 'PENGGUNA', 'email', $_POST['email'], '*');

  if (!$f) {
    echo json_encode($data);
  } else if ($f['password'] != $_POST['pass']){
    array_push($data["posts"], "Password salah");
    echo json_encode($data);
  } else if ($f['peran'] != 1) {
    array_push($data["posts"], "Anda bukan admin, silahkan mendaftar sebagai admin");
    echo json_encode($data);
  } else if ($f['status'] != 1) {
    array_push($data["posts"], "Anda belum diterima");
    echo json_encode($data);
  } else {
    array_push($data["posts"], $f);
    $data["success"] = 1;
    echo json_encode($data);
  }
}

function taman_baca($db, $data){
  $sth = $db->prepare("SELECT TAMAN_BACA.id AS id, PENGGUNA.id AS id_user, PENGGUNA.nama AS nama_user, TAMAN_BACA.nama AS nama_tb, TAMAN_BACA.alamat AS alamat, twitter, facebook
     FROM TAMAN_BACA, PENGGUNA WHERE id_user = PENGGUNA.id");
  $sth->execute();
  $f = $sth->fetchAll();
  if($f){
    $data['success'] = 1;
    $data['posts'] = $f;
    echo json_encode($data);
  } else {
    echo json_encode($data);
  }
}

function spinner_user($db, $data){
  $sth = $db->prepare("SELECT id, nama from PENGGUNA where status = 1");
  $sth->execute();
  $f = $sth->fetchAll();
  if($f){
    $data['success'] = 1;
    $data['posts'] = $f;
    echo json_encode($data);
  } else {
    echo json_encode($data);
  }
}

function summary($db, $data){
  $sth = $db->prepare("SELECT  id, id_taman_baca AS id_tb, individu, organisasi, beli_sendiri, 1001_buku AS yayasan, tanggal
     FROM SUMMARY_BUKU WHERE id_taman_baca = ".$_POST['id']);
  $sth->execute();
  $f = $sth->fetchAll();
  if($f){
    $data['success'] = 1;
    $data['posts'] = $f;
    echo json_encode($data);
  } else {
    echo json_encode($data);
  }
}


function buku($db, $data){
  $sth = $db->prepare("SELECT *, KATEGORI.nama AS nama_kategori
     FROM BUKU, KATEGORI WHERE BUKU.id_kategori = KATEGORI.id AND BUKU.id_taman_baca = KATEGORI.id_taman_baca  AND BUKU.id_taman_baca = ".$_POST['id']);
  $sth->execute();
  $f = $sth->fetchAll();
  if($f){
    $data['success'] = 1;
    $data['posts'] = $f;
    echo json_encode($data);
  } else {
    echo json_encode($data);
  }
}

function log_harian($db, $data){
  $sth = $db->prepare("SELECT *, id_taman_baca AS id_tb
     FROM LOG_HARIAN WHERE id_taman_baca = ".$_POST['id']);
  $sth->execute();
  $f = $sth->fetchAll();
  if($f){
    $data['success'] = 1;
    $data['posts'] = $f;
    echo json_encode($data);
  } else {
    echo json_encode($data);
  }
}

function log_kegiatan($db, $data){
  $sth = $db->prepare("SELECT *
     FROM KEGIATAN WHERE id_taman_baca = ".$_POST['id']);
  $sth->execute();
  $f = $sth->fetchAll();
  if($f){
    $data['success'] = 1;
    $data['posts'] = $f;
    echo json_encode($data);
  } else {
    echo json_encode($data);
  }
}

function user($db, $data){
  $sth = $db->prepare("SELECT *, IFNULL(id_taman_baca,-1) as id_tb
     FROM PENGGUNA");
  $sth->execute();
  $f = $sth->fetchAll();
  if($f){
    $data['success'] = 1;
    $data['posts'] = $f;
    echo json_encode($data);
  } else {
    echo json_encode($data);
  }
}

function admin($db, $data){
  $sth = $db->prepare("SELECT *, IFNULL(id_taman_baca,-1) as id_tb
     FROM PENGGUNA WHERE peran = 1");
  $sth->execute();
  $f = $sth->fetchAll();
  if($f){
    $data['success'] = 1;
    $data['posts'] = $f;
    echo json_encode($data);
  } else {
    echo json_encode($data);
  }
}

?>
