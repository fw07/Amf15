<?php

class Konsolidasi extends Front_Controller{
	
	private $per_page 	= '15';
	private $title 		= 'Konsolidasi';
	private $module 	= 'konsolidasi';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('accounting_model');	
		$this->load->model('jurnal_model');		
		$this->load->library('pagination');		
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			redirect('konsolidasi/neraca', 'refresh');
		}
		else
		{
			 //If no session, redirect to login page
			 redirect('login', 'refresh');
		}
	}
	
	private function hitung_laba_rugi($date_start,$date_end,$user_branch){
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
					
					$date_end_before = strtotime($date_start);
					$date_end_before = strtotime("-1 day", $date_end_before); 
					$date_end_before = date('Y-m-d', $date_end_before);			
					$date_start_before = "2013-01-01";
					
					if($haschild == "1" AND $parent == "0"){
					}elseif($haschild == "1" AND $parent != "0"){
					}else{	
						//LEVEL 3					
						$account_debet = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$user_branch);
						$account_credit = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$user_branch);
						
						$account_debet_before = $this->jurnal_model->sum_account_debet_by_date($code,$date_start_before,$date_end_before,$user_branch);
						$account_credit_before = $this->jurnal_model->sum_account_credit_by_date($code,$date_start_before,$date_end_before,$user_branch);
						
						//$account_saldo_before = $account_debet_before - $account_credit_before;
						//$account_saldo = $account_saldo_before + $account_debet - $account_credit;
						
						if($code_level0 == "4"){					
							$account_saldo_before = $account_credit_before -  $account_debet_before;
							$account_saldo = $account_saldo_before + $account_credit - $account_debet;
						}elseif($code_level0 == "5"){
							$account_saldo_before = $account_debet_before - $account_credit_before;
							$account_saldo = $account_saldo_before + $account_debet - $account_credit;					
						}
						
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
					//GRAND TOTAL				
				$grand_total_saldo = $saldo_awal+$grand_total_debet-$grand_total_credit;
				$grand_total_pendapatan_saldo = $grand_total_pendapatan_before-$grand_total_pendapatan_debet+$grand_total_pendapatan_credit;
				$grand_total_beban_saldo = $grand_total_beban_before+$grand_total_beban_debet-$grand_total_beban_credit;
				//$laba_rugi = ($grand_total_pendapatan_before+$grand_total_pendapatan_credit) - ($grand_total_beban_before+$grand_total_beban_debet);
				
				$laba_rugi_before = $grand_total_pendapatan_before - $grand_total_beban_before;	
				$laba_rugi = $laba_rugi_before + $grand_total_pendapatan_saldo - $grand_total_beban_saldo;			
				//End of Hitung Laba Rugi
				
				return $laba_rugi;
	}
	
	
	//NERACA
	public function neraca()
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
			
			
				
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				$saldo_awal =0;
				
			//PENDAPATAN
			$print .= '	<tr><td align="left" ><b>ASET</b></td>	<td colspan="8" ></td></tr>';
			$print .= '	<tr><td align="left" ><b>ASET LANCAR</b></td>	<td colspan="8" ></td></tr>';
			
				//Kas
				//1010000
				$code = "1010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1010000[$branch] = $account_1010000_debet[$branch] - $account_1010000_credit[$branch] ;
					$account_1010000_konsolidasi += $account_1010000[$branch];
					$account_kas[$branch] += $account_1010000[$branch];				
					$account_aset_lancar[$branch] += $account_1010000[$branch];	
				}
				//1020000 
				$code = "1020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020000[$branch] = $account_1020000_debet[$branch] - $account_1020000_credit[$branch];
					$account_1020000_konsolidasi += $account_1020000[$branch];
					$account_kas[$branch] += $account_1020000[$branch];		
					$account_aset_lancar[$branch] += $account_1020000[$branch];
				}
				//Total Kas
				$account_kas_konsolidasi = $account_1010000_konsolidasi + $account_1020000_konsolidasi;
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Kas dan setara Kas</td>
								<td align="right" >'.($account_kas_konsolidasi < 0 ? "(".number_format(abs($account_kas_konsolidasi)).")" : number_format($account_kas_konsolidasi)).'</td>
								<td align="right" >'.($account_kas[0] < 0 ? "(".number_format(abs($account_kas[0])).")" : number_format($account_kas[0])).'</td>
								<td align="right" >'.($account_kas[1] < 0 ? "(".number_format(abs($account_kas[1])).")" : number_format($account_kas[1])).'</td>
								<td align="right" >'.($account_kas[4] < 0 ? "(".number_format(abs($account_kas[4])).")" : number_format($account_kas[4])).'</td>
								<td align="right" >'.($account_kas[3] < 0 ? "(".number_format(abs($account_kas[3])).")" : number_format($account_kas[3])).'</td>
								<td align="right" >'.($account_kas[2] < 0 ? "(".number_format(abs($account_kas[2])).")" : number_format($account_kas[2])).'</td>
								<td align="right" >'.($account_kas[5] < 0 ? "(".number_format(abs($account_kas[5])).")" : number_format($account_kas[5])).'</td>
								<td align="right" >'.($account_kas[6] < 0 ? "(".number_format(abs($account_kas[6])).")" : number_format($account_kas[6])).'</td>
							</tr>';	
							
				//Piutang MBA 1030102
				$code = "1030102";
				for($branch=0; $branch <=6; $branch++){
					$account_1030102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030102[$branch] = $account_1030102_debet[$branch] - $account_1030102_credit[$branch];
					$account_1030102_konsolidasi += $account_1030102[$branch];
					$account_piutang_pembiayaan[$branch] += $account_1030102[$branch];			
					$account_aset_lancar[$branch] += $account_1030102[$branch];
				}
				//Piutang IJA 1030103
				$code = "1030103";
				for($branch=0; $branch <=6; $branch++){
					$account_1030103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030103[$branch] = $account_1030103_debet[$branch] - $account_1030103_credit[$branch];
					$account_1030103_konsolidasi += $account_1030103[$branch];
					$account_piutang_pembiayaan[$branch] += $account_1030103[$branch];				
					$account_aset_lancar[$branch] += $account_1030103[$branch];
				}
				//Piutang QH 1030104
				$code = "1030104";
				for($branch=0; $branch <=6; $branch++){
					$account_1030104_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030104_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030104[$branch] = $account_1030104_debet[$branch] - $account_1030104_credit[$branch];
					$account_1030104_konsolidasi += $account_1030104[$branch];

					$account_piutang_pembiayaan[$branch] += $account_1030104[$branch];					
					$account_aset_lancar[$branch] += $account_1030104[$branch];
				}
				//Piutang Pembiayaan Lembaga 1030200
				
				
				
				//Piutang QH 1030504
				$code = "1030504";
				for($branch=0; $branch <=6; $branch++){
					$account_1030504_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030504_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030104[$branch] = $account_1030504_debet[$branch] - $account_1030504_credit[$branch];
					$account_1030104_konsolidasi += $account_1030104[$branch];
					$account_piutang_pembiayaan[$branch] += $account_1030104[$branch];					
					$account_aset_lancar[$branch] += $account_1030104[$branch];
				}
				
				$code = "1030200";
				$code_level2 = substr($code,0,5);
				for($branch=0; $branch <=6; $branch++){
					$account_1030200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030200[$branch] = $account_1030200_debet[$branch] - $account_1030200_credit[$branch];
					$account_1030104[$branch] += $account_1030200[$branch];
					$account_1030104_konsolidasi += $account_1030200[$branch];

					$account_piutang_pembiayaan[$branch] += $account_1030200[$branch];					
					$account_aset_lancar[$branch] += $account_1030200[$branch];
				}
				
				/*
				//Piutang Cabang 1030400
				$code = "1030400";
				$code_level2 = substr($code,0,5);
				for($branch=0; $branch <=6; $branch++){
					$account_1030400_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030400_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030400[$branch] = $account_1030400_debet[$branch] - $account_1030400_credit[$branch];
					//$account_1030400_konsolidasi += $account_1030400[$branch];
					$account_1030400_konsolidasi = 0; 
					$account_piutang_pembiayaan[$branch] += $account_1030400[$branch];
					$account_aset_lancar[$branch] += $account_1030400[$branch];
				}
				*/
				
				
				$account_piutang_pembiayaan_konsolidasi = $account_1030102_konsolidasi  + $account_1030103_konsolidasi + + $account_1030104_konsolidasi + $account_1030400_konsolidasi;
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Piutang Pembiayaan</b></td>
								<td align="right" >'.($account_piutang_pembiayaan_konsolidasi < 0 ? "(".number_format(abs($account_piutang_pembiayaan_konsolidasi)).")" : number_format($account_piutang_pembiayaan_konsolidasi)).'</td>
								<td align="right" >'.($account_piutang_pembiayaan[0] < 0 ? "(".number_format(abs($account_piutang_pembiayaan[0])).")" : number_format($account_piutang_pembiayaan[0])).'</td>
								<td align="right" >'.($account_piutang_pembiayaan[1] < 0 ? "(".number_format(abs($account_piutang_pembiayaan[1])).")" : number_format($account_piutang_pembiayaan[1])).'</td>
								<td align="right" >'.($account_piutang_pembiayaan[4] < 0 ? "(".number_format(abs($account_piutang_pembiayaan[4])).")" : number_format($account_piutang_pembiayaan[4])).'</td>
								<td align="right" >'.($account_piutang_pembiayaan[3] < 0 ? "(".number_format(abs($account_piutang_pembiayaan[3])).")" : number_format($account_piutang_pembiayaan[3])).'</td>
								<td align="right" >'.($account_piutang_pembiayaan[2] < 0 ? "(".number_format(abs($account_piutang_pembiayaan[2])).")" : number_format($account_piutang_pembiayaan[2])).'</td>
								<td align="right" >'.($account_piutang_pembiayaan[5] < 0 ? "(".number_format(abs($account_piutang_pembiayaan[5])).")" : number_format($account_piutang_pembiayaan[5])).'</td>
								<td align="right" >'.($account_piutang_pembiayaan[6] < 0 ? "(".number_format(abs($account_piutang_pembiayaan[6])).")" : number_format($account_piutang_pembiayaan[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Murabahah</td>
								<td align="right" >'.($account_1030102_konsolidasi < 0 ? "(".number_format(abs($account_1030102_konsolidasi)).")" : number_format($account_1030102_konsolidasi)).'</td>
								<td align="right" >'.($account_1030102[0] < 0 ? "(".number_format(abs($account_1030102[0])).")" : number_format($account_1030102[0])).'</td>
								<td align="right" >'.($account_1030102[1] < 0 ? "(".number_format(abs($account_1030102[1])).")" : number_format($account_1030102[1])).'</td>
								<td align="right" >'.($account_1030102[4] < 0 ? "(".number_format(abs($account_1030102[4])).")" : number_format($account_1030102[4])).'</td>
								<td align="right" >'.($account_1030102[3] < 0 ? "(".number_format(abs($account_1030102[3])).")" : number_format($account_1030102[3])).'</td>
								<td align="right" >'.($account_1030102[2] < 0 ? "(".number_format(abs($account_1030102[2])).")" : number_format($account_1030102[2])).'</td>
								<td align="right" >'.($account_1030102[5] < 0 ? "(".number_format(abs($account_1030102[5])).")" : number_format($account_1030102[5])).'</td>
								<td align="right" >'.($account_1030102[6] < 0 ? "(".number_format(abs($account_1030102[6])).")" : number_format($account_1030102[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ijarah</td>
								<td align="right" >'.($account_1030103_konsolidasi < 0 ? "(".number_format(abs($account_1030103_konsolidasi)).")" : number_format($account_1030103_konsolidasi)).'</td>
								<td align="right" >'.($account_1030103[0] < 0 ? "(".number_format(abs($account_1030103[0])).")" : number_format($account_1030103[0])).'</td>
								<td align="right" >'.($account_1030103[1] < 0 ? "(".number_format(abs($account_1030103[1])).")" : number_format($account_1030103[1])).'</td>
								<td align="right" >'.($account_1030103[4] < 0 ? "(".number_format(abs($account_1030103[4])).")" : number_format($account_1030103[4])).'</td>
								<td align="right" >'.($account_1030103[3] < 0 ? "(".number_format(abs($account_1030103[3])).")" : number_format($account_1030103[3])).'</td>
								<td align="right" >'.($account_1030103[2] < 0 ? "(".number_format(abs($account_1030103[2])).")" : number_format($account_1030103[2])).'</td>
								<td align="right" >'.($account_1030103[5] < 0 ? "(".number_format(abs($account_1030103[5])).")" : number_format($account_1030103[5])).'</td>
								<td align="right" >'.($account_1030103[6] < 0 ? "(".number_format(abs($account_1030103[6])).")" : number_format($account_1030103[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Qadrul Hasan</td>
								<td align="right" >'.($account_1030104_konsolidasi < 0 ? "(".number_format(abs($account_1030104_konsolidasi)).")" : number_format($account_1030104_konsolidasi)).'</td>
								<td align="right" >'.($account_1030104[0] < 0 ? "(".number_format(abs($account_1030104[0])).")" : number_format($account_1030104[0])).'</td>
								<td align="right" >'.($account_1030104[1] < 0 ? "(".number_format(abs($account_1030104[1])).")" : number_format($account_1030104[1])).'</td>
								<td align="right" >'.($account_1030104[4] < 0 ? "(".number_format(abs($account_1030104[4])).")" : number_format($account_1030104[4])).'</td>
								<td align="right" >'.($account_1030104[3] < 0 ? "(".number_format(abs($account_1030104[3])).")" : number_format($account_1030104[3])).'</td>
								<td align="right" >'.($account_1030104[2] < 0 ? "(".number_format(abs($account_1030104[2])).")" : number_format($account_1030104[2])).'</td>
								<td align="right" >'.($account_1030104[5] < 0 ? "(".number_format(abs($account_1030104[5])).")" : number_format($account_1030104[5])).'</td>
								<td align="right" >'.($account_1030104[6] < 0 ? "(".number_format(abs($account_1030104[6])).")" : number_format($account_1030104[6])).'</td>
							</tr>';				
				/*$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cabang</td>
								<td align="right" >'.($account_1030400_konsolidasi < 0 ? "(".number_format(abs($account_1030400_konsolidasi)).")" : number_format($account_1030400_konsolidasi)).'</td>
								<td align="right" >'.($account_1030400[0] < 0 ? "(".number_format(abs($account_1030400[0])).")" : number_format($account_1030400[0])).'</td>
								<td align="right" >'.($account_1030400[1] < 0 ? "(".number_format(abs($account_1030400[1])).")" : number_format($account_1030400[1])).'</td>
								<td align="right" >'.($account_1030400[4] < 0 ? "(".number_format(abs($account_1030400[4])).")" : number_format($account_1030400[4])).'</td>
								<td align="right" >'.($account_1030400[3] < 0 ? "(".number_format(abs($account_1030400[3])).")" : number_format($account_1030400[3])).'</td>
								<td align="right" >'.($account_1030400[2] < 0 ? "(".number_format(abs($account_1030400[2])).")" : number_format($account_1030400[2])).'</td>
								<td align="right" >'.($account_1030400[5] < 0 ? "(".number_format(abs($account_1030400[5])).")" : number_format($account_1030400[5])).'</td>
								<td align="right" >'.($account_1030400[6] < 0 ? "(".number_format(abs($account_1030400[6])).")" : number_format($account_1030400[6])).'</td>
							</tr>';
				*/			
				//Beban dibayar dimuka 1050000
				$code = "1050000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1050000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1050000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1050000[$branch] = $account_1050000_debet[$branch] - $account_1050000_credit[$branch];
					$account_1050000_konsolidasi += $account_1050000[$branch];
					$account_aset_lancar[$branch] += $account_1050000[$branch];
				}
				//Persediaan Barang Cetakan 1060000
				$code = "1060000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1060000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1060000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1060000[$branch] = $account_1060000_debet[$branch] - $account_1060000_credit[$branch];
					$account_1060000_konsolidasi += $account_1060000[$branch];
					$account_aset_lancar[$branch] += $account_1060000[$branch];
				}
				$account_aset_lancar_konsolidasi = $account_kas_konsolidasi + $account_piutang_pembiayaan_konsolidasi + $account_1060000_konsolidasi+$account_1050000_konsolidasi;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Beban dibayar dimuka</td>
								<td align="right" >'.($account_1050000_konsolidasi < 0 ? "(".number_format(abs($account_1050000_konsolidasi)).")" : number_format($account_1050000_konsolidasi)).'</td>
								<td align="right" >'.($account_1050000[0] < 0 ? "(".number_format(abs($account_1050000[0])).")" : number_format($account_1050000[0])).'</td>
								<td align="right" >'.($account_1050000[1] < 0 ? "(".number_format(abs($account_1050000[1])).")" : number_format($account_1050000[1])).'</td>
								<td align="right" >'.($account_1050000[4] < 0 ? "(".number_format(abs($account_1050000[4])).")" : number_format($account_1050000[4])).'</td>
								<td align="right" >'.($account_1050000[3] < 0 ? "(".number_format(abs($account_1050000[3])).")" : number_format($account_1050000[3])).'</td>
								<td align="right" >'.($account_1050000[2] < 0 ? "(".number_format(abs($account_1050000[2])).")" : number_format($account_1050000[2])).'</td>
								<td align="right" >'.($account_1050000[5] < 0 ? "(".number_format(abs($account_1050000[5])).")" : number_format($account_1050000[5])).'</td>
								<td align="right" >'.($account_1050000[6] < 0 ? "(".number_format(abs($account_1050000[6])).")" : number_format($account_1050000[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Persediaan Barang Cetakan</td>
								<td align="right" class="border_btm">'.($account_1060000_konsolidasi < 0 ? "(".number_format(abs($account_1060000_konsolidasi)).")" : number_format($account_1060000_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_1060000[0] < 0 ? "(".number_format(abs($account_1060000[0])).")" : number_format($account_1060000[0])).'</td>
								<td align="right" class="border_btm">'.($account_1060000[1] < 0 ? "(".number_format(abs($account_1060000[1])).")" : number_format($account_1060000[1])).'</td>
								<td align="right" class="border_btm">'.($account_1060000[4] < 0 ? "(".number_format(abs($account_1060000[4])).")" : number_format($account_1060000[4])).'</td>
								<td align="right" class="border_btm">'.($account_1060000[3] < 0 ? "(".number_format(abs($account_1060000[3])).")" : number_format($account_1060000[3])).'</td>
								<td align="right" class="border_btm">'.($account_1060000[2] < 0 ? "(".number_format(abs($account_1060000[2])).")" : number_format($account_1060000[2])).'</td>
								<td align="right" class="border_btm">'.($account_1060000[5] < 0 ? "(".number_format(abs($account_1060000[5])).")" : number_format($account_1060000[5])).'</td>
								<td align="right" class="border_btm">'.($account_1060000[6] < 0 ? "(".number_format(abs($account_1060000[6])).")" : number_format($account_1060000[6])).'</td>
							</tr>';
				
				$print .= '	<tr><td align="left" >Jumlah Aset Lancar</td>
								<td align="right" class="border_btm">'.($account_aset_lancar_konsolidasi < 0 ? "(".number_format(abs($account_aset_lancar_konsolidasi)).")" : number_format($account_aset_lancar_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_aset_lancar[0] < 0 ? "(".number_format(abs($account_aset_lancar[0])).")" : number_format($account_aset_lancar[0])).'</td>
								<td align="right" class="border_btm">'.($account_aset_lancar[1] < 0 ? "(".number_format(abs($account_aset_lancar[1])).")" : number_format($account_aset_lancar[1])).'</td>
								<td align="right" class="border_btm">'.($account_aset_lancar[4] < 0 ? "(".number_format(abs($account_aset_lancar[4])).")" : number_format($account_aset_lancar[4])).'</td>
								<td align="right" class="border_btm">'.($account_aset_lancar[3] < 0 ? "(".number_format(abs($account_aset_lancar[3])).")" : number_format($account_aset_lancar[3])).'</td>
								<td align="right" class="border_btm">'.($account_aset_lancar[2] < 0 ? "(".number_format(abs($account_aset_lancar[2])).")" : number_format($account_aset_lancar[2])).'</td>
								<td align="right" class="border_btm">'.($account_aset_lancar[5] < 0 ? "(".number_format(abs($account_aset_lancar[5])).")" : number_format($account_aset_lancar[5])).'</td>
								<td align="right" class="border_btm">'.($account_aset_lancar[6] < 0 ? "(".number_format(abs($account_aset_lancar[6])).")" : number_format($account_aset_lancar[6])).'</td>
							</tr>';
							
				//ASET TIDAK LANCAR
				$print .= '	<tr><td align="left" colspan="8" ></td></tr>';
				$print .= '	<tr><td align="left" ><b>ASET TIDAK LANCAR</b></td>	<td colspan="8" ></td></tr>';
				
				//Aset Tetap setelah dikurangi = 1080301 + 1080302				
				$code = "1080301";
				for($branch=0; $branch <=6; $branch++){
					$account_1080301_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1080301_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1080301[$branch] = $account_1080301_debet[$branch] - $account_1080301_credit[$branch];
					$account_1080301_konsolidasi += $account_1080301[$branch];
					$account_aset_tetap[$branch] += $account_1080301[$branch];					
					$account_aset_tidak_lancar[$branch] += $account_1080301[$branch];
				}
				$code = "1080302";
				for($branch=0; $branch <=6; $branch++){
					$account_1080302_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1080302_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1080302[$branch] = $account_1080302_debet[$branch] - $account_1080302_credit[$branch];
					$account_1080302_konsolidasi += $account_1080302[$branch];
					$account_aset_tetap[$branch] += $account_1080302[$branch];					
					$account_aset_tidak_lancar[$branch] += $account_1080302[$branch];
				}
				$account_aset_tetap_konsolidasi = $account_1080301_konsolidasi + $account_1080302_konsolidasi;
				
				//Aset Lain	1090000
				$code = "1090000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1090000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1090000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1090000[$branch] = $account_1090000_debet[$branch] - $account_1090000_credit[$branch];
					$account_aset_lain_konsolidasi += $account_1090000[$branch];
					$account_aset_tidak_lancar[$branch] += $account_1090000[$branch];
				}
				
				$account_aset_tidak_lancar_konsolidasi = $account_aset_tetap_konsolidasi + $account_aset_lain_konsolidasi;
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Aset Tetap - setelah dikurangi</td>
								<td align="right" >'.($account_aset_tetap_konsolidasi < 0 ? "(".number_format(abs($account_aset_tetap_konsolidasi)).")" : number_format($account_aset_tetap_konsolidasi)).'</td>
								<td align="right" >'.($account_aset_tetap[0] < 0 ? "(".number_format(abs($account_aset_tetap[0])).")" : number_format($account_aset_tetap[0])).'</td>
								<td align="right" >'.($account_aset_tetap[1] < 0 ? "(".number_format(abs($account_aset_tetap[1])).")" : number_format($account_aset_tetap[1])).'</td>
								<td align="right" >'.($account_aset_tetap[4] < 0 ? "(".number_format(abs($account_aset_tetap[4])).")" : number_format($account_aset_tetap[4])).'</td>
								<td align="right" >'.($account_aset_tetap[3] < 0 ? "(".number_format(abs($account_aset_tetap[3])).")" : number_format($account_aset_tetap[3])).'</td>
								<td align="right" >'.($account_aset_tetap[2] < 0 ? "(".number_format(abs($account_aset_tetap[2])).")" : number_format($account_aset_tetap[2])).'</td>
								<td align="right" >'.($account_aset_tetap[5] < 0 ? "(".number_format(abs($account_aset_tetap[5])).")" : number_format($account_aset_tetap[5])).'</td>
								<td align="right" >'.($account_aset_tetap[6] < 0 ? "(".number_format(abs($account_aset_tetap[6])).")" : number_format($account_aset_tetap[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Aset Lain</td>
								<td align="right" class="border_btm">'.($account_aset_lain_konsolidasi < 0 ? "(".number_format(abs($account_aset_lain_konsolidasi)).")" : number_format($account_aset_lain_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_1090000[0] < 0 ? "(".number_format(abs($account_1090000[0])).")" : number_format($account_1090000[0])).'</td>
								<td align="right" class="border_btm">'.($account_1090000[1] < 0 ? "(".number_format(abs($account_1090000[1])).")" : number_format($account_1090000[1])).'</td>
								<td align="right" class="border_btm">'.($account_1090000[4] < 0 ? "(".number_format(abs($account_1090000[4])).")" : number_format($account_1090000[4])).'</td>
								<td align="right" class="border_btm">'.($account_1090000[3] < 0 ? "(".number_format(abs($account_1090000[3])).")" : number_format($account_1090000[3])).'</td>
								<td align="right" class="border_btm">'.($account_1090000[2] < 0 ? "(".number_format(abs($account_1090000[2])).")" : number_format($account_1090000[2])).'</td>
								<td align="right" class="border_btm">'.($account_1090000[5] < 0 ? "(".number_format(abs($account_1090000[5])).")" : number_format($account_1090000[5])).'</td>
								<td align="right" class="border_btm">'.($account_1090000[6] < 0 ? "(".number_format(abs($account_1090000[6])).")" : number_format($account_1090000[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >Jumlah Aset Tidak Lancar</td>
								<td align="right" class="border_btm">'.($account_aset_tidak_lancar_konsolidasi < 0 ? "(".number_format(abs($account_aset_tidak_lancar_konsolidasi)).")" : number_format($account_aset_tidak_lancar_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_aset_tidak_lancar[0] < 0 ? "(".number_format(abs($account_aset_tidak_lancar[0])).")" : number_format($account_aset_tidak_lancar[0])).'</td>
								<td align="right" class="border_btm">'.($account_aset_tidak_lancar[1] < 0 ? "(".number_format(abs($account_aset_tidak_lancar[1])).")" : number_format($account_aset_tidak_lancar[1])).'</td>
								<td align="right" class="border_btm">'.($account_aset_tidak_lancar[4] < 0 ? "(".number_format(abs($account_aset_tidak_lancar[4])).")" : number_format($account_aset_tidak_lancar[4])).'</td>
								<td align="right" class="border_btm">'.($account_aset_tidak_lancar[3] < 0 ? "(".number_format(abs($account_aset_tidak_lancar[3])).")" : number_format($account_aset_tidak_lancar[3])).'</td>
								<td align="right" class="border_btm">'.($account_aset_tidak_lancar[2] < 0 ? "(".number_format(abs($account_aset_tidak_lancar[2])).")" : number_format($account_aset_tidak_lancar[2])).'</td>
								<td align="right" class="border_btm">'.($account_aset_tidak_lancar[5] < 0 ? "(".number_format(abs($account_aset_tidak_lancar[5])).")" : number_format($account_aset_tidak_lancar[5])).'</td>
								<td align="right" class="border_btm">'.($account_aset_tidak_lancar[6] < 0 ? "(".number_format(abs($account_aset_tidak_lancar[6])).")" : number_format($account_aset_tidak_lancar[6])).'</td>
							</tr>';
				
				
				//JUMLAH ASET
				for($branch=0; $branch <=6; $branch++){
					$account_aset[$branch] += $account_aset_lancar[$branch] + $account_aset_tidak_lancar[$branch];
					$account_aset_konsolidasi += $account_aset_lancar[$branch] + $account_aset_tidak_lancar[$branch];
				}
				$account_aset_konsolidasi = $account_aset_lancar_konsolidasi + $account_aset_tidak_lancar_konsolidasi;
				
				$account_aset_tidak_lancar_konsolidasi = $account_aset_tetap_konsolidasi + $account_aset_lain_konsolidasi;
				$print .= '	<tr><td>&nbsp;</td><td align="left" colspan="8" class="border_btm"></td></tr>';
				$print .= '	<tr><td align="left" ><b>JUMLAH ASET</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_aset_konsolidasi < 0 ? "(".number_format(abs($account_aset_konsolidasi)).")" : number_format($account_aset_konsolidasi)).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_aset[0] < 0 ? "(".number_format(abs($account_aset[0])).")" : number_format($account_aset[0])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_aset[1] < 0 ? "(".number_format(abs($account_aset[1])).")" : number_format($account_aset[1])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_aset[4] < 0 ? "(".number_format(abs($account_aset[4])).")" : number_format($account_aset[4])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_aset[3] < 0 ? "(".number_format(abs($account_aset[3])).")" : number_format($account_aset[3])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_aset[2] < 0 ? "(".number_format(abs($account_aset[2])).")" : number_format($account_aset[2])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_aset[5] < 0 ? "(".number_format(abs($account_aset[5])).")" : number_format($account_aset[5])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_aset[6] < 0 ? "(".number_format(abs($account_aset[6])).")" : number_format($account_aset[6])).'</b></td>
							</tr>';
							
				//LIABILITAS
				$print .= '	<tr><td colspan="8" ></td>&nbsp;</tr>';
				$print .= '	<tr><td align="left" ><b>LIABILITAS DAN EKUITAS</b></td>	<td colspan="8" ></td></tr>';
				$print .= '	<tr><td align="left" ><b>LIABILITAS JANGKA PENDEK</b></td>	<td colspan="8" ></td></tr>';
				
				//Simpanan Anggota 2010000
				$code = "2010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010000[$branch] = $account_2010000_credit[$branch] - $account_2010000_debet[$branch];
					$account_2010000_konsolidasi += $account_2010000[$branch];
					$account_liabilitas[$branch] += $account_2010000[$branch];
				}
				
				//Simpanan Berjangka 2020000
				$code = "2020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2020000[$branch] = $account_2020000_credit[$branch] - $account_2020000_debet[$branch];
					$account_2020000_konsolidasi += $account_2020000[$branch];
					$account_liabilitas[$branch] += $account_2020000[$branch];
				}
				
				//Hutang Pembiayaan 2030000
				$code = "2030000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2030000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2030000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2030000[$branch] = $account_2030000_credit[$branch] - $account_2030000_debet[$branch];
					if($branch!=0) { $account_2030000[$branch] = 0; }
					$account_2030000_konsolidasi += $account_2030000[$branch];
					$account_liabilitas[$branch] += $account_2030000[$branch];
				}
				
				//Hutang Pembiayaan Kantor Pusat 2030200
				/*$code = "2030200";
				$code_level2 = substr($code,0,5);
				for($branch=0; $branch <=6; $branch++){
					$account_2030200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					$account_2030200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);
					$account_2030200[$branch] = $account_2030200_credit[$branch] - $account_2030200_debet[$branch];
					if($branch==0) { $account_2030200[0] = 0; }
					$account_2030200_konsolidasi += $account_2030200[$branch];
					$account_liabilitas[$branch] += $account_2030200[$branch];
				}
				*/
				//Hutang Leasing 2040000
				$code = "2040000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2040000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2040000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2040000[$branch] = $account_2040000_credit[$branch] - $account_2040000_debet[$branch];
					$account_2040000_konsolidasi += $account_2040000[$branch];
					$account_liabilitas[$branch] += $account_2040000[$branch];
				}
				
				//Hutang Lain-lain 2050000
				$code = "2050000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2050000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2050000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2050000[$branch] = $account_2050000_credit[$branch] - $account_2050000_debet[$branch];
					$account_2050000_konsolidasi += $account_2050000[$branch];
					$account_liabilitas[$branch] += $account_2050000[$branch];
				}
				
				for($branch=0; $branch <=6; $branch++){
					$account_liabilitas_konsolidasi += $account_liabilitas[$branch];
				}
				
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Simpanan Anggota</td>
								<td align="right" >'.($account_2010000_konsolidasi < 0 ? "(".number_format(abs($account_2010000_konsolidasi)).")" : number_format($account_2010000_konsolidasi)).'</td>
								<td align="right" >'.($account_2010000[0] < 0 ? "(".number_format(abs($account_2010000[0])).")" : number_format($account_2010000[0])).'</td>
								<td align="right" >'.($account_2010000[1] < 0 ? "(".number_format(abs($account_2010000[1])).")" : number_format($account_2010000[1])).'</td>
								<td align="right" >'.($account_2010000[4] < 0 ? "(".number_format(abs($account_2010000[4])).")" : number_format($account_2010000[4])).'</td>
								<td align="right" >'.($account_2010000[3] < 0 ? "(".number_format(abs($account_2010000[3])).")" : number_format($account_2010000[3])).'</td>
								<td align="right" >'.($account_2010000[2] < 0 ? "(".number_format(abs($account_2010000[2])).")" : number_format($account_2010000[2])).'</td>
								<td align="right" >'.($account_2010000[5] < 0 ? "(".number_format(abs($account_2010000[5])).")" : number_format($account_2010000[5])).'</td>
								<td align="right" >'.($account_2010000[6] < 0 ? "(".number_format(abs($account_2010000[6])).")" : number_format($account_2010000[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Simpanan Berjangka</td>
								<td align="right" >'.($account_2020000_konsolidasi < 0 ? "(".number_format(abs($account_2020000_konsolidasi)).")" : number_format($account_2020000_konsolidasi)).'</td>
								<td align="right" >'.($account_2020000[0] < 0 ? "(".number_format(abs($account_2020000[0])).")" : number_format($account_2020000[0])).'</td>
								<td align="right" >'.($account_2020000[1] < 0 ? "(".number_format(abs($account_2020000[1])).")" : number_format($account_2020000[1])).'</td>
								<td align="right" >'.($account_2020000[4] < 0 ? "(".number_format(abs($account_2020000[4])).")" : number_format($account_2020000[4])).'</td>
								<td align="right" >'.($account_2020000[3] < 0 ? "(".number_format(abs($account_2020000[3])).")" : number_format($account_2020000[3])).'</td>
								<td align="right" >'.($account_2020000[2] < 0 ? "(".number_format(abs($account_2020000[2])).")" : number_format($account_2020000[2])).'</td>
								<td align="right" >'.($account_2020000[5] < 0 ? "(".number_format(abs($account_2020000[5])).")" : number_format($account_2020000[5])).'</td>
								<td align="right" >'.($account_2020000[6] < 0 ? "(".number_format(abs($account_2020000[6])).")" : number_format($account_2020000[6])).'</td>
							</tr>';
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Hutang Pembiayaan</td>
								<td align="right" >'.($account_2030000_konsolidasi < 0 ? "(".number_format(abs($account_2030000_konsolidasi)).")" : number_format($account_2030000_konsolidasi)).'</td>
								<td align="right" >'.($account_2030000[0] < 0 ? "(".number_format(abs($account_2030000[0])).")" : number_format($account_2030000[0])).'</td>
								<td align="right" >'.($account_2030000[1] < 0 ? "(".number_format(abs($account_2030000[1])).")" : number_format($account_2030000[1])).'</td>
								<td align="right" >'.($account_2030000[4] < 0 ? "(".number_format(abs($account_2030000[4])).")" : number_format($account_2030000[4])).'</td>
								<td align="right" >'.($account_2030000[3] < 0 ? "(".number_format(abs($account_2030000[3])).")" : number_format($account_2030000[3])).'</td>
								<td align="right" >'.($account_2030000[2] < 0 ? "(".number_format(abs($account_2030000[2])).")" : number_format($account_2030000[2])).'</td>
								<td align="right" >'.($account_2030000[5] < 0 ? "(".number_format(abs($account_2030000[5])).")" : number_format($account_2030000[5])).'</td>
								<td align="right" >'.($account_2030000[6] < 0 ? "(".number_format(abs($account_2030000[6])).")" : number_format($account_2030000[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Hutang Pembiayaan K. Pusat</td>
								<td align="right" >'.($account_2030200_konsolidasi < 0 ? "(".number_format(abs($account_2030200_konsolidasi)).")" : number_format($account_2030200_konsolidasi)).'</td>
								<td align="right" >'.($account_2030200[0] < 0 ? "(".number_format(abs($account_2030200[0])).")" : number_format($account_2030200[0])).'</td>
								<td align="right" >'.($account_2030200[1] < 0 ? "(".number_format(abs($account_2030200[1])).")" : number_format($account_2030200[1])).'</td>
								<td align="right" >'.($account_2030200[4] < 0 ? "(".number_format(abs($account_2030200[4])).")" : number_format($account_2030200[4])).'</td>
								<td align="right" >'.($account_2030200[3] < 0 ? "(".number_format(abs($account_2030200[3])).")" : number_format($account_2030200[3])).'</td>
								<td align="right" >'.($account_2030200[2] < 0 ? "(".number_format(abs($account_2030200[2])).")" : number_format($account_2030200[2])).'</td>
								<td align="right" >'.($account_2030200[5] < 0 ? "(".number_format(abs($account_2030200[5])).")" : number_format($account_2030200[5])).'</td>
								<td align="right" >'.($account_2030200[6] < 0 ? "(".number_format(abs($account_2030200[6])).")" : number_format($account_2030200[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Hutang Leasing</td>
								<td align="right" >'.($account_2040000_konsolidasi < 0 ? "(".number_format(abs($account_2040000_konsolidasi)).")" : number_format($account_2040000_konsolidasi)).'</td>
								<td align="right" >'.($account_2040000[0] < 0 ? "(".number_format(abs($account_2040000[0])).")" : number_format($account_2040000[0])).'</td>
								<td align="right" >'.($account_2040000[1] < 0 ? "(".number_format(abs($account_2040000[1])).")" : number_format($account_2040000[1])).'</td>
								<td align="right" >'.($account_2040000[4] < 0 ? "(".number_format(abs($account_2040000[4])).")" : number_format($account_2040000[4])).'</td>
								<td align="right" >'.($account_2040000[3] < 0 ? "(".number_format(abs($account_2040000[3])).")" : number_format($account_2040000[3])).'</td>
								<td align="right" >'.($account_2040000[2] < 0 ? "(".number_format(abs($account_2040000[2])).")" : number_format($account_2040000[2])).'</td>
								<td align="right" >'.($account_2040000[5] < 0 ? "(".number_format(abs($account_2040000[5])).")" : number_format($account_2040000[5])).'</td>
								<td align="right" >'.($account_2040000[6] < 0 ? "(".number_format(abs($account_2040000[6])).")" : number_format($account_2040000[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Hutang Lain-lain</td>
								<td align="right" class="border_btm">'.($account_2050000_konsolidasi < 0 ? "(".number_format(abs($account_2050000_konsolidasi)).")" : number_format($account_2050000_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_2050000[0] < 0 ? "(".number_format(abs($account_2050000[0])).")" : number_format($account_2050000[0])).'</td>
								<td align="right" class="border_btm">'.($account_2050000[1] < 0 ? "(".number_format(abs($account_2050000[1])).")" : number_format($account_2050000[1])).'</td>
								<td align="right" class="border_btm">'.($account_2050000[4] < 0 ? "(".number_format(abs($account_2050000[4])).")" : number_format($account_2050000[4])).'</td>
								<td align="right" class="border_btm">'.($account_2050000[3] < 0 ? "(".number_format(abs($account_2050000[3])).")" : number_format($account_2050000[3])).'</td>
								<td align="right" class="border_btm">'.($account_2050000[2] < 0 ? "(".number_format(abs($account_2050000[2])).")" : number_format($account_2050000[2])).'</td>
								<td align="right" class="border_btm">'.($account_2050000[5] < 0 ? "(".number_format(abs($account_2050000[5])).")" : number_format($account_2050000[5])).'</td>
								<td align="right" class="border_btm">'.($account_2050000[6] < 0 ? "(".number_format(abs($account_2050000[6])).")" : number_format($account_2050000[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >Jumlah Liabilitas Jangka Pendek</td>
								<td align="right" class="border_btm">'.($account_liabilitas_konsolidasi < 0 ? "(".number_format(abs($account_liabilitas_konsolidasi)).")" : number_format($account_liabilitas_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_liabilitas[0] < 0 ? "(".number_format(abs($account_liabilitas[0])).")" : number_format($account_liabilitas[0])).'</td>
								<td align="right" class="border_btm">'.($account_liabilitas[1] < 0 ? "(".number_format(abs($account_liabilitas[1])).")" : number_format($account_liabilitas[1])).'</td>
								<td align="right" class="border_btm">'.($account_liabilitas[4] < 0 ? "(".number_format(abs($account_liabilitas[4])).")" : number_format($account_liabilitas[4])).'</td>
								<td align="right" class="border_btm">'.($account_liabilitas[3] < 0 ? "(".number_format(abs($account_liabilitas[3])).")" : number_format($account_liabilitas[3])).'</td>
								<td align="right" class="border_btm">'.($account_liabilitas[2] < 0 ? "(".number_format(abs($account_liabilitas[2])).")" : number_format($account_liabilitas[2])).'</td>
								<td align="right" class="border_btm">'.($account_liabilitas[5] < 0 ? "(".number_format(abs($account_liabilitas[5])).")" : number_format($account_liabilitas[5])).'</td>
								<td align="right" class="border_btm">'.($account_liabilitas[6] < 0 ? "(".number_format(abs($account_liabilitas[6])).")" : number_format($account_liabilitas[6])).'</td>
							</tr>';
							
										
				//EKUITAS
				$print .= '	<tr><td colspan="8" ></td>&nbsp;</tr>';
				$print .= '	<tr><td align="left" ><b>EKUITAS</b></td>	<td colspan="8" ></td></tr>';
				
				//Simpanan Pokok 3010102
				$code = "3010102";
				for($branch=0; $branch <=6; $branch++){
					$account_3010102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010102[$branch] = $account_3010102_credit[$branch] - $account_3010102_debet[$branch];
					$account_3010102_konsolidasi += $account_3010102[$branch];
					$account_ekuitas[$branch] += $account_3010102[$branch];
					$account_ekuitas_konsolidasi += $account_3010102[$branch];
				}	
				//Simpanan Wajib 3010101
				$code = "3010101";
				for($branch=0; $branch <=6; $branch++){
					$account_3010101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010101[$branch] = $account_3010101_credit[$branch] - $account_3010101_debet[$branch];
					$account_3010101_konsolidasi += $account_3010101[$branch];
					$account_ekuitas[$branch] += $account_3010101[$branch];
					$account_ekuitas_konsolidasi += $account_3010101[$branch];
				}
				//Hibah 3010103
				$code = "3010103";
				for($branch=0; $branch <=6; $branch++){
					$account_3010103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010103[$branch] = $account_3010103_credit[$branch] - $account_3010103_debet[$branch];
					$account_3010103_konsolidasi += $account_3010103[$branch];
					$account_ekuitas[$branch] += $account_3010103[$branch];
					$account_ekuitas_konsolidasi += $account_3010103[$branch];
				}
				//Modal Penyertaan 3010201
				$code = "3010201";
				for($branch=0; $branch <=6; $branch++){
					$account_3010201_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010201_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010201[$branch] = $account_3010201_credit[$branch] - $account_3010201_debet[$branch];
					$account_3010201_konsolidasi += $account_3010201[$branch];
					$account_ekuitas[$branch] += $account_3010201[$branch];
					$account_ekuitas_konsolidasi += $account_3010201[$branch];
				}	
				//SHU Tahun Lalu
				$code = "3020001";
				for($branch=0; $branch <=6; $branch++){
					$account_3020001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3020001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3020001[$branch] = $account_3020001_credit[$branch] - $account_3020001_debet[$branch];
					$account_3020001_konsolidasi += $account_3020001[$branch];
					$account_ekuitas[$branch] += $account_3020001[$branch];
					$account_ekuitas_konsolidasi += $account_3020001[$branch];
				}
				for($branch=0; $branch <=6; $branch++){
					$account_3020002[$branch] = $this->hitung_laba_rugi($date_start,$date_end,$branch);
					$account_3020002_konsolidasi += $account_3020002[$branch];
					$account_ekuitas[$branch] += $account_3020002[$branch];
					$account_ekuitas_konsolidasi += $account_3020002[$branch];
				}
				$lr_ciseeng = $this->hitung_laba_rugi($date_start,$date_end,$user_branch);				
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Simpanan Pokok</td>
								<td align="right" >'.($account_3010102_konsolidasi < 0 ? "(".number_format(abs($account_3010102_konsolidasi)).")" : number_format($account_3010102_konsolidasi)).'</td>
								<td align="right" >'.($account_3010102[0] < 0 ? "(".number_format(abs($account_3010102[0])).")" : number_format($account_3010102[0])).'</td>
								<td align="right" >'.($account_3010102[1] < 0 ? "(".number_format(abs($account_3010102[1])).")" : number_format($account_3010102[1])).'</td>
								<td align="right" >'.($account_3010102[4] < 0 ? "(".number_format(abs($account_3010102[4])).")" : number_format($account_3010102[4])).'</td>
								<td align="right" >'.($account_3010102[3] < 0 ? "(".number_format(abs($account_3010102[3])).")" : number_format($account_3010102[3])).'</td>
								<td align="right" >'.($account_3010102[2] < 0 ? "(".number_format(abs($account_3010102[2])).")" : number_format($account_3010102[2])).'</td>
								<td align="right" >'.($account_3010102[5] < 0 ? "(".number_format(abs($account_3010102[5])).")" : number_format($account_3010102[5])).'</td>
								<td align="right" >'.($account_3010102[6] < 0 ? "(".number_format(abs($account_3010102[6])).")" : number_format($account_3010102[5])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Simpanan Wajib</td>
								<td align="right" >'.($account_3010101_konsolidasi < 0 ? "(".number_format(abs($account_3010101_konsolidasi)).")" : number_format($account_3010101_konsolidasi)).'</td>
								<td align="right" >'.($account_3010101[0] < 0 ? "(".number_format(abs($account_3010101[0])).")" : number_format($account_3010101[0])).'</td>
								<td align="right" >'.($account_3010101[1] < 0 ? "(".number_format(abs($account_3010101[1])).")" : number_format($account_3010101[1])).'</td>
								<td align="right" >'.($account_3010101[4] < 0 ? "(".number_format(abs($account_3010101[4])).")" : number_format($account_3010101[4])).'</td>
								<td align="right" >'.($account_3010101[3] < 0 ? "(".number_format(abs($account_3010101[3])).")" : number_format($account_3010101[3])).'</td>
								<td align="right" >'.($account_3010101[2] < 0 ? "(".number_format(abs($account_3010101[2])).")" : number_format($account_3010101[2])).'</td>
								<td align="right" >'.($account_3010101[5] < 0 ? "(".number_format(abs($account_3010101[5])).")" : number_format($account_3010101[5])).'</td>
								<td align="right" >'.($account_3010101[6] < 0 ? "(".number_format(abs($account_3010101[6])).")" : number_format($account_3010101[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Hibah</td>
								<td align="right" >'.($account_3010103_konsolidasi < 0 ? "(".number_format(abs($account_3010103_konsolidasi)).")" : number_format($account_3010103_konsolidasi)).'</td>
								<td align="right" >'.($account_3010103[0] < 0 ? "(".number_format(abs($account_3010103[0])).")" : number_format($account_3010103[0])).'</td>
								<td align="right" >'.($account_3010103[1] < 0 ? "(".number_format(abs($account_3010103[1])).")" : number_format($account_3010103[1])).'</td>
								<td align="right" >'.($account_3010103[4] < 0 ? "(".number_format(abs($account_3010103[4])).")" : number_format($account_3010103[4])).'</td>
								<td align="right" >'.($account_3010103[3] < 0 ? "(".number_format(abs($account_3010103[3])).")" : number_format($account_3010103[3])).'</td>
								<td align="right" >'.($account_3010103[2] < 0 ? "(".number_format(abs($account_3010103[2])).")" : number_format($account_3010103[2])).'</td>
								<td align="right" >'.($account_3010103[5] < 0 ? "(".number_format(abs($account_3010103[5])).")" : number_format($account_3010103[5])).'</td>
								<td align="right" >'.($account_3010103[6] < 0 ? "(".number_format(abs($account_3010103[6])).")" : number_format($account_3010103[6])).'</td>
							</tr>';				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Modal Penyertaan</td>
								<td align="right" >'.($account_3010201_konsolidasi < 0 ? "(".number_format(abs($account_3010201_konsolidasi)).")" : number_format($account_3010201_konsolidasi)).'</td>
								<td align="right" >'.($account_3010201[0] < 0 ? "(".number_format(abs($account_3010201[0])).")" : number_format($account_3010201[0])).'</td>
								<td align="right" >'.($account_3010201[1] < 0 ? "(".number_format(abs($account_3010201[1])).")" : number_format($account_3010201[1])).'</td>
								<td align="right" >'.($account_3010201[4] < 0 ? "(".number_format(abs($account_3010201[4])).")" : number_format($account_3010201[4])).'</td>
								<td align="right" >'.($account_3010201[3] < 0 ? "(".number_format(abs($account_3010201[3])).")" : number_format($account_3010201[3])).'</td>
								<td align="right" >'.($account_3010201[2] < 0 ? "(".number_format(abs($account_3010201[2])).")" : number_format($account_3010201[2])).'</td>
								<td align="right" >'.($account_3010201[5] < 0 ? "(".number_format(abs($account_3010201[5])).")" : number_format($account_3010201[5])).'</td>
								<td align="right" >'.($account_3010201[6] < 0 ? "(".number_format(abs($account_3010201[6])).")" : number_format($account_3010201[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;SHU Tahun Lalu</td>
								<td align="right" >'.($account_3020001_konsolidasi < 0 ? "(".number_format(abs($account_3020001_konsolidasi)).")" : number_format($account_3020001_konsolidasi)).'</td>
								<td align="right" >'.($account_3020001[0] < 0 ? "(".number_format(abs($account_3020001[0])).")" : number_format($account_3020001[0])).'</td>
								<td align="right" >'.($account_3020001[1] < 0 ? "(".number_format(abs($account_3020001[1])).")" : number_format($account_3020001[1])).'</td>
								<td align="right" >'.($account_3020001[4] < 0 ? "(".number_format(abs($account_3020001[4])).")" : number_format($account_3020001[4])).'</td>
								<td align="right" >'.($account_3020001[3] < 0 ? "(".number_format(abs($account_3020001[3])).")" : number_format($account_3020001[3])).'</td>
								<td align="right" >'.($account_3020001[2] < 0 ? "(".number_format(abs($account_3020001[2])).")" : number_format($account_3020001[2])).'</td>
								<td align="right" >'.($account_3020001[5] < 0 ? "(".number_format(abs($account_3020001[5])).")" : number_format($account_3020001[5])).'</td>
								<td align="right" >'.($account_3020001[6] < 0 ? "(".number_format(abs($account_3020001[6])).")" : number_format($account_3020001[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;SHU Tahun Berjalan</td>
								<td align="right" class="border_btm">'.($account_3020002_konsolidasi < 0 ? "(".number_format(abs($account_3020002_konsolidasi)).")" : number_format($account_3020002_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_3020002[0] < 0 ? "(".number_format(abs($account_3020002[0])).")" : number_format($account_3020002[0])).'</td>
								<td align="right" class="border_btm">'.($account_3020002[1] < 0 ? "(".number_format(abs($account_3020002[1])).")" : number_format($account_3020002[1])).'</td>
								<td align="right" class="border_btm">'.($account_3020002[4] < 0 ? "(".number_format(abs($account_3020002[4])).")" : number_format($account_3020002[4])).'</td>
								<td align="right" class="border_btm">'.($account_3020002[3] < 0 ? "(".number_format(abs($account_3020002[3])).")" : number_format($account_3020002[3])).'</td>
								<td align="right" class="border_btm">'.($account_3020002[2] < 0 ? "(".number_format(abs($account_3020002[2])).")" : number_format($account_3020002[2])).'</td>
								<td align="right" class="border_btm">'.($account_3020002[5] < 0 ? "(".number_format(abs($account_3020002[5])).")" : number_format($account_3020002[5])).'</td>
								<td align="right" class="border_btm">'.($account_3020002[6] < 0 ? "(".number_format(abs($account_3020002[6])).")" : number_format($account_3020002[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >Jumlah Ekuitas</td>
								<td align="right" class="border_btm">'.($account_ekuitas_konsolidasi < 0 ? "(".number_format(abs($account_ekuitas_konsolidasi)).")" : number_format($account_ekuitas_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_ekuitas[0] < 0 ? "(".number_format(abs($account_ekuitas[0])).")" : number_format($account_ekuitas[0])).'</td>
								<td align="right" class="border_btm">'.($account_ekuitas[1] < 0 ? "(".number_format(abs($account_ekuitas[1])).")" : number_format($account_ekuitas[1])).'</td>
								<td align="right" class="border_btm">'.($account_ekuitas[4] < 0 ? "(".number_format(abs($account_ekuitas[4])).")" : number_format($account_ekuitas[4])).'</td>
								<td align="right" class="border_btm">'.($account_ekuitas[3] < 0 ? "(".number_format(abs($account_ekuitas[3])).")" : number_format($account_ekuitas[3])).'</td>
								<td align="right" class="border_btm">'.($account_ekuitas[2] < 0 ? "(".number_format(abs($account_ekuitas[2])).")" : number_format($account_ekuitas[2])).'</td>
								<td align="right" class="border_btm">'.($account_ekuitas[5] < 0 ? "(".number_format(abs($account_ekuitas[5])).")" : number_format($account_ekuitas[5])).'</td>
								<td align="right" class="border_btm">'.($account_ekuitas[6] < 0 ? "(".number_format(abs($account_ekuitas[6])).")" : number_format($account_ekuitas[6])).'</td>
							</tr>';
				
				//JUMLAH lIABILITAS EKUITAS
				for($branch=0; $branch <=6; $branch++){
					$account_liabilitas_ekuitas[$branch] += $account_liabilitas[$branch] + $account_ekuitas[$branch];
					$account_liabilitas_ekuitas_konsolidasi += $account_liabilitas[$branch] + $account_ekuitas[$branch];
				}
				
				$account_aset_tidak_lancar_konsolidasi = $account_aset_tetap_konsolidasi + $account_aset_lain_konsolidasi;
				$print .= '	<tr><td>&nbsp;</td><td align="left" colspan="8" class="border_btm"></td></tr>';
				$print .= '	<tr><td align="left" ><b>JUMLAH LIABILITAS DAN EKUITAS</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_liabilitas_ekuitas_konsolidasi < 0 ? "(".number_format(abs($account_liabilitas_ekuitas_konsolidasi)).")" : number_format($account_liabilitas_ekuitas_konsolidasi)).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_liabilitas_ekuitas[0] < 0 ? "(".number_format(abs($account_liabilitas_ekuitas[0])).")" : number_format($account_liabilitas_ekuitas[0])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_liabilitas_ekuitas[1] < 0 ? "(".number_format(abs($account_liabilitas_ekuitas[1])).")" : number_format($account_liabilitas_ekuitas[1])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_liabilitas_ekuitas[4] < 0 ? "(".number_format(abs($account_liabilitas_ekuitas[4])).")" : number_format($account_liabilitas_ekuitas[4])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_liabilitas_ekuitas[3] < 0 ? "(".number_format(abs($account_liabilitas_ekuitas[3])).")" : number_format($account_liabilitas_ekuitas[3])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_liabilitas_ekuitas[2] < 0 ? "(".number_format(abs($account_liabilitas_ekuitas[2])).")" : number_format($account_liabilitas_ekuitas[2])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_liabilitas_ekuitas[5] < 0 ? "(".number_format(abs($account_liabilitas_ekuitas[5])).")" : number_format($account_liabilitas_ekuitas[5])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_liabilitas_ekuitas[6] < 0 ? "(".number_format(abs($account_liabilitas_ekuitas[6])).")" : number_format($account_liabilitas_ekuitas[6])).'</b></td>
							</tr>';
							
							
				$selisih = $account_aset_konsolidasi - $account_liabilitas_ekuitas_konsolidasi;	
				for($branch=0; $branch <=6; $branch++){
					$account_selisih[$branch] += $account_aset[$branch] - $account_liabilitas_ekuitas[$branch];
				}				
				$print .= '	<tr><td>&nbsp;</td><td align="left" colspan="8" class="border_btm"></td></tr>';
				$print .= '	<tr><td align="left" ><b>SELISIH</b></td>
								<td align="right" class="border_btm_double"><b>'.($selisih < 0 ? "(".number_format(abs($selisih)).")" : number_format($selisih)).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_selisih[0] < 0 ? "(".number_format(abs($account_selisih[0])).")" : number_format($account_selisih[0])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_selisih[1] < 0 ? "(".number_format(abs($account_selisih[1])).")" : number_format($account_selisih[1])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_selisih[4] < 0 ? "(".number_format(abs($account_selisih[4])).")" : number_format($account_selisih[4])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_selisih[3] < 0 ? "(".number_format(abs($account_selisih[3])).")" : number_format($account_selisih[3])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_selisih[2] < 0 ? "(".number_format(abs($account_selisih[2])).")" : number_format($account_selisih[2])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_selisih[5] < 0 ? "(".number_format(abs($account_selisih[5])).")" : number_format($account_selisih[5])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($account_selisih[6] < 0 ? "(".number_format(abs($account_selisih[6])).")" : number_format($account_selisih[6])).'</b></td>
							</tr>';
			$this->template	->set('menu_title', 'Laporan Neraca Konsolidasi')
							->set('menu_konsolidasi', 'active')
							->set('print', $print)
							->build('neraca');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	//LABA RUGI
	public function laba_rugi()
	{
		if($this->session->userdata('logged_in'))
		{
			//Cek User Branch
			$user_branch = $this->session->userdata('user_branch');
			
			if($user_branch == "0"){ $user_branch=NULL;}
			
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
				$year_today = date("Y"); 
				//$date_start =$week_today[0];
				$date_start = $year_today."-01-01";
				$date_end   = date("Y-m-d");			
			}
			
			$date_end_before = strtotime($date_start);
			//$date_end_before = $date_start;
			$date_end_before = strtotime("-1 day", $date_end_before); 
			$date_end_before = date('Y-m-d', $date_end_before);			
			$date_start_before = "2013-01-01";
			//echo $date_start_before." ----- ".$date_end_before; 
			
				
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				$saldo_awal =0;
				
			//PENDAPATAN
			$print .= '	<tr><td align="left" ><b>Pendapatan</b></td>	<td colspan="8" ></td></tr>';
			
				
				//4010102 Pendapatan dari Murabahah
				$code = "4010102";
				for($branch=0; $branch <=6; $branch++){
					$account_4010102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4010102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4010102[$branch] = $account_4010102_credit[$branch] - $account_4010102_debet[$branch];
					$account_4010102_total += $account_4010102[$branch];
					$account_pendapatan_pembiayaan[$branch] += $account_4010102[$branch];
					$account_pendapatan_total[$branch] += $account_4010102[$branch];
				}
				//4010103 Pendapatan dari Ijarah
				$code = "4010103";
				for($branch=0; $branch <=6; $branch++){
					$account_4010103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4010103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4010103[$branch] = $account_4010103_credit[$branch] - $account_4010103_debet[$branch];
					$account_4010103_total += $account_4010103[$branch];
					$account_pendapatan_pembiayaan[$branch] += $account_4010103[$branch];
					$account_pendapatan_total[$branch] += $account_4010103[$branch];
				}
				$account_pendapatan_pembiayaan_total = $account_4010102_total + $account_4010103_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Pembiayaan</td>
								<td align="right">'.($account_pendapatan_pembiayaan_total < 0 ? "(".number_format(abs($account_pendapatan_pembiayaan_total)).")" : number_format($account_pendapatan_pembiayaan_total)).'</td>
								<td align="right">'.($account_pendapatan_pembiayaan[0] < 0 ? "(".number_format(abs($account_pendapatan_pembiayaan[0])).")" : number_format($account_pendapatan_pembiayaan[0])).'</td>
								<td align="right">'.($account_pendapatan_pembiayaan[1] < 0 ? "(".number_format(abs($account_pendapatan_pembiayaan[1])).")" : number_format($account_pendapatan_pembiayaan[1])).'</td>
								<td align="right">'.($account_pendapatan_pembiayaan[4] < 0 ? "(".number_format(abs($account_pendapatan_pembiayaan[4])).")" : number_format($account_pendapatan_pembiayaan[4])).'</td>
								<td align="right">'.($account_pendapatan_pembiayaan[3] < 0 ? "(".number_format(abs($account_pendapatan_pembiayaan[3])).")" : number_format($account_pendapatan_pembiayaan[3])).'</td>
								<td align="right">'.($account_pendapatan_pembiayaan[2] < 0 ? "(".number_format(abs($account_pendapatan_pembiayaan[2])).")" : number_format($account_pendapatan_pembiayaan[2])).'</td>
								<td align="right">'.($account_pendapatan_pembiayaan[5] < 0 ? "(".number_format(abs($account_pendapatan_pembiayaan[5])).")" : number_format($account_pendapatan_pembiayaan[5])).'</td>
								<td align="right">'.($account_pendapatan_pembiayaan[6] < 0 ? "(".number_format(abs($account_pendapatan_pembiayaan[6])).")" : number_format($account_pendapatan_pembiayaan[6])).'</td>
							</tr>';	
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan dari Murabahah</td>
								<td align="right">'.($account_4010102_total < 0 ? "(".number_format(abs($account_4010102_total)).")" : number_format($account_4010102_total)).'</td>
								<td align="right">'.($account_4010102[0] < 0 ? "(".number_format(abs($account_4010102[0])).")" : number_format($account_4010102[0])).'</td>
								<td align="right">'.($account_4010102[1] < 0 ? "(".number_format(abs($account_4010102[1])).")" : number_format($account_4010102[1])).'</td>
								<td align="right">'.($account_4010102[4] < 0 ? "(".number_format(abs($account_4010102[4])).")" : number_format($account_4010102[4])).'</td>
								<td align="right">'.($account_4010102[3] < 0 ? "(".number_format(abs($account_4010102[3])).")" : number_format($account_4010102[3])).'</td>
								<td align="right">'.($account_4010102[2] < 0 ? "(".number_format(abs($account_4010102[2])).")" : number_format($account_4010102[2])).'</td>
								<td align="right">'.($account_4010102[5] < 0 ? "(".number_format(abs($account_4010102[5])).")" : number_format($account_4010102[5])).'</td>
								<td align="right">'.($account_4010102[6] < 0 ? "(".number_format(abs($account_4010102[6])).")" : number_format($account_4010102[6])).'</td>
							</tr>';	
				
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan dari Ijarah</td>
								<td align="right">'.($account_4010103_total < 0 ? "(".number_format(abs($account_4010103_total)).")" : number_format($account_4010103_total)).'</td>
								<td align="right">'.($account_4010103[0] < 0 ? "(".number_format(abs($account_4010103[0])).")" : number_format($account_4010103[0])).'</td>
								<td align="right">'.($account_4010103[1] < 0 ? "(".number_format(abs($account_4010103[1])).")" : number_format($account_4010103[1])).'</td>
								<td align="right">'.($account_4010103[4] < 0 ? "(".number_format(abs($account_4010103[4])).")" : number_format($account_4010103[4])).'</td>
								<td align="right">'.($account_4010103[3] < 0 ? "(".number_format(abs($account_4010103[3])).")" : number_format($account_4010103[3])).'</td>
								<td align="right">'.($account_4010103[2] < 0 ? "(".number_format(abs($account_4010103[2])).")" : number_format($account_4010103[2])).'</td>
								<td align="right">'.($account_4010103[5] < 0 ? "(".number_format(abs($account_4010103[5])).")" : number_format($account_4010103[5])).'</td>
								<td align="right">'.($account_4010103[6] < 0 ? "(".number_format(abs($account_4010103[6])).")" : number_format($account_4010103[6])).'</td>
							</tr>';	
				
				//4020000 Pendapatan Jasa Administrasi
				$code = "4020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_4020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4020000[$branch] = $account_4020000_credit[$branch] - $account_4020000_debet[$branch];
					$account_4020000_total += $account_4020000[$branch];
					$account_pendapatan_total[$branch] += $account_4020000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Jasa Administrasi</td>
								<td align="right" class="border_btm">'.($account_4020000_total < 0 ? "(".number_format(abs($account_4020000_total)).")" : number_format($account_4020000_total)).'</td>
								<td align="right" class="border_btm">'.($account_4020000[0] < 0 ? "(".number_format(abs($account_4020000[0])).")" : number_format($account_4020000[0])).'</td>
								<td align="right" class="border_btm">'.($account_4020000[1] < 0 ? "(".number_format(abs($account_4020000[1])).")" : number_format($account_4020000[1])).'</td>
								<td align="right" class="border_btm">'.($account_4020000[4] < 0 ? "(".number_format(abs($account_4020000[4])).")" : number_format($account_4020000[4])).'</td>
								<td align="right" class="border_btm">'.($account_4020000[3] < 0 ? "(".number_format(abs($account_4020000[3])).")" : number_format($account_4020000[3])).'</td>
								<td align="right" class="border_btm">'.($account_4020000[2] < 0 ? "(".number_format(abs($account_4020000[2])).")" : number_format($account_4020000[2])).'</td>
								<td align="right" class="border_btm">'.($account_4020000[5] < 0 ? "(".number_format(abs($account_4020000[5])).")" : number_format($account_4020000[5])).'</td>
								<td align="right" class="border_btm">'.($account_4020000[6] < 0 ? "(".number_format(abs($account_4020000[6])).")" : number_format($account_4020000[6])).'</td>
							</tr>';	
							
				//Jumlah Pendapatan			
				$account_pendapatan_konsolidasi = $account_4010102_total + $account_4010103_total + $account_4020000_total;
				$print .= '	<tr><td align="left" >Jumlah</td>
								<td align="right" class="border_btm">'.($account_pendapatan_konsolidasi < 0 ? "(".number_format(abs($account_pendapatan_konsolidasi)).")" : number_format($account_pendapatan_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_pendapatan_total[0] < 0 ? "(".number_format(abs($account_pendapatan_total[0])).")" : number_format($account_pendapatan_total[0])).'</td>
								<td align="right" class="border_btm">'.($account_pendapatan_total[1] < 0 ? "(".number_format(abs($account_pendapatan_total[1])).")" : number_format($account_pendapatan_total[1])).'</td>
								<td align="right" class="border_btm">'.($account_pendapatan_total[4] < 0 ? "(".number_format(abs($account_pendapatan_total[4])).")" : number_format($account_pendapatan_total[4])).'</td>
								<td align="right" class="border_btm">'.($account_pendapatan_total[3] < 0 ? "(".number_format(abs($account_pendapatan_total[3])).")" : number_format($account_pendapatan_total[3])).'</td>
								<td align="right" class="border_btm">'.($account_pendapatan_total[2] < 0 ? "(".number_format(abs($account_pendapatan_total[2])).")" : number_format($account_pendapatan_total[2])).'</td>
								<td align="right" class="border_btm">'.($account_pendapatan_total[5] < 0 ? "(".number_format(abs($account_pendapatan_total[5])).")" : number_format($account_pendapatan_total[5])).'</td>
								<td align="right" class="border_btm">'.($account_pendapatan_total[6] < 0 ? "(".number_format(abs($account_pendapatan_total[6])).")" : number_format($account_pendapatan_total[6])).'</td>
							</tr>';	
								
				
				//BEBAN
				
				//Beban Pembiayaan = 5010000 + 5020000;
				//5010000 
				$code = "5010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_5010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5010000[$branch] = $account_5010000_debet[$branch] - $account_5010000_credit[$branch];
					$account_beban_pembiayaan_konsolidasi += $account_5010000[$branch];
					$account_beban_pembiayaan_total[$branch] += $account_5010000[$branch];
				}
				//5020000 
				$code = "5020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_5020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5020000[$branch] = $account_5020000_debet[$branch] - $account_5020000_credit[$branch];
					$account_beban_pembiayaan_konsolidasi += $account_5020000[$branch];
					$account_beban_pembiayaan_total[$branch] += $account_5020000[$branch];
					
				}
				$print .= '	<tr><td align="left" colspan="8"> &nbsp;</td></tr>';
				$print .= '	<tr><td align="left" ><b>Beban Pembiayaan</b></td>
								<td align="right" >'.($account_beban_pembiayaan_konsolidasi < 0 ? "(".number_format(abs($account_beban_pembiayaan_konsolidasi)).")" : number_format($account_beban_pembiayaan_konsolidasi)).'</td>
								<td align="right" >'.($account_beban_pembiayaan_total[0] < 0 ? "(".number_format(abs($account_beban_pembiayaan_total[0])).")" : number_format($account_beban_pembiayaan_total[0])).'</td>
								<td align="right" >'.($account_beban_pembiayaan_total[1] < 0 ? "(".number_format(abs($account_beban_pembiayaan_total[1])).")" : number_format($account_beban_pembiayaan_total[1])).'</td>
								<td align="right" >'.($account_beban_pembiayaan_total[4] < 0 ? "(".number_format(abs($account_beban_pembiayaan_total[4])).")" : number_format($account_beban_pembiayaan_total[4])).'</td>
								<td align="right" >'.($account_beban_pembiayaan_total[3] < 0 ? "(".number_format(abs($account_beban_pembiayaan_total[3])).")" : number_format($account_beban_pembiayaan_total[3])).'</td>
								<td align="right" >'.($account_beban_pembiayaan_total[2] < 0 ? "(".number_format(abs($account_beban_pembiayaan_total[2])).")" : number_format($account_beban_pembiayaan_total[2])).'</td>
								<td align="right" >'.($account_beban_pembiayaan_total[5] < 0 ? "(".number_format(abs($account_beban_pembiayaan_total[5])).")" : number_format($account_beban_pembiayaan_total[5])).'</td>
								<td align="right" >'.($account_beban_pembiayaan_total[6] < 0 ? "(".number_format(abs($account_beban_pembiayaan_total[6])).")" : number_format($account_beban_pembiayaan_total[6])).'</td>
							</tr>';	

							
				//Beban Operasional = 5010000 + 5020000;
				//5040000 
				$code = "5030000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_5030000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5030000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5030000[$branch] = $account_5030000_debet[$branch] - $account_5030000_credit[$branch];
					$account_beban_operasional_konsolidasi += $account_5030000[$branch];
					$account_beban_operasional_total[$branch] += $account_5030000[$branch];
				}
				//5040000 
				$code = "5040000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_5040000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5040000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5040000[$branch] = $account_5040000_debet[$branch] - $account_5040000_credit[$branch];
					$account_beban_operasional_konsolidasi += $account_5040000[$branch];
					$account_beban_operasional_total[$branch] += $account_5040000[$branch];
				}
				//5050000 
				$code = "5050000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_5050000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5050000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5050000[$branch] = $account_5050000_debet[$branch] - $account_5050000_credit[$branch];
					$account_beban_operasional_konsolidasi += $account_5050000[$branch];
					$account_beban_operasional_total[$branch] += $account_5050000[$branch];
				}				
				//5060000 
				$code = "5060000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_5060000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5060000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5060000[$branch] = $account_5060000_debet[$branch] - $account_5060000_credit[$branch];
					$account_beban_operasional_konsolidasi += $account_5060000[$branch];
					$account_beban_operasional_total[$branch] += $account_5060000[$branch];
				}
				//5070000 
				$code = "5070000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_5070000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5070000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5070000[$branch] = $account_5070000_debet[$branch] - $account_5070000_credit[$branch];
					$account_beban_operasional_konsolidasi += $account_5070000[$branch];
					$account_beban_operasional_total[$branch] += $account_5070000[$branch];
				}
				//5080000 
				$code = "5080000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_5080000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5080000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5080000[$branch] = $account_5080000_debet[$branch] - $account_5080000_credit[$branch];
					$account_beban_operasional_konsolidasi += $account_5080000[$branch];
					$account_beban_operasional_total[$branch] += $account_5080000[$branch];
				}
				$print .= '	<tr><td align="left" ><b>Beban Operasional</b></td>
								<td align="right" class="border_btm">'.($account_beban_operasional_konsolidasi < 0 ? "(".number_format(abs($account_beban_operasional_konsolidasi)).")" : number_format($account_beban_operasional_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_beban_operasional_total[0] < 0 ? "(".number_format(abs($account_beban_operasional_total[0])).")" : number_format($account_beban_operasional_total[0])).'</td>
								<td align="right" class="border_btm">'.($account_beban_operasional_total[1] < 0 ? "(".number_format(abs($account_beban_operasional_total[1])).")" : number_format($account_beban_operasional_total[1])).'</td>
								<td align="right" class="border_btm">'.($account_beban_operasional_total[4] < 0 ? "(".number_format(abs($account_beban_operasional_total[4])).")" : number_format($account_beban_operasional_total[4])).'</td>
								<td align="right" class="border_btm">'.($account_beban_operasional_total[3] < 0 ? "(".number_format(abs($account_beban_operasional_total[3])).")" : number_format($account_beban_operasional_total[3])).'</td>
								<td align="right" class="border_btm">'.($account_beban_operasional_total[2] < 0 ? "(".number_format(abs($account_beban_operasional_total[2])).")" : number_format($account_beban_operasional_total[2])).'</td>
								<td align="right" class="border_btm">'.($account_beban_operasional_total[5] < 0 ? "(".number_format(abs($account_beban_operasional_total[5])).")" : number_format($account_beban_operasional_total[5])).'</td>
								<td align="right" class="border_btm">'.($account_beban_operasional_total[6] < 0 ? "(".number_format(abs($account_beban_operasional_total[6])).")" : number_format($account_beban_operasional_total[6])).'</td>
							</tr>';	
				//Jumlah Beban
				$beban_konsolidasi_total = $account_beban_pembiayaan_konsolidasi + $account_beban_operasional_konsolidasi;
				for($branch=0; $branch <=6; $branch++){
					$beban_total[$branch] = $account_beban_pembiayaan_total[$branch] + $account_beban_operasional_total[$branch];
				}
				$print .= '	<tr><td align="left" >Jumlah</td>
								<td align="right" class="border_btm">'.($beban_konsolidasi_total < 0 ? "(".number_format(abs($beban_konsolidasi_total)).")" : number_format($beban_konsolidasi_total)).'</td>
								<td align="right" class="border_btm">'.($beban_total[0] < 0 ? "(".number_format(abs($beban_total[0])).")" : number_format($beban_total[0])).'</td>
								<td align="right" class="border_btm">'.($beban_total[1] < 0 ? "(".number_format(abs($beban_total[1])).")" : number_format($beban_total[1])).'</td>
								<td align="right" class="border_btm">'.($beban_total[4] < 0 ? "(".number_format(abs($beban_total[4])).")" : number_format($beban_total[4])).'</td>
								<td align="right" class="border_btm">'.($beban_total[3] < 0 ? "(".number_format(abs($beban_total[3])).")" : number_format($beban_total[3])).'</td>
								<td align="right" class="border_btm">'.($beban_total[2] < 0 ? "(".number_format(abs($beban_total[2])).")" : number_format($beban_total[2])).'</td>
								<td align="right" class="border_btm">'.($beban_total[5] < 0 ? "(".number_format(abs($beban_total[5])).")" : number_format($beban_total[5])).'</td>
								<td align="right" class="border_btm">'.($beban_total[6] < 0 ? "(".number_format(abs($beban_total[6])).")" : number_format($beban_total[6])).'</td>
							</tr>';		
				
				//SHU
				$shu_konsolidasi = $account_pendapatan_konsolidasi-$beban_konsolidasi_total;
				for($branch=0; $branch <=6; $branch++){
					$shu[$branch] = $account_pendapatan_total[$branch] - $beban_total[$branch];
				}
				$print .= '	<tr><td align="left" colspan="8"> &nbsp;</td></tr>';
				$print .= '	<tr><td align="left" ><b>Sisa Hasil Usaha Operasional</b></td>
								<td align="right" >'.($shu_konsolidasi < 0 ? "(".number_format(abs($shu_konsolidasi)).")" : number_format($shu_konsolidasi)).'</td>
								<td align="right" >'.($shu[0] < 0 ? "(".number_format(abs($shu[0])).")" : number_format($shu[0])).'</td>
								<td align="right" >'.($shu[1] < 0 ? "(".number_format(abs($shu[1])).")" : number_format($shu[1])).'</td>
								<td align="right" >'.($shu[4] < 0 ? "(".number_format(abs($shu[4])).")" : number_format($shu[4])).'</td>
								<td align="right" >'.($shu[3] < 0 ? "(".number_format(abs($shu[3])).")" : number_format($shu[3])).'</td>
								<td align="right" >'.($shu[2] < 0 ? "(".number_format(abs($shu[2])).")" : number_format($shu[2])).'</td>
								<td align="right" >'.($shu[5] < 0 ? "(".number_format(abs($shu[5])).")" : number_format($shu[5])).'</td>
								<td align="right" >'.($shu[6] < 0 ? "(".number_format(abs($shu[6])).")" : number_format($shu[6])).'</td>
							</tr>';	
				
				//Pendapatan (beban) lainnya
				//4030000 Pendapatan Lain-lain
				$code = "4030000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_4030000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4030000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4030000[$branch] = $account_4030000_credit[$branch] - $account_4030000_debet[$branch];
					$account_4030000_konsolidasi += $account_4030000[$branch];
					$account_pendapatan_lain[$branch] += $account_4030000[$branch];					
				}
				//5090000 Beban Non Operasional
				$code = "5090000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_5090000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5090000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5090000[$branch] = $account_5090000_debet[$branch] - $account_5090000_credit[$branch];
					$account_5090000_konsolidasi += $account_5090000[$branch];
					$account_beban_lain[$branch] += $account_5090000[$branch];
				}
				//Jumlah Lain-lain
				$account_lain_lain_konsolidasi = $account_4030000_konsolidasi - $account_5090000_konsolidasi;
				for($branch=0; $branch <=6; $branch++){					
					$account_lain_lain[$branch] = $account_pendapatan_lain[$branch] - $account_beban_lain[$branch];
				}
				$print .= '	<tr><td align="left" colspan="8"> &nbsp;</td></tr>';
				$print .= '	<tr><td align="left" ><b><u>Pendapatan (beban) lainnya</u></b></td>
								<td colspan="8" ></td>
							</tr>';	
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Lain-lain</b></td>
								<td align="right" >'.($account_4030000_konsolidasi < 0 ? "(".number_format(abs($account_4030000_konsolidasi)).")" : number_format($account_4030000_konsolidasi)).'</td>
								<td align="right" >'.($account_4030000[0] < 0 ? "(".number_format(abs($account_4030000[0])).")" : number_format($account_4030000[0])).'</td>
								<td align="right" >'.($account_4030000[1] < 0 ? "(".number_format(abs($account_4030000[1])).")" : number_format($account_4030000[1])).'</td>
								<td align="right" >'.($account_4030000[4] < 0 ? "(".number_format(abs($account_4030000[4])).")" : number_format($account_4030000[4])).'</td>
								<td align="right" >'.($account_4030000[3] < 0 ? "(".number_format(abs($account_4030000[3])).")" : number_format($account_4030000[3])).'</td>
								<td align="right" >'.($account_4030000[2] < 0 ? "(".number_format(abs($account_4030000[2])).")" : number_format($account_4030000[2])).'</td>
								<td align="right" >'.($account_4030000[5] < 0 ? "(".number_format(abs($account_4030000[5])).")" : number_format($account_4030000[5])).'</td>
								<td align="right" >'.($account_4030000[6] < 0 ? "(".number_format(abs($account_4030000[6])).")" : number_format($account_4030000[6])).'</td>
							</tr>';	
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Lain-lain</b></td>
								<td align="right" class="border_btm">'.($account_5090000_konsolidasi < 0 ? "(".number_format(abs($account_5090000_konsolidasi)).")" : number_format($account_5090000_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_5090000[0] < 0 ? "(".number_format(abs($account_5090000[0])).")" : number_format($account_5090000[0])).'</td>
								<td align="right" class="border_btm">'.($account_5090000[1] < 0 ? "(".number_format(abs($account_5090000[1])).")" : number_format($account_5090000[1])).'</td>
								<td align="right" class="border_btm">'.($account_5090000[4] < 0 ? "(".number_format(abs($account_5090000[4])).")" : number_format($account_5090000[4])).'</td>
								<td align="right" class="border_btm">'.($account_5090000[3] < 0 ? "(".number_format(abs($account_5090000[3])).")" : number_format($account_5090000[3])).'</td>
								<td align="right" class="border_btm">'.($account_5090000[2] < 0 ? "(".number_format(abs($account_5090000[2])).")" : number_format($account_5090000[2])).'</td>
								<td align="right" class="border_btm">'.($account_5090000[5] < 0 ? "(".number_format(abs($account_5090000[5])).")" : number_format($account_5090000[5])).'</td>
								<td align="right" class="border_btm">'.($account_5090000[6] < 0 ? "(".number_format(abs($account_5090000[6])).")" : number_format($account_5090000[6])).'</td>
							</tr>';
				$print .= '	<tr><td align="left" >Total</td>
								<td align="right" class="border_btm">'.($account_lain_lain_konsolidasi < 0 ? "(".number_format(abs($account_lain_lain_konsolidasi)).")" : number_format($account_lain_lain_konsolidasi)).'</td>
								<td align="right" class="border_btm">'.($account_lain_lain[0] < 0 ? "(".number_format(abs($account_lain_lain[0])).")" : number_format($account_lain_lain[0])).'</td>
								<td align="right" class="border_btm">'.($account_lain_lain[1] < 0 ? "(".number_format(abs($account_lain_lain[1])).")" : number_format($account_lain_lain[1])).'</td>
								<td align="right" class="border_btm">'.($account_lain_lain[4] < 0 ? "(".number_format(abs($account_lain_lain[4])).")" : number_format($account_lain_lain[4])).'</td>
								<td align="right" class="border_btm">'.($account_lain_lain[3] < 0 ? "(".number_format(abs($account_lain_lain[3])).")" : number_format($account_lain_lain[3])).'</td>
								<td align="right" class="border_btm">'.($account_lain_lain[2] < 0 ? "(".number_format(abs($account_lain_lain[2])).")" : number_format($account_lain_lain[2])).'</td>
								<td align="right" class="border_btm">'.($account_lain_lain[5] < 0 ? "(".number_format(abs($account_lain_lain[5])).")" : number_format($account_lain_lain[5])).'</td>
								<td align="right" class="border_btm">'.($account_lain_lain[6] < 0 ? "(".number_format(abs($account_lain_lain[6])).")" : number_format($account_lain_lain[6])).'</td>
							</tr>';
				
				//SHU Bersih
				$shu_bersih_konsolidasi = $shu_konsolidasi + $account_lain_lain_konsolidasi;
				for($branch=0; $branch <=6; $branch++){					
					$shu_bersih[$branch] = $shu[$branch] + $account_lain_lain[$branch];
				}
				$print .= '	<tr><td align="left" ></td><td align="left" colspan="8" class="border_btm"> &nbsp;</td></tr>';
				$print .= '	<tr><td align="left" ><b>Sisa Hasil Usaha Bersih</b></td>
								<td align="right" class="border_btm_double"><b>'.($shu_bersih_konsolidasi < 0 ? "(".number_format(abs($shu_bersih_konsolidasi)).")" : number_format($shu_bersih_konsolidasi)).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($shu_bersih[0] < 0 ? "(".number_format(abs($shu_bersih[0])).")" : number_format($shu_bersih[0])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($shu_bersih[1] < 0 ? "(".number_format(abs($shu_bersih[1])).")" : number_format($shu_bersih[1])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($shu_bersih[4] < 0 ? "(".number_format(abs($shu_bersih[4])).")" : number_format($shu_bersih[4])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($shu_bersih[3] < 0 ? "(".number_format(abs($shu_bersih[3])).")" : number_format($shu_bersih[3])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($shu_bersih[2] < 0 ? "(".number_format(abs($shu_bersih[2])).")" : number_format($shu_bersih[2])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($shu_bersih[5] < 0 ? "(".number_format(abs($shu_bersih[5])).")" : number_format($shu_bersih[5])).'</b></td>
								<td align="right" class="border_btm_double"><b>'.($shu_bersih[6] < 0 ? "(".number_format(abs($shu_bersih[6])).")" : number_format($shu_bersih[6])).'</b></td>
							
							</tr>';	
			$this->template	->set('menu_title', 'Laporan Laba Rugi Konsolidasi')
							->set('menu_konsolidasi', 'active')
							->set('print', $print)
							->build('labarugi');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	
	//DOWNLOAD NERACA
	public function download_neraca()
	{
		if($this->session->userdata('logged_in'))
		{
			$timestamp=date("Ymdhis");
			$tgl=date("d-M-Y");
			$filename="NERACA_KONSOLIDASI_$timestamp";	
			$html = "<style> table tr td,table thead tr td, table tr th{ border-left:0; border-right:0;} table thead tr td,table thead tr th,table tr th{ border-bottom: 2px solid #000; }</style>";
			$html .= '';
			$html .= '<h1 align="center">Amartha Microfinance</h1>';
			$html .= '<hr/>';
			$html .= '<h2 align="center">NERACA KONSOLIDASI</h2><br/>';
			
			
			
			
			$html .= '<table border="1" width="100%">';
			////$html .= '<thead>';                 
			$html .= '<tr>';
			$html .= '<th>Account</th>';
			$html .= '<th align="right" class="text-right">Saldo Awal</th>';
			$html .= '<th align="right" class="text-right">Debet</th>';
			$html .= '<th align="right" class="text-right">Credit</th>';
			$html .= '<th align="right" class="text-right">Saldo Akhir</th>';
			$html .= '</tr> ';                 
			//$html .= '</thead>';
			
				
							
			$html .= $neraca;
			$this->load->library('mpdf');
			$mpdf=new mPDF('utf-8', 'A4-L');
			$mpdf->SetHeader("Amartha Microfinance".'||'.$tgl.'|'); 
			$mpdf->SetFooter("Neraca Saldo".'||{PAGENO}|'); 
			$mpdf->WriteHTML($html);
			//echo $html;
			//$this->mpdf->Output();
			$pdfFilePath = FCPATH."downloads/neraca/$filename.pdf";
			$pdffile = base_url()."downloads/neraca/$filename.pdf";
			$mpdf->Output($pdfFilePath,'F');
			
			redirect($pdffile, 'refresh');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	//DOWNLOAD LABARUGI
	public function download_labarugi()
	{
		if($this->session->userdata('logged_in'))
		{
			$timestamp=date("Ymdhis");
			$tgl=date("d-M-Y");
			$filename="LAPORAN_LABARUGI_$timestamp";	
			$html = "<style> table tr td,table thead tr td, table tr th{ border-left:0; border-right:0;} table thead tr td,table thead tr th,table tr th{ border-bottom: 2px solid #000; }</style>";
			$html .= '';
			//$html .= '<h1 align="center">Amartha Microfinance</h1>';
			//$html .= '<hr/>';
			$html .= '<h2 align="center">LAPORAN LABA RUGI</h2><br/>';
			$html .= '<table border="1" width="100%">';
			//$html .= '<thead>';                 
			$html .= '<tr>';
			$html .= '<th>Account</th>';
			$html .= '<th align="right" class="text-right" width="100px">Saldo Awal</th>';
			$html .= '<th align="right" class="text-right" width="100px">Debet</th>';
			$html .= '<th align="right" class="text-right" width="100px">Credit</th>';
			$html .= '<th align="right" class="text-right" width="100px">Saldo Akhir</th>';
			$html .= '</tr> ';                 
			$html .= '</thead>';
			
			//Cek User Branch
			$user_branch = $this->session->userdata('user_branch');
			
			if($user_branch == "0"){ $user_branch=NULL;}
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
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$user_branch);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$user_branch);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start_before,$date_end_before,$user_branch);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start_before,$date_end_before,$user_branch);
					
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
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$user_branch);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$user_branch);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start_before,$date_end_before,$user_branch);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start_before,$date_end_before,$user_branch);
					
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
				$laba_rugi = ($grand_total_pendapatan_before+$grand_total_pendapatan_credit) - ($grand_total_beban_before+$grand_total_beban_debet);
				
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
				$neraca .= '</table>';
			
			$html .= $neraca;
			$this->load->library('mpdf');
			$mpdf=new mPDF('utf-8', 'A4-L');
			$mpdf->SetHeader("Amartha Microfinance".'||'.$tgl.'|'); 
			$mpdf->SetFooter("Laporan Laba Rugi".'||{PAGENO}|'); 
			$mpdf->WriteHTML($html);
			//echo $html;
			//$this->mpdf->Output();
			$pdfFilePath = FCPATH."downloads/labarugi/$filename.pdf";
			$pdffile = base_url()."downloads/labarugi/$filename.pdf";
			$mpdf->Output($pdfFilePath,'F');
			
			redirect($pdffile, 'refresh');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	


	//NERACA EXCEL
	public function neraca_excel()
	{
		if($this->session->userdata('logged_in'))
		{
			
			//Cek User Branch
			$user_branch = $this->session->userdata('user_branch');
			
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
			$date_start=$this->uri->segment(3);
			$date_end=$this->uri->segment(4);
			if($date_start AND $date_end AND ($date_start <= $date_end )){
				$date_start=$this->uri->segment(3);
				$date_end=$this->uri->segment(4);
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
			
			
				
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				$saldo_awal =0;
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();
			$style_border_top_btm = array(
				  'borders' => array(
					'bottom' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					),
					'top' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				  )
				);
			$style_border_top_btm_double = array(
				  'borders' => array(
					'bottom' => array(
					  'style' => PHPExcel_Style_Border::BORDER_DOUBLE
					),
					'top' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				  )
				);
			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Neraca Konsolidasi");
			$objPHPExcel->getProperties()->setSubject("Neraca Konsolidasi");
			$objPHPExcel->getProperties()->setDescription("Neraca Konsolidasi");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Neraca Konsolidasi');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Neraca Konsolidasi");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A3", $date_start." s/d ".$date_end);
			$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray(array("font" => array( "bold" => true)));
			
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:I4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("B4:I4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "");
			$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(50);
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "KONSOLIDASI");
			$objPHPExcel->getActiveSheet()->getColumnDimension("B4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "PUSAT");
			$objPHPExcel->getActiveSheet()->getColumnDimension("C4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "CS");
			$objPHPExcel->getActiveSheet()->getColumnDimension("D4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "JS");
			$objPHPExcel->getActiveSheet()->getColumnDimension("E4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "BG");
			$objPHPExcel->getActiveSheet()->getColumnDimension("F4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "KM");
			$objPHPExcel->getActiveSheet()->getColumnDimension("G4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TN");			
			$objPHPExcel->getActiveSheet()->getColumnDimension("H4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "CK");			
			$objPHPExcel->getActiveSheet()->getColumnDimension("I4")->setWidth(25);
			
			//CONTENT
			//ASET LANCAR
			$objPHPExcel->getActiveSheet()->setCellValue("A5", "ASET");
			$objPHPExcel->getActiveSheet()->setCellValue("A6", "ASET LANCAR");
			
				//Kas
				//1010000
				$code = "1010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1010000[$branch] = $account_1010000_debet[$branch] - $account_1010000_credit[$branch] ;
					$account_1010000_konsolidasi += $account_1010000[$branch];
					$account_kas[$branch] += $account_1010000[$branch];				
					$account_aset_lancar[$branch] += $account_1010000[$branch];	
				}
				//1020000 
				$code = "1020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020000[$branch] = $account_1020000_debet[$branch] - $account_1020000_credit[$branch];
					$account_1020000_konsolidasi += $account_1020000[$branch];
					$account_kas[$branch] += $account_1020000[$branch];		
					$account_aset_lancar[$branch] += $account_1020000[$branch];
				}
				//Total Kas
				$account_kas_konsolidasi = $account_1010000_konsolidasi + $account_1020000_konsolidasi;
				
				$objPHPExcel->getActiveSheet()->getStyle("A4:A6")->applyFromArray(array("font" => array( "bold" => true)));
				$objPHPExcel->getActiveSheet()->setCellValue("A7", "     Kas dan setara Kas");
				$objPHPExcel->getActiveSheet()->setCellValue("B7", $account_kas_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C7", $account_kas[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D7", $account_kas[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E7", $account_kas[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F7", $account_kas[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G7", $account_kas[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H7", $account_kas[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I7", $account_kas[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B7:I7")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				
				//Piutang MBA 1030102
				$code = "1030102";
				for($branch=0; $branch <=6; $branch++){
					$account_1030102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030102[$branch] = $account_1030102_debet[$branch] - $account_1030102_credit[$branch];
					$account_1030102_konsolidasi += $account_1030102[$branch];
					$account_piutang_pembiayaan[$branch] += $account_1030102[$branch];			
					$account_aset_lancar[$branch] += $account_1030102[$branch];
				}
				//Piutang IJA 1030103
				$code = "1030103";
				for($branch=0; $branch <=6; $branch++){
					$account_1030103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030103[$branch] = $account_1030103_debet[$branch] - $account_1030103_credit[$branch];
					$account_1030103_konsolidasi += $account_1030103[$branch];
					$account_piutang_pembiayaan[$branch] += $account_1030103[$branch];				
					$account_aset_lancar[$branch] += $account_1030103[$branch];
				}
				//Piutang QH 1030104
				$code = "1030104";
				for($branch=0; $branch <=6; $branch++){
					$account_1030104_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030104_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030104[$branch] = $account_1030104_debet[$branch] - $account_1030104_credit[$branch];
					$account_1030104_konsolidasi += $account_1030104[$branch];

					$account_piutang_pembiayaan[$branch] += $account_1030104[$branch];					
					$account_aset_lancar[$branch] += $account_1030104[$branch];
				}
				//Piutang Pembiayaan Lembaga 1030200
				
				
				
				//Piutang QH 1030504
				$code = "1030504";
				for($branch=0; $branch <=6; $branch++){
					$account_1030504_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030504_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030104[$branch] = $account_1030504_debet[$branch] - $account_1030504_credit[$branch];
					$account_1030104_konsolidasi += $account_1030104[$branch];
					$account_piutang_pembiayaan[$branch] += $account_1030104[$branch];					
					$account_aset_lancar[$branch] += $account_1030104[$branch];
				}
				
				$code = "1030200";
				$code_level2 = substr($code,0,5);
				for($branch=0; $branch <=6; $branch++){
					$account_1030200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030200[$branch] = $account_1030200_debet[$branch] - $account_1030200_credit[$branch];
					$account_1030104[$branch] += $account_1030200[$branch];
					$account_1030104_konsolidasi += $account_1030200[$branch];

					$account_piutang_pembiayaan[$branch] += $account_1030200[$branch];					
					$account_aset_lancar[$branch] += $account_1030200[$branch];
				}
				
				/*
				//Piutang Cabang 1030400
				$code = "1030400";
				$code_level2 = substr($code,0,5);
				for($branch=0; $branch <=6; $branch++){
					$account_1030400_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030400_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030400[$branch] = $account_1030400_debet[$branch] - $account_1030400_credit[$branch];
					//$account_1030400_konsolidasi += $account_1030400[$branch];
					$account_1030400_konsolidasi = 0; 
					$account_piutang_pembiayaan[$branch] += $account_1030400[$branch];
					$account_aset_lancar[$branch] += $account_1030400[$branch];
				}
				*/
				
				
				$account_piutang_pembiayaan_konsolidasi = $account_1030102_konsolidasi  + $account_1030103_konsolidasi + + $account_1030104_konsolidasi + $account_1030400_konsolidasi;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A8", "     Piutang Pembiayaan");
				$objPHPExcel->getActiveSheet()->setCellValue("B8", $account_piutang_pembiayaan_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C8", $account_piutang_pembiayaan[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D8", $account_piutang_pembiayaan[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E8", $account_piutang_pembiayaan[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F8", $account_piutang_pembiayaan[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G8", $account_piutang_pembiayaan[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H8", $account_piutang_pembiayaan[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I8", $account_piutang_pembiayaan[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B8:I8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A9", "          Murabahah");
				$objPHPExcel->getActiveSheet()->setCellValue("B9", $account_1030102_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C9", $account_1030102[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D9", $account_1030102[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E9", $account_1030102[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F9", $account_1030102[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G9", $account_1030102[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H9", $account_1030102[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I9", $account_1030102[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B9:I9")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A10", "          Ijarah");
				$objPHPExcel->getActiveSheet()->setCellValue("B10", $account_1030103_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C10", $account_1030103[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D10", $account_1030103[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E10", $account_1030103[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F10", $account_1030103[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G10", $account_1030103[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H10", $account_1030103[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I10", $account_1030103[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B10:I10")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A11", "          Qadrul Hasan");
				$objPHPExcel->getActiveSheet()->setCellValue("B11", $account_1030104_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C11", $account_1030104[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D11", $account_1030104[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E11", $account_1030104[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F11", $account_1030104[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G11", $account_1030104[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H11", $account_1030104[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I11", $account_1030104[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B11:I11")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				//Beban dibayar dimuka 1050000
				$code = "1050000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1050000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1050000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1050000[$branch] = $account_1050000_debet[$branch] - $account_1050000_credit[$branch];
					$account_1050000_konsolidasi += $account_1050000[$branch];
					$account_aset_lancar[$branch] += $account_1050000[$branch];
				}
				//Persediaan Barang Cetakan 1060000
				$code = "1060000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1060000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1060000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1060000[$branch] = $account_1060000_debet[$branch] - $account_1060000_credit[$branch];
					$account_1060000_konsolidasi += $account_1060000[$branch];
					$account_aset_lancar[$branch] += $account_1060000[$branch];
				}
				$account_aset_lancar_konsolidasi = $account_kas_konsolidasi + $account_piutang_pembiayaan_konsolidasi + $account_1060000_konsolidasi+$account_1050000_konsolidasi;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A13", "     Beban dibayar dimuka");
				$objPHPExcel->getActiveSheet()->setCellValue("B13", $account_1050000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C13", $account_1050000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D13", $account_1050000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E13", $account_1050000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F13", $account_1050000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G13", $account_1050000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H13", $account_1050000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I13", $account_1050000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B13:I13")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
				$objPHPExcel->getActiveSheet()->setCellValue("A14", "     Persediaan Barang Cetakan");
				$objPHPExcel->getActiveSheet()->setCellValue("B14", $account_1060000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C14", $account_1060000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D14", $account_1060000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E14", $account_1060000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F14", $account_1060000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G14", $account_1060000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H14", $account_1060000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I14", $account_1060000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B14:I14")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
				$objPHPExcel->getActiveSheet()->setCellValue("A14", "Jumlah Aset Lancar");
				$objPHPExcel->getActiveSheet()->setCellValue("B14", $account_aset_lancar_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C14", $account_aset_lancar[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D14", $account_aset_lancar[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E14", $account_aset_lancar[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F14", $account_aset_lancar[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G14", $account_aset_lancar[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H14", $account_aset_lancar[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I14", $account_aset_lancar[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B14:I14")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('B14:I14')->applyFromArray($style_border_top_btm);
							
			//ASET TIDAK LANCAR
			$objPHPExcel->getActiveSheet()->setCellValue("A16", "ASET TIDAK LANCAR");
			$objPHPExcel->getActiveSheet()->getStyle("A16")->applyFromArray(array("font" => array( "bold" => true)));
			
				//Aset Tetap setelah dikurangi = 1080301 + 1080302				
				$code = "1080301";
				for($branch=0; $branch <=6; $branch++){
					$account_1080301_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1080301_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1080301[$branch] = $account_1080301_debet[$branch] - $account_1080301_credit[$branch];
					$account_1080301_konsolidasi += $account_1080301[$branch];
					$account_aset_tetap[$branch] += $account_1080301[$branch];					
					$account_aset_tidak_lancar[$branch] += $account_1080301[$branch];
				}
				$code = "1080302";
				for($branch=0; $branch <=6; $branch++){
					$account_1080302_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1080302_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1080302[$branch] = $account_1080302_debet[$branch] - $account_1080302_credit[$branch];
					$account_1080302_konsolidasi += $account_1080302[$branch];
					$account_aset_tetap[$branch] += $account_1080302[$branch];					
					$account_aset_tidak_lancar[$branch] += $account_1080302[$branch];
				}
				$account_aset_tetap_konsolidasi = $account_1080301_konsolidasi + $account_1080302_konsolidasi;
				
				//Aset Lain	1090000
				$code = "1090000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1090000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1090000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1090000[$branch] = $account_1090000_debet[$branch] - $account_1090000_credit[$branch];
					$account_aset_lain_konsolidasi += $account_1090000[$branch];
					$account_aset_tidak_lancar[$branch] += $account_1090000[$branch];
				}
				
				$account_aset_tidak_lancar_konsolidasi = $account_aset_tetap_konsolidasi + $account_aset_lain_konsolidasi;
				
				
				$objPHPExcel->getActiveSheet()->setCellValue("A17", "     Aset Tetap - setelah dikurangi");
				$objPHPExcel->getActiveSheet()->setCellValue("B17", $account_aset_tetap_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C17", $account_aset_tetap[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D17", $account_aset_tetap[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E17", $account_aset_tetap[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F17", $account_aset_tetap[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G17", $account_aset_tetap[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H17", $account_aset_tetap[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I17", $account_aset_tetap[6]);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A18", "     Aset Lain");
				$objPHPExcel->getActiveSheet()->setCellValue("B18", $account_aset_lain_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C18", $account_1090000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D18", $account_1090000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E18", $account_1090000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F18", $account_1090000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G18", $account_1090000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H18", $account_1090000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I18", $account_1090000[6]);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A19", "Jumlah Aset Tidak Lancar");
				$objPHPExcel->getActiveSheet()->setCellValue("B19", $account_aset_tidak_lancar_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C19", $account_aset_tidak_lancar[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D19", $account_aset_tidak_lancar[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E19", $account_aset_tidak_lancar[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F19", $account_aset_tidak_lancar[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G19", $account_aset_tidak_lancar[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H19", $account_aset_tidak_lancar[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I19", $account_aset_tidak_lancar[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B17:I19")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('B19:I19')->applyFromArray($style_border_top_btm);
				
			//JUMLAH ASET
			for($branch=0; $branch <=6; $branch++){
				$account_aset[$branch] += $account_aset_lancar[$branch] + $account_aset_tidak_lancar[$branch];
				$account_aset_konsolidasi += $account_aset_lancar[$branch] + $account_aset_tidak_lancar[$branch];
			}
			$account_aset_tidak_lancar_konsolidasi = $account_aset_tetap_konsolidasi + $account_aset_lain_konsolidasi;
			
			$objPHPExcel->getActiveSheet()->setCellValue("A21", "JUMLAH ASET");
			$objPHPExcel->getActiveSheet()->setCellValue("B21", $account_aset_konsolidasi);
			$objPHPExcel->getActiveSheet()->setCellValue("C21", $account_aset[0]);
			$objPHPExcel->getActiveSheet()->setCellValue("D21", $account_aset[1]);
			$objPHPExcel->getActiveSheet()->setCellValue("E21", $account_aset[4]);
			$objPHPExcel->getActiveSheet()->setCellValue("F21", $account_aset[3]);
			$objPHPExcel->getActiveSheet()->setCellValue("G21", $account_aset[2]);
			$objPHPExcel->getActiveSheet()->setCellValue("H21", $account_aset[5]);
			$objPHPExcel->getActiveSheet()->setCellValue("I21", $account_aset[6]);
			$objPHPExcel->getActiveSheet()->getStyle("H21:I21")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('B21:I21')->applyFromArray($style_border_top_btm_double);
			$objPHPExcel->getActiveSheet()->getStyle("A21:I21")->applyFromArray(array("font" => array( "bold" => true)));
			
			
			
			//LIABILITAS
			$objPHPExcel->getActiveSheet()->setCellValue("A23", "LIABILITAS DAN EKUITAS");
			$objPHPExcel->getActiveSheet()->setCellValue("A24", "LIABILITAS JANGKA PENDEK");
			$objPHPExcel->getActiveSheet()->getStyle("A23:A24")->applyFromArray(array("font" => array( "bold" => true)));
							
				//Simpanan Anggota 2010000
				$code = "2010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010000[$branch] = $account_2010000_credit[$branch] - $account_2010000_debet[$branch];
					$account_2010000_konsolidasi += $account_2010000[$branch];
					$account_liabilitas[$branch] += $account_2010000[$branch];
				}
				
				//Simpanan Berjangka 2020000
				$code = "2020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2020000[$branch] = $account_2020000_credit[$branch] - $account_2020000_debet[$branch];
					$account_2020000_konsolidasi += $account_2020000[$branch];
					$account_liabilitas[$branch] += $account_2020000[$branch];
				}
				
				//Hutang Pembiayaan 2030000
				$code = "2030000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2030000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2030000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2030000[$branch] = $account_2030000_credit[$branch] - $account_2030000_debet[$branch];
					if($branch!=0) { $account_2030000[$branch] = 0; }
					$account_2030000_konsolidasi += $account_2030000[$branch];
					$account_liabilitas[$branch] += $account_2030000[$branch];
				}
				
				/*//Hutang Pembiayaan Kantor Pusat 2030200*/
				$code = "2030200";
				$code_level2 = substr($code,0,5);
				for($branch=0; $branch <=6; $branch++){
					//$account_2030200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					//$account_2030200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);
					//$account_2030200[$branch] = $account_2030200_credit[$branch] - $account_2030200_debet[$branch];
					$account_2030200[$branch] =0;
					//if($branch==0) { $account_2030000[0] = 0; }
					$account_2030200_konsolidasi += $account_2030200[$branch];
					$account_liabilitas[$branch] += $account_2030200[$branch];
				}
				
				//Hutang Leasing 2040000
				$code = "2040000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2040000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2040000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2040000[$branch] = $account_2040000_credit[$branch] - $account_2040000_debet[$branch];
					$account_2040000_konsolidasi += $account_2040000[$branch];
					$account_liabilitas[$branch] += $account_2040000[$branch];
				}
				
				//Hutang Lain-lain 2050000
				$code = "2050000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2050000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2050000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2050000[$branch] = $account_2050000_credit[$branch] - $account_2050000_debet[$branch];
					$account_2050000_konsolidasi += $account_2050000[$branch];
					$account_liabilitas[$branch] += $account_2050000[$branch];
				}
				
				for($branch=0; $branch <=6; $branch++){
					$account_liabilitas_konsolidasi += $account_liabilitas[$branch];
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue("A25", "     Simpanan Anggota");
				$objPHPExcel->getActiveSheet()->setCellValue("B25", $account_2010000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C25", $account_2010000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D25", $account_2010000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E25", $account_2010000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F25", $account_2010000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G25", $account_2010000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H25", $account_2010000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I25", $account_2010000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H25:I25")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A26", "     Simpanan Berjangka");
				$objPHPExcel->getActiveSheet()->setCellValue("B26", $account_2020000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C26", $account_2020000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D26", $account_2020000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E26", $account_2020000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F26", $account_2020000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G26", $account_2020000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H26", $account_2020000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I26", $account_2020000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H26:I26")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A27", "     Hutang Pembiayaan");
				$objPHPExcel->getActiveSheet()->setCellValue("B27", $account_2030000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C27", $account_2030000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D27", $account_2030000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E27", $account_2030000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F27", $account_2030000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G27", $account_2030000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H27", $account_2030000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I27", $account_2030000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H27:I27")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A28", "     Hutang Pembiayaan K. Pusat");
				$objPHPExcel->getActiveSheet()->setCellValue("B28", $account_2030200_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C28", $account_2030200[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D28", $account_2030200[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E28", $account_2030200[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F28", $account_2030200[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G28", $account_2030200[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H28", $account_2030200[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I28", $account_2030200[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H28:I28")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A29", "     Hutang Leasing");
				$objPHPExcel->getActiveSheet()->setCellValue("B29", $account_2040000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C29", $account_2040000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D29", $account_2040000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E29", $account_2040000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F29", $account_2040000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G29", $account_2040000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H29", $account_2040000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I29", $account_2040000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H29:I29")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A30", "     Hutang Lain-lain");
				$objPHPExcel->getActiveSheet()->setCellValue("B30", $account_2050000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C30", $account_2050000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D30", $account_2050000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E30", $account_2050000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F30", $account_2050000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G30", $account_2050000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H30", $account_2050000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I30", $account_2050000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H30:I30")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=31;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "Jumlah Liabilitas Jangka Pendek");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_liabilitas_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_liabilitas[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_liabilitas[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_liabilitas[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_liabilitas[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_liabilitas[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_liabilitas[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_liabilitas[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->applyFromArray($style_border_top_btm);
				
				
			//EKUITAS
			$no=33;
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "EKUITAS");
			$objPHPExcel->getActiveSheet()->getStyle("A$no")->applyFromArray(array("font" => array( "bold" => true)));
			
				//Simpanan Pokok 3010102
				$code = "3010102";
				for($branch=0; $branch <=6; $branch++){
					$account_3010102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010102[$branch] = $account_3010102_credit[$branch] - $account_3010102_debet[$branch];
					$account_3010102_konsolidasi += $account_3010102[$branch];
					$account_ekuitas[$branch] += $account_3010102[$branch];
					$account_ekuitas_konsolidasi += $account_3010102[$branch];
				}	
				//Simpanan Wajib 3010101
				$code = "3010101";
				for($branch=0; $branch <=6; $branch++){
					$account_3010101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010101[$branch] = $account_3010101_credit[$branch] - $account_3010101_debet[$branch];
					$account_3010101_konsolidasi += $account_3010101[$branch];
					$account_ekuitas[$branch] += $account_3010101[$branch];
					$account_ekuitas_konsolidasi += $account_3010101[$branch];
				}
				//Hibah 3010103
				$code = "3010103";
				for($branch=0; $branch <=6; $branch++){
					$account_3010103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010103[$branch] = $account_3010103_credit[$branch] - $account_3010103_debet[$branch];
					$account_3010103_konsolidasi += $account_3010103[$branch];
					$account_ekuitas[$branch] += $account_3010103[$branch];
					$account_ekuitas_konsolidasi += $account_3010103[$branch];
				}
				//Modal Penyertaan 3010201
				$code = "3010201";
				for($branch=0; $branch <=6; $branch++){
					$account_3010201_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010201_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010201[$branch] = $account_3010201_credit[$branch] - $account_3010201_debet[$branch];
					$account_3010201_konsolidasi += $account_3010201[$branch];
					$account_ekuitas[$branch] += $account_3010201[$branch];
					$account_ekuitas_konsolidasi += $account_3010201[$branch];
				}	
				//SHU Tahun Lalu
				$code = "3020001";
				for($branch=0; $branch <=6; $branch++){
					$account_3020001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3020001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3020001[$branch] = $account_3020001_credit[$branch] - $account_3020001_debet[$branch];
					$account_3020001_konsolidasi += $account_3020001[$branch];
					$account_ekuitas[$branch] += $account_3020001[$branch];
					$account_ekuitas_konsolidasi += $account_3020001[$branch];
				}
				for($branch=0; $branch <=6; $branch++){
					$account_3020002[$branch] = $this->hitung_laba_rugi($date_start,$date_end,$branch);
					$account_3020002_konsolidasi += $account_3020002[$branch];
					$account_ekuitas[$branch] += $account_3020002[$branch];
					$account_ekuitas_konsolidasi += $account_3020002[$branch];
				}
				$lr_ciseeng = $this->hitung_laba_rugi($date_start,$date_end,$user_branch);				
				
				
				$no=34;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     Simpanan Pokok");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3010102_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3010102[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3010102[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3010102[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3010102[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3010102[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3010102[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3010102[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=35;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     Simpanan Wajib");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3010101_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3010101[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3010101[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3010101[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3010101[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3010101[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3010101[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3010101[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=36;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     Hibah");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3010103_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3010103[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3010103[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3010103[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3010103[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3010103[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3010103[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3010103[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=37;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     Modal Penyertaan");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3010201_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3010201[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3010201[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3010201[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3010201[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3010201[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3010201[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3010201[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=38;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     SHU Tahun Lalu");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3020001_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3020001[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3020001[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3020001[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3020001[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3020001[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3020001[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3020001[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=39;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     SHU Tahun Berjalan");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3020002_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3020002[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3020002[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3020002[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3020002[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3020002[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3020002[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3020002[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
			$no=40;
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "Jumlah Ekuitas");
			$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_ekuitas_konsolidasi);
			$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_ekuitas[0]);
			$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_ekuitas[1]);
			$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_ekuitas[4]);
			$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_ekuitas[3]);
			$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_ekuitas[2]);
			$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_ekuitas[5]);
			$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_ekuitas[6]);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->applyFromArray($style_border_top_btm);
				
			//JUMLAH lIABILITAS EKUITAS
			for($branch=0; $branch <=6; $branch++){
				$account_liabilitas_ekuitas[$branch] += $account_liabilitas[$branch] + $account_ekuitas[$branch];
				$account_liabilitas_ekuitas_konsolidasi += $account_liabilitas[$branch] + $account_ekuitas[$branch];
			}
			
			$account_aset_tidak_lancar_konsolidasi = $account_aset_tetap_konsolidasi + $account_aset_lain_konsolidasi;
				
			$no=42;
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "JUMLAH LIABILITAS DAN EKUITAS");
			$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_liabilitas_ekuitas_konsolidasi);
			$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_liabilitas_ekuitas[0]);
			$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_liabilitas_ekuitas[1]);
			$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_liabilitas_ekuitas[4]);
			$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_liabilitas_ekuitas[3]);
			$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_liabilitas_ekuitas[2]);
			$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_liabilitas_ekuitas[5]);
			$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_liabilitas_ekuitas[6]);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->applyFromArray($style_border_top_btm_double);
			$objPHPExcel->getActiveSheet()->getStyle("A$no:I$no")->applyFromArray(array("font" => array( "bold" => true)));
			
			
			//SELISIH
			$selisih = $account_aset_konsolidasi - $account_liabilitas_ekuitas_konsolidasi;	
			for($branch=0; $branch <=6; $branch++){
				$account_selisih[$branch] += $account_aset[$branch] - $account_liabilitas_ekuitas[$branch];
			}	
				
				
			$no=44;
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "SELISIH");
			$objPHPExcel->getActiveSheet()->setCellValue("B$no", ROUND($selisih));
			$objPHPExcel->getActiveSheet()->setCellValue("C$no", ROUND($account_selisih[0]));
			$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_selisih[1]);
			$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_selisih[4]);
			$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_selisih[3]);
			$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_selisih[2]);
			$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_selisih[5]);
			$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_selisih[6]);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->applyFromArray($style_border_top_btm_double);
			$objPHPExcel->getActiveSheet()->getStyle("A$no:I$no")->applyFromArray(array("font" => array( "bold" => true)));

				
			//Set Column Format Accounting
			$objPHPExcel->getActiveSheet()->getStyle("A7:I42")->getNumberFormat()->setFormatCode("#0");
			//Set Column Auto Width
			foreach(range('B','I') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			
			
			
			
			
			//EXPORT	
			$filename = "Neraca_Konsolidasi_" . time() . '.xls'; //save our workbook as this file name
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
	

	//LABARUGI EXCEL
	public function labarugi_excel()
	{
		if($this->session->userdata('logged_in'))
		{
			
			//Cek User Branch
			$user_branch = $this->session->userdata('user_branch');
			
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
			$date_start=$this->uri->segment(3);
			$date_end=$this->uri->segment(4);
			if($date_start AND $date_end AND ($date_start <= $date_end )){
				$date_start=$this->uri->segment(3);
				$date_end=$this->uri->segment(4);
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
			
			
				
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				$saldo_awal =0;
			
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();
			$style_border_top_btm = array(
				  'borders' => array(
					'bottom' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					),
					'top' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				  )
				);
			$style_border_top_btm_double = array(
				  'borders' => array(
					'bottom' => array(
					  'style' => PHPExcel_Style_Border::BORDER_DOUBLE
					),
					'top' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				  )
				);
			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Laba Rugi Konsolidasi");
			$objPHPExcel->getProperties()->setSubject("Laba Rugi Konsolidasi");
			$objPHPExcel->getProperties()->setDescription("Laba Rugi Konsolidasi");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Laba Rugi Konsolidasi');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Laba Rugi Konsolidasi");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A3", $date_start." s/d ".$date_end);
			$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray(array("font" => array( "bold" => true)));
			
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:I4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("B4:I4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "");
			$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(50);
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "KONSOLIDASI");
			$objPHPExcel->getActiveSheet()->getColumnDimension("B4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "PUSAT");
			$objPHPExcel->getActiveSheet()->getColumnDimension("C4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "CS");
			$objPHPExcel->getActiveSheet()->getColumnDimension("D4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "JS");
			$objPHPExcel->getActiveSheet()->getColumnDimension("E4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "BG");
			$objPHPExcel->getActiveSheet()->getColumnDimension("F4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("G4", "KM");
			$objPHPExcel->getActiveSheet()->getColumnDimension("G4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TN");			
			$objPHPExcel->getActiveSheet()->getColumnDimension("H4")->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "CK");			
			$objPHPExcel->getActiveSheet()->getColumnDimension("I4")->setWidth(25);
			
			
			
			//PENDAPATAN
			$print .= '	<tr><td align="left" ><b>Pendapatan</b></td>	<td colspan="8" ></td></tr>';
			
			$cell_no = 5;
			$objPHPExcel->getActiveSheet()->setCellValue("A$cell_no", "Pendapatan");
			
				
			
				//Kas
				//1010000
				$code = "1010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1010000[$branch] = $account_1010000_debet[$branch] - $account_1010000_credit[$branch] ;
					$account_1010000_konsolidasi += $account_1010000[$branch];
					$account_kas[$branch] += $account_1010000[$branch];				
					$account_aset_lancar[$branch] += $account_1010000[$branch];	
				}
				//1020000 
				$code = "1020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020000[$branch] = $account_1020000_debet[$branch] - $account_1020000_credit[$branch];
					$account_1020000_konsolidasi += $account_1020000[$branch];
					$account_kas[$branch] += $account_1020000[$branch];		
					$account_aset_lancar[$branch] += $account_1020000[$branch];
				}
				//Total Kas
				$account_kas_konsolidasi = $account_1010000_konsolidasi + $account_1020000_konsolidasi;
				
				$objPHPExcel->getActiveSheet()->getStyle("A4:A6")->applyFromArray(array("font" => array( "bold" => true)));
				$objPHPExcel->getActiveSheet()->setCellValue("A7", "     Kas dan setara Kas");
				$objPHPExcel->getActiveSheet()->setCellValue("B7", $account_kas_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C7", $account_kas[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D7", $account_kas[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E7", $account_kas[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F7", $account_kas[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G7", $account_kas[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H7", $account_kas[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I7", $account_kas[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B7:I7")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				
				//Piutang MBA 1030102
				$code = "1030102";
				for($branch=0; $branch <=6; $branch++){
					$account_1030102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030102[$branch] = $account_1030102_debet[$branch] - $account_1030102_credit[$branch];
					$account_1030102_konsolidasi += $account_1030102[$branch];
					$account_piutang_pembiayaan[$branch] += $account_1030102[$branch];			
					$account_aset_lancar[$branch] += $account_1030102[$branch];
				}
				//Piutang IJA 1030103
				$code = "1030103";
				for($branch=0; $branch <=6; $branch++){
					$account_1030103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030103[$branch] = $account_1030103_debet[$branch] - $account_1030103_credit[$branch];
					$account_1030103_konsolidasi += $account_1030103[$branch];
					$account_piutang_pembiayaan[$branch] += $account_1030103[$branch];				
					$account_aset_lancar[$branch] += $account_1030103[$branch];
				}
				//Piutang QH 1030104
				$code = "1030104";
				for($branch=0; $branch <=6; $branch++){
					$account_1030104_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030104_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030104[$branch] = $account_1030104_debet[$branch] - $account_1030104_credit[$branch];
					$account_1030104_konsolidasi += $account_1030104[$branch];

					$account_piutang_pembiayaan[$branch] += $account_1030104[$branch];					
					$account_aset_lancar[$branch] += $account_1030104[$branch];
				}
				//Piutang Pembiayaan Lembaga 1030200
				
				
				
				//Piutang QH 1030504
				$code = "1030504";
				for($branch=0; $branch <=6; $branch++){
					$account_1030504_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1030504_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1030104[$branch] = $account_1030504_debet[$branch] - $account_1030504_credit[$branch];
					$account_1030104_konsolidasi += $account_1030104[$branch];
					$account_piutang_pembiayaan[$branch] += $account_1030104[$branch];					
					$account_aset_lancar[$branch] += $account_1030104[$branch];
				}
				
				$code = "1030200";
				$code_level2 = substr($code,0,5);
				for($branch=0; $branch <=6; $branch++){
					$account_1030200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030200[$branch] = $account_1030200_debet[$branch] - $account_1030200_credit[$branch];
					$account_1030104[$branch] += $account_1030200[$branch];
					$account_1030104_konsolidasi += $account_1030200[$branch];

					$account_piutang_pembiayaan[$branch] += $account_1030200[$branch];					
					$account_aset_lancar[$branch] += $account_1030200[$branch];
				}
				
				/*
				//Piutang Cabang 1030400
				$code = "1030400";
				$code_level2 = substr($code,0,5);
				for($branch=0; $branch <=6; $branch++){
					$account_1030400_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030400_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);
					$account_1030400[$branch] = $account_1030400_debet[$branch] - $account_1030400_credit[$branch];
					//$account_1030400_konsolidasi += $account_1030400[$branch];
					$account_1030400_konsolidasi = 0; 
					$account_piutang_pembiayaan[$branch] += $account_1030400[$branch];
					$account_aset_lancar[$branch] += $account_1030400[$branch];
				}
				*/
				
				
				$account_piutang_pembiayaan_konsolidasi = $account_1030102_konsolidasi  + $account_1030103_konsolidasi + + $account_1030104_konsolidasi + $account_1030400_konsolidasi;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A8", "     Piutang Pembiayaan");
				$objPHPExcel->getActiveSheet()->setCellValue("B8", $account_piutang_pembiayaan_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C8", $account_piutang_pembiayaan[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D8", $account_piutang_pembiayaan[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E8", $account_piutang_pembiayaan[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F8", $account_piutang_pembiayaan[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G8", $account_piutang_pembiayaan[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H8", $account_piutang_pembiayaan[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I8", $account_piutang_pembiayaan[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B8:I8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A9", "          Murabahah");
				$objPHPExcel->getActiveSheet()->setCellValue("B9", $account_1030102_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C9", $account_1030102[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D9", $account_1030102[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E9", $account_1030102[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F9", $account_1030102[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G9", $account_1030102[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H9", $account_1030102[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I9", $account_1030102[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B9:I9")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A10", "          Ijarah");
				$objPHPExcel->getActiveSheet()->setCellValue("B10", $account_1030103_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C10", $account_1030103[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D10", $account_1030103[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E10", $account_1030103[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F10", $account_1030103[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G10", $account_1030103[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H10", $account_1030103[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I10", $account_1030103[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B10:I10")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A11", "          Qadrul Hasan");
				$objPHPExcel->getActiveSheet()->setCellValue("B11", $account_1030104_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C11", $account_1030104[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D11", $account_1030104[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E11", $account_1030104[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F11", $account_1030104[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G11", $account_1030104[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H11", $account_1030104[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I11", $account_1030104[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B11:I11")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				//Beban dibayar dimuka 1050000
				$code = "1050000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1050000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1050000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1050000[$branch] = $account_1050000_debet[$branch] - $account_1050000_credit[$branch];
					$account_1050000_konsolidasi += $account_1050000[$branch];
					$account_aset_lancar[$branch] += $account_1050000[$branch];
				}
				//Persediaan Barang Cetakan 1060000
				$code = "1060000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1060000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1060000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1060000[$branch] = $account_1060000_debet[$branch] - $account_1060000_credit[$branch];
					$account_1060000_konsolidasi += $account_1060000[$branch];
					$account_aset_lancar[$branch] += $account_1060000[$branch];
				}
				$account_aset_lancar_konsolidasi = $account_kas_konsolidasi + $account_piutang_pembiayaan_konsolidasi + $account_1060000_konsolidasi+$account_1050000_konsolidasi;
				
				$objPHPExcel->getActiveSheet()->setCellValue("A13", "     Beban dibayar dimuka");
				$objPHPExcel->getActiveSheet()->setCellValue("B13", $account_1050000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C13", $account_1050000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D13", $account_1050000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E13", $account_1050000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F13", $account_1050000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G13", $account_1050000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H13", $account_1050000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I13", $account_1050000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B13:I13")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
				$objPHPExcel->getActiveSheet()->setCellValue("A14", "     Persediaan Barang Cetakan");
				$objPHPExcel->getActiveSheet()->setCellValue("B14", $account_1060000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C14", $account_1060000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D14", $account_1060000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E14", $account_1060000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F14", $account_1060000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G14", $account_1060000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H14", $account_1060000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I14", $account_1060000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B14:I14")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
				$objPHPExcel->getActiveSheet()->setCellValue("A14", "Jumlah Aset Lancar");
				$objPHPExcel->getActiveSheet()->setCellValue("B14", $account_aset_lancar_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C14", $account_aset_lancar[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D14", $account_aset_lancar[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E14", $account_aset_lancar[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F14", $account_aset_lancar[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G14", $account_aset_lancar[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H14", $account_aset_lancar[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I14", $account_aset_lancar[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B14:I14")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('B14:I14')->applyFromArray($style_border_top_btm);
							
			//ASET TIDAK LANCAR
			$objPHPExcel->getActiveSheet()->setCellValue("A16", "ASET TIDAK LANCAR");
			$objPHPExcel->getActiveSheet()->getStyle("A16")->applyFromArray(array("font" => array( "bold" => true)));
			
				//Aset Tetap setelah dikurangi = 1080301 + 1080302				
				$code = "1080301";
				for($branch=0; $branch <=6; $branch++){
					$account_1080301_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1080301_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1080301[$branch] = $account_1080301_debet[$branch] - $account_1080301_credit[$branch];
					$account_1080301_konsolidasi += $account_1080301[$branch];
					$account_aset_tetap[$branch] += $account_1080301[$branch];					
					$account_aset_tidak_lancar[$branch] += $account_1080301[$branch];
				}
				$code = "1080302";
				for($branch=0; $branch <=6; $branch++){
					$account_1080302_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1080302_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1080302[$branch] = $account_1080302_debet[$branch] - $account_1080302_credit[$branch];
					$account_1080302_konsolidasi += $account_1080302[$branch];
					$account_aset_tetap[$branch] += $account_1080302[$branch];					
					$account_aset_tidak_lancar[$branch] += $account_1080302[$branch];
				}
				$account_aset_tetap_konsolidasi = $account_1080301_konsolidasi + $account_1080302_konsolidasi;
				
				//Aset Lain	1090000
				$code = "1090000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_1090000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1090000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1090000[$branch] = $account_1090000_debet[$branch] - $account_1090000_credit[$branch];
					$account_aset_lain_konsolidasi += $account_1090000[$branch];
					$account_aset_tidak_lancar[$branch] += $account_1090000[$branch];
				}
				
				$account_aset_tidak_lancar_konsolidasi = $account_aset_tetap_konsolidasi + $account_aset_lain_konsolidasi;
				
				
				$objPHPExcel->getActiveSheet()->setCellValue("A17", "     Aset Tetap - setelah dikurangi");
				$objPHPExcel->getActiveSheet()->setCellValue("B17", $account_aset_tetap_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C17", $account_aset_tetap[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D17", $account_aset_tetap[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E17", $account_aset_tetap[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F17", $account_aset_tetap[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G17", $account_aset_tetap[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H17", $account_aset_tetap[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I17", $account_aset_tetap[6]);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A18", "     Aset Lain");
				$objPHPExcel->getActiveSheet()->setCellValue("B18", $account_aset_lain_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C18", $account_1090000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D18", $account_1090000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E18", $account_1090000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F18", $account_1090000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G18", $account_1090000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H18", $account_1090000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I18", $account_1090000[6]);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A19", "Jumlah Aset Tidak Lancar");
				$objPHPExcel->getActiveSheet()->setCellValue("B19", $account_aset_tidak_lancar_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C19", $account_aset_tidak_lancar[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D19", $account_aset_tidak_lancar[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E19", $account_aset_tidak_lancar[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F19", $account_aset_tidak_lancar[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G19", $account_aset_tidak_lancar[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H19", $account_aset_tidak_lancar[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I19", $account_aset_tidak_lancar[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B17:I19")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('B19:I19')->applyFromArray($style_border_top_btm);
				
			//JUMLAH ASET
			for($branch=0; $branch <=6; $branch++){
				$account_aset[$branch] += $account_aset_lancar[$branch] + $account_aset_tidak_lancar[$branch];
				$account_aset_konsolidasi += $account_aset_lancar[$branch] + $account_aset_tidak_lancar[$branch];
			}
			$account_aset_tidak_lancar_konsolidasi = $account_aset_tetap_konsolidasi + $account_aset_lain_konsolidasi;
			
			$objPHPExcel->getActiveSheet()->setCellValue("A21", "JUMLAH ASET");
			$objPHPExcel->getActiveSheet()->setCellValue("B21", $account_aset_konsolidasi);
			$objPHPExcel->getActiveSheet()->setCellValue("C21", $account_aset[0]);
			$objPHPExcel->getActiveSheet()->setCellValue("D21", $account_aset[1]);
			$objPHPExcel->getActiveSheet()->setCellValue("E21", $account_aset[4]);
			$objPHPExcel->getActiveSheet()->setCellValue("F21", $account_aset[3]);
			$objPHPExcel->getActiveSheet()->setCellValue("G21", $account_aset[2]);
			$objPHPExcel->getActiveSheet()->setCellValue("H21", $account_aset[5]);
			$objPHPExcel->getActiveSheet()->setCellValue("I21", $account_aset[6]);
			$objPHPExcel->getActiveSheet()->getStyle("H21:I21")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('B21:I21')->applyFromArray($style_border_top_btm_double);
			$objPHPExcel->getActiveSheet()->getStyle("A21:I21")->applyFromArray(array("font" => array( "bold" => true)));
			
			
			
			//LIABILITAS
			$objPHPExcel->getActiveSheet()->setCellValue("A23", "LIABILITAS DAN EKUITAS");
			$objPHPExcel->getActiveSheet()->setCellValue("A24", "LIABILITAS JANGKA PENDEK");
			$objPHPExcel->getActiveSheet()->getStyle("A23:A24")->applyFromArray(array("font" => array( "bold" => true)));
							
				//Simpanan Anggota 2010000
				$code = "2010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010000[$branch] = $account_2010000_credit[$branch] - $account_2010000_debet[$branch];
					$account_2010000_konsolidasi += $account_2010000[$branch];
					$account_liabilitas[$branch] += $account_2010000[$branch];
				}
				
				//Simpanan Berjangka 2020000
				$code = "2020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2020000[$branch] = $account_2020000_credit[$branch] - $account_2020000_debet[$branch];
					$account_2020000_konsolidasi += $account_2020000[$branch];
					$account_liabilitas[$branch] += $account_2020000[$branch];
				}
				
				//Hutang Pembiayaan 2030000
				$code = "2030000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2030000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2030000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2030000[$branch] = $account_2030000_credit[$branch] - $account_2030000_debet[$branch];
					if($branch!=0) { $account_2030000[$branch] = 0; }
					$account_2030000_konsolidasi += $account_2030000[$branch];
					$account_liabilitas[$branch] += $account_2030000[$branch];
				}
				
				/*//Hutang Pembiayaan Kantor Pusat 2030200*/
				$code = "2030200";
				$code_level2 = substr($code,0,5);
				for($branch=0; $branch <=6; $branch++){
					//$account_2030200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$branch);
					//$account_2030200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$branch);
					//$account_2030200[$branch] = $account_2030200_credit[$branch] - $account_2030200_debet[$branch];
					$account_2030200[$branch] =0;
					//if($branch==0) { $account_2030000[0] = 0; }
					$account_2030200_konsolidasi += $account_2030200[$branch];
					$account_liabilitas[$branch] += $account_2030200[$branch];
				}
				
				//Hutang Leasing 2040000
				$code = "2040000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2040000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2040000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2040000[$branch] = $account_2040000_credit[$branch] - $account_2040000_debet[$branch];
					$account_2040000_konsolidasi += $account_2040000[$branch];
					$account_liabilitas[$branch] += $account_2040000[$branch];
				}
				
				//Hutang Lain-lain 2050000
				$code = "2050000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <=6; $branch++){
					$account_2050000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2050000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2050000[$branch] = $account_2050000_credit[$branch] - $account_2050000_debet[$branch];
					$account_2050000_konsolidasi += $account_2050000[$branch];
					$account_liabilitas[$branch] += $account_2050000[$branch];
				}
				
				for($branch=0; $branch <=6; $branch++){
					$account_liabilitas_konsolidasi += $account_liabilitas[$branch];
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue("A25", "     Simpanan Anggota");
				$objPHPExcel->getActiveSheet()->setCellValue("B25", $account_2010000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C25", $account_2010000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D25", $account_2010000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E25", $account_2010000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F25", $account_2010000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G25", $account_2010000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H25", $account_2010000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I25", $account_2010000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H25:I25")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A26", "     Simpanan Berjangka");
				$objPHPExcel->getActiveSheet()->setCellValue("B26", $account_2020000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C26", $account_2020000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D26", $account_2020000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E26", $account_2020000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F26", $account_2020000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G26", $account_2020000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H26", $account_2020000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I26", $account_2020000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H26:I26")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A27", "     Hutang Pembiayaan");
				$objPHPExcel->getActiveSheet()->setCellValue("B27", $account_2030000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C27", $account_2030000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D27", $account_2030000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E27", $account_2030000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F27", $account_2030000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G27", $account_2030000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H27", $account_2030000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I27", $account_2030000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H27:I27")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A28", "     Hutang Pembiayaan K. Pusat");
				$objPHPExcel->getActiveSheet()->setCellValue("B28", $account_2030200_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C28", $account_2030200[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D28", $account_2030200[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E28", $account_2030200[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F28", $account_2030200[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G28", $account_2030200[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H28", $account_2030200[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I28", $account_2030200[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H28:I28")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A29", "     Hutang Leasing");
				$objPHPExcel->getActiveSheet()->setCellValue("B29", $account_2040000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C29", $account_2040000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D29", $account_2040000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E29", $account_2040000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F29", $account_2040000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G29", $account_2040000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H29", $account_2040000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I29", $account_2040000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H29:I29")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A30", "     Hutang Lain-lain");
				$objPHPExcel->getActiveSheet()->setCellValue("B30", $account_2050000_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C30", $account_2050000[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D30", $account_2050000[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E30", $account_2050000[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F30", $account_2050000[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G30", $account_2050000[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H30", $account_2050000[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I30", $account_2050000[6]);
				$objPHPExcel->getActiveSheet()->getStyle("H30:I30")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=31;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "Jumlah Liabilitas Jangka Pendek");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_liabilitas_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_liabilitas[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_liabilitas[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_liabilitas[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_liabilitas[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_liabilitas[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_liabilitas[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_liabilitas[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->applyFromArray($style_border_top_btm);
				
				
			//EKUITAS
			$no=33;
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "EKUITAS");
			$objPHPExcel->getActiveSheet()->getStyle("A$no")->applyFromArray(array("font" => array( "bold" => true)));
			
				//Simpanan Pokok 3010102
				$code = "3010102";
				for($branch=0; $branch <=6; $branch++){
					$account_3010102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010102[$branch] = $account_3010102_credit[$branch] - $account_3010102_debet[$branch];
					$account_3010102_konsolidasi += $account_3010102[$branch];
					$account_ekuitas[$branch] += $account_3010102[$branch];
					$account_ekuitas_konsolidasi += $account_3010102[$branch];
				}	
				//Simpanan Wajib 3010101
				$code = "3010101";
				for($branch=0; $branch <=6; $branch++){
					$account_3010101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010101[$branch] = $account_3010101_credit[$branch] - $account_3010101_debet[$branch];
					$account_3010101_konsolidasi += $account_3010101[$branch];
					$account_ekuitas[$branch] += $account_3010101[$branch];
					$account_ekuitas_konsolidasi += $account_3010101[$branch];
				}
				//Hibah 3010103
				$code = "3010103";
				for($branch=0; $branch <=6; $branch++){
					$account_3010103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010103[$branch] = $account_3010103_credit[$branch] - $account_3010103_debet[$branch];
					$account_3010103_konsolidasi += $account_3010103[$branch];
					$account_ekuitas[$branch] += $account_3010103[$branch];
					$account_ekuitas_konsolidasi += $account_3010103[$branch];
				}
				//Modal Penyertaan 3010201
				$code = "3010201";
				for($branch=0; $branch <=6; $branch++){
					$account_3010201_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010201_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010201[$branch] = $account_3010201_credit[$branch] - $account_3010201_debet[$branch];
					$account_3010201_konsolidasi += $account_3010201[$branch];
					$account_ekuitas[$branch] += $account_3010201[$branch];
					$account_ekuitas_konsolidasi += $account_3010201[$branch];
				}	
				//SHU Tahun Lalu
				$code = "3020001";
				for($branch=0; $branch <=6; $branch++){
					$account_3020001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3020001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3020001[$branch] = $account_3020001_credit[$branch] - $account_3020001_debet[$branch];
					$account_3020001_konsolidasi += $account_3020001[$branch];
					$account_ekuitas[$branch] += $account_3020001[$branch];
					$account_ekuitas_konsolidasi += $account_3020001[$branch];
				}
				for($branch=0; $branch <=6; $branch++){
					$account_3020002[$branch] = $this->hitung_laba_rugi($date_start,$date_end,$branch);
					$account_3020002_konsolidasi += $account_3020002[$branch];
					$account_ekuitas[$branch] += $account_3020002[$branch];
					$account_ekuitas_konsolidasi += $account_3020002[$branch];
				}
				$lr_ciseeng = $this->hitung_laba_rugi($date_start,$date_end,$user_branch);				
				
				
				$no=34;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     Simpanan Pokok");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3010102_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3010102[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3010102[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3010102[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3010102[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3010102[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3010102[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3010102[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=35;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     Simpanan Wajib");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3010101_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3010101[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3010101[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3010101[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3010101[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3010101[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3010101[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3010101[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=36;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     Hibah");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3010103_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3010103[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3010103[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3010103[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3010103[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3010103[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3010103[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3010103[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=37;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     Modal Penyertaan");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3010201_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3010201[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3010201[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3010201[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3010201[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3010201[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3010201[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3010201[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=38;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     SHU Tahun Lalu");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3020001_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3020001[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3020001[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3020001[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3020001[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3020001[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3020001[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3020001[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$no=39;
				$objPHPExcel->getActiveSheet()->setCellValue("A$no", "     SHU Tahun Berjalan");
				$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_3020002_konsolidasi);
				$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_3020002[0]);
				$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_3020002[1]);
				$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_3020002[4]);
				$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_3020002[3]);
				$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_3020002[2]);
				$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_3020002[5]);
				$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_3020002[6]);
				$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
			$no=40;
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "Jumlah Ekuitas");
			$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_ekuitas_konsolidasi);
			$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_ekuitas[0]);
			$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_ekuitas[1]);
			$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_ekuitas[4]);
			$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_ekuitas[3]);
			$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_ekuitas[2]);
			$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_ekuitas[5]);
			$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_ekuitas[6]);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->applyFromArray($style_border_top_btm);
				
			//JUMLAH lIABILITAS EKUITAS
			for($branch=0; $branch <=6; $branch++){
				$account_liabilitas_ekuitas[$branch] += $account_liabilitas[$branch] + $account_ekuitas[$branch];
				$account_liabilitas_ekuitas_konsolidasi += $account_liabilitas[$branch] + $account_ekuitas[$branch];
			}
			
			$account_aset_tidak_lancar_konsolidasi = $account_aset_tetap_konsolidasi + $account_aset_lain_konsolidasi;
				
			$no=42;
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "JUMLAH LIABILITAS DAN EKUITAS");
			$objPHPExcel->getActiveSheet()->setCellValue("B$no", $account_liabilitas_ekuitas_konsolidasi);
			$objPHPExcel->getActiveSheet()->setCellValue("C$no", $account_liabilitas_ekuitas[0]);
			$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_liabilitas_ekuitas[1]);
			$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_liabilitas_ekuitas[4]);
			$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_liabilitas_ekuitas[3]);
			$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_liabilitas_ekuitas[2]);
			$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_liabilitas_ekuitas[5]);
			$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_liabilitas_ekuitas[6]);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->applyFromArray($style_border_top_btm_double);
			$objPHPExcel->getActiveSheet()->getStyle("A$no:I$no")->applyFromArray(array("font" => array( "bold" => true)));
			
			
			//SELISIH
			$selisih = $account_aset_konsolidasi - $account_liabilitas_ekuitas_konsolidasi;	
			for($branch=0; $branch <=6; $branch++){
				$account_selisih[$branch] += $account_aset[$branch] - $account_liabilitas_ekuitas[$branch];
			}	
				
				
			$no=44;
			$objPHPExcel->getActiveSheet()->setCellValue("A$no", "SELISIH");
			$objPHPExcel->getActiveSheet()->setCellValue("B$no", ROUND($selisih));
			$objPHPExcel->getActiveSheet()->setCellValue("C$no", ROUND($account_selisih[0]));
			$objPHPExcel->getActiveSheet()->setCellValue("D$no", $account_selisih[1]);
			$objPHPExcel->getActiveSheet()->setCellValue("E$no", $account_selisih[4]);
			$objPHPExcel->getActiveSheet()->setCellValue("F$no", $account_selisih[3]);
			$objPHPExcel->getActiveSheet()->setCellValue("G$no", $account_selisih[2]);
			$objPHPExcel->getActiveSheet()->setCellValue("H$no", $account_selisih[5]);
			$objPHPExcel->getActiveSheet()->setCellValue("I$no", $account_selisih[6]);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("B$no:I$no")->applyFromArray($style_border_top_btm_double);
			$objPHPExcel->getActiveSheet()->getStyle("A$no:I$no")->applyFromArray(array("font" => array( "bold" => true)));

				
			//Set Column Format Accounting
			$objPHPExcel->getActiveSheet()->getStyle("A7:I42")->getNumberFormat()->setFormatCode("#0");
			//Set Column Auto Width
			foreach(range('B','I') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			
			
			
			
			
			//EXPORT	
			$filename = "Neraca_Konsolidasi_" . time() . '.xls'; //save our workbook as this file name
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