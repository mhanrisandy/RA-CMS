<?php
session_start();
include "../config/koneksi.php";

  $username = enkrip($_POST['username']);
  $pass     = md5(enkrip($_POST['password']));

  // pastikan username dan password adalah berupa huruf atau angka.
  if (!ctype_alnum($username) OR !ctype_alnum($pass)){
    $_SESSION['pesan'] = 'username anda tidak ditemukan!';
    include "login.php";
  } else{
    $login = query("SELECT * FROM users WHERE username='$username' AND password='$pass'");
    $ketemu = mysqli_num_rows($login);
    $r = mysqli_fetch_array($login);

    // jika user ditemukan
    if ($ketemu > 0){
      $_SESSION[username]     = $r[username];
      $_SESSION[namalengkap]  = $r[nama_lengkap];
      $_SESSION[level]        = $r[level];
      
      $sid_lama = session_id();
      
      session_regenerate_id();

      $sid_baru = session_id();
      $_SESSION[id_session] = $sid_baru;

      query("UPDATE users SET id_session='$sid_baru' WHERE username='$username'");
      header('location:index.php?module=home');
    }
    else{
      session_start();
      $_SESSION['pesan'] = 'Maaf username dan password yang anda masukan salah!';
      include "login.php";
    }
  }

?>
