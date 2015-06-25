
<section class="main">
	<div class="container">
			
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
					
		<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-4 m-b-xs">
					Nama Anggota : <b><?php echo strtoupper($client->client_fullname); ?></b><br/>
					Nomor Rekening : <b><?php echo strtoupper($client->client_account); ?></b><br/>
					Nama Majelis : <b><?php echo strtoupper($client->group_name); ?></b>
				</div>
				
				<!-- SEARCH FORM
				<form action="" method="post"> 
					<div class="col-sm-4 m-b-xs pull-right text-right">
						<select name="key" class="input-sm form-control input-s-sm inline">
							<option value="fullname">Nama </option>
							<option value="account">No Rekening</option>
						</select>
						<input type="text" name="q" class="input-sm form-control input-s-sm inline" placeholder="Search">
						<button class="btn btn-sm btn-default" type="submit">Go!</button>
					</div>
				</form>	
				-->				
			</div>
			
			<!-- TABLE BODY -->
			<div class="table-responsive">
				
					<table class="table table-striped m-b-none text-sm">      
						<thead>                  
						  <tr>
							<th rowspan="2" width="30px">No</th>
							<th rowspan="2"  width="80px">Kode Transaksi</th>
							<th rowspan="2" width="100px">Tanggal</th>
							<th rowspan="2"  class="text-center">Angs. Ke</th>
							<th rowspan="2"  class="text-right">Freq</th>
							<th rowspan="2"  class="text-right">Angsuran</th>
							<th rowspan="2"  class="text-right">Tab. Wajib</th>
							<th rowspan="2"  class="text-right">Tab. Sukarela</th>
							<th rowspan="2"  class="text-right">Sisa Angsuran</th>
							<th rowspan="2"  class="text-center">PAR</th>
							<th rowspan="2"  class="text-center">TR</th>
							<th colspan="5"  class="text-center">Absensi</th>
						  </tr>                  
						  <tr>
							<th class="text-center">H</th>
							<th class="text-center">S</th>
							<th class="text-center">I</th>
							<th class="text-center">C</th>
							<th class="text-center">A</th>
						  </tr>                 
						</thead> 
						<tbody>	
						<?php 
							$no = 1;$par=0;							
							
							foreach($repayment as $c): 
							if($no == 1){ $sisaangsuran = $c->data_plafond + $c->data_margin;  }
							  if($c->tr_freq == 0){ $par++; }
							  $angsuran = $c->tr_freq * $c->data_totalangsuran;
							  $sisaangsuran -= $angsuran;
						?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>					              
								<td><?php echo $c->tr_topsheet_code; ?></td>
								<td><?php echo $c->tr_date; ?></td>
								<td class="text-center"><?php echo $c->tr_angsuranke; ?></td>
								<td class="text-center"><?php echo $c->tr_freq; ?></td>
								<td class="text-right"><?php echo number_format($angsuran); ?></td>
								<td class="text-right"><?php echo number_format($c->tr_tabwajib_debet - $c->tr_tabwajib_credit); ?></td>
								<td class="text-right"><?php echo number_format($c->tr_tabsukarela_debet - $c->tr_tabsukarela_credit); ?></td>
								<td class="text-right"><?php echo number_format($sisaangsuran); ?></td>
								<td class="text-center"><?php echo $par; ?></td>
								<td class="text-center"><?php echo $c->tr_tanggungrenteng; ?></td>
								<td class="text-center"><?php if($c->tr_absen_h == 1) { echo '<i class="fa fa-check text-success"></i>'; }else{ echo "-"; }	?>
								<td class="text-center"><?php if($c->tr_absen_s == 1) { echo '<i class="fa fa-check text-success"></i>'; }else{ echo "-"; }		?>
								<td class="text-center"><?php if($c->tr_absen_i == 1) { echo '<i class="fa fa-check text-success"></i>'; }else{ echo "-"; }		?>
								<td class="text-center"><?php if($c->tr_absen_c == 1) { echo '<i class="fa fa-check text-success"></i>'; }else{ echo "-"; }		?>
								<td class="text-center"><?php if($c->tr_absen_a == 1) { echo '<i class="fa fa-check text-success"></i>'; }else{ echo "-"; }		?>
								</td>
							
							</tr>
							
						<?php $no++; endforeach; ?>
						
						</tbody>	
					</table>  
					
				</div>
				
				
			</section>
		</div>
</section>

<div class="modal fade" id="modal" style="display: none;" aria-hidden="true">
	<div class="modal-dialog">
		
		<div class="modal-content">
			<form method="post" action="<?php echo site_url()."/branch/pencairan_submit/"; ?>" >
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Pencairan Pembiayaan</h4> 
				</div>
				<div class="modal-body" id="result">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-info" >Submit</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>


<script type="text/javascript">

$(document).ready(function() {

	<?php for ($i=1;$i<$no;$i++){ ?>
		//UPDATE PENCAIRAN
			$("#modalbtn_<?php echo $i; ?>").click(function() { 
				  var form_data = { data_id: $("#data_id_<?php echo $i; ?>").val() };		  
				  $.ajax({
						url: "<?php echo site_url('branch/get_pembiayaan'); ?>",
						type: 'POST',
						dataType: 'json',
						data: form_data,
						statusCode: 
								{ 200: function(msg) {
									var hasil='';
									$.each(msg, function(key, val) {
								
										hasil+="<table>"; 
										hasil+="<input type='hidden' name='data_id' value='"+val.data_id+"' />";
										hasil+="<input type='hidden' name='data_plafond' value='"+val.data_plafond+"' />";
										hasil+="<tr><td width='140px'>Pembiayaan Ke</td><td> : "+val.data_pengajuan+"</td></tr>";
										hasil+="<tr><td>Tujuan</td><td> : "+val.data_tujuan+" </td></tr>";
										hasil+="<tr><td>Tanggal Pengajuan</td><td> : "+val.data_tgl+" </td></tr>";
										hasil+="<tr><td>Tanggal Pencairan</td><td> : <input type='text' name='date_accept' value='"+val.data_date_accept+"' /></td></tr>";
										//hasil+="<tr><td>Profit</td><td> : <input  type='text' name='profit' value='' /></td></tr>";
										hasil+="<tr><td>Plafond</td><td> : <input  type='text' name='plafond' class='priceformat' value='"+val.data_plafond+"' /></td></tr>";
										hasil+="<tr><td>Margin (%)</td><td> : <input  type='text' name='bunga' value='' /></td></tr>";										
										hasil+="<tr><td>Tab Wajib</td><td> : <input  type='text' name='bunga' value='"+(val.data_pengajuan * 1000)+"' /></td></tr>";
										//hasil+="<tr><td>Angsuran</td><td> : <input  type='text' name='angsuran' value='' /></td></tr>";
										hasil+="<tr><td>Akad</td><td> : <select name='akad'><option value='Murabahah'>Murabahah</option><option value='Ijarah'>Ijarah</option><option value='Al Hiwalah'>Al Hiwalah</option><option value='Musyarakah'>Musyarakah</option><option value='Qardhul Hasan'>Qardhul Hasan</option></select></tr>";
										hasil+="<tr><td>Status</td><td> : <select name='status'><option value='1'>Cair</option><option value='4'>Gagal Dropping</option></select></tr>";
										hasil+="<tr><td>Alasan Gagal Dropping</td><td> : <input  type='text' name='alasan' value='' /></td></tr>";
										hasil+="</table>";
									});
									$("#result").html(hasil);
									}
								}
				 });
			});	
	
	<?php } ?>
	
		
});
</script>