<?php

class Portfolio extends Front_Controller{
	
	private $per_page 	= '15';
	private $title 		= 'Portfolio';
	private $module 	= 'portfolio';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('accounting_model');	
		$this->load->model('dashboard_model');	
		$this->load->library('pagination');		
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			redirect('portfolio/par', 'refresh');
		}
		else
		{
			 //If no session, redirect to login page
			 redirect('login', 'refresh');
		}
	}

	//LAPORAN BULANAN
	public function par()
	{
		if($this->session->userdata('logged_in'))
		{
			//Cek User Branch
			$user_branch = $this->session->userdata('user_branch');
			
			//if($user_branch == "0"){ $user_branch=NULL;}
			
			function getStartAndEndDate($week, $year)
			{
				$time = strtotime("1 January $year", time());
				$day = date('w', $time);
				$time += ((7*$week)+1-$day)*24*3600;
				$return[0] = date('Y-n-j', $time);
				$time += 6*24*3600;
				$return[1] = date('Y-n-j', $time);
				return $return;
			}
			
			$date_today=date("Y-m-d");
			$date_year_today=date("Y");
			$date_week_today=date("W", strtotime($date_today)) - 1;
			$date_week_before=$date_week_today-1;
			
			$week_today = getStartAndEndDate($date_week_today,$date_year_today);
			$date_start=$this->input->post('date_start');
			$date_end=$this->input->post('date_end');
			if($date_start AND $date_end AND ($date_start <= $date_end )){
				$date_start=$this->input->post('date_start');
				$date_end=$this->input->post('date_end');
			}else{
				//$date_start =$week_today[0];
				$date_start = "2013-01-01";
				$date_end   = date("Y-m-d");			
			}
			
			$date_end_before = strtotime($date_start);
			//$date_end_before = $date_start;
			$date_end_before = strtotime("-1 day", $date_end_before); 
			$date_end_before = date('Y-m-d', $date_end_before);			
			$date_start_before = "2013-01-01";
			//echo $date_start_before." ----- ".$date_end_before; 
			
			
			//PAR
			for($branch=0;$branch<=5;$branch++){	
				//JUMLAH PAR
					//hitung jumlah par 0-12 minggu
					for($i=0;$i<=12;$i++){ 
						$total_par[$branch][$i]=$this->dashboard_model->count_par($i,$branch);
						
						//total par 1 bulan
						if($i>=1 AND $i<=4){ $total_par_1_bulan[$branch] += $this->dashboard_model->count_par($i,$branch);	}
						
						//total par 2 bulan
						if($i>=5 AND $i<=8){ $total_par_2_bulan[$branch] += $this->dashboard_model->count_par($i,$branch); }
						
						//total par 3 bulan
						if($i>=9 AND $i<=12){ $total_par_3_bulan[$branch] += $this->dashboard_model->count_par($i,$branch); }
					}
					//hitung jumlah par >12 minggu
					$total_par[$branch][13]=$this->dashboard_model->count_par_13(13);
				
				//NOMINAL PAR
					//hitung sisa angsuran 0-12 minggu
					$par_nominal = $this->dashboard_model->get_pembiayaan_par($branch);
					foreach($par_nominal as $p){
						for($i=1;$i<=12;$i++){
							if($p->data_par == $i){ 
								$par_sisaangsuran[$branch][$i] += (50-($p->data_angsuranke)) * (($p->data_plafond + $p->data_margin)/50);
							}
						}
					}
					//hitung sisa angsuran > 12 minggu
					$par_nominal_13 = $this->dashboard_model->get_pembiayaan_par_13($branch);
					foreach($par_nominal_13 as $p){
						if($p->data_par >= 13){ $par_sisaangsuran[$branch][13] += (50-($p->data_angsuranke)) * (($p->data_plafond + $p->data_margin)/50);}
					}
			}	
				
			$print .= '	<tr class="bg-primary font_bold"><td align="left" >Pembiayaan Lancar</td>
							<td align="right" >'.($total_par[1][0]+$total_par[2][0]+$total_par[3][0]+$total_par[4][0]+$total_par[5][0]).'</td>
							<td align="right" >'.$total_par[1][0].'</td>
							<td align="right" >'.$total_par[2][0].'</td>
							<td align="right" >'.$total_par[3][0].'</td>
							<td align="right" >'.$total_par[4][0].'</td>
							<td align="right" >'.$total_par[5][0].'</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';	
			$print .= '	<tr class="bg_highlight font_bold"><td align="left" >Tertunggak 1 - 30 hari</td>
							<td align="right" >'.($total_par_1_bulan[1]+$total_par_1_bulan[2]+$total_par_1_bulan[3]+$total_par_1_bulan[4]+$total_par_1_bulan[5]).'</td>
							<td align="right" >'.$total_par_1_bulan[1].'</td>
							<td align="right" >'.$total_par_1_bulan[2].'</td>
							<td align="right" >'.$total_par_1_bulan[3].'</td>
							<td align="right" >'.$total_par_1_bulan[4].'</td>
							<td align="right" >'.$total_par_1_bulan[5].'</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 minggu</td>
							<td align="right" >'.($total_par[1][1]+$total_par[2][1]+$total_par[3][1]+$total_par[4][1]+$total_par[5][1]).'</td>
							<td align="right" >'.$total_par[1][1].'</td>
							<td align="right" >'.$total_par[2][1].'</td>
							<td align="right" >'.$total_par[3][1].'</td>
							<td align="right" >'.$total_par[4][1].'</td>
							<td align="right" >'.$total_par[5][1].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][1]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][1]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][1]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][1]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][1]).'</td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2 minggu</td>
							<td align="right" >'.($total_par[1][2]+$total_par[2][2]+$total_par[3][2]+$total_par[4][2]+$total_par[5][2]).'</td>
							<td align="right" >'.$total_par[1][2].'</td>
							<td align="right" >'.$total_par[2][2].'</td>
							<td align="right" >'.$total_par[3][2].'</td>
							<td align="right" >'.$total_par[4][2].'</td>
							<td align="right" >'.$total_par[5][2].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][2]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][2]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][2]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][2]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][2]).'</td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3 minggu</td>
							<td align="right" >'.($total_par[1][3]+$total_par[2][3]+$total_par[3][3]+$total_par[4][3]+$total_par[5][3]).'</td>
							<td align="right" >'.$total_par[1][3].'</td>
							<td align="right" >'.$total_par[2][3].'</td>
							<td align="right" >'.$total_par[3][3].'</td>
							<td align="right" >'.$total_par[4][3].'</td>
							<td align="right" >'.$total_par[5][3].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][3]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][3]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][3]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][3]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][3]).'</td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4 minggu</td>
							<td align="right" >'.($total_par[1][4]+$total_par[2][4]+$total_par[3][4]+$total_par[4][4]+$total_par[5][4]).'</td>
							<td align="right" >'.$total_par[1][4].'</td>
							<td align="right" >'.$total_par[2][4].'</td>
							<td align="right" >'.$total_par[3][4].'</td>
							<td align="right" >'.$total_par[4][4].'</td>
							<td align="right" >'.$total_par[5][4].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][4]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][4]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][4]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][4]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][4]).'</td>
						</tr>';		
			$print .= '	<tr class="bg_highlight font_bold"><td align="left" >Tertunggak 31 - 60 hari</td>
							<td align="right" >'.($total_par_2_bulan[1]+$total_par_2_bulan[2]+$total_par_2_bulan[3]+$total_par_2_bulan[4]+$total_par_2_bulan[5]).'</td>
							<td align="right" >'.$total_par_2_bulan[1].'</td>
							<td align="right" >'.$total_par_2_bulan[2].'</td>
							<td align="right" >'.$total_par_2_bulan[3].'</td>
							<td align="right" >'.$total_par_2_bulan[4].'</td>
							<td align="right" >'.$total_par_2_bulan[5].'</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5 minggu</td>
							<td align="right" >'.($total_par[1][5]+$total_par[2][5]+$total_par[3][5]+$total_par[4][5]+$total_par[5][5]).'</td>
							<td align="right" >'.$total_par[1][5].'</td>
							<td align="right" >'.$total_par[2][5].'</td>
							<td align="right" >'.$total_par[3][5].'</td>
							<td align="right" >'.$total_par[4][5].'</td>
							<td align="right" >'.$total_par[5][5].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][5]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][5]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][5]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][5]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][5]).'</td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6 minggu</td>
							<td align="right" >'.($total_par[1][6]+$total_par[2][6]+$total_par[3][6]+$total_par[5][6]+$total_par[5][6]).'</td>
							<td align="right" >'.$total_par[1][6].'</td>
							<td align="right" >'.$total_par[2][6].'</td>
							<td align="right" >'.$total_par[3][6].'</td>
							<td align="right" >'.$total_par[5][6].'</td>
							<td align="right" >'.$total_par[5][6].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][6]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][6]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][6]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][6]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][6]).'</td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7 minggu</td>
							<td align="right" >'.($total_par[1][7]+$total_par[2][7]+$total_par[3][7]+$total_par[4][7]+$total_par[5][7]).'</td>
							<td align="right" >'.$total_par[1][7].'</td>
							<td align="right" >'.$total_par[2][7].'</td>
							<td align="right" >'.$total_par[3][7].'</td>
							<td align="right" >'.$total_par[4][7].'</td>
							<td align="right" >'.$total_par[5][7].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][7]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][7]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][7]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][7]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][7]).'</td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8 minggu</td>
							<td align="right" >'.($total_par[1][8]+$total_par[2][8]+$total_par[3][8]+$total_par[4][8]+$total_par[5][8]).'</td>
							<td align="right" >'.$total_par[1][8].'</td>
							<td align="right" >'.$total_par[2][8].'</td>
							<td align="right" >'.$total_par[3][8].'</td>
							<td align="right" >'.$total_par[4][8].'</td>
							<td align="right" >'.$total_par[5][8].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][8]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][8]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][8]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][8]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][8]).'</td>
						</tr>';
				
			$print .= '	<tr class="bg_highlight font_bold"><td align="left" >Tertunggak 61 - 90 hari</td>
							<td align="right" >'.($total_par_3_bulan[1]+$total_par_3_bulan[2]+$total_par_3_bulan[3]+$total_par_3_bulan[4]+$total_par_3_bulan[5]).'</td>
							<td align="right" >'.$total_par_3_bulan[1].'</td>
							<td align="right" >'.$total_par_3_bulan[2].'</td>
							<td align="right" >'.$total_par_3_bulan[3].'</td>
							<td align="right" >'.$total_par_3_bulan[4].'</td>
							<td align="right" >'.$total_par_3_bulan[5].'</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9 minggu</td>
							<td align="right" >'.($total_par[1][9]+$total_par[2][9]+$total_par[3][9]+$total_par[4][9]+$total_par[5][9]).'</td>
							<td align="right" >'.$total_par[1][9].'</td>
							<td align="right" >'.$total_par[2][9].'</td>
							<td align="right" >'.$total_par[3][9].'</td>
							<td align="right" >'.$total_par[4][9].'</td>
							<td align="right" >'.$total_par[5][9].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][9]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][9]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][9]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][9]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][9]).'</td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;10 minggu</td>
							<td align="right" >'.($total_par[1][10]+$total_par[2][10]+$total_par[3][10]+$total_par[4][10]+$total_par[5][10]).'</td>
							<td align="right" >'.$total_par[1][10].'</td>
							<td align="right" >'.$total_par[2][10].'</td>
							<td align="right" >'.$total_par[3][10].'</td>
							<td align="right" >'.$total_par[4][10].'</td>
							<td align="right" >'.$total_par[5][10].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][10]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][10]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][10]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][10]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][10]).'</td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;11 minggu</td>
							<td align="right" >'.($total_par[1][11]+$total_par[2][11]+$total_par[3][11]+$total_par[4][11]+$total_par[5][11]).'</td>
							<td align="right" >'.$total_par[1][11].'</td>
							<td align="right" >'.$total_par[2][11].'</td>
							<td align="right" >'.$total_par[3][11].'</td>
							<td align="right" >'.$total_par[4][11].'</td>
							<td align="right" >'.$total_par[5][11].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][11]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][11]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][11]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][11]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][11]).'</td>
						</tr>';	
			$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12 minggu</td>
							<td align="right" >'.($total_par[1][12]+$total_par[2][12]+$total_par[3][12]+$total_par[4][12]+$total_par[5][12]).'</td>
							<td align="right" >'.$total_par[1][12].'</td>
							<td align="right" >'.$total_par[2][12].'</td>
							<td align="right" >'.$total_par[3][12].'</td>
							<td align="right" >'.$total_par[4][12].'</td>
							<td align="right" >'.$total_par[5][12].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][12]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][12]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][12]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][12]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][12]).'</td>
						</tr>';	
			$print .= '	<tr class="bg_highlight font_bold"><td align="left" >Tertunggak &gt; 120 hari</td>
							<td align="right" >'.($total_par[1][13]+$total_par[2][13]+$total_par[3][13]+$total_par[4][13]+$total_par[5][13]).'</td>
							<td align="right" >'.$total_par[1][13].'</td>
							<td align="right" >'.$total_par[2][13].'</td>
							<td align="right" >'.$total_par[3][13].'</td>
							<td align="right" >'.$total_par[4][13].'</td>
							<td align="right" >'.$total_par[5][13].'</td>
							<td align="right" >'.number_format($par_sisaangsuran[1][13]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[2][13]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[3][13]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[4][13]).'</td>
							<td align="right" >'.number_format($par_sisaangsuran[5][13]).'</td>
						</tr>';	
				
			$this->template	->set('menu_title', 'Laporan Portfolio')
							->set('menu_konsolidasi', 'active')
							->set('print', $print)
							->build('portfolio');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	


	//EXCEL
	public function neraca_excel()
	{
		if($this->session->userdata('logged_in'))
		{
			//Cek User Branch
			$user_branch = $this->session->userdata('user_branch');
			$branch_name = str_replace(' ', '', $this->session->userdata('user_branch_name'));
			
			if($user_branch == "0"){ $user_branch=NULL;}
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Neraca");
			$objPHPExcel->getProperties()->setSubject("Neraca");
			$objPHPExcel->getProperties()->setDescription("Neraca");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Neraca');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Cabang $branch_name");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:E4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("B4:E4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "ACCOUNT");
			$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(60);
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "SALDO AWAL");
			$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "DEBET");
			$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(15);
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "CREDIT");
			$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "SALDO AKHIR");
			$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(15);
			
			
			$timestamp=date("Ymdhis");
			
			function getStartAndEndDate($week, $year)
			{
				$time = strtotime("1 January $year", time());
				$day = date('w', $time);
				$time += ((7*$week)+1-$day)*24*3600;
				$return[0] = date('Y-n-j', $time);
				$time += 6*24*3600;
				$return[1] = date('Y-n-j', $time);
				return $return;
			}
			
			$date_today=date("Y-m-d");
			$date_year_today=date("Y");
			$date_week_today=date("W", strtotime($date_today)) - 1;
			$date_week_before=$date_week_today-1;
			
			$week_today = getStartAndEndDate($date_week_today,$date_year_today);
			
			$date_start=$this->input->post('date_start');
			$date_end=$this->input->post('date_end');
			
			if($date_start AND $date_end AND ($date_start <= $date_end )){
				$date_start=$this->input->post('date_start');
				$date_end=$this->input->post('date_end');
			}else{
				$date_start =$week_today[0];
				$date_end = $week_today[1];			
			}
			
			$date_end_before = strtotime($date_end);
			$date_end_before = strtotime("-7 day", $date_end_before);
			$date_end_before = date('Y-m-d', $date_end_before);			
			$date_start_before = "2013-01-01";
			
				//Hitung Laba Rugi
				$accounting = $this->accounting_model->get_all_accounting_labarugi()->result();
				foreach($accounting as $c):
					$code = $c->accounting_code;
					$code_level0 = substr($code,0,1);
					$code_level1 = substr($code,0,3);
					$code_level2 = substr($code,0,5);
					$haschild = $c->accounting_haschild;
					$parent = $c->accounting_parent;
					$account_debet = 0;
					$account_credit = 0;
					$account_saldo = 0;
					$saldo_awal =0;
					
					if($haschild == "1" AND $parent == "0"){
					}elseif($haschild == "1" AND $parent != "0"){
					}else{	
						//LEVEL 3					
						$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$user_branch);
						$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$user_branch);
						
						$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before,$user_branch);
						$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before,$user_branch);
						
						$account_saldo_before = $account_debet_before - $account_credit_before;
						$account_saldo = $account_saldo_before + $account_debet - $account_credit;
						
						//grand total dihitung dari total account level 3
						$grand_total_debet += $account_debet;
						$grand_total_credit += $account_credit;
						$grand_total_before += $account_saldo_before;
						
						if($code_level0 == "4"){
							$grand_total_pendapatan_debet += $account_debet;
							$grand_total_pendapatan_credit += $account_credit;
							$grand_total_pendapatan_before += $account_saldo_before;
						}elseif($code_level0 == "5"){
							$grand_total_beban_debet += $account_debet;
							$grand_total_beban_credit += $account_credit;
							$grand_total_beban_before += $account_saldo_before;
						}
					}	
					$code_level0_old = $code_level0;
				endforeach; 
				
				//GRAND TOTAL LABA RUGI BERJALAN			
					$grand_total_saldo = $saldo_awal+$grand_total_debet-$grand_total_credit;
					$grand_total_pendapatan_saldo = $grand_total_pendapatan_before+$grand_total_pendapatan_debet-$grand_total_pendapatan_credit;
					$grand_total_beban_saldo = $grand_total_beban_before+$grand_total_beban_debet-$grand_total_beban_credit;
					$laba_rugi = $grand_total_pendapatan_credit - $grand_total_beban_debet;				
				//End of Hitung Laba Rugi
			
			
			$grand_total_debet=0;
			$grand_total_credit=0;
			$grand_total_before=0;
			
			//ASET			
			$objPHPExcel->getActiveSheet()->setCellValue("A5", "ASET");
			$objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));			
			$accounting = $this->accounting_model->get_all_accounting_aset()->result();
			$no=6;
			foreach($accounting as $c){
				$code = $c->accounting_code;
				$code_level0 = substr($code,0,1);
				$code_level1 = substr($code,0,3);
				$code_level2 = substr($code,0,5);
				$haschild = $c->accounting_haschild;
				$parent = $c->accounting_parent;
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				$saldo_awal =0;
				if($code_level0_old != $code_level0){ 
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", "");
				}
				if($haschild == "1" AND $parent == "0"){
					//LEVEL 1						
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start_before,$date_end_before,$branch);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start_before,$date_end_before,$branch);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", $c->accounting_code." ".$c->accounting_name);
					$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)));
					$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($account_debet));
					$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($account_credit));
					$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)));					
					$objPHPExcel->getActiveSheet()->getStyle("A$no:E$no")->applyFromArray(array("font" => array( "bold" => true)));
					$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);						
					
				}elseif($haschild == "1" AND $parent != "0"){
					//LEVEL 2					
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start_before,$date_end_before,$branch);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start_before,$date_end_before,$branch);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", "  ".$c->accounting_code." ".$c->accounting_name);
					$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)));
					$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($account_debet));
					$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($account_credit));
					$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)));
					$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			
				}else{	
					//LEVEL 3					
					$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					
					$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before,$branch);
					$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before,$branch);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					//grand total dihitung dari total account level 3
					$grand_total_debet += $account_debet;
					$grand_total_credit += $account_credit;
					$grand_total_before += $account_saldo_before;
					
						$grand_total_aktiva_debet += $account_debet;
						$grand_total_aktiva_credit += $account_credit;
						$grand_total_aktiva_before += $account_saldo_before;					
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", "    ".$c->accounting_code." ".$c->accounting_name);
					$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)));
					$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($account_debet));
					$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($account_credit));
					$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)));
					$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							
				}	
				$code_level0_old = $code_level0;
				$no++;
			}
			
			
			$grand_total_aktiva_saldo = $grand_total_aktiva_before+$grand_total_aktiva_debet-$grand_total_aktiva_credit;				
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "TOTAL ASET");
			$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($grand_total_aktiva_before < 0 ? "(".number_format(abs($grand_total_aktiva_before)).")" : number_format($grand_total_aktiva_before)));
			$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($grand_total_aktiva_debet));
			$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($grand_total_aktiva_credit));
			$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($grand_total_aktiva_saldo < 0 ? "(".number_format(abs($grand_total_aktiva_saldo)).")" : number_format($grand_total_aktiva_saldo)));
			$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("A$no:E$no")->applyFromArray(array("font" => array( "bold" => true)));
			$no++;	
			
			//reset
			$grand_total_debet=0;
			$grand_total_credit=0;
			$grand_total_before=0;
			$grand_total_aktiva_before = 0;
			$grand_total_aktiva_debet = 0;
			$grand_total_aktiva_credit = 0;
			$grand_total_aktiva_saldo = 0;
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "");
			$no++;	
			
			//KEWAJIBAN			
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "KEWAJIBAN");
			$objPHPExcel->getActiveSheet()->getStyle("A$no")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));	
			$no++;	
			$accounting = $this->accounting_model->get_all_accounting_kewajiban()->result();
			foreach($accounting as $c){
				$code = $c->accounting_code;
				$code_level0 = substr($code,0,1);
				$code_level1 = substr($code,0,3);
				$code_level2 = substr($code,0,5);
				$haschild = $c->accounting_haschild;
				$parent = $c->accounting_parent;
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				$saldo_awal =0;
				if($code_level0_old != $code_level0){ 
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", "");
				}
				if($haschild == "1" AND $parent == "0"){
					//LEVEL 1						
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start_before,$date_end_before,$branch);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start_before,$date_end_before,$branch);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", $c->accounting_code." ".$c->accounting_name);
					$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)));
					$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($account_debet));
					$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($account_credit));
					$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)));					
					$objPHPExcel->getActiveSheet()->getStyle("A$no:E$no")->applyFromArray(array("font" => array( "bold" => true)));
					$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);						
					
				}elseif($haschild == "1" AND $parent != "0"){
					//LEVEL 2					
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start_before,$date_end_before,$branch);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start_before,$date_end_before,$branch);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", "  ".$c->accounting_code." ".$c->accounting_name);
					$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)));
					$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($account_debet));
					$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($account_credit));
					$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)));
					$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			
				}else{	
					//LEVEL 3					
					$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					
					$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before,$branch);
					$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before,$branch);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					//grand total dihitung dari total account level 3
					$grand_total_debet += $account_debet;
					$grand_total_credit += $account_credit;
					$grand_total_before += $account_saldo_before;
					
						$grand_total_aktiva_debet += $account_debet;
						$grand_total_aktiva_credit += $account_credit;
						$grand_total_aktiva_before += $account_saldo_before;					
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", "    ".$c->accounting_code." ".$c->accounting_name);
					$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)));
					$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($account_debet));
					$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($account_credit));
					$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)));
					$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							
				}	
				$code_level0_old = $code_level0;
				$no++;
			}
			
			
			$grand_total_aktiva_saldo = $grand_total_aktiva_before+$grand_total_aktiva_debet-$grand_total_aktiva_credit;				
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "TOTAL KEWAJIBAN"); 
			$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($grand_total_aktiva_before < 0 ? "(".number_format(abs($grand_total_aktiva_before)).")" : number_format($grand_total_aktiva_before)));
			$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($grand_total_aktiva_debet));
			$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($grand_total_aktiva_credit));
			$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($grand_total_aktiva_saldo < 0 ? "(".number_format(abs($grand_total_aktiva_saldo)).")" : number_format($grand_total_aktiva_saldo)));
			$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("A$no:E$no")->applyFromArray(array("font" => array( "bold" => true)));
			$no++;	
			
			
			//reset
			$grand_total_debet=0;
			$grand_total_credit=0;
			$grand_total_before=0;
			$grand_total_aktiva_before = 0;
			$grand_total_aktiva_debet = 0;
			$grand_total_aktiva_credit = 0;
			$grand_total_aktiva_saldo = 0;
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "");
			$no++;	
			
			//MODAL	
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "MODAL");
			$objPHPExcel->getActiveSheet()->getStyle("A$no")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));	
			$no++;	
			$accounting = $this->accounting_model->get_all_accounting_modal()->result();
			foreach($accounting as $c){
				$code = $c->accounting_code;
				$code_level0 = substr($code,0,1);
				$code_level1 = substr($code,0,3);
				$code_level2 = substr($code,0,5);
				$haschild = $c->accounting_haschild;
				$parent = $c->accounting_parent;
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				$saldo_awal =0;
				if($code_level0_old != $code_level0){ 
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", "");
				}
				if($haschild == "1" AND $parent == "0"){
					//LEVEL 1						
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start_before,$date_end_before,$branch);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start_before,$date_end_before,$branch);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", $c->accounting_code." ".$c->accounting_name);
					$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)));
					$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($account_debet));
					$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($account_credit));
					$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)));					
					$objPHPExcel->getActiveSheet()->getStyle("A$no:E$no")->applyFromArray(array("font" => array( "bold" => true)));
					$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);						
					
				}elseif($haschild == "1" AND $parent != "0"){
					//LEVEL 2					
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start_before,$date_end_before,$branch);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start_before,$date_end_before,$branch);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", "  ".$c->accounting_code." ".$c->accounting_name);
					$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)));
					$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($account_debet));
					$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($account_credit));
					$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)));
					$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			
				}else{	
					//LEVEL 3					
					$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					
					$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before,$branch);
					$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before,$branch);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					if($c->accounting_code == "3020002"){
						$account_saldo_before = $laba_rugi;
					}
					
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					//grand total dihitung dari total account level 3
					$grand_total_debet += $account_debet;
					$grand_total_credit += $account_credit;
					$grand_total_before += $account_saldo_before;
					
						$grand_total_aktiva_debet += $account_debet;
						$grand_total_aktiva_credit += $account_credit;
						$grand_total_aktiva_before += $account_saldo_before;					
					
					$objPHPExcel->getActiveSheet()->setCellValue("A$no", "    ".$c->accounting_code." ".$c->accounting_name);
					$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)));
					$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($account_debet));
					$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($account_credit));
					$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)));
					$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							
				}	
				$code_level0_old = $code_level0;
				$no++;
			}
			
			
			$grand_total_aktiva_saldo = $grand_total_aktiva_before+$grand_total_aktiva_debet-$grand_total_aktiva_credit;				
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "TOTAL MODAL"); 
			$objPHPExcel->getActiveSheet()->setCellValue("B$no", ($grand_total_aktiva_before < 0 ? "(".number_format(abs($grand_total_aktiva_before)).")" : number_format($grand_total_aktiva_before)));
			$objPHPExcel->getActiveSheet()->setCellValue("C$no", number_format($grand_total_aktiva_debet));
			$objPHPExcel->getActiveSheet()->setCellValue("D$no", number_format($grand_total_aktiva_credit));
			$objPHPExcel->getActiveSheet()->setCellValue("E$no", ($grand_total_aktiva_saldo < 0 ? "(".number_format(abs($grand_total_aktiva_saldo)).")" : number_format($grand_total_aktiva_saldo)));
			$objPHPExcel->getActiveSheet()->getStyle("B$no:E$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("A$no:E$no")->applyFromArray(array("font" => array( "bold" => true)));
			$no++;	
			
			
			//EXPORT	
			$filename = "Neraca_".$branch_name."_" . time() . '.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			
			redirect('accounting/neraca', 'refresh');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
}