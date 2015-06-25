
							<ul class="breadcrumb no-border no-radius b-b b-light pull-in">
								<li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
								<li class="active">Dashboard</li>
							</ul>
							<div class="m-b-md">
								<h3 class="m-b-none">Dashboard</h3>  <small>Welcome back, <?php echo $this->session->userdata('user_fullname');?></small> 
							</div>
							<section class="panel panel-default">
								<div class="row m-l-none m-r-none bg-light lter">
									<div class="col-sm-6 col-md-3 padder-v b-r b-light"> <span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-info"></i> <i class="fa fa-male fa-stack-1x text-white"></i> </span> 
										<a class="clear" href="#"> <span class="h3 block m-t-xs"><strong><?php echo $total_anggota; ?></strong></span>  <small class="text-muted text-uc">ANGGOTA</small> 
										</a>
									</div>
									<div class="col-sm-6 col-md-3 padder-v b-r b-light lt"> <span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-warning"></i> <i class="fa fa-group fa-stack-1x text-white"></i> </span>
										<a
										class="clear" href="#"> <span class="h3 block m-t-xs"><strong id="bugs"><?php echo $total_majelis; ?></strong></span>  <small class="text-muted text-uc">MAJELIS</small> 
											</a>
									</div>
									<div class="col-sm-6 col-md-3 padder-v b-r b-light"> <span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-danger"></i> <i class="fa fa-building-o fa-stack-1x text-white"></i> <!--<span class="easypiechart pos-abt" data-percent="100" data-line-width="4" data-track-Color="#f5f5f5" data-scale-Color="false" data-size="50" data-line-cap='butt' data-animate="3000" data-target="#firers" data-update="5000"></span>--> </span>
										<a
										class="clear" href="#"> <span class="h3 block m-t-xs"><strong id="firers"><?php echo $total_cabang; ?></strong></span>  <small class="text-muted text-uc">CABANG</small> 
											</a>
									</div>
									<div class="col-sm-6 col-md-3 padder-v b-r b-light lt"> <span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x icon-muted"></i> <i class="fa fa-briefcase fa-stack-1x text-white"></i> </span> 
										<a class="clear" href="#"> <span class="h3 block m-t-xs"><strong><?php echo $total_tpl; ?></strong></span>  <small class="text-muted text-uc">PENDAMPING</small> 
										</a>
									</div>
								</div>
							</section>
							<div class="row">
								
								<!-- SECTION LEFT -->								
								<div class="col-md-6">
									<!-- GRAFIK Anggota -->
									<section class="hidden panel panel-default">
										<header class="panel-heading font-bold">
											&nbsp;
											<div class="pull-left">Grafik Pertumbuhan Anggota </div>
											<div class="pull-right">
												<form method="post" action="<?php echo site_url(); ?>/dashboard/">
												<select name="filter" onchange="submit()">
													<option value="">Filter</option>
													<option value="2014-12" >Dec 2014</option>
													<option value="2014-11" >Nov 2014</option>
													<option value="2014-10" >Oct 2014</option>
													<option value="2014-09" >Sept 2014</option>
													<option value="2014-08" >Aug 2014</option>
													<option value="2014-07" >July 2014</option>
													<option value="2014-06" >June 2014</option>
													<option value="2014-05" >May 2014</option>
												</select>
												</form>
											</div>
											
										</header>
										<div class="clear"></div>
										<div class="panel-body">
											<div id="flot-1ine" style="height:210px"></div>
										</div>
										<footer class="text-center">Minggu ke-<br/><br/></footer>
										
										<footer class="panel-footer bg-white no-padder">
											<div class="row text-center no-gutter">
												<?php 
													$date=date('Y-m');	
													$filter=date($_POST['filter']); 
													if($_POST['filter']){ 
														$elemen = explode("-",$filter); 
													}else{
														$elemen = explode("-",$date);											
													}
													$month = $elemen[1];
													$year = $elemen[0];
													$timestamp = mktime(0, 0, 0, $month, 1);
													$monthName = date('M', $timestamp );												
												?>
												<div class="col-xs-2 b-r b-light bg-primary"> <span class="h4 font-bold m-t block text-white "><?php echo $monthName; ?></span>  <small class="text-white m-b block"><?php echo $year; ?></small> 
												</div>
												<div class="col-xs-2 b-r b-light"> <span class="h4 font-bold m-t block"><?php echo $total_total_clients_weekly[1]; ?></span>  <small class="text-muted m-b block">Minggu 1</small> 
												</div>
												<div class="col-xs-2 b-r b-light"> <span class="h4 font-bold m-t block"><?php echo $total_total_clients_weekly[2]; ?></span>  <small class="text-muted m-b block">Minggu 2</small> 
												</div>
												<div class="col-xs-2 b-r b-light"> <span class="h4 font-bold m-t block"><?php echo $total_total_clients_weekly[3]; ?></span>  <small class="text-muted m-b block">Minggu 3</small> 
												</div>
												<div class="col-xs-2 b-r b-light"> <span class="h4 font-bold m-t block"><?php echo $total_total_clients_weekly[4]; ?></span>  <small class="text-muted m-b block">Minggu 4</small> 
												</div>
												<div class="col-xs-2"> <span class="h4 font-bold m-t block"><?php echo $total_total_clients_weekly[5]; ?></span>  <small class="text-muted m-b block">Minggu 5</small> 
												</div>
											</div>
										</footer>
										
									</section>
									
									<!-- GRAFIK Anggota 
									<section class="panel panel-default">
										<header class="panel-heading font-bold">Clients Grow Chart</header>
										<div class="bg-light dk wrapper"> 
											<div class="text-center m-b-n m-t-sm">
												<div class="sparkline" data-type="line" data-height="100" data-width="100%" data-line-width="2" data-line-color="#999" data-spot-color="#bbbbbb" data-fill-color="" data-highlight-line-color="#fff" data-spot-radius="4" data-resize="true" values="<?php echo $total_clients_weekly[3].",".$total_clients_weekly[2].",".$total_clients_weekly[1]; ?>">
												</div>
												
											</div>
										</div>
										<div class="panel-body">
											<div> <span class="text-muted">Total Anggota:</span>  <span class="h3 block"><?php echo $total_anggota; ?></span> 
											</div>
											<div class="line pull-in"></div>
											<div class="row m-t-sm">
												<div class="col-xs-4"> <small class="text-muted block">Pengajuan</small>  <span>$600.00</span> </div>
												<div class="col-xs-4"> <small class="text-muted block">Pencairan</small>  <span>$400.00</span> </div>
												<div class="col-xs-4"> <small class="text-muted block">Aktif</small>  <span>$400.00</span> </div>
											</div>
										</div>
									</section>
									-->
									<!-- TABEL Anggota -->
									<section class="panel panel-default">
										<header class="panel-heading font-bold">Portfolio Anggota</header>
										<div class="panel-body">
											<div>
												<table class="table table-striped m-b-none text-sm">   
													<tr><td>Anggota Aktif Pembiayaan</td>	<td width="200px" align="right"><?php echo $total_anggota_aktif_pembiayaan; ?></td></tr>
													<tr><td>Anggota Aktif Menabung</td>		<td width="200px" align="right"><?php echo $total_anggota; ?></td></tr>
													<tr><td>Anggota Keluar</td>				<td width="200px" align="right"><?php echo $total_anggota_keluar; ?></td></tr>
													<tr><td>Monitoring Pembiayaan</td>		<td width="200px" align="right"><?php echo $total_monitoring; ?></td></tr>
												</table>
											</div>
										</div>
									</section>
									<!-- TABEL Sektor Pembiayaan -->
									<section class="panel panel-default">
										<header class="panel-heading font-bold">Sektor Pembiayaan</header>
										<div class="panel-body">
											<div>
												<table class="table table-striped m-b-none text-sm">   
													<tr><td>Perdagangan</td><td width="120px" align="right"><?php echo $total_sektor_pembiayaan[1]; ?></td>	<td width="120px" align="right"><?php echo number_format($total_sektor_pembiayaan_persen[1],2); ?> %</td></tr>
													<tr><td>Pertanian</td>	<td width="120px" align="right"><?php echo $total_sektor_pembiayaan[2]; ?></td>	<td width="120px" align="right"><?php echo number_format($total_sektor_pembiayaan_persen[2],2); ?> %</td></tr>
													<tr><td>Industri Rumah Tangga</td>	<td width="120px" align="right"><?php echo $total_sektor_pembiayaan[3]; ?></td>	<td width="120px" align="right"><?php echo number_format($total_sektor_pembiayaan_persen[3],2); ?> %</td></tr>
													<tr><td>Jasa</td>	<td width="120px" align="right"><?php echo $total_sektor_pembiayaan[4]; ?></td>	<td width="120px" align="right"><?php echo number_format($total_sektor_pembiayaan_persen[4],2); ?> %</td></tr>
													<tr><td>Perumahan</td><td width="120px" align="right"><?php echo $total_sektor_pembiayaan[5]; ?></td>	<td width="120px" align="right"><?php echo number_format($total_sektor_pembiayaan_persen[5],2); ?> %</td></tr>
													<tr><td>Air dan Sanitasi</td>	<td width="120px" align="right"><?php echo $total_sektor_pembiayaan[6]; ?></td>	<td width="120px" align="right"><?php echo number_format($total_sektor_pembiayaan_persen[6],2); ?> %</td></tr>
													<tr><td>Pendidikan</td>	<td width="120px" align="right"><?php echo $total_sektor_pembiayaan[7]; ?></td>	<td width="120px" align="right"><?php echo number_format($total_sektor_pembiayaan_persen[7],2); ?> %</td></tr>
													<tr><td>Kesehatan</td>	<td width="120px" align="right"><?php echo $total_sektor_pembiayaan[8]; ?></td>	<td width="120px" align="right"><?php echo number_format($total_sektor_pembiayaan_persen[8],2); ?> %</td></tr>
													<tr><td>Lainnya</td>	<td width="120px" align="right"><?php echo $total_sektor_pembiayaan[9]; ?></td>	<td width="120px" align="right"><?php echo number_format($total_sektor_pembiayaan_persen[9],2); ?> %</td></tr>
												</table>
											</div>
										</div>
									</section>
									
								</div>
								
								<!-- SECTION RIGHT -->
								<div class="col-md-6">
									<!-- GRAFIK KEHADIRAN -->
									<section class="hidden  panel panel-default">
										<header class="panel-heading font-bold">
											&nbsp;
											<div class="pull-left">Grafik Kehadiran Anggota (%)</div>
											<div class="pull-right">
												<form method="post" action="<?php echo site_url(); ?>/dashboard/">
												<select name="filter" onchange="submit()">
													<option value="">Filter</option>
													<option value="2014-12" >Dec 2014</option>
													<option value="2014-11" >Nov 2014</option>
													<option value="2014-10" >Oct 2014</option>
													<option value="2014-09" >Sept 2014</option>
													<option value="2014-08" >Aug 2014</option>
													<option value="2014-07" >July 2014</option>
													<option value="2014-06" >June 2014</option>
													<option value="2014-05" >May 2014</option>
												</select>
												</form>
											</div>
											
										</header>
										<div class="clear"></div>
										<div class="panel-body">
											<div id="flot-1ine-2" style="height:210px"></div>
										</div>
										<footer class="text-center">Minggu ke-<br/><br/></footer>
										
										<footer class="panel-footer bg-white no-padder">
											<div class="row text-center no-gutter">
												<?php 
													$date=date('Y-m');	
													$filter=date($_POST['filter']); 
													if($_POST['filter']){ 
														$elemen = explode("-",$filter); 
													}else{
														$elemen = explode("-",$date);											
													}
													$month = $elemen[1];
													$year = $elemen[0];
													$timestamp = mktime(0, 0, 0, $month, 1);
													$monthName = date('M', $timestamp );												
												?>
												<div class="col-xs-2 b-r b-light bg-primary"> <span class="h4 font-bold m-t block text-white "><?php echo $monthName; ?></span>  <small class="text-white m-b block"><?php echo $year; ?></small> 
												</div>
												<div class="col-xs-2 b-r b-light"> <span class="h4 font-bold m-t block"><?php echo $total_kehadiran_h[1]; ?></span>  <small class="text-muted m-b block">Minggu 1</small> 
												</div>
												<div class="col-xs-2 b-r b-light"> <span class="h4 font-bold m-t block"><?php echo $total_kehadiran_h[2]; ?></span>  <small class="text-muted m-b block">Minggu 2</small> 
												</div>
												<div class="col-xs-2 b-r b-light"> <span class="h4 font-bold m-t block"><?php echo $total_kehadiran_h[3]; ?></span>  <small class="text-muted m-b block">Minggu 3</small> 
												</div>
												<div class="col-xs-2 b-r b-light"> <span class="h4 font-bold m-t block"><?php echo $total_kehadiran_h[4]; ?></span>  <small class="text-muted m-b block">Minggu 4</small> 
												</div>
												<div class="col-xs-2"> <span class="h4 font-bold m-t block"><?php echo $total_kehadiran_h[5]; ?></span>  <small class="text-muted m-b block">Minggu 5</small> 
												</div>
											</div>
										</footer>
										
									</section>
									
									<!-- TABEL P.A.R -->
									<section class="panel panel-default">
										<header class="panel-heading font-bold">Portfolio At Risk</header>
										<div class="panel-body">
											<div>
												<b>Data Kelancaran Pembiayaan</b>
												<table class="table table-striped m-b-none text-sm">   
													<tr><td>Lancar</td><td width="120px" align="right"><b><?php echo $total_par[0];?></b></td><td width="120px" align="right"></td></tr>
													<tr><td>1 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/1"><u><?php echo $total_par[1];?></u></a></td><td width="120px" align="right"><?php echo number_format($par_sisaangsuran[1]);?></td></tr>
													<tr><td>2 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/2"><u><?php echo $total_par[2];?></u></a></td><td width="120px" align="right"><?php echo number_format($par_sisaangsuran[2]);?></td></tr>
													<tr><td>3 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/3"><u><?php echo $total_par[3];?></u></a></td><td width="120px" align="right"><?php echo number_format($par_sisaangsuran[3]);?></td></tr>
													<tr><td>4 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/4"><u><?php echo $total_par[4];?></u></a></td><td width="120px" align="right"><?php echo number_format($par_sisaangsuran[4]);?></td></tr>
													<tr><td>5 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/5"><u><?php echo $total_par[5];?></u></a></td><td width="120px" align="right"><?php echo number_format($par_sisaangsuran[5]);?></td></tr>
													<tr><td>6 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/6"><u><?php echo $total_par[6];?></u></a></td><td width="120px" align="right"><?php echo number_format($par_sisaangsuran[6]);?></td></tr>
													<tr><td>7 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/7"><u><?php echo $total_par[7];?></u></a></td><td width="120px" align="right"><?php echo number_format($par_sisaangsuran[7]);?></td></tr>
													<tr><td>8 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/8"><u><?php echo $total_par[8];?></u></a></td><td width="120px" align="right"><?php echo number_format($par_sisaangsuran[8]);?></td></tr>
													<tr><td>9 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/9"><u><?php echo $total_par[9];?></u></a></td><td width="120px" align="right"><?php echo number_format($par_sisaangsuran[9]);?></td></tr>
													<tr><td>12 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/12"><u><?php echo ($total_par[10]+$total_par[11]+$total_par[12]);?></u></a></td><td width="120px" align="right"><?php echo number_format(($par_sisaangsuran[10] + $par_sisaangsuran[11] + $par_sisaangsuran[12]));?></td></tr>
													<tr><td>> 12 Minggu</td><td width="120px" align="right"><a href="<?php echo site_url(); ?>/pembiayaan/par_filter/13"><u><?php echo $total_par[13];?></u></a></td><td width="120px" align="right"><?php echo number_format($par_sisaangsuran[13]);?></td></tr>
												</table>
											</div>
										</div>
									</section>
									
									<!-- TABEL Pembiayaan -->
									<section class="panel panel-default">
										<header class="panel-heading font-bold">Portfolio Pembiayaan</header>
										<div class="panel-body">
											<div>
												<table class="table table-striped m-b-none text-sm">   
													<tr><td>1<superscript>st</superscript> Pembiayaan</td><td width="200px" align="right"><?php echo $total_pembiayaan_aktif_ke_1; ?></td></tr>
													<tr><td>2<superscript>nd</superscript> Pembiayaan</td><td width="200px" align="right"><?php echo $total_pembiayaan_aktif_ke_2; ?></td></tr>
													<tr><td>3<superscript>rd</superscript> Pembiayaan</td><td width="200px" align="right"><?php echo $total_pembiayaan_aktif_ke_3; ?></td></tr>
													<tr><td>4<superscript>th</superscript> Pembiayaan</td><td width="200px" align="right"><?php echo $total_pembiayaan_aktif_ke_4; ?></td></tr>
													<tr><td>5<superscript>th</superscript> Pembiayaan</td><td width="200px" align="right"><?php echo $total_pembiayaan_aktif_ke_5; ?></td></tr>
												</table>
											</div>
										</div>
									</section>
								</div>
								
								
							</div>
