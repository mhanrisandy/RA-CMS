<?php

function set_notif($pesan,$icon,$warna){
  session_start();
  $_SESSION[pesan] = $pesan;
  $_SESSION[c] = $warna;
  $_SESSION[i] = $icon;
}

function show_notif(){
  if (!empty($_SESSION['pesan'])) {
    echo"<div class='alert alert-$_SESSION[c] alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
              <h5><i class='icon fas fa-info'></i> Alert!</h5>$_SESSION[pesan]
            </div>";
    unset($_SESSION['pesan']);
    unset($_SESSION['i']);
    unset($_SESSION['c']);
  }
}

function show_notif_swal(){
  if (!empty($_SESSION['pesan'])) {
    echo "<script>
              swal('"; echo $_SESSION['pesan']; echo"', {
                icon: 'success',
              });
          </script>";
    unset($_SESSION['pesan']);
    unset($_SESSION['i']);
    unset($_SESSION['c']);
  }
}

?>
