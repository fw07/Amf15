<?php

class Ts_download extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Ts_download';
	private $module 	= 'ts_download';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('group_model');
		$this->load->model('clients_model');
		$this->load->model('officer_model');
		$this->load->model('tsdaily_model');
		$this->load->model('transaction_model');
		$this->load->model('saving_model');
		$this->load->model('clients_pembiayaan_model');
		$this->load->model('branch_model');
		$this->load->model('topsheet_model');
		$this->load->model('tabwajib_model');
		$this->load->model('tabsukarela_model');
		$this->load->model('tabberjangka_model');
		$this->load->model('tr_tabwajib_model');
		$this->load->model('tr_tabsukarela_model');
		$this->load->model('tr_tabberjangka_model');
		$this->load->model('jurnal_model');
		$this->load->model('risk_model');
		
		$this->load->library('pagination');	
	}
	
	
	public function daily(){			
		$user_branch = $this->session->userdata('user_branch');
		$branch = $this->branch_model->get_branch($user_branch)->result();	
		$branch_name=$branch[0]->branch_name;			
		$branch = 1;
		//$schedule = $this->group_model->get_schedule($user_branch, "Senin");
		//foreach ($schedule as $day){
			
			$group_id =  $this->uri->segment(4);
			//$group_id =  $day->group_id;
			//Get group details
			$group = $this->group_model->get_group($group_id)->result();	
			$group = $group[0];	
			//Get total client per group
			$total_client = $this->clients_model->count_client_by_group($group_id);	
			
			//Get client detail
			$clients = $this->clients_model->get_pembiayaan_by_group($group_id);
			
			//Count TR per group
			$group_tr = $this->clients_pembiayaan_model->count_tr_by_group($group_id);		
			
			$timestamp=date("Ymdhis");
			$filename="Topsheet_$branch_name_$timestamp";			
			
			$get_last_ts_entry = $this->transaction_model->get_last_tr_date_by_group($group_id)->result();
			$get_last_ts_entry = $get_last_ts_entry[0];
			$date_next_week = date("d / m / Y", strtotime(date("Y-m-d", strtotime($get_last_ts_entry->tr_date)) . " +1 week"));
			$date_next_week_2 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($get_last_ts_entry->tr_date)) . " +1 week"));
			
			$html = "";
			$html .= '<style>
						@page{ margin-top: 0.5cm; margin-bottom: 0.5cm; margin-left: 1cm; margin-right: 1cm;}
						body{ font-family: Helvetica, Arial;font-size: 11px;line-height: 125%;} 
						.tbl{border-collapse: collapse;border: none;font-size: 11px;}
						.tbl thead{border-bottom: 2px solid #000;}
						.tbl td, .tbl th{padding: 2px 3px;border: 1px solid #333;}
						.clear{float: none;clear: both}
						#topsheet{width: 100%;float: none;clear: both;padding-bottom: 20px;}
						.topsheet_head2{width: 20%;float: left;font-size: 11px;}
						.topsheet_head3{width: 50%;float: left;font-size: 11px; text-align: center;}
						.topsheet_head3 h2{font-size: 14pt;}
						.topsheet_head{width: 25%;float: left;font-size: 11px;}
						.topsheet_head td,.topsheet_head2 td{ border: none;font-size: 11px;}
						.tbl tr td.bdr_btm, .tbl tr th.bdr_btm{ border: none; border-left: none; border-right: none;border-bottom: 1px solid #000;}
						.tbl tr td.bdr_leftbtm, .tbl tr th.bdr_leftbtm{ border: none; border-left: 1px solid #000; border-right: none;border-bottom: 1px solid #000;}
						.tbl tr td.bdr_btm_bold, .tbl tr th.bdr_btm_bold{ border: none; border-left: none; border-right: none;border-bottom: 2px solid #000;}
						.tbl tr td.bdr_leftbtm_bold, .tbl tr th.bdr_leftbtm_bold{ border: none; border-left: 1px solid #000;; border-right: none;border-bottom: 2px solid #000;}
						.tbl tr td.nobdr, .tbl tr th.nobdr{border: none;}
						.tbl tr td.border_bold{border: 2px solid #000;}
						.tbl td.padlr{ padding-left: 5px;padding-right: 7px;}
					</style>';
					$html .= "<div style='page-break-after: alwayss;'>";
			//$html .= "<div style=''><img src='http://mis.amartha.com/files/logo_amartha.png' /></div>"; 
			//$html .= "<div style='float:right;position:absolute;right:35px;top:20px;'><small>TS ".$timestamp."</small></div>"; 
			$html .= '<h2 align="center">TOPSHEET</h2>';			
			//$html .= '<div id="topsheet"><div class="topsheet_head2"><table border="0"><tr><td>Area</td><td>: Bogor Barat</td></tr><tr><td>Cabang</td><td>: 101 Ciseeng</td></tr><tr><td>Majelis</td><td>: <b>Melati</b></td></tr></table></div><div class="topsheet_head"><table border="0"><tr><td>Kampung</td><td>: Blok Sukun</td></tr><tr><td>Desa</td><td>: Cibeuntang</td></tr><tr><td>Jumlah Anggota</td><td>: 21</td></tr></table></div><div class="topsheet_head"><table border="0"><tr><td>Pertemuan Ke</td><td>: 32</td></tr><tr><td>Tanggal</td><td>: 01/10/2014</td></tr><tr><td>Ketua</td><td>: Elsah</td></tr></table></div><div class="topsheet_head"><table border="0"><tr><td>Tanggung Renteng</td><td>: Ada / Tidak</td></tr><tr><td>Akumulasi TR</td><td>: 2</td></tr><tr><td>Pendamping</td><td>: Linda</td></tr></table></div><div class="clear"></div></div><table class="tbl" width="100%" cellspacing="0"><thead><tr><th rowspan="2">No</th><th rowspan="2">Rekening</th><th rowspan="2">Nama</th><th colspan="5"><b>Kehadiran</b></th><th colspan="5"><b>Pembiayaan</b></th><th colspan="2"><b>Keterlambatan</b></th><th colspan="3"><b>Tabungan Sukarela</b></th><th colspan="5"><b>Tabungan Berjangka</b></th><th rowspan="2">Ket</th></tr><tr><td align="center">S</td><td align="center">C</td><td align="center">I</td><td align="center">A</td><td align="center">V</td><td align="center">Sisa<br/>Pokok</td><td align="center">Sisa<br/>Profit</td><td align="center">F</td><td align="center">P</td><td align="center">Total<br/>Angsur</td><td align="center">F</td><td align="center">Total<br/>Angsur</td><td align="center">Saldo</td><td align="center">Setor</td><td align="center">Tarik</td><td align="center">V</td><td align="center">P</td><td align="center">Saldo</td><td align="center">Setor</td><td align="center">Tarik</td></tr></thead>';
			/*
			$html .= '<div id="topsheet">';
			$html .= '<div class="topsheet_head2">';
			$html .= '<table border="0"  width="100%" class="nobdr">';
			$html .= '<tr><td  class="nobdr"><img src="http://mis.amartha.com/files/logo_amartha.png" /></td></tr>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<div class="topsheet_head3 nobdr">';
			$html .= '<table border="0"  width="100%" class="nobdr" >';
			$html .= '<tr><td align="center"  class="nobdr"><h2 align="center">TOPSHEET</h2></td></tr>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<div class="topsheet_head2">';
			$html .= '<table border="0" width="100%">';
			$html .= "<tr><td align='right'><i>* Semua angka dalam ribuan ('000)</i></td></tr>";
			$html .= '</table>';
			$html .= '</div>';
			$html .= '</div>';
			*/
			
			
			$html .= '<div id="topsheet">';
			$html .= '<div class="topsheet_head2">';
			$html .= '<table border="0">';
			$html .= '<tr><td>Area</td><td>: '.$group->area_name.'</td></tr>';
			$html .= '<tr><td>Cabang</td><td>: '.$group->area_code.$group->branch_code.' '.$group->branch_name.'</td></tr>';
			$html .= '<tr><td>Majelis</td><td>: <b>'.$group->group_name.'</b></td></tr>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<div class="topsheet_head">';
			$html .= '<table border="0">';
			$html .= '<tr><td>Kampung</td><td>: '.$group->group_kampung.'</td></tr>';
			$html .= '<tr><td>Desa</td><td>: '.$group->group_desa.'</td></tr>';
			$html .= '<tr><td>Jumlah Anggota</td><td>: '.$total_client.'</td></tr>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<div class="topsheet_head">';
			$html .= '<table border="0">';
			$html .= '<tr><td>Tanggal</td><td>: '.$date_next_week.'</td></tr>';
			$html .= '<tr><td>Ketua</td><td>: '.$group->group_leader.'</td></tr>';
			$html .= '<tr><td></td></tr>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<div class="topsheet_head">';
			$html .= '<table border="0">';
			$html .= '<tr><td>Akumulasi TR</td><td>: '.$group_tr.'</td></tr>';
			$html .= '<tr><td>Field Officer</td><td>: '.$group->officer_name.'</td></tr>';
			$html .= '<tr><td></td></tr>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<div class="clear"></div>';
			$html .= '</div>';
			//$html .= "<div><i>* Semua angka dalam ribuan ('000)</i></div>";
			//$html .= '<div class="clear"></div>';
			$html .= '<table class="tbl" width="100%" cellspacing="0">';
			//$html .= '<thead>';
			$html .= '<tr>';
			$html .= '	<th rowspan="2" align="left" class="bdr_btm_bold">No</th>';
			$html .= '	<th rowspan="2" align="left" class="bdr_btm_bold">Rekening</th>';
			$html .= '	<th rowspan="2" align="left" class="bdr_btm_bold">Nama</th>';
			$html .= '	<th colspan="6" class="bdr_btm_bold"><b>Absensi</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th colspan="8" class="bdr_btm_bold"><b>Pembiayaan</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th class="bdr_btm_bold"><b>PAR</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th colspan="3" class="bdr_btm_bold"><b>Tab Wajib</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th colspan="3" class="bdr_btm_bold"><b>Tab Sukarela</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th colspan="4" class="bdr_btm_bold"><b>Tab Berjangka</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th rowspan="2" class="bdr_btm_bold" width="60px">Ket</th>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '	<td align="center" class="bdr_btm_bold" width="25px">S</td>';
			$html .= '	<td align="center" class="bdr_btm_bold" width="25px">C</td>';
			$html .= '	<td align="center" class="bdr_btm_bold" width="25px">I</td>';
			$html .= '	<td align="center" class="bdr_btm_bold" width="25px">A</td>';
			$html .= '	<td align="center" class="bdr_btm_bold" width="25px">TR</td>';
			$html .= '	<td align="center" class="bdr_btm_bold" width="25px">H</td>';
			$html .= '	<td align="right" class="bdr_btm_bold">Plafond</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Status</td>';
			$html .= '	<td align="center" class="nobdr">&nbsp;</td>';
			$html .= '	<td align="center" class="bdr_btm_bold padlr" width="25px">F</td>';
			$html .= '	<td align="center" class="bdr_btm_bold padlr" width="25px">P</td>';
			$html .= '	<td align="right" class="bdr_btm_bold padlr" width="50px">Sisa<br/>Pokok</td>';
			$html .= '	<td align="right" class="bdr_btm_bold padlr" width="50px">Sisa<br/>Profit</td>';
			$html .= '	<td align="right" class="bdr_btm_bold padlr" width="50px">Total<br/>Angsur</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Minggu</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Saldo</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Setor</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Tarik</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Saldo</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Setor</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Tarik</td>';
			
			$html .= '	<td align="center" class="bdr_btm_bold">P</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Saldo</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Setor</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Tarik</td>';
			
			$html .= '</tr>';
			//$html .= '</thead>';			
			$html .= '<tbody>';
			$no=1;
			$today=date("Y-m-d");
			foreach($clients as $c):
			if($c->data_status != 4){
				$margin=0;
				$angsuranke=0;
				$angsuranke_sekarang = 0;
				$angsuran_pokok=0;
				$angsuran_profit=0;
				$sisa_pokok=0;
				$sisa_profit=0;
				if($c->data_status == 1){
					$status = "A";
					$id_pembiayaan = $c->data_id;
					$margin = $c->data_margin;
					$angsuranke= $c->data_angsuranke;
					$angsuranke_sekarang= $c->data_angsuranke;
					//$pertemuanke_sekarang = $c->data_pertemuanke + 1;
					$date_tagihan_pertama = $c->data_date_first;
					$diff = strtotime($today, 0) - strtotime($date_tagihan_pertama, 0);
					$pertemuanke_sekarang= floor($diff / 604800)  + 2;
					$plafond=  $c->data_plafond / 1000;
					$angsuran_pokok=  $c->data_angsuranpokok;
					$angsuran_profit= $c->data_margin / 50 ;
					$totalangsuran = $c->data_totalangsuran;
					$sisa_pokok  = ((50-$angsuranke) * $angsuran_pokok)/1000;
					$sisa_profit = ((50-$angsuranke) * $angsuran_profit)/1000;
					$total_tabwajib += $c->data_tabunganwajib;
					$grand_totalangsuran += $totalangsuran;					
					$data_par = $c->data_par;
				}elseif($c->data_status == 2 AND $c->data_date_accept == "$date_next_week_2"){
					$status = "T";
				}else{
					$status = "P";
				}
				$absen_s=0;
				$absen_c=0;
				$absen_i=0;
				$absen_a=0;
				if($id_pembiayaan!="" OR $id_pembiayaan!=0){
					$absen_s = $this->clients_model->count_absen_s($id_pembiayaan);
					$absen_c = $this->clients_model->count_absen_c($id_pembiayaan);
					$absen_i = $this->clients_model->count_absen_i($id_pembiayaan);
					$absen_a = $this->clients_model->count_absen_a($id_pembiayaan);
				}else{ 
					$absen_s=0;
					$absen_c=0;
					$absen_i=0;
					$absen_a=0;
				}
				$data_tr = $c->data_tr; 
				if($data_tr == 0){$data_tr = "-";}
				
				$html .= '<tr>';
				$html .= '<td align="center" class="bdr_btm">'.$no.'</td>';
				$html .= '<td class="bdr_btm">'.$c->client_account.'</td>';
				$html .= '<td class="bdr_btm">'.$c->client_fullname.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$absen_s.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$absen_c.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$absen_i.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$absen_a.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$data_tr.'</td>';
				$html .= '<td class="bdr_btm">&nbsp;</td>'; //hadir
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>'; //space
				$html .= '<td align="right" class="bdr_btm" >'.number_format($plafond,0).'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$status.'</td>';
				$html .= '<td class="nobdr">&nbsp;</td>';
				$html .= '<td class="bdr_btm">&nbsp;</td>';
				$html .= '<td align="center" class="bdr_btm">'.$angsuranke_sekarang.'</td>';
				if($c->data_status == 1){ $data_sisa_pokok=number_format($sisa_pokok,1); }else{ $data_sisa_pokok = "-";} ;
				if($c->data_status == 1){ $data_sisa_profit=number_format($sisa_profit,1); }else{ $data_sisa_profit = "-";} ;
				$html .= '<td align="right" class="bdr_btm">'.$data_sisa_pokok.'</td>';
				$html .= '<td align="right" class="bdr_btm">'.$data_sisa_profit.'</td>';
				if($c->data_status == 1){ $data_totalangsuran=number_format(($c->data_totalangsuran/1000),1); }else{ $data_totalangsuran = "-";} 
				$html .= '<td align="right" class="bdr_btm">'.$data_totalangsuran.'</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td align="center" class="bdr_btm">-</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				if($c->tabwajib_saldo){ $data_tabwajib=number_format(($c->tabwajib_saldo/1000),1);}else{ $data_tabwajib="0"; }
				if($c->data_status == 1){ $data_tabwajib_setor="1.0"; }else{ $data_tabwajib_setor="0"; }
				$html .= '<td class="bdr_btm" align="right">'.$data_tabwajib.'</td>';
				$html .= '<td class="bdr_leftbtm" align="right">'.$data_tabwajib_setor.'</td>';
				$html .= '<td class="bdr_leftbtm">&nbsp;</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				if($c->tabsukarela_saldo){ $data_tabsukarela=number_format(($c->tabsukarela_saldo/1000),1);}else{ $data_tabsukarela="0"; }
				$html .= '<td align="right" class="bdr_btm">'.$data_tabsukarela.'</td>';
				$html .= '<td class="bdr_leftbtm">&nbsp;</td>';
				$html .= '<td class="bdr_leftbtm">&nbsp;</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td align="center" class="bdr_btm"> </td>';
				
				if($c->tabberjangka_saldo){ $data_tabberjangka=number_format(($c->tabberjangka_saldo/1000),1);}else{ $data_tabberjangka="0"; }
				$html .= '<td align="right" class="bdr_btm">'.$data_tabberjangka.'</td>';
				$html .= '<td align="right" class="bdr_leftbtm"></td>';
				$html .= '<td class="bdr_leftbtm">&nbsp;</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="bdr_btm">&nbsp;</td>';
				
				$html .= '</tr>';
				$subtotal_angsuran += $data_totalangsuran;
				if($no == 5 OR $no == 10 OR $no == 15 OR $no == 20 OR $no == 25){ $html .=  "<tr><td colspan='17' class='nobdr' align='right'>Subtotal</td><td class='nobdr' align='right'><b>".number_format($subtotal_angsuran,1)."</b></td><td colspan='17' class='nobdr'></td></tr><tr><td colspan='35' class='bdr_btm'></td></tr>"; $subtotal_angsuran = 0;}
			$no++;
			}//endif
			endforeach;
			$html .=  "<tr><td colspan='17' class='nobdr' align='right'>Subtotal</td><td class='nobdr' align='right'><b>".number_format($subtotal_angsuran,1)."</b></td><td colspan='17' class='nobdr'></td></tr><tr><td colspan='35' class='bdr_btm'></td></tr>"; $subtotal_angsuran = 0;
			$html .= '<tr>';
				$html .= '<td class="nobdr"> </td>';
				$html .= '<td class="nobdr"> </td>';
				$html .= '<td class="nobdr"> </td>';
				$html .= '<td aligh="right" colspan="5"  class="bdr_btm_bold">Total Anggota Hadir</td>';
				$html .= '<td class="bdr_btm_bold"> </td>'; //kolom total hadir
				
				$html .= '<td class="nobdr" >&nbsp;</td>'; //space
				$html .= '<td class="nobdr" colspan="3">&nbsp;</td>'; //space
				
				$html .= '<td colspan="4" class="bdr_btm_bold">Sub Total</td>';
				$html .= '<td align="right" class="bdr_btm_bold"><b>'.number_format(($grand_totalangsuran/1000),1).'</b></td>';				
				$html .= '<td class="bdr_btm_bold"></td>';
				$html .= '<td class="bdr_btm_bold">&nbsp;</td>';
				$html .= '<td class="nobdr">&nbsp;</td>';
				$html .= '<td colspan="7" class="bdr_btm_bold"></td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td colspan="2" class="nobdr">&nbsp;</td>';
				$html .= '<td colspan="2" class="bdr_btm_bold">&nbsp;</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="bdr_btm_bold">&nbsp;</td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td colspan="12" rowspan="6" class="nobdr"><small><u>Keterangan:</u><br/>TR1: Hadir & tanggung renteng<br/>TR2: Absen tapi tanggung renteng<br/>TR3: Absen dan tidak tanggung renteng<br/><br/> S: Sakit<br/>C: Cuti Melahirkan/Pembiayaan<br/> I: Izin<br/>A: Alpha</small></td>';
				$html .= '<td rowspan="6" class="nobdr" >&nbsp;</td>'; //space
				$html .= '<td colspan="3" rowspan="6" class="nobdr" align="center">RF</td>';
				$html .= '<td colspan="4" class="bdr_btm_bold">Setoran</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td colspan="2" rowspan="6" class="nobdr" align="center">TAB</td>';
				//$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="4" class="bdr_btm_bold">Tab Wajib</td>';
				$html .= '<td align="right" class="bdr_btm_bold"><b>'.number_format(($total_tabwajib/1000),1).'</b></td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td colspan="4" class="bdr_btm_bold">Adm</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				//$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="5" class="bdr_btm_bold">Tab Sukarela</td>';
			$html .= '</tr>';
			
			
			$html .= '<tr>';
				$html .= '<td colspan="4" class="bdr_btm_bold"></td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				//$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="5" class="bdr_btm_bold">UMB Tab Sukarela</td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td colspan="4" class="bdr_btm_bold">LWK</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				//$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="5" class="bdr_btm_bold" >Tab Berjangka</td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td colspan="4" class="bdr_btm_bold">Gagal Dropping</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				//$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="5" class="bdr_btm_bold"></td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td colspan="4" class="bdr_btm_bold">Total</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				//$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="5" class="bdr_btm_bold">Total</td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td class="nobdr" width="2px" colspan="3">&nbsp;</td>';
				$html .= '<td class="nobdr" colspan="5" align="center">Ketua Majelis</td>';
				$html .= '<td class="nobdr" align="center" colspan="5">Field Officer</td>';
				$html .= '<td colspan="15" class="border_bold"><b>TOTAL</b></td>'; 
				$html .= '<td colspan="4" class="nobdr" align="center">Teller</td>';
				$html .= '<td colspan="4" class="nobdr" align="center">Manager</td>';
			$html .= '</tr>';
			
			
			
			$html .= '</tbody></table>';
			
			$html .= "</div>";
			$html;
			$this->load->library('mpdf');
			$mpdf=new mPDF('utf-8', array(330,210));
			$mpdf->SetFooter("Top Sheet".'||{PAGENO}|'); 
			//$mpdf->AddPage();
			$mpdf->WriteHTML($html);
			//$this->mpdf->Output();
			//echo $html;
			
		//}	
			
			$html .= "<br/><br/><br/>";
			$pdfFilePath = FCPATH."downloads/topsheet/$filename.pdf";
			$pdffile = base_url()."downloads/topsheet/$filename.pdf";
			//$mpdf->Output($pdfFilePath,'F');
			//redirect($pdffile, 'refresh');
			echo $html;
			echo $this->benchmark->elapsed_time();
	}
	
	public function schedule(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			
			//Get All Group (for filter button)
			$listgroup = $this->group_model->get_all_group_by_branch($total_rows ,0,$this->input->get('q'),$user_branch);
			
			//Get All Group (by day)
			$group_senin = $this->group_model->get_schedule($user_branch, "Senin");
			$group_selasa = $this->group_model->get_schedule($user_branch, "Selasa");
			$group_rabu = $this->group_model->get_schedule($user_branch, "Rabu");
			$group_kamis = $this->group_model->get_schedule($user_branch, "Kamis");
			$group_jumat = $this->group_model->get_schedule($user_branch, "Jumat");
			
			$this->template	->set('menu_title', 'Jadwal Pelayanan')
							->set('menu_transaksi', 'active')
							->set('group_senin', $group_senin)
							->set('group_selasa', $group_selasa)
							->set('group_rabu', $group_rabu)
							->set('group_kamis', $group_kamis)
							->set('group_jumat', $group_jumat)
							->set('listgroup', $listgroup)
							->set('no', $no)
							//->set('config', $config)
							->build('schedule');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function namahari($date){
				$namahari=date('l',strtotime($date));
				if ($namahari == "Sunday") $namahari = "Minggu";
				else if ($namahari == "Monday") $namahari = "Senin";
				else if ($namahari == "Tuesday") $namahari = "Selasa";
				else if ($namahari == "Wednesday") $namahari = "Rabu";
				else if ($namahari == "Thursday") $namahari = "Kamis";
				else if ($namahari == "Friday") $namahari = "Jumat";
				else if ($namahari == "Saturday") $namahari = "Sabtu";
				 
				return $namahari;
			}
}