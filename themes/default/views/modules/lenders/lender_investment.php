<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
		<?php //var_dump($form_type); ?>
		<?php  	if($form_type == 'edit_investment') $hidden = array('iid' => $one_investment->investment_id, 'type' => $form_type);
				else $hidden = array('type' => $form_type);
				$attributes = array('id' => 'createClientForm', 'class' => 'form-horizontal', 'data-validate' => 'parsley');
          		echo form_open_multipart('lenders/save_investment', $attributes, $hidden);
        ?>
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#personalinfo" data-toggle="tab">Rekap Investasi</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
					<?php //var_dump($investors); ?>
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>', '</div>'); ?>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Investor*</label>
								<div class="col-sm-4">								  
								  <select name="lender_id" data-required="true" class="form-control" >
								  <?php foreach ($all_lenders as $l) {
								  		if($form_type == 'edit_investment' && $one_investment->lender_id === $l->lender_id)
								  			{ $selected = 'selected="selected"'; }
								  		else { $selected = ''; }
								  		echo '<option value="'.$l->lender_id.'" '.$selected.'>'.$l->lender_name.'</option>';
								  	}	
								  ?>
								  </select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal Investasi*</label>
								<div class="col-sm-4">
								  	<input type="text" name="investment_date" class="datepicker-input inp90 form-control" data-date-format="yyyy-mm-dd" value="<?php echo $one_investment->investment_date; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tipe Investasi*</label>
								<div class="col-sm-4">								  
								  <select name="investment_type" data-required="true" class="form-control" >
								  <?php if($form_type == 'edit_investment' && $one_investment->investment_type == 'I')
								  		{ 
								  			echo '<option value="I" selected="selected">Penyetoran Investasi</option>'; 
								  			echo '<option value="O">Penarikan Investasi</option>';	
								  		}
								  		else if($form_type == 'edit_investment' && $one_investment->investment_type == 'O')
								  		{ 
								  			echo '<option value="I">Penyetoran Investasi</option>';
								  			echo '<option value="O" selected="selected">Penarikan Investasi</option>'; 
								  		}	
								  		else{
								  			echo '<option value="I" selected="selected">Penyetoran Investasi</option>'; 
								  			echo '<option value="O">Penarikan Investasi</option>';
								  		}
								  ?>
								  </select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nilai Investasi*</label>
								<div class="col-sm-4">								  
								  	<input type="text" name="investment_amount" class="form-control" value="<?php if($form_type == 'edit_investment') echo $one_investment->investment_amount; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Catatan Investasi*</label>
								<div class="col-sm-4">								  
								  	<input type="text" name="investment_remarks" class="form-control" id="" placeholder="" value="<?php if($form_type == 'edit_investment') echo $one_investment->investment_remarks; ?>">
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