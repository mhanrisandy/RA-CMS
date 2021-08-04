<?php
// Home
if ($_GET['module']=='home'){ 
  include "modul/mod_home/home.php";
}

// modul lain
elseif ($_GET['module']=='nama_modul'){
    include "modul/nama_modul/nama_modul.php";
}

// modul tanpa kondisi
else{
     if (file_exists("modul/mod_". $_GET['module'] ."/". $_GET['module'] .".php")){
       include "modul/mod_". $_GET['module'] ."/". $_GET['module'] .".php";
     } else {
       include "view/blank.php";   
     }
}
?>