<section class="main">
	<div class="container">
	
	<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
	</div>
	
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
		
	<section class="panel panel-default panel-body">
			<!-- TABLE HEADER -->
			
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>                  
					  <tr>
						<th width="30px">No</th>
						<th>Cabang</th>
						<th>Majelis</th>
						<th>No Rekening</th>
						<th>Nama</th>
						<th class="text-left">KTP</th>
						<th class="text-left">Alamat</th>
						<th class="text-left">Tgl Lahir</th>
						<th class="text-right">Plafond</th>
						<th class="text-right">Profit</th>
						<th width="120px">Tgl Pencairan</th>
						<th width="120px">Tgl. Jatuh Tempo</th>
						<th class="text-center">Ke</th>
						<th class="text-right">Sisa Pokok</th>
						<th class="text-right">Sisa Profit</th>
						<th class="text-center">Akad</th>
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

					foreach($asuransi as $c): 
						
						
					?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>					              
							<td><?php echo $c->Cabang; ?></td>
							<td><?php echo $c->Majelis; ?></td>
							<td><?php echo $c->Nomor_Rekening; ?></td>
							<td><?php echo $c->Nama; ?></td>
							<td><?php echo $c->KTP; ?></td>
							<td><?php echo $c->Alamat; ?></td>
							<td><?php echo $c->Tgl_Lahir; ?></td>
							<td class="text-right"><?php echo $c->Plafond; ?></td>
							<td class="text-right"><?php echo $c->Profit; ?></td>
							<td><?php echo $c->Tgl_pencairan; ?></td>
							<td><?php echo $c->Tgl_jatuh_tempo; ?></td>
							<td><?php echo $c->Angsuran_Ke; ?></td>
							<td class="text-right"><?php echo $c->sisa_pokok; ?></td>
							<td class="text-right"><?php echo $c->sisa_profit; ?></td>
							<td class="text-center"><?php echo $c->Akad; ?></td>
							
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
			<footer class="panel-footer">
					<div class="text-center">
					<br/>
					<a href="<?php echo site_url()."/asuransi/download/"; ?>" target="_blank" class="btn btn-sm btn-primary" >Download Laporan Asuransi</a>
					<br/><br/>	
				</div>
			</footer>
			
	</section>
	</div>
</section>	