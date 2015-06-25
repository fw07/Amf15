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
					<a href="<?php echo site_url().'/branch/pengajuan_reg/'; ?>" class="btn btn-sm btn-info" >Pengajuan Pembiayaan</a>
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
							<th width="150px">No. Rekening</th>
							<th>Nama Lengkap</th>
							<?php if($this->session->userdata('user_branch') == 0){ ?><th class="text-center">Cabang</th><?php } ?>
							<th class="text-center">Majelis</th>
							<th class="text-center">Plafond</th>
							<th class="text-center">Ke</th>
							<th class="text-center">Tgl Pengajuan</th>
							<th class="text-center">Tgl Pencairan</th>
							<th class="text-center">Sektor</th>
							<th class="text-center">Status</th>
							<th class="text-left" width="130px">Manage</th>
							<!--<th width="100px" class="text-center">Manage</th>-->
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
								<td><?php echo $c->client_fullname; ?></td>
								<?php if($this->session->userdata('user_branch') == 0){ ?><td class="text-center"><?php echo $c->branch_name; ?></td><?php } ?>
								<td class="text-center"><?php echo $c->group_name; ?></td>
								<td class="text-right" align="right"><?php echo number_format($c->data_plafond); ?></td>
								<td class="text-center"><?php echo $c->data_pengajuan; ?></td>
								<td class="text-center"><?php echo $c->data_tgl; ?></td>
								<td class="text-center"><?php echo $c->data_date_accept; ?></td>
								<td class="text-center"><?php echo $c->sector_name; ?></td>
								<td class="text-center">
									<?php 
										if($c->data_status == "1"){ echo "Berjalan"; }
										elseif($c->data_status == "2"){ echo "Pengajuan"; }
										elseif($c->data_status == "3"){ echo "Selesai"; }
										elseif($c->data_status == "4"){ echo "<a href='' title='".$c->data_alasan."'>Gagal Droping</a>"; }
									?>
								</td>
								<td class="text-left">
									<?php if($c->data_status == "2"){  ?>
									<select name="status_pengajuan_<?php echo $no; ?>" id="status_pengajuan_<?php echo $no; ?>">
										<option value="v" <?php if($c->data_status_pengajuan == "v"){ echo "selected"; } ?> >Disetujui</option>
										<option value="x" <?php if($c->data_status_pengajuan == "x"){ echo "selected"; } ?> >Ditunda</option>
										<option value="k" <?php if($c->data_status_pengajuan == "k"){ echo "selected"; } ?> >Komite</option>
									</select>
									<input type="hidden" name="data_id_<?php echo $no; ?>" id="data_id_<?php echo $no; ?>" value="<?php echo $c->data_id; ?>" />
									<span id="result_<?php echo $no; ?>"></span>
									<?php } ?>
								</td>
								<!--
								<td class="text-center">									
									<a href="<?php echo site_url()."/clients/view/".$c->data_id; ?>" title="Pengajuan Pembiayaan"><i class="fa fa-trash-o"></i></a> 
								</td>
								-->
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

<script type="text/javascript">
	$(document).ready(function() {
		<?php for ($i=1;$i<$no;$i++){ ?>
			$("#status_pengajuan_<?php echo $i; ?>").change(function() { 
				  var form_data = { status: $("#status_pengajuan_<?php echo $i; ?>").val() , data_id : $("#data_id_<?php echo $i; ?>").val()};		  
				  $.ajax({
						url: "<?php echo site_url('branch/update_status_pengajuan'); ?>",
						type: 'POST',
						dataType: 'json',
						data: form_data,
						statusCode: 
							{ 200: function() {
								var hasil ="<i class='fa fa-check text-success'></i>";
								$("#result_<?php echo $i; ?>").html(hasil);
								}
							}
						
				 });
			});	
		
		<?php } ?>
	});
</script>