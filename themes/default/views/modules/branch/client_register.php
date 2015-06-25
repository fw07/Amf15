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
									    <td>Kelompok</td>
										<td>Nama</td>
										<td>KTP</td>
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
									<td>
										<select name="client_subgroup_<?php echo $i; ?>" class="form-control">
										<option value="">Pilih Kelompok</option>
										<option value="A1" <?php if($client->client_subgroup == "A1"){ echo "selected"; } ?> >A1</option>
										<option value="A2" <?php if($client->client_subgroup == "A2"){ echo "selected"; } ?> >A2</option>
										<option value="A3" <?php if($client->client_subgroup == "A3"){ echo "selected"; } ?> >A3</option>
										<option value="A4" <?php if($client->client_subgroup == "A4"){ echo "selected"; } ?> >A4</option>
										<option value="A5" <?php if($client->client_subgroup == "A5"){ echo "selected"; } ?> >A5</option>
										<option value="A6" <?php if($client->client_subgroup == "A6"){ echo "selected"; } ?> >A6</option>
										
										<option value="B1" <?php if($client->client_subgroup == "B1"){ echo "selected"; } ?> >B1</option>
										<option value="B2" <?php if($client->client_subgroup == "B2"){ echo "selected"; } ?> >B2</option>
										<option value="B3" <?php if($client->client_subgroup == "B3"){ echo "selected"; } ?> >B3</option>
										<option value="B4" <?php if($client->client_subgroup == "B4"){ echo "selected"; } ?> >B4</option>
										<option value="B5" <?php if($client->client_subgroup == "B5"){ echo "selected"; } ?> >B5</option>
										<option value="B6" <?php if($client->client_subgroup == "B6"){ echo "selected"; } ?> >B6</option>
										
										<option value="C1" <?php if($client->client_subgroup == "C1"){ echo "selected"; } ?> >C1</option>
										<option value="C2" <?php if($client->client_subgroup == "C2"){ echo "selected"; } ?> >C2</option>
										<option value="C3" <?php if($client->client_subgroup == "C3"){ echo "selected"; } ?> >C3</option>
										<option value="C4" <?php if($client->client_subgroup == "C4"){ echo "selected"; } ?> >C4</option>
										<option value="C5" <?php if($client->client_subgroup == "C5"){ echo "selected"; } ?> >C5</option>
										<option value="C6" <?php if($client->client_subgroup == "C6"){ echo "selected"; } ?> >C6</option>
										
										<option value="D1" <?php if($client->client_subgroup == "D1"){ echo "selected"; } ?> >D1</option>
										<option value="D2" <?php if($client->client_subgroup == "D2"){ echo "selected"; } ?> >D2</option>
										<option value="D3" <?php if($client->client_subgroup == "D3"){ echo "selected"; } ?> >D3</option>
										<option value="D4" <?php if($client->client_subgroup == "D4"){ echo "selected"; } ?> >D4</option>
										<option value="D5" <?php if($client->client_subgroup == "D5"){ echo "selected"; } ?> >D5</option>
										<option value="D6" <?php if($client->client_subgroup == "D6"){ echo "selected"; } ?> >D6</option>
										
										<option value="E1" <?php if($client->client_subgroup == "E1"){ echo "selected"; } ?> >E1</option>
										<option value="E2" <?php if($client->client_subgroup == "E2"){ echo "selected"; } ?> >E2</option>
										<option value="E3" <?php if($client->client_subgroup == "E3"){ echo "selected"; } ?> >E3</option>
										<option value="E4" <?php if($client->client_subgroup == "E4"){ echo "selected"; } ?> >E4</option>
										<option value="E5" <?php if($client->client_subgroup == "E5"){ echo "selected"; } ?> >E5</option>
										<option value="E6" <?php if($client->client_subgroup == "E6"){ echo "selected"; } ?> >E6</option>
										
										<option value="F1" <?php if($client->client_subgroup == "F1"){ echo "selected"; } ?> >F1</option>
										<option value="F2" <?php if($client->client_subgroup == "F2"){ echo "selected"; } ?> >F2</option>
										<option value="F3" <?php if($client->client_subgroup == "F3"){ echo "selected"; } ?> >F3</option>
										<option value="F4" <?php if($client->client_subgroup == "F4"){ echo "selected"; } ?> >F4</option>
										<option value="F5" <?php if($client->client_subgroup == "F5"){ echo "selected"; } ?> >F5</option>
										<option value="F6" <?php if($client->client_subgroup == "F6"){ echo "selected"; } ?> >F6</option>
										
								  </select>
										</td>
										<td><input type="text" name="client_name_<?php echo $i; ?>" 	value="" 	class="" ></td>
										<td><input type="text" name="client_ktp_<?php echo $i; ?>" 		value="" 	class="" ></td>
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