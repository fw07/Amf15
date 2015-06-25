<?php 
$user_level = $this->session->userdata('user_level');

function namahari($date){
	$namahari=date('l',strtotime($date));
	if ($namahari == "Sunday") $namahari = "Minggu";
	else if ($namahari == "Monday") $namahari = "Senin";
	else if ($namahari == "Tuesday") $namahari = "Selasa";
	else if ($namahari == "Wednesday") $namahari = "Rabu";
	else if ($namahari == "Thursday") $namahari = "Kamis";
	else if ($namahari == "Friday") $namahari = "Jumat";
	else if ($namahari == "Saturday") $namahari = "Sabtu";
	 
	echo $namahari;
}
?>

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
				<div class="col-lg-1">
					
				</div>
				
				
				<!-- SEARCH FORM -->
				<form action="" method="post"> 
					<div class="col-sm-6 m-b-xs pull-right text-right">
						<input type="text" name="date_start" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd"  placeholder="Date Start">
						<input type="text" name="date_end" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd" placeholder="Date End">
						
						<button class="btn btn-sm btn-info" type="submit">Go!</button>
					</div>
				</form>	
			</div>
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>                  
					  <tr>
						<th rowspan="2" width="30px">No</th>
						<!--<th rowspan="2" >TS Code</th>-->
						<!--<th rowspan="2" nowrap width="100px">Tanggal</th>-->
						<th rowspan="2">Majelis</th>
						<th rowspan="2" class="text-right">Angsuran<br/>Pokok</th>
						<th rowspan="2" class="text-right">Profit</th>
						<th rowspan="2" class="text-right">Tabungan<br/>Wajib</th>
						<th colspan="2">Tabungan Sukarela</th>
						<th colspan="2">Tabungan Berjangka</th>
						<th rowspan="2" class="text-right">Total<br/>RF</th>
						<th rowspan="2" class="text-right">Total<br/>Tabungan</th>
						<th rowspan="2" class="text-right">GRAND TOTAL</th>
						<th colspan="5" class="text-center">Absen</th>
						<th rowspan="2" class="text-center">Total Anggota</th>
						<th rowspan="2"" class="text-center">Total TR</th>
						<th rowspan="2" width="80px" class="text-center">Manage</th>
					  </tr>  
					  <tr>
						<th>Kredit</th>
						<th>Debet</th>
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
					<?php $no=1; ?>
					<?php foreach($tsdaily as $c):  ?>
					<?php $tgl_start = $c->tsdaily_date;?>
					<?php if($tgl_start != $tgl_end AND $no==1){ ?>
						<tr>     
							<td></td>
							<!--<td></td>-->
							<td colspan="19"><b><?php namahari($c->tsdaily_date); echo ", ".date('d-M-Y',strtotime($c->tsdaily_date)); ?><b></td>						
						</tr>
					<?php }elseif($tgl_start != $tgl_end AND $no!=1){ ?>
						
						<tr>     
							<td><br/><br/></td>
							<!--<td>TSCODE</td>-->
							<!--<td>DATE</td>-->
							<td></td>
							<td align="right"><b><?php echo number_format($total_angsuranpokok); ?></b></td>
							<td align="right"><b><?php echo number_format($total_profit); ?></b></td>
							<td align="right"><b><?php echo number_format($total_tabwajib); ?></b></td>
							<td align="right"><b><?php echo number_format($total_tabungan_debet); ?></b></td>
							<td align="right"><b><?php echo number_format($total_tabungan_credit); ?></b></td>
							<td align="right"><b><?php echo number_format($total_tabungan_berjangka_debet); ?></b></td>
							<td align="right"><b><?php echo number_format($total_tabungan_berjangka_credit); ?></b></td>
							<td align="right"><b><?php echo number_format($total_total_rf); ?></b></td>
							<td align="right"><b><?php echo number_format($total_total_tabungan); ?></b></td>
							<td align="right"><b><?php echo number_format($total_total_tabungan + $total_total_rf); ?></b></td>
							<td><?php echo $total_absen_h; ?></td>
							<td><?php echo $total_absen_s; ?></td>
							<td><?php echo $total_absen_c; ?></td>
							<td><?php echo $total_absen_i; ?></td>
							<td><?php echo $total_absen_a; ?></td>
							<td class="text-center"></td>
							<td class="text-center"></td>
							<td class="text-center"></td>
						</tr>
						<tr>     
							<td></td>
							<!--<td></td>-->
							<td colspan="19"><b><?php namahari($c->tsdaily_date); echo ", ".date('d-M-Y',strtotime($c->tsdaily_date)); ?><b></td>						
						</tr>
						<?php 
							$total_angsuranpokok 	= 0;
							$total_profit  			= 0;
							$total_tabwajib  		= 0;
							$total_tabungan_debet  	= 0;
							$total_tabungan_credit  = 0;
							$total_tabungan_berjangka_debet  = 0;
							$total_tabungan_berjangka_credit = 0;
							$total_total_rf  		= 0;
							$total_total_tabungan  	= 0;
							$total_absen_h	= 0;
							$total_absen_s	= 0;
							$total_absen_c	= 0;
							$total_absen_i	= 0; 
							$total_absen_a	= 0;
						?>
						<?php } ?>
						<?php
							$total_angsuranpokok += $c->tsdaily_angsuranpokok;
							$total_profit += $c->tsdaily_profit;
							$total_tabwajib += $c->tsdaily_tabwajib;
							$total_tabungan_debet += $c->tsdaily_tabungan_debet;
							$total_tabungan_credit += $c->tsdaily_tabungan_credit;
							$total_tabungan_berjangka_debet += $c->tsdaily_tabungan_berjangka_debet;
							$total_tabungan_berjangka_credit += $c->tsdaily_tabungan_berjangka_credit;
							$total_total_rf += $c->tsdaily_total_rf + $total_tabwajib ;
							$total_total_tabungan += $c->tsdaily_total_tabungan - $total_tabwajib ;
							$total_absen_h	+= $c->tsdaily_absen_h;
							$total_absen_s	+= $c->tsdaily_absen_s;
							$total_absen_c	+= $c->tsdaily_absen_c;
							$total_absen_i	+= $c->tsdaily_absen_i;
							$total_absen_a	+= $c->tsdaily_absen_a;
							$total_transaction += $c->tsdaily_total_transaction;
							$total_tr += $c->tsdaily_total_tr;
						?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>
							<!--<td><a href="<?php echo site_url().'/topsheet/ts_view/'.$c->tsdaily_topsheet_code; ?>" class="link"><?php echo $c->tsdaily_topsheet_code; ?></a></td>-->
							<!--<td><?php echo $c->tsdaily_date; ?></td>-->
							<td><a href="<?php echo site_url().'/group/view/'.$c->tsdaily_groupid; ?>" class="link"><?php echo $c->tsdaily_group; ?></a></td>
							<td align="right"><?php echo number_format($c->tsdaily_angsuranpokok); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_profit); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_tabwajib); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_tabungan_debet); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_tabungan_credit); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_tabungan_berjangka_debet); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_tabungan_berjangka_credit); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_total_rf + $c->tsdaily_tabwajib); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_total_tabungan - $c->tsdaily_tabwajib); ?></td>
							<td align="right"><?php echo number_format($c->tsdaily_total_tabungan + $c->tsdaily_total_rf); ?></td>
							<td><?php echo $c->tsdaily_absen_h; ?></td>
							<td><?php echo $c->tsdaily_absen_s; ?></td>
							<td><?php echo $c->tsdaily_absen_c; ?></td>
							<td><?php echo $c->tsdaily_absen_i; ?></td>
							<td><?php echo $c->tsdaily_absen_a; ?></td>
							<td align="center"><?php echo number_format($c->tsdaily_total_transaction); ?></td>
							<td align="center"><?php echo number_format($c->tsdaily_total_tr); ?></td>
							<td class="text-center">
								<a href="<?php echo site_url().'/topsheet/tsdaily_group/'.$c->tsdaily_groupid; ?>" title="Transaction History"><i class="fa fa-book"></i></a>
								<a href="<?php echo site_url().'/topsheet/ts_view/'.$c->tsdaily_topsheet_code; ?>" title="View Topsheet (<?php echo $c->tsdaily_topsheet_code; ?>)"><i class="fa fa-search"></i></a>
								<?php if($user_level==1){ ?>
								<a href="<?php echo site_url().'/topsheet/ts_delete/'.$c->tsdaily_topsheet_code; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a>
								<?php } ?>
							</td>
						</tr>
					
					<?php $tgl_end = $c->tsdaily_date; ?>
					<?php $no++; endforeach; ?>
					
					<tr>     
							<td></td>
							<!--<td></td>-->
							<!--<td></td>-->
							<td></td>
							<td align="right"><b><?php echo number_format($total_angsuranpokok); ?></b></td>
							<td align="right"><b><?php echo number_format($total_profit); ?></b></td>
							<td align="right"><b><?php echo number_format($total_tabwajib); ?></b></td>
							<td align="right"><b><?php echo number_format($total_tabungan_debet); ?></b></td>
							<td align="right"><b><?php echo number_format($total_tabungan_credit); ?></b></td>
							<td align="right"><b><?php echo number_format($total_total_rf); ?></b></td>
							<td align="right"><b><?php echo number_format($total_total_tabungan); ?></b></td>
							<td align="right"><b><?php echo number_format($total_total_tabungan + $total_total_rf); ?></b></td>
							<td><b><?php echo $total_absen_h; ?></b></td>
							<td><b><?php echo $total_absen_s; ?></b></td>
							<td><b><?php echo $total_absen_c; ?></b></td>
							<td><b><?php echo $total_absen_i; ?></b></td>
							<td><b><?php echo $total_absen_a; ?></b></td>
							<td align="center"><b><?php echo number_format($total_transaction); ?></b></td>
							<td align="center"><b><?php echo number_format($total_tr); ?></b></td>
							<td class="text-center"></td>
						</tr>
					
					</tbody>	
				</table>  
				<div class="text-center">
					<br/>
					<?php 
						$date_start=$this->input->post('date_start');
						$date_end=$this->input->post('date_end');
					?>
					<a href="<?php echo site_url()."/topsheet/tsdaily_apel_download/$date_start/$date_end"; ?>" target="_blank" class="btn btn-sm btn-primary" >Download Rekap Topsheet</a>
					<br/><br/>
				</div>				
			</div>
			
	</section>
	</div>
</section>	