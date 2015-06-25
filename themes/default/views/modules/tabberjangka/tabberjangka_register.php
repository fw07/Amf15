<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
		
		<form class="form-horizontal" enctype="multipart/form-data" id="createClientForm" action="" method="post" data-validate="parsley"> 
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#personalinfo" data-toggle="tab">Entry Data</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body" style="overflow: auto; padding-right: 10px;">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>', '</div>'); ?>
							<?php if($this->session->flashdata('message')){ ?>
									<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
							<?php } ?>
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="hidden"><i class="fa fa-check text-success"></i></td>
										<td width="30px" class="text-center">No</td>
										<td>Majelis</td>
										<td>Nama</td>
										<td>Tgl Pengajuan</td>
										<td>Paket Tabungan</td>
										<td>Jangka Waktu</td>
									</tr>
								</thead>
								<tbody>
									<?php for($i=1;$i<=30;$i++){ ?>
									<tr>
										<td class="hidden"><input type="checkbox" name="client_reg_<?php echo $i; ?>"  value="1" checked class="hidden" /></td>
										<td class="text-center"><?php echo $i; ?></td>
										<td>
											<select name="client_group_<?php echo $i; ?>" id="client_group_<?php echo $i; ?>">
												<option value="0" >Majelis</option>
												<?php foreach($group as $g):  ?>
													<option value="<?php echo $g->group_id; ?>" ><?php echo $g->group_name; ?></option>
												<?php endforeach;  ?>
										  </select>
										</td>
										<td>
											<select name="client_name_<?php echo $i; ?>" id="client_name_<?php echo $i; ?>">
												<option value="0">Anggota</option>
											</select>										
										</td>
										<td><input type="text" name="client_date_<?php echo $i; ?>" 	value="" 	class="inp80 datepicker-input" data-date-format="yyyy-mm-dd" ></td>
										<td>
											<select name="client_paket_<?php echo $i; ?>" >
												<option value="5000">Rp 5.000</option>
												<option value="10000">Rp 10.000</option>
												<option value="15000">Rp 15.000</option>
												<option value="20000">Rp 20.000</option>

											</select>										
										</td>
										<td>
											<select name="client_waktu_<?php echo $i; ?>" >
												<option value="50">1 tahun (50 minggu)</option>
												<option value="100">2 tahun (100 minggu</option>
												<option value="150">3 tahun (150 minggu</option>

											</select>										
										</td>
										
									</tr>
									<?php } ?>
								</tbody>
							</table>
							
						</div>		
						
					</div>
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-2 ">
							<input type="hidden"  name="no" value="<?php echo $i-1; ?>" />
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
		</form>
	</div>
</div>	

<script type="text/javascript">

$(document).ready(function() {
	<?php for ($i=0;$i<=30;$i++){ ?>

		$("#client_group_<?php echo $i; ?>").change(function() { 
			  var form_data = { name: $("#client_group_<?php echo $i; ?>").val() };		  
			  $.ajax({
					url: "<?php echo site_url('tabberjangka/getclient'); ?>",
					type: 'POST',
					dataType: 'json',
					data: form_data,
					success: function(msg) {
						var hasil='';
						$.each(msg, function(key, val) {
							hasil+='<option value="'+val.client_id+'">'+val.client_fullname+'</option>';
						});
						$("#client_name_<?php echo $i; ?> option").remove();
						$("#client_name_<?php echo $i; ?>").append(hasil);
					}
			 });
		});	
	
	<?php } ?>
});
</script>