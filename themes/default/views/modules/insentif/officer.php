<section class="main">
	<div class="container">
	
	<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
	</div>
	
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
		
	<section class="panel panel-default panel-body">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-5 m-b-xs">
					<?php echo $_POST['q'];?>
				</div>
				<form action="" method="post"> 
				<div class="col-sm-3 pull-right">
					<div class="input-group">
						<select name="q" class="input-sm form-control">
							<option value="2014-12">2014-12</option>
							<option value="2014-11">2014-11</option>
							<option value="2014-10">2014-10</option>
						</select>
						<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit">Go!</button> </span> 
					</div>
				</div>
				</form>
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>                  
					  <tr>
						<th width="30px">No</th>
						<th>Nama</th>
						<th>No Pegawai</th>
						<th>Cabang</th>
						<th class="text-right">Transaksi</th>
						<th class="text-right">Anggota</th>
						<th class="text-right">Majelis</th>
						<th class="text-right">Pembiayaan<br/>Aktif (Anggota)</th>
						<th class="text-right">Outstanding (Saldo)</th>
						<th class="text-right">Penabung<br/>Tab.Sukarela</th>
						<th class="text-right">PAR</th>
						<th class="text-right">PAR (Saldo)</th>
						<th class="text-right">Tab. Wajib (Saldo)</th>
						<th class="text-right">Tab. Sukarela (Saldo)</th>
					  </tr>                  
					</thead> 
					<tbody>	
					<?php 
					$no=1;
					if($_POST['q']){ $date = "$_POST[q]"; }
					else{ $date = date("Y-m"); }
					foreach($officer as $c): 
						$total_transaksi_per_officer = $this->insentif_model->count_transaksi($c->officer_id, $date);
						$total_anggota_per_officer = $this->insentif_model->count_anggota($c->officer_id, $date);
						$total_majelis_per_officer = $this->insentif_model->count_majelis($c->officer_id, $date);	
						$total_pembiayaan_per_officer = $this->insentif_model->count_pembiayaan($c->officer_id, $date);
						$total_tabsukarela_per_officer = $this->insentif_model->count_tabsukarela($c->officer_id, $date);	
						$total_par_per_officer = $this->insentif_model->count_par($c->officer_id, $date);
						//$total_tabwajib_debet_per_officer = $this->insentif_model->count_tabwajib_debet($c->officer_id, $date);
						//$total_tabwajib_credit_per_officer = $this->insentif_model->count_tabwajib_credit($c->officer_id, $date);
						//$total_tabwajib_saldo_per_officer = $total_tabwajib_debet_per_officer - $total_tabwajib_kredit_per_officer;
						$total_tabsukarela_saldo_per_officer = $this->insentif_model->count_tabsukarela_saldo($c->officer_id);	
						$total_tabwajib_saldo_per_officer = $this->insentif_model->count_tabwajib_saldo($c->officer_id);	
						
						$total_pembiayaan_saldo_per_officer = $this->insentif_model->count_pembiayaan_saldo($c->officer_id);
						$total_par_saldo_per_officer = $this->insentif_model->sum_par($c->officer_id, $date);
						
						$total_anggota += $total_anggota_per_officer ;
						$total_majelis += $total_majelis_per_officer ;
						$total_pembiayaan += $total_pembiayaan_per_officer ;
					?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>					              
							<td><?php echo $c->officer_name; ?></td>
							<td><?php echo $c->officer_number; ?></td>
							<td><?php echo $c->branch_name; ?></td>
							<td class="text-right"><?php echo $total_transaksi_per_officer; ?></td>
							<td class="text-right"><?php echo $total_anggota_per_officer; ?></td>
							<td class="text-right"><?php echo $total_majelis_per_officer; ?></td>
							<td class="text-right"><?php echo $total_pembiayaan_per_officer; ?></td>
							<td class="text-right"><?php echo number_format($total_pembiayaan_saldo_per_officer,0); ?></td>
							<td class="text-right"><?php echo $total_tabsukarela_per_officer; ?></td>
							<td class="text-right"><?php echo $total_par_per_officer; ?></td>
							<td class="text-right"><?php echo number_format($total_par_saldo_per_officer,0); ?></td>
							<td class="text-right"><?php echo number_format($total_tabwajib_saldo_per_officer); ?></td>
							<td class="text-right"><?php echo number_format($total_tabsukarela_saldo_per_officer); ?></td>
							
						</tr>
					<?php $no++; endforeach; ?>
					</tbody>
					<!--<tfoot>                  
					  <tr>
						<td colspan="5"></td>
						<td><?php echo $total_anggota; ?></td>
						<td><?php echo $total_majelis; ?></td>
						<td></td>
					  </tr>                  
					</tfoot> 
					-->					
				</table>  
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="text-center">
					<br/>
					<a href="<?php echo site_url()."/insentif/download/".$date; ?>" target="_blank" class="btn btn-sm btn-primary" >Download Laporan Kinerja FO</a>
					<br/><br/>
				</div>	
				</div>
			</footer>
			
	</section>
	</div>
</section>	