<?php

class Report extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Report';
	private $module 	= 'report';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('group_model');
		$this->load->model('officer_model');
		$this->load->model('area_model');
		$this->load->model('branch_model');
		$this->load->model('report_model');
		$this->load->library('pagination');	
	
	}
	

	public function index($page='0'){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			//Get Total Row Report
			if($user_branch!=0){ 
				$total_rows = $this->report_model->count_report_branch($this->input->post('q'),$user_branch);	
			}else{
				$total_rows = $this->report_model->count_report_all($this->input->post('q'));	
			}
			
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
			
			$this->pagination->initialize($config);
			$no =  $this->uri->segment(3);
			
			//Query Report
			if($user_branch!=0){ 
				$report = $this->report_model->get_branch_report( $config['per_page'] ,$page,$this->input->post('q'),$user_branch);		
			}else{
				$report = $this->report_model->get_all_report( $config['per_page'] ,$page,$this->input->post('q'));		
			}
			
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
	
	public function report($page='0'){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			//Get Total Row Report
			if($user_branch!=0){ 
				$total_rows = $this->report_model->count_report_branch($this->input->post('q'),$user_branch);	
			}else{
				$total_rows = $this->report_model->count_report_all($this->input->post('q'));	
			}
			
			//pagination
			$config['base_url']     = site_url($this->module.'/all');
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 15; 
			$config['uri_segment']  = 3;
			$config['suffix'] 		= '?' . http_build_query($_GET, '', "&");
			$config['first_url'] 	= $config['base_url'] . $config['suffix'];
			$config['num_links'] = 2;
			
			$this->pagination->initialize($config);
			$no =  $this->uri->segment(3);
			
			//Query Report
			if($user_branch!=0){ 
				$report = $this->report_model->get_branch_report( $config['per_page'] ,$page,$this->input->post('q'),$user_branch);		
			}else{
				$report = $this->report_model->get_all_report( $config['per_page'] ,$page,$this->input->post('q'));		
			}
			
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
	

	public function create(){
		if($this->save_report()){
			$this->session->set_flashdata('message', 'success|Report telah ditambahkan');
			redirect($this->module.'/');
		}
			
			
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');		
			if($user_branch == 0) { $user_branch =="";}
			$branch_detail = $this->report_model->get_branch($user_branch)->result();	
			$branch_name = $branch_detail[0]->branch_name;
			
			$start_date = $this->input->post('startdate');
			$end_date = $this->input->post('enddate');
			
			$group = $this->report_model->get_new_group($user_branch,$start_date,$end_date)->result();	
			$client = $this->report_model->get_new_client($user_branch,$start_date,$end_date)->result();
			$pengajuan = $this->report_model->get_new_pengajuan($user_branch,$start_date,$end_date)->result();
			$pencairan = $this->report_model->get_new_pencairan($user_branch,$start_date,$end_date)->result();
			$kas = $this->report_model->get_new_kas($user_branch,$start_date,$end_date)->result();
			$client_unreg = $this->report_model->get_new_client_unreg($user_branch,$start_date,$end_date)->result();
			
			$this->template	->set('menu_title', 'Create Weekly Report')
							->set('officer', $officer)
							->set('area', $area)
							->set('branch', $branch)
							->set('group', $group)
							->set('client', $client)
							->set('pengajuan', $pengajuan)
							->set('pencairan', $pencairan)
							->set('kas', $kas)
							->set('client_unreg', $client_unreg)
							->set('start_date', $start_date)
							->set('end_date', $end_date)
							->set('menu_branch', 'active')
							->build('report_create');
	}


	public function view(){
		$id =  $this->uri->segment(3);
		
		//GET SPECIFIC PROJECT
		$data = $this->group_model->get_group($id)->result();
		$data = $data[0];
		$this->template	->set('data', $data)
						->set('menu_title', 'View Majelis')
						->set('menu_group', 'active')
						->build('group_view');	
	}
	
	
	public function delete($id = '0'){
		$this->module = "report";
		$id =  $this->uri->segment(3);
			if($this->report_model->delete($id)){
				$this->session->set_flashdata('message', 'success|Laporan telah dihapus');
				redirect('report/');
				exit;
			}
	}	
	
	
	private function save_report(){
	
		$this->form_validation->set_rules('report_saldo', 'Saldo', 'required');
		if($this->form_validation->run() === TRUE){
			
			
			$user_branch = $this->session->userdata('user_branch');		
			$branch_detail = $this->report_model->get_branch($user_branch)->result();	
			$branch_name = $branch_detail[0]->branch_name;
			
			$start_date = $this->input->post('startdate');
			$end_date = $this->input->post('enddate');
			
			
			$kas = $this->report_model->get_new_kas($user_branch,$start_date,$end_date)->result();
			$group = $this->report_model->get_new_group($user_branch,$start_date,$end_date)->result();	
			$client = $this->report_model->get_new_client($user_branch,$start_date,$end_date)->result();
			$pengajuan = $this->report_model->get_new_pengajuan($user_branch,$start_date,$end_date)->result();
			$pencairan = $this->report_model->get_new_pencairan($user_branch,$start_date,$end_date)->result();
			$client_unreg = $this->report_model->get_new_client_unreg($user_branch,$start_date,$end_date)->result();
			
			$date = date("Y-m-d");
			//$week = date("W", strtotime($date));
			$timestamp=date("Ymdhis");
			$filename="Laporan_Mingguan_$branch_name_$timestamp";
			
			$data_client = array(
					'report_branch'    		=> $user_branch,
					'report_date'    		=> $date,
					'report_startdate'    	=> $start_date,
					'report_enddate'    	=> $end_date,
					'report_week'    		=> 1,
					'report_groupnew'    	=> $this->input->post("report_groupnew"),
					'report_anggotabaru'    => $this->input->post("report_anggotabaru"),
					'report_anggotakeluar'  => $this->input->post("report_anggotakeluar"),
					'report_pembiayaan'    	=> $this->input->post("report_pencairan"),
					'report_pengajuan'    	=> $this->input->post("report_pengajuan"),
					'report_saldo'    		=> $this->input->post("report_saldo"),
					'report_file'    		=> $filename.".pdf",					
					'report_file_excel'    	=> $filename.".xls",
			);
			
			//load our new mpdf library
			$this->load->library('mpdf');
			//load our new PHPExcel library
			$this->load->library('excel');
			
			$html = "";
			$html.='<div style="float:left; width: 200px; text-align: left;"><i><b>Amartha</b> Microfinance</i></div>';
			$html.='<div style="float:right; width: 200px; text-align: right;">Cabang : <b>'.$branch_name.'</b></div>';
			$html.='<div style="float:none; clear: both;"></div>';
			$html.='<hr/>';
			$html.='<h1>Laporan Mingguan</h1>';
			$html.="<b>Tanggal :</b> $start_date s/d $end_date";
			$html.='<hr/>';
			
		 
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Laporan Mingguan");
			$objPHPExcel->getProperties()->setSubject("Laporan Mingguan");
			$objPHPExcel->getProperties()->setDescription("Laporan Mingguan");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Posisi Saldo');
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Cabang $branch_name");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "LAPORAN MINGGUAN");
			$objPHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			
			
			//GENERATE KAS REPORT
				$html .= '<style>
						@page{ margin-top: 1cm; margin-bottom: 1cm; margin-left: 1cm; margin-right: 1cm;}
						body{ font-size: 9pt;} 
						.tbl{border-collapse: collapse;border: none;font-size: 9pt;}
						.tbl thead{border-bottom: 2px solid #000;}
						.tbl td, .tbl th{padding: 0 3px;border: 1px solid #333;}
						.clear{float: none;clear: both}						
						.tbl tr td.bdr_btm, .tbl tr th.bdr_btm{ border: none; border-left: none; border-right: none;border-bottom: 1px solid #000;}
						.tbl tr td.bdr_btm_bold, .tbl tr th.bdr_btm_bold{ border: none; border-left: none; border-right: none;border-bottom: 2px solid #000;}
						.tbl tr td.nobdr, .tbl tr th.nobdr{border: none;}
						.tbl tr td.border_bold{border: 2px solid #000;}
					</style>';
				$html.='<h2>Posisi Saldo</h2>';			
				$html.='<table cellspacing="5px" class="tbl">';    
				$html.='<thead>';              
				$html.='<tr>';   
				$html.='<th width="30px" class="bdr_btm">No</th>';   
				$html.='<th width="100px" align="center" class="bdr_btm">Tanggal</th>';   
				$html.='<th width="100px" align="center" class="bdr_btm">Cabang</th>';   
				$html.='<th align="right" class="bdr_btm">Brangkas (Rp)</th>';   
				$html.='<th align="right" class="bdr_btm">RF (Rp)</th>';   
				$html.='<th align="right" class="bdr_btm">Amanah (Rp)</th>';   
				$html.='<th align="right" class="bdr_btm">Total (Rp)</th>';   
				$html.='</tr>';    
				
				$objPHPExcel->getActiveSheet()->setCellValue("A6", "No");
				$objPHPExcel->getActiveSheet()->setCellValue("B6", "Tanggal");
				$objPHPExcel->getActiveSheet()->setCellValue("C6", "Cabang");
				$objPHPExcel->getActiveSheet()->setCellValue("D6", "Brangkas (Rp)");
				$objPHPExcel->getActiveSheet()->setCellValue("E6", "RF (Rp)");
				$objPHPExcel->getActiveSheet()->setCellValue("F6", "Amanah (Rp)");
				$objPHPExcel->getActiveSheet()->setCellValue("G6", "Total (Rp)");
				$objPHPExcel->getActiveSheet()->getStyle("A6:G6")->applyFromArray(array("font" => array( "bold" => true)));	
				$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(4);
				$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(12);
					
				$html.='</thead>';   
				$html.='<tbody>';				
				$no=1;$total_saldo=0; 
				$cell=7;
				foreach($kas as $c): 
					$kas_total=$c->kas_total;
					$total_saldo = $total_saldo+$kas_total;
					$html.='<tr>';      
					$html.='<td align="center" class="bdr_btm">'.$no.'</td>'; 	
					$html.='<td align="center" class="bdr_btm">'.$c->kas_date.'</td>'; 
					$html.='<td align="center" class="bdr_btm">'.$c->branch_name.'</td>'; 
					$html.='<td align="right" class="bdr_btm">'. number_format($c->kas_brangkas).'</td>'; 
					$html.='<td align="right" class="bdr_btm">'. number_format($c->kas_rf).'</td>'; 
					$html.='<td align="right" class="bdr_btm">'. number_format($c->kas_amanah).'</td>'; 
					$html.='<td align="right" class="bdr_btm">'. number_format($c->kas_total).'</td>'; 											
					$html.='</tr>';

					$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
					$objPHPExcel->getActiveSheet()->getStyle("A$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->kas_date);
					$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->branch_name);
					$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->kas_brangkas);
					$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->kas_rf);
					$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->kas_amanah);
					$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->kas_total);
					
				$no++; $cell++; endforeach;
				$html.='</tbody>'; 	
				$html.='</table>'; 
			//----------------------------------- END OF GENERATE KAS REPORT
			
			//GENERATE GROUP REPORT		

				$html.='<h2>Majelis Baru</h2>';
				$html.='<table cellspacing="5px" class="tbl">'; 	             
				$html.='<thead>'; 	         
				$html.='<tr>'; 	
				$html.='<th width="30px" class="bdr_btm">No</th>'; 	
				$html.='<th class="bdr_btm">No Majelis</th>'; 	
				$html.='<th class="bdr_btm">Majelis</th>'; 	
				$html.='<th class="bdr_btm">Jumlah Anggota</th>'; 	
				$html.='<th align="center" class="bdr_btm">Tanggal Pengesahan</th>'; 	
				$html.='<th class="bdr_btm">Cabang</th>'; 	
				$html.='<th class="bdr_btm">Pendamping</th>'; 	
				$html.='<th class="bdr_btm">Hari</th>'; 	
				$html.='<th class="bdr_btm">Jam</th>'; 	
				$html.='</tr>'; 	               
				$html.='</thead>'; 	
				$html.='<tbody>'; 

				$sheetId = 1;
				$objPHPExcel->createSheet(NULL, $sheetId);
				$objPHPExcel->setActiveSheetIndex($sheetId);
				$objPHPExcel->getActiveSheet()->setTitle('Majelis');
				$objPHPExcel->getActiveSheet()->setCellValue("A1", "MAJELIS BARU");
				$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
				$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));	
				$cell=3;
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "No");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", "No Majelis");
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", "Majelis");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", "Jumlah Anggota");
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", "Tanggal Pengesahan");
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", "Cabang");
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", "Pendamping");
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", "Hari");	
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", "Jam");	
				$objPHPExcel->getActiveSheet()->getStyle("A3:I3")->applyFromArray(array("font" => array( "bold" => true)));	
				$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(4);
				$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(15);
				$cell++;

				$no=1;
				foreach($group as $c):
				
					$client_on_group = $this->group_model->count_clients_on_group($c->group_id);
					
					$html.='<tr> ';     
					$html.='<td align="center" class="bdr_btm">'.$no.'</td>'; 
					$html.='<td class="bdr_btm">'.$c->group_number.'</td>'; 
					$html.='<td class="bdr_btm">'.$c->group_name.'</td>'; 
					$html.='<td align="center" class="bdr_btm">'.$c->group_date.'</td>'; 
					$html.='<td class="bdr_btm">'.$c->branch_name.'</td>'; 
					$html.='<td class="bdr_btm">'.$client_on_group.'</td>'; 
					$html.='<td class="bdr_btm">'.$c->officer_name.'</td>'; 
					$html.='<td class="bdr_btm">'.$c->group_schedule_day.'</td>'; 
					$html.='<td class="bdr_btm">'.$c->group_schedule_time.'</td>'; 
					$html.='</tr>'; 
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
					$objPHPExcel->getActiveSheet()->getStyle("A$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->group_number);
					$objPHPExcel->getActiveSheet()->getStyle("B$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->group_name);
					$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $client_on_group);
					$objPHPExcel->getActiveSheet()->getStyle("D$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->group_date);
					$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->branch_name);
					$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->officer_name);
					$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $c->group_schedule_day);	
					$objPHPExcel->getActiveSheet()->setCellValue("I$cell", $c->group_schedule_time);
				$no++;$cell++; endforeach;
				$html.='</tbody>'; 	
				$html.='</table>';   
			//----------------------------------- END OF GENERATE GROUP REPORT	
			
			//GENERATE CLIENT REPORT			
				$html.='<h2>Anggota Baru</h2>';
				$html.='<table cellspacing="5px"  class="tbl">';    
				$html.='<thead>';
				$html.='<tr>';
				$html.='<th width="30px" class="bdr_btm">No</th>';
				$html.='<th width="100px" class="bdr_btm">No Rekening</th>';
				$html.='<th class="bdr_btm">Nama Lengkap</th>';
				$html.='<th align="center" class="bdr_btm">Majelis</th>';
				$html.='<th align="center" class="bdr_btm">Cabang</th>';
				$html.='<th align="center" class="bdr_btm">Tanggal<br/>Registrasi</th>';
				$html.='<th align="center" class="bdr_btm">Pembiayaan<br/>Ke</th>';
				$html.='<th align="center" class="bdr_btm">Status</th>';
				$html.='</tr>';                  
				$html.='</thead>'; 
				$html.='<tbody>';
				
				$sheetId = 2;
				$objPHPExcel->createSheet(NULL, $sheetId);
				$objPHPExcel->setActiveSheetIndex($sheetId);
				$objPHPExcel->getActiveSheet()->setTitle('Anggota');
				$objPHPExcel->getActiveSheet()->setCellValue("A1", "ANGGOTA BARU");
				$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
				$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));	
				$cell=3;
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "No");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", "No Rekening");
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", "Nama Lengkap");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", "Majelis");
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", "Cabang");
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", "Tgl Registrasi");
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", "Pembiayaan Ke");	
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", "Status");	
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", "Tempat Lahir");
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell", "Tgl Lahir");
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell", "No KTP");
				$objPHPExcel->getActiveSheet()->getStyle("A3:K3")->applyFromArray(array("font" => array( "bold" => true)));	
				$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(4);
				$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(13);
				$no=1;$cell++;
				foreach($client as $c): 
					$html.='<tr>';
					$html.='<td align="center" class="bdr_btm">'.$no.'</td>';			              
					$html.='<td class="bdr_btm">'.$c->client_account.'</td>';
					$html.='<td class="bdr_btm">'.$c->client_fullname.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->group_name.'</a></td>';
					$html.='<td align="center" class="bdr_btm">'.$c->branch_name.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->client_reg_date.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->client_pembiayaan.'</span></td>';
					if($c->client_status == "1"){ $status="Aktif";}else{ $status="Keluar"; }
					$html.='<td align="center" class="bdr_btm">'.$status.'</td>';
					$html.='</tr>';

					$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
					$objPHPExcel->getActiveSheet()->getStyle("A$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->client_account);
					$objPHPExcel->getActiveSheet()->getStyle("B$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->client_fullname);
					$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->group_name);
					$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->branch_name);
					$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->client_reg_date);
					$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->client_pembiayaan);	
					$objPHPExcel->getActiveSheet()->getStyle("G$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $status);
					$objPHPExcel->getActiveSheet()->setCellValue("I$cell", $c->client_birthplace);
					$objPHPExcel->getActiveSheet()->setCellValue("J$cell", $c->client_birthdate);
					$objPHPExcel->getActiveSheet()->setCellValue("K$cell", $c->client_ktp);
					
				$no++;$cell++; endforeach;
				$html.='</tbody>';
				$html.='</table>';
			//----------------------------------- END OF GENERATE CLIENT REPORT
			
			//GENERATE PENGAJUAN REPORT			
				$html.='<h2>Pengajuan</h2>';
				$html.='<table cellspacing="5px" class="tbl">';
				$html.='<thead>';                
				$html.='<tr>';
				$html.='<th width="30px" class="bdr_btm">No</th>';
				$html.='<th width="100px" class="bdr_btm">No. Rekening</th>';
				$html.='<th class="bdr_btm">Nama Lengkap</th>';
				$html.='<th align="center" class="bdr_btm">Majelis</th>';
				$html.='<th align="center" class="bdr_btm">Plafond</th>';
				$html.='<th align="center" class="bdr_btm">Pembiayaan<br/>Ke</th>';
				$html.='<th align="center" class="bdr_btm">Tgl Pengajuan</th>';
				$html.='<th align="center" class="bdr_btm">Tgl Pencairan</th>';
				$html.='<th align="left"  class="bdr_btm">Status</th>';
				$html.='</tr>';                  
				$html.='</thead>';
				$html.='<tbody>';
				
				$sheetId = 3;
				$objPHPExcel->createSheet(NULL, $sheetId);
				$objPHPExcel->setActiveSheetIndex($sheetId);
				$objPHPExcel->getActiveSheet()->setTitle('Pengajuan');
				$objPHPExcel->getActiveSheet()->setCellValue("A1", "PENGAJUAN BARU");
				$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
				$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));	
				$cell=3;
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "No");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", "No Rekening");
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", "Nama Lengkap");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", "Majelis");
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", "Plafond");
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", "Pembiayaan Ke");
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", "Tgl Pengajuan");	
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", "Tgl Pencairan");	
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", "Status");	
				$objPHPExcel->getActiveSheet()->getStyle("A3:I3")->applyFromArray(array("font" => array( "bold" => true)));	
				$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(4);
				$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(10);
				$no=1;$cell++;
				$pengajuan_total=0;
				foreach($pengajuan as $c):
					$html.='<tr> ';    
					$html.='<td align="center" class="bdr_btm">'.$no.'</td>';					              
					$html.='<td class="bdr_btm">'.$c->client_account.'</td>';
					$html.='<td class="bdr_btm">'.$c->client_fullname.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->group_name.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->data_pengajuan.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->data_ke.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->data_tgl.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->data_date_accept.'</td>';
						if($c->data_status_pengajuan == "v"){ $status="Disetujui"; }
						elseif($c->data_status_pengajuan == "x"){ $status="Ditunda"; }
						elseif($c->data_status_pengajuan == "k"){ $status="Komite"; }
					$html.='<td align="left" class="bdr_btm">'.$status.'</td>';
					$html.='</tr>';	

					$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
					$objPHPExcel->getActiveSheet()->getStyle("A$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->client_account);
					$objPHPExcel->getActiveSheet()->getStyle("B$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->client_fullname);
					$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->group_name);
					$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->data_pengajuan);
					$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->data_ke);
					$objPHPExcel->getActiveSheet()->getStyle("F$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->data_tgl);
					$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $c->data_date_accept);		
					$objPHPExcel->getActiveSheet()->setCellValue("I$cell", $status);
					
				$no++;$cell++; endforeach;
				$html.='</tbody>';	
				$html.='</table> '; 
			//----------------------------------- END OF GENERATE PENGAJUAN REPORT					
								
			//GENERATE PENCAIRAN REPORT			
				$html.='<h2>Pencairan</h2>';
				$html.='<table cellspacing="5px" class="tbl">';
				$html.='<thead>';                
				$html.='<tr>';
				$html.='<th width="30px" class="bdr_btm">No</th>';
				$html.='<th width="100px" class="bdr_btm">No. Rekening</th>';
				$html.='<th class="bdr_btm">Nama Lengkap</th>';
				$html.='<th align="center" class="bdr_btm">Majelis</th>';
				$html.='<th align="center" class="bdr_btm">Plafond</th>';
				$html.='<th align="center" class="bdr_btm">Angsuran</th>';
				$html.='<th align="center" class="bdr_btm">Margin</th>';
				$html.='<th align="center" class="bdr_btm">Pembiayaan<br/>Ke</th>';
				$html.='<th align="center" class="bdr_btm">Tgl Pengajuan</th>';
				$html.='<th align="center" class="bdr_btm">Tgl Pencairan</th>';
				$html.='<th align="left" class="bdr_btm">Status</th>';
				$html.='<th align="left" class="bdr_btm">Akad</th>';
				$html.='<th align="left" class="bdr_btm">Sektor</th>';
				$html.='<th align="left" class="bdr_btm">Tujuan</th>';
				$html.='</tr>';                  
				$html.='</thead>';
				$html.='<tbody>';
				
				$sheetId = 4;
				$objPHPExcel->createSheet(NULL, $sheetId);
				$objPHPExcel->setActiveSheetIndex($sheetId);
				$objPHPExcel->getActiveSheet()->setTitle('Pencairan');
				$objPHPExcel->getActiveSheet()->setCellValue("A1", "PENCAIRAN BARU");
				$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
				$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));	
				$cell=3;
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "No");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", "No Rekening");
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", "Nama Lengkap");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", "Majelis");
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", "Plafond");
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", "Profit");
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", "Angsuran");
				$objPHPExcel->getActiveSheet()->getStyle("E$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("F$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("G$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", "Pembiayaan Ke");
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell", "Tgl Pengajuan");	
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell", "Tgl Pencairan");	
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell", "Status");	
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell", "Akad");		
				$objPHPExcel->getActiveSheet()->setCellValue("M$cell", "Sektor");		
				$objPHPExcel->getActiveSheet()->setCellValue("N$cell", "Tujuan");	
				$objPHPExcel->getActiveSheet()->getStyle("A3:N3")->applyFromArray(array("font" => array( "bold" => true)));	
				$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(4);
				$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(25);
				$no=1;$cell++;
				foreach($pencairan as $c):
					$html.='<tr> ';    
					$html.='<td align="center" class="bdr_btm">'.$no.'</td>';					              
					$html.='<td class="bdr_btm">'.$c->client_account.'</td>';
					$html.='<td class="bdr_btm">'.$c->client_fullname.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->group_name.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->data_plafond.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->data_margin.'</td>';
					$html.='<td align="center" class="bdr_btm">'.((($c->data_plafond + $c->data_margin)/50) + $c->data_tabunganwajib ).'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->data_ke.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->data_tgl.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->data_date_accept.'</td>';
						if($c->data_status_pengajuan == "v"){ $status="Disetujui"; }
						elseif($c->data_status_pengajuan == "x"){ $status="Ditunda"; }
						elseif($c->data_status_pengajuan == "k"){ $status="Komite"; }
					$html.='<td align="left" class="bdr_btm">'.$status.'</td>';
					$html.='<td align="left" class="bdr_btm">'.$c->data_akad.'</td>';
					$html.='<td align="left" class="bdr_btm">'.$c->sector_name.'</td>';
					$html.='<td align="left" class="bdr_btm">'.$c->data_tujuan.'</td>';
					$html.='</tr>';	

					$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
					$objPHPExcel->getActiveSheet()->getStyle("A$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->client_account);
					$objPHPExcel->getActiveSheet()->getStyle("B$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->client_fullname);
					$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->group_name);
					$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->data_plafond);
					$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->data_margin);
					$objPHPExcel->getActiveSheet()->setCellValue("G$cell", ((($c->data_plafond + $c->data_margin)/50) + $c->data_tabunganwajib ));
					$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $c->data_ke);
					$objPHPExcel->getActiveSheet()->getStyle("H$cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->setCellValue("I$cell", $c->data_tgl);
					$objPHPExcel->getActiveSheet()->setCellValue("J$cell", $c->data_date_accept);		
					$objPHPExcel->getActiveSheet()->setCellValue("K$cell", $status);
					$objPHPExcel->getActiveSheet()->setCellValue("L$cell", $c->data_akad);
					$objPHPExcel->getActiveSheet()->setCellValue("M$cell", $c->sector_name);
					$objPHPExcel->getActiveSheet()->setCellValue("N$cell", $c->data_tujuan);
					
				$no++;$cell++; endforeach;
				$html.='</tbody>';	
				$html.='</table> '; 
			//----------------------------------- END OF GENERATE PENCAIRAN REPORT					

			//GENERATE ANGGOTA KELUAR REPORT			
				$html.='<h2>Anggota Keluar</h2>';
				$html.='<table cellspacing="5px" class="tbl">';
				$html.='<thead>';                
				$html.='<tr>';
				$html.='<th width="30px" class="bdr_btm">No</th>';
				$html.='<th class="bdr_btm">Majelis</th>';
				$html.='<th class="bdr_btm">Nama</th>';
				$html.='<th  class="bdr_btm" width="100px">Tgl Keluar</th>';
				$html.='<th class="bdr_btm">Alasan</th>';
				$html.='<th class="bdr_btm" align="center">Pembiayaan<br/>Ke</th>';
				//$html.='<th class="bdr_btm">Tabungan<br/>Wajib</th>';
				//$html.='<th class="bdr_btm">Tabungan<br/>Cadangan</th>';
				//$html.='<th class="bdr_btm">Tabungan<br/>Sukarela</th>';
				$html.='<th class="bdr_btm">Pendamping</th>';
				$html.='<th class="bdr_btm">Pewawancara</th>';
				$html.='</tr>';                  
				$html.='</thead>';
				$html.='<tbody>';
				
				$sheetId = 5;
				$objPHPExcel->createSheet(NULL, $sheetId);
				$objPHPExcel->setActiveSheetIndex($sheetId);
				$objPHPExcel->getActiveSheet()->setTitle('Anggota Keluar');
				$objPHPExcel->getActiveSheet()->setCellValue("A1", "ANGGOTA KELUAR");
				$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
				$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));	
				$cell=3;
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell", "No");
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell", "Majelis");
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell", "Nama Lengkap");
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell", "Tgl Keluar");
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell", "Alasan");
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell", "Pembiayaan Ke");	
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell", "Pendamping");		
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell", "Pewawancara");	
				$objPHPExcel->getActiveSheet()->getStyle("A3:I3")->applyFromArray(array("font" => array( "bold" => true)));	
				$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(4);
				$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(13);
				$no=1;$cell++;
				
				foreach($client_unreg as $c):
					$html.='<tr> ';    
					$html.='<td align="center" class="bdr_btm">'.$no.'</td>';
					$html.='<td class="bdr_btm">'.$c->group_name.'</td>';
					$html.='<td class="bdr_btm">'.$c->client_fullname.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->client_unreg_date.'</td>';
					$html.='<td class="bdr_btm">'.$c->client_reason.'</td>';
					$html.='<td align="center" class="bdr_btm">'.$c->client_pembiayaan.'</td>';
					//$html.='<td align="center" class="bdr_btm">'.'0'.'</td>';
					//$html.='<td align="center" class="bdr_btm">'.'0'.'</td>';
					//$html.='<td align="center" class="bdr_btm">'.'0'.'</td>';
					$html.='<td align="left" class="bdr_btm">'.$c->officer_name.'</td>';
					$html.='<td align="left" class="bdr_btm">'.$c->officer_name.'</td>';
					$html.='</tr>';			
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$cell", $no);
					$objPHPExcel->getActiveSheet()->setCellValue("B$cell", $c->group_name);
					$objPHPExcel->getActiveSheet()->setCellValue("C$cell", $c->client_fullname);
					$objPHPExcel->getActiveSheet()->setCellValue("D$cell", $c->client_unreg_date);
					$objPHPExcel->getActiveSheet()->setCellValue("E$cell", $c->alasan_name);
					$objPHPExcel->getActiveSheet()->setCellValue("F$cell", $c->client_pembiayaan);	
					$objPHPExcel->getActiveSheet()->setCellValue("G$cell", $c->officer_name);		
					$objPHPExcel->getActiveSheet()->setCellValue("H$cell", $c->officer_name);
				$no++; $cell++; endforeach;
				$html.='</tbody>';	
				$html.='</table>'; 
			//----------------------------------- END OF GENERATE ANGGOTA KELUAR REPORT	
			
			
			$objPHPExcel->setActiveSheetIndex(0);
			
			//$this->mpdf->setFooter('{PAGENO}');
			//$this->mpdf->Output();
			
			
			$this->mpdf->SetFooter("Amartha Microfinance".'|{PAGENO}|'."Laporan Mingguan"); 
			$this->mpdf->WriteHTML($html);
			$pdfFilePath = FCPATH."downloads/reports/$filename.pdf";
			$this->mpdf->Output($pdfFilePath, 'F');
			
			
			//EXPORT	
			$filename = "$filename".'.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			//$objWriter->save('php://output');			
			$xlsFilePath = FCPATH."downloads/reports/$filename";
			$objWriter->save("$xlsFilePath");
			
			return $this->report_model->insert($data_client);	
		}			
	}
}