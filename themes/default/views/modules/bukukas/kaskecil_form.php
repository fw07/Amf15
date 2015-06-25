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
									<input type="text" name="kaskecil_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="" placeholder="" value="<?php echo date('Y-m-d'); ?>">
				
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Cabang</label>
								<div class="col-sm-4">
											<select name="kaskecil_cabang" class="form-control"  readonly>												
												<option value="0" <?php if($user_branch == "0") { echo "selected"; }?>>Pusat</option>
												<option value="1" <?php if($user_branch == "1") { echo "selected"; }?>>Ciseeng</option>
												<option value="4" <?php if($user_branch == "4") { echo "selected"; }?>>Jasinga</option>
												<option value="3" <?php if($user_branch == "3") { echo "selected"; }?>>Bojong Gede</option>
												<option value="2" <?php if($user_branch == "2") { echo "selected"; }?>>Kemang</option>
												<option value="5" <?php if($user_branch == "5") { echo "selected"; }?>>Tenjo</option>
												<option value="6" <?php if($user_branch == "6") { echo "selected"; }?>>Cangkuang</option>
											</select>
								</div>
							</div>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Keterangan</label> 
								<div class="col-sm-4">
									<input type="text" name="kaskecil_remark" class="form-control" id="" placeholder="" value="<?php echo set_value('kaskecil_remark', isset($data->kaskecil_remark) ? $data->kaskecil_remark : ''); ?>" />
								</div>
							</div>	
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Account</label> 
								<div class="col-sm-4">
									<select name="kaskecil_account" class="form-control">												
											<?php foreach($account as $a){  ?>
												<option value="<?php echo $a->accounting_code; ?>" <?php if($data->kaskecil_account == $a->accounting_code){ echo "selected"; } ?>><?php echo $a->accounting_name." (".$a->accounting_code.")"; ?></option>
											<?php } reset($account); ?>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Qty</label>
								<div class="col-sm-4">
									<input type="text" name="kaskecil_qty" class="form-control pleasechange" id="kaskecil_qty" placeholder="" value="<?php echo set_value('kaskecil_qty', isset($data->kaskecil_qty) ? $data->kaskecil_qty : '0'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Harga Satuan</label>
								<div class="col-sm-4">
									<input type="text" name="kaskecil_hargasatuan" class="form-control pleasechange" id="kaskecil_hargasatuan" placeholder="" value="<?php echo set_value('kaskecil_hargasatuan', isset($data->kaskecil_hargasatuan) ? $data->kaskecil_hargasatuan : '0'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Total</label>
								<div class="col-sm-4">
									<input type="text" name="kaskecil_total" class="form-control " id="kaskecil_total" placeholder="" value="<?php echo set_value('kaskecil_total', isset($data->kaskecil_total) ? $data->kaskecil_total : '0'); ?>" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nomor Bukti</label> 
								<div class="col-sm-4">
									<select name="kaskecil_nobukti_kode">
										<option value="">KODE</option>
										<option value="BK" <?php if($data->kaskecil_nobukti_kode == "BK"){ echo "selected"; } ?>>BK</option>
										<option value="BM" <?php if($data->kaskecil_nobukti_kode == "BM"){ echo "selected"; } ?>>BM</option>
										<option value="KK" <?php if($data->kaskecil_nobukti_kode == "KK"){ echo "selected"; } ?>>KK</option>
										<option value="KM" <?php if($data->kaskecil_nobukti_kode == "KM"){ echo "selected"; } ?>>KM</option>
										<option value="LL" <?php if($data->kaskecil_nobukti_kode == "LL"){ echo "selected"; } ?>>LL</option>
									</select>
									<select name="kaskecil_nobukti_nomor">
										<option value="">NOMOR</option>
										<?php for($i=1;$i<=999;$i++){ ?>
											<?php if($i<10){ ?><option value="<?php echo "00".$i; ?>" <?php if($data->kaskecil_nobukti_nomor == "00$i"){ echo "selected"; } ?>><?php echo "00".$i; ?></option>
											<?php }elseif($i>=10 AND $i<100){ ?><option value="<?php echo "0".$i; ?>" <?php if($data->kaskecil_nobukti_nomor == "0$i"){ echo "selected"; } ?> ><?php echo "0".$i; ?></option>
											<?php }elseif($i>=100 AND $i<1000){ ?><option value="<?php echo $i; ?>" <?php if($data->kaskecil_nobukti_nomor == "$i"){ echo "selected"; } ?>><?php echo $i; ?></option><?php } ?>
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
							<input type="hidden" name="kaskecil_id" class="form-control" id="kaskecil_id" placeholder="" value="<?php echo set_value('kaskecil_id', isset($data->kaskecil_id) ? $data->kaskecil_id: ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	


<script type="text/javascript">

$(document).ready(function() {
		$(".pleasechange").change(function() { 
			var qty = $("#kaskecil_qty").val();
			var hargasatuan = $("#kaskecil_hargasatuan").val();
			var total = qty * hargasatuan;
			$("#kaskecil_total").val(total);
			//alert(data);
		});	
		
});

</script>