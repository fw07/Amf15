<?php

class Insentif extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Insentif';
	private $module 	= 'insentif';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('officer_model');
		$this->load->model('branch_model');
		$this->load->model('insentif_model');
		$this->load->library('pagination');	
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			$total_rows = $this->officer_model->count_all($this->input->post('q'));				
			$officer = $this->officer_model->get_all_officer();	
			
			
					
			$this->template	->set('menu_title', 'Laporan Kinerja FO')
							->set('menu_konsolidasi', 'active')
							->set('officer_total',$config['total_rows'])
							->set('officer', $officer)
							->build('officer');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function download(){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			$officer = $this->officer_model->get_all_officer();	
			
			
			$date =  $this->uri->segment(3);
			if(!$date){ $date = date("Y-m"); }
			else{ $date =  $this->uri->segment(3); }
			
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Laporan Kinerja FO");
			$objPHPExcel->getProperties()->setSubject("Laporan Kinerja FO");
			$objPHPExcel->getProperties()->setDescription("Laporan Kinerja FO");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Laporan Kinerja FO');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Laporan Kinerja FO");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:L4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("E4:L4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "NAMA");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "NO PEGAWAI");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "CABANG");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "TRANSAKSI");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "ANGGOTA");
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "MAJELIS");
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "PEMBIAYAAN AKTIF");
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "OUTSTANDING (SALDO)");
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "PENABUNG TAB.SUKARELA");
			$objPHPExcel->getActiveSheet()->setCellValue("K4", "P A R");
			$objPHPExcel->getActiveSheet()->setCellValue("L4", "P A R (SALDO)");
			$objPHPExcel->getActiveSheet()->setCellValue("M4", "TAB. WAJIB (TRANSAKSI)");
			$objPHPExcel->getActiveSheet()->setCellValue("N4", "TAB. SUKARELA (SALDO)");
			
			
			$no=1;
			$cell = 5;
			foreach($officer as $c): 
				$total_transaksi_per_officer = $this->insentif_model->count_transaksi($c->officer_id, $date);
				$total_anggota_per_officer = $this->insentif_model->count_anggota($c->officer_id, $date);
				$total_majelis_per_officer = $this->insentif_model->count_majelis($c->officer_id, $date);	
				$total_pembiayaan_per_officer = $this->insentif_model->count_pembiayaan($c->officer_id, $date);
				$total_tabsukarela_per_officer = $this->insentif_model->count_tabsukarela($c->officer_id, $date);	
				$total_par_per_officer = $this->insentif_model->count_par($c->officer_id, $date);
				//$total_tabwajib_debet_per_officer = $this->insentif_model->count_tabwajib_debet($c->officer_id, $date);
				//$total_tabwajib_credit_per_officer = $this->insentif_model->count_tabwajib_credit($c->officer_id, $date);
				//$total_tabwajib_saldo_per_officer = $total_tabwajib_debet_per_officer - $total_tabwajib_kredit_per_officer;
				$total_tabsukarela_saldo_per_officer = $this->insentif_model->count_tabsukarela_saldo($c->officer_id);
				$total_tabwajib_saldo_per_officer = $this->insentif_model->count_tabwajib_saldo($c->officer_id);
						
				$total_pembiayaan_saldo_per_officer = $this->insentif_model->count_pembiayaan_saldo($c->officer_id);
				$total_par_saldo_per_officer = $this->insentif_model->sum_par($c->officer_id, $date);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->officer_name);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->officer_number);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->branch_name);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $total_transaksi_per_officer);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $total_anggota_per_officer);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $total_majelis_per_officer);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $total_pembiayaan_per_officer);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", $total_pembiayaan_saldo_per_officer);
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell", $total_tabsukarela_per_officer);
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell", $total_par_per_officer);
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell", $total_par_saldo_per_officer);
				$objPHPExcel->getActiveSheet()->setCellValue("M$cell", $total_tabwajib_saldo_per_officer);
				$objPHPExcel->getActiveSheet()->setCellValue("N$cell", $total_tabsukarela_saldo_per_officer);
			
				$no++;
				$cell++; 
			endforeach;			
			
			
			//Set Column Auto Width
			foreach(range('A','k') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			//EXPORT	
			$filename = "Laporan_Kinerja_FO_".$date."_" . time() . '.xls'; //save our workbook as this file name
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