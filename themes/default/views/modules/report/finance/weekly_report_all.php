<section class="main">
	<div class="container">
	<div class="col-md-8">
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
					<h4>Cabang <b><?php echo $result['cabang']; ?></b></h4>
				</div>
				<div class="col-sm-6 m-b-xs text-right">
					<form method="post" action="">
					<input type="hidden" name="branch" value="<?php echo $this->input->post('branch'); ?>" />
					<input type="text" name="startdate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date" />
					<input type="text" name="enddate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="End Date" />
					<button type="submit" name="submit" class="btn btn-xs btn-info" >Filter</button> 
					</form>
				</div>

				
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>
					  <tr>
						<th>Parameter</th>
						<th class="text-right">Sebelum Tanggal <?php echo $this->input->post('startdate');?></th>
						<th class="text-right"><?php echo $this->input->post('startdate');?> s.d. <?php echo $this->input->post('enddate');?></th>
						<th class="text-right">Sampai dengan <?php echo $this->input->post('startdate');?></th>
					  </tr>
					  
					</thead> 
					<tbody>	
						<tr> 
							<td colspan="4"><b>Anggota</b></td>
						</tr>
						<tr> 
							<td>Anggota Aktif</td>
							<td class="text-right"><?php echo $result['total_clients_startweek']; ?></td>
							<td class="text-right"><?php echo $result['total_clients_inweek']; ?></td>
							<td class="text-right"><?php echo $result['total_clients_endweek']; ?></td>
						</tr>
						<tr> 
							<td>Anggota Keluar</td>
							<td class="text-right"><?php echo $result['total_unreg_clients_startweek']; ?></td>
							<td class="text-right"><?php echo $result['total_unreg_clients_inweek']; ?></td>
							<td class="text-right"><?php echo $result['total_unreg_clients_endweek']; ?></td>
						</tr>
						<tr> 
							<td>Majelis</td>
							<td class="text-right"><?php echo $result['total_majelis_startweek']; ?></td>
							<td class="text-right"><?php echo $result['total_majelis_inweek']; ?></td>
							<td class="text-right"><?php echo $result['total_majelis_endweek']; ?></td>
						</tr>
						<tr> 
							<td colspan="4"> </td>
						</tr>
						<tr> 
							<td colspan="4"><b>Pembiayaan</b></td>
						</tr>
						<tr> 
							<td>Nilai Pembiayaan Disalurkan</td>
							<td class="text-right"><?php echo number_format($result['total_pembiayaan_disalurkan_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_pembiayaan_disalurkan_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_pembiayaan_disalurkan_endweek']); ?></td>
						</tr>
						<tr> 
							<td>Pengembalian Pembiayaan Pokok</td>
							<td class="text-right"><?php echo number_format($result['total_pembiayaan_pengembalian_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_pembiayaan_pengembalian_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_pembiayaan_pengembalian_endweek']); ?></td>
						</tr>
						<tr> 
							<td>Profit/Margin</td>
							<td class="text-right"><?php echo number_format($result['total_pembiayaan_margin_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_pembiayaan_margin_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_pembiayaan_margin_endweek']); ?></td>
						</tr>
						<tr> 
							<td>Jumlah Peminjam Aktif</td>
							<td class="text-right"><?php echo $result['total_anggota_aktif_pembiayaan_startweek']; ?></td>
							<td class="text-right"><?php echo $result['total_anggota_aktif_pembiayaan_inweek']; ?></td>
							<td class="text-right"><?php echo $result['total_anggota_aktif_pembiayaan_endweek']; ?></td>
						</tr>
						<tr> 
							<td colspan="4"> </td>
						</tr>
						<tr> 
							<td colspan="4"><b>Simpanan</b></td>
						</tr>
						<tr> 
							<td>Simpanan Sukarela</td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabsukarela_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabsukarela_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabsukarela_endweek']); ?></td>
						</tr>
						<tr> 
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Masuk</td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabsukarela_debet_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabsukarela_debet_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabsukarela_debet_endweek']); ?></td>
						</tr>
						<tr> 
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Keluar</td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabsukarela_credit_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabsukarela_credit_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabsukarela_credit_endweek']); ?></td>
						</tr>
						
						<tr> 
							<td>Simpanan Wajib Kelompok</td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabwajib_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabwajib_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabwajib_endweek']); ?></td>
						</tr>
						<tr> 
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Masuk</td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabwajib_debet_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabwajib_debet_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabwajib_debet_endweek']); ?></td>
						</tr>
						<tr> 
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Keluar</td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabwajib_credit_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabwajib_credit_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabwajib_credit_endweek']); ?></td>
						</tr>
						
						
						<tr> 
							<td>Simpanan Berjangka</td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabberjangka_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabberjangka_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabberjangka_endweek']); ?></td>
						</tr>
						<tr> 
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Masuk</td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabberjangka_debet_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabberjangka_debet_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabberjangka_debet_endweek']); ?></td>
						</tr>
						<tr> 
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Keluar</td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabberjangka_credit_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabberjangka_credit_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabberjangka_credit_endweek']); ?></td>
						</tr>
						<tr> 
							<td><b>JUMLAH SIMPANAN</b></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabungan_startweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabungan_inweek']); ?></td>
							<td class="text-right"><?php echo number_format($result['total_weekly_tabungan_endweek']); ?></td>
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