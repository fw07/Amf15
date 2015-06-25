<?php

class Finance extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Finance';
	private $module 	= 'finance';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('group_model');
		$this->load->model('officer_model');
		$this->load->model('area_model');
		$this->load->model('branch_model');
		$this->load->model('report_model');
		
		$this->load->model('mailreport_model');
		$this->load->model('tsdaily_model');
		$this->load->model('finance_model');
		
		$this->load->library('pagination');	
	
	}
	

	public function index($page='0'){
		$user_level = $this->session->userdata('user_level');
		if($this->session->userdata('logged_in') AND $user_level == 1)
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			$branch = $this->branch_model->get_all()->result();
			
			//Build
			$this->template	->set('menu_title', 'Finance Report')
							->set('menu_report', 'active')
							->set('branch', $branch)
							->build('finance_browse');
			
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function daily_report(){
		if($this->session->userdata('logged_in'))
		{
			
			
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
							
			
			$report_branch = $this->input->post('branch');
			$report_date = $this->input->post('date');
			
			$branch = $this->branch_model->get_branch($report_branch)->result(); 
			$report['cabang'] = $branch[0]->branch_name;
			$report['jumlah_rf_masuk'] = 0;
			$report['jumlah_rf_keluar'] = 0;
			$report['jumlah_amanah_masuk'] = 0;
			$report['jumlah_amanah_keluar'] = 0;
			$report['jumlah_masuk'] = 0;
			$report['jumlah_keluar'] = 0;			
			$report['jumlah_setor'] = 0;
			
			
			
			
			//Anggota
			$report['anggota_baru'] = $this->finance_model->count_clients_daily($report_branch, $report_date);
			$report['anggota_keluar'] = $this->finance_model->count_clients_unreg_daily($report_branch, $report_date);
			
			//Pencairan
			$report['pencairan'] = $this->finance_model->count_daily_pencairan($report_branch, $report_date);
			//$report['jumlah_rf_keluar'] += $report['pencairan'];
			
			//Setoran
			$report['setoran_pokok'] = $this->finance_model->count_daily_setoran_pokok($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_pokok'];
			$report['setoran_margin'] = $this->finance_model->count_daily_setoran_margin($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_margin'];
			$report['setoran_adm'] = $this->finance_model->count_daily_setoran_adm($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_adm'];
			$report['setoran_asuransi'] = $this->finance_model->count_daily_setoran_asuransi($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_asuransi'];
			$report['setoran_butab'] = $this->finance_model->count_daily_setoran_butab($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_butab'];
			$report['setoran_lwk'] = $this->finance_model->count_daily_setoran_lwk($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_lwk'];
				
			//Tabungan
			$report['tabwajib_debet'] = $this->finance_model->count_daily_tab_tabwajib_debet($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['tabwajib_debet'];
			$report['tabwajib_credit'] = $this->finance_model->count_daily_tab_tabwajib_credit($report_branch, $report_date);
				$report['jumlah_rf_keluar'] += $report['tabwajib_credit'];
			$report['tabsukarela_debet'] = $this->finance_model->count_daily_tab_tabsukarela_debet($report_branch, $report_date);
				$report['jumlah_amanah_masuk'] += $report['tabsukarela_debet'];
			$report['tabsukarela_credit'] = $this->finance_model->count_daily_tab_tabsukarela_credit($report_branch, $report_date);
				$report['jumlah_amanah_keluar'] += $report['tabsukarela_credit'];
			$report['tabberjangka_debet'] = $this->finance_model->count_daily_tab_tabberjangka_debet($report_branch, $report_date);
				$report['jumlah_amanah_masuk'] += $report['tabberjangka_debet'];
			$report['tabberjangka_credit'] = $this->finance_model->count_daily_tab_tabberjangka_credit($report_branch, $report_date);
				$report['jumlah_amanah_keluar'] += $report['tabberjangka_credit'];
			
			$report['jumlah_masuk'] = $report['jumlah_rf_masuk'] + $report['jumlah_amanah_masuk'];
			$report['jumlah_keluar'] = $report['jumlah_rf_keluar'] + $report['jumlah_amanah_keluar'];			
			$report['jumlah_teller'] = $report['jumlah_masuk'] - $report['jumlah_keluar'];
			
			
			//Build
			$this->template	->set('menu_title', 'Laporan Harian')
							->set('menu_report', 'active')
							->set('report', $report)
							->build('finance/daily_report');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function daily_report_download(){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
							
			
			$report_branch = $this->input->post('branch');
			$report_date = $this->input->post('date');
			
			$branch = $this->branch_model->get_branch($report_branch)->result(); 
			$report['cabang'] = $branch[0]->branch_name;
			$report['jumlah_rf_masuk'] = 0;
			$report['jumlah_rf_keluar'] = 0;
			$report['jumlah_amanah_masuk'] = 0;
			$report['jumlah_amanah_keluar'] = 0;
			$report['jumlah_masuk'] = 0;
			$report['jumlah_keluar'] = 0;			
			$report['jumlah_setor'] = 0;
			//load our new mpdf library
			$this->load->library('mpdf');
			
			$html = "";
			$html.='<div style="float:left; width: 200px; text-align: left;"><i><b>Amartha</b> Microfinance</i></div>';
			$html.='<div style="float:right; width: 200px; text-align: right;">Cabang : <b>'.$report['cabang'].'</b></div>';
			$html.='<div style="float:none; clear: both;"></div>';
			$html.='<hr/>';
			$html.='<h1>Laporan Harian</h1>';
			$html.="<b>Tanggal :</b> ".$report_date." ";
			$html.='<hr/>';
			
			
			
			//Anggota
			$report['anggota_baru'] = $this->finance_model->count_clients_daily($report_branch, $report_date);
			$report['anggota_keluar'] = $this->finance_model->count_clients_unreg_daily($report_branch, $report_date);
			
			//Pencairan
			$report['pencairan'] = $this->finance_model->count_daily_pencairan($report_branch, $report_date);
			//$report['jumlah_rf_keluar'] += $report['pencairan'];
			
			//Setoran
			$report['setoran_pokok'] = $this->finance_model->count_daily_setoran_pokok($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_pokok'];
			$report['setoran_margin'] = $this->finance_model->count_daily_setoran_margin($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_margin'];
			$report['setoran_adm'] = $this->finance_model->count_daily_setoran_adm($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_adm'];
			$report['setoran_asuransi'] = $this->finance_model->count_daily_setoran_asuransi($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_asuransi'];
			$report['setoran_butab'] = $this->finance_model->count_daily_setoran_butab($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_butab'];
			$report['setoran_lwk'] = $this->finance_model->count_daily_setoran_lwk($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_lwk'];
				
			//Tabungan
			$report['tabwajib_debet'] = $this->finance_model->count_daily_tab_tabwajib_debet($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['tabwajib_debet'];
			$report['tabwajib_credit'] = $this->finance_model->count_daily_tab_tabwajib_credit($report_branch, $report_date);
				$report['jumlah_rf_keluar'] += $report['tabwajib_credit'];
			$report['tabsukarela_debet'] = $this->finance_model->count_daily_tab_tabsukarela_debet($report_branch, $report_date);
				$report['jumlah_amanah_masuk'] += $report['tabsukarela_debet'];
			$report['tabsukarela_credit'] = $this->finance_model->count_daily_tab_tabsukarela_credit($report_branch, $report_date);
				$report['jumlah_amanah_keluar'] += $report['tabsukarela_credit'];
			$report['tabberjangka_debet'] = $this->finance_model->count_daily_tab_tabberjangka_debet($report_branch, $report_date);
				$report['jumlah_amanah_masuk'] += $report['tabberjangka_debet'];
			$report['tabberjangka_credit'] = $this->finance_model->count_daily_tab_tabberjangka_credit($report_branch, $report_date);
				$report['jumlah_amanah_keluar'] += $report['tabberjangka_credit'];
			
			$report['jumlah_masuk'] = $report['jumlah_rf_masuk'] + $report['jumlah_amanah_masuk'];
			$report['jumlah_keluar'] = $report['jumlah_rf_keluar'] + $report['jumlah_amanah_keluar'];			
			$report['jumlah_teller'] = $report['jumlah_masuk'] - $report['jumlah_keluar'];
			
			$html .= "<table class='table table-striped m-b-none text-sm'> ";          
$html .= "<thead>";
$html .= "<tr>";
$html .= "<th rowspan='2'>No</th>";
$html .= "<th rowspan='2'>Keterangan</th>";
$html .= "<th colspan='2' align='center'>RF</th>";
$html .= "<th width='20px' rowspan='2'>&nbsp;&nbsp;&nbsp;</th>";
$html .= "<th colspan='2' align='center'>Amanah</th>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<th align='center'>Masuk</th>";
$html .= "<th align='center'>Keluar</th>";
$html .= "<th align='center'>Masuk</th>";
$html .= "<th align='center'>Keluar</th>";
$html .= "</tr>";
$html .= "</thead> ";
$html .= "<tbody>";

$html .= "<tr> ";
$html .= "<td>A</td>";
$html .= "<td>Anggota</td>";
$html .= "<td align='right'>".$report['anggota_baru']."</td>";
$html .= "<td align='right'>".$report['anggota_keluar']."</td>";
$html .= "<td></td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr> ";
$html .= "<td>B</td>";
$html .= "<td>Pencairan</td>";
$html .= "<td align='right'>-</td>";
$html .= "<td align='right'>".number_format($report['pencairan'])."</td>";
$html .= "<td></td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr> ";
$html .= "<td>C</td>";
$html .= "<td>Gagal Dropping</td>";
$html .= "<td align='right'>0</td>";
$html .= "<td align='right'>-</td>";
$html .= "<td></td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr> ";
$html .= "<td>D</td>";
$html .= "<td>Setoran Pokok</td>";
$html .= "<td align='right'>".number_format($report['setoran_pokok'])."</td>";
$html .= "<td align='right'>-</td>";
$html .= "<td></td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr> ";
$html .= "<td>E</td>";
$html .= "<td>Setoran Margin</td>";
$html .= "<td align='right'>".number_format($report['setoran_margin'])."</td>";
$html .= "<td align='right'>-</td>";
$html .= "<td></td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr>"; 
$html .= "<td>F</td>";
$html .= "<td>Pendapatan Admin</td>";
$html .= "<td align='right'>".number_format($report['setoran_adm'])."</td>";
$html .= "<td align='right'>-</td>";
$html .= "<td></td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr>";
$html .= "<td>G</td>";
$html .= "<td>Asuransi</td>";
$html .= "<td align='right'>".number_format($report['setoran_asuransi'])."</td>";
$html .= "<td align='right'>-</td>";
$html .= "<td></td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr>";
$html .= "<td>H</td>";
$html .= "<td>Butab / Kartu Angsuran</td>";
$html .= "<td align='right'>".number_format($report['setoran_butab'])."</td>";
$html .= "<td align='right'>-</td>";
$html .= "<td></td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr>";
$html .= "<td>I</td>";
$html .= "<td>LWK</td>";
$html .= "<td align='right'>".number_format($report['setoran_lwk'])."</td>";
$html .= "<td align='right'>-</td>";
$html .= "<td></td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr>";
$html .= "<td>J</td>";
$html .= "<td>UMB Tabungan</td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "<td></td>";
$html .= "<td align='right'>".number_format($report['umb'])."</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr>";
$html .= "<td>K</td>";
$html .= "<td>Tabungan Wajib</td>";
$html .= "<td align='right'>".number_format($report['tabwajib_debet'])."</td>";
$html .= "<td align='right'>".number_format($report['tabwajib_credit'])."</td>";
$html .= "<td></td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "</tr>";

$html .= "<tr>";
$html .= "<td>L</td>";
$html .= "<td>Tabungan Sukarela</td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "<td></td>";
$html .= "<td align='right'>".number_format($report['tabsukarela_debet'])."</td>";
$html .= "<td align='right'>".number_format($report['tabsukarela_credit'])."</td>";
$html .= "</tr>";

$html .= "<tr>";
$html .= "<td>M</td>";
$html .= "<td>Tabungan Berjangka</td>";
$html .= "<td align='center'>-</td>";
$html .= "<td align='center'>-</td>";
$html .= "<td></td>";
$html .= "<td align='right'>".number_format($report['tabberjangka_debet'])."</td>";
$html .= "<td align='right'>".number_format($report['tabberjangka_credit'])."</td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td>N</td>";
$html .= "<td align='right'><b>JUMLAH</b></td>";
$html .= "<td align='right'><b>".number_format($report['jumlah_rf_masuk'])."</b></td>";
$html .= "<td align='right'><b>".number_format($report['jumlah_rf_keluar'])."</b></td>";
$html .= "<td></td>";
$html .= "<td align='right'><b>".number_format($report['jumlah_amanah_masuk'])."</b></td>";
$html .= "<td align='right'><b>".number_format($report['jumlah_amanah_keluar'])."</b></td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td>O</td>";
$html .= "<td>Jumlah Masuk &nbsp;&nbsp;&nbsp;<i>(A+C+D+E+F+G+H+I+J+K+L+M)</i></td>";
$html .= "<td colspan='5' align='right'>".number_format($report['jumlah_masuk'])."</td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td>P</td>";
$html .= "<td>Jumlah Keluar &nbsp;&nbsp;&nbsp;<i>(A+C+D+E+F+G+H+I+J+K+L+M)</i></td>";
$html .= "<td colspan='5' align='right'>".number_format($report['jumlah_keluar'])."</td>";
$html .= "</tr>";

$html .= "<tr>";
$html .= "<td>Q</td>";
$html .= "<td><b>TOTAL SETOR KE TELLER &nbsp;&nbsp;&nbsp;<i>(O-P)</i></b></td>";
$html .= "<td colspan='5' align='right'><b>".number_format($report['jumlah_teller'])."</b></td>";
$html .= "</tr>";
$html .= "</tbody>";
$html .= "</table>";
			
			
			$filename = $report_date."_Laporan_Harian_".$report['cabang']; 
			$this->mpdf->SetFooter("Amartha Microfinance".'|{PAGENO}|'."Laporan Harian"); 
			$this->mpdf->WriteHTML($html);
			$pdfFilePath = FCPATH."downloads/reports/daily/$filename.pdf";
			$this->mpdf->Output($pdfFilePath, 'F');			
			redirect(base_url()."downloads/reports/daily/$filename.pdf");
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function daily_validasi_teller(){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
							
			
			$report_branch = $this->input->post('branch');
			$report_date = $this->input->post('date');
			
			$branch = $this->branch_model->get_branch($report_branch)->result(); 
			$report['cabang'] = $branch[0]->branch_name;
			
			//Anggota
			$tsdaily = $this->finance_model->get_tsdaily($report_branch, $report_date);
			
			
			
			//Build
			$this->template	->set('menu_title', 'Validasi Teller')
							->set('menu_report', 'active')
							->set('tsdaily', $tsdaily)
							->set('report', $report)
							->build('finance/daily_validasi_teller');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}

	
	public function mail_daily_report(){
			
			$report_branch = 0;
			$report_date = $this->input->post('date');
			
			$branch = $this->branch_model->get_branch($report_branch)->result(); 
			$report['cabang'] = $branch[0]->branch_name;
			$report['jumlah_rf_masuk'] = 0;
			$report['jumlah_rf_keluar'] = 0;
			$report['jumlah_amanah_masuk'] = 0;
			$report['jumlah_amanah_keluar'] = 0;
			$report['jumlah_masuk'] = 0;
			$report['jumlah_keluar'] = 0;			
			$report['jumlah_setor'] = 0;
			
			//Anggota
			$report['anggota_baru'] = $this->finance_model->count_clients_daily($report_branch, $report_date);
			$report['anggota_keluar'] = $this->finance_model->count_clients_unreg_daily($report_branch, $report_date);
			
			//Pencairan
			$report['pencairan'] = $this->finance_model->count_daily_pencairan($report_branch, $report_date);
			//$report['jumlah_rf_keluar'] += $report['pencairan'];
			
			//Setoran
			$report['setoran_pokok'] = $this->finance_model->count_daily_setoran_pokok($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_pokok'];
			$report['setoran_margin'] = $this->finance_model->count_daily_setoran_margin($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_margin'];
			$report['setoran_adm'] = $this->finance_model->count_daily_setoran_adm($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_adm'];
			$report['setoran_asuransi'] = $this->finance_model->count_daily_setoran_asuransi($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_asuransi'];
			$report['setoran_butab'] = $this->finance_model->count_daily_setoran_butab($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_butab'];
			$report['setoran_lwk'] = $this->finance_model->count_daily_setoran_lwk($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['setoran_lwk'];
				
			//Tabungan
			$report['tabwajib_debet'] = $this->finance_model->count_daily_tab_tabwajib_debet($report_branch, $report_date);
				$report['jumlah_rf_masuk'] += $report['tabwajib_debet'];
			$report['tabwajib_credit'] = $this->finance_model->count_daily_tab_tabwajib_credit($report_branch, $report_date);
				$report['jumlah_rf_keluar'] += $report['tabwajib_credit'];
			$report['tabsukarela_debet'] = $this->finance_model->count_daily_tab_tabsukarela_debet($report_branch, $report_date);
				$report['jumlah_amanah_masuk'] += $report['tabsukarela_debet'];
			$report['tabsukarela_credit'] = $this->finance_model->count_daily_tab_tabsukarela_credit($report_branch, $report_date);
				$report['jumlah_amanah_keluar'] += $report['tabsukarela_credit'];
			$report['tabberjangka_debet'] = $this->finance_model->count_daily_tab_tabberjangka_debet($report_branch, $report_date);
				$report['jumlah_amanah_masuk'] += $report['tabberjangka_debet'];
			$report['tabberjangka_credit'] = $this->finance_model->count_daily_tab_tabberjangka_credit($report_branch, $report_date);
				$report['jumlah_amanah_keluar'] += $report['tabberjangka_credit'];
			
			$report['jumlah_masuk'] = $report['jumlah_rf_masuk'] + $report['jumlah_amanah_masuk'];
			$report['jumlah_keluar'] = $report['jumlah_rf_keluar'] + $report['jumlah_amanah_keluar'];			
			$report['jumlah_teller'] = $report['jumlah_masuk'] - $report['jumlah_keluar'];
			
			
			$body = "<table class='std' width='320px'>";
			$body .= "";
			$body .= "<tr><td width='180px'>TOTAL ANGGOTA  </td><td>".number_format($total_anggota)."</td></tr>"; 
			$body .= "<tr><td>TOTAL MAJELIS  </td><td>".number_format($total_majelis)."</td></tr>"; 
			$body .= "<tr><td colspan='2'><hr/></td></tr>";
			$body .= "<tr><td>ANGGOTA BARU </td><td>".$total_clients_weekly."</td></tr>";
			$body .= "<tr><td>ANGGOTA KELUAR </td><td>".$total_unreg_clients_weekly."</td></tr>";
			$body .= "<tr><td colspan='2'><hr/></td></tr>";
			$body .= "<tr><td>JUMLAH TRANSAKSI </td><td>".$total_transaksi."</td></tr>";
			$body .= "<tr><td>JUMLAH TOPSHEET </td><td>".$tsdaily->total_majelis."</td></tr>";
			$body .= "<tr><td>TINGKAT KEHADIRAN </td><td>".round($total_kehadiran_persen)."%</td></tr>";
			$body .= "<tr><td colspan='2'><hr/></td></tr>";
			$body .= "<tr><td>ANGSURAN POKOK  </td><td>Rp ".number_format($tsdaily->total_angsuranpokok)."</td></tr>";  
			$body .= "<tr><td>ANGSURAN PROFIT </td><td>Rp ".number_format($tsdaily->total_angsuranprofit)."</td></tr>";  
			$body .= "<tr><td>TABUNGAN SUKARELA </td><td>Rp ".number_format($tsdaily->total_tabungan_sukarela)."</td></tr>"; 
			$body .= "<tr><td>TABUNGAN BERJANGKA </td><td>Rp ".number_format($tsdaily->total_tabungan_berjangka)."</td></tr>"; 
			$body .= "<tr><td colspan='2'><hr/></td></tr>";
			$body .= "<tr><td>JUMLAH PENGAJUAN</td><td>".$total_pengajuan."</td></tr>"; 
			$body .= "<tr><td>DANA PENGAJUAN</td><td>Rp ".number_format($total_uang_pengajuan)."</td></tr>"; 
			$body .= "<tr><td>JUMLAH PENCAIRAN</td><td>".$total_pencairan."</td></tr>";
			$body .= "<tr><td>DANA DICAIRKAN</td><td>Rp ".number_format($total_uang_pencairan)."</td></tr>";
			$body .= "</table>";
			
			$html ='<html lang="en">
							<head>
							  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
							  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
							  <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
							  <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
							  <title>Amartha Microfinance</title>

							  <style type="text/css">
								body {
								  margin: 0;
								  padding: 0;
								  -ms-text-size-adjust: 100%;
								  -webkit-text-size-adjust: 100%;
								}

								table {
								  border-spacing: 0;
								}

								table td {
								  border-collapse: collapse;
								  
								}

								.ExternalClass {
								  width: 100%;
								}

								.ExternalClass,
								.ExternalClass p,
								.ExternalClass span,
								.ExternalClass font,
								.ExternalClass td,
								.ExternalClass div {
								  line-height: 100%;
								}

								.ReadMsgBody {
								  width: 100%;
								  background-color: #ebebeb;
								}

								table {
								  mso-table-lspace: 0pt;
								  mso-table-rspace: 0pt;
								}

								img {
								  -ms-interpolation-mode: bicubic;
								}

								.yshortcuts a {
								  border-bottom: none !important;
								}
						
								@media screen and (max-width: 599px) {
								  table[class="force-row"],
								  table[class="container"] {
									width: 100% !important;
									max-width: 100% !important;
								  }
								}
								@media screen and (max-width: 400px) {
								  td[class*="container-padding"] {
									padding-left: 12px !important;
									padding-right: 12px !important;
								  }
								}
								.ios-footer a {
								  color: #aaaaaa !important;
								  text-decoration: underline;
								}
								.std{
									font-size: 12px;
									
								}
								hr{
									border: 0;
									border-bottom: 1px dashed #ccc;
									background: #999;
								}
								table.std td{
									padding: 4px 0;
								}
								.purple{ color #704390; }
								</style>

							</head>
							<body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

							<!-- 100% background wrapper (grey background) -->
							<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
							  <tr>
								<td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

								  <br>

								  <!-- 600px container (white background) -->
								  <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
									<tr>
									  <td class="container-padding header" align="left" style="padding-bottom:12px;color:#99cc00;padding-left:24px;padding-right:24px">
									   <br/><img src="http://amartha.co.id/themes/default/img/logo-black.png" />
									  
									  </td>
									</tr>
									<tr>
									  <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
										<br>
										<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#704390">Weeky Transaction Report ('.$date_now.')</div>
										<br>

										<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
											'.$body.'
											<br><br>
											<small style="color:#666;"><i>The numbers are calculated based on transaction entry by admin staff at Amartha Microfinance.<br/>Generated at '.$timestamp.'</i></small>
											
											<br><br>
										</div>

									  </td>
									</tr>
									<tr>
									  <td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
										<br>
										<strong>Amartha Microfinance</strong><br>
										<span class="ios-footer">
										 <a href="mailto:info@amartha.co.id" style="color:#aaaaaa;text-decoration: none;">info@amartha.co.id</a><br/>
										</span>
										<a href="http://www.amartha.co.id" style="color:#aaaaaa;text-decoration: none;">www.amartha.co.id</a><br>

										<br><br>

									  </td>
									</tr>
								  </table>


								</td>
							  </tr>
							</table>

							</body>
							</html>';
							echo $html; 
			
			//UPDATE EMAIL	
			$this->load->library('email');
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'mail.amartha.com',
				'smtp_port' => '25',
				'smtp_user' => 'mis@amartha.com', // change it to yours
				'smtp_pass' => 'MISamartha', // change it to yours
				'mailtype' => 'html',
				'charset' => 'utf-8',
				'wordwrap' => FALSE,
				'newline' => "\r\n"
			);

			$this->email->initialize($config);

			$this->email->from('mis@amartha.com','Amartha MIS'); 
			$this->email->to('fikri@amartha.co.id, ataufan@amartha.co.id'); 
			$this->email->bcc('mis@amartha.com'); 
			$this->email->subject('[Amartha MIS] Weekly Transaction Report ('.$date_now.')'); 
			$messagebody =  $html;	
			$this->email->message($messagebody); 
			$this->email->send();		
		
	}
	
	
}