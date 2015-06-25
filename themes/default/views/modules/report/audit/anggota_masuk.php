<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>			
		</div>
									
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<?php echo "<b>".$branch_name."</b>  | ".$end_date." s/d ".$end_date; ?>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						
						<!-- ANGGOTA -->
							<div class="table-responsive">
								<table class="table table-striped m-b-none text-sm">      
									<thead>                  
									  <tr>
										<th width="30px">No</th>
										<th width="100px">Nomor<br/>Rekening</th>
										<th>Nama Lengkap</th>
										<th class="text-left">Majelis</th>
										<th class="text-left">Cabang</th>
										<th class="text-left">Petugas<br/>UPK</th>
										<th class="text-center">Tanggal<br/>UPK</th>
										<th class="text-center">Tanggal<br/>Realisasi</th>
										<th class="text-right">Jumlah<br/>Pinjaman</th>
										<th class="text-left">Status</th>
									  </tr>                  
									</thead> 
									<tbody>	
									<?php $no=1;?>
									<?php foreach($client as $c):  ?>
										<tr>     
											<td align="center"><?php echo $no; ?></td>					              
											<td><?php echo $c->client_account; ?></td>
											<td><?php echo $c->client_fullname; ?></td>
											<td class="text-left"><?php echo $c->group_name; ?></td>
											<td class="text-left"><?php echo $c->branch_name; ?></td>
											<td class="text-left"><?php echo $c->officer_name; ?></td>
											<td class="text-center"><?php echo $c->client_reg_date; ?></td>
											<td class="text-center"><?php if($c->data_status == "1"){ echo $c->data_date;}else{ echo "-"; } ?></td>
											<td class="text-right"><?php echo number_format($c->data_plafond); ?></td>
											<td class="text-left"><?php if($c->data_status == "1"){ echo "Berjalan";}elseif($c->data_status == "2"){ echo "Pengajuan"; }else{ echo "-"; }; ?></td>
										</tr>										
									<?php $no++; endforeach; ?>
									</tbody>	
								</table> 								
							</div>
						
							
						</div>
					</div>
				</div><!-- End Panel Body -->
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-3 ">
							<form method="post" action="<?php echo site_url();?>/report/audit/anggota_masuk_download">
							<input type="hidden" name="branch" value="<?php echo $branch; ?>" />
							<input type="hidden" name="startdate" value="<?php echo $start_date; ?>" />
							<input type="hidden" name="enddate" value="<?php echo $end_date; ?>" />
							<button type="submit" class="btn btn-primary">Download Report</button>
							</form>
						</div>
					</div>
				</div>
			
	</div>
</div>	
