<?php

include "connect.php";

switch ($_POST['aksi']) {
  case 'buat':
    $return = buat_tb($db);
    break;
  case 1:
    // $db->close();
    break;
  case 2:
    // $db->close();
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

?>
