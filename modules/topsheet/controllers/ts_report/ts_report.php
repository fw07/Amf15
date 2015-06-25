<?php

class Ts_report extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Topsheet';
	private $module 	= 'topsheet';
	
	
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
	
	
	public function tsdaily_report($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			
			//FILTER DATE
			$date_start = $this->input->post('date_start');
			$date_end = $this->input->post('date_end');
			if($date_start AND $date_end){ 
				$date_start = $this->input->post('date_start');
				$date_end = $this->input->post('date_end');
			}else{
				$date = date("Y-m-d");	
				$date_day = date('l',strtotime($date));	
				if($date_day == "Monday"){
					function week_range($date) {
						$ts = strtotime($date);
						$start = strtotime("-7 day", $ts);
						//$start = date('Y-m-d', $start);	echo $start;
						return array(date('Y-m-d', $start),
									 date('Y-m-d', strtotime('next saturday', $start)));
					}
					list($date_start, $date_end) = week_range($date);
				}else{
					function week_range($date) {
						$ts = strtotime($date);
						$start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
						return array(date('Y-m-d', $start),
									 date('Y-m-d', strtotime('next saturday', $start)));
					}
					list($date_start, $date_end) = week_range($date);
				}
			}

			/*
			$total_rows = $this->tsdaily_model->count_all_daily_report($this->input->get('q'), $user_branch);
			
			//pagination
			$config['base_url']     = site_url($this->module.'/tsdaily_report');
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 15; 
			$config['uri_segment']  = 3;
			$config['suffix'] 		= '?' . http_build_query($_GET, '', "&");
			$config['first_url'] 	= $config['base_url'] . $config['suffix'];
			$config['num_links'] = 2;
			$config['full_tag_open'] = '<li>';
			$config['full_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li><a href="#"><b>';
			$config['cur_tag_close'] = '</b></a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			
			$this->pagination->initialize($config);
			$no =  $this->uri->segment(3);
			
			//$tsdaily = $this->tsdaily_model->get_all_daily_report( $config['per_page'] , $page, $this->input->get('q'), $user_branch);
			*/
			if($user_branch == 0){ $user_branch = ""; }			
			
			$total_rows = $this->tsdaily_model->count_all_daily_report_by_date($user_branch,$date_start,$date_end);
			$tsdaily = $this->tsdaily_model->get_all_daily_report_by_date($user_branch,$date_start,$date_end);	
			
			$this->template	->set('menu_title', 'Rekap Harian')
							->set('menu_transaksi', 'active')
							->set('total_rows',$total_rows)
							->set('tsdaily', $tsdaily)
							->set('no', $no)
							->set('date_start', $date_start)
							->set('date_end', $date_end)
							->build('tsdaily_report_by_date'); 
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function tsdaily_report_download(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			$branch_name = str_replace(' ', '', $this->session->userdata('user_branch_name'));
			
			//FILTER DATE
			$date_start = $this->input->post('date_start');
			$date_end = $this->input->post('date_end');
			if($date_start AND $date_end){ 
				$date_start = $this->input->post('date_start');
				$date_end = $this->input->post('date_end');
			}else{
				$date = date("Y-m-d");	
				$date_day = date('l',strtotime($date));	
				if($date_day == "Monday"){
					function week_range($date) {
						$ts = strtotime($date);
						$start = strtotime("-7 day", $ts);
						//$start = date('Y-m-d', $start);	echo $start;
						return array(date('Y-m-d', $start),
									 date('Y-m-d', strtotime('next saturday', $start)));
					}
					list($date_start, $date_end) = week_range($date);
				}else{
					function week_range($date) {
						$ts = strtotime($date);
						$start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
						return array(date('Y-m-d', $start),
									 date('Y-m-d', strtotime('next saturday', $start)));
					}
					list($date_start, $date_end) = week_range($date);
				}
			}

			
			if($user_branch == 0){ $user_branch = ""; }
			$tsdaily = $this->tsdaily_model->get_all_daily_report_by_date($user_branch,$date_start,$date_end);			
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Rekap Harian");
			$objPHPExcel->getProperties()->setSubject("Rekap Harian");
			$objPHPExcel->getProperties()->setDescription("Rekap Harian");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Rekap Harian');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Cabang $branch_name");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:O1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:O2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:Q5")->applyFromArray(array("font" => array( "bold" => true)));	
			$objPHPExcel->getActiveSheet()->getStyle("E4:O5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
			$objPHPExcel->getActiveSheet()->getStyle("A4:D5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);		
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
				$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(4);
				$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "TS CODE");
				$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
				$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "TANGGAL");
				$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
				$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "HARI");
				$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(20);
				$objPHPExcel->getActiveSheet()->mergeCells("D4:D5");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "ANGSURAN POKOK");
				$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(22);
				$objPHPExcel->getActiveSheet()->mergeCells("E4:E5");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "PROFIT");
				$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(20);
				$objPHPExcel->getActiveSheet()->mergeCells("F4:F5");
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "TAB WAJIB");
				$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(15);
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TAB SUKARELA");
				$objPHPExcel->getActiveSheet()->mergeCells("H4:I4");
				$objPHPExcel->getActiveSheet()->setCellValue("H5", "DEBET");
				$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(15);
				$objPHPExcel->getActiveSheet()->setCellValue("I5", "KREDIT");
				$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(15);
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "TAB BERJANGKA");
				$objPHPExcel->getActiveSheet()->mergeCells("J4:K4");
				$objPHPExcel->getActiveSheet()->setCellValue("J5", "DEBET");
				$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(15);
				$objPHPExcel->getActiveSheet()->setCellValue("K5", "KREDIT");
				$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(15);
			$objPHPExcel->getActiveSheet()->setCellValue("L4", "TOTAL RF");
				$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(15);
				$objPHPExcel->getActiveSheet()->mergeCells("L4:L5");
			$objPHPExcel->getActiveSheet()->setCellValue("M4", "TOTAL TABUNGAN");				
				$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(17);
				$objPHPExcel->getActiveSheet()->mergeCells("M4:M5");
			$objPHPExcel->getActiveSheet()->setCellValue("N4", "GRAND TOTAL");			
				$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(17);
				$objPHPExcel->getActiveSheet()->mergeCells("N4:N5"); 
				
				
			$no =1;
			$cell_no = 6;
			foreach($tsdaily as $c):
				$date = "$c->tsdaily_date"; 						
				$day = date('l', strtotime($date));
				if($day == "Sunday"){ $day = "Minggu"; }
				elseif($day == "Monday"){ $day = "Senin"; }
				elseif($day == "Tuesday"){ $day = "Selasa"; }
				elseif($day == "Wednesday"){ $day = "Rabu"; }
				elseif($day == "Thursday"){ $day = "Kamis"; }
				elseif($day == "Friday"){ $day = "Jumat"; }
				elseif($day == "Saturday"){ $day = "Sabtu"; }
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell_no", $no);
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell_no", "TS".$c->tsdaily_topsheet_code);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell_no", $c->tsdaily_date);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell_no", $day);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell_no", $c->total_angsuranpokok);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell_no", $c->total_profit);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell_no", $c->total_tabwajib);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell_no", $c->total_tabungan_debet);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell_no", $c->total_tabungan_credit);
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell_no", $c->total_tabungan_berjangka_debet);
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell_no", $c->total_tabungan_berjangka_credit);
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell_no", $c->total_total_rf);
				$objPHPExcel->getActiveSheet()->setCellValue("M$cell_no", $c->total_total_tabungan);
				$objPHPExcel->getActiveSheet()->setCellValue("N$cell_no", ($c->total_total_tabungan + $c->total_total_rf));
				
				$cell_no++;$no++; 
			endforeach;
			
			$objPHPExcel->getActiveSheet()->getStyle("E6:O$cell_no")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_ACCOUNTING);
			
			
			//Set Column Auto Width
			foreach(range('B','J') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			//EXPORT	
			$filename = "Rekap_Harian_".$branch_name."_" . time() . '.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			
			$this->template	->set('menu_title', 'Rekap Harian')
							->set('menu_transaksi', 'active')
							->set('group_total',$config['total_rows'])
							->set('tsdaily', $tsdaily)
							->set('no', $no)
							->set('config', $config)
							->build('tsdaily_report');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
}