<?php

class Client_data extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Client_data';
	private $module 	= 'client_data';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('regpyd_model');
		$this->load->library('pagination');	
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			redirect($this->module.'/browse', 'refresh');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function browse(){
		if($this->session->userdata('logged_in')){
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			$total_rows = $this->regpyd_model->count_all($this->input->post('q'));
			
			//pagination
			$config['base_url']     = site_url($this->module.'/browse');
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 30; 
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
			
			//$this->pagination->initialize($config);
			$no =  $this->uri->segment(3);			
			$regpyd = $this->regpyd_model->get_all_data($config['per_page'] , $page, $this->input->post('q'));	
			
			$this->template	->set('menu_title', 'Laporan REGPYD')
							->set('menu_konsolidasi', 'active')
							->set('config',$config)
							->set('regpyd', $regpyd)
							->build('regpyd');
		
		}
	}
	
	
	public function download(){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			$regpyd = $this->regpyd_model->get_all_clients();	
			
			 $date =  $this->uri->segment(3);
			if(!$date){ $date = date("Y-m"); }
			else{ $date =  $this->uri->segment(3); }
			
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Laporan Regpyd");
			$objPHPExcel->getProperties()->setSubject("Laporan Regpyd");
			$objPHPExcel->getProperties()->setDescription("Laporan Regpyd");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Laporan Regpyd');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Laporan Regpyd");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:W4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("F4:G4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("L4:L4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("N4:O4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "CABANG");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "MAJELIS");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "NO REKENING");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "NAMA ANGGOTA");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "TANGGAL LAHIR");
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "DESA");
			
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "PLAFOND");
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "PROFIT");
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "TGL PENCAIRAN");
			$objPHPExcel->getActiveSheet()->setCellValue("K4", "TGL JATUH TEMPO");
			$objPHPExcel->getActiveSheet()->setCellValue("L4", "PEMBIAYAAN KE");
			$objPHPExcel->getActiveSheet()->setCellValue("M4", "ANGSURAN KE");
			$objPHPExcel->getActiveSheet()->setCellValue("N4", "SISA POKOK");
			$objPHPExcel->getActiveSheet()->setCellValue("O4", "AKAD");
			$objPHPExcel->getActiveSheet()->setCellValue("P4", "STATUS PEMBIAYAAN");
			$objPHPExcel->getActiveSheet()->setCellValue("Q4", "PAR");
			$objPHPExcel->getActiveSheet()->setCellValue("R4", "TR");
			$objPHPExcel->getActiveSheet()->setCellValue("S4", "SEKTOR");
			$objPHPExcel->getActiveSheet()->setCellValue("T4", "TUJUAN PEMBIAYAAN");
			$objPHPExcel->getActiveSheet()->setCellValue("U4", "TAB. WAJIB");
			$objPHPExcel->getActiveSheet()->setCellValue("V4", "TAB. SUKARELA");
			$objPHPExcel->getActiveSheet()->setCellValue("W4", "TAB. BERJANGKA");
			
			
			$no=1;
			$cell = 5;
			foreach($regpyd as $c): 
			
				if($p->data_status ==1 ){ $status="Berjalan";}
				elseif($p->data_status == 2 ){ $status="Pengajuan";}
				elseif($p->data_status == 3 ){ $status="Selesai";}
				else{ $status="-";}
				
				//GET PEMBIAYAAN
				$pembiayaan = $this->regpyd_model->get_pembiayaan_detail($c->client_pembiayaan_id)->result();	
				$p = $pembiayaan[0];
				
				//GET TAB WAJIB
				$tabwajib = $this->regpyd_model->get_tab_wajib($c->client_account)->result();	
				$tabwajib = $tabwajib[0];
				
				//GET TAB SUKARELA
				$tabsukarela = $this->regpyd_model->get_tab_sukarela($c->client_account)->result();	
				$tabsukarela = $tabsukarela[0];
				
				//GET TAB BERJANGKA
				$tabberjangka = $this->regpyd_model->get_tab_berjangka($c->client_account)->result();	
				$tabberjangka = $tabberjangka[0];
				
				
				if($p->data_status == 1 ){ $sisa_pokok = (50 - $p->data_angsuranke) * ($p->data_plafond / 50); }else{ $sisa_pokok = 0; }
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->branch_name);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->group_name);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->client_account);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->client_fullname);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->client_birthdate);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->client_desa);
				
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $p->data_plafond);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", $p->data_margin);
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell", $p->data_date_accept);
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell", $p->data_jatuhtempo);
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell", $p->data_ke);
				$objPHPExcel->getActiveSheet()->setCellValue("M$cell", $p->data_angsuranke);
				$objPHPExcel->getActiveSheet()->setCellValue("N$cell", $sisa_pokok);
				$objPHPExcel->getActiveSheet()->setCellValue("O$cell", $p->data_akad);
				$objPHPExcel->getActiveSheet()->setCellValue("P$cell", $status);
				$objPHPExcel->getActiveSheet()->setCellValue("Q$cell", $p->data_par);
				$objPHPExcel->getActiveSheet()->setCellValue("R$cell", $p->data_tr);
				$objPHPExcel->getActiveSheet()->setCellValue("S$cell", $p->sector_name);
				$objPHPExcel->getActiveSheet()->setCellValue("T$cell", $p->data_keterangan);
				
				$objPHPExcel->getActiveSheet()->setCellValue("U$cell", $tabwajib->tabwajib_saldo);
				$objPHPExcel->getActiveSheet()->setCellValue("V$cell", $tabsukarela->tabsukarela_saldo);
				$objPHPExcel->getActiveSheet()->setCellValue("W$cell", $tabberjangka->tabberjangka_saldo);
				
				$objPHPExcel->getActiveSheet()->getStyle("F$cell:G$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("L$cell:L$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("P$cell:Q$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$no++;
				$cell++; 
			endforeach;			
			
			
			//Set Column Auto Width
			foreach(range('A','U') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			//EXPORT	
			$filename = "Laporan_Regpyd_".$date."_" . time() . '.xls'; //save our workbook as this file name
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