<?php

include "connect.php";

switch ($_POST['aksi']) {
  case 'buat':
    $return = buat($db);
    break;
  case 'update':
    $return = ubah($db);
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

function buat($db){

  $query = $db->prepare("INSERT INTO PENGUMUMAN (id_admin,judul,isi,waktu)
                        VALUES(
                          ".$_POST['id_admin'].",
                          '".$_POST['judul']."',
                          '".$_POST['isi']."',
                          CURRENT_TIMESTAMP
                        )");

  $success = $query->execute();
  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();
  if ($success){
    array_push($data["posts"], "Sukses");
  }

  return $data;
}

function ubah($db){

  $query = $db->prepare("UPDATE PENGUMUMAN SET id_admin = ".$_POST['id_admin'].", isi = '".$_POST['isi']."', judul = '".$_POST['judul']."', waktu = CURRENT_TIMESTAMP WHERE id = ".$_POST['id']);

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

function lihat($db){
  $sth = $db->prepare("SELECT *, judul as nama FROM PENGUMUMAN WHERE id = ".$_POST['id']);
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
  $success = deleteTableContent($db, "PENGUMUMAN", $_POST['del_id']);
  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();
  if ($success){
    array_push($data["posts"], "Sukses");
  }
  return $data;
}

?>
