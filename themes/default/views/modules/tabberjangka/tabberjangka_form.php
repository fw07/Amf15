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
						<li class="active"><a href="#personalinfo" data-toggle="tab">Tabungan Berjangka</a></li>
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
									<input type="text" name="tabberjangka_account" class="form-control" id="" placeholder="" value="<?php echo  $client_account; ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal</label>
								<div class="col-sm-4">
									<?php if(!$data->tabberjangka_date){ ?>
									<input type="text" name="tabberjangka_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="" placeholder="" value="<?php echo date('Y-m-d'); ?>">
									<?php }else{ ?>
									<input type="text" name="tabberjangka_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="" placeholder="" value="<?php echo set_value('tabberjangka_tr_date', isset($data->tabberjangka_tr_date) ? $data->tabberjangka_date : ''); ?>">
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Debet</label>
								<div class="col-sm-4">
									<input type="text" name="tabberjangka_debet" class="form-control" id="" placeholder="" value="<?php echo set_value('tabberjangka_debet', isset($data->tabberjangka_tr_debet) ? $data->tabberjangka_tr_debet : '0'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kredit</label>
								<div class="col-sm-4">
									<input type="text" name="tabberjangka_credit" class="form-control" id="" placeholder="" value="<?php echo set_value('tabberjangka_credit', isset($data->tabberjangka_tr_credit) ? $data->tabberjangka_tr_credit : '0'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Remark</label>
								<div class="col-sm-4">
									<input type="text" name="tabberjangka_remark" class="form-control" id="" placeholder="" value="<?php echo set_value('tabberjangka_remark', isset($data->tabberjangka_tr_remark) ? $data->tabberjangka_tr_remark : ''); ?>" />
								</div>
							</div>
							
							
						</div>		
						
						
					</div>
					
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-3 ">
							<input type="hidden" name="tabberjangka_client" class="form-control" id="tabberjangka_client" placeholder="" value="<?php echo set_value('tabberjangka_client', isset($data->tabberjangka_client) ? $data->tabberjangka_client : ''); ?>">
							<input type="hidden" name="tabberjangka_saldo" class="form-control" id="tabberjangka_saldo" placeholder="" value="<?php echo set_value('tabberjangka_saldo', isset($data->tabberjangka_saldo) ? $data->tabberjangka_saldo : ''); ?>">
							<input type="hidden" name="tabberjangka_total_debet" class="form-control" id="tabberjangka_total_debet" placeholder="" value="<?php echo set_value('tabberjangka_total_debet', isset($data->tabberjangka_debet) ? $data->tabberjangka_debet : ''); ?>">
							<input type="hidden" name="tabberjangka_total_credit" class="form-control" id="tabberjangka_total_credit" placeholder="" value="<?php echo set_value('tabberjangka_total_credit', isset($data->tabberjangka_credit) ? $data->tabberjangka_credit: ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	