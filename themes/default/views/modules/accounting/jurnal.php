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
				<div class="col-lg-1">
				<a href="<?php echo site_url()."/accounting/add/"; ?>" class="btn btn-sm btn-primary" >Tambah Jurnal</a>
				</div>
				
				
				<!-- SEARCH FORM -->
				<form action="" method="post"> 
					<div class="col-sm-6 m-b-xs pull-right text-right">
						<select name="key" class="input-sm form-control input-s-sm inline">
							<!--<option value="date">Tanggal</option>-->
							<option value="account_debet">Account</option>
							<option value="remark">Keterangan</option>
						</select>
						<input type="text" name="q" class="input-sm form-control input-s-sm inline" placeholder="Search" />
						<input type="text" name="date_start" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd"  placeholder="Date Start">
						<input type="text" name="date_end" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd" placeholder="Date End">
						
						<button class="btn btn-sm btn-info" type="submit">Go!</button>
					</div>
				</form>	
			</div>
			
			<!-- TABLE BODY -->
			<div class="table-responsive">
					<table class="table table-striped m-b-none text-sm" data-ride="datatables">      
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th width="120px">Tanggal</th>
							<?php if($this->session->userdata('user_branch') == 0){ ?><th>Cabang</th><?php } ?>
							<th>Deskripsi</th>
							<th>Nomor Bukti</th>
							<th>Account</th>
							<th width="120px" class="text-right">Debet</th>
							<th width="120px" class="text-right">Credit</th>
							<!--<th width="100px" class="text-center">Manage</th>-->
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
								$jurnal_date = $c->jurnal_date;
								$jurnal_month = substr($jurnal_date, 5, 2); 
							?>
							<tr>     
								<td align="center" rowspan="2" ><?php echo $no; ?></td>	
								<td rowspan="2"><?php echo date("d-M-Y", strtotime($c->jurnal_date)); ?></td>
								<?php if($this->session->userdata('user_branch') == 0){ ?><td rowspan="2"><?php echo $c->branch_name; ?></td><?php } ?>
								<td rowspan="2"><?php echo $c->jurnal_remark; ?></td>	
								<td rowspan="2"><?php if($c->jurnal_nobukti_kode != "-" OR $c->jurnal_nobukti_nomor != "-"){ echo $c->jurnal_nobukti_kode."/".$jurnal_month."/".$c->jurnal_nobukti_nomor; } ?></td>		              
								<td><?php echo $c->accounting_debet_code." ".$c->accounting_debet_name; ?></td>
								<td class="text-right"><?php echo number_format($c->jurnal_debet,2); ?></td>
								<td class="text-right">0</td>
								<!--<td rowspan="2" class="text-center">
									<a href="<?php echo site_url()."/accounting/jurnal_edit/".$c->jurnal_id; ?>" title="Edit"><i class="fa fa-pencil"></i></a> 
									<a href="<?php echo site_url()."/accounting/jurnal_delete/".$c->jurnal_id; ?>" title="Delete" onclick="return confirmDialog();"><i class="fa fa-trash-o"></i></a> 
								</td>-->
							</tr>
							<tr>     				              
								<td><?php echo $c->accounting_credit_code." ".$c->accounting_credit_name; ?></td>
								<td class="text-right">0</td>
								<td class="text-right"><?php echo number_format($c->jurnal_credit,2); ?></td> 
								
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
				
					<div class="text-center">
						<br/>
						<a href="<?php echo site_url()."/accounting/jurnal_excel/$date_start/$date_end/$key/$q"; ?>" target="_blank" class="btn btn-sm btn-primary" >Download Jurnal (.xls)</a>
						<br/><br/>
					</div>
			</section>
		</div>
</section>
