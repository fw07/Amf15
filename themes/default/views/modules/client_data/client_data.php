<section class="main">
	<div class="container">
	
	<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
	</div>
	
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
	
	<!-- TABLE HEADER -->
	<div class="row text-sm wrapper">
		
		
		<!-- SEARCH FORM -->
		<form action="" method="post"> 
			<div class="col-sm-4 m-b-xs pull-right text-right">
				<input type="text" name="q" class="input-sm form-control input-s-sm inline" placeholder="Search">
				<button class="btn btn-sm btn-default" type="submit">Go!</button>
			</div>
		</form>					
	</div>
	
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
						<th class="text-right">Plafond</th>
						<th class="text-right">Profit</th>
						<th width="120px">Tgl Pencairan</th>
						<th width="120px">Tgl. Jatuh Tempo</th>
						<th class="text-center">Pemb.<br/>Ke</th>
						<th class="text-center">Angs.<br/>Ke</th>
						<th class="text-right">Sisa Pokok</th>
						<th class="text-center">PAR</th>
						<th class="text-center">TR</th>
						<th class="text-center">Akad</th>
						<th class="text-right">Tab. Wajib</th>
						<th class="text-right">Tab. Sukarela</th>
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
					
					if($config['total_rows'] < $config['per_page']){ $noend = $config['total_rows']; }
					else{ $noend=$nostart+$config['per_page']-1; }
					
					foreach($regpyd as $c): 
						
						
					?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>					              
							<td><?php echo $c->Cabang; ?></td>
							<td><?php echo $c->Majelis; ?></td>
							<td><?php echo $c->Nomor_Rekening; ?></td>
							<td><?php echo $c->Nama; ?></td>
							<td class="text-right"><?php echo number_format($c->Plafond,0); ?></td>
							<td class="text-right"><?php echo number_format($c->Profit,0); ?></td>
							<td><?php echo $c->Tgl_pencairan; ?></td>
							<td><?php echo $c->Tgl_jatuh_tempo; ?></td>
							<td class="text-center"><?php echo $c->Pembiayaan_Ke; ?></td>
							<td class="text-center"><?php echo $c->Angsuran_Ke; ?></td>
							<td class="text-right"><?php echo number_format($c->sisa_pokok,0); ?></td>
							<td class="text-center"><?php echo $c->Par; ?></td>
							<td class="text-center"><?php echo $c->TR; ?></td>
							<td class="text-center"><?php echo $c->Akad; ?></td>
							<td class="text-right"><?php echo number_format($c->Tab_Wajib,0); ?></td>
							<td class="text-right"><?php echo number_format($c->Tab_Sukarela,0); ?></td>
							
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
					<a href="<?php echo site_url()."/regpyd/download/"; ?>" target="_blank" class="btn btn-sm btn-primary" >Download Laporan Regpyd</a>
					<br/><br/>	
				</div>
			</footer>
			
	</section>
	</div>
</section>	