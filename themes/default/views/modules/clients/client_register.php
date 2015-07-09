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
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Majelis *</label>
								<div class="col-sm-4">								  
								  <select name="client_group" data-required="true" class="form-control" >
										<?php if(!$client->client_group){ ?>
										<option value="" >Pilih Majelis</option>
										<?php } ?>
										<?php foreach($group as $g):  ?>
										<option value="<?php echo $g->group_id; ?>" <?php if($client->client_group == $g->group_id) { echo "selected";}?>  ><?php echo $g->group_name; ?> - <?php echo $g->branch_name; ?></option>
										<?php endforeach; ?>
								  </select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kelompok *</label>
								<div class="col-sm-4">								  
								  <select name="client_subgroup" class="form-control">
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
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendamping Lapangan *</label>
								<div class="col-sm-4">								  
								  <select name="client_officer" class="form-control">
										<?php if(!$client->client_officer){ ?>
										<option value="">Pilih Petugas Pendamping</option>
										<?php } ?>
										<?php foreach($officer as $tpl):  ?>
										<option value="<?php echo $tpl->officer_id; ?>" <?php if($client->client_officer == $tpl->officer_id) { echo "selected";}?> ><?php echo $tpl->officer_name; ?></option>
										<?php endforeach; ?>
								  </select>
								</div>
							</div>
							<?php if($client->client_account){ ?>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nomor Rekening</label>
								<div class="col-sm-4">
									<input type="text" name="client_account" class="form-control" id="" placeholder="" value="<?php echo set_value('client_account', isset($client->client_account) ? $client->client_account : ''); ?>" readonly />
									<input type="hidden" name="client_no" class="form-control" id="" placeholder="" value="<?php echo set_value('client_no', isset($client->client_no) ? $client->client_no : ''); ?>" readonly />
								</div>
							</div>
							<?php } ?>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Lengkap *</label>
								<div class="col-sm-4">
								  <input type="text" name="client_fullname" class="form-control" id="" placeholder="" value="<?php echo set_value('client_fullname', isset($client->client_fullname) ? $client->client_fullname : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Panggilan</label>
								<div class="col-sm-4">
								  <input type="text" name="client_simplename" class="form-control" id="" placeholder="" value="<?php echo set_value('client_simplename', isset($client->client_simplename) ? $client->client_simplename : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal Lahir *</label>
								<div class="col-sm-4">
									<input type="text" name="client_birthdate" class="form-control datepicker-input" data-date-format="dd-mm-yyyy" id="group_date" placeholder="dd-mm-yyyy" value="<?php echo set_value('client_birthdate', isset($client->client_birthdate) ? $client->client_birthdate : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tempat Lahir</label>
								<div class="col-sm-4">
									<input type="text" name="client_birthplace" class="form-control" id="" placeholder="" value="<?php echo set_value('client_birthplace', isset($client->client_birthplace) ? $client->client_birthplace : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="martialstatus" class="col-sm-3 control-label">Status</label>
								<div class="col-sm-4">
									<input type="radio" name="client_martialstatus" id="martialstatus" value="Menikah" <?php if($client->client_martialstatus == "Menikah"){ echo "checked"; } ?> /> Menikah  &nbsp;&nbsp;
									<input type="radio" name="client_martialstatus" id="martialstatus" value="Janda" <?php if($client->client_martialstatus == "Janda"){ echo "checked"; } ?> /> Janda 
								 </div>
							</div>
							<hr/>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT / RW</label>
								<div class="col-sm-1">
								  <input type="text" name="client_rt" class="form-control" id="" placeholder="RT" value="<?php echo set_value('client_rt', isset($client->client_rt) ? $client->client_rt : ''); ?>"> 
								</div>
								<div class="col-sm-1">
								  <input type="text" name="client_rw" class="form-control" id="" placeholder="RW" value="<?php echo set_value('client_rw', isset($client->client_rw) ? $client->client_rw : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kampung</label>
								<div class="col-sm-4">
									<input type="text" name="client_kampung" class="form-control" id="" placeholder="" value="<?php echo set_value('client_kampung', isset($client->client_kampung) ? $client->client_kampung : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Desa</label>
								<div class="col-sm-4">
									<input type="text" name="client_desa" class="form-control" id="" placeholder="" value="<?php echo set_value('client_desa', isset($client->client_desa) ? $client->client_desa : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kecamatan</label>
								<div class="col-sm-4">
								  <input type="text" name="client_kecamatan" class="form-control" id="" placeholder="" value="<?php echo set_value('client_kecamatan', isset($client->client_kecamatan) ? $client->client_kecamatan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">No HP</label>
								<div class="col-sm-4">
									<input type="text" name="client_phone" class="form-control" id="" placeholder="" value="<?php echo set_value('client_phone', isset($client->client_phone) ? $client->client_phone : ''); ?>">
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">No KTP</label>
								<div class="col-sm-4">
									<input type="text" name="client_ktp" class="form-control" id="" placeholder="" value="<?php echo set_value('client_ktp', isset($client->client_ktp) ? $client->client_ktp : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Agama</label>
								<div class="col-sm-4">
									<select name="client_religion" class="form-control">
										<?php if(!$client->client_religion){ ?>
										<option value="">Pilih Agama</option>
										<?php } ?>
										<option value="Islam" 		<?php if($client->client_religion == "Islam") { echo "selected";} ?> 		>Islam</option>
										<option value="Katolik" 	<?php if($client->client_religion == "Katolik") { echo "selected";} ?> 		>Katolik</option>
										<option value="Protestan" 	<?php if($client->client_religion == "Protestan") { echo "selected";} ?>	>Protestan</option>
										<option value="Hindu" 		<?php if($client->client_religion == "Hindu") { echo "selected";} ?> 		>Hindu</option>
										<option value="Budha" 		<?php if($client->client_religion == "Budha") { echo "selected";} ?> 		>Budha</option>
										<option value="Kong Hu Cu" 	<?php if($client->client_religion == "Kong Hu Cu") { echo "selected";} ?> 	>Kong Hu Cu</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan Terakhir</label>
								<div class="col-sm-4">
									<select name="client_education" class="form-control">
										<?php if(!$client->client_education){ ?>
										<option value="">Pilih Pendidikan Terakhir</option>
										<?php } ?>
										<option value="Tidak Sekolah"	<?php if($client->client_education == "Tidak Sekolah") { echo "selected";} ?> 	>Tidak Sekolah</option>
										<option value="Tidak Tamat SD"	<?php if($client->client_education == "Tidak Tamat SD") { echo "selected";} ?> 	>Tidak Tamat SD</option>
										<option value="SD"				<?php if($client->client_education == "SD") { echo "selected";} ?> 				>SD</option>
										<option value="SMP"				<?php if($client->client_education == "SMP") { echo "selected";} ?> 			>SMP</option>
										<option value="SMA/SMK"			<?php if($client->client_education == "SMA/SMK") { echo "selected";} ?> 		>SMA/SMK</option>
										<option value="Diploma"			<?php if($client->client_education == "Diploma") { echo "selected";} ?> 		>Diploma</option>
										<option value="S1 Sarjana"		<?php if($client->client_education == "S1 Sarjana") { echo "selected";} ?> 		>S1 Sarjana</option>
										<option value="Kursus"			<?php if($client->client_education == "Kursus") { echo "selected";} ?> 			>Kursus</option>
										<option value="Pesantren"		<?php if($client->client_education == "Pesantren") { echo "selected";} ?> 		>Pesantren</option>
										<option value="Lainnya"			<?php if($client->client_education == "Lainnya") { echo "selected";} ?> 		>Lainnya</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pekerjaan</label>
								<div class="col-sm-4">								  
									<select name="client_job" class="form-control">
										<?php if(!$client->client_job){ ?>
										<option value="">Pilih Pekerjaan</option>
										<?php } ?>
										<option value="Petani" <?php if($client->client_job == "Petani") { echo "selected";} ?> 		>Petani</option>
										<option value="Pedagang" <?php if($client->client_job == "Pedagang") { echo "selected";} ?> 	>Pedagang</option>
										<option value="Peternak" <?php if($client->client_job == "Peternak") { echo "selected";} ?> 	>Peternak</option>
										<option value="Pengrajin" <?php if($client->client_job == "Pengrajin") { echo "selected";} ?> 	>Pengrajin</option>
										<option value="Buruh" <?php if($client->client_job == "Buruh") { echo "selected";} ?> 			>Buruh</option>
										<option value="Industri Kecil" <?php if($client->client_job == "Industri Kecil") { echo "selected";} ?> >Industri Kecil</option>
										<option value="Pegawai Swasta" <?php if($client->client_job == "Pegawai Swasta") { echo "selected";} ?> >Pegawai Swasta</option>
										<option value="Pegawai Negeri" <?php if($client->client_job == "Pegawai Negeri") { echo "selected";} ?> >Pegawai Negeri</option>
										<option value="Ibu Rumah Tangga" <?php if($client->client_job == "Ibu Rumah Tangga") { echo "selected";} ?> >Ibu Rumah Tangga</option>
										<option value="Lainnya" <?php if($client->client_job == "Lainnya") { echo "selected";} ?> >Lainnya</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Komoditas</label>
								<div class="col-sm-4">
									<input type="text" name="client_comodity" class="form-control" id="" placeholder="" value="<?php echo set_value('client_comodity', isset($client->client_comodity) ? $client->client_comodity : ''); ?>">
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pilih Sumber Pembiayaan</label>
								<div class="col-sm-4">
									<select name="client_pembiayaan_sumber" class="form-control">
									<?php if($client->client_pembiayaan_sumber == null) { ?> 
										<?php echo '<option value="" selected="selected">Pilih Investor</option>'; ?>
										<?php for($i = 0; $i < count($investor); $i++) { ?>
										<?php 
											echo '<option value="';
											echo $investor[$i]->lender_id.'">';
											echo $investor[$i]->lender_name.'</option>';
											?>
										<?php } ?>
									<?php } else { ?>
										<?php for($i = 0; $i < count($investor); $i++) { ?>
										<?php 
											if($investor[$i]->lender_id == $client->client_pembiayaan_sumber){
												$selected = 'selected="selected"';
												echo '<option value="';
												echo $investor[$i]->lender_id.'" '.$selected.'>';
												echo $investor[$i]->lender_name.'</option>';
											}else{
												echo '<option value="';
												echo $investor[$i]->lender_id.'">';
												echo $investor[$i]->lender_name.'</option>';
											}
											?>	
										<?php } ?>
									<?php } ?>
									</select>
								</div>
							</div>
						</div>		
						
					</div>
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-2 ">
							<input type="hidden"  name="client_id" value="<?php echo set_value('client_id', isset($client->client_id) ? $client->client_id : ''); ?>" />
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
		</form>
	</div>
</div>	