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
						<li class="active"><a href="#personalinfo" data-toggle="tab">Area Information</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>', '</div>'); ?>
							
							
							
							
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Area Name *</label>
								<div class="col-sm-4">
									<input type="text" name="area_name" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('area_name', isset($data->area_name) ? $data->area_name : ''); ?>" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Area Code *</label>
								<div class="col-sm-4">
									<input type="text" name="area_code" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('area_code', isset($data->area_code) ? $data->area_code : ''); ?>" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Leader</label>
								<div class="col-sm-4">
									<input type="text" name="area_leader" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('area_leader', isset($data->area_leader) ? $data->area_leader : ''); ?>" />
								</div>
							</div>
							
							
							
							
						</div>		
						
						
					</div>
					
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-3 ">
							<input type="hidden" name="branch_id" class="form-control" id="area_id" placeholder="" value="<?php echo set_value('area_id', isset($data->area_id) ? $data->area_id : ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	