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
				<a href="<?php echo site_url()."/saving/tabsukarela_add/".$this->uri->segment(3); ?>" class="btn btn-sm btn-primary" >Add New Transaction</a>
				</div>
			</div>
			
			<!-- TABLE BODY -->
			<div class="table-responsive">
					<table class="table table-striped m-b-none text-sm">      
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th width="180px">TS Code</th>
							<th width="150px">Tanggal</th>
							<th class="text-right">Debet (Rp)</th>
							<th class="text-right">Credit (Rp)</th>
							<th class="text-right">Saldo (Rp)</th>
							<th>Remark</th>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php
							if(empty($no)){ 
									$no=1; 
									$nostart=1;
									$noend=$config['per_page'];
									$totalrow = $config['total_rows'];
									if($noend > $totalrow) {$noend=$totalrow;}
								}else{ 
									$no=$no+1;
									$nostart=$no;
									$noend=$nostart+$config['per_page']-1;
									$totalrow = $config['total_rows'];
									if($noend > $totalrow) {$noend=$totalrow;}
								} 
						?>
						<?php foreach($data as $c):  ?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>	
								<td><?php if($c->tr_topsheet_code != 0) { echo $c->tr_topsheet_code; }else{ echo $c->tr_transactioncode; }; ?></td>				              
								<td><?php echo date("Y-m-d", strtotime($c->tr_date)); ?></td>
								<td class="text-right"><?php echo number_format($c->tr_debet); ?></td>
								<td class="text-right"><?php echo number_format($c->tr_credit); ?></td>
								<td class="text-right"><?php echo number_format($c->tr_saldo); ?></td>
								<td><?php echo $c->tr_remark; ?></td>
								
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