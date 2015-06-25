<?php

class Accounting extends Front_Controller{
	
	private $per_page 	= '15';
	private $title 		= 'Accounting';
	private $module 	= 'accounting';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('accounting_model');	
		$this->load->model('jurnal_model');		
		$this->load->library('pagination');		
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			redirect('accounting/jurnal', 'refresh');
		}
		else
		{
			 //If no session, redirect to login page
			 redirect('login', 'refresh');
		}
	}
	
	public function jurnal($page='0')
	{
		if($this->session->userdata('logged_in'))
		{
			$total_rows = $this->jurnal_model->count_all_jurnal($this->input->post('q'),$this->input->post('key'));
			
			//pagination
			$config['base_url']     = site_url($this->module.'/jurnal');
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
			
			$jurnal = $this->jurnal_model->get_all_jurnal($config['per_page'] ,$page,$this->input->post('q'),$this->input->post('key'));
			//print_r($jurnal);
			$this->template	->set('menu_title', 'Jurnal')
							->set('menu_jurnal', 'active')
							->set('jurnal', $jurnal)
							->set('no', $no)
							->set('config', $config)
							->build('jurnal');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}


	public function add(){
		if($this->save_jurnal()){
			$this->session->set_flashdata('message', 'success|Jurnal telah ditambahkan');
			redirect($this->module.'/jurnal');
		}
		$account = $this->accounting_model->get_all()->result();
		
		$this->template	->set('data', $data)
						->set('menu_title', 'Tambah Jurnal Baru')
						->set('menu_jurnal', 'active')
						->set('account', $account)
						->build('jurnal_form');	
	}
	public function jurnal_edit(){
		if($this->save_jurnal()){
			$this->session->set_flashdata('message', 'success|Jurnal telah diedit');
			redirect($this->module.'/jurnal');
		}
		$id =  $this->uri->segment(3);
		$account = $this->accounting_model->get_all()->result();
		$data = $this->jurnal_model->get_jurnal($id)->result();
		$data = $data[0];
		$this->template	->set('data', $data)
						->set('menu_title', 'Edit Jurnal')
						->set('menu_jurnal', 'active')
						->set('account', $account)
						->build('jurnal_form');	
	}
	
	public function jurnal_delete($id = '0'){
		$id =  $this->uri->segment(3);
			if($this->jurnal_model->delete($id)){
				$this->session->set_flashdata('message', 'success|Jurnal telah dihapus');
				redirect('accounting/jurnal');
				exit;
			}
	}
	
	private function save_jurnal(){
		
		//set form validation
		$this->form_validation->set_rules('jurnal_date', 'Tanggal', 'required');
		$this->form_validation->set_rules('jurnal_account_debet', 'Account Debet', 'required');
		$this->form_validation->set_rules('jurnal_nominal', 'Nominal', 'required');
		//$this->form_validation->set_rules('jurnal_debet', 'Nominal Debet', 'required');
		$this->form_validation->set_rules('jurnal_account_credit', 'Account Kredit', 'required');
		//$this->form_validation->set_rules('jurnal_credit', 'Nominal Kredit', 'required');
		$this->form_validation->set_rules('jurnal_remark', 'Keterangan', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('jurnal_id');
			
			//process the form
			$data = array(
					'jurnal_date'       	=> $this->input->post('jurnal_date'),
					'jurnal_account_debet' 	=> $this->input->post('jurnal_account_debet'),	
					'jurnal_debet' 			=> $this->input->post('jurnal_nominal'),			
					'jurnal_account_credit' => $this->input->post('jurnal_account_credit'),
					'jurnal_credit'	    	=> $this->input->post('jurnal_nominal'),
					'jurnal_remark'	    	=> $this->input->post('jurnal_remark'),					
			);
				
			if(!$id){
				return $this->jurnal_model->insert($data);
			}else{
				return $this->jurnal_model->update($id, $data);
			} 
		}
	}
	
	
	//NERACA
	public function neraca()
	{
		if($this->session->userdata('logged_in'))
		{
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
			
			$accounting = $this->accounting_model->get_all_accounting_std()->result();
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
				if($code_level0_old != $code_level0){ $neraca .= '<tr><td colspan="5">&nbsp;</td></tr><tr>'; }
				if($haschild == "1" AND $parent == "0"){
					//LEVEL 1						
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$neraca .= '<tr>     
								<td align="left" ><b>'.$c->accounting_code." ".$c->accounting_name.'</b></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>								
								</tr>';
				}elseif($haschild == "1" AND $parent != "0"){
					//LEVEL 2					
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$neraca .= '<tr>     
								<td align="left" ><b>&nbsp;&nbsp;&nbsp;&nbsp;'.$c->accounting_code." ".$c->accounting_name.'</b></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>
								</tr>';
				}else{	
					//LEVEL 3					
					$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end);
					
					$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					//grand total dihitung dari total account level 3
					$grand_total_debet += $account_debet;
					$grand_total_credit += $account_credit;
					$grand_total_before += $account_saldo_before;
					
					if($code_level0 == "1" OR $code_level0 == "2"){
						$grand_total_aktiva_debet += $account_debet;
						$grand_total_aktiva_credit += $account_credit;
						$grand_total_aktiva_before += $account_saldo_before;
					}elseif($code_level0 == "3"){
						$grand_total_pasiva_debet += $account_debet;
						$grand_total_pasiva_credit += $account_credit;
						$grand_total_pasiva_before += $account_saldo_before;
					}
					
					$neraca .= '<tr>     
								<td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$c->accounting_code." ".$c->accounting_name.'</td>	
								<td class="text-right">'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</td>
								<td class="text-right">'.number_format($account_debet).'</td>
								<td class="text-right">'.number_format($account_credit).'</td>
								<td class="text-right">'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</td>
								</tr>';					
				}	
				$code_level0_old = $code_level0;
			endforeach; 
			
				//GRAND TOTAL				
				$grand_total_saldo = $grand_total_aktiva_before+$grand_total_debet-$grand_total_credit;
				$grand_total_aktiva_saldo = $grand_total_aktiva_before+$grand_total_aktiva_debet-$grand_total_aktiva_credit;
				$grand_total_pasiva_saldo = $grand_total_pasiva_before+$grand_total_pasiva_debet-$grand_total_pasiva_credit;
				
				$neraca .= '<tfoot ><tr bgcolor="#eee">     
							<td align="left" ><b>TOTAL AKTIVA</b></td>	
							<td class="text-right"><b>'.($grand_total_aktiva_before < 0 ? "(".number_format(abs($grand_total_aktiva_before)).")" : number_format($grand_total_aktiva_before)).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_aktiva_debet).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_aktiva_credit).'</b></td>
							<td class="text-right"><b>'.($grand_total_aktiva_saldo < 0 ? "(".number_format(abs($grand_total_aktiva_saldo)).")" : number_format($grand_total_aktiva_saldo)).'</b></td>
							</tr>';	
				$neraca .= '<tr bgcolor="#eee">     
							<td align="left" ><b>TOTAL PASIVA</b></td>	
							<td class="text-right"><b>'.($grand_total_pasiva_before < 0 ? "(".number_format(abs($grand_total_pasiva_before)).")" : number_format($grand_total_pasiva_before)).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_pasiva_debet).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_pasiva_credit).'</b></td>
							<td class="text-right"><b>'.($grand_total_pasiva_saldo < 0 ? "(".number_format(abs($grand_total_pasiva_saldo)).")" : number_format($grand_total_pasiva_saldo)).'</b></td>
							</tr>';	
							
				$neraca .= '<tfoot bgcolor="#ddd"><tr">     
							<td align="left" ><b>GRAND TOTAL</b></td>	
							<td class="text-right"><b>0</b></td>
							<td class="text-right"><b>'.number_format($grand_total_debet).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_credit).'</b></td>
							<td class="text-right"><b>'.($grand_total_saldo < 0 ? "(".number_format(abs($grand_total_saldo)).")" : number_format($grand_total_saldo)).'</b></td>
							</tr></tfoot>';	
			
			$this->template	->set('menu_title', 'Neraca')
							->set('menu_jurnal', 'active')
							->set('accounting', $accounting)
							->set('neraca', $neraca)
							->build('neraca');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	//NERACA
	public function laba_rugi()
	{
		if($this->session->userdata('logged_in'))
		{
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
				if($code_level0_old != $code_level0){ $neraca .= '<tr><td colspan="5">&nbsp;</td></tr><tr>'; }
				if($haschild == "1" AND $parent == "0"){
					//LEVEL 1						
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$neraca .= '<tr>     
								<td align="left" ><b>'.$c->accounting_code." ".$c->accounting_name.'</b></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>								
								</tr>';
				}elseif($haschild == "1" AND $parent != "0"){
					//LEVEL 2					
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$neraca .= '<tr>     
								<td align="left" ><b>&nbsp;&nbsp;&nbsp;&nbsp;'.$c->accounting_code." ".$c->accounting_name.'</b></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>
								</tr>';
				}else{	
					//LEVEL 3					
					$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end);
					
					$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before);
					
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
					
					$neraca .= '<tr>     
								<td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$c->accounting_code." ".$c->accounting_name.'</td>	
								<td class="text-right">'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</td>
								<td class="text-right">'.number_format($account_debet).'</td>
								<td class="text-right">'.number_format($account_credit).'</td>
								<td class="text-right">'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</td>
								</tr>';					
				}	
				$code_level0_old = $code_level0;
			endforeach; 
			
				//GRAND TOTAL				
				$grand_total_saldo = $saldo_awal+$grand_total_debet-$grand_total_credit;
				$grand_total_pendapatan_saldo = $grand_total_pendapatan_before+$grand_total_pendapatan_debet-$grand_total_pendapatan_credit;
				$grand_total_beban_saldo = $grand_total_beban_before+$grand_total_beban_debet-$grand_total_beban_credit;
				$laba_rugi = $grand_total_pendapatan_credit - $grand_total_beban_debet;
				
				$neraca .= '<tfoot ><tr bgcolor="#eee">     
							<td align="left" ><b>TOTAL PENDAPATAN</b></td>	
							<td class="text-right"><b>'.($grand_total_pendapatan_before < 0 ? "(".number_format(abs($grand_total_pendapatan_before)).")" : number_format($grand_total_pendapatan_before)).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_pendapatan_debet).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_pendapatan_credit).'</b></td>
							<td class="text-right"><b>'.($grand_total_pendapatan_saldo < 0 ? "(".number_format(abs($grand_total_pendapatan_saldo)).")" : number_format($grand_total_pendapatan_saldo)).'</b></td>
							</tr>';	
				$neraca .= '<tr bgcolor="#eee">     
							<td align="left" ><b>TOTAL BEBAN</b></td>	
							<td class="text-right"><b>'.($grand_total_beban_before < 0 ? "(".number_format(abs($grand_total_beban_before)).")" : number_format($grand_total_beban_before)).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_beban_debet).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_beban_credit).'</b></td>
							<td class="text-right"><b>'.($grand_total_beban_saldo < 0 ? "(".number_format(abs($grand_total_beban_saldo)).")" : number_format($grand_total_beban_saldo)).'</b></td>
							</tr>';	
				$neraca .= '<tr bgcolor="#ddd">     
							<td align="left" ><b>LABA RUGI</b></td>	
							<td class="text-right"></td>
							<td class="text-right"></td>
							<td class="text-right"></td>
							<td class="text-right"><b>'.($laba_rugi < 0 ? "(".number_format(abs($laba_rugi)).")" : number_format($laba_rugi)).'</b></td>
							</tr></tfoot>';	
			
			$this->template	->set('menu_title', 'Neraca Laba Rugi')
							->set('menu_jurnal', 'active')
							->set('accounting', $accounting)
							->set('neraca', $neraca)
							->build('neraca');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	
	
	//LAPORAN KEUANGAN
	public function laporan_keuangan()
	{
		if($this->session->userdata('logged_in'))
		{
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
						$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end);
						$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end);
						
						$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before);
						$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before);
						
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
			
			
			//lap keuangan
			$grand_total_debet =0;
			$grand_total_credit=0;
			$grand_total_before =0;
			$accounting = $this->accounting_model->get_all_accounting()->result();
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
				if($code_level0_old != $code_level0){ $neraca .= '<tr><td colspan="5">&nbsp;</td></tr><tr>'; }
				if($haschild == "1" AND $parent == "0"){
					//LEVEL 1
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					
					$neraca .= '<tr>     
								<td align="left" ><b>'.$c->accounting_code." ".$c->accounting_name.'</b></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>								
									</tr>';					
					
				}elseif($haschild == "1" AND $parent != "0"){
					//LEVEL 2					
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					$neraca .= '<tr>     
								<td align="left" ><b>&nbsp;&nbsp;&nbsp;&nbsp;'.$c->accounting_code." ".$c->accounting_name.'</b></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>
								</tr>';
				}else{	
					//LEVEL 3					
					$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end);
					
					$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					if($c->accounting_code == "3020002"){
						$account_saldo_before = $laba_rugi;
					}
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					
					//grand total dihitung dari total account level 3
					$grand_total_debet += $account_debet;
					$grand_total_credit += $account_credit;
					$grand_total_before += $account_saldo_before;
					
					
					$neraca .= '<tr>     
								<td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$c->accounting_code." ".$c->accounting_name.'</td>	
								<td class="text-right">'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</td>
								<td class="text-right">'.number_format($account_debet).'</td>
								<td class="text-right">'.number_format($account_credit).'</td>
								<td class="text-right">'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</td>
								</tr>';	
				}	
				$code_level0_old = $code_level0;
			endforeach; 
			
				//GRAND TOTAL				
				$grand_total_saldo = $grand_total_before+$grand_total_debet-$grand_total_credit;
				$neraca .= '<tfoot bgcolor="#ddd"><tr">     
							<td align="left" ><b>GRAND TOTAL</b></td>	
							<td class="text-right"><b>'.($grand_total_before < 0 ? "(".number_format(abs($grand_total_before)).")" : number_format($grand_total_before)).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_debet).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_credit).'</b></td>
							<td class="text-right"><b>'.($grand_total_saldo < 0 ? "(".number_format(abs($grand_total_saldo)).")" : number_format($grand_total_saldo)).'</b></td>
							</tr></tfoot>';	
			
			$this->template	->set('menu_title', 'Laporan Keuangan')
							->set('menu_jurnal', 'active')
							->set('accounting', $accounting)
							->set('neraca', $neraca)
							->build('neraca');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	//NERACA SIMPLE
	public function neraca_simple()
	{
		if($this->session->userdata('logged_in'))
		{
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
			
			$accounting = $this->accounting_model->get_all_accounting()->result();
			foreach($accounting as $c):
				$code = $c->accounting_code;
				$code_level0 = substr($code,0,1);
				$code_level1 = substr($code,0,3);
				$code_level2 = substr($code,0,5);
				$haschild = $c->accounting_haschild;
				$parent = $c->accounting_parent;
				
				$saldo_awal =0;
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				
				
				if($haschild == "1" AND $parent == "0"){
					//LEVEL 1
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					
					$neraca .= '<tr>     
								<td align="left" ><b>'.$c->accounting_code." ".$c->accounting_name.'</b></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>								
								</tr>';					
					
				}elseif($haschild == "1" AND $parent != "0"){
					//LEVEL 2					
					//Nothing here
				}else{	
					//LEVEL 3					
					$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end);
					
					$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					if($c->accounting_code == "3020002"){
						$account_saldo_before = $laba_rugi;
					}
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					
					//grand total dihitung dari total account level 3
					$grand_total_debet += $account_debet;
					$grand_total_credit += $account_credit;
					$grand_total_before += $account_saldo_before;
							
				}	
				
				//$grand_total_debet += $account_debet;
				//$grand_total_credit += $account_credit;
				
			endforeach; 
			
				//GRAND TOTAL				
				$grand_total_saldo = $saldo_awal+$grand_total_debet-$grand_total_credit;
				$neraca .= '<tfoot bgcolor="#ddd"><tr">     
							<td align="left" ><b>GRAND TOTAL</b></td>	
							<td class="text-right"><b>'.($grand_total_before < 0 ? "(".number_format(abs($grand_total_before)).")" : number_format($grand_total_before)).'</td>
							<td class="text-right"><b>'.number_format($grand_total_debet).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_credit).'</b></td>
							<td class="text-right"><b>'.($grand_total_saldo < 0 ? "(".number_format(abs($grand_total_saldo)).")" : number_format($grand_total_saldo)).'</b></td>
							</tr></tfoot>';	
			
			$this->template	->set('menu_title', 'Neraca Simple')
							->set('menu_jurnal', 'active')
							->set('accounting', $accounting)
							->set('neraca', $neraca)
							->build('neraca');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	//GENERAL LEDGER
	public function general_ledger()
	{
		if($this->session->userdata('logged_in'))
		{
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
			
			$accounting = $this->accounting_model->get_all_accounting()->result();
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
				if($code_level0_old != $code_level0){ $neraca .= '<tr><td colspan="5">&nbsp;</td></tr><tr>'; }
				if($haschild == "1" AND $parent == "0"){
					//LEVEL 1
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					$neraca .= '<tr>     
								<td align="left" ><a href="'.site_url($this->module.'/general_ledger_detail/'.$c->accounting_code).'" title="view details"><b>'.$c->accounting_code." ".$c->accounting_name.'</b></a></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>								
								</tr>';
				}elseif($haschild == "1" AND $parent != "0"){
					//LEVEL 2					
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					$neraca .= '<tr>     
								<td align="left" ><b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.site_url($this->module.'/general_ledger_detail/'.$c->accounting_code).'" title="view details">'.$c->accounting_code." ".$c->accounting_name.'</a></b></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>
								</tr>';
				}else{		
					//LEVEL 3					
					$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end);
					$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end);
					
					$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before);
					$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before);
					
					$account_saldo_before = $account_debet_before - $account_credit_before;
					$account_saldo = $account_saldo_before + $account_debet - $account_credit;
					
					
					//grand total dihitung dari total account level 3
					$grand_total_debet += $account_debet;
					$grand_total_credit += $account_credit;
					$grand_total_before += $account_saldo_before;	
					
					$neraca .= '<tr>     
								<td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.site_url($this->module.'/general_ledger_detail/'.$c->accounting_code).'" title="view details"><b>'.$c->accounting_code." ".$c->accounting_name.'</b></a></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>
								</tr>';
				}	
				$code_level0_old = $code_level0;
			endforeach; 
			
				//GRAND TOTAL				
				$grand_total_saldo = $saldo_awal+$grand_total_debet-$grand_total_credit;
				$neraca .= '<tfoot bgcolor="#ddd"><tr">     
							<td align="left" ><b>GRAND TOTAL</b></td>	
							<td class="text-right"><b>'.($grand_total_before < 0 ? "(".number_format(abs($grand_total_before)).")" : number_format($grand_total_before)).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_debet).'</b></td>
							<td class="text-right"><b>'.number_format($grand_total_credit).'</b></td>
							<td class="text-right"><b>'.($grand_total_saldo < 0 ? "(".number_format(abs($grand_total_saldo)).")" : number_format($grand_total_saldo)).'</b></td>
							</tr></tfoot>';	
			
			$this->template	->set('menu_title', 'General Ledger')
							->set('menu_jurnal', 'active')
							->set('accounting', $accounting)
							->set('neraca', $neraca)
							->build('general_ledger');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}

	//GENERAL LEDGER DETAIL
	public function general_ledger_detail($page='0')
	{
		if($this->session->userdata('logged_in'))
		{
			$account_no =  $this->uri->segment(3);
			$total_rows = $this->jurnal_model->count_all_jurnal_by_account($account_no);
			
			//pagination
			$config['base_url']     = site_url($this->module.'/general_ledger_detail/'.$account_no);
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 15; 
			$config['uri_segment']  = 4;
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
			$no =  $this->uri->segment(4);
			$page =  $this->uri->segment(4);
			$jurnal = $this->jurnal_model->get_all_jurnal_by_account($config['per_page'] , $page, $account_no);
			$this->template	->set('menu_title', 'General Ledger ('.$account_no.')')
							->set('menu_jurnal', 'active')
							->set('jurnal', $jurnal)
							->set('no', $no)
							->set('account_no', $account_no)
							->set('config', $config)
							->build('general_ledger_detail');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}

}