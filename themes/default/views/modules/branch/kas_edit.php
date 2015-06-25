
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
								
							</tbody>	
						</table>  
						
					</div>
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-3 ">
							<input type="hidden" name="kas_id" class="form-control" id="kas_id" placeholder="" value="<?php echo $kas_id; ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>	
				</div>
				</form>
			</section>
		</div>
</section>

