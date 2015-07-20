
							<ul class="breadcrumb no-border no-radius b-b b-light pull-in">
								<li><a href=""><i class="fa fa-home"></i> Home</a></li>
								<li class="active">Operational Report</li>
							</ul>
							<div class="m-b-md">
								<h3 class="m-b-none">Operational Report</h3>  <small>Welcome back, <?php echo $this->session->userdata('user_fullname');?></small> 
							</div>
							<div class="row">
								<div class="cold-md-12">
									<div class="col-sm-6 m-b-xs text-right">
									</div>
									<div class="col-sm-6 m-b-xs text-right">
										<form method="post" action="">
										<input type="hidden" name="filter" value="1">
										<input type="hidden" name="branch" value="<?php echo $this->uri->segment(4); ?>">
										<input type="text" name="startdate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="Start Date">
										<input type="text" name="enddate" class="datepicker-input inp90" data-date-format="yyyy-mm-dd" placeholder="End Date">
										<button type="submit" name="submit" class="btn btn-xs btn-info">Filter</button> 
										</form>
									</div>
								</div>
							</div>
							<div class="row">
								<!-- SECTION RIGHT -->
								<div class="col-md-12">
									<!-- TABEL P.A.R -->
									<section class="panel panel-default">
										<header class="panel-heading font-bold">OPERATIONAL REVIEW REPORT <span style="color: red;"><?php echo strtoupper($branch_name); ?></span> PER <span style="color: blue;"><?php echo strtoupper($date_awal).' TO '.strtoupper($date_akhir); ?></span></header>
										<div class="panel-body">
											<div>
												
												<table class="table table-striped m-b-none text-sm"> 
													<tr>
														<td width="10px"></td>
														<td width="" align=""></td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td width="102px" align="right">';
															  echo '<b>'.strtoupper($officer_list[$i]['officer_name']).'</b></a></td>'; ?>
														<?php } ?>
														<td width="102px" align="right"><b> ALL</b></td>
													</tr>
													<tr>
														<td><b>1</b></td>
														<td colspan="7"><b>ANGGOTA</b></td>
													</tr>												
													<tr><!-- echo $officer_list[0]['officer_name']; -->
														<td></td>
														<td>a. Anggota Awal</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$officer_list[$i]['no_clients_awal'].'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. Anggota Akhir</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$officer_list[$i]['no_clients_akhir'].'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Mutasi</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right"></td>' ?>
														<?php } ?>
														<td align="right"><b><?php //echo array_sum($officer_list[]['no_clients_akhir'])-array_sum($officer_list[]['no_clients_awal']); ?></b></td>
													</tr>
													<tr>
														<td><b>2</b></td>
														<td colspan="7"><b>MAJELIS</b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. Majelis Awal</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$officer_list[$i]['no_majelis_awal'].'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. Majelis Akhir</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$officer_list[$i]['no_majelis_akhir'].'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Mutasi</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right"></td>' ?>
														<?php } ?>
														<td align="right"><b><?php //echo array_sum($officer_list[]['no_clients_akhir'])-array_sum($officer_list[]['no_clients_awal']; ?></b></td>
													</tr>
													<tr>
														<td><b>3</b></td>
														<td colspan="4"><b>OUTSTANDING PINJAMAN</b></td>
														<td colspan="2"><b><?php echo "Rp ".number_format(array_sum($total_outstanding_pinjaman_per_cabang_akhir)); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. OS Awal</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.number_format($total_outstanding_pinjaman_per_cabang_awal[$i]).'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo number_format(array_sum($total_outstanding_pinjaman_per_cabang_awal)); ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. OS Akhir</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.number_format($total_outstanding_pinjaman_per_cabang_akhir[$i]).'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo number_format(array_sum($total_outstanding_pinjaman_per_cabang_akhir)); ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Mutasi</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right"></td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo "Rp ".number_format(array_sum($total_outstanding_pinjaman_per_cabang_akhir)-array_sum($total_outstanding_pinjaman_per_cabang_awal)); ?></b></td>
													</tr>
													<tr>
														<td><b>4</b></td>
														<td colspan="4"><b>OUTSTANDING TABUNGAN SUKARELA</b></td>
														<td colspan="2"><b><?php echo "Rp ".number_format($total_saldo_tabsukarela_akhir); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. OS Awal</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.number_format($total_saldo_tabsukarela_per_cabang_awal[$i]).'</td>' ?>
														<?php } ?>
														<td align="right">
															<b><?php echo number_format(array_sum($total_saldo_tabsukarela_per_cabang_awal));
																	 //$total_saldo_tabsukarela_per_lastmonth; ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. OS Akhir</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.number_format($total_saldo_tabsukarela_per_cabang_akhir[$i]).'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo number_format(array_sum($total_saldo_tabsukarela_per_cabang_akhir));
																						//$total_saldo_tabsukarela); ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Mutasi</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right"></td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo number_format(array_sum($total_saldo_tabsukarela_per_cabang_akhir)-array_sum($total_saldo_tabsukarela_per_cabang_awal))
																				   //$total_saldo_tabsukarela-$total_saldo_tabsukarela_per_lastmonth ?></b></td>
													</tr>												
													<tr>
														<td><b>5</b></td>
														<td colspan="4"><b>OUTSTANDING TABUNGAN BERJANGKA</b></td>
														<td colspan="2"><b><?php echo "Rp ".number_format($total_saldo_tabberjangka_akhir); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. OS Awal</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.number_format($total_saldo_tabberjangka_per_cabang_awal[$i]).'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo number_format(array_sum($total_saldo_tabberjangka_per_cabang_awal)); 
																						//$total_saldo_tabberjangka_per_lastmonth) ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. OS Akhir</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.number_format($total_saldo_tabberjangka_per_cabang_akhir[$i]).'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo number_format(array_sum($total_saldo_tabberjangka_per_cabang_akhir));
																						//$total_saldo_tabberjangka ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Mutasi</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right"></td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo number_format(array_sum($total_saldo_tabberjangka_per_cabang_akhir)-array_sum($total_saldo_tabberjangka_per_cabang_awal));
																						//$total_saldo_tabberjangka-$total_saldo_tabberjangka_per_lastmonth ?></b></td>
													</tr>
													<tr>
														<td><b>6</b></td>
														<td colspan="4"><b>OUTSTANDING TABUNGAN WAJIB</b></td>
														<td colspan="2"><b><?php echo "Rp ".number_format($total_saldo_tabwajib_akhir); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. OS Awal</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.number_format($total_saldo_tabwajib_per_cabang_awal[$i]).'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo number_format(array_sum($total_saldo_tabwajib_per_cabang_awal));
																						//$total_saldo_tabwajib_per_lastmonth ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. OS Akhir</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.number_format($total_saldo_tabwajib_per_cabang_akhir[$i]).'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo number_format(array_sum($total_saldo_tabwajib_per_cabang_akhir));
																						//$total_saldo_tabwajib); ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Mutasi</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right"></td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo number_format(array_sum($total_saldo_tabwajib_per_cabang_akhir)-array_sum($total_saldo_tabwajib_per_cabang_awal));
																						//$total_saldo_tabwajib-$total_saldo_tabwajib_per_lastmonth); ?></b></td>
													</tr>	
													<tr>
														<td><b>7</b></td>
														<td colspan="4"><b>RATA-RATA PINJAMAN</b></td>
														<td colspan="2"><b><?php //echo "Rp ".number_format($total_saldo_tabwajib); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. Rerata OS Awal</td>
														<?php $akumulasi_rerata_os_awal = 0;?>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.number_format($total_outstanding_pinjaman_per_cabang_awal[$i]/$total_anggota_per_cabang_awal[$i]).'</td>' ?>
														<?php $akumulasi_rerata_os_awal = $akumulasi_rerata_os_awal + ($total_outstanding_pinjaman_per_cabang_awal[$i]/$total_anggota_per_cabang_awal[$i]); ?>
														<?php } ?>
														<td align="right"><b><?php echo //number_format($akumulasi_rerata_os_awal); 
																						number_format(array_sum($total_outstanding_pinjaman_per_cabang_awal)/array_sum($total_anggota_per_cabang_awal));
																						//$total_saldo_tabwajib_per_lastmonth ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. Rerata OS Akhir</td>
														<?php $akumulasi_rerata_os_akhir = 0;?>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.number_format($total_outstanding_pinjaman_per_cabang_akhir[$i]/$total_anggota_per_cabang_akhir[$i]).'</td>' ?>
														<?php $akumulasi_rerata_os_akhir = $akumulasi_rerata_os_akhir + ($total_outstanding_pinjaman_per_cabang_akhir[$i]/$total_anggota_per_cabang_akhir[$i]); ?>
														<?php } ?>
														<td align="right"><b><?php echo //number_format($akumulasi_rerata_os_akhir);
																						number_format(array_sum($total_outstanding_pinjaman_per_cabang_akhir)/array_sum($total_anggota_per_cabang_akhir));
																						//$total_saldo_tabwajib); ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Mutasi</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right"></td>' ?>
														<?php } ?>
														<td align="right"><b><?php 		$awal = array_sum($total_outstanding_pinjaman_per_cabang_awal)/array_sum($total_anggota_per_cabang_awal);
																						$akhir = array_sum($total_outstanding_pinjaman_per_cabang_akhir)/array_sum($total_anggota_per_cabang_akhir);
																						echo //number_format($akumulasi_rerata_os_akhir-$akumulasi_rerata_os_awal);
																						'Rp '.number_format($akhir-$awal);
																						//$total_saldo_tabwajib-$total_saldo_tabwajib_per_lastmonth; ?></b></td>
													</tr>
													<tr>
														<td><b>8</b></td>
														<td colspan="4"><b>PENCAIRAN</b></td>
														<td colspan="2"><b><?php //echo "Rp ".number_format($total_saldo_tabwajib); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. Target Pencairan</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. Realisasi Pencairan</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>c. Pencapaian Pencairan(%)</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>
													<tr>
														<td><b>9</b></td>
														<td colspan="4"><b>KOLEKTABILITAS PINJAMAN</b></td>
														<td colspan="2"><b><?php //echo "Rp ".number_format($total_saldo_tabwajib); ?></b></td>
													</tr>												
													<tr>
														<td>NASABAH</td>
														<td>Minggu 1</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>	
													<tr>
														<td></td>
														<td>Minggu 2</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>	
													<tr>
														<td></td>
														<td>Minggu 3</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Minggu > 3</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>									
													<tr>
														<td>OUTSTANDING</td>
														<td>Minggu 1</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Minggu 2</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Minggu 3</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>Minggu > 3</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$i.'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo ''; ?></b></td>
													</tr>
													<tr>
														<td><b>10</b></td>
														<td colspan="4"><b>RASIO FO</b></td>
														<td colspan="2"><b><?php //echo "Rp ".number_format($total_saldo_tabwajib); ?></b></td>
													</tr>												
													<tr>
														<td></td>
														<td>a. Jumlah FO</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.$total_officer_per_cabang[$i].'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo array_sum($total_officer_per_cabang); ?></b></td>
													</tr>											
													<tr>
														<td></td>
														<td>b. Majelis per FO </td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.round($total_majelis_per_cabang_akhir[$i]/$total_officer_per_cabang[$i]).'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo round(array_sum($total_majelis_per_cabang_akhir)/array_sum($total_officer_per_cabang)); ?></b></td>
													</tr>
													<tr>
														<td></td>
														<td>c. Anggota per FO</td>
														<?php for($i=0; $i<count($officer_list); $i++) { ?>
														<?php echo '<td align="right">'.round($total_anggota_per_cabang_akhir[$i]/$total_officer_per_cabang[$i]).'</td>' ?>
														<?php } ?>
														<td align="right"><b><?php echo round(array_sum($total_anggota_per_cabang_akhir)/array_sum($total_officer_per_cabang)); ?></b></td>
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