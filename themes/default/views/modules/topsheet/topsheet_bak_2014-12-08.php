<section class="main">
	<div class="container">
	
	
	<div class="row text-sm wrapper">
		<!-- SEARCH FORM -->
		<div id="module_title" class="col-sm-4 m-b-xs">
				<h3 class="m-b-none"><?php echo $menu_title; ?></h3>
		</div>
		
		<div class="col-sm-4 m-b-xs pull-right text-right">
			<br/><form action="<?php echo site_url(); ?>/topsheet/ts_filter" method="post"> 
				<select name="key" class="input-sm form-control input-s-sm inline">
					<option value="fullname">Majelis</option>
					<?php foreach($listgroup as $list):  ?>
					<option value="<?php echo $list->group_id; ?>"><?php echo $list->group_name ; ?></option>
					<?php endforeach; ?>
				</select>
				<button class="btn btn-sm btn-default" type="submit">Go!</button>
			</form>
		</div>				
	</div>
	
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
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
							<td>&nbsp;&nbsp;: <input type="text" name="ts_freq" value="" class="inp30" /></td>
						</tr>
						<tr>
							<td><b>Tanggal</b></td>
							<td>&nbsp;&nbsp;: <input type="text" name="ts_date" value="<?php echo date("Y-m-d")?>" class="inp90" /></td>
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
								<select name="topsheet_tr">
									<option value="1">Ada</option>
									<option value="2">Tidak Ada</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><b>Akumulasi TR</b></td>
							<td>&nbsp;&nbsp;: <?php echo $group_tr; ?></td>
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
						<th colspan="8" class="text-center">Pembiayaan</th>
						<th colspan="3" class="text-center">Tabungan Wajib</th>
						<th colspan="3" class="text-center">Tabungan Sukarela</th>
						<!--<th colspan="3" class="text-center">Tabungan Berjangka</th>-->
						<th colspan="4" class="text-center">Lain-Lain</th>
					  </tr> 
					  <tr>
						<!--Pembiayaan -->
						<th class="text-right">Sisa Pokok</th>
						<th class="text-right">Sisa Profit</th>
						<th class="text-center" width="30px" >F</th>
						<th class="text-center" width="30px" >P</th>
						<th class="text-center" width="30px" >AK</th>
						<th class="text-center" width="30px" >TR</th>
						<th class="text-center">Angsuran Pokok</th>
						<th class="text-center">Angsuran Profit</th>
						<!--Tabungan Wajib -->
						<th class="text-right">Saldo</th>
						<th class="text-center">Setor</th>
						<th class="text-center">Tarik</th>
						<!--Tabungan Sukarela -->
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
							$today=date("Y-m-d");
					?>
					<?php foreach($clients as $c): ?>
					<?php if($c->data_status != 4 AND $c->tbl_pembiayaan.deleted!=1 ){ ?>
						<tr>     
							<td align="center"><?php echo $no; ?> </td>
							<td><a href="<?php echo site_url();?>/clients/summary/<?php echo $c->client_id; ?>" title="view"><?php echo $c->client_account; ?></a></td>
							<td><a href="<?php echo site_url();?>/clients/summary/<?php echo $c->client_id; ?>" title="view"><?php echo $c->client_fullname; ?></a></td>
							<td>
								<select name="data_absen_<?php echo $no; ?>">
									<option value="h">Hadir</option>
									<option value="s">Sakit</option>
									<option value="c">Cuti</option>
									<option value="i">Ijin</option>
									<option value="a">Alpha</option>
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
								$angsuranke= $c->data_angsuranke+1;
								$angsuran_pokok=  $c->data_angsuranpokok;
								$angsuran_profit= $c->data_margin / 50 ;
								
								$sisa_pokok  = ((50-$c->data_angsuranke) * $angsuran_pokok)/1000;
								$sisa_profit = ((50-$c->data_angsuranke) * $angsuran_profit)/1000;
								
								$total_angsuran_pokok  += $angsuran_pokok/1000;
								$total_angsuran_profit += $angsuran_profit/1000;
								$total_angsuran += ( $angsuran_pokok/1000 + $angsuran_profit/1000 );
								$tabwajib_total_setor  += $c->data_tabunganwajib/1000;
							}
							?>
							<td class="text-right"><?php if($c->data_status ==1 AND $c->client_pembiayaan_status == 1 AND $c->data_date_first <= $today){ echo number_format($sisa_pokok,1); }else{ echo "-";} ; ?></td>							
							<td class="text-right"><?php if($c->data_status ==1 AND $c->client_pembiayaan_status == 1 AND $c->data_date_first <= $today){ echo number_format($sisa_profit,1); }else{ echo "-";} ; ?></td>
							<td class="text-center"><input type="text" name="data_freq_<?php echo $no; ?>" value="<?php if($c->data_status ==1 AND $c->client_pembiayaan_status == 1 AND $c->data_date_first <= $today){ echo "1"; }else{ echo "0";} ?>" class="inp30" id="data_freq_<?php echo $no; ?>" onblur="recalculateSum_angsuran();" /></td>
							<td class="text-center">
								<input type="text" name="data_angsuranke_<?php echo $no; ?>" value="<?php if($c->data_status == 1 AND $c->client_pembiayaan_status == 1 AND $c->data_date_first <= $today){ echo $angsuranke; }else{ echo "0";} ?>" class="inp30" readonly />
								<input type="hidden" name="data_pertemuanke_<?php echo $no; ?>" value="<?php if($c->data_id){ echo ($c->data_pertemuanke)+1; }else{ echo "0";} ?>" class="inp30" readonly />
							</td>
							<td class="text-center"><?php if($c->data_status == 1 AND $c->client_pembiayaan_status == 1 AND $c->data_date_first <= $today){ echo $c->data_tr;}else{ echo "-";} ?></td>
							<td class="text-center"><input type="checkbox" name="data_tr_today_<?php echo $no; ?>" value="1" /></td>
							<!--<td class="text-center"><input type="text" name="data_totalangsuran_<?php echo $no; ?>" value="<?php if($c->data_status==1 AND $c->client_pembiayaan_status == 1 AND $c->data_date_first <= $today){ echo number_format(($c->data_totalangsuran/1000),1); }else{ echo "0";} ?>" class="inp50  text-right" id="data_totalangsuran_<?php echo $no; ?>" readonly /></td>-->
							<td class="text-center"><input type="text" name="data_totalangsuranpokok_<?php echo $no; ?>" value="<?php if($c->data_status==1 AND $c->client_pembiayaan_status == 1 AND $c->data_date_first <= $today){ echo number_format(($c->data_angsuranpokok/1000),1); }else{ echo "0";} ?>" class="inp50  text-right" id="data_totalangsuranpokok_<?php echo $no; ?>" onblur="recalculateSum_angsuranpokok();" readonly /></td>
							<td class="text-center"><input type="text" name="data_totalangsuranprofit_<?php echo $no; ?>" value="<?php if($c->data_status==1 AND $c->client_pembiayaan_status == 1 AND $c->data_date_first <= $today){ echo number_format(($c->data_margin/(50*1000)),1); }else{ echo "0";} ?>" class="inp50  text-right" id="data_totalangsuranprofit_<?php echo $no; ?>" onblur="recalculateSum_angsuranprofit();" /></td>
							<td class="text-right"><?php if($c->tabwajib_saldo){ echo number_format(($c->tabwajib_saldo/1000),1);}else{ echo "0"; } ?></td>
							<td class="text-center"><input type="text" name="data_tabwajib_debet_<?php echo $no; ?>" value="<?php if($c->data_status==1 AND $c->client_pembiayaan_status == 1 AND $c->data_date_first <= $today){ echo number_format(($c->data_tabunganwajib/1000),1);; }else{ echo "0";} ?>" class="inp50 text-right" id="data_tabunganwajib_<?php echo $no; ?>"  onblur="recalculateSum_tabwajibdebet();" /></td>
							<td class="text-center"><input type="text" name="data_tabwajib_credit_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" id="data_tabunganwajibcredit_<?php echo $no; ?>"  onkeyup="recalculateSum_tabwajibcredit();" /></td>
							<td class="text-right"><?php if($c->tabsukarela_saldo){ echo number_format(($c->tabsukarela_saldo/1000),1);}else{ echo "0"; } ?></td>
							<td class="text-center"><input type="text" name="data_tabsukarela_debet_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" tabindex="<?php echo $no; ?>" id="data_tabsukarela_debet_<?php echo $no; ?>" onblur="recalculateSum_tabsukarela_debet();" /></td>
							<td class="text-center"><input type="text" name="data_tabsukarela_credit_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" tabindex="<?php echo $no; ?>" id="data_tabsukarela_credit_<?php echo $no; ?>" onblur="recalculateSum_tabsukarela_credit();" /></td>
							<!--<td class="text-right"><?php if($c->tabberjangka_saldo){ echo number_format(($c->tabsukarela_saldo/1000),1);}else{ echo "0"; } ?></td>
							<td class="text-center"><input type="text" name="data_tabberjangka_debet_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" /></td>
							<td class="text-center"><input type="text" name="data_tabberjangka_credit_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" /></td>-->
							<td class="text-center"><input type="text" name="data_adm_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" 	  id="data_adm_<?php echo $no; ?>" onblur="recalculateSum_adm();" /></td>
							<td class="text-center"><input type="text" name="data_butab_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" 	  id="data_butab_<?php echo $no; ?>" onblur="recalculateSum_butab();" /></td>
							<td class="text-center"><input type="text" name="data_lwk_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" 	  id="data_lwk_<?php echo $no; ?>" onblur="recalculateSum_lwk();" /></td>
							<td class="text-center"><input type="text" name="data_asuransi_<?php echo $no; ?>" value="0" class="inp50 text-right" placeholder="0" id="data_asuransi_<?php echo $no; ?>" onblur="recalculateSum_asuransi();" /></td>
						</tr>
						<input type="hidden" name="data_client_<?php echo $no; ?>" value="<?php echo $c->client_id; ?>">
						<input type="hidden" name="data_id_<?php echo $no; ?>" value="<?php echo $c->data_id; ?>">
						<input type="hidden" name="data_account_<?php echo $no; ?>" value="<?php echo $c->client_account; ?>">
						<input type="hidden" name="data_sisaangsuran_<?php echo $no; ?>" value="<?php echo $c->data_sisaangsuran; ?>">
						<input type="hidden" name="data_angsuran_pokok_<?php echo $no; ?>" value="<?php echo $angsuran_pokok; ?>" >
						<input type="hidden" name="data_angsuran_profit_<?php echo $no; ?>" value="<?php echo $angsuran_profit; ?>">
						<input type="hidden" name="data_tr_<?php echo $no; ?>" value="<?php echo $c->data_tr; ?>">
						<input type="hidden" name="client_tr_<?php echo $no; ?>" value="<?php echo $c->client_tr; ?>">
						<input type="hidden" name="data_tabwajib_saldo_<?php echo $no; ?>" value="<?php echo $c->tabwajib_saldo; ?>">
						<input type="hidden" name="data_tabwajib_totaldebet_<?php echo $no; ?>" value="<?php echo $c->tabwajib_debet; ?>"  >
						<input type="hidden" name="data_tabwajib_totalcredit_<?php echo $no; ?>" value="<?php echo $c->tabwajib_credit; ?>" >
						<input type="hidden" name="data_tabsukarela_totaldebet_<?php echo $no; ?>" value="<?php echo $c->tabsukarela_debet; ?>" >
						<input type="hidden" name="data_tabsukarela_totalcredit_<?php echo $no; ?>" value="<?php echo $c->tabsukarela_credit; ?>">
						<input type="hidden" name="data_tabsukarela_saldo_<?php echo $no; ?>" value="<?php echo $c->tabsukarela_saldo; ?>">
						<input type="hidden" name="data_baseangsuran_<?php echo $no; ?>" value="<?php if($c->data_totalangsuran){ echo number_format(($c->data_totalangsuran/1000),1); }else{ echo "0";} ?>" class="inp50  text-right" id="data_baseangsuran_<?php echo $no; ?>" />
						<input type="hidden" name="data_baseangsuranpokok_<?php echo $no; ?>" value="<?php if($c->data_status==1){ echo number_format(($c->data_angsuranpokok/1000),1); }else{ echo "0";} ?>" class="inp50  text-right" id="data_baseangsuranpokok_<?php echo $no; ?>" />
						<input type="hidden" name="data_baseangsuranprofit_<?php echo $no; ?>" value="<?php if($c->data_status==1){ echo number_format(($c->data_margin/50000),1); }else{ echo "0";} ?>" class="inp50  text-right" id="data_baseangsuranprofit_<?php echo $no; ?>" />
						<input type="hidden" name="data_basetabwajib_<?php echo $no; ?>" value="<?php if($c->data_tabunganwajib){ echo number_format(($c->data_tabunganwajib/1000),1); }else{ echo "0";} ?>" class="inp50  text-right" id="data_basetabwajib_<?php echo $no; ?>" />
						<input type="hidden" name="data_par_<?php echo $no; ?>" value="<?php echo $c->data_par; ?>">
						
					<?php $no++; } ?>
					<?php  endforeach; ?>
					<?php $totalno=$no-1; ?>
					</tbody>
					<tfoot> 
					  <tr>
						<th width="30px" ></th>
						<th ></th>
						<th ></th>
						<th width="80px"></th>
						<!--Pembiayaan -->
						<th class="text-right"></th>
						<th class="text-right"></th>
						<th class="text-center" width="30px" ></th>
						<th class="text-center" width="30px" ></th>
						<th class="text-center"></th>
						<th class="text-center" width="30px" ><b>TOTAL</b></th>
						<th class="text-center" width="30px" ><input type="text" name="total_angsuranpokok" value="<?php echo $total_angsuran_pokok; ?>" class="inp50 text-right" id="total_angsuranpokok" /></th>
						<th class="text-center" width="30px" ><input type="text" name="total_angsuranprofit" value="<?php echo $total_angsuran_profit; ?>" class="inp50 text-right" id="total_angsuranprofit" /></th>
						
						<!--Tabungan Wajib -->
						<th class="text-right"></th>
						<th class="text-center"><input type="text" name="total_tabwajibdebet" value="<?php echo $tabwajib_total_setor; ?>" class="inp50 text-right" id="total_tabwajib_setor" /></th>
						<th class="text-center"><input type="text" name="total_tabwajibcredit" value="0" class="inp50 text-right" id="total_tabwajib_tarik" /></th>
						<!--Tabungan Sukarela -->
						<th class="text-right"></th>
						<th class="text-center"><input type="text" name="total_tabsukarela_debet" value="0" class="inp50 text-right" id="total_tabsukarela_setor" /></th>
						<th class="text-center"><input type="text" name="total_tabsukarela_credit" value="0" class="inp50 text-right" id="total_tabsukarela_tarik"/></th>
						<!--Lain -->
						<th class="text-right"><input type="text" name="total_adm" value="0" class="inp50 text-right" id="total_adm" /></th>
						<th class="text-right"><input type="text" name="total_butab" value="0" class="inp50 text-right" id="total_butab" /></th>
						<th class="text-right"><input type="text" name="total_lwk" value="0" class="inp50 text-right" id="total_lwk" /></th>
						<th class="text-right"><input type="text" name="total_asuransi" value="0" class="inp50 text-right" id="total_asuransi" /></th>
					  </tr>					  
					</tfoot>					
					
				</table>  
				<table cellpadding="5">
				<tr>
					<td></td>
					<td><b>TARIK</b></td>
					<td><b>SETOR</b></td>
					<td><b>SALDO</b></td>
				</tr>
				<tr>
					<td><b>TOTAL TABUNGAN</b></td>
					<td><input type="text" name="total_tab_tarik" value="0" class="inp50 text-right" id="total_tab_tarik" onblur="recalculateSum_total_tabungan();" readonly /></td>
					<td><input type="text" name="total_tab_setor" value="<?php echo $tabwajib_total_setor; ?>" class="inp50 text-right" id="total_tab_setor" onblur="recalculateSum_total_tabungan();" readonly /></td>
					<td><input type="text" name="total_tab_saldo" value="<?php echo $tabwajib_total_setor; ?>" class="inp50 text-right" id="total_tab_saldo" onblur="recalculateSum_total_tabungan();"  readonly /></td>
				</tr>
				<tr>
					<td><b>TOTAL RF</b></td>
					<td><input type="text" name="total_rf" value="0" class="inp50 text-right" id="" onblur="recalculateSum_total_rf();" readonly /></td>
					<td><input type="text" name="total_rf" value="<?php echo $total_angsuran; ?>" class="inp50 text-right" id="total_rf" onblur="recalculateSum_total_rf();" readonly /></td>
					<td><input type="text" name="total_saldo_rf" value="<?php echo $total_angsuran; ?>" class="inp50 text-right" id="total_saldo_rf" onblur="recalculateSum_total_rf();"  readonly /></td>
				</tr>
				<tr>
					<td><b>GRAND TOTAL</b></td>
					<td><input type="text" name="grandtotal_debet" value="0" class="inp50 text-right" id="grandtotal_debet" onblur="recalculateSum_grandtotal();"  id="grandtotal_debet" readonly /></td>
					<td><input type="text" name="grandtotal_kredit" value="<?php echo $tabwajib_total_setor+$total_angsuran; ?>" class="inp50 text-right" id="grandtotal_credit" onblur="recalculateSum_grandtotal();" readonly /></td>
					<td><input type="text" name="grandtotal_saldo" value="<?php echo $tabwajib_total_setor+$total_angsuran; ?>" class="inp50 text-right" id="grandtotal_saldo" onblur="recalculateSum_grandtotal();"  readonly /></td>
				</tr>
				</table>
				
			</div>
			<footer class="spanel-footer">
				<div class="row">
					<div class="form-group">
					<div class="col-sm-3 ">
						<br/>
						<input type="hidden" name="ts_id" class="form-control" value="<?php echo set_value('ts_id', isset($data->ts_id) ? $data->ts_id : ''); ?>" />
						<input type="hidden" name="no" class="form-control" id="no" value="<?php echo $totalno; ?>" />
						<input type="hidden" name="group_id" value="<?php echo $group->group_id; ?>" />
						<input type="hidden" name="group_name" value="<?php echo $group->group_name; ?>" />
						<button type="submit" class="btn btn-primary" onclick="return confirmDialog();" >Save Data</button>
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
	<?php for ($i=0;$i<=50;$i++){ ?>

		$("#data_freq_<?php echo $i; ?>").change(function() { 
			var freq = $("#data_freq_<?php echo $i; ?>").val();
			var angsuran = $("#data_baseangsuran_<?php echo $i; ?>").val();
			var angsuranpokok = $("#data_baseangsuranpokok_<?php echo $i; ?>").val();
			var angsuranprofit = $("#data_baseangsuranprofit_<?php echo $i; ?>").val();
			var tabwajib = $("#data_basetabwajib_<?php echo $i; ?>").val();
			var total_angsuran = freq * angsuran;
			var total_angsuranpokok = freq * angsuranpokok;
			var total_angsuranprofit = freq * angsuranprofit;
			var tabwajib_debet = freq * tabwajib;
			//$("#data_totalangsuran_<?php echo $i; ?>").val(total_angsuran.toFixed(1));
			$("#data_totalangsuranpokok_<?php echo $i; ?>").val(total_angsuranpokok.toFixed(1));
			$("#data_totalangsuranprofit_<?php echo $i; ?>").val(total_angsuranprofit.toFixed(1));
			$("#data_tabunganwajib_<?php echo $i; ?>").val(tabwajib_debet.toFixed(1));
			//alert(data);
		});	

		/*
		$("#data_tabsukarela_debet_<?php echo $i; ?>").change(function() { 
			//var total_tabwajib_debet = $("#total_tabsukarela_debet").val();
			<?php for ($j=0;$j<=1;$j++){ ?>
			var freq = $("#data_tabsukarela_debet_<?php echo $j; ?>").val();
			var total_angsuran = total_angsuran + freq;
			<?php } ?>
			$("#total_tabsukarela_debet").val(total_angsuran);
		});	
		*/
		
		
		
	<?php } ?>
});

	function recalculateSum_tabsukarela_debet()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
		var num_<?php echo $i; ?> = parseFloat(document.getElementById("data_tabsukarela_debet_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_tabsukarela_setor").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num_<?php echo $i; ?> + <?php } ?>  + num_<?php echo $totalno;?> ;
		recalculateSum_total_tabungan();
		recalculateSum_grandtotal();
	}
	
	function recalculateSum_tabsukarela_credit()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
		var num_<?php echo $i; ?> = parseFloat(document.getElementById("data_tabsukarela_credit_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_tabsukarela_tarik").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num_<?php echo $i; ?> + <?php } ?>  + num_<?php echo $totalno;?> ;
		recalculateSum_total_tabungan();
		recalculateSum_grandtotal();
	}
	
	function recalculateSum_adm()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
		var num_<?php echo $i; ?> = parseFloat(document.getElementById("data_adm_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_adm").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num_<?php echo $i; ?> + <?php } ?>  + num_<?php echo $totalno;?> ;
		recalculateSum_total_rf();
		recalculateSum_grandtotal();
	}
	
	
	function recalculateSum_butab()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
		var num_<?php echo $i; ?> = parseFloat(document.getElementById("data_butab_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_butab").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num_<?php echo $i; ?> + <?php } ?>  + num_<?php echo $totalno;?> ;
		recalculateSum_total_rf();
		recalculateSum_grandtotal();
	}
	
	
	function recalculateSum_asuransi()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
		var num_<?php echo $i; ?> = parseFloat(document.getElementById("data_asuransi_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_asuransi").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num_<?php echo $i; ?> + <?php } ?>  + num_<?php echo $totalno;?> ;
		recalculateSum_total_rf();
		recalculateSum_grandtotal();
	}
	
	
	function recalculateSum_lwk()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
		var num_<?php echo $i; ?> = parseFloat(document.getElementById("data_lwk_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_lwk").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num_<?php echo $i; ?> + <?php } ?>  + num_<?php echo $totalno;?> ;
		recalculateSum_total_rf();
		recalculateSum_grandtotal();
	}
	
	
	function recalculateSum_tabwajibdebet()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
		var num_<?php echo $i; ?> = parseFloat(document.getElementById("data_tabunganwajib_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_tabwajib_setor").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num_<?php echo $i; ?> + <?php } ?>  + num_<?php echo $totalno;?> ;
		recalculateSum_total_tabungan();
		recalculateSum_grandtotal();
	}
	
	function recalculateSum_tabwajibcredit()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
		var num_<?php echo $i; ?> = parseFloat(document.getElementById("data_tabunganwajibcredit_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_tabwajib_tarik").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num_<?php echo $i; ?> + <?php } ?>  + num_<?php echo $totalno;?> ;
		recalculateSum_total_tabungan();
		recalculateSum_grandtotal();
	}
	
	function recalculateSum_angsuranpokok()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
		var num_<?php echo $i; ?> = parseFloat(document.getElementById("data_totalangsuranpokok_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_angsuranpokok").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num_<?php echo $i; ?> + <?php } ?>  + num_<?php echo $totalno;?> ;
		recalculateSum_total_rf();
		recalculateSum_grandtotal();
	}
	
	function recalculateSum_angsuranprofit()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
		var num_<?php echo $i; ?> = parseFloat(document.getElementById("data_totalangsuranprofit_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_angsuranprofit").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num_<?php echo $i; ?> + <?php } ?>  + num_<?php echo $totalno;?> ;
		recalculateSum_total_rf();
		recalculateSum_grandtotal();
	}
	
	function recalculateSum_angsuran()
	{
		<?php for ($i=1;$i<=$totalno;$i++){ ?>
			var num1_<?php echo $i; ?> = parseFloat(document.getElementById("data_totalangsuranpokok_<?php echo $i; ?>").value);
			var num2_<?php echo $i; ?> = parseFloat(document.getElementById("data_totalangsuranprofit_<?php echo $i; ?>").value);
		<?php } ?>
		
		document.getElementById("total_angsuranpokok").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num1_<?php echo $i; ?> + <?php } ?>  + num1_<?php echo $totalno;?> ;
		document.getElementById("total_angsuranprofit").value =  <?php for ($i=1;$i<=$totalno-1;$i++){ ?> num2_<?php echo $i; ?> + <?php } ?>  + num2_<?php echo $totalno;?> ;
		recalculateSum_total_rf();
		recalculateSum_grandtotal();
	}
	
	function recalculateSum_total_tabungan()
	{		
		var total_tabwajib_setor = parseFloat(document.getElementById("total_tabwajib_setor").value);
		var total_tabwajib_tarik = parseFloat(document.getElementById("total_tabwajib_tarik").value);
		var total_tabsukarela_setor = parseFloat(document.getElementById("total_tabsukarela_setor").value);	
		var total_tabsukarela_tarik = parseFloat(document.getElementById("total_tabsukarela_tarik").value);	
		
		document.getElementById("total_tab_setor").value =  total_tabwajib_setor + total_tabsukarela_setor;
		document.getElementById("total_tab_tarik").value  =  total_tabwajib_tarik + total_tabsukarela_tarik ;
		document.getElementById("total_tab_saldo").value  =  total_tabwajib_setor + total_tabsukarela_setor -  (total_tabwajib_tarik + total_tabsukarela_tarik);	
		//recalculateSum_grandtotal();
	}
	
	function recalculateSum_total_rf()
	{		
		var total_angsuran_pokok = parseFloat(document.getElementById("total_angsuranpokok").value);
		var total_angsuran_profit = parseFloat(document.getElementById("total_angsuranprofit").value);
		var total_adm = parseFloat(document.getElementById("total_adm").value);
		var total_butab = parseFloat(document.getElementById("total_butab").value);
		var total_asuransi = parseFloat(document.getElementById("total_asuransi").value);
		var total_lwk = parseFloat(document.getElementById("total_lwk").value);
		
		document.getElementById("total_rf").value =  total_angsuran_pokok + total_angsuran_profit + total_adm + total_butab + total_asuransi + total_lwk;
		document.getElementById("total_saldo_rf").value =  total_angsuran_pokok + total_angsuran_profit + total_adm + total_butab + total_asuransi + total_lwk;
		//recalculateSum_grandtotal();
	}
	
	function recalculateSum_grandtotal()
	{		
		var total_tab_setor = parseFloat(document.getElementById("total_tab_setor").value);
		var total_tab_tarik = parseFloat(document.getElementById("total_tab_tarik").value);
		var total_rf = parseFloat(document.getElementById("total_rf").value);
		
		document.getElementById("grandtotal_debet").value =  total_tab_tarik;
		document.getElementById("grandtotal_credit").value =  total_tab_setor + total_rf;
		document.getElementById("grandtotal_saldo").value =  total_tab_setor + total_rf - total_tab_tarik; 
	}
</script>
