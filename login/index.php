<?php
session_start();
include "../config/koneksi.php";
include "../config/setting.php";
//error_reporting(0);

if (empty($_SESSION['username'])){
	include "login.php";
} else {
	?>
	<!doctype html>
	<html lang="en">
	  <head>
		<meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <title>Administrator Home</title>
	  <!-- Tell the browser to be responsive to screen width -->
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <!-- Font Awesome -->
	  <link rel="stylesheet" href="../template/adminlte/plugins/fontawesome-free/css/all.min.css">
	  <!-- Ionicons -->
	  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	  <!-- Tempusdominus Bbootstrap 4 -->
	  <link rel="stylesheet" href="../template/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	    <!-- Select2 -->
      <link rel="stylesheet" href="../template/adminlte/plugins/select2/css/select2.min.css">
      <link rel="stylesheet" href="../template/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	  <!-- iCheck -->
	  <link rel="stylesheet" href="../template/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	  <!-- JQVMap -->
	  <!--<link rel="stylesheet" href="../template/adminlte/plugins/jqvmap/jqvmap.min.css">-->
	  <!-- Theme style -->
	  <link rel="stylesheet" href="../template/adminlte/dist/css/adminlte.min.css">
	  <!-- overlayScrollbars -->
	  <link rel="stylesheet" href="../template/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	  <!-- Daterange picker -->
	  <link rel="stylesheet" href="../template/adminlte/plugins/daterangepicker/daterangepicker.css">
	  <!-- summernote -->
	  <link rel="stylesheet" href="../template/adminlte/plugins/summernote/summernote-bs4.css">
	  <!-- Google Font: Source Sans Pro -->
	  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	  
	  <!-- SweetAlert -->
	  <script src="../template/adminlte/node_modules/sweetalert/dist/sweetalert.min.js"></script>
	  	   <!-- JQuery -->
      <script src="../template/adminlte/plugins/jquery/jquery.min.js"></script>
	  </head>
	  <body class="hold-transition sidebar-mini layout-fixed">
	  <!-- WweetAlert -->
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	  <div class="wrapper">
	  
		  <!-- Navbar -->
		  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
				<?php include "view/nav_kiri.php"; ?> 
				<?php include "view/nav_kanan.php"; ?> 
		  </nav>
		  <!-- /.navbar -->
   
			<?php  include "view/menu.php"; ?>
			
			<div class="content-wrapper">
			<?php include "view/content_header.php"; ?>
				<section class="content">
					<div class="container-fluid">
						<div class="row">
						   <?php include "control.php"; ?>
						</div>   
					</div><!-- /.container-fluid -->
				 </section>
			</div>
			
			<!-- Control Sidebar -->
                 <?php include "view/menu_nav.php"; ?> 
            <!-- /.control-sidebar -->
			
			<footer class="main-footer">
			<strong>Copyright &copy; 2020 by <a href='http://rasoftware.net'>RASOFTWARE</a>. All rights reserved.</strong>
		   
			<div class="float-right d-none d-sm-inline-block">
			  <b>Version</b> 4
			</div>
		  </footer>

		</div>
		<!-- ./wrapper -->
		
			<!-- jQuery -->
			<script src="../template/adminlte/plugins/jquery/jquery.min.js"></script>
			<!-- jQuery UI 1.11.4 -->
			<script src="../template/adminlte/plugins/jquery-ui/jquery-ui.min.js"></script>
			<!-- Select -->
            <script src="../template/adminlte/plugins/select2/js/select2.full.min.js"></script>
			<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
			<script>
			  $.widget.bridge('uibutton', $.ui.button)
			</script>
			<!-- Bootstrap 4 -->
			<script src="../template/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
			<!-- ChartJS -->
			<script src="../template/adminlte/plugins/chart.js/Chart.min.js"></script>
			<!-- Sparkline -->
			<script src="../template/adminlte/plugins/sparklines/sparkline.js"></script>
			<!-- JQVMap -->
			<!--<script src="../template/adminlte/plugins/jqvmap/jquery.vmap.min.js"></script>
			<script src="../template/adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>-->
			<!-- jQuery Knob Chart -->
			<script src="../template/adminlte/plugins/jquery-knob/jquery.knob.min.js"></script>
			<!-- daterangepicker -->
			<script src="../template/adminlte/plugins/moment/moment.min.js"></script>
			<script src="../template/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
			<!-- Tempusdominus Bootstrap 4 -->
			<script src="../template/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
			<!-- Summernote -->
			<script src="../template/adminlte/plugins/summernote/summernote-bs4.min.js"></script>
			<!-- overlayScrollbars -->
			<script src="../template/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
			<!-- AdminLTE App -->
			<script src="../template/adminlte/dist/js/adminlte.js"></script>
			<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
			<script src="../template/adminlte/dist/js/pages/dashboard.js"></script>
			<!-- AdminLTE for demo purposes -->
			<!-- <script src="dist/js/demo.js"></script> -->  
	  </body>
	</html>
	<?php
	}
?>