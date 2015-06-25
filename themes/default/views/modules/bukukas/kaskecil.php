<section class="main">
	<div class="container">
			
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
					
		<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-lg-4">
				<a href="<?php echo site_url()."/bukukas/kaskecil_add/"; ?>" class="btn btn-sm btn-primary" >Tambah Kas Kecil</a>
				</div>
				<!-- SEARCH FORM -->
				<form action="" method="post"> 
					<div class="col-sm-4 m-b-xs pull-right text-right">
						<select name="key" class="input-sm form-control input-s-sm inline">
							<option value="date">Tanggal</option>
							<option value="remark">Keterangan</option>
						</select>
						<input type="text" name="q" class="input-sm form-control input-s-sm inline" placeholder="Search">
						<button class="btn btn-sm btn-default" type="submit">Go!</button>
					</div>
				</form>	
			</div>
			
			<!-- TABLE BODY -->
			<div class="table-responsive">
					<table class="table table-striped m-b-none text-sm" data-ride="datatables">      
						<thead>                  
						  <tr>
							<th width="30px">No</th>							
							<th>Cabang</th>
							<th width="100px">Tanggal</th>
							<th>Account</th>							
							<th>Deskripsi</th>							
							<th>NOMOR BUKTI</th>
							<th class="text-center" width="50px">Qty</th>
							<th class="text-right">Harga Satuan</th>
							<th class="text-right">Total</th>
							<th width="100px" class="text-center">Manage</th>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php
							if(empty($no)){ 
									$no=1; 
									$nostart=1;
									$noend=$config['per_page'];
									if( $noend > $config['total_rows']) { $noend = $config['total_rows']; }
								}else{ 
									$no=$no+1;
									$nostart=$no;
									$noend=$nostart+$config['per_page']-1;
									if( $noend > $config['total_rows']) { $noend = $config['total_rows']; }
								} 
						?>
						<?php foreach($jurnal as $c):  ?>
							<?php 
								$kaskecil_date = $c->kaskecil_date;
								$kaskecil_month = substr($kaskecil_date, 5, 2); 
							?>
							<tr>     
								<td align="center" ><?php echo $no; ?></td>	
								<td><?php 
									if($c->kaskecil_cabang == 0) {echo "Pusat"; }
									elseif($c->kaskecil_cabang == 1) {echo "Ciseeng"; }
									elseif($c->kaskecil_cabang == 4) {echo "Jasinga"; }
									elseif($c->kaskecil_cabang == 3) {echo "Bojong Gede"; }
									elseif($c->kaskecil_cabang == 2) {echo "Kemang"; }
									elseif($c->kaskecil_cabang == 5) {echo "Tenjo"; }
									elseif($c->kaskecil_cabang == 6) {echo "Cangkuang"; }
								?></td>
								<td><?php echo date("d-M-Y", strtotime($c->kaskecil_date)); ?></td>
								<td><?php echo $c->accounting_code." ".$c->accounting_name; ?></td>
								<td><?php echo $c->kaskecil_remark; ?></td>	
								<td><?php if($c->kaskecil_nobukti_kode != "-" OR $c->kaskecil_nobukti_nomor != "-"){ echo $c->kaskecil_nobukti_kode."/".$kaskecil_month."/".$c->kaskecil_nobukti_nomor; } ?></td>	
								<td class="text-center"><?php echo $c->kaskecil_qty; ?></td>		              
								<td class="text-right"><?php echo number_format($c->kaskecil_hargasatuan); ?></td>
								<td class="text-right"><?php echo number_format($c->kaskecil_total); ?></td>
								<td class="text-center">
									<a href="<?php echo site_url()."/bukukas/kaskecil_edit/".$c->kaskecil_id; ?>" title="Edit"><i class="fa fa-pencil"></i></a> 
									<a href="<?php echo site_url()."/bukukas/kaskecil_delete/".$c->kaskecil_id; ?>" title="Delete" onclick="return confirmDialog();"><i class="fa fa-trash-o"></i></a> 
								</td>
							</tr>
							
						<?php $no++; endforeach; ?>
						<?php echo $list;?>
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
