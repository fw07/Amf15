<?php

class Audit extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Audit';
	private $module 	= 'audit';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('group_model');
		$this->load->model('officer_model');
		$this->load->model('area_model');
		$this->load->model('branch_model');
		$this->load->model('report_model');
		$this->load->model('audit_model');
		$this->load->library('pagination');	
	
	}
	

	public function index($page='0'){
		$user_level = $this->session->userdata('user_level');
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			$branch = $this->branch_model->get_all()->result();
			
			//Build
			$this->template	->set('menu_title', 'Audit Report')
							->set('menu_report', 'active')
							->set('branch', $branch)
							->build('audit_browse');
			
		}else{
			 redirect('login', 'refresh');
		}
	}
	public function cek($page='0'){
		$user_level = $this->session->userdata('user_level');
		if($this->session->userdata('logged_in'))
		{
			echo "ok";
			//Build
			$this->template	->set('menu_title', 'Audit Report')
							->set('menu_report', 'active')
							->set('branch', $branch)
							->build('audit_browse');
			
		}else{
			 redirect('login', 'refresh');
		}
	}
	public function browse(){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			
			//Build
			$this->template	->set('menu_title', 'Laporan Mingguan')
							->set('menu_branch', 'active')
							->set('group_total',$config['total_rows'])
							->set('report', $report)
							->set('no', $no)
							->set('config', $config)
							->build('report');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function anggota_masuk(){
		if($this->session->userdata('logged_in'))
		{
			//GET POST DATA
			$branch = $this->input->post('branch');	
			$start_date = $this->input->post('startdate');
			$end_date = $this->input->post('enddate');
			
			//GET BRANCH DETAIL
			$branch_detail = $this->branch_model->get_branch($branch)->result();	
			$branch_name = $branch_detail[0]->branch_name;
			
			$client = $this->audit_model->get_new_client($branch,$start_date,$end_date)->result();
			
			
			//Build
			$this->template	->set('menu_title', 'Laporan Anggota Masuk')
							->set('menu_report', 'active')
							->set('client', $client)
							->set('branch', $branch)
							->set('branch_name', $branch_name)
							->set('start_date', $start_date)
							->set('end_date', $end_date)
							->build("audit/anggota_masuk");
		}else{
			echo "Forbidden";
		}
			
	}
	public function anggota_masuk_download(){
		echo "ok";
		if($this->session->userdata('logged_in'))
		{
			//GET POST DATA
			$branch = $this->input->post('branch');	
			$start_date = $this->input->post('startdate');
			$end_date = $this->input->post('enddate');
			
			//GET BRANCH DETAIL
			$branch_detail = $this->branch_model->get_branch($branch)->result();	
			$branch_name = $branch_detail[0]->branch_name;
			
			$client = $this->audit_model->get_new_client($branch,$start_date,$end_date)->result();
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Laporan Anggota Masuk");
			$objPHPExcel->getProperties()->setSubject("Laporan Anggota Masuk");
			$objPHPExcel->getProperties()->setDescription("Laporan Anggota Masuk");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Laporan Anggota Masuk');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Laporan Anggota Masuk $branch_name");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:Q4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("F4:F4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "CABANG");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "MAJELIS");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "NO REKENING");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "NAMA ANGGOTA");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "JUMLAH PINJAMAN");
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "PETUGAS UPK");
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TGL UPK");
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "TGL REALISASI");
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "STATUS");
			
			
			$no=1;
			$cell = 5;
			foreach($client as $c): 
				if($c->data_status == "1"){ $status="Berjalan";}elseif($c->data_status == "2"){  $status="Pengajuan"; }else{  $status="-"; };
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->branch_name);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->group_name);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->client_account);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->client_fullname);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->data_plafond);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->officer_name);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $c->client_reg_date);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", $c->data_date_accept);
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell", $c->Pembiayaan_Ke);
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell", $status);
				
				$objPHPExcel->getActiveSheet()->getStyle("F$cell:F$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$no++;
				$cell++; 
			endforeach;			
			
			
			//Set Column Auto Width
			foreach(range('A','J') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			//EXPORT	
			$filename = "Laporan_Anggota_Masuk_".$date."_" . time() . '.xls'; //save our workbook as this file name 
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			
			
			
			//Build
			$this->template	->set('menu_title', 'Laporan Anggota Masuk')
							->set('menu_report', 'active')
							->set('client', $client)
							->set('branch', $branch)
							->set('branch_name', $branch_name)
							->set('start_date', $start_date)
							->set('end_date', $end_date)
							->build("audit/anggota_masuk");
		}else{
			echo "Forbidden";
		}
			
	}
	
	public function anggota_keluar(){
		if($this->session->userdata('logged_in'))
		{
			//GET POST DATA
			$branch = $this->input->post('branch');	
			$start_date = $this->input->post('startdate');
			$end_date = $this->input->post('enddate');
			
			//GET BRANCH DETAIL
			$branch_detail = $this->branch_model->get_branch($branch)->result();	
			$branch_name = $branch_detail[0]->branch_name;
			
			$client = $this->audit_model->get_new_client_unreg($branch,$start_date,$end_date)->result();
			
			
			
			//Build
			$this->template	->set('menu_title', 'Laporan Anggota Keluar')
							->set('menu_report', 'active')
							->set('client', $client)
							->set('branch', $branch)
							->set('branch_name', $branch_name)
							->set('start_date', $start_date)
							->set('end_date', $end_date)
							->build("audit/anggota_keluar");
		}else{
			echo "Forbidden";
		}
			
	}
	
	public function anggota_keluar_download(){
		if($this->session->userdata('logged_in'))
		{
			//GET POST DATA
			$branch = $this->input->post('branch');	
			$start_date = $this->input->post('startdate');
			$end_date = $this->input->post('enddate');
			
			//GET BRANCH DETAIL
			$branch_detail = $this->branch_model->get_branch($branch)->result();	
			$branch_name = $branch_detail[0]->branch_name;
			
			$client = $this->audit_model->get_new_client_unreg($branch,$start_date,$end_date)->result();
			
						//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Laporan Anggota Keluar");
			$objPHPExcel->getProperties()->setSubject("Laporan Anggota Keluar");
			$objPHPExcel->getProperties()->setDescription("Laporan Anggota Keluar");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Laporan Anggota Keluar');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Laporan Anggota Keluar $branch_name");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:Q4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("J4:K4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "CABANG");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "MAJELIS");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "NO REKENING");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "NAMA ANGGOTA");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "KE");
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "TGL REALISASI");
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TGL LUNAS");
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "TGL KELUAR");
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "JUMLAH PINJAMAN");
			$objPHPExcel->getActiveSheet()->setCellValue("K4", "JUMLAH ANGSURAN");
			$objPHPExcel->getActiveSheet()->setCellValue("L4", "STATUS");
			
			
			$no=1;
			$cell = 5;
			foreach($client as $c): 
				if($c->data_status == "1"){ $status="Berjalan";}elseif($c->data_status == "2"){  $status="Pengajuan"; }elseif($c->data_status == "3"){ echo "Selesai"; }else{  $status="-"; };
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->branch_name);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->group_name);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->client_account);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->client_fullname);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->data_ke);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->data_date_accept);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $c->data_jatuhtempo);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", $c->client_unreg_date);
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell", number_format($c->data_plafond));
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell", number_format($c->data_plafond/50 * $c->data_angsuranke));
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell", $status);
				
				$objPHPExcel->getActiveSheet()->getStyle("J$cell:K$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$no++;
				$cell++; 
			endforeach;			
			
			
			//Set Column Auto Width
			foreach(range('A','K') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			//EXPORT	
			$filename = "Laporan_Anggota_Keluar_".$date."_" . time() . '.xls'; //save our workbook as this file name 
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			
			
			//Build
			$this->template	->set('menu_title', 'Laporan Anggota Keluar')
							->set('menu_report', 'active')
							->set('client', $client)
							->set('branch', $branch)
							->set('branch_name', $branch_name)
							->set('start_date', $start_date)
							->set('end_date', $end_date)
							->build("audit/anggota_keluar");
		}else{
			echo "Forbidden";
		}
			
	}
	
		
	public function laporan_nominatif(){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			$branch = $this->input->post('branch');
			$regpyd = $this->audit_model->get_pyd_data($branch);	
			
			$date =  $this->uri->segment(3);
			if(!$date){ $date = date("Y-m"); }
			else{ $date =  $this->uri->segment(3); }
			
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Laporan Nominatif");
			$objPHPExcel->getProperties()->setSubject("Laporan Nominatif");
			$objPHPExcel->getProperties()->setDescription("Laporan Nominatif");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Laporan Nominatif');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Laporan Nominatif $branch");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:Q4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("F4:G4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("L4:L4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("N4:O4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "CABANG");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "MAJELIS");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "NO REKENING");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "NAMA ANGGOTA");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "PLAFOND");
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "PROFIT");
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TGL PENCAIRAN");
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "TGL JATUH TEMPO");
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "PEMBIAYAAN KE");
			$objPHPExcel->getActiveSheet()->setCellValue("K4", "ANGSURAN KE");
			$objPHPExcel->getActiveSheet()->setCellValue("L4", "SISA POKOK");
			$objPHPExcel->getActiveSheet()->setCellValue("M4", "PAR");
			$objPHPExcel->getActiveSheet()->setCellValue("N4", "TR");
			$objPHPExcel->getActiveSheet()->setCellValue("O4", "AKAD");
			$objPHPExcel->getActiveSheet()->setCellValue("P4", "TAB. WAJIB");
			$objPHPExcel->getActiveSheet()->setCellValue("Q4", "TAB. SUKARELA");
			
			
			$no=1;
			$cell = 5;
			foreach($regpyd as $c): 
								
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->Cabang);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->Majelis);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->Nomor_Rekening);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->Nama);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->Plafond);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->Profit);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $c->Tgl_pencairan);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", $c->Tgl_jatuh_tempo);
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell", $c->Pembiayaan_Ke);
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell", $c->Angsuran_Ke);
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell", $c->sisa_pokok);
				$objPHPExcel->getActiveSheet()->setCellValue("M$cell", $c->Par);
				$objPHPExcel->getActiveSheet()->setCellValue("N$cell", $c->TR);
				$objPHPExcel->getActiveSheet()->setCellValue("O$cell", $c->Akad);
				$objPHPExcel->getActiveSheet()->setCellValue("P$cell", $c->Tab_Wajib);
				$objPHPExcel->getActiveSheet()->setCellValue("Q$cell", $c->Tab_Sukarela);
				
				$objPHPExcel->getActiveSheet()->getStyle("F$cell:G$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("L$cell:L$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("P$cell:Q$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$no++;
				$cell++; 
			endforeach;			
			
			
			//Set Column Auto Width
			foreach(range('A','Q') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			//EXPORT	
			$filename = "Laporan_Nominatif_".$date."_" . time() . '.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}

	public function laporan_nominatif_date(){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			$branch = $this->input->post('branch');
			$start_date = $this->input->post('startdate');
			$end_date = $this->input->post('enddate');
			
			 
			$regpyd = $this->audit_model->get_pyd_data_by_branch_by_date($branch,$start_date,$end_date);	
			
			//$date =  date('Y-m-d');
			
			//GET BRANCH DETAIL
			$branch_detail = $this->branch_model->get_branch($branch)->result();	
			$branch_name = $branch_detail[0]->branch_name;
			
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Laporan Nominatif");
			$objPHPExcel->getProperties()->setSubject("Laporan Nominatif");
			$objPHPExcel->getProperties()->setDescription("Laporan Nominatif");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Laporan Nominatif');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Laporan Nominatif $branch_name ($start_date s/d $end_date)");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:Q4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("F4:G4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("L4:L4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("N4:O4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "CABANG");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "MAJELIS");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "NO REKENING");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "NAMA ANGGOTA");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "PLAFOND");
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "PROFIT");
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TGL PENCAIRAN");
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "TGL JATUH TEMPO");
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "PEMBIAYAAN KE");
			$objPHPExcel->getActiveSheet()->setCellValue("K4", "ANGSURAN KE");
			$objPHPExcel->getActiveSheet()->setCellValue("L4", "SISA POKOK");
			$objPHPExcel->getActiveSheet()->setCellValue("M4", "PAR");
			$objPHPExcel->getActiveSheet()->setCellValue("N4", "TR");
			$objPHPExcel->getActiveSheet()->setCellValue("O4", "AKAD");
			
			
			$no=1;
			$cell = 5;
			
			foreach($regpyd as $c): 
				$sisa_pokok = (50-$c->tr_angsuranke) * ($c->data_plafond/50);
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->branch_name);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->group_name);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->client_account);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->client_fullname);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->data_plafond);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->data_margin);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $c->data_date_accept);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", $c->data_jatuhtempo);
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell", $c->data_ke);
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell", $c->tr_angsuranke);
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell", $sisa_pokok);
				$objPHPExcel->getActiveSheet()->setCellValue("M$cell", $c->data_par);
				$objPHPExcel->getActiveSheet()->setCellValue("N$cell", $c->tr_tanggungrenteng);
				$objPHPExcel->getActiveSheet()->setCellValue("O$cell", $c->data_akad);
				
				$objPHPExcel->getActiveSheet()->getStyle("F$cell:G$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("L$cell:L$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("P$cell:Q$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$no++;
				$cell++; 
			endforeach;			
			
			
			//Set Column Auto Width
			foreach(range('A','Q') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			/**/
			//EXPORT	 
			$filename = "Laporan_Nominatif_".$branch_name."_".$start_date."_".$end_date.".xls"; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
}