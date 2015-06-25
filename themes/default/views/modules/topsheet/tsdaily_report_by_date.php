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
			<div class="row text-sm wrapper">
				<div class="col-sm-4 m-b-xs">
					
				</div>
				<div class="col-sm-6 pull-right text-right">
					<form method="post" action="">	
						<div class="col-sm-12 m-b-xs pull-right text-right">
							<input type="text" name="date_start" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd"  placeholder="Date Start" value="<?php echo $date_start; ?>" />
							<input type="text" name="date_end" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd" placeholder="Date End" value="<?php echo $date_end; ?>"  />
							<button class="btn btn-sm inline btn-info" type="submit">Go!</button>
						</div>					
					</form>
				</div>
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>                  
					  <tr>
						<th rowspan="2" width="30px">No</th>
						<th rowspan="2" >TS Code</th>
						<th rowspan="2" nowrap width="100px">Tanggal</th>
						<th rowspan="2">Hari</th>
						<th rowspan="2" class="text-right">Angsuran<br/>Pokok</th>
						<th rowspan="2" class="text-right">Profit</th>
						<th rowspan="2" class="text-right">Tabungan<br/>Wajib</th>
						<th colspan="2">Tabungan Sukarela</th>
						<th rowspan="2" class="text-right">Total<br/>RF</th>
						<th rowspan="2" class="text-right">Total<br/>Tabungan</th>
						<th rowspan="2" class="text-right">GRAND TOTAL</th>
						<th rowspan="2" width="60px" class="text-center">Manage</th>
					  </tr>  
					  <tr>
						<th>Kredit</th>
						<th>Debet</th>
					  </tr> 					  
					</thead> 
					<tbody>	
					<?php
						$total_rows=$total_rows;
						$no=1;
					?>
					<?php foreach($tsdaily as $c):  ?>
					<?php 
						setlocale(LC_ALL, 'id_ID');
						$date = "$c->tsdaily_date"; 
						$day = date('l', strtotime($date));
						if($day == "Sunday"){ $day = "Minggu"; }
						elseif($day == "Monday"){ $day = "Senin"; }
						elseif($day == "Tuesday"){ $day = "Selasa"; }
						elseif($day == "Wednesday"){ $day = "Rabu"; }
						elseif($day == "Thursday"){ $day = "Kamis"; }
						elseif($day == "Friday"){ $day = "Jumat"; }
						elseif($day == "Saturday"){ $day = "Sabtu"; }
					?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>
							<td><a href="<?php echo site_url().'/topsheet/ts_view/'.$c->tsdaily_topsheet_code; ?>" class="link"><?php echo $c->tsdaily_topsheet_code; ?></a></td>
							<td><?php echo $c->tsdaily_date; ?></td>
							<td><?php echo $day; ?></td>
							<td align="right"><?php echo number_format($c->total_angsuranpokok); ?></td>
							<td align="right"><?php echo number_format($c->total_profit); ?></td>
							<td align="right"><?php echo number_format($c->total_tabwajib); ?></td>
							<td align="right"><?php echo number_format($c->total_tabungan_debet); ?></td>
							<td align="right"><?php echo number_format($c->total_tabungan_credit); ?></td>
							<td align="right"><?php echo number_format($c->total_total_rf); ?></td>
							<td align="right"><?php echo number_format($c->total_total_tabungan); ?></td>
							<td align="right"><?php echo number_format($c->total_total_tabungan + $c->total_total_rf); ?></td>
							<td class="text-center">
								<a href="<?php echo site_url().'/topsheet/tsdaily_report_view/'.$c->tsdaily_date; ?>" title="View"><i class="fa fa-search"></i></a>
								<!--<a href="<?php echo site_url().'/topsheet/ts_delete/'.$c->tsdaily_topsheet_code; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a>-->
							</td>
						</tr>
					<?php $no++; endforeach; ?>
					</tbody>	
				</table>  
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-sm-4 text-left"> <small class="text-muted inline m-t-sm m-b-sm">showing 1 - <?php echo ($no-1); ?> of <?php echo ($no-1); ?> items</small></div>
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
					<form method="post" action="<?php echo site_url()."/topsheet/ts_report/tsdaily_report_download";?>">	
							<input type="hidden" name="date_start" value="<?php echo $date_start; ?>" />
							<input type="hidden" name="date_end" value="<?php echo $date_end; ?>"  />
							<button class="btn btn-sm btn-primary" type="submit">Download Rekap Harian (.xls)</button>
									
					</form>
					
					<br/><br/>	
				</div>
			</footer>
			
	</section>
	</div>
</section>	