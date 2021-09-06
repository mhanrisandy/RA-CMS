<?php
session_start();
include "../../../config/koneksi.php";
if (empty($_SESSION['username'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Sesi login anda berhakhir<br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../../config/fungsi_notifbar.php";
include "../../../config/fungsi_file.php";
//include "../../../config/library.php";

$act=$_GET['act'];
$module=$_GET['module'];

// Hapus data
if ($act=='hapus'){
  query("DELETE FROM peg_data WHERE id='$_GET[id]'");
  
  session_start();
        $_SESSION[pesan] = 'Data berhasil dihapus';
        $_SESSION[c] = 'success';
        $_SESSION[i] = 'check';

  header('location:../../index.php?module='.$module);
}

// Input data
elseif ($act=='input'){
   $data=query("SELECT * FROM peg_data where NIK = '$_POST[NIK]' and NAMA = '$_POST[NAMA]'");
   if (mysqli_num_rows($data)>0){
        session_start();
        $_SESSION[pesan] = 'Gagal menyimpan data dengan NIK '. $_POST[NIK] .' dengan NAMA '. $_POST[NAMA] .', data sudah ada';
        $_SESSION[c] = 'danger';
        $_SESSION[i] = 'check';
   } else {
   query("INSERT INTO peg_data(
                               NO_KK,
                               NIK,
                               NAMA,
                               TEMPAT_LAHIR,
                               TANGGAL_LAHIR,
                               STATUS_PERKAWINAN,
                               JENIS_KELAMIN,
                               ALAMAT,
                               RT,
                               RW,
                               DISABILITAS
                                   ) 
					                VALUES(
                                 '$_POST[NO_KK]',
                                 '$_POST[NIK]',
                                 '$_POST[NAMA]',
                                 '$_POST[TEMPAT_LAHIR]',
                                 '$_POST[TANGGAL_LAHIR]',
                                 '$_POST[STATUS_PERKAWINAN]',
                                 '$_POST[JENIS_KELAMIN]',
                                 '$_POST[ALAMAT]',
                                 '$_POST[RT]',
                                 '$_POST[RW]',
                                 '$_POST[DISABILITAS]'
                                 )");

    filesimpan($_FILES['file'],"file_users",$_SESSION[username].'-');
                                
   }
  header('location:../../?module='.$module); 
}

// Update data
elseif ($act=='update'){
    $hasil = query("UPDATE peg_data SET   id_kelurahan = '$_POST[id_kelurahan]',
                                     id_relawan = '$_POST[id_relawan]',
                                     TPS  = '$_POST[TPS]',
                                     NO_KK  = '$_POST[NO_KK]',
                                     NIK  = '$_POST[NIK]',
                                     NAMA  = '$_POST[NAMA]',
                                     TEMPAT_LAHIR  = '$_POST[TEMPAT_LAHIR]',
                                     TANGGAL_LAHIR  = '$_POST[TANGGAL_LAHIR]',
                                     STATUS_PERKAWINAN  = '$_POST[STATUS_PERKAWINAN]',
                                     JENIS_KELAMIN  = '$_POST[JENIS_KELAMIN]',
                                     ALAMAT  = '$_POST[ALAMAT]',
                                     RT  = '$_POST[RT]',
                                     RW  = '$_POST[RW]',
                                     DISABILITAS  = '$_POST[DISABILITAS]',
                                     KETERANGAN  = '$_POST[KETERANGAN]'

                                    WHERE id = '$_POST[id]'
									");
    if ($hasil) {
        set_notif('Berhasil mengubah data','check','success');
    } else {
        set_notif('Gagal mengubah data','check','danger');
    }
  header('location:../../?module='.$module);
}

elseif ($act=='nik'){
    $nik = str_replace("+"," ",$_GET['nik']);
    $nik_bintang = substr($nik,0,6) . '*****' . substr($nik,11,16);
    //$cekdata = query("SELECT * FROM peg_data WHERE NIK = '$nik' or NIK = '$nik_bintang'");
    //if (mysqli_num_rows($cekdata)==0){ 
    $panjang_data = strlen($nik);
    if ( $panjang_data > 5) {
        $dpt = query("SELECT * FROM ref_dpt WHERE NIK = '$nik' or NIK = '$nik_bintang' 
                                                        or NO_KK = '$nik' or NO_KK = '$nik_bintang'
                                                        or NAMA LIKE '%$nik%'");
        $d=mysqli_fetch_array($dpt);
        if (mysqli_num_rows($dpt)==0){
            echo "<table class=\"table\">
    			  <tbody>
    			  <tr><td class='left' width='120'></td>  <td>Ups system tidak menemukan dpt yg dicari</tr>
    			  </tbody>
    			  </table>
    			  ";
            
        } elseif (mysqli_num_rows($dpt)==1) {
            $cekdata = query("SELECT * FROM peg_data WHERE NIK = '$d[NIK]' and NAMA = '$d[NAMA]'");
            if (mysqli_num_rows($cekdata)<>0){ 
                  echo "<table class=\"table\">
    			  <tbody>
     			  <tr><td class='left' width='120'></td>  <td>System menemukan data yg dicari sudah di input</tr>
    			  </tbody>
    			  </table>
    			  ";
            } else {    
                  echo "
                  <table class=\"table\">
    			  <tbody>
    				      <tr><td class='left' width='120'>Kecamatan</td>  <td>  
    				          <select id='id_kecamatan' class='form-control' name='id_kecamatan'>
                                          <option></option>";
                                    if ($_SESSION['leveluser']=='admin'){      
                                        $data = query("select * from ref_kecamatan order by NAMA");  
                                    } else {
                                        $data = query("select * from ref_kecamatan where id = '$_SESSION[id_kecamatan]' order by NAMA");   
                                    }
                                    if (mysqli_num_rows($data)>0){ 
                                        while ($row = mysql_fetch_assoc($data)){ 
                                		echo"<option value='$row[id]'>$row[NAMA]</option>";
                                		} 
                                	} 
                          echo"</select>
    				      </td></tr>
    				      <tr><td class='left' width='120'>Kelurahan</td>  <td>  
    				      <div id='load_kelurahan'>
    				          <select id='id_kelurahan' class='form-control' name='id_kelurahan' required>";
    				                $data = query("select * from ref_kelurahan WHERE id = '$d[id_kelurahan]'");
    				                $k = mysql_fetch_assoc($data);
    				            echo"<option value='$d[id_kelurahan]'>$k[NAMA]</option>
                              </select>
                          </div>
    				      </td></tr>
    				      <tr><td class='left' width='120'>TPS</td>  <td>  
            				      <div id='load_tps'>
            				          <input type=text name='TPS' class='form-control' value='$d[TPS]'>
                                  </div>
            				      </td></tr>
    				      <tr><td class='left' width='120'>Relawan</td>  <td>
    				          <select class='form-control select2bs4 select2-hidden-accessible' style='width: 100%;' data-select2-id='17' tabindex='-1' aria-hidden='true' name='id_relawan' required>
                                    <option selected='selected' value=''>-Pilih-</option>";
                                    $data = query("select * from peg_relawan WHERE username = '$_SESSION[namauser]'
                                                                  order by NAMA");    
                                    if (mysqli_num_rows($data)>0){ 
                                        while ($e = mysql_fetch_assoc($data)){ 
                                		echo"<option value='$e[id]'>$e[NAMA]</option>";
                                		} 
                                	} 
                                    echo "
                                </select>
    				      </td></tr>
                          
    					  
    					      <tr><td class='left' width='120'>NIK</td>  <td>  <input id='NIK' type=text name='NIK' class='form-control' value='$d[NIK]' readonly></td></tr>
        					  <tr><td class='left' width='120'>No KK</td>  <td>  <input type=text name='NO_KK' class='form-control' value='$d[NO_KK]'></td></tr>
        					  <tr><td class='left' width='120'>Nama</td>  <td>  <input type=text name='NAMA' class='form-control' value='$d[NAMA]' readonly></td></tr>
        					  <tr><td class='left' width='120'>Tempat lahir</td>  <td>  <input type=text name='TEMPAT_LAHIR' class='form-control' value='$d[TEMPAT_LAHIR]'></td></tr>
        					  <tr><td class='left' width='120'>Tanggal lahir</td>  <td>  <input type=date name='TANGGAL_LAHIR' class='form-control' value='$d[TANGGAL_LAHIR]'></td></tr>
        					  <tr><td class='left' width='120'>Status perkawinan</td>  <td>  
        					  <select class='form-control' name='STATUS_PERKAWINAN'>
        					        <option value='$d[STATUS_PERKAWINAN]'>$d[STATUS_PERKAWINAN]</option>
                                    <option value='B'>Belum Kawin</option>
                                    <option value='S'>Kawin</option>
                                     <option value='P'>Pernah Kawin</option> 
                              </select>
        					  </td></tr>
        					  <tr><td class='left' width='120'>Jenis kelamin</td>  <td>  
        					  <select class='form-control' name='JENIS_KELAMIN'>
        					        <option value='$d[JENIS_KELAMIN]'>$d[JENIS_KELAMIN]</option>
                                    <option value='L'>Laki-laki</option>
                                    <option value='P'>Perempuan</option> 
                              </select>
        					  </td></tr>
        					  <tr><td class='left' width='120'>Alamat</td>  <td>  <input type=text name='ALAMAT' class='form-control' value='$d[ALAMAT]'></td></tr>
        					  <tr><td class='left' width='120'>RT</td>  <td>  <input type=text name='RT' class='form-control' value='$d[RT]'></td></tr>
        					  <tr><td class='left' width='120'>RW</td>  <td>  <input type=text name='RW' class='form-control' value='$d[RW]'></td></tr>
        					  <tr><td class='left' width='120'>Disabilitas</td>  <td>  
        					  <select class='form-control' name='DISABILITAS'>
        					        <option value='$d[DISABILITAS]'>$d[DISABILITAS]</option>
                                    <option value='1'>Tuna Daksa</option>
                                    <option value='2'>Tuna Netra</option> 
                                    <option value='3'>Tuna Rungu/Wicara</option> 
                                    <option value='4'>Tuna Grahita</option> 
                                    <option value='5'>Tuna Disabilitas Lainnya</option> 
                              </select>
                              </td></tr>
        					  <tr><td class='left' width='120'>Keterangan</td>  <td>  <input type=text name='KETERANGAN' class='form-control' value='$d[KETERANGAN]'></td></tr>
                           
    					  <tr><td class='left'></td><td class='left' colspan=2><input class=\"btn btn-primary\" type=submit value=Simpan>
    					  <input class=\"btn btn-default\" type=button value=Batal onclick=self.history.back()></td></tr>
    				      </tbody>
    				      </table>
    					  "; 
            }
        } else {
          echo "<table class=\"table\">
          <thead>
          <tr>
              <th class='left'>No</th>
              <th class='left'>No KK</th>
              <th class='left'>NIK</th>
              <th class='left'>Nama</th>
              <th class='left'></th>
          </tr>
          </thead><tbody>";
            $tampil=query("SELECT * FROM ref_dpt WHERE NIK = '$nik' or NIK = '$nik_bintang' 
                                                        or NO_KK = '$nik' or NO_KK = '$nik_bintang'
                                                        or NAMA LIKE '%$nik%'");
            $no = 1;
            while ($r=mysqli_fetch_array($tampil)){
            $NAMA = str_replace(" ","+",$r['NAMA']);    
            echo"<tr><td class='left' width='25'>$no</td>
                            <td class='left'>$r[NO_KK]</td>
                            <td class='left'>$r[NIK]</td>
                            <td class='left'>$r[NAMA]</td>
                            <td class='left'><span href=\"modul/mod_data/aksi_data.php?module=data&act=pilih_nik&nik=$r[NIK]&nama=$NAMA\" class=\"btn btn-success\">Pilih</span></td>
                </tr>";
                $no++;
            }
            echo "</tbody></table>";
        }
  //  } else {
  //      echo "<table class=\"table\">
  //  			  <tbody>
   //  			  <tr><td class='left' width='120'></td>  <td>System menemukan data yg dicari sudah di input</tr>
   // 			  </tbody>
   // 			  </table>
   // 			  ";
   // }
    } else {
        echo "<table class=\"table\">
    			  <tbody>
     			  <tr><td class='left' width='120'></td>  <td>Masukan panjang data lebih dari 5 karakter</tr>
     			  </tbody>
    			  </table>
    			  ";
    }
}

elseif ($act=='pilih_nik'){
    $nik = str_replace("+"," ",$_GET['nik']);
    $nama = str_replace("+"," ",$_GET['nama']);
    
    $cekdata = query("SELECT * FROM peg_data WHERE NIK = '$nik' and NAMA = '$nama'");
    if (mysqli_num_rows($cekdata)<>0){ 
       echo "<table class=\"table\">
    			  <tbody>
     			  <tr><td class='left' width='120'></td>  <td>System menemukan data sudah di input</tr>
    			  </tbody>
    			  </table>
    			  ";
    } else {    
        $dpt = query("SELECT * FROM ref_dpt WHERE NIK = '$nik' and NAMA = '$nama'");
        $d=mysqli_fetch_array($dpt);
        echo "    <table class=\"table\">
    			  <tbody>
    				      <tr><td class='left' width='120'>Kecamatan</td>  <td>  
    				          <select id='id_kecamatan' class='form-control' name='id_kecamatan'>
                                          <option></option>";
                                    if ($_SESSION['leveluser']=='admin'){      
                                        $data = query("select * from ref_kecamatan order by NAMA");  
                                    } else {
                                        $data = query("select * from ref_kecamatan where id = '$_SESSION[id_kecamatan]' order by NAMA");   
                                    }
                                    if (mysqli_num_rows($data)>0){ 
                                        while ($row = mysql_fetch_assoc($data)){ 
                                		echo"<option value='$row[id]'>$row[NAMA]</option>";
                                		} 
                                	} 
                          echo"</select>
    				      </td></tr>
    				      <tr><td class='left' width='120'>Kelurahan</td>  <td>  
    				      <div id='load_kelurahan'>
    				          <select id='id_kelurahan' class='form-control' name='id_kelurahan' required>";
    				                $data = query("select * from ref_kelurahan WHERE id = '$d[id_kelurahan]'");
    				                $k = mysql_fetch_assoc($data);
    				            echo"<option value='$d[id_kelurahan]'>$k[NAMA]</option>
                              </select>
                          </div>
    				      </td></tr>
    				      <tr><td class='left' width='120'>TPS</td>  <td>  
            				      <div id='load_tps'>
            				          <input type=text name='TPS' class='form-control' value='$d[TPS]'>
                                  </div>
            				      </td></tr>
    				      <tr><td class='left' width='120'>Relawan</td>  <td>
    				          <select class='form-control select2bs4 select2-hidden-accessible' style='width: 100%;' data-select2-id='17' tabindex='-1' aria-hidden='true' name='id_relawan' required>
                                    <option selected='selected' value=''>-Pilih-</option>";
                                    $data = query("select * from peg_relawan WHERE username = '$_SESSION[namauser]'
                                                                  order by NAMA");    
                                    if (mysqli_num_rows($data)>0){ 
                                        while ($e = mysql_fetch_assoc($data)){ 
                                		echo"<option value='$e[id]'>$e[NAMA]</option>";
                                		} 
                                	} 
                                    echo "
                                </select>
    				      </td></tr>
                          
    					  
    					      <tr><td class='left' width='120'>NIK</td>  <td>  <input id='NIK' type=text name='NIK' class='form-control' value='$d[NIK]' readonly></td></tr>
        					  <tr><td class='left' width='120'>No KK</td>  <td>  <input type=text name='NO_KK' class='form-control' value='$d[NO_KK]'></td></tr>
        					  <tr><td class='left' width='120'>Nama</td>  <td>  <input type=text name='NAMA' class='form-control' value='$d[NAMA]' readonly></td></tr>
        					  <tr><td class='left' width='120'>Tempat lahir</td>  <td>  <input type=text name='TEMPAT_LAHIR' class='form-control' value='$d[TEMPAT_LAHIR]'></td></tr>
        					  <tr><td class='left' width='120'>Tanggal lahir</td>  <td>  <input type=date name='TANGGAL_LAHIR' class='form-control' value='$d[TANGGAL_LAHIR]'></td></tr>
        					  <tr><td class='left' width='120'>Status perkawinan</td>  <td>  
        					  <select class='form-control' name='STATUS_PERKAWINAN'>
        					        <option value='$d[STATUS_PERKAWINAN]'>$d[STATUS_PERKAWINAN]</option>
                                    <option value='B'>Belum Kawin</option>
                                    <option value='S'>Kawin</option>
                                     <option value='P'>Pernah Kawin</option> 
                              </select>
        					  </td></tr>
        					  <tr><td class='left' width='120'>Jenis kelamin</td>  <td>  
        					  <select class='form-control' name='JENIS_KELAMIN'>
        					        <option value='$d[JENIS_KELAMIN]'>$d[JENIS_KELAMIN]</option>
                                    <option value='L'>Laki-laki</option>
                                    <option value='P'>Perempuan</option> 
                              </select>
        					  </td></tr>
        					  <tr><td class='left' width='120'>Alamat</td>  <td>  <input type=text name='ALAMAT' class='form-control' value='$d[ALAMAT]'></td></tr>
        					  <tr><td class='left' width='120'>RT</td>  <td>  <input type=text name='RT' class='form-control' value='$d[RT]'></td></tr>
        					  <tr><td class='left' width='120'>RW</td>  <td>  <input type=text name='RW' class='form-control' value='$d[RW]'></td></tr>
        					  <tr><td class='left' width='120'>Disabilitas</td>  <td>  
        					  <select class='form-control' name='DISABILITAS'>
        					        <option value='$d[DISABILITAS]'>$d[DISABILITAS]</option>
                                    <option value='1'>Tuna Daksa</option>
                                    <option value='2'>Tuna Netra</option> 
                                    <option value='3'>Tuna Rungu/Wicara</option> 
                                    <option value='4'>Tuna Grahita</option> 
                                    <option value='5'>Tuna Disabilitas Lainnya</option> 
                              </select>
                              </td></tr>
        					  <tr><td class='left' width='120'>Keterangan</td>  <td>  <input type=text name='KETERANGAN' class='form-control' value='$d[KETERANGAN]'></td></tr>
                           
    					  <tr><td class='left'></td><td class='left' colspan=2><input class=\"btn btn-primary\" type=submit value=Simpan>
    					  <input class=\"btn btn-default\" type=button value=Batal onclick=self.history.back()></td></tr>
    				      </tbody>
    				      </table>
    					  ";
    }
}

elseif ($act=='tps'){
               echo "<tr><td class='left' width='120'>Kecamatan</td>  <td>  
				          <select id='id_kecamatan' class='form-control' name='id_kecamatan'>
                                      <option></option>";
                                $data = query("select * from ref_kecamatan order by NAMA");    
                                if (mysqli_num_rows($data)>0){ 
                                    while ($row = mysql_fetch_assoc($data)){ 
                            		echo"<option value='$row[id]'>$row[NAMA]</option>";
                            		} 
                            	} 
                      echo"</select>
				      </td></tr>
				      <tr><td class='left' width='120'>Kelurahan</td>  <td>  
				      <div id='load_kelurahan'>
				          <select id='id_kelurahan' class='form-control' name='id_kelurahan'>
                                      <option></option>
                          </select>
                      </div>
				      </td></tr>
				      <tr><td class='left' width='120'>TPS</td>  <td>  
				      <div id='load_tps'>
				          <select id='id_tps' class='form-control' name='id_tps' required>
                                      <option></option>
                          </select>
                      </div>
				      </td></tr>";
}


elseif ($act=='kecamatan'){
    $id = str_replace("+"," ",$_GET['id']);
    $data = query("SELECT * FROM ref_kelurahan WHERE id_kecamatan = '$id'");
    
    echo "<select id='id_kelurahan' class='form-control' name='id_kelurahan' required>
                                      <option></option>";
                                if (mysqli_num_rows($data)>0){ 
                                    while ($row = mysql_fetch_assoc($data)){ 
                            		echo"<option value='$row[id]'>$row[NAMA]</option>";
                            		} 
                            	} 
    echo"</select>";
}

elseif ($act=='kelurahan'){
    $id = str_replace("+"," ",$_GET['id']);
    $data = query("SELECT * FROM ref_tps WHERE id_kelurahan = '$id'");
    
    echo "<select id='id_tps' class='form-control' name='id_tps' required>
                                      <option></option>";
                                if (mysqli_num_rows($data)>0){ 
                                    while ($row = mysql_fetch_assoc($data)){ 
                            		echo"<option value='$row[id]'>$row[NAMA]</option>";
                            		} 
                            	} 
    echo"</select>";
}

elseif ($act=='relawan'){
    $id = str_replace("+"," ",$_GET['id']);
    $data = query("SELECT * FROM peg_relawan WHERE id_kelurahan = '$id'");
    
    echo "<select id='id_relawan' class='form-control' name='id_relawan' required>
                                      <option></option>";
                                if (mysqli_num_rows($data)>0){ 
                                    while ($row = mysql_fetch_assoc($data)){ 
                            		echo"<option value='$row[id]'>$row[NAMA]</option>";
                            		} 
                            	} 
    echo"</select>";
}

// import
elseif ($act=='import'){
    
    // upload file xls
    $target = basename($_FILES['fileimport']['name']);
    move_uploaded_file($_FILES['fileimport']['tmp_name'], $target);
     
    // beri permisi agar file xls dapat di baca
    chmod($_FILES['fileimport']['name'],0777);
     
    // mengambil isi file xls
    $data = new Spreadsheet_Excel_Reader($_FILES['fileimport']['name'],false);
    // menghitung jumlah baris data yang ada
    $jumlah_baris = $data->rowcount($sheet_index=0);
     
    // jumlah default data yang berhasil di import
    $berhasil = 0;
    $gagal = 0;
    $jumlah = 0;
    $pesan = '';
    for ($i=2; $i<=$jumlah_baris; $i++){
    	// menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
        $id_kelurahan = $_POST['id_kelurahan'];
        $id_relawan = $_POST['id_relawan'];
        $NIK        = $data->val($i, 1);
        $nik_bintang = substr($NIK,0,6) . '*****' . substr($NIK,11,16);
        
    	if($NIK != ""){
    		$cek=query("SELECT * FROM ref_dpt where NIK = '$NIK' or NIK = '$nik_bintang'");
            if (mysqli_num_rows($cek)>0){
                $cekdata=query("SELECT * FROM peg_data where NIK = '$NIK' or NIK = '$nik_bintang'");
                if (mysqli_num_rows($cekdata)>0){
                    $jumlah++;
                    $pesan = $pesan . '<a href="media.php?module=data&kata='.$NIK.'" target="_blank">'.$NIK .' '.$NAMA.'</a><br>';
                } else {    
                    $d=mysqli_fetch_array($cek);
        	        query("INSERT INTO peg_data(
        	                            username,
        	                            id_relawan,
                                        id_kelurahan,
                                        NO_KK,
                                        NIK,
                                        NAMA,
                                        TEMPAT_LAHIR,
                                        TANGGAL_LAHIR,
                                        STATUS_PERKAWINAN,
                                        JENIS_KELAMIN,
                                        ALAMAT,
                                        RT,
                                        RW,
                                        DISABILITAS,
                                        KETERANGAN,
                                        TPS
                                       ) 
    					                VALUES(
    					                '$_SESSION[namauser]',  
    					                '$id_relawan',
                                        '$id_kelurahan',
                                        '$d[NO_KK]',
                                        '$d[NIK]',
                                        '$d[NAMA]',
                                        '$d[TEMPAT_LAHIR]',
                                        '$d[TANGGAL_LAHIR]',
                                        '$d[STATUS_PERKAWINAN]',
                                        '$d[JENIS_KELAMIN]',
                                        '$d[ALAMAT]',
                                        '$d[RT]',
                                        '$d[RW]',
                                        '$d[DISABILITAS]',
                                        '$d[KETERANGAN]',
                                        '$d[TPS]'
                                     )");
                $berhasil++;                     
                }
            } else {
                $gagal++;
            }    
    	    
    	}
    }
    //pesan
    session_start();
    $_SESSION[pesan] = $berhasil .' berhasil, '.$gagal.' dpt tidak ditemukan dan '.$jumlah.' gagal karna data sudah ada<br><hr>'.$pesan;
    $_SESSION[c] = 'danger';
    $_SESSION[i] = 'check';
     
    // hapus kembali file .xls yang di upload tadi
    unlink($_FILES['fileimport']['name']);
    // alihkan halaman ke index.php
    header('location:../../media.php?module='.$module);
}

}
?>
<script>
    $(document).ready(function(){
        $('#id_kecamatan').on('change',function(){
           var str = $('#id_kecamatan').val();
           var res = str.replace(/\ /g, "+");    
           $('#load_kelurahan').load('modul/mod_data/aksi_data.php?module=data&act=kecamatan&id=' + res);
        });
        $('#CARI_NIK').on('change',function(){
           var str = $('#CARI_NIK').val();
           var res = str.replace(/\ /g, "+");    
           $('#LOAD_DPT').load('modul/mod_data/aksi_data.php?module=data&act=nik&nik=' + res);
        });
         $('span').click(function(){
           var link = $(this).attr("href");
           $('#LOAD_DPT').load(link);
        });
    });
</script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
</script>
