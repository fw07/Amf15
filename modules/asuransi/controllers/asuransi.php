<?php

class Asuransi extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Asuransi';
	private $module 	= 'asuransi';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('asuransi_model');
		$this->load->library('pagination');	
	}
	public function index($page='0'){
		if($this->session->userdata('logged_in'))
		{
			redirect($this->module.'/browse', 'refresh');
		}else{
			redirect('login', 'refresh');
		}
	}
	
	
	public function browse($page='0'){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			$total_rows = $this->asuransi_model->count_all();				
			
			//pagination
			$config['base_url']     = site_url($this->module.'/browse');
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 30; 
			$config['uri_segment']  = 3;
			//$config['suffix'] 		= '?' . http_build_query($_GET, '', "&");
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
			
			$asuransi = $this->asuransi_model->get_all_asuransi($config['per_page'] , $page);	
			
			
					
			$this->template	->set('menu_title', 'Laporan Asuransi')
							->set('menu_konsolidasi', 'active')
							->set('config',$config)
							->set('asuransi', $asuransi)
							->set('no', $no)
							->build('asuransi');
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
			$total_rows = $this->asuransi_model->count_all();				
			$asuransi = $this->asuransi_model->get_all_asuransi($total_rows,0);	
			
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Laporan Asuransi");
			$objPHPExcel->getProperties()->setSubject("Laporan Asuransi");
			$objPHPExcel->getProperties()->setDescription("Laporan Asuransi");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Laporan Asuransi');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Laporan Asuransi");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:P4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("I4:J4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("N4:O4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "CABANG");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "MAJELIS");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "NO REKENING");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "NAMA ANGGOTA");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "NO KTP");
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "ALAMAT");
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TGL LAHIR");
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "PLAFOND");
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "PROFIT");
			$objPHPExcel->getActiveSheet()->setCellValue("K4", "TGL PENCAIRAN");
			$objPHPExcel->getActiveSheet()->setCellValue("L4", "TGL JATUH TEMPO");
			$objPHPExcel->getActiveSheet()->setCellValue("M4", "ANGSURAN KE");
			$objPHPExcel->getActiveSheet()->setCellValue("N4", "SISA POKOK");
			$objPHPExcel->getActiveSheet()->setCellValue("O4", "SISA PROFIT");
			$objPHPExcel->getActiveSheet()->setCellValue("P4", "AKAD");
			
			
			$no=1;
			$cell = 5;
			foreach($asuransi as $c): 
								
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->Cabang);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->Majelis);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->Nomor_Rekening);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->Nama);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->KTP);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->Alamat);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $c->Tgl_Lahir);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", number_format($c->Plafond,0));
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell", number_format($c->Profit,0));
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell", $c->Tgl_pencairan);
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell", $c->Tgl_jatuh_tempo);
				$objPHPExcel->getActiveSheet()->setCellValue("M$cell", $c->Angsuran_Ke);
				$objPHPExcel->getActiveSheet()->setCellValue("N$cell", number_format($c->sisa_pokok,0));
				$objPHPExcel->getActiveSheet()->setCellValue("O$cell", number_format($c->sisa_profit,0));
				$objPHPExcel->getActiveSheet()->setCellValue("P$cell", $c->Akad);
				
				$objPHPExcel->getActiveSheet()->getStyle("I$cell:J$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("N$cell:O$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$no++;
				$cell++; 
			endforeach;			
			
			
			//Set Column Auto Width
			foreach(range('A','P') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			//EXPORT	
			$filename = "Laporan_Asuransi_".$date."_" . time() . '.xls'; //save our workbook as this file name
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
	
	
	
public function kirim(){	

//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			$total_rows = $this->asuransi_model->count_all();				
			$asuransi = $this->asuransi_model->get_all_asuransi($total_rows,0);	
			
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Laporan Asuransi");
			$objPHPExcel->getProperties()->setSubject("Laporan Asuransi");
			$objPHPExcel->getProperties()->setDescription("Laporan Asuransi");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Laporan Asuransi');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Laporan Asuransi");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:P4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("I4:J4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("N4:O4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "CABANG");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "MAJELIS");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "NO REKENING");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "NAMA ANGGOTA");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "NO KTP");
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "ALAMAT");
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TGL LAHIR");
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "PLAFOND");
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "PROFIT");
			$objPHPExcel->getActiveSheet()->setCellValue("K4", "TGL PENCAIRAN");
			$objPHPExcel->getActiveSheet()->setCellValue("L4", "TGL JATUH TEMPO");
			$objPHPExcel->getActiveSheet()->setCellValue("M4", "ANGSURAN KE");
			$objPHPExcel->getActiveSheet()->setCellValue("N4", "SISA POKOK");
			$objPHPExcel->getActiveSheet()->setCellValue("O4", "SISA PROFIT");
			$objPHPExcel->getActiveSheet()->setCellValue("P4", "AKAD");
			
			
			$no=1;
			$cell = 5;
			foreach($asuransi as $c): 
								
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->Cabang);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->Majelis);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->Nomor_Rekening);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->Nama);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->KTP);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->Alamat);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $c->Tgl_lahir);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", number_format($c->Plafond,0));
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell", number_format($c->Profit,0));
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell", $c->Tgl_pencairan);
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell", $c->Tgl_jatuh_tempo);
				$objPHPExcel->getActiveSheet()->setCellValue("M$cell", $c->Angsuran_Ke);
				$objPHPExcel->getActiveSheet()->setCellValue("N$cell", number_format($c->sisa_pokok,0));
				$objPHPExcel->getActiveSheet()->setCellValue("O$cell", number_format($c->sisa_profit,0));
				$objPHPExcel->getActiveSheet()->setCellValue("P$cell", $c->Akad);
				
				$objPHPExcel->getActiveSheet()->getStyle("I$cell:J$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("N$cell:O$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$no++;
				$cell++; 
			endforeach;			
			
			
			//Set Column Auto Width
			foreach(range('A','P') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			//EXPORT	
			$filename = "Laporan_Asuransi_".$date."_" . time() . '.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
					
			$xlsFilePath = FCPATH."downloads/reports/$filename";
			$objWriter->save("$xlsFilePath");
	
		//SEND EMAIL
		
		$date_year = date('Y');
		$date_month = date('m');
		
		$this->load->library('email');
		
		$this->email->from('mis@amartha.com', 'Amartha MIS');
		$this->email->to('fikri@amartha.co.id,imamt@amartha.co.id'); 

		$this->email->subject('Laporan Asuransi Bulanan');
		$this->email->message('Laporan Asuransi');	
		$this->email->attach($xlsFilePath);
		$this->email->send();

	}
	
	
}