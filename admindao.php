<?php

include "connect.php";

switch ($_POST['aksi']) {
  case 'buat':
    $return = buat_user($db);
    break;
  case 'lihat':
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
}
$data['posts'] = $return['posts'];

echo json_encode($data);

function buat_user($db){
  $query = $db->prepare("INSERT INTO PENGGUNA(nama, alamat, jabatan, notelp, email, password, peran)
                        VALUES(
                          '".$_POST['nama']."',
                          '".$_POST['alamat']."',
                          '".$_POST['jabatan']."',
                          '".$_POST['notelp']."',
                          '".$_POST['email']."',
                          '".$_POST['pass']."',
                          1
                        )");

  $success = $query->execute();

  $data = array();
  $data["success"] = $success;
  $data["posts"] = array();

  if (!$success) {
    if ($query->errorInfo()[0] == 23000) {
      array_push($data["posts"], "Email Pengguna sudah terdaftar");
    } else {
      array_push($data["posts"], "Terjadi kesalahan, silahkan hubungi admin");
    }
    return $data;
  }

  array_push($data["posts"], "Sukses", $db->lastInsertId());
  return $data;
}



?>
