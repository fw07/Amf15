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
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Area</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->area_name; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Cabang</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->branch_name; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Majelis</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->group_name; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nomor Rekening</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_account; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendamping Lapangan</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->officer_name; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Lengkap</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_fullname; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Panggilan</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_simplename; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal Lahir</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_birthdate; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tempat Lahir</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_birthplace; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="martialstatus" class="col-sm-3 control-label">Status</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_martialstatus; ?>" readonly /></div>
							</div>
							<hr/>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT / RW</label>
								<div class="col-sm-1"><input type="text" class="form-control" value="<?php echo $client->client_rt; ?>" readonly /></div>
								<div class="col-sm-1"><input type="text" class="form-control" value="<?php echo $client->client_rw; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kampung</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_kampung; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Desa</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_desa; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kecamatan</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_kecamatan; ?>" readonly /></div>
							</div>
							<hr/>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">No KTP</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_ktp; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Agama</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_religion; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan Terakhir</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_education; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pekerjaan</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_job; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Komoditas</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_comodity; ?>" readonly /></div>
							</div>
						</div>		
						
					</div>
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-2 ">
							<a href="<?php echo site_url().'/clients/edit/'. $client->client_id; ?>" class="btn btn-primary">Edit This Data</a>
						</div>
					</div>
				</div>
			</div>
			
		</form>
	</div>
</div>	