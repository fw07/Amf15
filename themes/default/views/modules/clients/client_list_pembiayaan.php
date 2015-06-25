<section class="main">

	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<section class="panel panel-default">
				
			<!-- TABLE BODY -->
			<div class="table-responsive">
					<table class="table table-striped m-b-none text-sm">      
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th>Nomor Rekening</th>
							<th>Nama</th>
							<th width="100px" class="text-center">Pembiayaan</th>
							<th width="100px">Tanggal</th>
							<th>Plafond</th>
							<th>Angsuran</th>
							<th>Status</th>
							<th width="100px" class="text-center">Manage</th>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php
							if(empty($no)){ 
									$no=1; 
									$nostart=1;
									$noend=$config['per_page'];
								}else{ 
									$no=$no+1;
									$nostart=$no;
									$noend=$nostart+$config['per_page']-1;
								} 
						?>
						<?php foreach($pembiayaan as $p):  ?>
							<tr>
								<td align="center"><?php echo $no; ?></td>	
								<td><?php echo $p->client_account; ?></td>	
								<td><?php echo $p->client_fullname; ?></td>	
								<td class="text-center"><?php echo $p->data_pengajuan; ?></td>
								<td><?php echo $p->data_tgl; ?></td>							
								<td><?php echo number_format($p->data_angsuranpokok*50); ?></td>
								<td><?php echo number_format($p->data_totalangsuran); ?></td>
								<td><?php if($p->data_status == 1){echo "Berjalan";}elseif($p->data_status == 2){echo "Pengajuan";}elseif($p->data_status == 3){echo "Selesai";}elseif($p->data_status == 4){echo "Gagal Dropping";} ?></td>
								<td class="text-center">
									<a href="<?php echo site_url()."/clients/pembiayaan_view/".$p->data_id; ?>" title="View"><i class="fa fa-search"></i></a> 
									<a href="<?php echo site_url()."/clients/pembiayaan_reg/".$p->data_id; ?>" title="Add Pembiayaan"><i class="fa fa-pencil"></i></a> 
									<a href="<?php echo site_url()."/clients/pembiayaan_delete/".$p->data_id; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a>
								</td>
							</tr>							
						<?php $no++; endforeach; ?>
						</tbody>	
					</table>  
					
				</div>
				
				<footer class="panel-footer">
					<div class="row">
						<div class="col-sm-4 text-left"> <small class="text-muted inline m-t-sm m-b-sm">showing <?php echo $nostart; ?>-<?php echo $noend; ?> of <?php echo $config['total_rows']; ?> items</small></div>
						<div class="col-sm-5 text-right text-center-xs pull-right">
							<ul class="pagination pagination-sm m-t-none m-b-none">
								<?php echo $this->pagination->create_links(); ?>
							</ul>
						</div>
					</div>
				</footer>
			</section>
		</div>
</section>
