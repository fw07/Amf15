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
			
				<div class="col-sm-4 m-b-xs pull-left text-left">
					<?php 
						if($this->input->post('date_start') AND $this->input->post('date_end') AND ($this->input->post('date_start') <= $this->input->post('date_end') )){
							echo "FILTER BY DATE : <br/><b>".$this->input->post('date_start')." : ".$this->input->post('date_end')."</b>"; 
						}
					?>
				</div>
			
				<!-- SEARCH FORM -->
				<form action="" method="post"> 
					<div class="col-sm-4 m-b-xs pull-right text-right">
						<input type="text" name="date_start" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd"  placeholder="Date Start">
						<input type="text" name="date_end" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd" placeholder="Date End">
						<button class="btn btn-sm btn-info" type="submit">Filter</button>
					</div>
				</form>	
			</div>
			
			<!-- TABLE BODY -->
			<div class="table-responsive">
				<table class="table table-striped m-b-none text-sm">      
					<thead>                  
					  <tr>
						<th></th>
						<th class="text-center">Konsolidasi</th>
						<th class="text-center">HO</th>
						<th class="text-center">CS</th>
						<th class="text-center">JS</th>
						<th class="text-center">BG</th>
						<th class="text-center">KM</th>
						<th class="text-center">TN</th>
						<th class="text-center">CK</th>
					  </tr>                  
					</thead> 
					<tbody>	
					<?php echo $print; ?>
				
					</tbody>	
				</table>  
			
				<!--<div class="text-center">
					<br/>
					<a href="<?php echo site_url()."/konsolidasi/download_labarugi/"; ?>" target="_blank" class="btn btn-sm btn-primary" >Download Laporan Konsolidasi</a>
					<br/><br/>
				</div>-->
			</div>
				
				
			</section>
		</div>
</section>
