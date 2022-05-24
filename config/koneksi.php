<?php
	// validasi xss dan injection x
	require_once('fungsi_validasi.php');

	// database
	$koneksi = mysqli_connect("localhost","root","","dpt_db");
    
	//execute query
	function query($str){
		global $koneksi;
		$result = mysqli_query($koneksi,$str);
		if(!$result){
			echo mysqli_error($koneksi);
			exit;
		} else {
			return $result;
		}
	}
    
	// select all
	function viewallquery($str){
		global $koneksi;
		$result = mysqli_query($koneksi,$str);
		if(!$result){
			echo mysqli_error($koneksi);
		} else {
			$rows = [];
			while($row = mysqli_fetch_assoc($result)){
				$rows[] = $row;
			}
			return $result;
		}
	}
    
	// select limit 1
	function viewquery($str){
		global $koneksi;
		$result = mysqli_query($koneksi,$str);
		if(!$result){
			echo mysqli_error($koneksi);
		} else {
			$result = mysqli_fetch_assoc($result);
			return $result;
		}
	}
    
	//number row
	function numrowquery($str){
		global $koneksi;
		$result = mysqli_num_rows(mysqli_query($koneksi,$str));
		if(!$result){
			echo mysqli_error($koneksi);
		} else {
			return $result;
		}
	}
    
	// enkripsi data/get/post/dll
	function enkrip($str){
		$result = htmlspecialchars($str, ENT_QUOTES);
		return $result;
	}

	// variable baru fungsi_validasi.php
	$val = new mvalidasi;

?>
