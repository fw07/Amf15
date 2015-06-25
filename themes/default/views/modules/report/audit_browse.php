<section class="main">
	<div class="container">
	
	<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
	</div>
	
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
		
	<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-12 m-b-xs">
					
				</div>

				
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>
					  <tr>
						<th class="text-center" width="30px">No</th>
						<th>Nama Laporan</th>
						<th width="150px">Cabang</th>
						<th width="250px">Filter Tanggal</th>
						<th width="120px"></th>
						
					</thead> 
					<tbody>	
					
						<tr> 
							<form method="post" action="audit/laporan_nominatif_date">
							<td align="center">1</td>
							<td>Laporan Nominatif</td>
							<td>
								<select name="branch">
									<option value="0">Pusat</option>
									<?php foreach($branch as $b){ ?>
											<option value="<?php echo $b->branch_id; ?>"><?php echo $b->branch_name; ?></option>
									<?php } ?>									
								</select>
							</td>
							<td>
								<input type="text" name="startdate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date" />
								<input type="text" name="enddate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="End Date" />
							</td>
							<td><button type="submit" class="btn btn-xs btn-info" >Submit</button></td>
							</form>
						</tr>
						<tr> 
							<form method="post" action="saving/tabsukarela/">
							<td align="center">2</td>
							<td>Rekening Koran (Tab Sukarela)</td>
							<td>
							</td>
							<td>
								</td>
							<td><a href="<?php echo site_url(); ?>/saving/tabsukarela/" class="btn btn-xs btn-info" >Submit</a></td>
							</form>
						</tr>
						<tr> 
							<form method="post" action="saving/tabberjangka">
							<td align="center">3</td>
							<td>Rekening Koran (Tab Berjangka)</td>
							<td>
							</td>
							<td>
							</td>
							<td><a href="<?php echo site_url(); ?>tabberjangka/" class="btn btn-xs btn-info" >Submit</a></td>
							</form>
						</tr>
						<tr> 
							<form method="post" action="audit/anggota_masuk_download">
							<td align="center">4</td>
							<td>Laporan Anggota Masuk</td>
							<td>
								<select name="branch">
									<option value="0">Pusat</option>
									<?php foreach($branch as $b){ ?>
											<option value="<?php echo $b->branch_id ?>"><?php echo $b->branch_name; ?></option>
									<?php } ?>									
								</select>
							</td>
							<td>
								<input type="text" name="startdate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date" />
								<input type="text" name="enddate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="End Date" /> 
							</td>
							<td><button type="submit" class="btn btn-xs btn-info" >Submit</button></td>
							</form>
						</tr>
						<tr> 
							<form method="post" action="audit/anggota_keluar">
							<td align="center">5</td>
							<td>Laporan Anggota Keluar</td>
							<td>
								<select name="branch">
									<option value="0">Pusat</option>
									<?php foreach($branch as $b){ ?>
											<option value="<?php echo $b->branch_id ?>"><?php echo $b->branch_name; ?></option>
									<?php } ?>									
								</select>
							</td>
							<td>
								<input type="text" name="startdate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date" />
								<input type="text" name="enddate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="End Date" /> 
							</td>
							<td><button type="submit" class="btn btn-xs btn-info" >Submit</button></td>
							</form>
						</tr>
						<tr> 
							<form method="post" action="report/audit/tunggakan">
							<td align="center">6</td>
							<td>Laporan Tunggakan</td>
							<td>
								<select name="branch">
									<option value="0">Pusat</option>
									<?php foreach($branch as $b){ ?>
											<option value="<?php echo $b->branch_id ?>"><?php echo $b->branch_name; ?></option>
									<?php } ?>									
								</select>
							</td>
							<td>
								<input type="text" name="startdate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date" />
								<input type="text" name="startdate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date" /> 
							</td>
							<td><button type="submit" class="btn btn-xs btn-info" >Submit</button></td>
							</form>
						</tr>
					
					</tbody>	
				</table>  
			</div>
			<footer class="panel-footer">
				<div class="row">
					
				</div>
			</footer>
			
	</section>
	</div>
</section>	