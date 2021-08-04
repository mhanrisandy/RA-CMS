<?php

	// panggil fungsi validasi xss dan injection
	require_once('fungsi_validasi.php');

	// definisikan koneksi ke database
	$koneksi = mysqli_connect("localhost","root","","dpt_db");

	// Koneksi dan memilih database di server
	if (mysqli_connect_errno()){
		echo "Koneksi database gagal : " . mysqli_connect_error();
		exit;
	}
    
	function query($str){
		$koneksi = mysqli_connect("localhost","root","","dpt_db");
		$result = mysqli_query($koneksi,$str);
		return $result;
	}

	function enkrip($str){
		$result = htmlspecialchars($str, ENT_QUOTES);
		return $result;
	}

	// buat variabel untuk validasi dari file fungsi_validasi.php
	$val = new mvalidasi;

?>
