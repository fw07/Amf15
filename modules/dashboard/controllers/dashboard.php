<?php

class Dashboard extends Front_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('clients_masterdata_model');
		$this->load->model('dashboard_model');
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');
				$par_sisaangsuran[1] = 0;
				
				//FOR WEEKLY CHART
				/*
				$today = date('Y-m-d');
				$year = date('Y');
				$week = date("W", strtotime($today));
				
				$time = strtotime("1 January $year", time());
				$day = date('w', $time);
				$time += ((7*$week)+1-$day)*24*3600;
				$startdate = date('Y-n-j', $time);
				$time += 7*24*3600;
				$enddate = date('Y-n-j', $time);
				$start_date[0] = $startdate;
				$end_date[0] = $enddate;
				*/
				
			if($user_branch!=0){
				$total_anggota=$this->dashboard_model->count_clients_by_branch($user_branch);
				$total_majelis=$this->dashboard_model->count_majelis_by_branch($user_branch);
				$total_cabang=1;
				$total_tpl=$this->dashboard_model->count_officer_by_branch($user_branch);
				
				
				//portfolio anggota
				$total_anggota_aktif_pembiayaan=$this->dashboard_model->count_anggota_aktif_pembiayaan($user_branch);
				$total_anggota_aktif_menabung=$this->dashboard_model->count_anggota_aktif_menabung($user_branch);
				$total_anggota_keluar=$this->dashboard_model->count_anggota_keluar($user_branch);
				$total_monitoring=$this->dashboard_model->count_monitoring_pembiayaan($user_branch);
				
				
				/*
				$date_month  = date('m');
				$date_year   = date('Y');
				$filter_date = $this->input->post('filter'); //filter date
				if($filter_date){
					$date_start = date("Y-m-d", strtotime("first monday of ".$filter_date));
				}else{
					$date_start = date("Y-m-d", strtotime("first monday of ".$date_year."-".$date_month));
				}
				
				for($i=1;$i<=5;$i++){
					$date_end = date("Y-m-d", strtotime($date_start . ' + 7 day'));					
					$start_date[$i] = $date_start;	
					$end_date[$i]	= $date_end;	
					
					//Total Clients
					$total_clients_weekly[$i] = $this->dashboard_model->count_weeklyclients_by_branch($user_branch,$start_date[$i],$end_date[$i]);
					$total_total_clients_weekly[$i] = $this->dashboard_model->count_totalweeklyclients_by_branch($user_branch,$start_date[$i],$end_date[$i]);
					
					//Total Kehadiran
					$total_kehadiran_h[$i] = $this->dashboard_model->count_weekly_kehadiran_by_branch($user_branch,$start_date[$i],$end_date[$i]);
					if(empty($total_kehadiran_h[$i]) ){ $total_kehadiran_h[$i] = 0; };
					$total_kehadiran_h_persen[$i] = $total_kehadiran_h[$i] / ($total_anggota-$total_clients_weekly[$i]) * 100;
					
					
					$date_start = $date_end;
				}
				
				
				
				
				//portfolio sektor pembiayaan
				$total_sektor=0;
				for($i=1;$i<=8;$i++){
					$total_sektor_pembiayaan[$i]=$this->dashboard_model->count_sektor_pembiayaan($i,$user_branch);
					$total_sektor += $total_sektor_pembiayaan[$i];
				}
				for($i=1;$i<=8;$i++){
					$total_sektor_pembiayaan_persen[$i]=$total_sektor_pembiayaan[$i] / $total_sektor * 100;					
				}
				*/
				
				//portfolio pembiayaan
				$total_pembiayaan_aktif_ke_1=$this->dashboard_model->count_pembiayaan_aktif(1,$user_branch);
				$total_pembiayaan_aktif_ke_2=$this->dashboard_model->count_pembiayaan_aktif(2,$user_branch);
				$total_pembiayaan_aktif_ke_3=$this->dashboard_model->count_pembiayaan_aktif(3,$user_branch);
				$total_pembiayaan_aktif_ke_4=$this->dashboard_model->count_pembiayaan_aktif(4,$user_branch);
				$total_pembiayaan_aktif_ke_5=$this->dashboard_model->count_pembiayaan_aktif(5,$user_branch);
				
				
				//PAR
				$total_par[13]=$this->dashboard_model->count_par_13(13,$user_branch);
				
				//PAR
				for($i=0;$i<=12;$i++){
					$total_par[$i]=$this->dashboard_model->count_par($i,$user_branch);
				}
				$total_par[13]=$this->dashboard_model->count_par_13(13);
				
				//NOMINAL PAR
				$par_nominal = $this->dashboard_model->get_pembiayaan_par($user_branch);
				foreach($par_nominal as $p){
					for($i=1;$i<=12;$i++){
						if($p->data_par == $i){ $par_sisaangsuran[$i] += $p->data_sisaangsuran;}
					}
				}
				$par_nominal_13 = $this->dashboard_model->get_pembiayaan_par_13($user_branch);
				foreach($par_nominal_13 as $p){
					if($p->data_par >= 13){ $par_sisaangsuran[13] += $p->data_sisaangsuran;}
				}
				
			}else{
				$total_anggota=$this->dashboard_model->count_all_clients();
				$total_majelis=$this->dashboard_model->count_majelis();
				$total_cabang=$this->dashboard_model->count_cabang();
				$total_tpl=$this->dashboard_model->count_officer();
				
				
				//portfolio anggota
				$total_anggota_aktif_pembiayaan=$this->dashboard_model->count_anggota_aktif_pembiayaan();
				$total_anggota_aktif_menabung=$this->dashboard_model->count_anggota_aktif_menabung();
				$total_anggota_keluar=$this->dashboard_model->count_anggota_keluar();				
				$total_monitoring=$this->dashboard_model->count_monitoring_pembiayaan();
				/*
				$date_month  = date('m');
				$date_year   = date('Y');
				$filter_date = $this->input->post('filter'); //filter date
				if($filter_date){
					$date_start = date("Y-m-d", strtotime("first monday of ".$filter_date));
				}else{
					$date_start = date("Y-m-d", strtotime("first monday of ".$date_year."-".$date_month));
				}
				
				for($i=1;$i<=5;$i++){
					$date_end = date("Y-m-d", strtotime($date_start . ' + 7 day'));					
					$start_date[$i] = $date_start;	
					$end_date[$i]	= $date_end;	
					
					//Total Clients
					$total_clients_weekly[$i] = $this->dashboard_model->count_weeklyclients($user_branch,$start_date[$i],$end_date[$i]);
					$total_total_clients_weekly[$i] = $this->dashboard_model->count_totalweeklyclients($user_branch,$start_date[$i],$end_date[$i]);
					
					//Total Kehadiran
					$total_kehadiran_h[$i] = $this->dashboard_model->count_weekly_kehadiran($start_date[$i],$end_date[$i]);
					if(empty($total_kehadiran_h[$i]) ){ $total_kehadiran_h[$i] = 0; };
					$total_kehadiran_h_persen[$i] = $total_kehadiran_h[$i] / ($total_anggota - $total_clients_weekly[$i]) * 100;
					
					$date_start = $date_end;
				}
				*/
				//portfolio pembiayaan
				$total_pembiayaan_aktif_ke_1=$this->dashboard_model->count_pembiayaan_aktif(1);
				$total_pembiayaan_aktif_ke_2=$this->dashboard_model->count_pembiayaan_aktif(2);
				$total_pembiayaan_aktif_ke_3=$this->dashboard_model->count_pembiayaan_aktif(3);
				$total_pembiayaan_aktif_ke_4=$this->dashboard_model->count_pembiayaan_aktif(4);
				$total_pembiayaan_aktif_ke_5=$this->dashboard_model->count_pembiayaan_aktif(5);
				
				
				//portfolio sektor pembiayaan
				$total_sektor=0;
				for($i=1;$i<=9;$i++){
					$total_sektor_pembiayaan[$i]=$this->dashboard_model->count_sektor_pembiayaan($i);
					$total_sektor += $total_sektor_pembiayaan[$i];
				}
				for($i=1;$i<=9;$i++){
					$total_sektor_pembiayaan_persen[$i]=$total_sektor_pembiayaan[$i] / $total_sektor * 100;					
				}
				
				//PAR
				for($i=0;$i<=12;$i++){
					$total_par[$i]=$this->dashboard_model->count_par($i);
				}
				$total_par[13]=$this->dashboard_model->count_par_13(13);
				
				//NOMINAL PAR
				$par_nominal = $this->dashboard_model->get_pembiayaan_par();
				foreach($par_nominal as $p){
					for($i=1;$i<=12;$i++){
						
						if($p->data_par == $i){ 
							$sisa_angsuran = (50 - $p->data_angsuranke) * ($p->data_plafond / 50);
							$par_sisaangsuran[$i] += $sisa_angsuran;
						}
					}
				}
				$par_nominal_13 = $this->dashboard_model->get_pembiayaan_par_13();
				foreach($par_nominal_13 as $p){
					if($p->data_par >= 13){ 
						$sisa_angsuran = (50 - $p->data_angsuranke) * ($p->data_plafond / 50);
						$par_sisaangsuran[13] += $sisa_angsuran;
					}
				}
				
			}
			
			
			$this->template	->set('menu_title', 'Dashboard')
							->set('agent_name', $this->session->userdata('agent_name'))
							->set('total_anggota', $total_anggota)
							->set('total_majelis', $total_majelis)
							->set('total_cabang', $total_cabang)
							->set('total_tpl', $total_tpl)
							->set('total_clients_weekly', $total_clients_weekly)
							->set('start_date', $start_date)
							->set('end_date', $end_date)
							->set('agent_number', $this->session->userdata('agent_number'))
							->set('total_pembiayaan_aktif_ke_1', $total_pembiayaan_aktif_ke_1)
							->set('total_pembiayaan_aktif_ke_2', $total_pembiayaan_aktif_ke_2)
							->set('total_pembiayaan_aktif_ke_3', $total_pembiayaan_aktif_ke_3)
							->set('total_pembiayaan_aktif_ke_4', $total_pembiayaan_aktif_ke_4)
							->set('total_pembiayaan_aktif_ke_5', $total_pembiayaan_aktif_ke_5)
							->set('total_anggota_aktif_pembiayaan', $total_anggota_aktif_pembiayaan)
							->set('total_anggota_aktif_menabung', $total_anggota_aktif_menabung)
							->set('total_anggota_keluar', $total_anggota_keluar)
							->set('total_monitoring', $total_monitoring)
							->set('total_sektor_pembiayaan', $total_sektor_pembiayaan)
							->set('total_sektor_pembiayaan_persen', $total_sektor_pembiayaan_persen)
							->set('total_par', $total_par)
							->set('par_sisaangsuran', $par_sisaangsuran)
							->set('total_kehadiran_h', $total_kehadiran_h)
							->set('total_kehadiran_h_persen', $total_kehadiran_h_persen)
							->set('total_total_clients_weekly', $total_total_clients_weekly)							
							->set('menu_dashboard', 'active')
							->build('dashboard');
		}
		else
		{
			redirect('login', 'refresh');
		}
	}
	
	
	public function narative_report(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');
			
				//FOR WEEKLY CHART
				$today = date('Y-m-d');
				$year = date('Y');
				$week = date("W", strtotime($today));
				
				$time = strtotime("1 January $year", time());
				$day = date('w', $time);
				$time += ((7*$week)+1-$day)*24*3600;
				$startdate = date('Y-n-j', $time);
				$time += 7*24*3600;
				$enddate = date('Y-n-j', $time);
				$start_date[0] = $startdate;
				$end_date[0] = $enddate;
				
				$par_sisaangsuran[1] = 0;
				
		
			for($b=1;$b<=5;$b++){
				//Total Anggota
				$total_anggota[$b] =$this->dashboard_model->count_clients_by_branch($b);
				$total_anggota_all += $total_anggota[$b];
					
				//Total Majelis
				$total_majelis[$b]=$this->dashboard_model->count_majelis_by_branch($b);
				$total_majelis_all += $total_majelis[$b];
								
				//portfolio pembiayaan
				$total_pembiayaan_aktif_ke_1[$b]=$this->dashboard_model->count_pembiayaan_aktif(1,$b); 
				$total_pembiayaan_aktif_ke_2[$b]=$this->dashboard_model->count_pembiayaan_aktif(2,$b);
				$total_pembiayaan_aktif_ke_3[$b]=$this->dashboard_model->count_pembiayaan_aktif(3,$b);
				$total_pembiayaan_aktif_ke_4[$b]=$this->dashboard_model->count_pembiayaan_aktif(4,$b);
				$total_pembiayaan_aktif_ke_5[$b]=$this->dashboard_model->count_pembiayaan_aktif(5,$b);
				$sum_pembiayaan_aktif_1[$b]=$this->dashboard_model->sum_pembiayaan_aktif(1,$b);
				$sum_pembiayaan_aktif_2[$b]=$this->dashboard_model->sum_pembiayaan_aktif(2,$b);
				$sum_pembiayaan_aktif_3[$b]=$this->dashboard_model->sum_pembiayaan_aktif(3,$b);
				$sum_pembiayaan_aktif_4[$b]=$this->dashboard_model->sum_pembiayaan_aktif(4,$b);
				$sum_pembiayaan_aktif_5[$b]=$this->dashboard_model->sum_pembiayaan_aktif(5,$b);
				$sum_margin_1[$b]=$this->dashboard_model->sum_margin(1,$b);
				$sum_margin_2[$b]=$this->dashboard_model->sum_margin(2,$b);
				$sum_margin_3[$b]=$this->dashboard_model->sum_margin(3,$b);
				$sum_margin_4[$b]=$this->dashboard_model->sum_margin(4,$b);
				$sum_margin_5[$b]=$this->dashboard_model->sum_margin(5,$b);
				
				//portfolio anggota
				$total_anggota_aktif_pembiayaan[$b]=$this->dashboard_model->count_anggota_aktif_pembiayaan($b);
				$total_anggota_aktif_menabung=$this->dashboard_model->count_anggota_aktif_menabung($user_branch);
				$total_anggota_keluar[$b]=$this->dashboard_model->count_anggota_keluar($b);
				$total_anggota_keluar_all += $total_anggota_keluar[$b];
				
				//Total TPL
				$total_tpl[$b]=$this->dashboard_model->count_officer_by_branch($b);
				$total_tpl_all += $total_tpl[$b];
				
				
				//weekly
				for($i=0;$i<=4;$i++){
					$start_date[$i] = date("Y-m-d", (strtotime($startdate) - ($i*7*24*3600)));	
					$end_date[$i]	= date("Y-m-d", (strtotime($enddate) - ($i*7*24*3600)));	
					$total_clients_weekly[$b][$i] = $this->dashboard_model->count_weeklyclients_by_branch($b,$start_date[$i],$end_date[$i]);
				}
			}
			
			
			$this->template	->set('menu_title', 'Dashboard')
							->set('agent_name', $this->session->userdata('agent_name'))
							->set('total_anggota', $total_anggota)
							->set('total_majelis', $total_majelis)
							->set('total_cabang', $total_cabang)
							->set('total_tpl', $total_tpl)
							->set('total_clients_weekly', $total_clients_weekly)
							->set('start_date', $start_date)
							->set('end_date', $end_date)
							->set('agent_number', $this->session->userdata('agent_number'))
							->set('total_pembiayaan_aktif_ke_1', $total_pembiayaan_aktif_ke_1)
							->set('total_pembiayaan_aktif_ke_2', $total_pembiayaan_aktif_ke_2)
							->set('total_pembiayaan_aktif_ke_3', $total_pembiayaan_aktif_ke_3)
							->set('total_pembiayaan_aktif_ke_4', $total_pembiayaan_aktif_ke_4)
							->set('total_pembiayaan_aktif_ke_5', $total_pembiayaan_aktif_ke_5)
							->set('total_anggota_aktif_pembiayaan', $total_anggota_aktif_pembiayaan)
							->set('total_anggota_aktif_menabung', $total_anggota_aktif_menabung)
							->set('total_anggota_keluar', $total_anggota_keluar)
							->set('total_anggota_keluar_all', $total_anggota_keluar_all)
							->set('total_sektor_pembiayaan', $total_sektor_pembiayaan)
							->set('total_sektor_pembiayaan_persen', $total_sektor_pembiayaan_persen)
							->set('total_par', $total_par)
							->set('par_sisaangsuran', $par_sisaangsuran)
							->set('menu_dashboard', 'active')
							->set('total_anggota_all', $total_anggota_all)
							->set('total_majelis_all', $total_majelis_all)
							->set('total_tpl_all', $total_tpl_all)
							->set('sum_pembiayaan_aktif_1', $sum_pembiayaan_aktif_1)
							->set('sum_pembiayaan_aktif_2', $sum_pembiayaan_aktif_2)
							->set('sum_pembiayaan_aktif_3', $sum_pembiayaan_aktif_3)
							->set('sum_pembiayaan_aktif_4', $sum_pembiayaan_aktif_4)
							->set('sum_pembiayaan_aktif_5', $sum_pembiayaan_aktif_5)
							->set('sum_margin_1', $sum_margin_1)
							->set('sum_margin_2', $sum_margin_2)
							->set('sum_margin_3', $sum_margin_3)
							->set('sum_margin_4', $sum_margin_4)
							->set('sum_margin_5', $sum_margin_5)
							
							->build('narative_report');
		}
		else
		{
			redirect('login', 'refresh');
		}
	}
	
		public function testing(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');
				$par_sisaangsuran[1] = 0;
				
				//FOR WEEKLY CHART
				/*
				$today = date('Y-m-d');
				$year = date('Y');
				$week = date("W", strtotime($today));
				
				$time = strtotime("1 January $year", time());
				$day = date('w', $time);
				$time += ((7*$week)+1-$day)*24*3600;
				$startdate = date('Y-n-j', $time);
				$time += 7*24*3600;
				$enddate = date('Y-n-j', $time);
				$start_date[0] = $startdate;
				$end_date[0] = $enddate;
				*/
			/*	
			if($user_branch!=0){
				$total_anggota=$this->dashboard_model->count_clients_by_branch($user_branch);
				$total_majelis=$this->dashboard_model->count_majelis_by_branch($user_branch);
				$total_cabang=1;
				$total_tpl=$this->dashboard_model->count_officer_by_branch($user_branch);
				
				
				$date_month  = date('m');
				$date_year   = date('Y');
				$filter_date = $this->input->post('filter'); //filter date
				if($filter_date){
					$date_start = date("Y-m-d", strtotime("first monday of ".$filter_date));
				}else{
					$date_start = date("Y-m-d", strtotime("first monday of ".$date_year."-".$date_month));
				}
				
				for($i=1;$i<=5;$i++){
					$date_end = date("Y-m-d", strtotime($date_start . ' + 7 day'));					
					$start_date[$i] = $date_start;	
					$end_date[$i]	= $date_end;	
					
					//Total Clients
					$total_clients_weekly[$i] = $this->dashboard_model->count_weeklyclients_by_branch($user_branch,$start_date[$i],$end_date[$i]);
					$total_total_clients_weekly[$i] = $this->dashboard_model->count_totalweeklyclients_by_branch($user_branch,$start_date[$i],$end_date[$i]);
					
					//Total Kehadiran
					$total_kehadiran_h[$i] = $this->dashboard_model->count_weekly_kehadiran_by_branch($user_branch,$start_date[$i],$end_date[$i]);
					if(empty($total_kehadiran_h[$i]) ){ $total_kehadiran_h[$i] = 0; };
					$total_kehadiran_h_persen[$i] = $total_kehadiran_h[$i] / ($total_anggota-$total_clients_weekly[$i]) * 100;
					
					
					$date_start = $date_end;
				}
				
				//portfolio pembiayaan
				$total_pembiayaan_aktif_ke_1=$this->dashboard_model->count_pembiayaan_aktif(1,$user_branch);
				$total_pembiayaan_aktif_ke_2=$this->dashboard_model->count_pembiayaan_aktif(2,$user_branch);
				$total_pembiayaan_aktif_ke_3=$this->dashboard_model->count_pembiayaan_aktif(3,$user_branch);
				$total_pembiayaan_aktif_ke_4=$this->dashboard_model->count_pembiayaan_aktif(4,$user_branch);
				$total_pembiayaan_aktif_ke_5=$this->dashboard_model->count_pembiayaan_aktif(5,$user_branch);
				
				//portfolio anggota
				$total_anggota_aktif_pembiayaan=$this->dashboard_model->count_anggota_aktif_pembiayaan($user_branch);
				$total_anggota_aktif_menabung=$this->dashboard_model->count_anggota_aktif_menabung($user_branch);
				$total_anggota_keluar=$this->dashboard_model->count_anggota_keluar($user_branch);
				$total_monitoring=$this->dashboard_model->count_monitoring_pembiayaan($user_branch);
				
				//portfolio sektor pembiayaan
				$total_sektor=0;
				for($i=1;$i<=8;$i++){
					$total_sektor_pembiayaan[$i]=$this->dashboard_model->count_sektor_pembiayaan($i,$user_branch);
					$total_sektor += $total_sektor_pembiayaan[$i];
				}
				for($i=1;$i<=8;$i++){
					$total_sektor_pembiayaan_persen[$i]=$total_sektor_pembiayaan[$i] / $total_sektor * 100;					
				}
				
				//PAR
				$total_par[13]=$this->dashboard_model->count_par_13(13,$user_branch);
				
				//PAR
				for($i=0;$i<=12;$i++){
					$total_par[$i]=$this->dashboard_model->count_par($i,$user_branch);
				}
				$total_par[13]=$this->dashboard_model->count_par_13(13);
				
				//NOMINAL PAR
				$par_nominal = $this->dashboard_model->get_pembiayaan_par($user_branch);
				foreach($par_nominal as $p){
					for($i=1;$i<=12;$i++){
						if($p->data_par == $i){ $par_sisaangsuran[$i] += $p->data_sisaangsuran;}
					}
				}
				$par_nominal_13 = $this->dashboard_model->get_pembiayaan_par_13($user_branch);
				foreach($par_nominal_13 as $p){
					if($p->data_par >= 13){ $par_sisaangsuran[13] += $p->data_sisaangsuran;}
				}
				
			}else{
				$total_anggota=$this->dashboard_model->count_all_clients();
				$total_majelis=$this->dashboard_model->count_majelis();
				$total_cabang=$this->dashboard_model->count_cabang();
				$total_tpl=$this->dashboard_model->count_officer();
				
				
				$date_month  = date('m');
				$date_year   = date('Y');
				$filter_date = $this->input->post('filter'); //filter date
				if($filter_date){
					$date_start = date("Y-m-d", strtotime("first monday of ".$filter_date));
				}else{
					$date_start = date("Y-m-d", strtotime("first monday of ".$date_year."-".$date_month));
				}
				
				for($i=1;$i<=5;$i++){
					$date_end = date("Y-m-d", strtotime($date_start . ' + 7 day'));					
					$start_date[$i] = $date_start;	
					$end_date[$i]	= $date_end;	
					
					//Total Clients
					$total_clients_weekly[$i] = $this->dashboard_model->count_weeklyclients($user_branch,$start_date[$i],$end_date[$i]);
					$total_total_clients_weekly[$i] = $this->dashboard_model->count_totalweeklyclients($user_branch,$start_date[$i],$end_date[$i]);
					
					
					$date_start = $date_end;
				}
				
				//portfolio pembiayaan
				$total_pembiayaan_aktif_ke_1=$this->dashboard_model->count_pembiayaan_aktif(1);
				$total_pembiayaan_aktif_ke_2=$this->dashboard_model->count_pembiayaan_aktif(2);
				$total_pembiayaan_aktif_ke_3=$this->dashboard_model->count_pembiayaan_aktif(3);
				$total_pembiayaan_aktif_ke_4=$this->dashboard_model->count_pembiayaan_aktif(4);
				$total_pembiayaan_aktif_ke_5=$this->dashboard_model->count_pembiayaan_aktif(5);
				
				//portfolio anggota
				$total_anggota_aktif_pembiayaan=$this->dashboard_model->count_anggota_aktif_pembiayaan();
				$total_anggota_aktif_menabung=$this->dashboard_model->count_anggota_aktif_menabung();
				$total_anggota_keluar=$this->dashboard_model->count_anggota_keluar();				
				$total_monitoring=$this->dashboard_model->count_monitoring_pembiayaan();
				
				//portfolio sektor pembiayaan
				$total_sektor=0;
				for($i=1;$i<=9;$i++){
					$total_sektor_pembiayaan[$i]=$this->dashboard_model->count_sektor_pembiayaan($i);
					$total_sektor += $total_sektor_pembiayaan[$i];
				}
				for($i=1;$i<=9;$i++){
					$total_sektor_pembiayaan_persen[$i]=$total_sektor_pembiayaan[$i] / $total_sektor * 100;					
				}
				
				//PAR
				for($i=0;$i<=12;$i++){
					$total_par[$i]=$this->dashboard_model->count_par($i);
				}
				$total_par[13]=$this->dashboard_model->count_par_13(13);
				
				//NOMINAL PAR
				$par_nominal = $this->dashboard_model->get_pembiayaan_par();
				foreach($par_nominal as $p){
					for($i=1;$i<=12;$i++){
						if($p->data_par == $i){ $par_sisaangsuran[$i] += $p->data_sisaangsuran;}
					}
				}
				$par_nominal_13 = $this->dashboard_model->get_pembiayaan_par_13();
				foreach($par_nominal_13 as $p){
					if($p->data_par >= 13){ $par_sisaangsuran[13] += $p->data_sisaangsuran;}
				}
			}
			
			*/
			$this->template	->set('menu_title', 'Dashboard')
							->set('agent_name', $this->session->userdata('agent_name'))
							->set('total_anggota', $total_anggota)
							->set('total_majelis', $total_majelis)
							->set('total_cabang', $total_cabang)
							->set('total_tpl', $total_tpl)
							->set('total_clients_weekly', $total_clients_weekly)
							->set('start_date', $start_date)
							->set('end_date', $end_date)
							->set('agent_number', $this->session->userdata('agent_number'))
							->set('total_pembiayaan_aktif_ke_1', $total_pembiayaan_aktif_ke_1)
							->set('total_pembiayaan_aktif_ke_2', $total_pembiayaan_aktif_ke_2)
							->set('total_pembiayaan_aktif_ke_3', $total_pembiayaan_aktif_ke_3)
							->set('total_pembiayaan_aktif_ke_4', $total_pembiayaan_aktif_ke_4)
							->set('total_pembiayaan_aktif_ke_5', $total_pembiayaan_aktif_ke_5)
							->set('total_anggota_aktif_pembiayaan', $total_anggota_aktif_pembiayaan)
							->set('total_anggota_aktif_menabung', $total_anggota_aktif_menabung)
							->set('total_anggota_keluar', $total_anggota_keluar)
							->set('total_monitoring', $total_monitoring)
							->set('total_sektor_pembiayaan', $total_sektor_pembiayaan)
							->set('total_sektor_pembiayaan_persen', $total_sektor_pembiayaan_persen)
							->set('total_par', $total_par)
							->set('par_sisaangsuran', $par_sisaangsuran)
							->set('total_kehadiran_h', $total_kehadiran_h)
							->set('total_kehadiran_h_persen', $total_kehadiran_h_persen)
							->set('total_total_clients_weekly', $total_total_clients_weekly)							
							->set('menu_dashboard', 'active')
							->build('dashboard');
		}
		else
		{
			redirect('login', 'refresh');
		}
	}
}