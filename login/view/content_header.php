<div class="content-header">
			  <div class="container-fluid">
				<div class="row mb-2">
				  <div class="col-sm-6">
					<h1 class="m-0 text-dark">
					<?php if ($module=='home'){
							echo "Dashboard"; 
						 } elseif ($module=='ref_kecamatan'){
							echo "Kecamatan"; 
						 } elseif ($module=='ref_kelurahan'){
							echo "Kelurahan / Desa"; 
						 } elseif ($module=='ref_tps'){
							echo "TPS"; 
						 } elseif ($module=='ref_data'){
							echo "Data"; 
						 } else {
						    $label = ucfirst($module);
						    $label = str_replace("_"," ",$label);
							echo $label;
						 }					
					?>
					</h1>
				  </div><!-- /.col -->
				  <div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
					  <li class="breadcrumb-item"><a href="index.php?module=home">Home</a></li>
					  <li class="breadcrumb-item active">
					      <?php
					        if ($module=='home'){
							    echo "Home"; 
    						} elseif ($module=='ref_kecamatan'){
    							echo "<a onclick=self.history.back()>kecamatan</a>";
    						} else { 
    						     $label = $module; 
    							echo "<a href=index.php?module=$label>$label</a>"; 
    						}
					      ?>
					  </li>
					</ol>
				  </div><!-- /.col -->
				</div><!-- /.row -->
			  </div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->