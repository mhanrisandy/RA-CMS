<?php
include "../config/fungsi_paging.php";
include "../config/fungsi_notifbar.php";

  if ($_SESSION['level']=='admin' OR $_SESSION['level']=='user'){  
    $aksi="modul/mod_data/aksi_data.php";
    
    $act = '';
    if (!empty($_GET['act'])){
    	$act = $_GET['act'];
    } 

    switch($act){

    default:
    echo "<section class=\"col-lg-12 connectedSortable\">";
    show_notif_swal();
    echo"
	        <div class=\"card card-primary card-outline\">
            <div class=\"card-header\">
                
			    <input class=\"btn btn-default\" type=button value='Tambah' onclick=location.href='?module=data&act=tambah'>
			    <input class=\"btn btn-success\" type=button value='Import' onclick=location.href='?module=data&act=import'> 
			  
			    <div class=\"card-tools\">
					<form method=get action='$_SERVER[PHP_SELF]'> 
						<div class=\"input-group input-group\" style=\"width: 300px;\">
							<input type=hidden name=module value=data>
							<input type=\"text\" name=\"kata\" class=\"form-control float-right\" placeholder=\"Cari\">

							<div class=\"input-group-append\">
							  <button type=\"submit\" class=\"btn btn-default\"><i class=\"fas fa-search\"></i></button>
							</div>
						</div>
					</form>                  
                </div>
		    </div>";
			if (empty($_GET['kata'])){	 
			echo" 
		          <div class=\"card-body table-responsive p-0\">
				  <table class=\"table\">
				  <thead>
		          <tr>
				  <th class='left'>No</th>
				  <th class='left'>No KK</th>
				  <th class='left'>NIK</th>
				  <th class='left'>Nama</th>
				  <th class='left'>Tempat lahir</th>
				  <th class='left'>Tanggal lahir</th>
				  <th class='left'>Status perkawinan</th>
				  <th class='left'>Jenis kelamin</th>
				  <th class='left'>Alamat</th>
				  <th class='left'>Rt</th>
				  <th class='left'>Rw</th>
				  <th class='left'>Disabilitas</th>
				  <th class='left'>Keterangan</th>
		          <th style=\"width: 14%\"></th>
		          </tr></thead><tbody>";

		    $p      = new Paging;
		    $batas  = 10;
		    $posisi = $p->cariPosisi($batas);

		    $tampil= query("SELECT * FROM peg_data ORDER BY id DESC LIMIT $posisi,$batas");
		    

		    $no = $posisi+1;
		    while ($r=mysqli_fetch_array($tampil)){
		echo"<td class='left' width='25'>$no</td>
						<td class='left'>$r[NO_KK]</td>
						<td class='left'>$r[NIK]</td>
						<td class='left'>$r[NAMA]</td>
						<td class='left'>$r[TEMPAT_LAHIR]</td>
						<td class='left'>$r[TANGGAL_LAHIR]</td>
						<td class='left'>$r[STATUS_PERKAWINAN]</td>
						<td class='left'>$r[JENIS_KELAMIN]</td>
						<td class='left'>$r[ALAMAT]</td>
						<td class='left'>$r[RT]</td>
						<td class='left'>$r[RW]</td>
						<td class='left'>$r[DISABILITAS]</td>
						<td class='left'>$r[KETERANGAN]</td>

						<td class=\"project-actions text-right\">
						<a class=\"btn btn-primary\" href=?module=data&act=edit&id=$r[id]>
						    <i class=\"fas fa-pencil-alt\">
		                    </i>
		                    Edit</a>";
		            if ($_SESSION['level']=='admin'){        
				   echo "
						<a class=\"btn btn-danger\" href=$aksi?module=data&act=hapus&id=$r[id] onClick=\"confirmation(event)\">
						    <i class=\"fas fa-trash\">
		                    </i>
		                    Hapus</a>";
		            } echo "  
						</td>
				        </tr>";
		      $no++;
		    }
		    echo "</tbody></table></div> 
			      ";

		    $jmldata = mysqli_num_rows(query("SELECT * FROM peg_data"));
		    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
		    $linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman);

		echo "<div class=\"card-footer\" width='150'>
		      $linkHalaman
			 </div>
			 ";

		    break;    
			}else{
			echo" 
		          <div class=\"card-body table-responsive p-0\">
				  <table class=\"table\">
				  <thead>
		          <tr><th class='left'>No</th>
		          <th class='left'>No KK</th>
		          <th class='left'>NIK</th>
		          <th class='left'>Nama</th>
		          <th class='left'>Tempat lahir</th>
		          <th class='left'>Tanggal lahir</th>
		          <th class='left'>Status perkawinan</th>
		          <th class='left'>Jenis kelamin</th>
		          <th class='left'>Alamat</th>
		          <th class='left'>Rt</th>
		          <th class='left'>Rw</th>
		          <th class='left'>Disabilitas</th>
		          <th class='left'>Keterangan</th>
				  <th style=\"width: 14%\"></th>
		          </tr></thead><tbody>";

		    $p      = new Paging9;
		    $batas  = 10;
		    $posisi = $p->cariPosisi($batas);
		    $tampil=query("SELECT * FROM peg_data where NAMA LIKE '%$_GET[kata]%' or NIK = '$_GET[kata]'
			                       ORDER BY id DESC LIMIT $posisi,$batas");


		    $no = $posisi+1;
		    while ($r=mysqli_fetch_array($tampil)){
		      echo "<tr><td class='left' width='25'>$no</td>
						<td class='left'>$r[NO_KK]</td>
						<td class='left'>$r[NIK]</td>
						<td class='left'>$r[NAMA]</td>
						<td class='left'>$r[TEMPAT_LAHIR]</td>
						<td class='left'>$r[TANGGAL_LAHIR]</td>
						<td class='left'>$r[STATUS_PERKAWINAN]</td>
						<td class='left'>$r[JENIS_KELAMIN]</td>
						<td class='left'>$r[ALAMAT]</td>
						<td class='left'>$r[RT]</td>
						<td class='left'>$r[RW]</td>
						<td class='left'>$r[DISABILITAS]</td>
						<td class='left'>$r[KETERANGAN]</td>

						<td class=\"project-actions text-right\">
						<a class=\"btn btn-primary\" href=?module=data&act=editdata&id=$r[id]>
						    <i class=\"fas fa-pencil-alt\">
		                    </i>
		                    Edit</a>";
		            if ($_SESSION['level']=='admin'){        
				   echo "
						<a class=\"btn btn-danger\" href=$aksi?module=data&act=hapus&id=$r[id] onClick=\"confirmation(event)\">
						    <i class=\"fas fa-trash\">
		                    </i>
		                    Hapus</a>";
		            } echo "  
						</td>
				    </tr>";
		      $no++;
		    }
		    echo "</tbody></table></div>";

		    $jmldata = mysqli_num_rows(query("SELECT * FROM peg_data where NAMA LIKE '%$_GET[kata]%' or NIK = '$_GET[kata]'"));
		    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
		    $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);

		echo "<div class=\"card-footer\" width='150'>
		      $linkHalaman
			 </div>";
		echo "</div>
			      </section>";
		    } 
    break;
	
	
	
  case "tambah":
    echo "<section class=\"col-lg-12 connectedSortable\">
          <div class=\"card card-primary card-outline\">
				<div class=\"card-header\">
					<h3 class=\"card-title\">
					  <i class=\"fas fa-plus mr-1\"></i>
					  Tambah
					</h3>
				</div>
               <table class=\"table\">
			   <tbody>
               <tr><td class='left' width='120'>Cari</td>  <td>  <input id='CARI_NIK' type=text name='CARI_NIK' class='form-control' required></td></tr>
                </tbody>
			   </table>
			   <form method=POST action=$aksi?module=data&act=input>
               <div id='LOAD_DPT'>
				      
			   </div>
			   </form>
		</div>
        </section>";
    break;
  

  case "edit":
    $edit = query("SELECT * FROM peg_data WHERE id='$_GET[id]'");
    $r    = mysqli_fetch_array($edit);

    echo "<section class=\"col-lg-12 connectedSortable\">
          <div class=\"card card-primary card-outline\">
				<div class=\"card-header\">
					<h3 class=\"card-title\">
					  <i class=\"fas fa-edit mr-1\"></i>
					  Edit
					</h3>
				</div>
				
				  <form method=POST action=$aksi?module=data&act=update>
					  <input type=hidden name=id value=$r[id]>
					  <table class=\"table\">
						  <tbody>
						      
						      <tr><td class='left' width='120'>Wilayah</td>  <td>";       
						       $data = query("select ref_kelurahan.NAMA as KELURAHAN, ref_kecamatan.NAMA as KECAMATAN from ref_kelurahan 
						       left JOIN ref_kecamatan on (ref_kelurahan.id_kecamatan = ref_kecamatan.id) where ref_kelurahan.id ='$r[id_kelurahan]'");
                               $d    = mysqli_fetch_array($data);
                               echo "$d[KELURAHAN], $d[KECAMATAN]";
						 echo"</td></tr>
						     
						     <tr><td class='left' width='120'>Kecamatan</td>  <td>  
				             <select id='id_kecamatan' class='form-control' name='id_kecamatan'>
                                      <option>$d[KECAMATAN]</option>
                                      <option></option>";
                                if ($_SESSION['level']=='admin'){      
                                    $data = query("select * from ref_kecamatan order by NAMA");  
                                } else {
                                    $data = query("select * from ref_kecamatan where id = '$_SESSION[id_kecamatan]' order by NAMA");   
                                }  
                                if (mysqli_num_rows($data)>0){ 
                                    while ($row = mysqli_fetch_assoc($data)){ 
                            		echo"<option value='$row[id]'>$row[NAMA]</option>";
                            		} 
                            	} 
                              echo"</select>
        				      </td></tr>
        				      <tr><td class='left' width='120'>Kelurahan</td>  <td>  
        				      <div id='load_kelurahan'>
        				          <select id='id_kelurahan' class='form-control' name='id_kelurahan' required>
                                              <option value='$r[id_kelurahan]'>$d[KELURAHAN]</option>
                                  </select>
                              </div>
        				      </td></tr>
        				      <tr><td class='left' width='120'>TPS</td>  <td>  
        				      <div id='load_tps'>
        				          <input type=text name='TPS' class='form-control' value='$r[TPS]'>
                              </div>
        				      </td></tr>
        				      <tr><td class='left' width='120'>Relawan</td>  <td>
        				          <select class='form-control select2bs4 select2-hidden-accessible' style='width: 100%;' data-select2-id='17' tabindex='-1' aria-hidden='true' name='id_relawan' required>";
        				                $data = query("select * from peg_relawan WHERE 
                                                                            id = '$r[id_relawan]' LIMIT 1");
                                        $c = mysqli_fetch_assoc($data);                                    
                                        echo"<option selected='selected' value='$r[id_relawan]'>$c[NAMA]</option>";
                                        
                                        $data = query("select * from peg_relawan 
                                                                      order by NAMA");    
                                        if (mysqli_num_rows($data)>0){ 
                                            while ($e = mysqli_fetch_assoc($data)){ 
                                    		echo"<option value='$e[id]'>$e[NAMA]</option>";
                                    		} 
                                    	} 
                                        echo "
                                    </select>
        				      </td></tr>
				      
						      <tr><td class='left' width='120'>No KK</td>  <td>       <input type=text name='NO_KK' class='form-control' value='$r[NO_KK]'></td></tr>
							  <tr><td class='left' width='120'>NIK</td>  <td>       <input type=text name='NIK' class='form-control' value='$r[NIK]' readonly></td></tr>
							  <tr><td class='left' width='120'>Nama</td>  <td>       <input type=text name='NAMA' class='form-control' value='$r[NAMA]' required></td></tr>
							  <tr><td class='left' width='120'>Tempat lahir</td>  <td>       <input type=text name='TEMPAT_LAHIR' class='form-control' value='$r[TEMPAT_LAHIR]'></td></tr>
							  <tr><td class='left' width='120'>Tanggal lahir</td>  <td><input type=date name='TANGGAL_LAHIR' class='form-control' value='$r[TANGGAL_LAHIR]'></td></tr>
							  <tr><td class='left' width='120'>Status perkawinan</td>  <td>       
							  <select class='form-control' name='STATUS_PERKAWINAN'>";
							  if ($r[STATUS_PERKAWINAN] == 'B') {
							      echo "<option value='$r[STATUS_PERKAWINAN]'>Belum Kawin</option>
                                          <option value='S'>Kawin</option>
                                          <option value='P'>Pernah Kawin</option>
							            <option value=''>-Kosong-</option>";
							      
							  } elseif ($r[STATUS_PERKAWINAN] == 'S') {
							      echo "<option value='$r[STATUS_PERKAWINAN]'>Kawin</option>
                                          <option value='B'>Belum Kawin</option>
                                          <option value='P'>Pernah Kawin</option>
                                        <option value=''>-Kosong-</option>"; 
							  } elseif ($r[STATUS_PERKAWINAN] == 'P') {
							      echo "<option value='$r[STATUS_PERKAWINAN]'>Pernah Kawin</option>
                                          <option value='B'>Belum Kawin</option>
                                          <option value='S'>Kawin</option>
                                        <option value=''>-Kosong-</option>"; 
							  } else {
							       echo " <option value=''>-Kosong-</option>
                                          <option value='B'>Belum Kawin</option>
                                          <option value='S'>Kawin</option>
                                          <option value='P'>Pernah Kawin</option>";
							  }
                                     
                              echo "</select>
							  </td></tr>
							  <tr><td class='left' width='120'>Jenis kelamin</td>  <td>       
							  <select class='form-control' name='JENIS_KELAMIN'>";
							  if ($r[JENIS_KELAMIN] == 'L') {
							      echo "<option value='$r[JENIS_KELAMIN]'>Laki-Laki</option>
							            <option value='P'>Perempuan</option>
							            <option value=''>-Kosong-</option>";
							      
							  } elseif ($r[JENIS_KELAMIN] == 'P') {
							      echo "<option value='$r[JENIS_KELAMIN]'>Perempuan</option>
                                        <option value='L'>Laki-laki</option>
                                        <option value=''>-Kosong-</option>"; 
							  } else {
							       echo "<option value=''>-Kosong-</option>
							             <option value='L'>Laki-Laki</option>
							             <option value='P'>Perempuan</option>";
							  }
                                     
                              echo "</select>
							  </td></tr>
							  <tr><td class='left' width='120'>Alamat</td>  <td>       <input type=text name='ALAMAT' class='form-control' value='$r[ALAMAT]'></td></tr>
							  <tr><td class='left' width='120'>RT</td>  <td>       <input type=text name='RT' class='form-control' value='$r[RT]'></td></tr>
							  <tr><td class='left' width='120'>RW</td>  <td>       <input type=text name='RW' class='form-control' value='$r[RW]'></td></tr>
							  <tr><td class='left' width='120'>Disabilitas</td>  <td>       
							   <select class='form-control' name='DISABILITAS'>";
							  if ($r[DISABILITAS] == '1') {
							      echo "<option value='$r[DISABILITAS]'>Tuna Daksa</option>
                                         <option value='2'>Tuna Netra</option> 
                                         <option value='3'>Tuna Rungu/Wicara</option> 
                                         <option value='4'>Tuna Grahita</option> 
                                         <option value='5'>Tuna Disabilitas Lainnya</option>
                                         <option value=''>-Kosong-</option>";
							  } elseif ($r[DISABILITAS] == '2') {
							      echo "<option value='$r[DISABILITAS]'>Tuna Netra</option>
                                        <option value='1'>Tuna Daksa</option>
                                         <option value='3'>Tuna Rungu/Wicara</option> 
                                         <option value='4'>Tuna Grahita</option> 
                                         <option value='5'>Tuna Disabilitas Lainnya</option>
                                         <option value=''>-Kosong-</option>"; 
							  } elseif ($r[DISABILITAS] == '3') {
							      echo "<option value='$r[DISABILITAS]'>Tuna Rungu/Wicara</option>
                                        <option value='1'>Tuna Daksa</option>
                                         <option value='2'>Tuna Netra</option>  
                                         <option value='4'>Tuna Grahita</option> 
                                         <option value='5'>Tuna Disabilitas Lainnya</option>
                                         <option value=''>-Kosong-</option>"; 
							  } elseif ($r[DISABILITAS] == '4') {
							      echo "<option value='$r[DISABILITAS]'>Tuna Grahita</option>
                                        <option value='1'>Tuna Daksa</option>
                                         <option value='2'>Tuna Netra</option> 
                                         <option value='3'>Tuna Rungu/Wicara</option> 
                                         <option value='5'>Tuna Disabilitas Lainnya</option>
                                         <option value=''>-Kosong-</option>"; 
							  } elseif ($r[DISABILITAS] == '5') {
							      echo "<option value='$r[DISABILITAS]'>Tuna Disabilitas Lainnya</option>
                                        <option value='1'>Tuna Daksa</option>
                                         <option value='2'>Tuna Netra</option> 
                                         <option value='3'>Tuna Rungu/Wicara</option> 
                                         <option value='4'>Tuna Grahita</option>
                                         <option value=''>-Kosong-</option>"; 
							  } else {
							       echo "<option value=''>-Kosong-</option>
                                         <option value='1'>Tuna Daksa</option>
                                         <option value='2'>Tuna Netra</option> 
                                         <option value='3'>Tuna Rungu/Wicara</option> 
                                         <option value='4'>Tuna Grahita</option> 
                                         <option value='5'>Tuna Disabilitas Lainnya</option>";
							  }
                                     
                              echo "</select>
							  </td></tr>
							  <tr><td class='left' width='120'>Keterangan</td>  <td>       <input type=text name='KETERANGAN' class='form-control' value='$r[KETERANGAN]'></td></tr>

							  <tr><td class='left'></td><td class='left' colspan=2>";
            if ($_SESSION['level']=='admin'){        
		   echo "<input class=\"btn btn-primary\"  type=submit value=Update>";
            } echo "  
												<input  class=\"btn btn-default\" type=button value=Batal onclick=self.history.back()></td></tr>
						  </tbody>
					  </table>
				  </form>
		 </div>
         </section>";
    break;
    
    case "importdata":
        echo "<section class=\"col-lg-12 connectedSortable\">
              <div class=\"card card-primary card-outline\">
    				<div class=\"card-header\">
    					<h3 class=\"card-title\">
    					  <i class=\"fas fa-edit mr-1\"></i>
    					  Import
    					</h3>
    				</div>
              <form method=\"post\" enctype=\"multipart/form-data\" action=$aksi?module=data&act=import>
              <table class=\"table\">
    		    <tbody>
    		        <tr><td class='left' width='120'>Kecamatan</td>  <td>  
    				          <select id='id_kecamatan' class='form-control' name='id_kecamatan'>
                                          <option></option>";
                                    if ($_SESSION['level']=='admin'){      
                                        $data = query("select * from ref_kecamatan order by NAMA");  
                                    } else {
                                        $data = query("select * from ref_kecamatan where id = '$_SESSION[id_kecamatan]' order by NAMA");   
                                    }
                                    if (mysqli_num_rows($data)>0){ 
                                        while ($row = mysqli_fetch_assoc($data)){ 
                                		echo"<option value='$row[id]'>$row[NAMA]</option>";
                                		} 
                                	} 
                          echo"</select>
    				      </td></tr>
    				      <tr><td class='left' width='120'>Kelurahan</td>  <td>  
    				      <div id='load_kelurahan'>
    				          <select id='id_kelurahan' class='form-control' name='id_kelurahan' required>";
    				                $data = query("select * from ref_kelurahan WHERE id = '$d[id_kelurahan]'");
    				                $k = mysqli_fetch_assoc($data);
    				            echo"<option value='$d[id_kelurahan]'>$k[NAMA]</option>
                              </select>
                          </div>
    				      </td></tr>
    				      <tr><td class='left' width='120'>Relawan</td>  <td>
    				          <select class='form-control select2bs4 select2-hidden-accessible' style='width: 100%;' data-select2-id='17' tabindex='-1' aria-hidden='true' name='id_relawan' required>
                                    <option selected='selected' value=''>-Pilih-</option>";
                                    $data = query("select * from peg_relawan WHERE username = '$_SESSION[namauser]'
                                                                  order by NAMA");    
                                    if (mysqli_num_rows($data)>0){ 
                                        while ($e = mysqli_fetch_assoc($data)){ 
                                		echo"<option value='$e[id]'>$e[NAMA]</option>";
                                		} 
                                	} 
                                    echo "
                                </select>
    				      </td></tr>
    				      
                	<tr><td class='left' width='120'>Pilih File:</td><td><input name=\"fileimport\" type=\"file\" required=\"required\"></td></tr> 
                	<tr><td class='left' width='120'></td><td><input class=\"btn btn-primary\" name=\"upload\" type=\"submit\" value=\"Import\">
                                                              <input class=\"btn btn-default\" type=button value=Batal onclick=self.history.back()>     
                	</td></tr>
                </tbody>
              </table>  	
              </form>
                  <div class=\"card-footer\" width='150'>
                   </div>
              </div>
             </section>";
        break;
    }
  } else {
        include "view/blank.php";
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
           $('#LOAD_DPT').html("<table class='table'><tbody><tr><td class='left' width='120'></td><td>Tunggu Sebentar!</tr></tbody></table>");
           var str = $('#CARI_NIK').val();
           var res = str.replace(/\ /g, "+");    
           $('#LOAD_DPT').load('modul/mod_data/aksi_data.php?module=data&act=nik&nik=' + res);
        });
        $('span').click(function(){
           var link = $(this).attr("href");
           $('#LOAD_DPT').load(link);
        });
        //$('#load_kelurahan').on('change',function(){
           //var str = $('#id_kelurahan').val();
           //var res = str.replace(/\ /g, "+");    
           //$('#load_tps').load('modul/mod_data/aksi_data.php?module=data&act=kelurahan&id=' + res);
           //$('#load_relawan').load('modul/mod_data/aksi_data.php?module=data&act=relawan&id=' + res);
        //});
         
    });
</script>
<script>
	function confirmation(ev) {
	ev.preventDefault();
	var urlToRedirect = ev.currentTarget.getAttribute('href'); 
	console.log(urlToRedirect);
		swal({
				title: "Yakin ingin menghapus?",
				text: "Anda tidak bisa mengembalikan data setelah dihapus! ",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
				.then((willDelete) => {
				    if (willDelete) {
						swal("Data berhasil dihapus!", {
						  icon: "success",
						});
						setTimeout(function() 
								   { window.open(urlToRedirect,"_self"); 
								   }, 500);
						
					    } else {
						swal("Perintah penghapusan dibatalkan!");
					}
				});
			}
</script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
</script>