<script>
$(function(){

  // 
  var d1 = [];
  var d2 = [];
  //for (var i = 0; i <= 11; i += 1) {
  //  d1.push([i,i]);
  //}
   d1.push([1,<?php echo $total_clients_weekly[1]; ?>]);
   d1.push([2,<?php echo $total_clients_weekly[2]; ?>]);
   d1.push([3,<?php echo $total_clients_weekly[3]; ?>]);
   d1.push([4,<?php echo $total_clients_weekly[4]; ?>]);
   d1.push([5,<?php echo $total_clients_weekly[5]; ?>]);
   
   d2.push([1,<?php echo $total_kehadiran_h_persen[1]; ?>]);
   d2.push([2,<?php echo $total_kehadiran_h_persen[2]; ?>]);
   d2.push([3,<?php echo $total_kehadiran_h_persen[3]; ?>]);
   d2.push([4,<?php echo $total_kehadiran_h_persen[4]; ?>]);
   d2.push([5,<?php echo $total_kehadiran_h_persen[5]; ?>]);
   
  $("#flot-1ine").length && $.plot($("#flot-1ine"), [{
          data: d1
      }], 
      {
        series: {
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.0
                    }, {
                        opacity: 0.2
                    }]
                }
            },
            points: {
                radius: 5,
                show: true
            },
            grow: {
              active: true,
              steps: 50
            },
            shadowSize: 2
        },
        grid: {
            hoverable: true,
            clickable: true,
            tickColor: "#f0f0f0",
            borderWidth: 1,
            color: '#f0f0f0'
        },
        colors: ["#65bd77"],
        xaxis:{
			min:1,
			max:5,
			ticks: 5,
			tickDecimals:0
        },
        yaxis: {
			ticks: 5,
			tickDecimals:0
        },
        tooltip: true,
        tooltipOpts: {
          content: "Minggu: %x : %y",
          defaultTheme: false,
          shifts: {
            x: 0,
            y: 20
          }
        }
      }
  );
  
  $("#flot-1ine-2").length && $.plot($("#flot-1ine-2"), [{
          data: d2
      }], 
      {
        series: {
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.0
                    }, {
                        opacity: 0.2
                    }]
                }
            },
            points: {
                radius: 5,
                show: true
            },
            grow: {
              active: true,
              steps: 50
            },
            shadowSize: 2
        },
        grid: {
            hoverable: true,
            clickable: true,
            tickColor: "#f0f0f0",
            borderWidth: 1,
            color: '#f0f0f0'
        },
        colors: ["#65bd77"],
        xaxis:{
			min:1,
			max:5,
			ticks: 5,
			tickDecimals:0
        },
        yaxis: {
			min:0,
			max:100,
			ticks: 5,
			tickDecimals:0
        },
        tooltip: true,
        tooltipOpts: {
          content: "Minggu: %x : %y",
          defaultTheme: false,
          shifts: {
            x: 0,
            y: 20
          }
        }
      }
  );

});
</script>