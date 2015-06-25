<section class="main">
	<div class="container">
	<div class="col-md-12">
	<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
	</div>
	
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
	
	<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-6 m-b-xs">
					<h4>Cabang <b><?php echo $report['cabang']; ?></b> (<?php echo $this->input->post('date');?>)</h4>
				</div>
				<div class="col-sm-6 m-b-xs text-right">
					<form method="post" action="">
					<input type="hidden" name="branch" value="<?php echo $this->input->post('branch'); ?>" />
					<input type="text" name="date" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Select Date" />
					<button type="submit" name="submit" class="btn btn-xs btn-info" >Filter</button> 
					</form>
				</div>

				
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>
					  <tr>
						<th rowspan="2">No</th>
						<th rowspan="2">Keterangan</th>
						<th colspan="2" class="text-center">RF</th>
						<th width="20px" rowspan="2">&nbsp;&nbsp;&nbsp;</th>
						<th colspan="2" class="text-center">Amanah</th>
					  </tr>
					  <tr>
						<th class="text-center">Masuk</th>
						<th class="text-center">Keluar</th>
						<th class="text-center">Masuk</th>
						<th class="text-center">Keluar</th>
					  </tr>
					</thead> 
					<tbody>	
					
						<tr> 
							<td>A</td>
							<td>Anggota</td>
							<td class="text-right"><?php echo $report['anggota_baru']; ?></td>
							<td class="text-right"><?php echo $report['anggota_keluar']; ?></td>
							<td></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
						
						<tr> 
							<td>B</td>
							<td>Pencairan</td>
							<td class="text-right">-</td>
							<td class="text-right"><?php echo number_format($report['pencairan']); ?></td>
							<td></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
					
						<tr> 
							<td>C</td>
							<td>Gagal Dropping</td>
							<td class="text-right">0</td>
							<td class="text-right">-</td>
							<td></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
						
						<tr> 
							<td>D</td>
							<td>Setoran Pokok</td>
							<td class="text-right"><?php echo number_format($report['setoran_pokok']); ?></td>
							<td class="text-right">-</td>
							<td></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
						
						<tr> 
							<td>E</td>
							<td>Setoran Margin</td>
							<td class="text-right"><?php echo number_format($report['setoran_margin']); ?></td>
							<td class="text-right">-</td>
							<td></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
						
						<tr> 
							<td>F</td>
							<td>Pendapatan Admin</td>
							<td class="text-right"><?php echo number_format($report['setoran_adm']); ?></td>
							<td class="text-right">-</td>
							<td></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
						
						<tr> 
							<td>G</td>
							<td>Asuransi</td>
							<td class="text-right"><?php echo number_format($report['setoran_asuransi']); ?></td>
							<td class="text-right">-</td>
							<td></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
						
						<tr> 
							<td>H</td>
							<td>Butab / Kartu Angsuran</td>
							<td class="text-right"><?php echo number_format($report['setoran_butab']); ?></td>
							<td class="text-right">-</td>
							<td></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
						
						<tr> 
							<td>I</td>
							<td>LWK</td>
							<td class="text-right"><?php echo number_format($report['setoran_lwk']); ?></td>
							<td class="text-right">-</td>
							<td></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
						
						<tr> 
							<td>J</td>
							<td>UMB Tabungan</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td></td>
							<td class="text-right"><?php echo number_format($report['umb']); ?></td>
							<td class="text-center">-</td>
						</tr>
						
						<tr> 
							<td>K</td>
							<td>Tabungan Wajib</td>
							<td class="text-right"><?php echo number_format($report['tabwajib_debet']); ?></td>
							<td class="text-right"><?php echo number_format($report['tabwajib_credit']); ?></td>
							<td></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
						
						<tr> 
							<td>L</td>
							<td>Tabungan Sukarela</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td></td>
							<td class="text-right"><?php echo number_format($report['tabsukarela_debet']); ?></td>
							<td class="text-right"><?php echo number_format($report['tabsukarela_credit']); ?></td>
						</tr>
						
						<tr> 
							<td>M</td>
							<td>Tabungan Berjangka</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td></td>
							<td class="text-right"><?php echo number_format($report['tabberjangka_debet']); ?></td>
							<td class="text-right"><?php echo number_format($report['tabberjangka_credit']); ?></td>
						</tr>
						<tr> 
							<td>N</td>
							<td class="text-right"><b>JUMLAH</b></td>
							<td class="text-right"><b><?php echo number_format($report['jumlah_rf_masuk']); ?></b></td>
							<td class="text-right"><b><?php echo number_format($report['jumlah_rf_keluar']); ?></b></td>
							<td></td>
							<td class="text-right"><b><?php echo number_format($report['jumlah_amanah_masuk']); ?></b></td>
							<td class="text-right"><b><?php echo number_format($report['jumlah_amanah_keluar']); ?></b></td>
						</tr>
						<tr> 
							<td>O</td>
							<td>Jumlah Masuk &nbsp;&nbsp;&nbsp;<i>(A+C+D+E+F+G+H+I+J+K+L+M)</i></td>
							<td colspan="5" class="text-right"><?php echo number_format($report['jumlah_masuk']); ?></td>
						</tr>
						<tr> 
							<td>P</td>
							<td>Jumlah Keluar &nbsp;&nbsp;&nbsp;<i>(A+C+D+E+F+G+H+I+J+K+L+M)</i></td>
							<td colspan="5" class="text-right"><?php echo number_format($report['jumlah_keluar']); ?></td>
						</tr>
						
						<tr> 
							<td>Q</td>
							<td><b>TOTAL SETOR KE TELLER &nbsp;&nbsp;&nbsp;<i>(O-P)</i></b></td>
							<td colspan="5" class="text-right"><b><?php echo number_format($report['jumlah_teller']); ?></b></td>
						</tr>
					</tbody>	
				</table>  
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="form-group">
						<div class="col-sm-3 ">
							<form method="post" action="<?php echo site_url();?>/report/finance/daily_report_download">
							<input type="hidden" name="branch" value="<?php echo $this->input->post('branch'); ?>" />
							<input type="hidden" name="date" value="<?php echo $this->input->post('date'); ?>" />
							<button type="submit" class="btn btn-primary" target="_blank">Download Report (.pdf)</button>
							</form>
							<br/>
						</div>
					</div>
				</div>
			</footer>
			
	</section>
</div>
	
	
	
</section>	