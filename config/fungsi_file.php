<?php   
    function resizeImage($resourceType,$image_width,$image_height) {
        $resizeWidth = 1024;
        $persen = ($resizeWidth * 100) / $image_width;
        $resizeHeight = ($persen / 100) * $image_height;
        $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
        imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
        return $imageLayer;
    }

    function fileinsert($file,$table,$oldstr,$str){
        global $koneksi;
        $temp = '../../../images/temp/';
        $dir  = '../../../images/'.$table.'/';
        //cek temp
        if(!is_dir($temp)){
            mkdir($temp);       
        }
        //cek dir
        if(!is_dir($dir)){
            mkdir($dir);       
        }
        //cek table mysql
        $numrow = numrowquery("SELECT * FROM information_schema.tables WHERE table_name = '$table' LIMIT 1");
        if($numrow==0){
            query("CREATE TABLE `$table` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `file_content` longblob NULL,
                `file_name` varchar(255) NULL,
                `file_type` varchar(255) NULL,
                `file_size` bigint(20) NULL DEFAULT 0,
                PRIMARY KEY (id)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        }

        //menyimpan ke blob
        $file_name = $file['name'];
        $tmp_name  = $file['tmp_name'];
        $file_size = $file['size'];
        $file_type = $file['type'];
        
        $ekstensi_file = explode('.', $file_name);
        $ekstensi_file =  strtolower(end($ekstensi_file));
        //nama file
        $file_name = $str . uniqid() . '.'.$ekstensi_file;
        
        if ($file_size > 0 and ($file_type =='image/jpeg' or $file_type == 'image/png')){
            $sourceProperties = getimagesize($tmp_name);
            $resizeFileName = date("Ymdhisa") . time() . rand();
            $resizeuploadPath = $temp;
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
            $file_content = mysqli_real_escape_string($koneksi, $file_content) or die("Error: cannot read file");
            fclose($fp);
            
            $cek = "SELECT * FROM $table WHERE file_name = '$oldstr'";
            if(numrowquery($cek)==0){
                $hasil = query("INSERT INTO $table (`file_content`,`file_name`,`file_type`,`file_size`)
                                        VALUES
                                                ('".$file_content."','".$file_name."','".$file_type."','".$file_size."')") 
                    or die ("Sorry Cant insert db!");
            } else {
                $hasil =  query("UPDATE $table SET `file_content` = '$file_content',
                                                            `file_name` = '$file_name',
                                                            `file_type` = '$file_type',
                                                            `file_size` = '$file_size'
                                                             WHERE file_name = '$oldstr'");  
            }

            unlink($newfile);
        } else {
            $ekstensifilevalid = ['pdf','doc','docx','xls','xlsx','rar'];
            if(in_array($ekstensi_file, $ekstensifilevalid)){ 
                if ($file_size > 0 and $file_size <= 1000000){
                    $fp = fopen($tmp_name, 'r');
                    $file_content = fread($fp, $file_size) or die("Error: cannot read file");
                    $file_content = mysqli_real_escape_string($koneksi, $file_content) or die("Error: cannot read file");
                    fclose($fp);
                    
                    $cek = "SELECT * FROM $table WHERE file_name = '$oldstr'";
                    if(numrowquery($cek)==0){
                        $hasil = query("INSERT INTO $table (`file_content`,`file_name`,`file_type`,`file_size`)
                                                VALUES ('".$file_content."','".$file_name."','".$file_type."','".$file_size."')") 
                            or die ("Sorry Cant insert db!");
                    } else {
                        $hasil =  query("UPDATE $table SET `file_content` = '$file_content',
                                                            `file_name` = '$file_name',
                                                            `file_type` = '$file_type',
                                                            `file_size` = '$file_size'
                                                             WHERE file_name = '$oldstr'");  
                    }
                }
            }
        }
        
        //menyimpan blob ke file
        if ($hasil){ 
            //hapus file yang lama
            if (file_exists($dir.$oldstr)) {
                unlink($dir.$oldstr);
             }

            $query   = query("SELECT * FROM $table WHERE file_name='$file_name'");
            if (mysqli_num_rows($query)>0){ 
                $result  = mysqli_fetch_array($query);
                $id_file = $result['id'];
                $content = $result['file_content'];
                $name = $result['file_name'];
                
                $berhasil = file_put_contents($dir.$name, $content);
                if($berhasil){
                    //hapus blob
                    query("UPDATE $table SET file_content  = NULL
                                        WHERE id = '$id_file'");
                } 
            }
        } 

        $result = $file_name;
        return $result;
	}

    function filedelete($table,$oldstr){
        query("DELETE FROM $table WHERE file_name='$oldstr'");
        $dir  = '../../../images/'.$table.'/';
        if (file_exists($dir.$oldstr)) {
            unlink($dir.$oldstr);
        }
    }

    function fileview($table,$oldstr){     
        $dir   = '../../../images/'.$table.'/';

        $query = query("SELECT * FROM $table WHERE file_name='$oldstr'") or die(mysql_error());
        if (mysqli_num_rows($query)>0){
            $result = mysqli_fetch_array($query);
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
?>