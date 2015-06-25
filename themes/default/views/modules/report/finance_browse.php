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
					<h4>Laporan Harian</h4>
				</div>

				
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>
					  <tr>
						<th class="text-center" width="30px">No</th>
						<th>Nama Laporan</th>
						<th>Cabang</th>
						<th>Tanggal</th>
						<th width="100px"></th>
						
					</thead> 
					<tbody>	
					
						<tr> 
							<form method="post" action="finance/daily_report">
							<td align="center">1</td>
							<td>Laporan Harian</td>
							<td>
								<select name="branch">
									<?php foreach($branch as $b){ ?>
											<option value="<?php echo $b->branch_id ?>"><?php echo $b->branch_name; ?></option>
									<?php } ?>									
								</select>
							</td>
							<td>
								<input type="text" name="date" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Select Date" />
							</td>
							<td><button type="submit" name="submit" class="btn btn-xs btn-info" >Submit</button></td>
							</form>
						</tr>
						<tr> 
							<form method="post" action="finance/daily_validasi_teller">
							<td align="center">2</td>
							<td>Validasi Teller</td>
							<td>
								<select name="branch">
									<?php foreach($branch as $b){ ?>
											<option value="<?php echo $b->branch_id ?>"><?php echo $b->branch_name; ?></option>
									<?php } ?>									
								</select>
							</td>
							<td>
								<input type="text" name="date" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Select Date" />
							</td>
							<td><button type="submit" name="submit" class="btn btn-xs btn-info" >Submit</button></td>
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

		
	<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-12 m-b-xs">
					<h4>Laporan Mingguan</h4>
				</div>

				
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>
					  <tr>
						<th class="text-center" width="30px">No</th>
						<th>Nama Laporan</th>
						<th>Cabang</th>
						<th>Tanggal</th>
						<th width="100px"></th>
						
					</thead> 
					<tbody>	
					
						<tr> 
							<form method="post" action="finance_week/laporan_mingguan">
							<td align="center">1</td>
							<td>Laporan Mingguan</td>
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
							<form method="post" action="report/finance/weekly/laporan_dana">
							<td align="center">2</td>
							<td>Laporan Pengelolaan Dana Mingguan</td>
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
						
						<tr> 
							<form method="post" action="report/finance/weekly/laporan_proyeksi">
							<td align="center">3</td>
							<td>Proyeksi Pengajuan Dana</td>
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
							<form method="post" action="report/finance/weekly/pengajuan_dana">
							<td align="center">4</td>
							<td>Pengajuan Dana</td>
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

	<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-12 m-b-xs">
					<h4>Laporan Bulanan</h4>
				</div>

				
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>
					  <tr>
						<th class="text-center" width="30px">No</th>
						<th>Keterangan</th>
						<th>Cabang</th>
						<th>Tanggal</th>
						<th width="100px"></th>
						
					</thead> 
					<tbody>	
						<tr> 
							<form method="post" action="<?php echo site_url(); ?>/report/accounting/laba_rugi">
							<td align="center">1</td>
							<td>Laba Rugi New</td>
							<td>Pusat + Cabang</td>
							<td>
								<input type="text" name="date_start" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date" />
								<input type="text" name="date_end" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="End Date" /> 
							</td>
							<td><button type="submit" title="submit" class="btn btn-xs btn-info" >Submit</button></td>
							</form>
						</tr>
						<tr> 
							<form method="post" action="<?php echo site_url(); ?>/report/accounting/neraca">
							<td align="center">2</td>
							<td>Neraca New</td>
							<td>Pusat + Cabang</td>
							<td>
								<input type="text" name="date_start" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date" />
								<input type="text" name="date_end" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="End Date" /> 
							</td>
							<td><button type="submit" title="submit" class="btn btn-xs btn-info" >Submit</button></td>
							</form>
						</tr>
						<tr> 
							<form method="post" action="<?php echo site_url(); ?>/report/accounting/shu">
							<td align="center">3</td>
							<td>Sisa Hasil Usaha</td>
							<td>Pusat + Cabang</td>
							<td>
								<input type="text" name="date_start" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date" />
								<input type="text" name="date_end" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="End Date" /> 
							</td>
							<td><button type="submit" title="submit" class="btn btn-xs btn-info" >Submit</button></td>
							</form>
						</tr>
						<tr> 
							<form method="post" action="<?php echo site_url(); ?>/accounting/neraca">
							<td align="center">4</td>
							<td>Neraca</td>
							<td>
								<select name="branch">
									<option value="0">Pusat</option>
									<?php foreach($branch as $b){ ?>
											<option value="<?php echo $b->branch_id ?>"><?php echo $b->branch_name; ?></option>
									<?php } ?>									
								</select>
							</td>
							<td>
								<input type="text" name="date_start" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date" />
								<input type="text" name="date_end" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="End Date" /> 
							</td>
							<td><button type="submit" title="submit" class="btn btn-xs btn-info" >Submit</button></td>
							</form>
						</tr>
						<tr> 
							<td align="center">5</td>
							<td>Laba Rugi</td>
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
							<td><a href="<?php echo site_url(); ?>/accounting/laba_rugi" title="submit" class="btn btn-xs btn-info" >Submit</a></td>
						</tr>
						<tr> 
							<td align="center">6</td>
							<td>General Ledger</td>
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
							<td><a href="<?php echo site_url(); ?>/accounting/general_ledger" title="submit" class="btn btn-xs btn-info" >Submit</a></td>
						</tr>
					
						<tr> 
							<td align="center">7</td>
							<td>Insentif</td>
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
							<td><a href="<?php echo site_url(); ?>/insentif" title="submit" class="btn btn-xs btn-info" >Submit</a></td>
						</tr>
						
						<tr> 
							<td align="center">8</td>
							<td>Cash FLow</td>
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
							<td><a href="report/finance/daily/validasi_teller" title="submit" class="btn btn-xs btn-info" >Submit</a></td>
						</tr>
						
						
						
						<tr> 
							<form method="post" action="finance_week/laporan_mingguan">
							<td align="center">9</td>
							<td>Progress Report</td>
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
						<!--
						<tr> 
							<form method="post" action="finance_progress/summary">
							<td align="center">6</td>
							<td>Progress Report</td>
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
							<td><button type="submit" title="submit" class="btn btn-xs btn-info" >Submit</button></td>
							</form> 
						</tr>
						-->
						<tr> 
							<td align="center">10</td>
							<td>Portfolio Information</td>
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
							<td><a href="report/finance/daily/validasi_teller" title="submit" class="btn btn-xs btn-info" >Submit</a></td>
						</tr>
						
						
						
					</tbody>	
				</table>  
			</div>
			<footer class="panel-footer">
				<div class="row">
					<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
				</div>
			</footer>
			
	</section>
	
	
</section>	