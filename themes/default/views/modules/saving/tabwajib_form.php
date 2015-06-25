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
						<li class="active"><a href="#personalinfo" data-toggle="tab">Tabungan Wajib</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>', '</div>'); ?>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nomor Rekening</label>
								<div class="col-sm-4">
									<input type="text" name="tabwajib_account" class="form-control" id="" placeholder="" value="<?php echo  $client_account; ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal</label>
								<div class="col-sm-4">
									<?php if(!$data->tabwajib_date){ ?>
									<input type="text" name="tabwajib_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="" placeholder="" value="<?php echo date('Y-m-d'); ?>">
									<?php }else{ ?>
									<input type="text" name="tabwajib_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="" placeholder="" value="<?php echo set_value('tabwajib_tr_date', isset($data->tabwajib_tr_date) ? $data->tabwajib_date : ''); ?>">
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Debet</label>
								<div class="col-sm-4">
									<input type="text" name="tabwajib_debet" class="form-control" id="" placeholder="" value="<?php echo set_value('tabwajib_debet', isset($data->tabwajib_tr_debet) ? $data->tabwajib_tr_debet : '0'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kredit</label>
								<div class="col-sm-4">
									<input type="text" name="tabwajib_credit" class="form-control" id="" placeholder="" value="<?php echo set_value('tabwajib_credit', isset($data->tabwajib_tr_credit) ? $data->tabwajib_tr_credit : '0'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Remark</label>
								<div class="col-sm-4">
									<input type="text" name="tabwajib_remark" class="form-control" id="" placeholder="" value="<?php echo set_value('tabwajib_remark', isset($data->tabwajib_tr_remark) ? $data->tabwajib_tr_remark : ''); ?>" />
								</div>
							</div>
							
							
						</div>		
						
						
					</div>
					
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-3 ">
							<input type="hidden" name="tabwajib_client" class="form-control" id="tabwajib_client" placeholder="" value="<?php echo set_value('tabwajib_client', isset($data->tabwajib_client) ? $data->tabwajib_client : ''); ?>">
							<input type="hidden" name="tabwajib_saldo" class="form-control" id="tabwajib_saldo" placeholder="" value="<?php echo set_value('tabwajib_saldo', isset($data->tabwajib_saldo) ? $data->tabwajib_saldo : ''); ?>">
							<input type="hidden" name="tabwajib_total_debet" class="form-control" id="tabwajib_total_debet" placeholder="" value="<?php echo set_value('tabwajib_total_debet', isset($data->tabwajib_debet) ? $data->tabwajib_debet : ''); ?>">
							<input type="hidden" name="tabwajib_total_credit" class="form-control" id="tabwajib_total_credit" placeholder="" value="<?php echo set_value('tabwajib_total_credit', isset($data->tabwajib_credit) ? $data->tabwajib_credit: ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	