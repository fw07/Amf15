<?php

class Finance_progress extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Finance_progress';
	private $module 	= 'finance_progress';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('branch_model');
	
	}
	
		
	public function summary(){
	
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			$branch = $this->input->post('branch');	
			
			$date =  $this->uri->segment(3);
			if(!$date){ $date = date("Y-m"); }
			else{ $date =  $this->uri->segment(3); }
			
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Progress Report");
			$objPHPExcel->getProperties()->setSubject("Progress Report");
			$objPHPExcel->getProperties()->setDescription("Progress Report");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Summary');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Progress Report $branch");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:Q4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("N4:O4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "Ringkasan");
			$objPHPExcel->getActiveSheet()->setCellValue("A5", "Jumlah Cabang");
			$objPHPExcel->getActiveSheet()->setCellValue("A6", "Jumlah Desa");
			$objPHPExcel->getActiveSheet()->setCellValue("A8", "Jumlah Anggota");
			$objPHPExcel->getActiveSheet()->setCellValue("A9", "Jumlah Anggota Menerima Pembiayaan");
			$objPHPExcel->getActiveSheet()->setCellValue("A10", "Presentase Pertumbuhan Anggota");
			$objPHPExcel->getActiveSheet()->setCellValue("A11", "Jumlah Majelis");
			$objPHPExcel->getActiveSheet()->setCellValue("A13", "Pembiayaan Disalurkan");
			$objPHPExcel->getActiveSheet()->setCellValue("A14", "Saldo Portfolio");
			$objPHPExcel->getActiveSheet()->setCellValue("A15", "Rataan Saldo Portofolio/Anggota");
			$objPHPExcel->getActiveSheet()->setCellValue("A17", "Jumlah Anggota Aktif Menabung");
			$objPHPExcel->getActiveSheet()->setCellValue("A18", "Portofolio Tabungan Sukarela");
			$objPHPExcel->getActiveSheet()->setCellValue("A20", "Portofolio Beresiko (>30 hari)");
			$objPHPExcel->getActiveSheet()->setCellValue("A21", "Presentase Anggota Perempuan");
			$objPHPExcel->getActiveSheet()->setCellValue("A22", "Jumlah Karyawan");
			
			
			
			
			
			$no=1;
			$cell = 5;
			
			//Set Column Auto Width
			foreach(range('A','Q') as $columnID) {
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
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}

}