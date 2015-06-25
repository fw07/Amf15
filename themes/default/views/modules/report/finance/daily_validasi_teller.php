<section class="main">
	<div class="container">
	<div class="col-md-12">
	<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
	</div>
	
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
	
	<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-6 m-b-xs">
					<h4>Cabang <b><?php echo $report['cabang']; ?></b> (<?php echo $this->input->post('date');?>)</h4>
				</div>
				<div class="col-sm-6 m-b-xs text-right">
					<form method="post" action="">
					<input type="hidden" name="branch" value="<?php echo $this->input->post('branch'); ?>" />
					<input type="text" name="date" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Select Date" />
					<button type="submit" name="submit" class="btn btn-xs btn-info" >Filter</button> 
					</form>
				</div>

				
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm" id="yourHtmTable">              
					<thead>
					  <tr>
						<th class="text-left">FO</th>
						<th class="text-left">Majelis</th>
						<th class="text-right">RF</th>
						<th class="text-right">Tab</th>
						<th class="text-right">Jumlah</th>
						<th class="text-right">Pencairan</th>
						<th class="text-right">Gagal Dropping</th>
					  </tr>
					</thead> 
					<tbody>	
						<?php $no=1; foreach($tsdaily AS $row):  ?>
						<?php 
							$tpl = $row->officer_name;
							$tplid = $row->officer_id;							
							$pencairan =  $this->finance_model->count_daily_pencairan_by_group( $row->tsdaily_groupid, $this->input->post('date'));
							
							$grandtotal_rf += $row->tsdaily_total_rf;
							$grandtotal_tab += $row->tsdaily_total_tabungan;
							$grandtotal_rftab += $row->tsdaily_total;
							$grandtotal_pencairan += $pencairan;
							
							$arr_total_rf[$tplid] += $row->tsdaily_total_rf;
							$arr_total_tab[$tplid] += $row->tsdaily_total_tabungan;
							$arr_total_rftab[$tplid] += $row->tsdaily_total;
							$arr_total_pencairan[$tplid] += $pencairan;
							
							
							if($tpl == $tpl_before){
								$total_rf += $row->tsdaily_total_rf;
								$total_tab += $row->tsdaily_total_tabungan;
								$total_rftab += $row->tsdaily_total;
								$total_pencairan += $pencairan;
							}
						?>
						<?php if($tpl != $tpl_before AND $no!=1){ ?>
						<tr> 
							<td colspan="2" class="text-center"><b>TOTAL</b></td>
							<td class="text-right"><b><?php echo ($total_rf < 0 ? "(".number_format(abs($total_rf)).")" : number_format($total_rf)) ?></b></td>
							<td class="text-right"><b><?php echo ($total_tab < 0 ? "(".number_format(abs($total_tab)).")" : number_format($total_tab)) ?></b></td>
							<td class="text-right"><b><?php echo ($total_rftab < 0 ? "(".number_format(abs($total_rftab)).")" : number_format($total_rftab)) ?></b></td>
							<td class="text-right"><b><?php echo number_format($arr_total_pencairan[$tplid_before]); ?></b></td>
							<td class="text-right">0</td>
						</tr>
						<?php 
							$total_rf = 0;
							$total_tab = 0;
							$total_rftab = 0;
							$total_pencairan = 0;
							}
						?>
						<tr> 
							<td><?php echo $row->officer_name; ?></td>
							<td><?php echo $row->group_name; ?></td>
							<td class="text-right"><?php echo ($row->tsdaily_total_rf < 0 ? "(".number_format(abs($row->tsdaily_total_rf)).")" : number_format($row->tsdaily_total_rf)) ?></td>
							<td class="text-right"><?php echo ($row->tsdaily_total_tabungan < 0 ? "(".number_format(abs($row->tsdaily_total_tabungan)).")" : number_format($row->tsdaily_total_tabungan)) ?></td>
							<td class="text-right"><?php echo ($row->tsdaily_total < 0 ? "(".number_format(abs($row->tsdaily_total)).")" : number_format($row->tsdaily_total)) ?></td>
							<td class="text-right"><?php echo ($pencairan < 0 ? "(".number_format(abs($pencairan)).")" : number_format($pencairan)) ?></td>
							<td class="text-right">0</td>
						</tr>
						<?php $tpl_before = $row->officer_name; $tplid_before = $row->officer_id;?>
						<?php $no++; endforeach; ?>
						<tr> 
							<td colspan="2" class="text-center"><b>TOTAL</b></td>
							<td class="text-right"><b><?php echo ($total_rf < 0 ? "(".number_format(abs($total_rf)).")" : number_format($total_rf)) ?></b></td>
							<td class="text-right"><b><?php echo ($total_tab < 0 ? "(".number_format(abs($total_tab)).")" : number_format($total_tab)) ?></b></td>
							<td class="text-right"><b><?php echo ($total_rftab < 0 ? "(".number_format(abs($total_rftab)).")" : number_format($total_rftab)) ?></b></td>
							<td class="text-right"><b><?php echo number_format($arr_total_pencairan[$tplid_before]); ?></b></td>
							<td class="text-right">0</td>
						</tr>
						<tr> 
							<td colspan="7" class="text-center">&nbsp;&nbsp;</td>							
						</tr>
						<tr> 
							<td colspan="2" class="text-center"><b>GRAND TOTAL</b></td>
							<td class="text-right"><b><?php echo ($grandtotal_rf < 0 ? "(".number_format(abs($grandtotal_rf)).")" : number_format($grandtotal_rf)) ?></b></td>
							<td class="text-right"><b><?php echo ($grandtotal_tab < 0 ? "(".number_format(abs($grandtotal_tab)).")" : number_format($grandtotal_tab)) ?></b></td>
							<td class="text-right"><b><?php echo ($grandtotal_rftab < 0 ? "(".number_format(abs($grandtotal_rftab)).")" : number_format($grandtotal_rftab)) ?></b></td>
							<td class="text-right"><b><?php echo ($grandtotal_pencairan < 0 ? "(".number_format(abs($grandtotal_pencairan)).")" : number_format($grandtotal_pencairan)) ?></b></td>
							<td class="text-right">0</td>
						</tr>
					</tbody>	
				</table>  
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-sm-4 text-left">  
					<a href="#" onClick ="goExport()" class="btn btn-sm btn-success" ><i class="fa fa-file-excel-o"></i>&nbsp; Export Excel</a>
 					</div>
					<script>
					function goExport(){
						$("#yourHtmTable").table2excel({
							exclude: ".excludeThisClass",
							name: "Validasi Teller",
							filename: "Validasi Teller" 
						});}
					</script>
				</div>
			</footer>
			
	</section>
</div>
	
	
	
</section>	