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
						<li class="active"><a href="#personalinfo" data-toggle="tab">User Information</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>', '</div>'); ?>
							
							
							<div class="form-group">
								<label for="group_branch" class="col-sm-3 control-label">Kantor Cabang *</label>
								<div class="col-sm-4">
									<select name="user_branch" class="form-control" readonly >
										<?php if(!$data->group_branch){ ?>
										<option value="">Pilih Kantor Cabang</option>
										<?php } ?>
										<?php foreach($branch as $b):  ?>
										<option value="<?php echo $b->branch_id; ?>" <?php if($data->user_branch == $b->branch_id) { echo "selected";} ?> ><?php echo $b->branch_name; ?></option>
										<?php endforeach; ?>
										
									</select>
								</div>
							</div>		
							
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Username *</label>
								<div class="col-sm-4">
									<input type="text" name="username" class="form-control" id="group_name" placeholder="" readonly value="<?php echo set_value('username', isset($data->username) ? $data->username : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Full Name *</label>
								<div class="col-sm-4">
									<input type="text" name="fullname" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('fullname', isset($data->fullname) ? $data->fullname : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="group_leader" class="col-sm-3 control-label">Password *</label>
								<div class="col-sm-4">
									<input type="password" name="password" class="form-control" id="group_leader" placeholder="" value="" />
								</div>
							</div>
							<div class="form-group">
								<label for="group_leader" class="col-sm-3 control-label">Confirm Password *</label>
								<div class="col-sm-4">
									<input type="password" name="password2" class="form-control" id="group_leader" placeholder="" value="" />
								</div>
							</div>
							<div class="form-group">
								<label for="group_frequency" class="col-sm-3 control-label">Level *</label>
								<div class="col-sm-4">
									<select name="user_level" class="form-control" readonly>
										<option value="1" <?php if($data->user_level == "1"){ echo "selected"; } ?>>Administrator</option>
										<option value="2" <?php if($data->user_level == "2"){ echo "selected"; } ?>>Manager</option>
										<option value="3" <?php if($data->user_level == "3"){ echo "selected"; } ?>>Data Entry</option>
										<option value="4" <?php if($data->user_level == "4"){ echo "selected"; } ?>>Viewer</option>
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
							<input type="hidden" name="user_id" class="form-control" id="user_id" placeholder="" value="<?php echo set_value('user_id', isset($data->user_id) ? $data->user_id : ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	