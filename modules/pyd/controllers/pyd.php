<?php

class Pyd extends Front_Controller{
	
	private $per_page 	= '15';
	private $title 		= 'Pyd';
	private $module 	= 'pyd';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('accounting_model');	
		$this->load->model('jurnal_model');	
		$this->load->model('regpyd_model');		
		$this->load->library('pagination');		
	}
	
	public function index($page='0'){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			//$total_rows = $this->group_model->count_all($this->input->post('q'));
			//Get Total Group Row 
			
			
			//pagination
			$config['base_url']     = site_url($this->module.'/all');
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
			
			//$this->pagination->initialize($config);
			$no =  $this->uri->segment(3);
			
			//$group = $this->group_model->get_group()->result();	
			//$group = $this->group_model->get_all_group( $config['per_page'] ,$page,$this->input->post('q'));			
			
			if($user_branch != 0){	
				$pyd = $this->regpyd_model->get_all($config['per_page'] ,$page,$user_branch);			
			}else{
				$user_branch = "";
				$pyd = $this->regpyd_model->get_all( $config['per_page'] ,$page,$user_branch);	
			}	
			
			$pyd = $this->regpyd_model->get_all( );	
			
			$this->template	->set('menu_title', 'REGPYD')
							->set('menu_group', 'active')
							->set('group_total',$config['total_rows'])
							->set('pyd', $pyd)
							->set('no', $no)
							//->set('config', $config)
							->build('pyd');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function download()
	{
		if($this->session->userdata('logged_in'))
		{
			//Cek User Branch
			$user_branch = $this->session->userdata('user_branch');
			$branch_name = str_replace(' ', '', $this->session->userdata('user_branch_name'));
			if($user_branch == 0){ $branch_name = "Pusat";}
			
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("REGPYD");
			$objPHPExcel->getProperties()->setSubject("REGPYD");
			$objPHPExcel->getProperties()->setDescription("REGPYD");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('REGPYD');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "REGPYD Cabang $branch_name");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:O4")->applyFromArray(array("font" => array( "bold" => true)));			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "NOMOR REKENING");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "NAMA");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "MAJELIS");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "CABANG");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "PLAFOND");
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "PROFIT");
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TGL PENCAIRAN");
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "TGL JATUH TEMPO");
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "ANGSURAN KE");
			$objPHPExcel->getActiveSheet()->setCellValue("K4", "AKAD");
			$objPHPExcel->getActiveSheet()->setCellValue("L4", "TAB WAJIB");
			$objPHPExcel->getActiveSheet()->setCellValue("M4", "TAB SUKARELA");
			$objPHPExcel->getActiveSheet()->setCellValue("N4", "SEKTOR PEMBIAYAAN");
			$objPHPExcel->getActiveSheet()->setCellValue("O4", "TUJUAN PEMBIAYAAN");
			
			$no=5;
			
			//EXPORT	
			$filename = "REGPYD_".$branch_name."_" . time() . '.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			
			//redirect('accounting/jurnal', 'refresh');
					 
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	
}