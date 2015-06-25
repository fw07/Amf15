<style type="text/css">
	/* Hack untuk bxSlider di Homepage */
	.bx-wrapper .bx-controls-direction a {
		margin-top: -10px;
	}
	@media screen and (orientation: portrait){
		.bx-wrapper .bx-pager { margin-top: 0px }
		.banner img { min-height: 160px }
	}
	@media screen and (orientation: landscape){
		.banner img { min-height: 200px }
	}
	.bx-viewport { margin-bottom: 0 }
	@media only screen and (max-width: 640px) {
		.banner img { height: 90% }
		.bx-wrapper .bx-pager { margin-top: 45px }
	}
	@media only screen and (min-width: 641px) and (max-width: 767px) {
		.bx-wrapper .bx-pager { margin-top: 90px }
	}
	@media only screen and (min-width: 768px) {
		.bx-wrapper .bx-pager { margin-top: 130px }
	}
	@media only screen and (min-width: 768px){
		.bx-wrapper { margin-top: 45px }
	}
</style>
<ul id="banner" class="banner">
	<?php foreach($banners as $b): ?>
	<li>
		<a href="<?php echo $b->url; ?>" target="<?php echo $b->target; ?>">
			<img src="<?php echo base_url(); ?>files/banners/<?php echo $b->image; ?>">
		</a>
	</li>
	<?php endforeach; ?>
</ul>
<div class="twelve columns fullscreen" id="menu">
	<h5 class="title">Menu Utama</h5>
	<!-- <iframe src="<?php echo base_url(); ?>menu.php" style="width: 100%; height: 325px; border: 0; overflow: hidden"></iframe> -->
	<ul class="menu">
		<li>
			<div class="arrow"></div>
			<?php echo anchor(site_url('locations'), 'Lokasi BNI'); ?>
		</li>
		<li>
			<div class="arrow"></div>
			<?php echo anchor(site_url('category/index/promo'), 'Promo BNI'); ?>
		</li>
		<li>
			<div class="arrow"></div>
			<?php echo anchor(site_url('home/info'), 'Info BNI'); ?>
		</li>
		<li>
			<div class="arrow"></div>
			<?php echo anchor(site_url('home/chelsea'), 'BNI Chelsea'); ?>
		</li>
		<li>
			<div class="arrow"></div>
			<?php echo anchor(site_url('category/index/product'), 'Belanja'); ?>
		</li>
		<li>
			<div class="arrow"></div>
			<?php echo anchor(site_url('contact'), 'Kontak Kami'); ?>
		</li>
		<li>
			<div class="arrow"></div>
			<?php echo anchor(site_url('fm'), 'BNI Fantasy Manager'); ?>
		</li>
		<?php 
		if($menus):
			foreach($menus as $m):
		?>
		<li>
			<div class="arrow"></div>
			<?php echo anchor($m->url, $m->title,'target="'.$m->target.'"'); ?>
		</li>
		<?php 
			endforeach;
		endif;
		?>
	</ul>
</div>