<?php
  // Home
  if ($module=='home'){ 
    include "modul/mod_home/home.php";
  }

  // modul lain
  elseif ($module=='nama_modul'){
      include "modul/nama_modul/nama_modul.php";
  }

  // other
  else{
      if (file_exists("modul/mod_". $module ."/". $module .".php")){
        include "modul/mod_". $module ."/". $module .".php";
      } else {
        include "view/blank.php";   
      }
  }
?>