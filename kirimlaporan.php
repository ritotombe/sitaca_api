<?php

include "connect.php";

switch ($_POST['aksi']) {
  case 'kirim_laporan':
    $return = buat_laporan($db);
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
$data['posts'] = $return['posts'];

if ($return['success']){
  $data['success'] = 1;
} else {
  array_push($data['posts'], $db->errorInfo());
}

$db = null;

echo json_encode($data);

function buat_laporan($db){

  $data = array();
  $data["success"] = 0;
  $data["posts"] = array();

  $id_tb = get1Val($db, "PENGGUNA", "id", $_POST['id_user'], "id_taman_baca");

  if (!$id_tb){
    array_push($data["posts"], "Pengguna belum terdaftar, silahkan hubungi admin.");
    return $data;
  }

  if ($id_tb != $_POST['id_tb']){
    array_push($data["posts"], "Kesalahan Otentikasi, silahkan hubungi admin.");
    return $data;
  }

  $status = get1Val($db, "PENGGUNA", "id", $_POST['id_user'], "status");

  if (!$status){
    array_push($data["posts"], "Pengguna belum diverifikasi, silahkan hubungi admin.");
    return $data;
  }

  deleteTableContent($db, "KATEGORI", "id_taman_baca", $id_tb);
  deleteTableContent($db, "BUKU", "id_taman_baca", $id_tb);
  deleteTableContent($db, "SUMMARY_BUKU", "id_taman_baca", $id_tb);
  deleteTableContent($db, "LOG_HARIAN", "id_taman_baca", $id_tb);
  deleteTableContent($db, "KEGIATAN", "id_taman_baca", $id_tb);

  $success3 = upsert($db, "KATEGORI", $_POST['kategori_query']);
  $success1 = upsert($db, "BUKU", $_POST['rating_query']);
  $success2 = upsert($db, "SUMMARY_BUKU", $_POST['summary_query']);
  $success4 = upsert($db, "LOG_HARIAN", $_POST['log_query']);
  $success5 = upsert($db, "KEGIATAN", $_POST['kegiatan_query']);

  if ($success1 && $success2 && $success3 && $success4 && $success5){
    $data["success"] = 1;
    array_push($data["posts"], "Sukses");
  }

  return $data;
}

?>
