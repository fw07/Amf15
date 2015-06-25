<?php 
$user_level = $this->session->userdata('user_level');
?>


<section class="main">
	<div class="container">
			
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
					
		<section class="panel panel-default panel-body">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-4 m-b-xs">
					<!--<a href="<?php echo site_url().'/branch/pengajuan_reg/'; ?>" class="btn btn-sm btn-info" >Pengajuan Pembiayaan</a>-->
				</div>
				
				<!-- SEARCH FORM -->
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
			</div>
			
			<!-- TABLE BODY -->
			<div class="table-responsive">
				
					<table class="table table-striped m-b-none text-sm">      
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th width="100px">No. Rekening</th>
							<th>Nama Lengkap</th>
							<?php if($this->session->userdata('user_branch') == 0){ ?><th class="text-center">Cabang</th><?php } ?>
							<th class="text-center">Majelis</th>
							<th class="text-center">Plafond</th>
							<th class="text-center">Ke</th>
							<th class="text-center">Tanggal Pengajuan</th>
							<th class="text-center">Tanggal Pencairan</th>
							<th class="text-center">Sektor</th>
							<th class="text-center">Database</th>
							<th class="text-center">Status Pengajuan</th>
							<th class="text-center">Status Pembiayaan</th>
							<?php if($user_level==1 OR $user_level==2 OR $user_level==3){ ?>
							<th width="100px" class="text-center">Pencairan</th>
							<?php } ?>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php
							if(empty($no)){ 
									$no=1; 
									$nostart=1;
									$noend=$config['per_page'];
								}else{ 
									$no=$no+1;
									$nostart=$no;
									$noend=$nostart+$config['per_page']-1;
								} 
						?>
						<?php foreach($clients as $c):  ?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>					              
								<td><?php echo $c->client_account; ?></td>
								<td><a href="<?php echo site_url()."/clients/summary/".$c->client_id; ?>" title="View This Client"><?php echo $c->client_fullname; ?></a></td>
								<?php if($this->session->userdata('user_branch') == 0){ ?><td class="text-center"><?php echo $c->branch_name; ?></td><?php } ?>
								<td class="text-left"><a href="<?php echo site_url()."/group/view/".$c->client_group; ?>" title="View This Group"><?php echo $c->group_name; ?></a></td>
								<td class="text-right"><?php echo number_format($c->data_plafond); ?></td>
								<td class="text-center"><?php echo $c->client_pembiayaan; ?></td>
								<td class="text-center"><?php echo $c->data_tgl; ?></td>
								<td class="text-center"><?php echo $c->data_date_accept; ?></td>
								<td class="text-center"><?php echo $c->sector_name; ?></td>
								<td class="text-center">
									<?php 
										if($c->data_popi_anggotart AND $c->data_popi_masihsekolah AND $c->data_popi_pendidikanistri AND $c->data_popi_pekerjaansuami AND $c->data_popi_jenislantai AND $c->data_popi_jeniswc AND $c-> data_popi_bahanbakar AND $c->data_popi_gas AND $c-> data_popi_kulkas AND $c->data_popi_motor AND $c->data_popi_total AND $c->data_popi_kategori AND
										   $c->data_rmc_ukuranrumah AND $c->data_rmc_kondisirumah AND $c->data_rmc_jenisatap AND $c->data_rmc_jenisdinding AND $c->data_rmc_jenislantai AND $c->data_rmc_listrik AND $c->data_rmc_sumberair AND $c->data_rmc_total AND $c->data_rmc_kategori){
											$database= "OK"; 
											$database_label = "label-success";
										}else{ 
											$database= "NOK";
											$database_label = "label-danger";
										}
									?>
									<span class="label <?php echo $database_label; ?>"><?php echo $database; ?></span>
								</td>
								<td class="text-center"><?php if($c->data_status_pengajuan == "v"){ echo "Disetujui"; }elseif($c->data_status_pengajuan == "x"){ echo "Ditunda"; }elseif($c->data_status_pengajuan == "k"){ echo "Ditolak"; } ?></td>
								<td class="text-center">
									<?php 
										if($c->data_status == "1"){ echo "Berjalan"; }
										elseif($c->data_status == "2"){ echo "Pengajuan"; }
										elseif($c->data_status == "3"){ echo "Lunas"; }
										elseif($c->data_status == "4"){ echo "<a href='' title='".$c->data_alasan."'>Gagal Droping</a>"; }
									?>
								</td>
								<?php if($user_level==1 OR $user_level==2 OR $user_level==3){ ?>
								<td class="text-center">
									<?php if($c->data_status == "2" AND $database == "OK"){  ?>
									<input type="hidden" name="data_id_<?php echo $no; ?>" id="data_id_<?php echo $no; ?>" value="<?php echo $c->data_id; ?>" />
									<a href="#modal" data-toggle="modal" id="modalbtn_<?php echo $no; ?>" title=""><i class="fa fa-money"></i></a>
									<?php } ?>
								</td>
								<?php } ?>
							
							</tr>
							
						<?php $no++; endforeach; ?>
						<?php echo $list;?>
						</tbody>	
					</table>  
					
				</div>
				
				<footer class="panel-footer">
					<div class="row">
						<div class="col-sm-4 text-left"> <small class="text-muted inline m-t-sm m-b-sm">showing <?php echo $nostart; ?>-<?php echo $noend; ?> of <?php echo $config['total_rows']; ?> items</small></div>
						<div class="col-sm-5 text-right text-center-xs pull-right">
							<ul class="pagination pagination-sm m-t-none m-b-none">
								<?php echo $this->pagination->create_links(); ?>
							</ul>
						</div>
					</div>
				</footer>
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
										var tabwajib =1000;
										if(val.data_plafond >=500000 & val.data_plafond <=1500000){
											tabwajib =2000;
										}else if(val.data_plafond >1500000 & val.data_plafond <=2500000){
											tabwajib =3000;
										}else if(val.data_plafond >2500000 & val.data_plafond <=3500000){
											tabwajib =4000;
										}else if(val.data_plafond >3500000 & val.data_plafond <=4500000){
											tabwajib =5000;
										}else if(val.data_plafond >4500000 & val.data_plafond <=5500000){
											tabwajib =6000;
										}else if(val.data_plafond >5500000 & val.data_plafond <=6500000){
											tabwajib =7000;
										}
										hasil+="<table>"; 
										hasil+="<input type='hidden' name='data_id' value='"+val.data_id+"' />";
										hasil+="<input type='hidden' name='data_plafond' value='"+val.data_plafond+"' />";
										hasil+="<tr><td width='140px'>Pembiayaan Ke</td><td> : "+val.data_ke+"</td></tr>";
										hasil+="<tr><td>Tujuan</td><td> : "+val.data_tujuan+" </td></tr>";
										hasil+="<tr><td>Tanggal Pengajuan</td><td> : "+val.data_tgl+" </td></tr>";
										hasil+="<tr><td>Tanggal Pencairan</td><td> : <input type='text' name='date_accept' value='"+val.data_date_accept+"' /></td></tr>";
										//hasil+="<tr><td>Profit</td><td> : <input  type='text' name='profit' value='' /></td></tr>";
										hasil+="<tr><td>Plafond (Rp)</td><td> : <input  type='text' name='plafond' class='' value='"+val.data_plafond+"' /></td></tr>";
										hasil+="<tr><td>Margin (Rp)</td><td> : <input  type='text' name='bunga' value='' /></td></tr>";										
										hasil+="<tr><td>Tab Wajib</td><td> : <input  type='text' name='tabwajib' value='"+tabwajib+"' /></td></tr>";
										//hasil+="<tr><td>Angsuran</td><td> : <input  type='text' name='angsuran' value='' /></td></tr>";
										hasil+="<tr><td>Akad</td><td> : <select name='akad'><option value='MBA'>Murabahah</option><option value='IJR'>Ijarah</option><option value='AHA'>Al Hiwalah</option><option value='MYR'>Musyarakah</option></select></tr>";
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