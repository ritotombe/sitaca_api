<?php

include "connect.php";

switch ($_POST['tag']) {
  case 'pengumuman':
    pengumuman($db);
    break;
  case 1:
    // $db->close();
    break;
  case 2:
    // $db->close();
    break;
}

$db = null;

function pengumuman($db){
  $sth = $db->prepare("SELECT * FROM PENGUMUMAN, PENGGUNA WHERE id_admin = PENGGUNA.id");
  $sth->execute();
  $f = $sth->fetchAll();
  $data = array();
  $data['success'] = 0;
  if($f){
    $data['success'] = 1;
    $data['posts'] = $f;
    echo json_encode($data);
  }
}

?>
