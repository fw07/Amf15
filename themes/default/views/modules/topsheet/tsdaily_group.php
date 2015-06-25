<section class="main">
	<div class="container">
	
	<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
	</div>
	
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
		
	<section class="panel panel-default panel-body">
			<!-- TABLE HEADER 
			<div class="row text-sm wrapper">
				<div class="col-sm-4 m-b-xs">
					<a href="<?php echo site_url().'/group/register/'; ?>" class="btn btn-sm btn-info" >Registrasi Majelis</a>
				</div>
				<div class="col-sm-3 pull-right">
					<div class="input-group">
						<input type="text" class="input-sm form-control" placeholder="Search"> <span class="input-group-btn"> <button class="btn btn-sm btn-default" type="button">Go!</button> </span> 
					</div>
				</div>
			</div>
			-->
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>                  
					  <tr>
						<th rowspan="2" width="30px">No</th>
						<th rowspan="2" >TS Code</th>
						<th rowspan="2" nowrap width="100px">Tanggal</th>
						<th rowspan="2">Majelis</th>
						<th rowspan="2" class="text-right">Angsuran<br/>Pokok</th>
						<th rowspan="2" class="text-right">Profit</th>
						<th rowspan="2" class="text-right">Tabungan<br/>Wajib</th>
						<th colspan="2">Tabungan Sukarela</th>
						<th rowspan="2" class="text-right">Total<br/>RF</th>
						<th rowspan="2" class="text-right">Total<br/>Tabungan</th>
						<th rowspan="2" class="text-right">GRAND TOTAL</th>
						<th colspan="5" class="text-center">Absen</th>
						<th rowspan="2" width="80px" class="text-center">Manage</th>
					  </tr>  
					  <tr>
						<th>Kredit</th>
						<th>Debet</th>
						<th>H</th>
						<th>S</th>
						<th>C</th>
						<th>I</th>
						<th>A</th>
					  </tr> 					  
					</thead> 
					<tbody>	
					<?php
						$total_rows=$total_rows;
						if(empty($no)){ 
							$no=1; 
							$nostart=1;
							$noend=$config['per_page'];
							if($noend>$total_rows){ $noend=$total_rows; }
						}else{ 
							$no=$no+1;
							$nostart=$no;
							$noend=$nostart+$config['per_page']-1;
							if($noend>$total_rows){ $noend=$total_rows; }
						} 
					?>
					<?php foreach($tsdaily as $c):  ?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>
							<td><a href="<?php echo site_url().'/topsheet/ts_view/'.$c->tsdaily_topsheet_code; ?>" class="link"><?php echo $c->tsdaily_topsheet_code; ?></a></td>
							<td><?php echo $c->tsdaily_date; ?></td>
							<td><a href="<?php echo site_url().'/group/view/'.$c->tsdaily_groupid; ?>" class="link"><?php echo $c->tsdaily_group; ?></a></td>
							<td align="right"><?php echo number_format($c->tsdaily_angsuranpokok); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_profit); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_tabwajib); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_tabungan_debet); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_tabungan_credit); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_total_rf); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_total_tabungan); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_total_tabungan + $c->tsdaily_total_rf); ?></td>
							<td><?php echo $c->tsdaily_absen_h; ?></td>
							<td><?php echo $c->tsdaily_absen_s; ?></td>
							<td><?php echo $c->tsdaily_absen_c; ?></td>
							<td><?php echo $c->tsdaily_absen_i; ?></td>
							<td><?php echo $c->tsdaily_absen_a; ?></td>
							<td class="text-center">
								<a href="<?php echo site_url().'/topsheet/ts_view/'.$c->tsdaily_topsheet_code; ?>" title="View"><i class="fa fa-search"></i></a>
								<a href="<?php echo site_url().'/topsheet/ts_delete/'.$c->tsdaily_topsheet_code; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a>
							</td>
						</tr>
					<?php $no++; endforeach; ?>
					</tbody>	
				</table>  
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-sm-4 text-left"> <small class="text-muted inline m-t-sm m-b-sm">showing <?php echo $nostart; ?>-<?php echo ($no-1); ?> of <?php echo $total_rows; ?> items</small></div>
					<div class="col-sm-5 text-right text-center-xs pull-right">
						<ul class="pagination pagination-sm m-t-none m-b-none">
							<?php //echo $this->pagination->create_links(); ?>
						</ul>
					</div>
				</div>
			</footer>
			
	</section>
	</div>
</section>	