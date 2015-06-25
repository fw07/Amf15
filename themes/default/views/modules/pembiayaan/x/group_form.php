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
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>', '</div>'); ?>
							
							<div class="form-group">
								<label for="group_branch" class="col-sm-3 control-label">Area *</label>
								<div class="col-sm-4">
									<select name="group_area" class="form-control">
										<!--<?php foreach($area as $a):  ?>
										<option value="<?php echo $a->area_id; ?>" ><?php echo $a->area_name; ?></option>
										
										<?php endforeach; ?>-->
										<option value="1" ><?php echo "Bogor Utara"; ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="group_branch" class="col-sm-3 control-label">Kantor Cabang *</label>
								<div class="col-sm-4">
									<select name="group_branch" class="form-control">
										<?php foreach($branch as $b):  ?>
										<option value="<?php echo $b->branch_id; ?>" ><?php echo $b->branch_name; ?></option>
										<?php endforeach; ?>
										
									</select>
								</div>
							</div>							
							<div class="form-group">
								<label for="group_tpl" class="col-sm-3 control-label">Petugas Pendamping *</label>
								<div class="col-sm-4">
									<select name="group_tpl" class="form-control">
										<?php if(!$data->group_tpl){ ?>
										<option value="">Pilih Petugas Pendamping</option>
										<?php } ?>
										<?php foreach($officer as $tpl):  ?>
										<option value="<?php echo $tpl->officer_id; ?>" <?php if($data->group_tpl == $tpl->officer_id) { echo "selected";} ?> ><?php echo $tpl->officer_name; ?></option>
										<?php endforeach; ?>
								  </select>
								</div>
							</div>
							
							<?php if($data->group_number){ ?>
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Nomor Majelis</label>
								<div class="col-sm-4">
									<input type="text" name="group_number" class="form-control" id="group_number" placeholder="" value="<?php echo set_value('group_number', isset($data->group_number) ? $data->group_number : ''); ?>" readonly />
									<input type="hidden" name="group_no" class="form-control" id="group_no" placeholder="" value="<?php echo set_value('group_no', isset($data->group_no) ? $data->group_no : ''); ?>" readonly />
								</div>
							</div>
							<?php } ?>
							<div class="form-group">
								<label for="group_date" class="col-sm-3 control-label">Tanggal Terbentuk *</label>
								<div class="col-sm-4">
									<?php if(!$data->group_date){ ?>
									<input type="text" name="group_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="group_date" placeholder="" value="<?php echo date('Y-m-d'); ?>">
									<?php }else{ ?>
									<input type="text" name="group_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="group_date" placeholder="" value="<?php echo set_value('group_date', isset($data->group_date) ? $data->group_date : ''); ?>">
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Nama Majelis *</label>
								<div class="col-sm-4">
									<input type="text" name="group_name" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('group_name', isset($data->group_name) ? $data->group_name : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="group_leader" class="col-sm-3 control-label">Ketua Majelis *</label>
								<div class="col-sm-4">
									<input type="text" name="group_leader" class="form-control" id="group_leader" placeholder="" value="<?php echo set_value('group_leader', isset($data->group_leader) ? $data->group_leader : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="group_leaderphone" class="col-sm-3 control-label">Telp Ketua Majelis</label>
								<div class="col-sm-4">
									<input type="text" name="group_leaderphone" class="form-control" id="group_leaderphone" placeholder="" value="<?php echo set_value('group_leaderphone', isset($data->group_leaderphone) ? $data->group_leaderphone : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="group_desa" class="col-sm-3 control-label">Desa</label>
								<div class="col-sm-4">
									<input type="text" name="group_desa" class="form-control" id="group_desa" placeholder="" value="<?php echo set_value('group_desa', isset($data->group_desa) ? $data->group_desa : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="group_kampung" class="col-sm-3 control-label">Kampung</label>
								<div class="col-sm-4">
									<input type="text" name="group_kampung" class="form-control priceformat"  id="group_kampung" placeholder="" value="<?php echo set_value('group_kampung', isset($data->group_kampung) ? $data->group_kampung : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="group_rt" class="col-sm-3 control-label">Nama RT</label>
								<div class="col-sm-4">
									<input type="text" name="group_rt" class="form-control" id="group_rt" placeholder="" value="<?php echo set_value('group_rt', isset($data->group_rt) ? $data->group_rt : ''); ?>">
								</div>
							</div>							
							<!--
							<div class="form-group">
								<label for="group_tanggungrenteng" class="col-sm-3 control-label">Tanggung Renteng</label>
								<div class="col-sm-4">
									<select name="group_tanggungrenteng" class="form-control">
										<option value="1" <?php if($data->group_tanggungrenteng == "1"){ echo "selected"; } ?>>Ada</option>
										<option value="0" <?php if($data->group_tanggungrenteng == "0"){ echo "selected"; } ?>>Tidak Ada</option>
									</select>
								</div>
							</div>
							-->
							<div class="form-group">
								<label for="group_frequency" class="col-sm-3 control-label">Frekuensi Pertemuan</label>
								<div class="col-sm-4">
									<select name="group_frequency" class="form-control">
										<option value="Mingguan" <?php if($data->group_frequency == "Mingguan"){ echo "selected"; } ?>>Mingguan</option>
										<option value="Dwi Mingguan" <?php if($data->group_frequency == "Dwi Mingguan"){ echo "selected"; } ?>>Dwi Mingguan</option>
										<option value="Bulanan" <?php if($data->group_frequency == "Bulanan"){ echo "selected"; } ?>>Bulanan</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="group_schedule_day" class="col-sm-3 control-label">Jadwal Pertemuan</label>
								<div class="col-sm-3">									
									<select name="group_schedule_day" class="form-control">
										<option value="Senin"	<?php if($data->group_schedule_day == "Senin"){ echo "selected"; } ?>>Senin</option>
										<option value="Selasa" 	<?php if($data->group_schedule_day == "Selasa"){ echo "selected"; } ?>>Selasa</option>
										<option value="Rabu" 	<?php if($data->group_schedule_day == "Rabu"){ echo "selected"; } ?>>Rabu</option>
										<option value="Kamis" 	<?php if($data->group_schedule_day == "Kamis"){ echo "selected"; } ?>>Kamis</option>
										<option value="Jumat" 	<?php if($data->group_schedule_day == "Jumat"){ echo "selected"; } ?>>Jumat</option>
									</select>
								</div>
								<div class="col-sm-3">									
									<select name="group_schedule_time" class="form-control">
										<option value="07.00 - 08.00"	<?php if($data->group_schedule_time == "07.00 - 08.00"){ echo "selected"; } ?> >07.00 - 08.00</option>
										<option value="08.00 - 09.00"	<?php if($data->group_schedule_time == "08.00 - 09.00"){ echo "selected"; } ?> >08.00 - 09.00</option>
										<option value="09.00 - 10.00"	<?php if($data->group_schedule_time == "09.00 - 10.00"){ echo "selected"; } ?> >09.00 - 10.00</option>
										<option value="10.00 - 11.00"	<?php if($data->group_schedule_time == "10.00 - 11.00"){ echo "selected"; } ?> >10.00 - 11.00</option>
										<option value="11.00 - 12.00"	<?php if($data->group_schedule_time == "11.00 - 12.00"){ echo "selected"; } ?> >11.00 - 12.00</option>
										<option value="12.00 - 13.00"	<?php if($data->group_schedule_time == "12.00 - 13.00"){ echo "selected"; } ?> >12.00 - 13.00</option>
										<option value="13.00 - 14.00"	<?php if($data->group_schedule_time == "13.00 - 14.00"){ echo "selected"; } ?> >13.00 - 14.00</option>
										<option value="14.00 - 15.00"	<?php if($data->group_schedule_time == "14.00 - 15.00"){ echo "selected"; } ?> >14.00 - 15.00</option>
										<option value="15.00 - 16.00"	<?php if($data->group_schedule_time == "15.00 - 16.00"){ echo "selected"; } ?> >15.00 - 16.00</option>
										<option value="16.00 - 17.00"	<?php if($data->group_schedule_time == "16.00 - 17.00"){ echo "selected"; } ?> >16.00 - 17.00</option>
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
							<input type="hidden" name="group_id" class="form-control" id="group_id" placeholder="" value="<?php echo set_value('group_id', isset($data->group_id) ? $data->group_id : ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	