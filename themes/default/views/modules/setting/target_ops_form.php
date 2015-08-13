<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		<?php  	if($form_type == 'edit') $hidden = array('tid' => $target[0]['target_id'], 'type' => $form_type);
				else $hidden = array('type' => $form_type);
				$attributes = array('class' => 'form-horizontal', 'data-validate' => 'parsley');
          		echo form_open_multipart('', $attributes, $hidden);
        ?>
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#personalinfo" data-toggle="tab">Target Parameter Info</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>', '</div>'); ?>
							 
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Kategori Target *</label>
								<div class="col-sm-4">
									<input type="text" name="target_category" class="form-control" id="group_name" placeholder="" value="<?php echo $target[0]['target_category']; ?>" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Item Target *</label>
								<div class="col-sm-4">
									<input type="text" name="target_item" class="form-control" id="group_name" placeholder="" value="<?php echo $target[0]['target_item']; ?>" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Nilai Target</label>
								<div class="col-sm-4">
									<input type="text" name="target_amount" class="form-control" id="group_name" placeholder="" value="<?php echo $target[0]['target_amount']; ?>" />
								</div>
							</div>

							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Officer</label>
								<div class="col-sm-4">
									<select name="target_officer" data-required="true" class="form-control" >
									<?php
										echo '<option value="0">Pilih FO untuk diberi target</option>'; 
										foreach ($officer as $o) {
									  		if($form_type == 'edit' && $target[0]['target_officer'] == $o->officer_id)
									  			{ $selected = 'selected="selected"'; }
									  		else { $selected = ''; }
									  		echo '<option value="'.$o->officer_id.'" '.$selected.'>'.$o->officer_name.'</option>';
									  	}	
									  ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Cabang</label>
								<div class="col-sm-4">
									<select name="target_branch" data-required="true" class="form-control" >
									<?php
										echo '<option value="0">Pilih Cabang untuk diberi target</option>'; 
										foreach ($branch as $b) {
									  		if($form_type == 'edit' && $target[0]['target_branch'] == $b->branch_id)
									  			{ $selected = 'selected="selected"'; }
									  		else { $selected = ''; }
									  		echo '<option value="'.$b->branch_id.'" '.$selected.'>'.$b->branch_name.'</option>';
									  	}	
									  ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Tanggal Target Jatuh Tempo*</label>
								<div class="col-sm-4">
								  	<input type="text" name="target_bydate" class="datepicker-input inp90 form-control" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d', strtotime('next month')); ?>">
								</div>
							</div>

							<div class="form-group">
								<label for="group_name" class="col-sm-3 control-label">Catatan tentang Target</label>
								<div class="col-sm-4">
									<input type="text" name="target_remarks" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('area_leader', isset($data->area_leader) ? $data->area_leader : ''); ?>" />
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