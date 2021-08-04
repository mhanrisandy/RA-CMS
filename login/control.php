<?php
// Bagian Home
if ($_GET['module']=='home'){ 
  include "modul/mod_home/home.php";
}

// Bagian User
elseif ($_GET['module']=='nama_modul'){
    include "modul/nama_modul/nama_modul.php";
}

// Apabila modul tidak ditemukan
else{
     if (file_exists("modul/mod_". $_GET['module'] ."/". $_GET['module'] .".php")){
       include "modul/mod_". $_GET['module'] ."/". $_GET['module'] .".php";
     } else {
       include "view/blank.php";   
     }
}
?>