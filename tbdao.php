<?php

include "connect.php";

switch ($_POST['aksi']) {
  case 'buat':
    $return = buat_tb($db);
    break;
  case 'ubah_user':
    $return = ubah_user($db);
    break;
  case 'lihat':
    $return = lihat($db);
    break;
  case 'hapus':
    $return = hapus($db);
    break;
}



$data = array();
$data['success'] = 0;
$data['posts'] = array();

if ($return['success']){
  $data['success'] = 1;
  $data['posts'] = $return['posts'];
} else {
  array_push($data['posts'], $db->errorInfo());
}

$db = null;

echo json_encode($data);

function buat_tb($db){

  $user_id = get1Val($db, "PENGGUNA", "email", $_POST['email_user'], "id");

  $query = $db->prepare("INSERT INTO TAMAN_BACA(nama, alamat, twitter, facebook, id_user)
                        VALUES(
                          '".$_POST['nama']."',
                          '".$_POST['alamat']."',
                          '".$_POST['twitter']."',
                          '".$_POST['facebook']."',
                          ".$user_id."
                        )");

  $success = $query->execute();
  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();
  if ($success){
    $id_tb = $db->lastInsertId();
    $query = $db->prepare("UPDATE PENGGUNA SET id_taman_baca =".$id_tb." WHERE id  =".$user_id);
    $success = $query->execute();
    array_push($data["posts"], "Sukses", $id_tb);
  }

  return $data;
}

function ubah_user($db){

  $query = $db->prepare("UPDATE TAMAN_BACA SET id_user = ".$_POST['id_post']." WHERE id = ".$_POST['id_tb']);

  $success = $query->execute();
  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();
  if ($success){
    $query = $db->prepare("UPDATE PENGGUNA SET id_taman_baca = NULL WHERE id = ".$_POST['id_pre']);
    $success = $query->execute();
    $query = $db->prepare("UPDATE PENGGUNA SET id_taman_baca = ".$_POST['id_tb']." WHERE id = ".$_POST['id_post']);
    $success = $query->execute();
    array_push($data["posts"], "Sukses");
  } else {
    array_push($data["posts"], "Gagal, Silahkan hubungi admin");
  }

  return $data;
}

function lihat($db){
  $sth = $db->prepare("SELECT TAMAN_BACA.id AS id, PENGGUNA.id AS id_user, PENGGUNA.nama AS nama_user, TAMAN_BACA.nama AS nama_tb, TAMAN_BACA.alamat AS alamat, twitter, facebook
     FROM TAMAN_BACA, PENGGUNA WHERE id_user = PENGGUNA.id AND TAMAN_BACA.id = ".$_POST['id']);
  $sth->execute();
  $success = $sth->fetchAll();
  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();
  if ($success){
    $data["posts"] = $success;
  }

  return $data;
}

function hapus($db){
  $success = deleteTableContent($db, "TAMAN_BACA", $_POST['del_id']);
  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();
  if ($success){
    array_push($data["posts"], "Sukses");
  }
  return $data;
}

?>
