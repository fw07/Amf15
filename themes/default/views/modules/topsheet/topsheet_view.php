<section class="main">
	<div class="container">
	
	
	<div class="row text-sm wrapper">
		<!-- SEARCH FORM -->
		<div id="module_title" class="col-sm-4 m-b-xs">
				<h3 class="m-b-none"><?php echo $menu_title; ?></h3>
				<h5>NO : <?php echo $ts_id; ?></h5>
		</div>
		<div class="col-sm-4 m-b-xs pull-right text-right">
			<br/><form action="<?php echo site_url(); ?>/topsheet/ts_filter" method="post"> 
				<select name="key" class="input-sm form-control input-s-sm inline">
					<option value="fullname">Majelis</option>
					<?php foreach($listgroup as $list):  ?>
					<option value="<?php echo $list->group_id; ?>"><?php echo $list->group_name ." - ". $list->branch_name; ?></option>
					<?php endforeach; ?>
				</select>
				<button class="btn btn-sm btn-default" type="submit">Go!</button>
			</form>
		</div>				
	</div>
	
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
		
	<section class="panel panel-default panel-body">
		
		<form class="" enctype="multipart/form-data" action="" method="post">
			<!-- TABLE HEADER -->			
			<div class="row text-sm wrapper">
				<div class="col-lg-2 col-md-2 col-sm-2 m-b-xs">
					<table>
						<tr>
							<td><b>Area</b></td>
							<td>&nbsp;&nbsp;: <?php echo $group->area_name;?></td>
						</tr>
						<tr>
							<td valign="top"><b>Cabang</b></td>
							<td>&nbsp;&nbsp;: <?php echo $group->branch_name;?></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 m-b-xs">
					<table>
						<tr>
							<td><b>Majelis</b></td>
							<td>&nbsp;&nbsp;: <?php echo $group->group_name ;?></td>
						</tr>
						<tr>
							<td><b>Kampung/Desa</b></td>
							<td>&nbsp;&nbsp;: <?php echo $group->group_kampung ."/". $group->group_desa;?></td>
						</tr>
						<tr>
							<td><b>Jumlah Anggota</b></td>
							<td>&nbsp;&nbsp;: <?php echo $total_client; ?></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 m-b-xs">
					<table>
						<tr>
							<td><b>Pertemuan ke</b></td>
							<td>&nbsp;&nbsp;: <input type="text" name="ts_freq" value="<?php echo $freq; ?>" class="inp30" /></td>
						</tr>
						<tr>
							<td><b>Tanggal</b></td>
							<td>&nbsp;&nbsp;: <input type="text" name="ts_date" value="<?php echo $topsheet->tsdaily_date; ?>" class="inp90" /></td>
						</tr>
						<tr>
							<td><b>Ketua</b></td>
							<td>&nbsp;&nbsp;: <?php echo $group->group_leader ;?></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 m-b-xs">
					<table>
						<tr>
							<td><b>Tanggung Renteng</b></td>
							<td>&nbsp;&nbsp;: 
								<?php if($group->group_tanggungrenteng == "1"){?> Ada / <strike>Tidak</strike>
								<?php }else{ ?><strike>Ada</strike> / Tidak <?php }?>
							</td>
						</tr>
						<tr>
							<td><b>Akumulasi TR</b></td>
							<td>&nbsp;&nbsp;: 0</td>
						</tr>
						<tr>
							<td><b>Pendamping</b></td>
							<td>&nbsp;&nbsp;: <?php echo $group->officer_name ;?></td>
						</tr>
					</table>
				</div>
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>                  
					  <tr>
						<th width="30px" rowspan="2">No</th>
						<th rowspan="2">Rekening</th>
						<th rowspan="2">Nama</th>
						<th width="80px" rowspan="2">Kehadiran</th>
						<th colspan="7" class="text-center">Pembiayaan</th>
						<th colspan="3" class="text-center">Tabungan Sukarela</th>
						<!--<th colspan="3" class="text-center">Tabungan Berjangka</th>-->
						<th colspan="4" class="text-center">Lain-Lain</th>
					  </tr> 
					<tr>
						<th class="text-right">Sisa Pokok</th>
						<th class="text-right">Sisa Profit</th>
						<th class="text-center" width="30px" >F</th>
						<th class="text-center" width="30px" >P</th>
						<th class="text-center" width="30px" >AK</th>
						<th class="text-center" width="30px" >TR</th>
						<th class="text-center">Angsuran</th>
						<th class="text-right">Saldo</th>
						<th class="text-center">Setor</th>
						<th class="text-center">Tarik</th>
						<!--<th class="text-right">Saldo</th>
						<th class="text-center">Setor</th>
						<th class="text-center">Tarik</th>-->
						<th class="text-right">Adm</th>
						<th class="text-right">Butab</th>
						<th class="text-right">LWK</th>
						<th class="text-right">Asuransi</th>
					  </tr>					  
					</thead> 
					<tbody>	
					<?php
						$total_rows=$config['total_rows'];
						if(empty($no)){ 
							$no=1; 
							$nostart=1;
							$noend=$config['per_page'];
							if($noend>$total_rows){ $noend=$total_rows; }
						}else{ 
							$no=$no+1;
							$nostart=$no;
							$noend=$nostart+$config['per_page']-1;
							if($noend>$total_rows){ $noend=$total_rows; }
						} 
					?>
					<?php foreach($data as $c):  ?>
						<?php if($c->data_status != 4){ ?>
						<tr>     
							<td align="center"><?php echo $no; ?> </td>
							<td><?php echo $c->client_account; ?> </td>
							<td><?php echo $c->client_fullname; ?></td>
							<td>
								<select name="data_absen_<?php echo $no; ?>">
									<option value="h" <?php if($c->tr_absen_h == 1){ echo "selected"; } ?>>Hadir</option>
									<option value="s" <?php if($c->tr_absen_s == 1){ echo "selected"; } ?>>Sakit</option>
									<option value="c" <?php if($c->tr_absen_c == 1){ echo "selected"; } ?>>Cuti</option>
									<option value="i" <?php if($c->tr_absen_i == 1){ echo "selected"; } ?>>Ijin</option>
									<option value="a" <?php if($c->tr_absen_a == 1){ echo "selected"; } ?>>Alpha</option>
								</select>
							</td>
							<?php 
							$margin=0;
							$angsuranke=0;
							$angsuran_pokok=0;
							$angsuran_profit=0;
							$sisa_pokok=0;
							$sisa_profit=0;
							if($c->data_status == 1){
								$margin = $c->data_margin;
								$angsuranke= $c->data_angsuranke;
								$angsuran_pokok=  $c->data_angsuranpokok;
								$angsuran_profit= $c->data_margin / 50 ;
								
								$sisa_pokok  = ((50-$angsuranke) * $angsuran_pokok)/1000;
								$sisa_profit = ((50-$angsuranke) * $angsuran_profit)/1000;
				
							}
							?>
							<td class="text-right"><?php if($c->data_status == 1){ echo number_format($sisa_pokok,1); }else{ echo "-";} ; ?></td>							
							<td class="text-right"><?php if($c->data_status == 1){ echo number_format($sisa_profit,1); }else{ echo "-";} ; ?></td>
							<td class="text-center"><input type="text" name="data_freq_<?php echo $no; ?>" value="<?php if($c->data_status == 1){ echo "1"; }else{ echo "0";} ?>" class="inp30" id="data_freq_<?php echo $no; ?>" /></td>
							<td class="text-center"><input type="text" name="data_angsuranke_<?php echo $no; ?>" value="<?php if($c->data_status == 1){ echo ($c->tr_angsuranke); }else{ echo "0";} ?>" class="inp30" readonly /></td>
							<td class="text-center"><?php echo $c->data_tr; ?></td>
							<td class="text-center"><input type="checkbox" name="data_tr_today_<?php echo $no; ?>" value="1" <?php if($c->tr_tanggungrenteng == 1){ echo "checked"; } ?> /></td>
							<td class="text-center"><input type="text" name="data_totalangsuran_<?php echo $no; ?>" value="<?php if($c->data_status == 1){ echo number_format(($c->data_totalangsuran/1000),1); }else{ echo "0";} ?>" class="inp50  text-right" id="data_totalangsuran_<?php echo $no; ?>" readonly /></td>
							<td class="text-right"><?php if($c->tabsukarela_saldo){ echo number_format(($c->tabsukarela_saldo/1000),1);}else{ echo "0"; } ?></td>
							<td class="text-center"><input type="text" name="data_tabsukarela_debet_<?php echo $no; ?>" value="<?php echo number_format(($c->tr_tabsukarela_debet/1000),1); ?>" class="inp50 text-right" placeholder="0" /></td>
							<td class="text-center"><input type="text" name="data_tabsukarela_credit_<?php echo $no; ?>" value="<?php echo number_format(($c->tr_tabsukarela_credit/1000),1); ?>" class="inp50 text-right" placeholder="0" /></td>
							<!--<td class="text-right"><?php if($c->tabberjangka_saldo){ echo number_format(($c->tabsukarela_saldo/1000),1);}else{ echo "0"; } ?></td>
							<td class="text-center"><input type="text" name="data_tabberjangka_debet_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" /></td>
							<td class="text-center"><input type="text" name="data_tabberjangka_credit_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" /></td>-->
							<td class="text-center"><input type="text" name="data_adm_<?php echo $no; ?>" 	value="<?php echo number_format(($c->tr_adm/1000),1); ?>" class="inp50 text-right" placeholder="0" /></td>
							<td class="text-center"><input type="text" name="data_butab_<?php echo $no; ?>" value="<?php echo number_format(($c->tr_butab/1000),1); ?>" class="inp50 text-right" placeholder="0" /></td>
							<td class="text-center"><input type="text" name="data_lwk_<?php echo $no; ?>" 	value="<?php echo number_format(($c->tr_lwk/1000),1); ?>" class="inp50 text-right" placeholder="0" /></td>
							<td class="text-center"><input type="text" name="data_asuransi_<?php echo $no; ?>" value="<?php echo number_format(($c->tr_asuransi/1000),1); ?>" class="inp50 text-right" placeholder="0" /></td>
						</tr>
						<input type="hidden" name="data_client_<?php echo $no; ?>" value="<?php echo $c->client_id; ?>">
						<input type="hidden" name="data_id_<?php echo $no; ?>" value="<?php echo $c->data_id; ?>">
						<input type="hidden" name="data_account_<?php echo $no; ?>" value="<?php echo $c->client_account; ?>">
						<input type="hidden" name="data_sisaangsuran_<?php echo $no; ?>" value="<?php echo $c->data_sisaangsuran; ?>">
						<input type="hidden" name="data_angsuran_pokok_<?php echo $no; ?>" value="<?php echo $angsuran_pokok; ?>">
						<input type="hidden" name="data_angsuran_profit_<?php echo $no; ?>" value="<?php echo $angsuran_profit; ?>">
						<input type="hidden" name="data_tr_<?php echo $no; ?>" value="<?php echo $c->data_tr; ?>">
						<input type="hidden" name="client_tr_<?php echo $no; ?>" value="<?php echo $c->client_tr; ?>">
						<input type="hidden" name="data_tabwajib_saldo_<?php echo $no; ?>" value="<?php echo $c->tabwajib_saldo; ?>">
						<input type="hidden" name="data_tabwajib_totaldebet_<?php echo $no; ?>" value="<?php echo $c->tabwajib_debet; ?>">
						<input type="hidden" name="data_tabsukarela_totaldebet_<?php echo $no; ?>" value="<?php echo $c->tabsukarela_debet; ?>">
						<input type="hidden" name="data_tabsukarela_totalcredit_<?php echo $no; ?>" value="<?php echo $c->tabsukarela_credit; ?>">
						<input type="hidden" name="data_tabsukarela_saldo_<?php echo $no; ?>" value="<?php echo $c->tabsukarela_saldo; ?>">
						<input type="hidden" name="data_tabberjangka_saldo_<?php echo $no; ?>" value="<?php echo $c->tabberjangka_saldo; ?>">
						<input type="hidden" name="data_baseangsuran_<?php echo $no; ?>" value="<?php if($c->data_totalangsuran){ echo number_format(($c->data_totalangsuran/1000),1); }else{ echo "0";} ?>" class="inp50  text-right" id="data_baseangsuran_<?php echo $no; ?>" />
						
					<?php $no++; } endforeach; ?>
					<?php $totalno=$no-1; ?>
					</tbody>	
				</table>  
			</div>
			<footer class="spanel-footer">
				<div class="row">
					<div class="form-group">
					<div class="col-sm-3 ">
						<br/>
						<input type="hidden" name="ts_id" class="form-control" id="group_id" placeholder="" value="<?php echo set_value('ts_id', isset($data->ts_id) ? $data->ts_id : ''); ?>">
						<input type="hidden" name="no" class="form-control" id="no" placeholder="" value="<?php echo $totalno; ?>">
						<input type="hidden" name="group_id" value="<?php echo $group->group_id; ?>">
						<input type="hidden" name="group_name" value="<?php echo $group->group_name; ?>">
					</div>
				</div>
				</div>
			</footer>
			</form>
	</section>
	</div>
</section>	

<script type="text/javascript">

$(document).ready(function() {
	<?php for ($i=0;$i<=10;$i++){ ?>

		$("#data_freq_<?php echo $i; ?>").change(function() { 
			var freq = $("#data_freq_<?php echo $i; ?>").val();
			var angsuran = $("#data_baseangsuran_<?php echo $i; ?>").val();
			var total_angsuran = freq * angsuran;
			$("#data_totalangsuran_<?php echo $i; ?>").val(total_angsuran.toFixed(1));
			//alert(data);
		});	

	
	<?php } ?>
});
</script>