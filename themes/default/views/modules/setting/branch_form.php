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
						<li class="active"><a href="#personalinfo" data-toggle="tab">Branch Information</a></li>
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
									<select name="branch_area" class="form-control">
										<?php if(!$data->branch_area){ ?>
										<option value="">Pilih Area</option>
										<?php } ?>
										<?php foreach($area as $b):  ?>
										<option value="<?php echo $b->area_id; ?>" <?php if($data->branch_area == $b->area_id) { echo "selected";} ?> ><?php echo $b->area_name; ?></option>
										<?php endforeach; ?>
										
									</select>
								</div>
							</div>		
							
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Branch Name *</label>
								<div class="col-sm-4">
									<input type="text" name="branch_name" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('branch_name', isset($data->branch_name) ? $data->branch_name : ''); ?>" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Branch Code *</label>
								<div class="col-sm-4">
									<input type="text" name="branch_code" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('branch_code', isset($data->branch_code) ? $data->branch_code : ''); ?>" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Branch Number *</label>
								<div class="col-sm-4">
									<input type="text" name="branch_number" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('branch_number', isset($data->branch_number) ? $data->branch_number : ''); ?>" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Location</label>
								<div class="col-sm-4">
									<input type="text" name="branch_location" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('branch_location', isset($data->branch_location) ? $data->branch_location : ''); ?>" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Leader</label>
								<div class="col-sm-4">
									<input type="text" name="branch_leader" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('branch_leader', isset($data->branch_leader) ? $data->branch_leader : ''); ?>" />
								</div>
							</div>
							
							
							
							
						</div>		
						
						
					</div>
					
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-3 ">
							<input type="hidden" name="branch_id" class="form-control" id="user_id" placeholder="" value="<?php echo set_value('branch_id', isset($data->branch_id) ? $data->branch_id : ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	