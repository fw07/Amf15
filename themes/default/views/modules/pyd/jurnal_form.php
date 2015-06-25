<?php $client_account =  $this->uri->segment(3); ?>
<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
	
		<form class="form-horizontal" enctype="multipart/form-data" id="" action="" method="post">
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#personalinfo" data-toggle="tab"><?php echo $menu_title; ?></a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>', '</div>'); ?>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal</label>
								<div class="col-sm-4">
									<?php if(!$data->jurnal_date){ ?>
									<input type="text" name="jurnal_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="" placeholder="" value="<?php echo date('Y-m-d'); ?>">
									<?php }else{ ?>
									<input type="text" name="jurnal_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="" placeholder="" value="<?php echo set_value('jurnal_date', isset($data->jurnal_date) ? $data->jurnal_date : ''); ?>">
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nominal</label>
								<div class="col-sm-4">
									<input type="text" name="jurnal_nominal" class="form-control priceformat" id="" placeholder="" value="<?php echo set_value('jurnal_nominal', isset($data->jurnal_debet) ? number_format($data->jurnal_debet) : '0'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Account Debet</label>
								<div class="col-sm-4">
											<select name="jurnal_account_debet" class="form-control">
												<?php foreach($account as $a){  ?>
												<option value="<?php echo $a->accounting_code; ?>" <?php if($data->jurnal_account_debet == $a->accounting_code){ echo "selected"; } ?>><?php echo $a->accounting_name." (".$a->accounting_code.")"; ?></option>
												<?php } reset($account); ?>
											</select>
											<!--<br/>
											<input type="text" name="jurnal_debet" class="form-control" id="" placeholder="" value="<?php echo set_value('jurnal_debet', isset($data->jurnal_debet) ? $data->jurnal_debet : '0'); ?>" />-->
										
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Account Credit</label>
								<div class="col-sm-4">
											<select name="jurnal_account_credit" class="form-control">
												<?php foreach($account as $b){  ?>
												<option value="<?php echo $b->accounting_code; ?>" <?php if($data->jurnal_account_credit == $b->accounting_code){ echo "selected"; } ?> ><?php echo $b->accounting_name." (".$b->accounting_code.")"; ?></option>
												<?php } ?>
											</select>
											<!--<br/>
											<input type="text" name="jurnal_credit" class="form-control" id="" placeholder="" value="<?php echo set_value('jurnal_credit', isset($data->jurnal_credit) ? $data->jurnal_credit : '0'); ?>" />-->
										
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Keterangan</label> 
								<div class="col-sm-4">
									<input type="text" name="jurnal_remark" class="form-control" id="" placeholder="" value="<?php echo set_value('tabwajib_remark', isset($data->jurnal_remark) ? $data->jurnal_remark : ''); ?>" />
								</div>
							</div>
							<!--<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nomor Bukti</label> 
								<div class="col-sm-4">
									<input type="text" name="jurnal_nobukti" class="form-control" id="" placeholder="" value="<?php echo set_value('jurnal_nobukti', isset($data->jurnal_nobukti) ? $data->jurnal_nobukti : ''); ?>" />
								</div>
							</div>-->
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nomor Bukti</label> 
								<div class="col-sm-4">
									<select name="jurnal_nobukti_kode">
										<option value="">KODE</option>
										<option value="BK" <?php if($data->jurnal_nobukti_kode == "BK"){ echo "selected"; } ?>>BK</option>
										<option value="BM" <?php if($data->jurnal_nobukti_kode == "BM"){ echo "selected"; } ?>>BM</option>
										<option value="KK" <?php if($data->jurnal_nobukti_kode == "KK"){ echo "selected"; } ?>>KK</option>
										<option value="KM" <?php if($data->jurnal_nobukti_kode == "KM"){ echo "selected"; } ?>>KM</option>
										<option value="LL" <?php if($data->jurnal_nobukti_kode == "LL"){ echo "selected"; } ?>>LL</option>
									</select>
									<select name="jurnal_nobukti_nomor">
										<option value="">NOMOR</option>
										<?php for($i=1;$i<=999;$i++){ ?>
											<?php if($i<10){ ?><option value="<?php echo "00".$i; ?>" <?php if($data->jurnal_nobukti_nomor == "00$i"){ echo "selected"; } ?>><?php echo "00".$i; ?></option>
											<?php }elseif($i>=10 AND $i<100){ ?><option value="<?php echo "0".$i; ?>" <?php if($data->jurnal_nobukti_nomor == "0$i"){ echo "selected"; } ?> ><?php echo "0".$i; ?></option>
											<?php }elseif($i>=100 AND $i<1000){ ?><option value="<?php echo $i; ?>" <?php if($data->jurnal_nobukti_nomor == "$i"){ echo "selected"; } ?>><?php echo $i; ?></option><?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							
							
						</div>		
						
						
					</div>
					
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-3 ">
							<input type="hidden" name="jurnal_id" class="form-control" id="jurnal_id" placeholder="" value="<?php echo set_value('jurnal_id', isset($data->jurnal_id) ? $data->jurnal_id: ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	