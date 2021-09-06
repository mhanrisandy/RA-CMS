<?php
include "../config/fungsi_paging.php";
include "../config/fungsi_notifbar.php";

$aksi="modul/mod_mahasiswa/aksi_mahasiswa.php";
switch($control_act){
    default:
    echo "<section class=\"col-lg-12 connectedSortable\">";
    show_notif_swal();
    echo"
	        <div class=\"card card-primary card-outline\">
            <div class=\"card-header\">
                
			    <input class=\"btn btn-default\" type=button value='Tambah' onclick=location.href='?module=mahasiswa&act=tambah'>
			    <input class=\"btn btn-success\" type=button value='Import' onclick=location.href='?module=mahasiswa&act=import'> 
			  
			    <div class=\"card-tools\">
					<form method=get action='$_SERVER[PHP_SELF]'> 
						<div class=\"input-group input-group\" style=\"width: 300px;\">
							<input type=hidden name=module value=mahasiswa>
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
		  <th class='left'>NAMA</th>
		  <th class='left'>NIM</th>
		  <th class='left'>ALAMAT</th>

		          <th style=\"width: 14%\"></th>
		          </tr></thead><tbody>";

		    $p      = new Paging;
		    $batas  = 10;
		    $posisi = $p->cariPosisi($batas);

		    $tampil = viewallquery("SELECT * FROM peg_mahasiswa ORDER BY id DESC LIMIT $posisi,$batas");
		    $no = $posisi+1;
		    foreach($tampil as $r) :
		echo"<td class='left' width='25'>$no</td>
				<td class='left'>$r[NAMA]</td>
				<td class='left'>$r[NIM]</td>
				<td class='left'>$r[ALAMAT]</td>


						<td class=\"project-actions text-right\">
						<a class=\"btn btn-primary\" href=?module=mahasiswa&act=edit&id=$r[id]>
						    <i class=\"fas fa-pencil-alt\">
		                    </i>
		                    Edit</a>
						<a class=\"btn btn-danger\" href=$aksi?module=mahasiswa&act=hapus&id=$r[id] onClick=\"confirmation(event)\">
						    <i class=\"fas fa-trash\">
		                    </i>
		                    Hapus</a>
						</td>
				        </tr>";
		      $no++;
			endforeach;
		    echo "</tbody></table></div> 
			      ";

		    $jmldata = numrowquery("SELECT * FROM peg_mahasiswa");
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
		  <th class='left'>NAMA</th>
		  <th class='left'>NIM</th>
		  <th class='left'>ALAMAT</th>

				  <th style=\"width: 14%\"></th>
		          </tr></thead><tbody>";

		    $p      = new Paging9;
		    $batas  = 10;
		    $posisi = $p->cariPosisi($batas);
		    $tampil = viewallquery("SELECT * FROM peg_mahasiswa where TANGGAL LIKE '%$_GET[kata]%' or KETERANGAN = '$_GET[kata]'
			                       ORDER BY id DESC LIMIT $posisi,$batas");


		    $no = $posisi+1;
		    foreach($tampil as $r) :
		      echo "<tr><td class='left' width='25'>$no</td>
				<td class='left'>$r[NAMA]</td>
				<td class='left'>$r[NIM]</td>
				<td class='left'>$r[ALAMAT]</td>


						<td class=\"project-actions text-right\">
						<a class=\"btn btn-primary\" href=?module=mahasiswa&act=editdata&id=$r[id]>
						    <i class=\"fas fa-pencil-alt\">
		                    </i>
		                    Edit</a>
						<a class=\"btn btn-danger\" href=$aksi?module=mahasiswa&act=hapus&id=$r[id] onClick=\"confirmation(event)\">
						    <i class=\"fas fa-trash\">
		                    </i>
		                    Hapus</a>
						</td>
				    </tr>";
		      $no++;
			endforeach;
		    echo "</tbody></table></div>";

		    $jmldata = numrowquery("SELECT * FROM peg_mahasiswa where TANGGAL LIKE '%$_GET[kata]%' or KETERANGAN = '$_GET[kata]'");
		    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
		    $linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman);

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
			   <form method=POST action='$aksi?module=mahasiswa&act=input' enctype='multipart/form-data'>
					  <tr><td class='left' width='120'>Nama</td>  <td>  <input type=text name='NAMA' class='form-control'></td></tr>
					  <tr><td class='left' width='120'>Nim</td>  <td>  <input type=text name='NIM' class='form-control'></td></tr>
					  <tr><td class='left' width='120'>Alamat</td>  <td>  <input type=text name='ALAMAT' class='form-control'></td></tr>
					  <tr><td class='left' width='120'>File</td>  <td><input type=\"file\" class=\"form-control\" name=\"file\"></tr> 
							  <tr><td class='left'></td><td class='left' colspan=2>
                                  <input class=\"btn btn-primary\" type=submit value=Simpan>
    					          <input class=\"btn btn-default\" type=button value=Batal onclick=self.history.back()>
                              </td></tr>
			   </form>
                </tbody>
			   </table>
			   
		</div>
        </section>";
    break;
  
  case "edit":
    $r = viewquery("SELECT * FROM peg_mahasiswa WHERE id='$_GET[id]'");
    echo "<section class=\"col-lg-12 connectedSortable\">
          <div class=\"card card-primary card-outline\">
				<div class=\"card-header\">
					<h3 class=\"card-title\">
					  <i class=\"fas fa-edit mr-1\"></i>
					  Edit
					</h3>
				</div>
				
				  <form method=POST action='$aksi?module=mahasiswa&act=update' enctype='multipart/form-data'>
					  <input type=hidden name=id value=$r[id]>
					  <input type=hidden name='foto_lama' class='form-control' value='$r[foto]'>
					  <table class=\"table\">
						  <tbody>
							  <tr><td class='left' width='120'>Nama</td>  <td>       <input type=text name='NAMA' class='form-control' value='$r[NAMA]'></td></tr>
							  <tr><td class='left' width='120'>Nim</td>  <td>       <input type=text name='NIM' class='form-control' value='$r[NIM]'></td></tr>
							  <tr><td class='left' width='120'>Alamat</td>  <td>       <input type=text name='ALAMAT' class='form-control' value='$r[ALAMAT]'></td></tr>
							  <tr><td class='left' width='120'>Foto</td>  <td>      <img src='modul/mod_mahasiswa/aksi_mahasiswa.php?act=view&table=file_mahasiswa&file_name=$r[foto]' width='100' height='100' class='img-circle elevation-2'></td></tr>
							  <tr><td class='left' width='120'>File</td>  <td><input type=\"file\" class=\"form-control\" name=\"file\"></tr> 
                          <tr><td class='left'></td><td class='left' colspan=2>
                                <input class=\"btn btn-primary\"  type=submit value=Update>
                                <input  class=\"btn btn-default\" type=button value=Batal onclick=self.history.back()>
                          </td></tr>
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
              <form method=\"post\" enctype=\"multipart/form-data\" action=$aksi?module=mahasiswa&act=import>
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
?>
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
						setTimeout(function() 
								   { window.open(urlToRedirect,"_self"); 
								   }, 0);
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
