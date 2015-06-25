
<section class="main">
	<div class="container">
			
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
		<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>', '</div>'); ?>
							
					
		<section class="panel panel-default">	
		<form class="form-horizontal" enctype="multipart/form-data" id="" action="" method="post">
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#personalinfo" data-toggle="tab">Rekapitulasi Kas</a></li>
					</ul>
				</div>
				
			<!-- TABLE BODY -->
			
			<div class="panel-body">
				<div class="table-responsive">
					
						<table class="table table-striped m-b-none text-sm">      
							<thead>                  
							  <tr>
								<th width="30px">No</th>
								<th width="150px">Kantor Cabang</th>
								<th>Tanggal Laporan</th>
								<th class="text-right">Brangkas (Rp)</th>
								<th class="text-right">R F (Rp)</th>
								<th class="text-right">Amanah (Rp)</th>
								<th class="text-center">Total (Rp)</th>
							  </tr>                  
							</thead> 
							<tbody>	
								<tr>     
									<td align="center"><?php echo $no; ?></td>					              
									<td>
										<?php foreach($branch as $b):  ?>
										<input type="text" name="kas_branchname" value="<?php echo $b->branch_name; ?>" class="inp90" readonly />
										<input type="hidden" name="kas_branch" value="<?php echo $b->branch_id; ?>" />
										<?php endforeach; ?>
									</td>
									<td class="text-left"><input type="text" name="kas_date" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" /></td>
									<td class="text-right"><input type="text" name="kas_brangkas" class="inp90 priceformat"/></td>
									<td class="text-right"><input type="text" name="kas_rf" class="inp90 priceformat"/></td>
									<td class="text-right"><input type="text" name="kas_amanah" class="inp90 priceformat"/></td>
									<!--<td class="text-right"><input type="text" name="kas_total" class="inp90"/></td>-->
									<td></td>
								</tr>
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
								<?php foreach($kas as $c):  ?>
									<tr>     
										<td align="center"><?php echo $no; ?></td>	
										<td><?php echo $c->branch_name; ?></td>
										<td class="text-center"><?php echo $c->kas_date; ?></td>
										<td class="text-right"><?php echo number_format($c->kas_brangkas); ?></td>
										<td class="text-right"><?php echo number_format($c->kas_rf); ?></td>
										<td class="text-right"><?php echo number_format($c->kas_amanah); ?></td>
										<td class="text-right"><?php echo number_format($c->kas_total); ?></td>
										
									</tr>
									
								<?php $no++; endforeach; ?>
							</tbody>	
						</table>  
						
					</div>
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-3 ">
							<input type="hidden" name="group_id" class="form-control" id="group_id" placeholder="" value="<?php echo set_value('group_id', isset($data->group_id) ? $data->group_id : ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>	
				</div>
				</form>
			</section>
		</div>
</section>


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
										hasil+="<tr><td>Profit</td><td> : <input  type='text' name='profit' value='' /></td></tr>";
										hasil+="<tr><td>Angsuran</td><td> : <input  type='text' name='angsuran' value='' /></td></tr>";
										hasil+="<tr><td>Akad</td><td> : <select name='akad'><option value='Murabahah'>Murabahah</option><option value='Ijarah'>Ijarah</option><option value='Al Hiwalah'>Al Hiwalah</option></select></tr>";
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