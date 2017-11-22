<?php

include "connect.php";

switch ($_POST['aksi']) {
  case 'buat':
    $return = buat_user($db);
    break;
  case 'lihat':
    $return = lihat_user($db);
    break;
  case 'hapus':
    $return = hapus($db);
    break;
  case 'terima':
    $return = terima($db);
    break;
}

$db = null;

$data = array();
$data['success'] = 0;
$data['posts'] = array();

if ($return['success']){
  $data['success'] = 1;
  $data['posts'] = $return['posts'];
}

echo json_encode($data);

function buat_user($db){
  $query = $db->prepare("INSERT INTO PENGGUNA(nama, alamat, jabatan, notelp, email, password, peran)
                        VALUES(
                          '".$_POST['nama']."',
                          '".$_POST['alamat']."',
                          '".$_POST['jabatan']."',
                          '".$_POST['notelp']."',
                          '".$_POST['email']."',
                          '".$_POST['password']."',
                          2
                        )");

  $success = $query->execute();
  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();
  array_push($data["posts"], "Sukses", $db->lastInsertId());
  return $data;
}

function lihat_user($db){
  $sth = $db->prepare("SELECT *, IFNULL(id_taman_baca,-1) as id_tb
     FROM PENGGUNA WHERE id = ".$_POST['id']);
  $sth->execute();
  $val = $sth->fetchAll();
  if ($val){
    $data = array();
    $data["success"] = 1;
    $data["posts"] = $val;
    echo json_encode($data);
  }
}

function hapus($db){
  $success = deleteTableContent($db, "PENGGUNA", $_POST['del_id']);
  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();
  if ($success){
    array_push($data["posts"], "Sukses");
  }
  return $data;
}

function terima($db){

  $query = $db->prepare("UPDATE PENGGUNA SET status = 1 WHERE id = ".$_POST['id']);

  $success = $query->execute();
  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();
  if ($success){
    array_push($data["posts"], "Sukses");
  } else {
    array_push($data["posts"], "Gagal, Silahkan hubungi admin");
  }

  return $data;
}

?>
