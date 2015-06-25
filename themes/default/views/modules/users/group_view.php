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
						<li class="active"><a href="#personalinfo" data-toggle="tab">Group Information</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							
							<div class="form-group">
								<label for="group_area" class="col-sm-2 control-label">Area</label>
								<div class="col-sm-4">
									<input type="text" name="group_area" class="form-control" id="group_area" placeholder="" value="<?php echo set_value('area_name', isset($data->area_name) ? $data->area_name : ''); ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_branch" class="col-sm-2 control-label">Kantor Cabang</label>
								<div class="col-sm-4">
									<input type="text" name="group_branch" class="form-control" id="group_branch" placeholder="" value="<?php echo set_value('branch_name', isset($data->branch_name) ? $data->branch_name : ''); ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_tpl" class="col-sm-2 control-label">Petugas Pendamping</label>
								<div class="col-sm-4">
									<input type="text" name="group_tpl" class="form-control" id="group_tpl" placeholder="" value="<?php echo $data->officer_name; ?>" readonly />
								</div>
							</div>
							
							<div class="form-group">
								<label for="group_tpl" class="col-sm-2 control-label">Nomor Majelis</label>
								<div class="col-sm-4">
									<input type="text" name="group_number" class="form-control" id="group_number" placeholder="" value="<?php echo $data->group_number; ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_name" class="col-sm-2 control-label">Nama Majelis</label>
								<div class="col-sm-4">
									<input type="text" name="group_name" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('group_name', isset($data->group_name) ? $data->group_name : ''); ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_leader" class="col-sm-2 control-label">Ketua Majelis</label>
								<div class="col-sm-4">
									<input type="text" name="group_leader" class="form-control" id="group_leader" placeholder="" value="<?php echo set_value('group_leader', isset($data->group_leader) ? $data->group_leader : ''); ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_leaderphone" class="col-sm-2 control-label">Telp Ketua Majelis</label>
								<div class="col-sm-4">
									<input type="text" name="group_leaderphone" class="form-control" id="group_leaderphone" placeholder="" value="<?php echo set_value('group_leaderphone', isset($data->group_leaderphone) ? $data->group_leaderphone : ''); ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_date" class="col-sm-2 control-label">Tanggal Terbentuk</label>
								<div class="col-sm-4">
									<input type="text" name="group_date" class="form-control datepicker-input" data-date-format="dd-mm-yyyy" id="group_date" placeholder="" value="<?php echo set_value('group_date', isset($data->group_date) ? $data->group_date : ''); ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_rt" class="col-sm-2 control-label">Nama RT</label>
								<div class="col-sm-4">
									<input type="text" name="group_rt" class="form-control" id="group_rt" placeholder="" value="<?php echo set_value('group_rt', isset($data->group_rt) ? $data->group_rt : ''); ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_leader" class="col-sm-2 control-label">Kampung</label>
								<div class="col-sm-4">
									<input type="text" name="group_kampung" class="form-control" id="group_location" placeholder="" value="<?php echo set_value('group_kampung', isset($data->group_kampung) ? $data->group_kampung : ''); ?>"  readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_leader" class="col-sm-2 control-label">Desa</label>
								<div class="col-sm-4">
									<input type="text" name="group_desa" class="form-control" id="group_desa" placeholder="" value="<?php echo set_value('group_desa', isset($data->group_desa) ? $data->group_desa : ''); ?>"  readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_frequency" class="col-sm-2 control-label">Frekuensi Pertemuan</label>
								<div class="col-sm-4">
									<input type="text" name="group_frequency" class="form-control" id="group_frequency" placeholder="" value="<?php echo set_value('group_frequency', isset($data->group_frequency) ? $data->group_frequency : ''); ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="group_schedule_day" class="col-sm-2 control-label">Jadwal Pertemuan</label>
								<div class="col-sm-2">
									<input type="text" name="group_schedule_day" class="form-control" id="group_schedule_day" placeholder="" value="<?php echo set_value('group_schedule_day', isset($data->group_schedule_day) ? $data->group_schedule_day : ''); ?>" readonly />
								</div>
								<div class="col-sm-2">									
									<input type="text" name="group_schedule_time" class="form-control" id="group_schedule_time" placeholder="" value="<?php echo set_value('group_schedule_time', isset($data->group_schedule_time) ? $data->group_schedule_time : ''); ?>" readonly />
								</div>
							</div>
							
						</div>		
						
						
					</div>
					
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-2 ">
							<input type="hidden" name="group_id" class="form-control" id="group_id" placeholder="" value="<?php echo set_value('group_id', isset($data->group_id) ? $data->group_id : ''); ?>">
							<a href="<?php echo site_url()."/group/edit/".$data->group_id; ?>" class="btn btn-primary">Modify Data</a>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	