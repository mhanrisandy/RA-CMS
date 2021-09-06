<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{

include "../../../config/koneksi.php";
include "../../../config/fungsi_seo.php";
include "../../../config/library.php";

$module=$_GET[module];
$act=$_GET[act];
$dir='../../../images/file_kegiatan_harian/';

date_default_timezone_set('Asia/Jakarta');
$tanggal_daftar = date("Y-m-d");

// Hapus kegiatan_harian_file
if ($module=='kegiatan_harian_file' AND $act=='hapus'){
    //hapus file
    $query   = mysql_query("SELECT * FROM file_kegiatan_harian WHERE id='$_GET[id]' and id_kegiatan_harian='$_GET[id_kegiatan_harian]'");
    $result = mysql_fetch_array($query);
    $nama_lama   = $result['file_name'];
    if (file_exists($dir.$nama_lama)) {
        unlink($dir.$nama_lama);
    }
    
    mysql_query("DELETE FROM file_kegiatan_harian WHERE id='$_GET[id]' and id_kegiatan_harian='$_GET[id_kegiatan_harian]'");
    header('location:../../media.php?module=kegiatan_harian&act=editkegiatan_harian&id='.$_GET['id_kegiatan_harian']);
}

// Input kegiatan_harian_file
elseif ($module=='kegiatan_harian_file' AND $act=='input'){
    $id_kegiatan_harian = $_POST['id_kegiatan_harian'];
    $KETERANGAN = htmlspecialchars($_POST[KETERANGAN], ENT_QUOTES);
    $file_name = $_FILES['file']['name'];
    $tmp_name  = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_type = $_FILES['file']['type'];
    
    $ekstensi_file = explode('.', $file_name);
    $ekstensi_file =  strtolower(end($ekstensi_file));
    $file_name = $id_kegiatan_harian .'-'. uniqid() . '.'.$ekstensi_file;
    
	if ($file_size > 0 and ($file_type =='image/jpeg' or $file_type == 'image/png')){
        function resizeImage($resourceType,$image_width,$image_height) {
            $resizeWidth = 1024;
            $persen = ($resizeWidth * 100) / $image_width;
            $resizeHeight = ($persen / 100) * $image_height;
            $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
            imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
            return $imageLayer;
        }

        $sourceProperties = getimagesize($tmp_name);
        $resizeFileName = date("Ymdhisa") . time() . rand();
        $resizeuploadPath = "uploads/";
        $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $uploadImageType = $sourceProperties[2];
        $sourceImageWidth = $sourceProperties[0];
        $sourceImageHeight = $sourceProperties[1];
        
        $newfile = $resizeuploadPath."thump_".$resizeFileName.'.'. $fileExt;
        switch ($uploadImageType) {
            case IMAGETYPE_JPEG:
                $resourceType = imagecreatefromjpeg($tmp_name); 
                $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                imagejpeg($imageLayer,$newfile);
                break;

            case IMAGETYPE_GIF:
                $resourceType = imagecreatefromgif($tmp_name); 
                $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                imagegif($imageLayer,$newfile);
                break;

            case IMAGETYPE_PNG:
                $resourceType = imagecreatefrompng($tmp_name); 
                $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                imagepng($imageLayer,$newfile);
                break;

            default:
               
                break;
        }
        
        $file_size = filesize($newfile);
        $fp = fopen($newfile, 'r');
        $file_content = fread($fp, $file_size) or die("Error: cannot read file");
        $file_content = mysql_real_escape_string($file_content) or die("Error: cannot read file");
        fclose($fp);
                    
        $qu = "INSERT INTO file_kegiatan_harian
                    (`id_kegiatan_harian`,`id_user`,`file_content`,`file_name`,`file_type`,`file_size`,`KETERANGAN`)
                    VALUES
                    ('".$id_kegiatan_harian."','".$_SESSION[id_user]."','".$file_content."','".$file_name."','".$file_type."','".$file_size."','".$KETERANGAN."')";
        $re = mysql_query($qu) or die ("Sorry Cant insert db!");
        
        unlink($newfile);
    } else {
        $ekstensifilevalid = ['pdf','doc','docx','xls','xlsx','rar'];
        if(in_array($ekstensi_file, $ekstensifilevalid)){ 
            if ($file_size > 0 and $file_size <= 1000000){
                $fp = fopen($tmp_name, 'r');
                $file_content = fread($fp, $file_size) or die("Error: cannot read file");
                $file_content = mysql_real_escape_string($file_content) or die("Error: cannot read file");
                fclose($fp);
                            
                $qu = "INSERT INTO file_kegiatan_harian
                            (`id_kegiatan_harian`,`id_user`,`file_content`,`file_name`,`file_type`,`file_size`,`KETERANGAN`)
                            VALUES
                            ('".$id_kegiatan_harian."','".$_SESSION[id_user]."','".$file_content."','".$file_name."','".$file_type."','".$file_size."','".$KETERANGAN."')";
                $re = mysql_query($qu) or die ("Sorry Cant insert db!");
            }
        }
    }
    
    //notif
    if ($qu){ 
         //menyimpan blob ke file
         $query   = mysql_query("SELECT * FROM file_kegiatan_harian WHERE id=(SELECT MAX(id) as id FROM file_kegiatan_harian WHERE id_user='$_SESSION[id_user]')");
         if (mysql_num_rows($query)>0){ 
            $result  = mysql_fetch_array($query);
            $id_file = $result['id'];
            $content = $result['file_content'];
            $name = $result['file_name'];
            
            $berhasil = file_put_contents($dir.$name, $content);
            if($berhasil){
                //hapus blob
                mysql_query("UPDATE file_kegiatan_harian SET
                                                        file_content  = NULL
                                                        WHERE id = '$id_file'
									                    ");
            } 
         }
         
	     session_start();
         $_SESSION[pesan] = 'Berhasil menyimpan file';
         $_SESSION[c] = 'success';
         $_SESSION[i] = 'ban';
	} 
	else {
	    session_start();
        $_SESSION[pesan] = 'Gagal menyimpan file, maksimal file size 1 mb.';
        $_SESSION[c] = 'danger';
        $_SESSION[i] = 'check';
	} 
    //header('location:../../media.php?module=kegiatan_harian&act=editkegiatan_harian&id='. $_GET['id_kegiatan_harian']);
    header('location:../../media.php?module=kegiatan_harian');
}

// Update kegiatan_harian_file
elseif ($module=='kegiatan_harian_file' AND $act=='update'){
    $KETERANGAN = htmlspecialchars($_POST[KETERANGAN], ENT_QUOTES);
    mysql_query("UPDATE file_kegiatan_harian SET 
                                    KETERANGAN='$KETERANGAN'
            					    WHERE id='$_POST[id]'
									"); 
    
    $id = $_POST['id'];
    $KETERANGAN = htmlspecialchars($_POST[KETERANGAN], ENT_QUOTES);
    $file_name = $_FILES['file']['name'];
    $tmp_name  = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_type = $_FILES['file']['type'];
    
    //nama lama
    $query   = mysql_query("SELECT * FROM file_kegiatan_harian WHERE id='$id'");
    $result = mysql_fetch_array($query);
    $nama_lama   = $result['file_name'];
    
    //ubah nama
    $ekstensi_file = explode('.', $file_name);
    $ekstensi_file =  strtolower(end($ekstensi_file));
    $file_name = 'E'.$id .'-'. uniqid() . '.'.$ekstensi_file;
    
	if ($file_size > 0 and ($file_type =='image/jpeg' or $file_type == 'image/png')){
        function resizeImage($resourceType,$image_width,$image_height) {
            $resizeWidth = 1024;
            $persen = ($resizeWidth * 100) / $image_width;
            $resizeHeight = ($persen / 100) * $image_height;
            $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
            imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
            return $imageLayer;
        }

        $sourceProperties = getimagesize($tmp_name);
        $resizeFileName = date("Ymdhisa") . time() . rand();
        $resizeuploadPath = "uploads/";
        $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $uploadImageType = $sourceProperties[2];
        $sourceImageWidth = $sourceProperties[0];
        $sourceImageHeight = $sourceProperties[1];
        
        $newfile = $resizeuploadPath."thump_".$resizeFileName.'.'. $fileExt;
        switch ($uploadImageType) {
            case IMAGETYPE_JPEG:
                $resourceType = imagecreatefromjpeg($tmp_name); 
                $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                imagejpeg($imageLayer,$newfile);
                break;

            case IMAGETYPE_GIF:
                $resourceType = imagecreatefromgif($tmp_name); 
                $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                imagegif($imageLayer,$newfile);
                break;

            case IMAGETYPE_PNG:
                $resourceType = imagecreatefrompng($tmp_name); 
                $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                imagepng($imageLayer,$newfile);
                break;

            default:
               
                break;
        }
        
        $file_size = filesize($newfile);
        $fp = fopen($newfile, 'r');
        $file_content = fread($fp, $file_size) or die("Error: cannot read file");
        $file_content = mysql_real_escape_string($file_content) or die("Error: cannot read file");
        fclose($fp);
        
        $hasil =  mysql_query("UPDATE file_kegiatan_harian SET `file_content` = '$file_content',
        	                                            `file_name` = '$file_name',
        	                                            `file_type` = '$file_type',
        	                                            `file_size` = '$file_size'
        	                                             WHERE id = '$id'");  
        unlink($newfile); 	                                             
    } else {
        $ekstensifilevalid = ['pdf','doc','docx','xls','xlsx','rar'];
        if(in_array($ekstensi_file, $ekstensifilevalid)){ 
            if ($file_size > 0 and $file_size <= 1000000){
                $fp = fopen($tmp_name, 'r');
                $file_content = fread($fp, $file_size) or die("Error: cannot read file");
                $file_content = mysql_real_escape_string($file_content) or die("Error: cannot read file");
                fclose($fp);
                $hasil =  mysql_query("UPDATE file_kegiatan_harian SET `file_content` = '$file_content',
            	                                            `file_name` = '$file_name',
            	                                            `file_type` = '$file_type',
            	                                            `file_size` = '$file_size'
            	                                             WHERE id = '$id'");  
        	    
        
            }    
        }
    }
    
    //notif
    if ($hasil){ 
         //hapus file yang lama
         if (file_exists($dir.$nama_lama)) {
            unlink($dir.$nama_lama);
         }
         //menyimpan blob ke file
         $query   = mysql_query("SELECT * FROM file_kegiatan_harian WHERE id='$id'");
         if (mysql_num_rows($query)>0){ 
            $result  = mysql_fetch_array($query);
            $content = $result['file_content'];
            $name = $result['file_name'];
            
            $berhasil = file_put_contents($dir.$name, $content);
            
            if($berhasil){
                //hapus blob
                mysql_query("UPDATE file_kegiatan_harian SET
                                                        file_content  = NULL
                                                        WHERE id = '$id'
									                    ");
            } 
         }
	     session_start();
         $_SESSION[pesan] = 'Berhasil mengubah file';
         $_SESSION[c] = 'success';
         $_SESSION[i] = 'ban';
	} 
	else {
	    session_start();
        $_SESSION[pesan] = 'Gagal mengubah file, maksimal file size 1 mb.';
        $_SESSION[c] = 'danger';
        $_SESSION[i] = 'check';
	}
    //header('location:../../media.php?module=kegiatan_harian&act=editkegiatan_harian&id='. $_GET['id_kegiatan_harian']);
    header('location:../../media.php?module=kegiatan_harian');
}

// Input kegiatan_harian_file
elseif ($module=='kegiatan_harian_file' AND $act=='view'){
    $id = $_GET['id'];
    $id_user = $_GET['id_user'];
    
    $sqll = "SELECT * FROM file_kegiatan_harian WHERE id='".$id."' and id_user='".$id_user."'  LIMIT 1";
    $query=mysql_query($sqll) or die(mysql_error());
    
    if (mysql_num_rows($query)>0){
        $result = mysql_fetch_array($query);
        $content = $result['file_content'];
        $name   = $result['file_name'];
        $id_kegiatan_harian = $result['id_kegiatan_harian'];
        $dir_file = $dir.$name;
        
        if (($result[file_type] == 'image/png') or ($result[file_type] == 'image/jpeg') or ($result[file_type] == 'application/pdf')){
            header("Content-type: $result[file_type]");
           
        } else {
            header("Content-Disposition: attachment; filename=".$name);
            header("Content-length: ".$result['file_size']."");
            header("Content-type: $result[file_type]");
        }
        
        if (file_exists($dir_file)) {
            readfile($dir_file);
        } else {
            //echo $content;
        }
    }
}

}
?>