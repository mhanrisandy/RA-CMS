<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$tanggal = date("YmdHis");
$aksi="modul/mod_kegiatan_harian_file/aksi_kegiatan_harian_file.php";
switch($_GET[act]){
  // Tampil
  default:
    echo "
          <section class=\"col-lg-12 connectedSortable\">
	        <div class=\"card card-default card-outline\">
            <div class=\"card-header\">
          
          <input class=\"btn btn-default\" type=button value='Tambah' onclick=location.href='?module=kegiatan_harian_file&act=tambahkegiatan_harian_file&id=$_GET[id]'>
		  </div>";
 
	echo" <div class=\"card-body table-responsive p-0\">
		  <table class=\"table\"><thead>
          <tr>
          <th class='left'>NO</th>
          <th class='left'>NAMA FILE</th>
          <th class='left'>KETERANGAN</th>
          <th class='center'>AKSI</th>
          </tr></thead><tbody>";

    $p      = new Paging;
    $batas  = 10;
    $posisi = $p->cariPosisi($batas);

    $tampil=mysql_query("SELECT id, id_kegiatan_harian, file_name, KETERANGAN FROM file_kegiatan_harian    
                                     WHERE id_kegiatan_harian = '$_GET[id]'
                                     ");
    $no = $posisi+1;
    while ($r=mysql_fetch_array($tampil)){
      echo "<tr><td class='left' width='25'>$no</td>
                <td class='left'>$r[file_name]</td>
                <td class='left'>$r[KETERANGAN]</td>
				<td class='center' width='230'>
    				<a class=\"btn btn-default\" target=\"_blank\" href=$aksi?module=kegiatan_harian_file&act=view&id=$r[id]&id_user=$_SESSION[id_user]&v=$tanggal>File</a>
                    <a class=\"btn btn-primary\" href=?module=kegiatan_harian_file&act=editkegiatan_harian_file&id=$r[id]>Edit</a>
    	            <a class=\"btn btn-danger\" href=$aksi?module=kegiatan_harian_file&act=hapus&id=$r[id]&id_kegiatan_harian=$r[id_kegiatan_harian] onClick=\"confirmation(event)\">Hapus</a>
	            </td>
		        </tr>";
      $no++;
    }
    echo "</tbody></table></div> ";

    $jmldata = mysql_num_rows(mysql_query("SELECT id, id_kegiatan_harian, KETERANGAN FROM file_kegiatan_harian WHERE id_kegiatan_harian = '$_GET[id]'"));
    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
    $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);

echo "<div class=\"card-footer\" width='150'>
     
	 </div>
	 
	 </div>
	      </section>";

    break;   
    
  case "tambahkegiatan_harian_file":
  $data = mysql_query("SELECT * FROM kegiatan_harian WHERE id_kegiatan_harian='$_GET[id]' and id_user='$_SESSION[id_user]'");
  if (mysql_num_rows($data)>0){      
    echo "<section class=\"col-lg-12 connectedSortable\">
          <div class=\"card card-primary card-outline\">
				<div class=\"card-header\">
					<h3 class=\"card-title\">
					  <i class=\"fas fa-plus mr-1\"></i>
					  Tambah
					</h3>
				</div>
				
		  <div class=\"card-body\">		
	      <div class=\"card-content\">		
          <form method=POST action='$aksi?module=kegiatan_harian_file&act=input&id_kegiatan_harian=$_GET[id]' enctype=\"multipart/form-data\">
          <input type=hidden name=id_kegiatan_harian value=$_GET[id]>
            <div class=\"form-group\">
                <label for=\"KETERANGAN\">Keterangan</label>
                <input type=\"text\" class=\"form-control\" id=\"KETERANGAN\" placeholder=\"Keterangan\" name=\"KETERANGAN\">
            </div>
		    <div class=\"form-group\">
                        <label for=\"file\">Upload Foto</label>
                        <input type=\"file\" class=\"form-control\" name=\"file\">
            </div>
            
            
           <input id='simpan' class=\"btn btn-primary\" type=submit value=Simpan>
           <input class=\"btn btn-default\" type=button value=Batal onclick=self.history.back()>
           <div id='loading'></div>
          </form>
          </div>
          </div>
          
           </div>
           </section>";
    } else {
        echo "<section class=\"col-lg-12 connectedSortable\">
        <div class=\"card card-success card-outline\">
              <div class=\"card-header\">
              <h3 class=\"card-title\">
                  <i class=\"fas fa-info mr-1\"></i>
                 Info
                </h3>
              </div>
        <div class=\"card-body\">
         
              <p><b>Anda tidak bisa menambahkan file yang bukan kegiatan anda!</b></p>
        </div></div>
        </section>";
      }
    break;
  

  case "editkegiatan_harian_file":
    $edit = mysql_query("SELECT id, id_kegiatan_harian, KETERANGAN FROM file_kegiatan_harian WHERE id='$_GET[id]' and id_user='$_SESSION[id_user]'");
    if (mysql_num_rows($edit)>0){
        $r    = mysql_fetch_array($edit);
        echo "<section class=\"col-lg-12 connectedSortable\">
          <div class=\"card card-primary card-outline\">
				<div class=\"card-header\">
					<h3 class=\"card-title\">
					  <i class=\"fas fa-edit mr-1\"></i>
					  Edit
					</h3>
				</div>
		  
		  <div class=\"card-body\">		
	      <div class=\"card-content\">				
          <form method=POST action=$aksi?module=kegiatan_harian_file&act=update&id_kegiatan_harian=$r[id_kegiatan_harian] enctype=\"multipart/form-data\">
          <input type=hidden name=id value=$r[id]>
            <div class='form-group'>
                <label for='KETERANGAN'>Keterangan</label>
                <input type='text' class='form-control' value='$r[KETERANGAN]' placeholder='Keterangan' name='KETERANGAN'>
            </div>
		    <div class=\"form-group\">
                        <label for=\"file\">Upload Foto</label>
                        <input type=\"file\" class=\"form-control\" name=\"file\">
            </div>
          <input id='simpan' class=\"btn btn-primary\"  type=submit value=Update>
          <input  class=\"btn btn-default\" type=button value=Batal onclick=self.history.back()>
          <div id='loading'></div>
          </tbody></table></form>
          </div></div>
          
          </div>
         </section>";
    } else {
        echo "<section class=\"col-lg-12 connectedSortable\">
        <div class=\"card card-success card-outline\">
              <div class=\"card-header\">
              <h3 class=\"card-title\">
                  <i class=\"fas fa-info mr-1\"></i>
                 Info
                </h3>
              </div>
        <div class=\"card-body\">
         
              <p><b>Anda tidak bisa mengubah file yang bukan file anda!</b></p>
        </div></div>
        </section>";
      }
    break;
    
    case "map":
    echo "<iframe class='form-control' style='height: 700px;' src=\"https://sdn005nunukan.sch.id/api/map.php?act=lokasi_kegiatan_harian_file\" title=\"RASOFTWARE\">
          </iframe>";
    break; 
    }
}
unset($_SESSION[status]);
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
    $(document).ready(function(){
        $('#simpan').click(function(){
            $(this).hide();
            $('#loading').html("<center><i class='fas fa-spinner fa-pulse'></i> Sedang Menyimpan</center>");
        });    
    });
</script>