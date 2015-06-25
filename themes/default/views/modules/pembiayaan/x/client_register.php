<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
		
		<form class="form-horizontal" enctype="multipart/form-data" id="createClientForm" action="" method="post" data-validate="parsley"> 
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#personalinfo" data-toggle="tab">Data Anggota</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>', '</div>'); ?>
							<?php if($this->session->flashdata('message')){ ?>
									<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
							<?php } ?>
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="hidden"><i class="fa fa-check text-success"></i></td>
										<td width="30px" class="text-center">No</td>
										<td>Majelis</td>
										<td>Nama</td>
										<td>Desa</td>
										<td>Tanggal Lahir</td>
										<td>Tanggal Pengesahan</td>
									</tr>
								</thead>
								<tbody>
									<?php for($i=1;$i<=30;$i++){ ?>
										<?php $tgl=date("Y-m-d"); ?>
									<tr>
										<td class="hidden"><input type="checkbox" name="client_reg_<?php echo $i; ?>"  value="1"  checked class="hidden" /></td>
										<td class="text-center"><?php echo $i; ?></td>
										<td>
											<select name="client_group_<?php echo $i; ?>" class="">
												<option value="0" >Majelis</option>
												<?php foreach($group as $g):  ?>
													<option value="<?php echo $g->group_id; ?>" ><?php echo $g->group_name; ?></option>
												<?php endforeach;  ?>
										  </select>
										</td>
										<td><input type="text" name="client_name_<?php echo $i; ?>" 	value="" 	class="" ></td>
										<td><input type="text" name="client_desa_<?php echo $i; ?>" 	value="" 	class="inp90" ></td>
										<td>
											<input type="text" name="client_birth_place_<?php echo $i; ?>" 	value="<?php echo $branch->area_name; ?>" 	class="" placeholder="Tempat Lahir" ><br/>
											<select name="client_birth_date_<?php echo $i; ?>">
												<?php foreach (range(1, 31) as $date): ?>
												  <option value="<?= sprintf("%02d", $date) ?>"><?= sprintf("%02d", $date) ?></option>
												<?php endforeach?>
											</select>
											<select name="client_birth_month_<?php echo $i; ?>">
												<?php foreach (range(1, 12) as $month): ?>
												  <option value="<?= sprintf("%02d", $month) ?>"><?= sprintf("%02d", $month) ?></option>
												<?php endforeach?>
											</select>
											<select name="client_birth_year_<?php echo $i; ?>">
												<?php $year=date(Y); ?>
												<?php for($j=1930;$j<=$year;$j++){ ?>
													<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
												<?php } ?>
											</select>
										</td>
										<td><input type="text" name="client_reg_date_<?php echo $i; ?>" 	value="<?php echo $tgl; ?>" 	class="inp90 datepicker-input" data-date-format="yyyy-mm-dd" ></td>
										
									</tr>
									<?php } ?>
								</tbody>
							</table>
							
						</div>		
						
					</div>
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-2 ">
							<input type="hidden"  name="no" value="<?php echo $i-1; ?>" />
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
		</form>
	</div>
</div>	