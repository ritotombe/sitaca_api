<?php

include "connect.php";

switch ($_POST['aksi']) {
  case 'buat':
    $return = buat_user($db);
    break;
  case 'lihat':
    $return = lihat_user($db);
    break;
  case 2:
    // $db->close();
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
  $query = $db->prepare("INSERT INTO PENGGUNA(nama, alamat, jabatan, notelp, email, password)
                        VALUES(
                          '".$_POST['nama']."',
                          '".$_POST['alamat']."',
                          '".$_POST['jabatan']."',
                          '".$_POST['notelp']."',
                          '".$_POST['email']."',
                          '".$_POST['password']."'
                        )");

  $success = $query->execute();
  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();
  array_push($data["posts"], "Sukses", $db->lastInsertId());
  return $data;
}

function lihat_user($db){
  $val = getAllVal($db, "PENGGUNA", "id", $_POST['id']);
  if ($val){
    $data = array();
    $data["success"] = 1;
    $data["posts"] = $val;
    echo json_encode($data);
  }
}

?>
