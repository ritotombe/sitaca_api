<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "sitaca";

$db = new PDO('mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8mb4', $db_user, $db_pass);

function get1Val($db, $tableName, $prop, $value, $columnName){
  $sth = $db->prepare("SELECT `$columnName` FROM `$tableName` WHERE $prop='".$value."' LIMIT 1");
  $sth->execute();
  $f = $sth->fetch();
  $result = $f[$columnName];
  return $result;
}

function getAllVal($db, $tableName, $prop, $value){
  $sth = $db->prepare("SELECT * FROM ".$tableName." WHERE ".$prop." = ".$value."");
  $sth->execute();
  $f = $sth->fetchAll();
  return $f;
}

function upsert($db, $table, $value){
  $query = $db->prepare("REPLACE INTO ".$table." VALUES".$value);
  $success = $query->execute();
  return $success;
}

function deleteTableContent($db, $table, $id, $value){
  $query = $db->prepare("DELETE FROM ".$table." WHERE ".$id." = ".$value);
  $success = $query->execute();
  return $success;
}

?>
