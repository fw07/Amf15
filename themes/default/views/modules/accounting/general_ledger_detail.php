<section class="main">
	<div class="container">
			
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3><?php echo "<b>".$account_name." - ".$account_no."</b>"; ?></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
					
		<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-4 m-b-xs pull-left text-left">
					<?php 
						if($this->input->post('date_start') AND $this->input->post('date_end') AND ($this->input->post('date_start') <= $this->input->post('date_end') )){
							echo "FILTER BY DATE : <br/><b>".$this->input->post('date_start')." : ".$this->input->post('date_end')."</b>"; 
						}
					?>
				</div>
			
				<!-- SEARCH FORM -->
				<form action="" method="post"> 
					<div class="col-sm-4 m-b-xs pull-right text-right">
						<input type="text" name="date_start" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd"  placeholder="Date Start">
						<input type="text" name="date_end" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd" placeholder="Date End">
						<button class="btn btn-sm btn-info" type="submit">Filter</button>
					</div>
				</form>	
					
			</div>
			
			<!-- TABLE BODY -->
			<div class="table-responsive">
					<table class="table table-striped m-b-none text-sm" data-ride="datatables">      
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th>Tanggal</th>
							<th>Deskripsi</th>
							<th class="text-right">Debet</th>
							<th class="text-right">Credit</th>
							<th class="text-right">Saldo</th>
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
						<?php $month=0; ?>
						<?php foreach($jurnal as $c):  ?>
							<?php 
								
								if($account_no == "1010004"){ 
									$kk = "$c->jurnal_remark" ;
									$get_kaskecil = $this->jurnal_model->get_kaskecil_detail($kk)->result();
									$kaskecil_detail = $get_kaskecil[0]->kaskecil_remark;
								}else{
									$kaskecil_detail = NULL;
								}								
								
								if($c->jurnal_account_debet == $account_no) { $saldo_debet = $c->jurnal_debet; }else{ $saldo_debet = 0; }
								if($c->jurnal_account_credit == $account_no) { $saldo_credit = $c->jurnal_credit; }else{ $saldo_credit = 0; }
								$saldo += ($saldo_debet - $saldo_credit);
								
								/*if($no==1) { $saldo = $total_saldo; 
								
								
								}else{ $saldo -= ($saldo_debet - $saldo_credit); }
							*/
								$month = date("M", strtotime($c->jurnal_date)); 
								if($month_last != $month){
							?>
							<tr> 	
								<td colspan="6"><?php echo "<b>".date("F Y", strtotime($c->jurnal_date))."</b>"; ?></td>
							</tr>
							<?php } ?>
							<tr>     
								<td align="center"  ><?php echo $no; ?></td>	
								<td ><?php echo date("Y-M-d", strtotime($c->jurnal_date)); ?></td>
								<td ><?php echo $c->jurnal_remark." ".$kaskecil_detail; ?></td>			              
								<td class="text-right"><?php if($c->jurnal_account_debet == $account_no) { echo number_format($c->jurnal_debet); }else{ echo "0"; } ?></td>
								<td class="text-right"><?php if($c->jurnal_account_credit == $account_no) { echo number_format($c->jurnal_credit); }else{ echo "0"; } ?></td>
								<td class="text-right"><?php echo ($saldo < 0 ? "(".number_format(abs($saldo)).")" : number_format($saldo)); ?></td>
								
							</tr>
							<?php $month_last = date("M", strtotime($c->jurnal_date)); ?>
							
						<?php $no++; endforeach; ?>
						<?php echo $list;?>
						</tbody>	
					</table>  
					
				</div>
				<div class="text-center">
					<br/>
					<a href="<?php echo site_url()."/accounting/general_ledger_detail_download/$account_no/".$this->input->post('date_start')."/".$this->input->post('date_end'); ?>" target="_blank" class="btn btn-sm btn-primary" >Download GL (<?php echo $account_no; ?>) (.xls)</a>
					<br/><br/>
				</div>
					
				<!--
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
				-->
			</section>
		</div>
</section>
