<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
		<?php  	if($form_type == 'edit') $hidden = array('lid' => $lender_id, 'type' => $form_type);
				else $hidden = array('type' => $form_type);
				$attributes = array('id' => 'createClientForm', 'class' => 'form-horizontal', 'data-validate' => 'parsley');
          		echo form_open_multipart('lenders/save_lender', $attributes, $hidden);
        ?>
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#personalinfo" data-toggle="tab">Data Investor</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>', '</div>'); ?>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tipe Investor*</label>
								<div class="col-sm-4">								  
								  <select name="lender_type" data-required="true" class="form-control" >
								  <?php if($form_type == 'edit'){ ?>
											<option value="C" <?php if ($lender_object->lender_type == 'C') { echo 'selected="selected"'; } ?> >Company</option>
											<option value="I" <?php if ($lender_object->lender_type == 'I') { echo 'selected="selected"'; } ?> >Individual</option>
											<option value="G" <?php if ($lender_object->lender_type == 'G') { echo 'selected="selected"'; } ?> >Government</option>
											<option value="NP" <?php if ($lender_object->lender_type == 'NP') { echo 'selected="selected"'; } ?> >Non-profit</option>
								  <?php } else if($form_type == 'registration'){ ?>
											<option value="">Pilih Tipe Investor</option>
											<option value="C">Company</option>
											<option value="I">Individual</option>
											<option value="G">Government</option>
											<option value="NP">Non-profit</option>
								  <?php	}									     ?>
								  </select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Investor*</label>
								<div class="col-sm-4">								  
								  	<input type="text" name="lender_name" class="form-control" id="" placeholder="" value="<?php if($lender_id != NULL) echo $lender_object->lender_name; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Alamat Investor*</label>
								<div class="col-sm-4">								  
								  	<input type="text" name="lender_address" class="form-control" id="" placeholder="" value="<?php if($lender_id != NULL) echo $lender_object->lender_address; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Telephone Investor*</label>
								<div class="col-sm-4">								  
								  	<input type="text" name="lender_phone" class="form-control" id="" placeholder="" value="<?php if($lender_id != NULL) echo $lender_object->lender_phone; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Email Investor*</label>
								<div class="col-sm-4">								  
								  	<input type="text" name="lender_email" class="form-control" id="" placeholder="" value="<?php if($lender_id != NULL) echo $lender_object->lender_email; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Bank Account Investor*</label>
								<div class="col-sm-4">								  
								  	<input type="text" name="lender_account_no" class="form-control" id="" placeholder="" value="<?php if($lender_id != NULL) echo $lender_object->lender_account_no; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Person In-Charge (CP)</label>
								<div class="col-sm-4">
								<?php if($form_type == 'edit'){ ?>
								  	<input type="text" name="person_in_charge" class="form-control" id="" placeholder="Nama" value="<?php if ($lender_id != NULL) echo $lender_object->person_in_charge; ?>">
								  	<input type="text" name="person_address" class="form-control" id="" placeholder="Alamat" value="<?php if ($lender_id != NULL) echo $lender_object->person_address; ?>">
								  	<input type="text" name="person_phone" class="form-control" id="" placeholder="Phone" value="<?php if ($lender_id != NULL) echo $lender_object->person_phone; ?>">
								  	<input type="text" name="person_email" class="form-control" id="" placeholder="Email" value="<?php if ($lender_id != NULL) echo $lender_object->person_email; ?>">
								<?php } else if($form_type == 'registration'){ ?>
								  	<input type="text" name="person_in_charge" class="form-control" id="" placeholder="Nama" value="">
								  	<input type="text" name="person_address" class="form-control" id="" placeholder="Alamat" value="">
								  	<input type="text" name="person_phone" class="form-control" id="" placeholder="Phone" value="">
								  	<input type="text" name="person_email" class="form-control" id="" placeholder="Email" value="">
								<?php	}									     ?>
								</div>
							</div>
						</div>		
						
					</div>
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-2 ">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
		</form>
	</div>
</div>	