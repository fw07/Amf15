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
					
		<section class="panel panel-default">
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
							<th class="text-center">Status Pembiayaan</th>
							<?php if($user_level==1 OR $user_level==2){ ?>
							<th width="100px" class="text-center">Monitoring</th>
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
								<td class="text-center"><a href="<?php echo site_url()."/group/view/".$c->client_group; ?>" title="View This Group"><?php echo $c->group_name; ?></a></td>
								<td class="text-right"><?php echo number_format($c->data_plafond); ?></td>
								<td class="text-center"><?php echo $c->client_pembiayaan; ?></td>
								<td class="text-center"><?php echo $c->data_tgl; ?></td>
								<td class="text-center"><?php echo $c->data_date_accept; ?></td>
								<td class="text-center"><?php echo $c->sector_name; ?></td>
								<td class="text-center">
									<?php 
										if($c->data_status == "1"){ echo "Berjalan"; }
										elseif($c->data_status == "2"){ echo "Pengajuan"; }
										elseif($c->data_status == "3"){ echo "Lunas"; }
										elseif($c->data_status == "4"){ echo "<a href='' title='".$c->data_alasan."'>Gagal Droping</a>"; }
									?>
								</td>
								<?php if($user_level==1 OR $user_level==2){ ?>
								<td class="text-center">
									<input type="hidden" name="data_id_<?php echo $no; ?>" id="data_id_<?php echo $no; ?>" value="<?php echo $c->data_id; ?>" />
									<?php if($c->data_monitoring_pembiayaan == "0"){  ?>
									<a href="#modal" data-toggle="modal" id="modalbtn_<?php echo $no; ?>" title=""><i class="fa fa-calendar"></i></a>
									<?php }elseif($c->data_monitoring_pembiayaan == "1"){  ?>
									<a href="#modal" data-toggle="modal" id="modalbtn_<?php echo $no; ?>" title=""><i class="fa fa-check text-success"></i></a>
									<?php }elseif($c->data_monitoring_pembiayaan == "2"){  ?>
									<a href="#modal" data-toggle="modal" id="modalbtn_<?php echo $no; ?>" title=""><i class="fa fa-times text-danger"></i></a>
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
			<form method="post" action="<?php echo site_url()."/branch/monitoring_submit/"; ?>" >
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Monitoring Pembiayaan</h4> 
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
										hasil+="<tr><td width='140px'>Pembiayaan Ke</td><td> : "+val.data_ke+"</td></tr>";
										hasil+="<tr><td>Tujuan</td><td> : "+val.data_tujuan+" </td></tr>";
										if(val.data_monitoring_pembiayaan == 1){
											hasil+="<tr><td>Tanggal Monitoring</td><td> : <input type='text' name='data_monitoring_pembiayaan_date' value='"+val.data_monitoring_pembiayaan_date+"' /></td></tr>";
											hasil+="<tr><td>Hasil Monitoring</td><td> : <input type='radio' name='data_monitoring_pembiayaan' value='1' checked /> OK &nbsp;&nbsp; <input type='radio' name='data_monitoring_pembiayaan' value='2' /> NOK </td></tr>";											
										}else if(val.data_monitoring_pembiayaan == 2){
											hasil+="<tr><td>Tanggal Monitoring</td><td> : <input type='text' name='data_monitoring_pembiayaan_date' value='"+val.data_monitoring_pembiayaan_date+"' /></td></tr>";
											hasil+="<tr><td>Hasil Monitoring</td><td> : <input type='radio' name='data_monitoring_pembiayaan' value='1' /> OK &nbsp;&nbsp; <input type='radio' name='data_monitoring_pembiayaan' value='2' checked /> NOK </td></tr>";											
										}else{
											hasil+="<tr><td>Tanggal Monitoring</td><td> : <input type='text' name='data_monitoring_pembiayaan_date' value='<?php echo date("Y-m-d");?>' /></td></tr>";
											hasil+="<tr><td>Hasil Monitoring</td><td> : <input type='radio' name='data_monitoring_pembiayaan' value='1' checked /> OK &nbsp;&nbsp; <input type='radio' name='data_monitoring_pembiayaan' value='2' /> NOK </td></tr>";
										}
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