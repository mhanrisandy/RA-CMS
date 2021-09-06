<?php
session_start();
if (empty($_SESSION['username'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
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
if (isset($_POST['NAMA'])){
  $NAMA = htmlspecialchars($_POST['NAMA'], ENT_QUOTES);
  $NIM = htmlspecialchars($_POST['NIM'], ENT_QUOTES);
  $ALAMAT = htmlspecialchars($_POST['ALAMAT'], ENT_QUOTES);
  $foto_lama = htmlspecialchars($_POST['foto_lama'], ENT_QUOTES);
}

// Hapus
if ($act=='hapus'){
  $id = htmlspecialchars($_GET['id'], ENT_QUOTES);

  $r = viewquery("SELECT * FROM peg_mahasiswa WHERE id='$id'");
  filedelete("file_mahasiswa",$r[foto]);

  $hasil = query("DELETE FROM peg_mahasiswa WHERE id='$id'");

  if ($hasil) {
    set_notif('Berhasil menghapus data','check','success');
  } else {
    set_notif('Gagal menghapus data','check','danger');
  }

  header('location:../../index.php?module='.$module);
}

// Input
elseif ($act=='input'){
  if($_FILES['file']['size'] > 0){
    $foto = fileinsert($_FILES['file'],"file_mahasiswa",$foto_lama,$_SESSION['username'].'-');	
  } else {
    $foto = $foto_lama;	
  }
   
  $hasil = query("INSERT INTO peg_mahasiswa(
                               NAMA,
                               NIM,
                               ALAMAT,
							                 foto
                               ) VALUES (
                               '$NAMA',
                               '$NIM',
                               '$ALAMAT',
							                 '$foto'
                               )");

  if ($hasil) {
    set_notif('Berhasil menambah data','check','success');
  } else {
    set_notif('Gagal menambah data','check','danger');
  }

  header('location:../../index.php?module='.$module);
}

// Update
elseif ($act=='update'){
  if($_FILES['file']['size'] > 0){
    $foto = fileinsert($_FILES['file'],"file_mahasiswa",$foto_lama,$_SESSION['username'].'-');	
  } else {
    $foto = $foto_lama;	
  }
  $hasil = query("UPDATE peg_mahasiswa SET
                                    NAMA  = '$NAMA',
                                    NIM  = '$NIM',
                                    ALAMAT  = '$ALAMAT',
                                    foto = '$foto'

                                    WHERE id = '$_POST[id]'
									");

  if ($hasil) {
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

