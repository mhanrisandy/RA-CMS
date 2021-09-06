<?php
include "../config/fungsi_paging.php";
include "../config/fungsi_notifbar.php";

$aksi="modul/mod_users/aksi_users.php";
switch($control_act){
    default:
    echo "<section class=\"col-lg-12 connectedSortable\">";
    show_notif_swal();
    echo"
	        <div class=\"card card-primary card-outline\">
            <div class=\"card-header\">
                
			    <input class=\"btn btn-default\" type=button value='Add' onclick=location.href='?module=users&act=add'>
			    <input class=\"btn btn-success\" type=button value='Import' onclick=location.href='?module=users&act=import'> 
			  
			    <div class=\"card-tools\">
					<form method=get action='$_SERVER[PHP_SELF]'> 
						<div class=\"input-group input-group\" style=\"width: 300px;\">
							<input type=hidden name=module value=users>
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
		  <th class='left'>USERNAME</th>
		  <th class='left'>PASSWORD</th>
		  <th class='left'>NAMA LENGKAP</th>
		  <th class='left'>EMAIL</th>
		  <th class='left'>LEVEL</th>
		  <th class='left'>BLOKIR</th>
		  <th class='left'>ID SESSION</th>

		          <th style=\"width: 14%\"></th>
		          </tr></thead><tbody>";

		    $p      = new Paging;
		    $batas  = 10;
		    $posisi = $p->cariPosisi($batas);

		    $tampil = viewallquery("SELECT * FROM users ORDER BY id DESC LIMIT $posisi,$batas");
		    $no = $posisi+1;
		    foreach($tampil as $r) :
		echo"<td class='left' width='25'>$no</td>
				<td class='left'>$r[username]</td>
				<td class='left'>$r[password]</td>
				<td class='left'>$r[nama_lengkap]</td>
				<td class='left'>$r[email]</td>
				<td class='left'>$r[level]</td>
				<td class='left'>$r[blokir]</td>
				<td class='left'>$r[id_session]</td>


						<td class=\"project-actions text-right\">
						<a class=\"btn btn-primary\" href=?module=users&act=edit&id=$r[id]>
						    <i class=\"fas fa-pencil-alt\">
		                    </i>
		                    Edit</a>
						<a class=\"btn btn-danger\" href=$aksi?module=users&act=delete&id=$r[id] onClick=\"confirmation(event)\">
						    <i class=\"fas fa-trash\">
		                    </i>
		                    Delete</a>
						</td>
				        </tr>";
		      $no++;
			endforeach;
		    echo "</tbody></table></div> 
			      ";

		    $jmldata = numrowquery("SELECT * FROM users");
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
		  <th class='left'>USERNAME</th>
		  <th class='left'>PASSWORD</th>
		  <th class='left'>NAMA LENGKAP</th>
		  <th class='left'>EMAIL</th>
		  <th class='left'>LEVEL</th>
		  <th class='left'>BLOKIR</th>
		  <th class='left'>ID SESSION</th>

				  <th style=\"width: 14%\"></th>
		          </tr></thead><tbody>";

		    $p      = new Paging9;
		    $batas  = 10;
		    $posisi = $p->cariPosisi($batas);
		    $tampil = viewallquery("SELECT * FROM users where username LIKE '%$_GET[kata]%' or nama_lengkap = '$_GET[kata]'
			                       ORDER BY id DESC LIMIT $posisi,$batas");


		    $no = $posisi+1;
		    foreach($tampil as $r) :
		      echo "<tr><td class='left' width='25'>$no</td>
				<td class='left'>$r[username]</td>
				<td class='left'>$r[password]</td>
				<td class='left'>$r[nama_lengkap]</td>
				<td class='left'>$r[email]</td>
				<td class='left'>$r[level]</td>
				<td class='left'>$r[blokir]</td>
				<td class='left'>$r[id_session]</td>


						<td class=\"project-actions text-right\">
						<a class=\"btn btn-primary\" href=?module=users&act=editdata&id=$r[id]>
						    <i class=\"fas fa-pencil-alt\">
		                    </i>
		                    Edit</a>
						<a class=\"btn btn-danger\" href=$aksi?module=users&act=delete&id=$r[id] onClick=\"confirmation(event)\">
						    <i class=\"fas fa-trash\">
		                    </i>
		                    Delete</a>
						</td>
				    </tr>";
		      $no++;
			endforeach;
		    echo "</tbody></table></div>";

		    $jmldata = numrowquery("SELECT * FROM users where username LIKE '%$_GET[kata]%' or nama_lengkap = '$_GET[kata]'");
		    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
		    $linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman);

		echo "<div class=\"card-footer\" width='150'>
		      $linkHalaman
			 </div>";
		echo "</div>
			      </section>";
		    } 
    break;
	
  case "add":
    echo "<section class=\"col-lg-12 connectedSortable\">
          <div class=\"card card-primary card-outline\">
				<div class=\"card-header\">
					<h3 class=\"card-title\">
					  <i class=\"fas fa-plus mr-1\"></i>
					  Add
					</h3>
				</div>
               <table class=\"table\">
			   <tbody>
			   <form method=POST action='$aksi?module=users&act=input'>
					  <tr><td class='left' width='120'>username</td>  <td>  <input type=text name='username' class='form-control'></td></tr>
					  <tr><td class='left' width='120'>password</td>  <td>  <input type=text name='password' class='form-control'></td></tr>
					  <tr><td class='left' width='120'>nama lengkap</td>  <td>  <input type=text name='nama_lengkap' class='form-control'></td></tr>
					  <tr><td class='left' width='120'>email</td>  <td>  <input type=text name='email' class='form-control'></td></tr>
					  <tr><td class='left' width='120'>level</td>  <td>  <input type=text name='level' class='form-control'></td></tr>
					  <tr><td class='left' width='120'>blokir</td>  <td>  <input type=text name='blokir' class='form-control'></td></tr>
					  <tr><td class='left' width='120'>id session</td>  <td>  <input type=text name='id_session' class='form-control'></td></tr>

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
    $r = viewquery("SELECT * FROM users WHERE id='$_GET[id]'");
    echo "<section class=\"col-lg-12 connectedSortable\">
          <div class=\"card card-primary card-outline\">
				<div class=\"card-header\">
					<h3 class=\"card-title\">
					  <i class=\"fas fa-edit mr-1\"></i>
					  Edit
					</h3>
				</div>
				
				  <form method=POST action='$aksi?module=users&act=update' enctype='multipart/form-data'>
					  <input type=hidden name=id value=$r[id]>
					  <input type=hidden name='foto_lama' class='form-control' value='$r[foto]'>
					  <table class=\"table\">
						  <tbody>
							  <tr><td class='left' width='120'>username</td>  <td>       <input type=text name='username' class='form-control' value='$r[username]'></td></tr>
							  <tr><td class='left' width='120'>password</td>  <td>       <input type=text name='password' class='form-control' value='$r[password]'></td></tr>
							  <tr><td class='left' width='120'>nama lengkap</td>  <td>       <input type=text name='nama_lengkap' class='form-control' value='$r[nama_lengkap]'></td></tr>
							  <tr><td class='left' width='120'>email</td>  <td>       <input type=text name='email' class='form-control' value='$r[email]'></td></tr>
							  <tr><td class='left' width='120'>level</td>  <td>       <input type=text name='level' class='form-control' value='$r[level]'></td></tr>
							  <tr><td class='left' width='120'>blokir</td>  <td>       <input type=text name='blokir' class='form-control' value='$r[blokir]'></td></tr>
							  <tr><td class='left' width='120'>id session</td>  <td>       <input type=text name='id_session' class='form-control' value='$r[id_session]'></td></tr>
							  <tr><td class='left' width='120'>Foto</td>  <td>      <img src='modul/mod_users/aksi_users.php?act=view&table=file_users&file_name=$r[foto]' width='100' height='100' class='img-circle elevation-2'></td></tr>
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
              <form method=\"post\" enctype=\"multipart/form-data\" action=$aksi?module=users&act=import>
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