<?php 
$user_level = $this->session->userdata('user_level');
?>
										
<!DOCTYPE html>
<html lang="en" class="app">

<head>
	<meta charset="utf-8" />
	<title><?php echo $menu_title; ?> | Amartha MIS</title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="robots" content="noindex, nofollow" />
	<link rel="stylesheet" href="<?php echo $this->template->get_theme_path(); ?>/css/app.v2.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->template->get_theme_path(); ?>/css/font.css" type="text/css" cache="false" />
	<link rel="stylesheet" href="<?php echo $this->template->get_theme_path(); ?>/js/calendar/bootstrap_calendar.css" type="text/css" cache="false" />	
	<link rel="stylesheet" href="<?php echo $this->template->get_theme_path(); ?>/js/datepicker/datepicker.css" type="text/css" cache="false" />
	<link rel="stylesheet" href="<?php echo $this->template->get_theme_path(); ?>/css/custom.css" type="text/css" />
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/jquery-1.10.2.min.js" cache="false"></script>
	<!--[if lt IE 9]> <script src="js/ie/html5shiv.js" cache="false"></script> <script src="js/ie/respond.min.js" cache="false"></script> <script src="js/ie/excanvas.js" cache="false"></script> <![endif]-->
</head>

<body>
	<section class="vbox">
		<!-- TOP MENU -->
		<header class="bg-dark dk header navbar navbar-fixed-top-xs">
			<div class="navbar-header aside-md">
				<a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav"> <i class="fa fa-bars"></i> 
				</a>
				<a href="#" class="navbar-brand" data-toggle="fullscreen">
					<img src="<?php echo $this->template->get_theme_path(); ?>/images/logo_amartha.png" class="m-r-sm" alt="Amartha"> </a>
				<a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user"> <i class="fa fa-cog"></i> 
				</a>
			</div>
			<!--
			<ul class="nav navbar-nav hidden-xs">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle dker" data-toggle="dropdown"> <i class="fa fa-building-o"></i>  <span class="font-bold"> Cabang Ciseeng</span></a>
				</li>
				<li>
					<div class="m-t m-l"> <a href="#" class="dropdown-toggle btn btn-xs btn-primary" title="Upgrade"><i class="fa fa-building-o icon"></i>&nbsp;&nbsp;Kantor Pusat</a> 
					</div>
				</li>				
			</ul>-->
			
			<ul class="nav navbar-nav navbar-right hidden-xs nav-user">
				
				<li class="dropdown hidden-xs"> <a href="#" class="dker"><?php echo date("D, d M Y");?></a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
						<!--<span class="thumb-sm avatar pull-left"> 
						<img src="<?php echo $this->template->get_theme_path(); ?>/images/avatar.jpg"> 
						</span>-->
						<?php echo $this->session->userdata('user_fullname');?> <b class="caret"></b> 
					</a>
					<ul class="dropdown-menu animated fadeInRight"> <span class="arrow top"></span> 
						<li><a href="<?php echo site_url(); ?>/users/setting_user/<?php echo $this->session->userdata('user_id') ?>">Settings</a></li>
						<li class="divider"></li>
						<li> <a href="<?php echo site_url(); ?>/login/logout" >Logout</a> 
						</li>
					</ul>
				</li>
			</ul>
		</header>
		<!-- END TOP MENU -->
		
		<section>
			<section class="hbox stretch">
				<!-- .aside -->
				<aside class="bg-dark lter aside-md hidden-print" id="nav">
					<section class="vbox">
						<header class="header bg-primary lter text-center clearfix">
							<div class="btn-group">
								<button type="button" class="btn btn-sm btn-dark btn-icon" title="Cabang"><i class="fa fa-building-o"></i></button>
								
								<div class="btn-group hidden-nav-xs">
									<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
										<?php 
											if($this->session->userdata('user_branch')==0){ echo "Kantor Pusat"; }
											else{ echo "Cabang ".$this->session->userdata('user_branch_name'); }
										?> 
										<!--&nbsp;&nbsp;<span class="caret"></span>-->
									</button>
									<?php if($user_level==1){ ?>
									<!---->
									<ul class="dropdown-menu text-left">
										<li><a href="<?php echo site_url();?>/switchbranch/cabang/0">Kantor Pusat</a></li>
										<li><a href="<?php echo site_url();?>/switchbranch/cabang/1">Cabang Ciseeng</a></li>
										<li><a href="<?php echo site_url();?>/switchbranch/cabang/2">Cabang Kemang</a></li>
										<li><a href="<?php echo site_url();?>/switchbranch/cabang/4">Cabang Jasinga</a></li>
										<li><a href="<?php echo site_url();?>/switchbranch/cabang/3">Cabang Bojong</a></li>
										<li><a href="<?php echo site_url();?>/switchbranch/cabang/5">Cabang Tenjo</a></li>
										<li><a href="<?php echo site_url();?>/switchbranch/cabang/6">Cabang Cangkuang</a></li> 
									</ul>
									
									<?php } ?>
								</div>
								
							</div>
						</header>
						<section class="w-f scrollable">
							<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
								<!-- nav -->
								<nav class="nav-primary hidden-xs">
									<ul class="nav">
										<li class="<?php echo $menu_dashboard; ?>">
											<a href="<?php echo site_url(); ?>/dashboard/" class="active"> <i class="fa fa-dashboard icon"> <b class="bg-danger"></b> </i>  <span>Dashboard</span></a>
											<?php 
											if($user_level==1){ 
											?>
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/dashboard/"> <i class="fa fa-angle-right"></i>  <span>Dashboard</span></a></li>
												<li><a href="<?php echo site_url(); ?>/dashboard/narative_report"> <i class="fa fa-angle-right"></i>  <span>Narative Report</span></a></li>
												<li><a href="<?php echo site_url(); ?>/report/finance_week/dashboad_week"> <i class="fa fa-angle-right"></i>  <span>Weekly Progress</span></a></li>
											</ul>
											<?php } ?>
											<ul class="nav lt">								
												<li><a href="<?php echo site_url(); ?>/topsheet/schedule/"> <i class="fa fa-angle-right"></i>  <span>Schedule</span></a></li>	
												</ul>
										</li>
										<?php 
										if($user_level==2 OR $user_level==3){ $menu_branch="active"; }
										?>
										<li class="<?php echo $menu_branch; ?>">
											<a href="#layout"> <i class="fa fa-building-o icon"> <b class="bg-info"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span>  <span>Kantor Cabang</span></a>
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/branch/group"> <i class="fa fa-angle-right"></i>  <span>Majelis</span></a></li>
												<li><a href="<?php echo site_url(); ?>/branch/client"> <i class="fa fa-angle-right"></i>  <span>Anggota</span></a></li>
												<li><a href="<?php echo site_url(); ?>/branch/pengajuan"> <i class="fa fa-angle-right"></i>  <span>Pengajuan</span></a></li>
												<li><a href="<?php echo site_url(); ?>/branch/pencairan"> <i class="fa fa-angle-right"></i>  <span>Pencairan</span></a></li>
												<li><a href="<?php echo site_url(); ?>/branch/monitoring_pembiayaan"> <i class="fa fa-angle-right"></i>  <span>Monitoring</span></a></li>
												<li><a href="<?php echo site_url(); ?>/branch/client_unreg_list"> <i class="fa fa-angle-right"></i>  <span>Anggota Keluar</span></a></li>
												<li><a href="<?php echo site_url(); ?>/branch/kas"> <i class="fa fa-angle-right"></i>  <span>Laporan Kas</span></a></li>												
												<li><a href="<?php echo site_url(); ?>/report"> <i class="fa fa-angle-right"></i>  <span>Laporan Mingguan</span></a></li>
												<li><a href="<?php echo site_url(); ?>/officer"> <i class="fa fa-angle-right"></i>  <span>Pendamping Lapangan</span></a></li>
											</ul>
										</li>
										<?php if($user_level==1){ ?>
										<li class="<?php echo $menu_client; ?>">
											<a href="#layout"> <i class="fa fa-male icon"> <b class="bg-warning"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span>  <span>Anggota</span></a>
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/branch/client"> <i class="fa fa-angle-right"></i>  <span>Data Anggota</span></a></li>
												<li><a href="<?php echo site_url(); ?>/branch/client_reg"> <i class="fa fa-angle-right"></i>  <span>Registrasi Anggota</span></a></li>
												<li><a href="<?php echo site_url(); ?>/pembiayaan"> <i class="fa fa-angle-right"></i>  <span>Pembiayaan</span></a></li>
												<li><a href="<?php echo site_url(); ?>/pembiayaan/par"> <i class="fa fa-angle-right"></i>  <span>P A R</span></a></li>
											</ul>
										</li>
										<li class="<?php echo $menu_group; ?>">
											<a href="#layout"> <i class="fa fa-group icon"> <b class="bg-warning dker"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span>  <span>Majelis</span></a>
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/branch/group"> <i class="fa fa-angle-right"></i>  <span>Data Majelis</span></a></li>
												<li><a href="<?php echo site_url(); ?>/group/register"> <i class="fa fa-angle-right"></i>  <span>Registrasi Majelis</span></a></li>
												<li><a href="<?php echo site_url(); ?>/branch/group_performance"> <i class="fa fa-angle-right"></i>  <span>Kehadiran Majelis</span></a></li>
											</ul>
										</li>
										<li class="<?php echo $menu_investor; ?>">
											<a href="#layout"> <i class="fa fa-usd icon"> <b class="bg-warning dker"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span>  <span>Investor</span></a>
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/lenders"> <i class="fa fa-angle-right"></i>  <span>Data Investor</span></a></li>
												<li><a href="<?php echo site_url(); ?>/lenders/registration"> <i class="fa fa-angle-right"></i>  <span>Registrasi Investor</span></a></li>
												<li><a href="<?php echo site_url(); ?>/lenders/investment"> <i class="fa fa-angle-right"></i>  <span>Data Investasi</span></a></li>
												<li><a href="<?php echo site_url(); ?>/lenders/investment_recap"> <i class="fa fa-angle-right"></i>  <span>Rekap Investasi</span></a></li>
											</ul>
										</li>
										<!--<li class="<?php echo $menu_office; ?>">
											<a href="#uikit"> <i class="fa fa-building-o icon"> <b class="bg-success"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span>  <span>Kantor Cabang</span></a>
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/officer/"> <i class="fa fa-angle-right"></i>  <span>Pendamping Lapangan</span></a></li>
												<li><a href="<?php echo site_url(); ?>/branch/"> <i class="fa fa-angle-right"></i>  <span>Kantor Cabang</span></a></li>
											</ul>
										</li>-->
										<?php } ?>
										<li class="<?php echo $menu_transaksi; ?>">
											<a href="#uikit"> <i class="fa fa-money icon"> <b class="bg-success"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span>  <span>Transaksi</span></a>
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/topsheet/"> <i class="fa fa-angle-right"></i>  <span>Top Sheet</span></a></li>												
												<?php if($user_level==1 OR $user_level==3 OR $user_level==2){ ?>
												<li><a href="<?php echo site_url(); ?>/topsheet/tsdaily/"> <i class="fa fa-angle-right"></i>  <span>Rekap Topsheet</span></a></li>												
												<!--<li><a href="<?php echo site_url(); ?>/topsheet/tsdaily_report/"> <i class="fa fa-angle-right"></i>  <span>Rekap Harian </span></a></li>-->
												<li><a href="<?php echo site_url(); ?>/topsheet/ts_report/tsdaily_report"> <i class="fa fa-angle-right"></i>  <span>Rekap Harian </span></a></li>										
												<li><a href="<?php echo site_url(); ?>/topsheet/tsdaily_apel/"> <i class="fa fa-angle-right"></i>  <span>Rekap Topsheet (Apel)</span></a></li>										
												<li><a href="<?php echo site_url(); ?>/topsheet/schedule/"> <i class="fa fa-angle-right"></i>  <span>Schedule</span></a></li>																								
												<?php } ?>
											</ul>
										</li>
										<?php if($user_level==1 OR $user_level==2 OR $user_level==3 ){ ?>
										<li class="<?php echo $menu_saving; ?>">
											<a href="#uikit"> <i class="fa fa-book icon"> <b class="bg-success dker"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span>  <span>Tabungan</span></a>
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/saving/tabwajib"> <i class="fa fa-angle-right"></i>  <span>Tabungan Wajib</span></a></li>												
												<li><a href="<?php echo site_url(); ?>/saving/tabsukarela"> <i class="fa fa-angle-right"></i>  <span>Tabungan Sukarela</span></a></li>												
												<li><a href="<?php echo site_url(); ?>/tabberjangka/"> <i class="fa fa-angle-right"></i>  <span>Tabungan Berjangka</span></a></li>											
											</ul>
										</li>
										<?php } ?>
										<?php if($user_level==1 OR $user_level==3 OR $user_level==5){ ?>
										<li class="<?php echo $menu_jurnal; ?>">
											<a href="#pages"> <i class="fa fa-file-text icon"> <b class="bg-primary"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span>  <span>Accounting</span> 
											</a>
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/accounting/jurnal"> <i class="fa fa-angle-right"></i>  <span>Jurnal</span></a></li>
												<li><a href="<?php echo site_url(); ?>/accounting/neraca"> <i class="fa fa-angle-right"></i>  <span>Neraca</span></a></li>
												<li><a href="<?php echo site_url(); ?>/accounting/laba_rugi"> <i class="fa fa-angle-right"></i>  <span>Laba Rugi</span></a></li>
												<!--<li><a href="<?php echo site_url(); ?>/accounting/neraca_simple"> <i class="fa fa-angle-right"></i>  <span>Neraca Simple</span></a></li>-->
												<!--<li><a href="<?php echo site_url(); ?>/accounting/laporan_keuangan"> <i class="fa fa-angle-right"></i>  <span>Laporan Keuangan</span></a></li>-->
												<li><a href="<?php echo site_url(); ?>/accounting/general_ledger"> <i class="fa fa-angle-right"></i>  <span>Buku Besar</span></a></li>
												<!--<li><a href="<?php echo site_url(); ?>/accounting/laporan_arus_kas"> <i class="fa fa-angle-right"></i>  <span>Laporan Arus Kas</span></a></li>
												<li><a href="<?php echo site_url(); ?>/accounting/laporan_ekuitas"> <i class="fa fa-angle-right"></i>  <span>Laporan Ekuitas</span></a></li>
												-->
												<li><a href="<?php echo site_url(); ?>/bukukas/kaskecil"> <i class="fa fa-angle-right"></i>  <span>Kas Kecil</span></a></li>
												<!--<li><a href="<?php echo site_url(); ?>/accounting/laporan_shu"> <i class="fa fa-angle-right"></i>  <span>Laporan SHU</span></a></li>-->
											</ul>
										</li>
										<?php } ?>
										<?php if($user_level==1 OR $user_level==5){ ?>
										<li class="<?php echo $menu_report; ?>">
											<a href="#pages"> <i class="fa fa-file-text icon"> <b class="bg-primary dker"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span><span>Report</span></a>											
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/report/finance"> <i class="fa fa-angle-right"></i>  <span>Finance</span></a></li>
												<li><a href="<?php echo site_url(); ?>/report/audit"> <i class="fa fa-angle-right"></i>  <span>Audit</span></a></li>
												<li><a href="<?php echo site_url(); ?>/report/operation"> <i class="fa fa-angle-right"></i>  <span>Operation</span></a></li>

											</ul>
										</li>
										<?php } ?>
										<?php if($user_level==1 OR $user_level==5){ ?>
										<li class="<?php echo $menu_konsolidasi; ?>">
											<a href="#pages"> <i class="fa fa-file-text icon"> <b class="bg-primary dker"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span><span>Konsolidasi</span></a>											
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/konsolidasi/neraca"> <i class="fa fa-angle-right"></i>  <span>Neraca</span></a></li>
												<li><a href="<?php echo site_url(); ?>/konsolidasi/laba_rugi"> <i class="fa fa-angle-right"></i>  <span>Laba Rugi</span></a></li>
												<li><a href="<?php echo site_url(); ?>/portfolio/par"> <i class="fa fa-angle-right"></i>  <span>Portfolio</span></a></li>
												<li><a href="<?php echo site_url(); ?>/asuransi"> <i class="fa fa-angle-right"></i>  <span>Asuransi</span></a></li> 
												<li><a href="<?php echo site_url(); ?>/insentif"> <i class="fa fa-angle-right"></i>  <span>Insentif</span></a></li>
												<li><a href="<?php echo site_url(); ?>/regpyd/download"> <i class="fa fa-angle-right"></i>  <span>REGPYD</span></a></li>
											</ul>
										</li>
										<?php } ?>
										<?php if($user_level==1){ ?>
										<li class="<?php echo $menu_setting; ?>">
											<a href="#pages"> <i class="fa fa-cog icon"> <b class="bg-primary dker"></b> </i>  <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span><span>Settings</span></a>											
											<ul class="nav lt">
												<li><a href="<?php echo site_url(); ?>/setting/branch"> <i class="fa fa-angle-right"></i>  <span>Setting Branch</span></a></li>
												<li><a href="<?php echo site_url(); ?>/setting/area"> <i class="fa fa-angle-right"></i>  <span>Setting Area</span></a></li>
												<li><a href="<?php echo site_url(); ?>/setting/target_ops"> <i class="fa fa-angle-right"></i>  <span>Setting Target Params</span></a></li>
												<li><a href="<?php echo site_url(); ?>/users"> <i class="fa fa-angle-right"></i>  <span>User Account</span></a></li>
											</ul>
										</li>
										<?php } ?>
									</ul>
								</nav>
								<!-- / nav -->
							</div>
						</section>
						<footer class="footer lt hidden-xs b-t b-dark">
							<!--
							<div id="chat" class="dropup">
								<section class="dropdown-menu on aside-md m-l-n">
									<section class="panel bg-white">
										<header class="panel-heading b-b b-light">Active chats</header>
										<div class="panel-body animated fadeInRight">
											<p class="text-sm">No active chats.</p>
											<p><a href="#" class="btn btn-sm btn-default">Start a chat</a>
											</p>
										</div>
									</section>
								</section>
							</div>
							<div id="invite" class="dropup">
								<section class="dropdown-menu on aside-md m-l-n">
									<section class="panel bg-white">
										<header class="panel-heading b-b b-light">John <i class="fa fa-circle text-success"></i> 
										</header>
										<div class="panel-body animated fadeInRight">
											<p class="text-sm">No contacts in your lists.</p>
											<p><a href="#" class="btn btn-sm btn-facebook"><i class="fa fa-fw fa-facebook"></i> Invite from Facebook</a>
											</p>
										</div>
									</section>
								</section>
							</div>
							--><?php echo "<small class='pull-left'><br/>".$this->benchmark->elapsed_time()." | ".$this->benchmark->memory_usage()."</small>";?>
							<a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-dark btn-icon"> <i class="fa fa-angle-left text"></i>  <i class="fa fa-angle-right text-active"></i> 
							</a>
							<!--
							<div class="btn-group hidden-nav-xs">
								<button type="button" title="Chats" class="btn btn-icon btn-sm btn-dark" data-toggle="dropdown" data-target="#chat"><i class="fa fa-comment-o"></i>
								</button>
								<button type="button" title="Contacts" class="btn btn-icon btn-sm btn-dark" data-toggle="dropdown" data-target="#invite"><i class="fa fa-facebook"></i>
								</button>
							</div>
							-->
						</footer>
					</section>
				</aside>
				<!-- /.aside -->
				
				<!-- CONTENT -->
				<section id="content">
					<section class="vbox">
						<section class="scrollable padder">
					<?php echo $template['body']; ?>
					
	
						</section>
					</section>
					<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
				</section>
				<aside class="bg-light lter b-l aside-md hide" id="notes">
					<div class="wrapper">Notification</div>
				</aside>
			</section>
		</section>
	</section>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/app.v2.js"></script>
	<!-- Bootstrap -->
	<!-- App -->
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/charts/easypiechart/jquery.easy-pie-chart.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/datepicker/bootstrap-datepicker.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/charts/sparkline/jquery.sparkline.min.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/charts/flot/jquery.flot.min.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/charts/flot/jquery.flot.tooltip.min.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/charts/flot/jquery.flot.resize.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/charts/flot/jquery.flot.grow.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/charts/flot/demo.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/calendar/bootstrap_calendar.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/calendar/demo.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/sortable/jquery.sortable.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/priceformat/jquery.price_format.2.0.min.js" cache="false"></script>
	<script src="<?php echo $this->template->get_theme_path(); ?>/js/jquery.table2excel.min.js" cache="false"></script>
	
	<script>
		function confirmDialog() {
			return confirm("Are you sure you want to delete this record?")
		}
		
		function confirmSave() {
			return confirm("Are you sure you want to save this record?")
		}
		function confirmSaving() {
			return confirm("Are you sure you want to open Saving Account?")
		}
		$( document ).ready(function() {
			$('.priceformat').priceFormat({
				prefix: '',
				centsSeparator: ',',
				thousandsSeparator: '.',
				centsLimit: 0,
			});
		});
	</script>
	
</body>

</html>