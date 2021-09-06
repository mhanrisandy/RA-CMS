<?php
session_start();
if (empty($_SESSION['username'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
} else {
include "../../../config/koneksi.php";
include "../../../config/fungsi_notifbar.php";
include "../../../config/fungsi_file.php";

if (!empty($_GET['act'])){
  $act=$_GET['act'];
}
if (!empty($_GET['module'])){
  $module=$_GET['module'];
}

//field
if (isset($_POST['username'])){
  $id = htmlspecialchars($_POST['id'], ENT_QUOTES);
  $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
  $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
  $nama_lengkap = htmlspecialchars($_POST['nama_lengkap'], ENT_QUOTES);
  $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
  $level = htmlspecialchars($_POST['level'], ENT_QUOTES);
  $blokir = htmlspecialchars($_POST['blokir'], ENT_QUOTES);
  $id_session = htmlspecialchars($_POST['id_session'], ENT_QUOTES);
}

if ($act=='delete'){
  $id = htmlspecialchars($_GET['id'], ENT_QUOTES);
  $query = query("DELETE FROM users WHERE id='$id'");

  if ($query) {
    set_notif('Berhasil menghapus data','check','success');
  } else {
    set_notif('Gagal menghapus data','check','danger');
  }

  header('location:../../index.php?module='.$module);
}

elseif ($act=='input'){
   $query = query("INSERT INTO users(
                               username,
                               password,
                               nama_lengkap,
                               email,
                               level,
                               blokir,
                               id_session

                               ) VALUES (
                               '$username',
                               '$password',
                               '$nama_lengkap',
                               '$email',
                               '$level',
                               '$blokir',
                               '$id_session'

                               )");

  if ($query) {
    set_notif('Berhasil menambah data','check','success');
  } else {
    set_notif('Gagal menambah data','check','danger');
  }

  header('location:../../index.php?module='.$module);
}

elseif ($act=='update'){
  if($_FILES['file']['size'] > 0){
    $foto = fileinsert($_FILES['file'],"file_users",$foto_lama,$_SESSION['username'].'-');	
  } else {
    $foto = $foto_lama;	
  }

  $query = query("UPDATE users SET
                                    username  = '$username',
                                    password  = '$password',
                                    nama_lengkap  = '$nama_lengkap',
                                    email  = '$email',
                                    level  = '$level',
                                    blokir  = '$blokir',
                                    id_session  = '$id_session',
                                    foto = '$foto'

                                    WHERE id = '$id'");

  if ($query) {
    set_notif('Berhasil menambah data','check','success');
  } else {
    set_notif('Gagal menambah data','check','danger');
  }

  header('location:../../index.php?module='.$module);
}

// view 
elseif ($act=='view'){  
  $file_name = $_GET['file_name'];
  $table = $_GET['table'];
  $dir   = '../../../images/'.$table.'/';
  $query = query("SELECT * FROM $table WHERE file_name='$file_name'") or die(mysqli_error());

   if (mysqli_num_rows($query)>0){
       $result = mysqli_fetch_array($query);
       $content = $result['file_content'];
       $name   = $result['file_name'];
       $dir_file = $dir.$name;
  
      if (($result['file_type'] == 'image/png') or ($result['file_type'] == 'image/jpeg') or ($result['file_type'] == 'application/pdf')){
          header("Content-type: $result[file_type]");
      } else {
          header("Content-Disposition: attachment; filename=".$name);
          header("Content-length: ".$result['file_size']."");
          header("Content-type: $result[file_type]");
      }
      
      if (file_exists($dir_file)) {
          readfile($dir_file);
      } else {
          echo $content;
      }
 }
}

}
?>