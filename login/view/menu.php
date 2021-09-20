<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
      <img src="../images/logo.png?v=1" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">SEEDATA-DPT</span>
    </a>
	    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="modul/mod_users/aksi_users.php?module=user&act=foto_circle&id=<?=$_SESSION[username]?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="media.php?module=user&act=edituser&id=<?=$_SESSION['id_session']?>" class="d-block"><?=$_SESSION['username']?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
      <!-- hapus nav-child-indent jika ingin pakai yg default -->    
	  <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
<?php
        echo "<li class=\"nav-item\"><a class=\"nav-link\" href=?module=home> <i class=\"nav-icon fas fa-tachometer-alt\"></i><p>Beranda</p></a></li>"; 
    		echo "<li class=\"nav-item\"><a class=\"nav-link\" href=?module=mahasiswa> <i class=\"nav-icon fas fa-user\"></i><p>Mahasiswa</p></a></li>";
        echo "<li class=\"nav-item\"><a class=\"nav-link\" href=?module=user> <i class=\"nav-icon fas fa-users\"></i><p>Users</p></a></li>";
	    	echo'<li class="nav-item"><a class="nav-link" href=logout.php><i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p></a></li>';
?>
     </ul>
	 </nav>
	 <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>	