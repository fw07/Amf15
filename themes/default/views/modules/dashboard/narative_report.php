
							<ul class="breadcrumb no-border no-radius b-b b-light pull-in">
								<li><a href=""><i class="fa fa-home"></i> Home</a></li>
								<li class="active">Narative Report</li>
							</ul>
							<div class="m-b-md">
								<h3 class="m-b-none">Narative Report</h3>  <small>Welcome back, <?php echo $this->session->userdata('user_fullname');?></small> 
							</div>
							<section class="panel panel-default">
								<div class="row m-l-none m-r-none bg-light lter">
									<div class="col-sm-6 col-md-3 padder-v b-r b-light"> <span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-info"></i> <i class="fa fa-male fa-stack-1x text-white"></i> </span> 
										<a class="clear" href="#"> <span class="h3 block m-t-xs"><strong><?php echo $total_anggota_all; ?></strong></span>  <small class="text-muted text-uc">ANGGOTA</small> 
										</a>
									</div>
									<div class="col-sm-6 col-md-3 padder-v b-r b-light lt"> <span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-warning"></i> <i class="fa fa-group fa-stack-1x text-white"></i> </span>
										<a
										class="clear" href="#"> <span class="h3 block m-t-xs"><strong id="bugs"><?php echo $total_majelis_all; ?></strong></span>  <small class="text-muted text-uc">MAJELIS</small> 
											</a>
									</div>
									<div class="col-sm-6 col-md-3 padder-v b-r b-light"> <span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-danger"></i> <i class="fa fa-building-o fa-stack-1x text-white"></i> <!--<span class="easypiechart pos-abt" data-percent="100" data-line-width="4" data-track-Color="#f5f5f5" data-scale-Color="false" data-size="50" data-line-cap='butt' data-animate="3000" data-target="#firers" data-update="5000"></span>--> </span>
										<a
										class="clear" href="#"> <span class="h3 block m-t-xs"><strong id="firers"><?php echo "5"; ?></strong></span>  <small class="text-muted text-uc">CABANG</small> 
											</a>
									</div>
									<div class="col-sm-6 col-md-3 padder-v b-r b-light lt"> <span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x icon-muted"></i> <i class="fa fa-briefcase fa-stack-1x text-white"></i> </span> 
										<a class="clear" href="#"> <span class="h3 block m-t-xs"><strong><?php echo $total_tpl_all; ?></strong></span>  <small class="text-muted text-uc">PENDAMPING</small> 
										</a>
									</div>
								</div>
							</section>
							<div class="row">
								
								
								<!-- SECTION RIGHT -->
								<div class="col-md-12">
									<!-- TABEL P.A.R -->
									<section class="panel panel-default">
										<header class="panel-heading font-bold">NARATIVE REPORT</header>
										<div class="panel-body">
											<div>
												
												<table class="table table-striped m-b-none text-sm"> 
													<tr>
														<td width="10px"></td>
														<td width="" align=""></td>
														<td width="120px" align="right"><b>CISEENG</b></td>
														<td width="120px" align="right"><b>BOJONG</b></td>
														<td width="120px" align="right"><b>KEMANG</b></td>
														<td width="120px" align="right"><b>JASINGA</b></td>
														<td width="120px" align="right"><b>TENJO</b></td>
														<td width="120px" align="right"><b>ALL</b></td>
													</tr>
													<tr>
														<td><b>1</b></td>
														<td colspan="7"><b>ANGGOTA</b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. Anggota Baru</td>
														<td align="right"><?php echo $total_anggota[1]; ?></td>
														<td align="right"><?php echo $total_anggota[2]; ?></td>
														<td align="right"><?php echo $total_anggota[3]; ?></td>
														<td align="right"><?php echo $total_anggota[4]; ?></td>
														<td align="right"><?php echo $total_anggota[5]; ?></td>
														<td align="right"><b><?php echo $total_anggota_all; ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. Anggota Keluar</td>
														<td align="right"><?php echo $total_anggota_keluar[1]; ?></td>
														<td align="right"><?php echo $total_anggota_keluar[2]; ?></td>
														<td align="right"><?php echo $total_anggota_keluar[3]; ?></td>
														<td align="right"><?php echo $total_anggota_keluar[4]; ?></td>
														<td align="right"><?php echo $total_anggota_keluar[5]; ?></td>
														<td align="right"><b><?php echo $total_anggota_keluar_all; ?></b></td>
													</tr>
													<tr>
														<td><b>2</b></td>
														<td colspan="7"><b>MAJELIS</b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. Majelis Baru</td>
														<td align="right"><?php echo $total_majelis[1]; ?></td>
														<td align="right"><?php echo $total_majelis[2]; ?></td>
														<td align="right"><?php echo $total_majelis[3]; ?></td>
														<td align="right"><?php echo $total_majelis[4]; ?></td>
														<td align="right"><?php echo $total_majelis[5]; ?></td>
														<td align="right"><b><?php echo $total_majelis_all; ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. Tambal Sulam</td>
														<td align="right">0</td>
														<td align="right">0</td>
														<td align="right">0</td>
														<td align="right">0</td>
														<td align="right">0</td>
														<td align="right"><b>0</b></td>
													</tr>										
													<tr>
														<td></td>
														<td>c. Ditutup</td>
														<td align="right">0</td>
														<td align="right">0</td>
														<td align="right">0</td>
														<td align="right">0</td>
														<td align="right">0</td>
														<td align="right"><b>0</b></td>
													</tr>
													<tr>
														<td><b>3</b></td>
														<td colspan="7"><b>PEMBIAYAAN</b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. Pembiayaan ke 1</td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_1[1]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_1[2]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_1[3]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_1[4]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_1[5]; ?></td>
														<td align="right"><b><?php echo ($total_pembiayaan_aktif_ke_1[1]+$total_pembiayaan_aktif_ke_1[2]+$total_pembiayaan_aktif_ke_1[3]+$total_pembiayaan_aktif_ke_1[4]+$total_pembiayaan_aktif_ke_1[5]); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>b. Pembiayaan ke 2</td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_2[1]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_2[2]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_2[3]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_2[4]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_2[5]; ?></td>
														<td align="right"><b><?php echo ($total_pembiayaan_aktif_ke_2[1] + $total_pembiayaan_aktif_ke_2[2] + $total_pembiayaan_aktif_ke_2[3] + $total_pembiayaan_aktif_ke_2[4] + $total_pembiayaan_aktif_ke_2[5]); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>c. Pembiayaan ke 3</td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_3[1]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_3[2]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_3[3]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_3[4]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_3[5]; ?></td>
														<td align="right"><b><?php echo ($total_pembiayaan_aktif_ke_3[1] + $total_pembiayaan_aktif_ke_3[2] + $total_pembiayaan_aktif_ke_3[3] + $total_pembiayaan_aktif_ke_3[4] + $total_pembiayaan_aktif_ke_3[5]); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>d. Pembiayaan ke 4</td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_4[1]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_4[2]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_4[3]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_4[4]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_4[5]; ?></td>
														<td align="right"><b><?php echo ($total_pembiayaan_aktif_ke_4[1] + $total_pembiayaan_aktif_ke_4[2] + $total_pembiayaan_aktif_ke_4[3] + $total_pembiayaan_aktif_ke_4[4] + $total_pembiayaan_aktif_ke_4[5]); ?></b></td>
													</tr>	
													
													<tr>
														<td></td>
														<td>e. Pembiayaan ke 5</td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_5[1]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_5[2]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_5[3]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_5[4]; ?></td>
														<td align="right"><?php echo $total_pembiayaan_aktif_ke_5[5]; ?></td>
														<td align="right"><b><?php echo ($total_pembiayaan_aktif_ke_5[1] + $total_pembiayaan_aktif_ke_5[2] + $total_pembiayaan_aktif_ke_5[3] + $total_pembiayaan_aktif_ke_5[4] + $total_pembiayaan_aktif_ke_5[5]); ?></b></td>
													</tr>
													<tr>
														<td><b>4</b></td>
														<td colspan="7"><b>PLAFOND AKTIF</b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. Pembiayaan ke 1</td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_1[1]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_1[2]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_1[3]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_1[4]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_1[5]); ?></td>
														<td align="right"><b><?php echo number_format($sum_pembiayaan_aktif_1[1] + $sum_pembiayaan_aktif_1[2] + $sum_pembiayaan_aktif_1[3] + $sum_pembiayaan_aktif_1[4] + $sum_pembiayaan_aktif_1[5]); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>b. Pembiayaan ke 2</td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_2[1]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_2[2]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_2[3]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_2[4]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_2[5]); ?></td>
														<td align="right"><b><?php echo number_format($sum_pembiayaan_aktif_2[1] + $sum_pembiayaan_aktif_2[2] + $sum_pembiayaan_aktif_2[3] + $sum_pembiayaan_aktif_2[4] + $sum_pembiayaan_aktif_2[5]); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>c. Pembiayaan ke 3</td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_3[1]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_3[2]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_3[3]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_3[4]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_3[5]); ?></td>
														<td align="right"><b><?php echo number_format($sum_pembiayaan_aktif_3[1] + $sum_pembiayaan_aktif_3[2] + $sum_pembiayaan_aktif_3[3] + $sum_pembiayaan_aktif_3[4] + $sum_pembiayaan_aktif_3[5]); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>d. Pembiayaan ke 4</td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_4[1]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_4[2]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_4[3]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_4[4]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_4[5]); ?></td>
														<td align="right"><b><?php echo number_format($sum_pembiayaan_aktif_4[1] + $sum_pembiayaan_aktif_4[2] + $sum_pembiayaan_aktif_4[3] + $sum_pembiayaan_aktif_4[4] + $sum_pembiayaan_aktif_4[5]); ?></b></td>
													</tr>													
													<tr>
														<td></td>
														<td>e. Pembiayaan ke 5</td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_5[1]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_5[2]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_5[3]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_5[4]); ?></td>
														<td align="right"><?php echo number_format($sum_pembiayaan_aktif_5[5]); ?></td>
														<td align="right"><b><?php echo number_format($sum_pembiayaan_aktif_5[1] + $sum_pembiayaan_aktif_5[2] + $sum_pembiayaan_aktif_5[3] + $sum_pembiayaan_aktif_5[4] + $sum_pembiayaan_aktif_5[5]); ?></b></td>
													</tr>
													
														<td><b>5</b></td>
														<td colspan="7"><b>PROFIT</b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. Pembiayaan ke 1</td>
														<td align="right"><?php echo number_format($sum_margin_1[1]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_1[2]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_1[3]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_1[4]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_1[5]); ?></td>
														<td align="right"><b><?php echo number_format($sum_margin_1[1] + $sum_margin_1[2] + $sum_margin_1[3] + $sum_margin_1[4] + $sum_margin_1[5]); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>b. Pembiayaan ke 2</td>
														<td align="right"><?php echo number_format($sum_margin_2[1]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_2[2]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_2[3]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_2[4]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_2[5]); ?></td>
														<td align="right"><b><?php echo number_format($sum_margin_2[1] + $sum_margin_2[2] + $sum_margin_2[3] + $sum_margin_2[4] + $sum_margin_2[5]); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>c. Pembiayaan ke 3</td>
														<td align="right"><?php echo number_format($sum_margin_3[1]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_3[2]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_3[3]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_3[4]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_3[5]); ?></td>
														<td align="right"><b><?php echo number_format($sum_margin_3[1] + $sum_margin_3[2] + $sum_margin_3[3] + $sum_margin_3[4] + $sum_margin_3[5]); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>d. Pembiayaan ke 4</td>
														<td align="right"><?php echo number_format($sum_margin_4[1]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_4[2]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_4[3]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_4[4]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_4[5]); ?></td>
														<td align="right"><b><?php echo number_format($sum_margin_4[1] + $sum_margin_4[2] + $sum_margin_4[3] + $sum_margin_4[4] + $sum_margin_4[5]); ?></b></td>
													</tr>	
													
													<tr>
														<td></td>
														<td>e. Pembiayaan ke 5</td>
														<td align="right"><?php echo number_format($sum_margin_5[1]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_5[2]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_5[3]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_5[4]); ?></td>
														<td align="right"><?php echo number_format($sum_margin_5[5]); ?></td>
														<td align="right"><b><?php echo number_format($sum_margin_5[1] + $sum_margin_5[2] + $sum_margin_5[3] + $sum_margin_5[4] + $sum_margin_5[5]); ?></b></td>
													</tr>
													<tr>
														<td><b>6</b></td>
														<td colspan="7"><b>PENDAMPING LAPANGAN</b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. Pendamping Lapangan</td>
														<td align="right"><?php echo $total_tpl[1]; ?></td>
														<td align="right"><?php echo $total_tpl[2]; ?></td>
														<td align="right"><?php echo $total_tpl[3]; ?></td>
														<td align="right"><?php echo $total_tpl[4]; ?></td>
														<td align="right"><?php echo $total_tpl[5]; ?></td>
														<td align="right"><b><?php echo ($total_tpl[1] + $total_tpl[2] + $total_tpl[3] + $total_tpl[4] + $total_tpl[5]); ?></b></td>
													</tr>	
												</table>
											</div>
										</div>
									</section>
									
									
								</div>
								
								
							</div>
<script>
$(function(){

  // 
  var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  var d1 = [];
  //for (var i = 0; i <= 11; i += 1) {
  //  d1.push([i,i]);
  //}
   d1.push([1,<?php echo $total_clients_weekly[4]; ?>]);
   d1.push([2,<?php echo $total_clients_weekly[3]; ?>]);
   d1.push([3,<?php echo $total_clients_weekly[2]; ?>]);
   d1.push([4,<?php echo $total_clients_weekly[1]; ?>]);
   
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
        },
        yaxis: {
          ticks: 5
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