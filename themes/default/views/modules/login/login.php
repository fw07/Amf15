<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
		<div class="container aside-xxl"> <br/><br/><br/><a class="text-center block" href=""><img src="<?php echo $this->template->get_theme_path(); ?>/images/logo_amartha.png" alt="Amartha" /></a> 
			<section class="panel panel-default bg-white m-t-lg">
				<header class="panel-heading text-center"> <strong>Please sign in</strong> </header>
				<?php if(validation_errors()){ ?>
				<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo validation_errors(); ?></div>
				<?php } ?>
				<?php if($this->session->flashdata('message')){ ?>
					<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo $this->session->flashdata('message'); ?></div>
				<?php } ?>
			
				<form method="post" action="<?php echo site_url(); ?>/login/checklogin" class="panel-body wrapper-lg">
					<div class="form-group">
						<label class="control-label">Username</label>
						<input type="text" name="username" placeholder="" class="form-control input-lg" required="">
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
						<input type="password" name="password"  id="inputPassword" placeholder="" class="form-control input-lg" required="">
					</div>

					<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-lock pull-left"></i> Sign in</button>
					
				</form>
			</section>
		</div>
	</section>
	