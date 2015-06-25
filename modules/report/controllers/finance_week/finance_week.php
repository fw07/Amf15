<?php

class Finance_week extends Front_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('finance_week_model');
		$this->load->model('tsdaily_model');
		$this->load->model('branch_model');
	}
	
	public function laporan_mingguan(){
			$timestamp = date('Y-m-d H:i:s');
			
			$report_branch = $this->input->post('branch');
			$branch = $this->branch_model->get_branch($report_branch)->result(); 
			$result['cabang'] = $branch[0]->branch_name;
			if($report_branch  == 0){$result['cabang'] = "Pusat";}
			$report_startdate = $this->input->post('startdate');
			$report_enddate = $this->input->post('enddate');
			if($report_startdate=="" AND $report_enddate==""){
				$day = date('w');
				$date_now = date('Y-m-d');
				$date_month  = date('m');
				$date_year   = date('Y');
				$date_start  = date("Y-m-d", strtotime('-'.$day.' days'));
				$report_startdate  = date("Y-m-d", strtotime($date_start . ' + 1 day'));
				$report_enddate    = date("Y-m-d", strtotime($date_start . ' + 7 day'));					
			}
			
			//ANGGOTA AKTIF
			$total_clients=$this->finance_week_model->count_clients_by_branch($report_branch);
			$result['total_clients_inweek'] =  $this->finance_week_model->count_weeklyclients_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_clients_startweek'] =  $this->finance_week_model->count_startweekclients_by_branch($report_branch,$report_startdate);
			$result['total_clients_endweek'] = $result['total_clients_inweek'] + $result['total_clients_startweek'];
			
			//ANGGOTA KELUAR
			$result['total_unreg_clients_inweek'] = $this->finance_week_model->count_weekly_unregclients_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_unreg_clients_startweek'] = $this->finance_week_model->count_startweekly_unregclients_by_branch($report_branch,$report_startdate);
			$result['total_unreg_clients_endweek'] = $result['total_unreg_clients_inweek'] + $result['total_unreg_clients_startweek'];
			
			//MAJELIS
			$result['total_majelis_inweek'] = $this->finance_week_model->count_weekly_majelis_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_majelis_startweek'] = $this->finance_week_model->count_startweekly_majelis_by_branch($report_branch,$report_startdate);
			$result['total_majelis_endweek'] = $result['total_majelis_inweek'] + $result['total_majelis_startweek'];
			
			//DESA
			$result['total_desa'] = $this->finance_week_model->count_desa_by_branch($report_branch);
			
			//PEMBIAYAAN
			$result['total_pembiayaan_disalurkan_inweek'] = $this->finance_week_model->count_total_pembiayaan_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_pembiayaan_disalurkan_startweek'] = $this->finance_week_model->count_total_startpembiayaan_by_branch($report_branch,$report_startdate);
			$result['total_pembiayaan_disalurkan_endweek'] = $result['total_pembiayaan_disalurkan_inweek'] + $result['total_pembiayaan_disalurkan_startweek'];
			$result['total_pembiayaan_disalurkan_endweek'] =  $this->finance_week_model->sum_pembiayaan_aktif($report_branch);
			
			//MARGIN
			$result['total_pembiayaan_margin_inweek'] = $this->finance_week_model->count_total_margin_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_pembiayaan_margin_startweek'] = $this->finance_week_model->count_total_startmargin_by_branch($report_branch,$report_startdate);
			$result['total_pembiayaan_margin_endweek'] = $result['total_pembiayaan_margin_inweek'] + $result['total_pembiayaan_margin_startweek'];
			
			//PENGEMBALIAN POKOK
			$result['total_pembiayaan_pengembalian_inweek'] = $this->finance_week_model->count_total_pengembalian_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_pembiayaan_pengembalian_startweek'] = $this->finance_week_model->count_total_startpengembalian_by_branch($report_branch,$report_startdate);
			$result['total_pembiayaan_pengembalian_endweek'] = $result['total_pembiayaan_pengembalian_inweek'] + $result['total_pembiayaan_pengembalian_startweek'];
			
			//ANGGOTA AKTIF PEMBIAYAAN
			$result['total_anggota_aktif_pembiayaan_inweek'] = $this->finance_week_model->count_weekly_clients_aktif_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_anggota_aktif_pembiayaan_startweek'] = $this->finance_week_model->count_startweekly_clients_aktif_by_branch($report_branch,$report_startdate);
			$result['total_anggota_aktif_pembiayaan_endweek'] = $result['total_anggota_aktif_pembiayaan_inweek'] + $result['total_anggota_aktif_pembiayaan_startweek'];
			
			
			//TAB SUKARELA
			//TAB SUKARELA debet
			$result['total_weekly_tabsukarela_debet_inweek'] = $this->finance_week_model->count_weekly_tabsukarela_debet_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabsukarela_debet_startweek'] = $this->finance_week_model->count_startweekly_tabsukarela_debet_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabsukarela_debet_endweek'] = $result['total_weekly_tabsukarela_debet_inweek'] + $result['total_weekly_tabsukarela_debet_startweek'];
			
			//TAB SUKARELA credit
			$result['total_weekly_tabsukarela_credit_inweek'] = $this->finance_week_model->count_weekly_tabsukarela_credit_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabsukarela_credit_startweek'] = $this->finance_week_model->count_startweekly_tabsukarela_credit_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabsukarela_credit_endweek'] = $result['total_weekly_tabsukarela_credit_inweek'] + $result['total_weekly_tabsukarela_credit_startweek'];
			
			//TAB SUKARELA total
			$result['total_weekly_tabsukarela_startweek'] = $result['total_weekly_tabsukarela_debet_startweek'] - $result['total_weekly_tabsukarela_credit_startweek'];
			$result['total_weekly_tabsukarela_inweek'] = $result['total_weekly_tabsukarela_debet_inweek'] - $result['total_weekly_tabsukarela_credit_inweek'];
			$result['total_weekly_tabsukarela_endweek'] = $result['total_weekly_tabsukarela_debet_endweek'] - $result['total_weekly_tabsukarela_credit_endweek'];
			
			
			//TAB WAJIB
			//TAB WAJIB debet
			$result['total_weekly_tabwajib_debet_inweek'] = $this->finance_week_model->count_weekly_tabwajib_debet_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabwajib_debet_startweek'] = $this->finance_week_model->count_startweekly_tabwajib_debet_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabwajib_debet_endweek'] = $result['total_weekly_tabwajib_debet_inweek'] + $result['total_weekly_tabwajib_debet_startweek'];
			
			//TAB WAJIB credit
			$result['total_weekly_tabwajib_credit_inweek'] = $this->finance_week_model->count_weekly_tabwajib_credit_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabwajib_credit_startweek'] = $this->finance_week_model->count_startweekly_tabwajib_credit_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabwajib_credit_endweek'] = $result['total_weekly_tabwajib_credit_inweek'] + $result['total_weekly_tabwajib_credit_startweek'];
			
			//TAB WAJIB total
			$result['total_weekly_tabwajib_startweek'] = $result['total_weekly_tabwajib_debet_startweek'] - $result['total_weekly_tabwajib_credit_startweek'];
			$result['total_weekly_tabwajib_inweek'] = $result['total_weekly_tabwajib_debet_inweek'] - $result['total_weekly_tabwajib_credit_inweek'];
			$result['total_weekly_tabwajib_endweek'] = $result['total_weekly_tabwajib_debet_endweek'] - $result['total_weekly_tabwajib_credit_endweek'];
			
			
			//TAB BERJANGKA
			//TAB BERJANGKA debet
			$result['total_weekly_tabberjangka_debet_inweek'] = $this->finance_week_model->count_weekly_tabberjangka_debet_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabberjangka_debet_startweek'] = $this->finance_week_model->count_startweekly_tabberjangka_debet_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabberjangka_debet_endweek'] = $result['total_weekly_tabberjangka_debet_inweek'] + $result['total_weekly_tabberjangka_debet_startweek'];
			
			//TAB BERJANGKA credit
			$result['total_weekly_tabberjangka_credit_inweek'] = $this->finance_week_model->count_weekly_tabberjangka_credit_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabberjangka_credit_startweek'] = $this->finance_week_model->count_startweekly_tabberjangka_credit_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabberjangka_credit_endweek'] = $result['total_weekly_tabberjangka_credit_inweek'] + $result['total_weekly_tabberjangka_credit_startweek'];
			
			//TAB BERJANGKA total
			$result['total_weekly_tabberjangka_startweek'] = $result['total_weekly_tabberjangka_debet_startweek'] - $result['total_weekly_tabberjangka_credit_startweek'];
			$result['total_weekly_tabberjangka_inweek'] = $result['total_weekly_tabberjangka_debet_inweek'] - $result['total_weekly_tabberjangka_credit_inweek'];
			$result['total_weekly_tabberjangka_endweek'] = $result['total_weekly_tabberjangka_debet_endweek'] - $result['total_weekly_tabberjangka_credit_endweek'];
			
			//TAB TOTAL
			$result['total_weekly_tabungan_startweek'] = $result['total_weekly_tabsukarela_startweek'] + $result['total_weekly_tabwajib_startweek'] + $result['total_weekly_tabberjangka_startweek'];
			$result['total_weekly_tabungan_inweek'] = $result['total_weekly_tabsukarela_inweek'] + $result['total_weekly_tabwajib_inweek'] + $result['total_weekly_tabberjangka_inweek'];
			$result['total_weekly_tabungan_endweek'] = $result['total_weekly_tabsukarela_endweek'] + $result['total_weekly_tabwajib_endweek'] + $result['total_weekly_tabberjangka_endweek'];
			
			
			$result['total_weekly_pengajuan_orang'] = $this->finance_week_model->count_pengajuan($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_pengajuan_rp'] = $this->finance_week_model->count_total_pengajuan($report_branch,$report_startdate,$report_enddate);
			
			//echo number_format($result['total_weekly_tabberjangka_debet_inweek']);
			
			//Build
			$this->template	->set('menu_title', 'Laporan Mingguan')
							->set('menu_report', 'active')
							->set('result', $result)
							->set('branch_name', $branch_name)
							->set('branch', $report_branch)
							->set('start_date', $report_startdate)
							->set('end_date', $report_enddate)
							->build('finance/weekly_report');
	}
	
	public function laporan_mingguan_download(){
			$timestamp = date('Y-m-d H:i:s');
			
			$report_branch = $this->input->post('branch');
			$branch = $this->branch_model->get_branch($report_branch)->result(); 
			$result['cabang'] = $branch[0]->branch_name;
			if($report_branch  == 0){$result['cabang'] = "Pusat";}
			$report_startdate = $this->input->post('startdate');
			$report_enddate = $this->input->post('enddate');
			if($report_startdate=="" AND $report_enddate==""){
				$day = date('w');
				$date_now = date('Y-m-d');
				$date_month  = date('m');
				$date_year   = date('Y');
				$date_start  = date("Y-m-d", strtotime('-'.$day.' days'));
				$report_startdate  = date("Y-m-d", strtotime($date_start . ' + 1 day'));
				$report_enddate    = date("Y-m-d", strtotime($date_start . ' + 7 day'));					
			}
			
			//ANGGOTA AKTIF
			$total_clients=$this->finance_week_model->count_clients_by_branch($report_branch);
			$result['total_clients_inweek'] =  $this->finance_week_model->count_weeklyclients_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_clients_startweek'] =  $this->finance_week_model->count_startweekclients_by_branch($report_branch,$report_startdate);
			$result['total_clients_endweek'] = $result['total_clients_inweek'] + $result['total_clients_startweek'];
			
			//ANGGOTA KELUAR
			$result['total_unreg_clients_inweek'] = $this->finance_week_model->count_weekly_unregclients_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_unreg_clients_startweek'] = $this->finance_week_model->count_startweekly_unregclients_by_branch($report_branch,$report_startdate);
			$result['total_unreg_clients_endweek'] = $result['total_unreg_clients_inweek'] + $result['total_unreg_clients_startweek'];
			
			//MAJELIS
			$result['total_majelis_inweek'] = $this->finance_week_model->count_weekly_majelis_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_majelis_startweek'] = $this->finance_week_model->count_startweekly_majelis_by_branch($report_branch,$report_startdate);
			$result['total_majelis_endweek'] = $result['total_majelis_inweek'] + $result['total_majelis_startweek'];
			
			//DESA
			$result['total_desa'] = $this->finance_week_model->count_desa_by_branch($report_branch);
			
			//PEMBIAYAAN
			$result['total_pembiayaan_disalurkan_inweek'] = $this->finance_week_model->count_total_pembiayaan_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_pembiayaan_disalurkan_startweek'] = $this->finance_week_model->count_total_startpembiayaan_by_branch($report_branch,$report_startdate);
			$result['total_pembiayaan_disalurkan_endweek'] = $result['total_pembiayaan_disalurkan_inweek'] + $result['total_pembiayaan_disalurkan_startweek'];
			$result['total_pembiayaan_disalurkan_endweek'] =  $this->finance_week_model->sum_pembiayaan_aktif($report_branch);
			
			//MARGIN
			$result['total_pembiayaan_margin_inweek'] = $this->finance_week_model->count_total_margin_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_pembiayaan_margin_startweek'] = $this->finance_week_model->count_total_startmargin_by_branch($report_branch,$report_startdate);
			$result['total_pembiayaan_margin_endweek'] = $result['total_pembiayaan_margin_inweek'] + $result['total_pembiayaan_margin_startweek'];
			
			//PENGEMBALIAN POKOK
			$result['total_pembiayaan_pengembalian_inweek'] = $this->finance_week_model->count_total_pengembalian_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_pembiayaan_pengembalian_startweek'] = $this->finance_week_model->count_total_startpengembalian_by_branch($report_branch,$report_startdate);
			$result['total_pembiayaan_pengembalian_endweek'] = $result['total_pembiayaan_pengembalian_inweek'] + $result['total_pembiayaan_pengembalian_startweek'];
			
			//ANGGOTA AKTIF PEMBIAYAAN
			$result['total_anggota_aktif_pembiayaan_inweek'] = $this->finance_week_model->count_weekly_clients_aktif_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_anggota_aktif_pembiayaan_startweek'] = $this->finance_week_model->count_startweekly_clients_aktif_by_branch($report_branch,$report_startdate);
			$result['total_anggota_aktif_pembiayaan_endweek'] = $result['total_anggota_aktif_pembiayaan_inweek'] + $result['total_anggota_aktif_pembiayaan_startweek'];
			
			
			//TAB SUKARELA
			//TAB SUKARELA debet
			$result['total_weekly_tabsukarela_debet_inweek'] = $this->finance_week_model->count_weekly_tabsukarela_debet_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabsukarela_debet_startweek'] = $this->finance_week_model->count_startweekly_tabsukarela_debet_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabsukarela_debet_endweek'] = $result['total_weekly_tabsukarela_debet_inweek'] + $result['total_weekly_tabsukarela_debet_startweek'];
			
			//TAB SUKARELA credit
			$result['total_weekly_tabsukarela_credit_inweek'] = $this->finance_week_model->count_weekly_tabsukarela_credit_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabsukarela_credit_startweek'] = $this->finance_week_model->count_startweekly_tabsukarela_credit_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabsukarela_credit_endweek'] = $result['total_weekly_tabsukarela_credit_inweek'] + $result['total_weekly_tabsukarela_credit_startweek'];
			
			//TAB SUKARELA total
			$result['total_weekly_tabsukarela_startweek'] = $result['total_weekly_tabsukarela_debet_startweek'] - $result['total_weekly_tabsukarela_credit_startweek'];
			$result['total_weekly_tabsukarela_inweek'] = $result['total_weekly_tabsukarela_debet_inweek'] - $result['total_weekly_tabsukarela_credit_inweek'];
			$result['total_weekly_tabsukarela_endweek'] = $result['total_weekly_tabsukarela_debet_endweek'] - $result['total_weekly_tabsukarela_credit_endweek'];
			
			
			//TAB WAJIB
			//TAB WAJIB debet
			$result['total_weekly_tabwajib_debet_inweek'] = $this->finance_week_model->count_weekly_tabwajib_debet_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabwajib_debet_startweek'] = $this->finance_week_model->count_startweekly_tabwajib_debet_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabwajib_debet_endweek'] = $result['total_weekly_tabwajib_debet_inweek'] + $result['total_weekly_tabwajib_debet_startweek'];
			
			//TAB WAJIB credit
			$result['total_weekly_tabwajib_credit_inweek'] = $this->finance_week_model->count_weekly_tabwajib_credit_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabwajib_credit_startweek'] = $this->finance_week_model->count_startweekly_tabwajib_credit_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabwajib_credit_endweek'] = $result['total_weekly_tabwajib_credit_inweek'] + $result['total_weekly_tabwajib_credit_startweek'];
			
			//TAB WAJIB total
			$result['total_weekly_tabwajib_startweek'] = $result['total_weekly_tabwajib_debet_startweek'] - $result['total_weekly_tabwajib_credit_startweek'];
			$result['total_weekly_tabwajib_inweek'] = $result['total_weekly_tabwajib_debet_inweek'] - $result['total_weekly_tabwajib_credit_inweek'];
			$result['total_weekly_tabwajib_endweek'] = $result['total_weekly_tabwajib_debet_endweek'] - $result['total_weekly_tabwajib_credit_endweek'];
			
			
			//TAB BERJANGKA
			//TAB BERJANGKA debet
			$result['total_weekly_tabberjangka_debet_inweek'] = $this->finance_week_model->count_weekly_tabberjangka_debet_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabberjangka_debet_startweek'] = $this->finance_week_model->count_startweekly_tabberjangka_debet_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabberjangka_debet_endweek'] = $result['total_weekly_tabberjangka_debet_inweek'] + $result['total_weekly_tabberjangka_debet_startweek'];
			
			//TAB BERJANGKA credit
			$result['total_weekly_tabberjangka_credit_inweek'] = $this->finance_week_model->count_weekly_tabberjangka_credit_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabberjangka_credit_startweek'] = $this->finance_week_model->count_startweekly_tabberjangka_credit_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabberjangka_credit_endweek'] = $result['total_weekly_tabberjangka_credit_inweek'] + $result['total_weekly_tabberjangka_credit_startweek'];
			
			//TAB BERJANGKA total
			$result['total_weekly_tabberjangka_startweek'] = $result['total_weekly_tabberjangka_debet_startweek'] - $result['total_weekly_tabberjangka_credit_startweek'];
			$result['total_weekly_tabberjangka_inweek'] = $result['total_weekly_tabberjangka_debet_inweek'] - $result['total_weekly_tabberjangka_credit_inweek'];
			$result['total_weekly_tabberjangka_endweek'] = $result['total_weekly_tabberjangka_debet_endweek'] - $result['total_weekly_tabberjangka_credit_endweek'];
			
			//TAB TOTAL
			$result['total_weekly_tabungan_startweek'] = $result['total_weekly_tabsukarela_startweek'] + $result['total_weekly_tabwajib_startweek'] + $result['total_weekly_tabberjangka_startweek'];
			$result['total_weekly_tabungan_inweek'] = $result['total_weekly_tabsukarela_inweek'] + $result['total_weekly_tabwajib_inweek'] + $result['total_weekly_tabberjangka_inweek'];
			$result['total_weekly_tabungan_endweek'] = $result['total_weekly_tabsukarela_endweek'] + $result['total_weekly_tabwajib_endweek'] + $result['total_weekly_tabberjangka_endweek'];
			
			
			$result['total_weekly_pengajuan_orang'] = $this->finance_week_model->count_pengajuan($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_pengajuan_rp'] = $this->finance_week_model->count_total_pengajuan($report_branch,$report_startdate,$report_enddate);
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Progress Report");
			$objPHPExcel->getProperties()->setSubject("Progress Report");
			$objPHPExcel->getProperties()->setDescription("Progress Report");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Progress Report');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Progress Report $branch_name $report_startdate s/d $report_enddate ");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:D4")->applyFromArray(array("font" => array( "bold" => true)));		
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "PARAMETER");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "SEBELUM TANGGAL $report_startdate");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "$report_startdate S/D $report_enddate");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "SAMPAI DENGAN $report_enddate");
			
			
			$cell = 5;
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Anggota");
				$objPHPExcel->getActiveSheet()->getStyle("A$cell:A$cell")->applyFromArray(array("font" => array( "bold" => true)));			
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++; 		
			
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Anggota Aktif");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_clients_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_clients_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_clients_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
							
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Anggota Keluar");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_unreg_clients_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_unreg_clients_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_unreg_clients_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;	
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Majelis");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_majelis_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_majelis_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_majelis_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Desa");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_desa']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Pembiayaan");
				$objPHPExcel->getActiveSheet()->getStyle("A$cell:A$cell")->applyFromArray(array("font" => array( "bold" => true)));			
				$cell++; 	
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Nilai Pembiayaan Disalurkan");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_pembiayaan_disalurkan_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_pembiayaan_disalurkan_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_pembiayaan_disalurkan_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Pengembalian Pembiayaan Pokok");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_pembiayaan_pengembalian_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_pembiayaan_pengembalian_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_pembiayaan_pengembalian_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Pengembalian Pembiayaan Pokok");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_pembiayaan_pengembalian_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_pembiayaan_pengembalian_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_pembiayaan_pengembalian_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Profit/Margin");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_pembiayaan_margin_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_pembiayaan_margin_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_pembiayaan_margin_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Jumlah Peminjam Aktif");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_anggota_aktif_pembiayaan_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_anggota_aktif_pembiayaan_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_anggota_aktif_pembiayaan_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Simpanan");
				$objPHPExcel->getActiveSheet()->getStyle("A$cell:A$cell")->applyFromArray(array("font" => array( "bold" => true)));			
				$cell++; 
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Simpanan Sukarela");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_weekly_tabsukarela_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_weekly_tabsukarela_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_tabsukarela_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "       Masuk");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_weekly_tabsukarela_debet_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_weekly_tabsukarela_debet_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_tabsukarela_debet_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "       Keluar");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_weekly_tabsukarela_credit_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_weekly_tabsukarela_credit_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_tabsukarela_credit_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Simpanan Wajib Kelompok");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_weekly_tabwajib_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_weekly_tabwajib_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_tabwajib_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "       Masuk");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_weekly_tabwajib_debet_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_weekly_tabwajib_debet_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_tabwajib_debet_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "       Keluar");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_weekly_tabwajib_credit_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_weekly_tabwajib_credit_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_tabwajib_credit_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Simpanan Berjangka");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_weekly_tabberjangka_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_weekly_tabberjangka_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_tabberjangka_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "       Masuk");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_weekly_tabberjangka_debet_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_weekly_tabberjangka_debet_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_tabberjangka_debet_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "       Keluar");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_weekly_tabberjangka_credit_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_weekly_tabberjangka_credit_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_tabberjangka_credit_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "JUMLAH SIMPANAN");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $result['total_weekly_tabungan_startweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $result['total_weekly_tabungan_inweek']);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_tabungan_endweek']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;				
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Informasi Lainnya : Minggu Berikutnya");
				$objPHPExcel->getActiveSheet()->getStyle("A$cell:A$cell")->applyFromArray(array("font" => array( "bold" => true)));			
				$cell++; 
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Pengajuan (Orang)");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_pengajuan_orang']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Pengajuan (Rp)");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_pengajuan_rp']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;	
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Kas Besar");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_kas']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Rekening RF");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_rf']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "Rekening Amanah");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $result['total_weekly_amanah']);				
				$objPHPExcel->getActiveSheet()->getStyle("B$cell:D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$cell++;
				
				
			//Set Column Auto Width
			foreach(range('A','D') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			//EXPORT	
			$filename = "Progress_Report_".$date."_" . time() . '.xls'; //save our workbook as this file name 
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			
			
			//Build
			$this->template	->set('menu_title', 'Finance Report')
							->set('menu_report', 'active')
							->set('result', $result)
							->set('branch_name', $branch_name)
							->set('branch', $report_branch)
							->set('start_date', $report_startdate)
							->set('end_date', $report_enddate)
							->build('finance/weekly_report');
	}
	
	public function dashboard_week(){
			$timestamp = date('Y-m-d H:i:s');
			
			$report_branch = "0";
			$branch = $this->branch_model->get_branch($report_branch)->result(); 
			$result['cabang'] = $branch[0]->branch_name;
			if($report_branch  == 0){$result['cabang'] = "Pusat";}
			$report_startdate = $this->input->post('startdate');
			$report_enddate = $this->input->post('enddate');
			if($report_startdate=="" AND $report_enddate==""){
				$day = date('w');
				$date_now = date('Y-m-d');
				$date_month  = date('m');
				$date_year   = date('Y');
				$date_start  = date("Y-m-d", strtotime('-'.$day.' days'));
				$report_startdate  = date("Y-m-d", strtotime($date_start . ' + 1 day'));
				$report_enddate    = date("Y-m-d", strtotime($date_start . ' + 7 day'));					
			}
			
			//ANGGOTA AKTIF
			$total_clients=$this->finance_week_model->count_clients_by_branch($report_branch);
			$result['total_clients_inweek'] =  $this->finance_week_model->count_weeklyclients_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_clients_startweek'] =  $this->finance_week_model->count_startweekclients_by_branch($report_branch,$report_startdate);
			$result['total_clients_endweek'] = $result['total_clients_inweek'] + $result['total_clients_startweek'];
			
			//ANGGOTA KELUAR
			$result['total_unreg_clients_inweek'] = $this->finance_week_model->count_weekly_unregclients_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_unreg_clients_startweek'] = $this->finance_week_model->count_startweekly_unregclients_by_branch($report_branch,$report_startdate);
			$result['total_unreg_clients_endweek'] = $result['total_unreg_clients_inweek'] + $result['total_unreg_clients_startweek'];
			
			//MAJELIS
			$result['total_majelis_inweek'] = $this->finance_week_model->count_weekly_majelis_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_majelis_startweek'] = $this->finance_week_model->count_startweekly_majelis_by_branch($report_branch,$report_startdate);
			$result['total_majelis_endweek'] = $result['total_majelis_inweek'] + $result['total_majelis_startweek'];
			
			//DESA
			$result['total_desa'] = $this->finance_week_model->count_desa_by_branch($report_branch);
			
			//PEMBIAYAAN
			$result['total_pembiayaan_disalurkan_inweek'] = $this->finance_week_model->count_total_pembiayaan_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_pembiayaan_disalurkan_startweek'] = $this->finance_week_model->count_total_startpembiayaan_by_branch($report_branch,$report_startdate);
			$result['total_pembiayaan_disalurkan_endweek'] = $result['total_pembiayaan_disalurkan_inweek'] + $result['total_pembiayaan_disalurkan_startweek'];
			$result['total_pembiayaan_disalurkan_endweek'] =  $this->finance_week_model->sum_pembiayaan_aktif($report_branch);
			
			//MARGIN
			$result['total_pembiayaan_margin_inweek'] = $this->finance_week_model->count_total_margin_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_pembiayaan_margin_startweek'] = $this->finance_week_model->count_total_startmargin_by_branch($report_branch,$report_startdate);
			$result['total_pembiayaan_margin_endweek'] = $result['total_pembiayaan_margin_inweek'] + $result['total_pembiayaan_margin_startweek'];
			
			//PENGEMBALIAN POKOK
			$result['total_pembiayaan_pengembalian_inweek'] = $this->finance_week_model->count_total_pengembalian_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_pembiayaan_pengembalian_startweek'] = $this->finance_week_model->count_total_startpengembalian_by_branch($report_branch,$report_startdate);
			$result['total_pembiayaan_pengembalian_endweek'] = $result['total_pembiayaan_pengembalian_inweek'] + $result['total_pembiayaan_pengembalian_startweek'];
			
			//ANGGOTA AKTIF PEMBIAYAAN
			$result['total_anggota_aktif_pembiayaan_inweek'] = $this->finance_week_model->count_weekly_clients_aktif_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_anggota_aktif_pembiayaan_startweek'] = $this->finance_week_model->count_startweekly_clients_aktif_by_branch($report_branch,$report_startdate);
			$result['total_anggota_aktif_pembiayaan_endweek'] = $result['total_anggota_aktif_pembiayaan_inweek'] + $result['total_anggota_aktif_pembiayaan_startweek'];
			
			
			//TAB SUKARELA
			//TAB SUKARELA debet
			$result['total_weekly_tabsukarela_debet_inweek'] = $this->finance_week_model->count_weekly_tabsukarela_debet_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabsukarela_debet_startweek'] = $this->finance_week_model->count_startweekly_tabsukarela_debet_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabsukarela_debet_endweek'] = $result['total_weekly_tabsukarela_debet_inweek'] + $result['total_weekly_tabsukarela_debet_startweek'];
			
			//TAB SUKARELA credit
			$result['total_weekly_tabsukarela_credit_inweek'] = $this->finance_week_model->count_weekly_tabsukarela_credit_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabsukarela_credit_startweek'] = $this->finance_week_model->count_startweekly_tabsukarela_credit_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabsukarela_credit_endweek'] = $result['total_weekly_tabsukarela_credit_inweek'] + $result['total_weekly_tabsukarela_credit_startweek'];
			
			//TAB SUKARELA total
			$result['total_weekly_tabsukarela_startweek'] = $result['total_weekly_tabsukarela_debet_startweek'] - $result['total_weekly_tabsukarela_credit_startweek'];
			$result['total_weekly_tabsukarela_inweek'] = $result['total_weekly_tabsukarela_debet_inweek'] - $result['total_weekly_tabsukarela_credit_inweek'];
			$result['total_weekly_tabsukarela_endweek'] = $result['total_weekly_tabsukarela_debet_endweek'] - $result['total_weekly_tabsukarela_credit_endweek'];
			
			
			//TAB WAJIB
			//TAB WAJIB debet
			$result['total_weekly_tabwajib_debet_inweek'] = $this->finance_week_model->count_weekly_tabwajib_debet_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabwajib_debet_startweek'] = $this->finance_week_model->count_startweekly_tabwajib_debet_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabwajib_debet_endweek'] = $result['total_weekly_tabwajib_debet_inweek'] + $result['total_weekly_tabwajib_debet_startweek'];
			
			//TAB WAJIB credit
			$result['total_weekly_tabwajib_credit_inweek'] = $this->finance_week_model->count_weekly_tabwajib_credit_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabwajib_credit_startweek'] = $this->finance_week_model->count_startweekly_tabwajib_credit_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabwajib_credit_endweek'] = $result['total_weekly_tabwajib_credit_inweek'] + $result['total_weekly_tabwajib_credit_startweek'];
			
			//TAB WAJIB total
			$result['total_weekly_tabwajib_startweek'] = $result['total_weekly_tabwajib_debet_startweek'] - $result['total_weekly_tabwajib_credit_startweek'];
			$result['total_weekly_tabwajib_inweek'] = $result['total_weekly_tabwajib_debet_inweek'] - $result['total_weekly_tabwajib_credit_inweek'];
			$result['total_weekly_tabwajib_endweek'] = $result['total_weekly_tabwajib_debet_endweek'] - $result['total_weekly_tabwajib_credit_endweek'];
			
			
			//TAB BERJANGKA
			//TAB BERJANGKA debet
			$result['total_weekly_tabberjangka_debet_inweek'] = $this->finance_week_model->count_weekly_tabberjangka_debet_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabberjangka_debet_startweek'] = $this->finance_week_model->count_startweekly_tabberjangka_debet_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabberjangka_debet_endweek'] = $result['total_weekly_tabberjangka_debet_inweek'] + $result['total_weekly_tabberjangka_debet_startweek'];
			
			//TAB BERJANGKA credit
			$result['total_weekly_tabberjangka_credit_inweek'] = $this->finance_week_model->count_weekly_tabberjangka_credit_by_branch($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_tabberjangka_credit_startweek'] = $this->finance_week_model->count_startweekly_tabberjangka_credit_by_branch($report_branch,$report_startdate);
			$result['total_weekly_tabberjangka_credit_endweek'] = $result['total_weekly_tabberjangka_credit_inweek'] + $result['total_weekly_tabberjangka_credit_startweek'];
			
			//TAB BERJANGKA total
			$result['total_weekly_tabberjangka_startweek'] = $result['total_weekly_tabberjangka_debet_startweek'] - $result['total_weekly_tabberjangka_credit_startweek'];
			$result['total_weekly_tabberjangka_inweek'] = $result['total_weekly_tabberjangka_debet_inweek'] - $result['total_weekly_tabberjangka_credit_inweek'];
			$result['total_weekly_tabberjangka_endweek'] = $result['total_weekly_tabberjangka_debet_endweek'] - $result['total_weekly_tabberjangka_credit_endweek'];
			
			//TAB TOTAL
			$result['total_weekly_tabungan_startweek'] = $result['total_weekly_tabsukarela_startweek'] + $result['total_weekly_tabwajib_startweek'] + $result['total_weekly_tabberjangka_startweek'];
			$result['total_weekly_tabungan_inweek'] = $result['total_weekly_tabsukarela_inweek'] + $result['total_weekly_tabwajib_inweek'] + $result['total_weekly_tabberjangka_inweek'];
			$result['total_weekly_tabungan_endweek'] = $result['total_weekly_tabsukarela_endweek'] + $result['total_weekly_tabwajib_endweek'] + $result['total_weekly_tabberjangka_endweek'];
			
			
			$result['total_weekly_pengajuan_orang'] = $this->finance_week_model->count_pengajuan($report_branch,$report_startdate,$report_enddate);
			$result['total_weekly_pengajuan_rp'] = $this->finance_week_model->count_total_pengajuan($report_branch,$report_startdate,$report_enddate);
			
			//echo number_format($result['total_weekly_tabberjangka_debet_inweek']);
			
			//Build
			$this->template	->set('menu_title', 'Weekly Progress')
							->set('menu_dashboard', 'active')
							->set('result', $result)
							->set('branch_name', $branch_name)
							->set('branch', $report_branch)
							->set('start_date', $report_startdate)
							->set('end_date', $report_enddate)
							->build('finance/dashboard_week');
	}
	
}