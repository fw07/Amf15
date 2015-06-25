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
						<li class="active"><a href="#personalinfo" data-toggle="tab">Officer Information</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>', '</div>'); ?>
							
							<div class="form-group">
								<label for="officer_branch" class="col-sm-2 control-label">Kantor Cabang</label>
								<div class="col-sm-4">
									<select name="officer_branch" class="form-control">
										<?php if(!$data->officer_branch){ ?>
										<option value="">Pilih Kantor Cabang</option>
										<?php } ?>
										<?php foreach($branch as $b):  ?>
										<option value="<?php echo $b->branch_id; ?>" <?php if($data->group_branch == $b->branch_id) { echo "selected";} ?> ><?php echo $b->branch_name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="officer_name" class="col-sm-2 control-label">Nama Lengkap</label>
								<div class="col-sm-4">
									<input type="text" name="officer_name" class="form-control" id="officer_name" placeholder="" value="<?php echo set_value('officer_name', isset($data->officer_name) ? $data->officer_name : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="officer_number" class="col-sm-2 control-label">Nomor Pegawai</label>
								<div class="col-sm-4">
									<input type="text" name="officer_number" class="form-control" id="officer_number" placeholder="" value="<?php echo set_value('officer_number', isset($data->officer_number) ? $data->officer_number : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="officer_born" class="col-sm-2 control-label">Tempat, Tgl Lahir</label>
								<div class="col-sm-2">
									<input type="text" name="officer_bornplace" class="form-control" id="officer_bornplace" placeholder="Tempat Lahir" value="<?php echo set_value('officer_bornplace', isset($data->officer_bornplace) ? $data->officer_bornplace : ''); ?>">
								</div>
								<div class="col-sm-2">
									<input type="text" name="officer_borndate" class="form-control datepicker-input" data-date-format="dd-mm-yyyy" id="officer_born" placeholder="dd-mm-yyyy" value="<?php echo set_value('officer_borndate', isset($data->officer_borndate) ? $data->officer_borndate : ''); ?>">
								</div>
							</div>
							
							<div class="form-group">
								<label for="officer_sex" class="col-sm-2 control-label">Jenis Kelamin</label>
								<div class="col-sm-4">
									<input type="radio" name="officer_sex" id="officer_sex" value="Male" <?php if($data->officer_sex == "Male") { echo "checked"; } ?> /> Laki-laki  &nbsp;&nbsp;
									<input type="radio" name="officer_sex" id="officer_sex" value="Female" <?php if($data->officer_sex == "Female") { echo "checked"; } ?> /> Perempuan 
								 </div>
							</div>
							<div class="form-group">
								<label for="officer_phone" class="col-sm-2 control-label">Phone Number</label>
								<div class="col-sm-4">
									<input type="text" name="officer_phone" class="form-control" id="officer_phone" placeholder="" value="<?php echo set_value('officer_phone', isset($data->officer_phone) ? $data->officer_phone : ''); ?>">
								</div>
							</div>
							
							</div>
							
						</div>
					
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-2 ">
							<input type="hidden" name="officer_id" class="form-control" id="officer_id" placeholder="" value="<?php echo set_value('officer_id', isset($data->officer_id) ? $data->officer_id : ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	