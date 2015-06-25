<?php

class Accounting extends Front_Controller{
	
	private $per_page 	= '15';
	private $title 		= 'Accounting';
	private $module 	= 'accounting';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('accounting_model');	
		$this->load->model('jurnal_model');		
		$this->load->model('branch_model');	
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
	
	
	//LABA RUGI
	public function laba_rugi()
	{
		if($this->session->userdata('logged_in'))
		{
			
			$total_branch = $this->branch_model->count_branch();

			
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
				//$date_start =$week_today[0];
				$date_start = "2015-01-01";
				$date_end   = date("Y-m-d");			
			}
			
			$date_end_before = strtotime($date_start);
			//$date_end_before = $date_start;
			$date_end_before = strtotime("-1 day", $date_end_before); 
			$date_end_before = date('Y-m-d', $date_end_before);			
			$date_start_before = "2014-01-01";
			//echo $date_start_before." ----- ".$date_end_before; 
			
				
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				$saldo_awal =0;
				
			//PENDAPATAN
			$print .= '	<tr><td align="left" ><b>Pendapatan</b></td>	<td colspan="8" ></td></tr>';
			
								
				//4010000 Pendapatan Pembiayaan
				$code = "4010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4010000[$branch] = $account_4010000_credit[$branch] - $account_4010000_debet[$branch];
					$account_4010000_total += $account_4010000[$branch];
					$account_pendapatan_total[$branch] += $account_4010000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Pembiayaan</td>';
				$print .= '	<td align="right" class="">'.($account_4010000_total < 0 ? "(".number_format(abs($account_4010000_total)).")" : number_format($account_4010000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_4010000[$branch] < 0 ? "(".number_format(abs($account_4010000[$branch])).")" : number_format($account_4010000[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
				
				//4020000 Pendapatan Jasa Administrasi
				$code = "4020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4020000[$branch] = $account_4020000_credit[$branch] - $account_4020000_debet[$branch];
					$account_4020000_total += $account_4020000[$branch];
					$account_pendapatan_total[$branch] += $account_4020000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Administrasi</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_4020000_total < 0 ? "(".number_format(abs($account_4020000_total)).")" : number_format($account_4020000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_4020000[$branch] < 0 ? "(".number_format(abs($account_4020000[$branch])).")" : number_format($account_4020000[$branch])).'</td>';
				}
				$print .= '</tr>';	
							
				//Jumlah Pendapatan			
				$account_pendapatan_konsolidasi = $account_4010000_total + $account_4020000_total;
				$print .= '	<tr><td align="left" >Total Pendapatan</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_konsolidasi < 0 ? "(".number_format(abs($account_pendapatan_konsolidasi)).")" : number_format($account_pendapatan_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_total[$branch] < 0 ? "(".number_format(abs($account_pendapatan_total[$branch])).")" : number_format($account_pendapatan_total[$branch])).'</td>';
				}
				$print .= '	</tr>';	
				
				//---------------------------------------------------------------------------------------------
				//---------------------------------------------------------------------------------------------
				//---------------------------------------------------------------------------------------------
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Biaya Langsung</b></td>	<td colspan="8" ></td></tr>';
				
				//Beban Dana Pinjaman Bank
				$code = "5010200";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5010200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5010200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5010200[$branch] = $account_5010200_debet[$branch] - $account_5010200_credit[$branch];
					$account_5010200_total += $account_5010200[$branch];
					$account_biaya_langsung_total[$branch] += $account_5010200[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Dana Pinjaman Bank</td>';
				$print .= '	<td align="right" class="">'.($account_5010200_total < 0 ? "(".number_format(abs($account_5010200_total)).")" : number_format($account_5010200_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5010200[$branch] < 0 ? "(".number_format(abs($account_5010200[$branch])).")" : number_format($account_5010200[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Dana Simpanan Berjangka
				$code = "5010100";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5010100_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5010100_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5010100[$branch] = $account_5010100_debet[$branch] - $account_5010100_credit[$branch];
					$account_5010100_total += $account_5010100[$branch];
					$account_biaya_langsung_total[$branch] += $account_5010100[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Dana Simpanan Berjangka</td>';
				$print .= '	<td align="right" class="">'.($account_5010100_total < 0 ? "(".number_format(abs($account_5010100_total)).")" : number_format($account_5010100_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5010100[$branch] < 0 ? "(".number_format(abs($account_5010100[$branch])).")" : number_format($account_5010100[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Bonus
				$code = "5020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5020000[$branch] = $account_5020000_debet[$branch] - $account_5020000_credit[$branch];
					$account_5020000_total += $account_5020000[$branch];
					$account_biaya_langsung_total[$branch] += $account_5020000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Bonus</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_5020000_total < 0 ? "(".number_format(abs($account_5020000_total)).")" : number_format($account_5020000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_5020000[$branch] < 0 ? "(".number_format(abs($account_5020000[$branch])).")" : number_format($account_5020000[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Total Biaya_langsung			
				$account_biaya_langsung_konsolidasi = $account_5010100_total + $account_5010200_total + $account_5020000_total;
				$print .= '	<tr><td align="left" >Total Biaya Langsung</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_biaya_langsung_konsolidasi < 0 ? "(".number_format(abs($account_biaya_langsung_konsolidasi)).")" : number_format($account_biaya_langsung_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_biaya_langsung_total[$branch] < 0 ? "(".number_format(abs($account_biaya_langsung_total[$branch])).")" : number_format($account_biaya_langsung_total[$branch])).'</td>';
				}
				$print .= '	</tr>';	
				
				$print .= '	<tr><td></td><td align="left" class="border_btm" colspan="8"> &nbsp;</td></tr>';
				
				//Laba Rugi Kotor		
				$account_labarugi_kotor_konsolidasi = $account_pendapatan_konsolidasi - $account_biaya_langsung_konsolidasi;
				$print .= '	<tr><td align="left" ><b>Laba (Rugi) Kotor</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_labarugi_kotor_konsolidasi < 0 ? "(".number_format(abs($account_labarugi_kotor_konsolidasi)).")" : number_format($account_labarugi_kotor_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_labarugi_kotor_total[$branch] = $account_pendapatan_total[$branch] - $account_biaya_langsung_total[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_labarugi_kotor_total[$branch] < 0 ? "(".number_format(abs($account_labarugi_kotor_total[$branch])).")" : number_format($account_labarugi_kotor_total[$branch])).'</b></td>';
				}
				$print .= '	</tr>';	
				
				
				//---------------------------------------------------------------------------------------------
				//BEBAN OPERASI
				//---------------------------------------------------------------------------------------------
				
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Biaya Operasi</b></td>	<td colspan="8" ></td></tr>';
				
				//Beban Lainnya: 5030101, 5030102, 5030103, 5030104, 5030105, 5030106, 5030108, 5030109, 5030110, 5030111, 5030112, 5030113, 5030114 
				
				$code = "5030101";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030101[$branch] = $account_5030101_debet[$branch] - $account_5030101_credit[$branch];
					$account_5030101_total += $account_5030101[$branch];
					$account_beban_gaji_total[$branch] += $account_5030101[$branch];
					$account_beban_operasi_total[$branch] += $account_5030101[$branch];
					
				}
				$code = "5030102";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030102[$branch] = $account_5030102_debet[$branch] - $account_5030102_credit[$branch];
					$account_5030102_total += $account_5030102[$branch];
					$account_beban_gaji_total[$branch] += $account_5030102[$branch];
					$account_beban_operasi_total[$branch] += $account_5030102[$branch];
					
				}
				$code = "5030103";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030103[$branch] = $account_5030103_debet[$branch] - $account_5030103_credit[$branch];
					$account_5030103_total += $account_5030103[$branch];
					$account_beban_gaji_total[$branch] += $account_5030103[$branch];
					$account_beban_operasi_total[$branch] += $account_5030103[$branch];
					
				}					
				$code = "5030104";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030104_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030104_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030104[$branch] = $account_5030104_debet[$branch] - $account_5030104_credit[$branch];
					$account_5030104_total += $account_5030104[$branch];
					$account_beban_gaji_total[$branch] += $account_5030104[$branch];
					$account_beban_operasi_total[$branch] += $account_5030104[$branch];
					
				}
				$code = "5030105";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030105_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030105_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030105[$branch] = $account_5030105_debet[$branch] - $account_5030105_credit[$branch];
					$account_5030105_total += $account_5030105[$branch];
					$account_beban_gaji_total[$branch] += $account_5030105[$branch];
					$account_beban_operasi_total[$branch] += $account_5030105[$branch];
					
				}
				
				$code = "5030106";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030106_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030106_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030106[$branch] = $account_5030106_debet[$branch] - $account_5030106_credit[$branch];
					$account_5030106_total += $account_5030106[$branch];
					$account_beban_gaji_total[$branch] += $account_5030106[$branch];
					$account_beban_operasi_total[$branch] += $account_5030106[$branch];
					
				}
				$code = "5030108";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030108_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030108_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030108[$branch] = $account_5030108_debet[$branch] - $account_5030108_credit[$branch];
					$account_5030108_total += $account_5030108[$branch];
					$account_beban_gaji_total[$branch] += $account_5030108[$branch];
					$account_beban_operasi_total[$branch] += $account_5030108[$branch];
					
				}
				$code = "5030109";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030109_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030109_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030109[$branch] = $account_5030109_debet[$branch] - $account_5030109_credit[$branch];
					$account_5030109_total += $account_5030109[$branch];
					$account_beban_gaji_total[$branch] += $account_5030109[$branch];
					$account_beban_operasi_total[$branch] += $account_5030109[$branch];
					
				}
				$code = "5030110";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030110_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030110_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030110[$branch] = $account_5030110_debet[$branch] - $account_5030110_credit[$branch];
					$account_5030110_total += $account_5030110[$branch];
					$account_beban_gaji_total[$branch] += $account_5030110[$branch];
					$account_beban_operasi_total[$branch] += $account_5030110[$branch];
					
				}					
				$code = "5030111";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030111_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030111_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030111[$branch] = $account_5030111_debet[$branch] - $account_5030111_credit[$branch];
					$account_5030111_total += $account_5030111[$branch];
					$account_beban_gaji_total[$branch] += $account_5030111[$branch];
					$account_beban_operasi_total[$branch] += $account_5030111[$branch];
					
				}
				$code = "5030112";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030112_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030112_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030112[$branch] = $account_5030112_debet[$branch] - $account_5030112_credit[$branch];
					$account_5030112_total += $account_5030112[$branch];
					$account_beban_gaji_total[$branch] += $account_5030112[$branch];
					$account_beban_operasi_total[$branch] += $account_5030112[$branch];
					
				}
				
				$code = "5030113";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030113_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030113_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030113[$branch] = $account_5030113_debet[$branch] - $account_5030113_credit[$branch];
					$account_5030113_total += $account_5030113[$branch];
					$account_beban_gaji_total[$branch] += $account_5030113[$branch];
					$account_beban_operasi_total[$branch] += $account_5030113[$branch];
					
				}
				
				
				$code = "5030114";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030114_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030114_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030114[$branch] = $account_5030114_debet[$branch] - $account_5030114_credit[$branch];
					$account_5030114_total += $account_5030114[$branch];
					$account_beban_gaji_total[$branch] += $account_5030114[$branch];
					$account_beban_operasi_total[$branch] += $account_5030114[$branch];
					
				}
				
				$account_beban_gaji_konsolidasi = $account_5030101_total + $account_5030102_total + $account_5030103_total + $account_5030104_total + $account_5030105_total  + $account_5030106_total + $account_5030108_total + $account_5030109_total + $account_5030110_total + $account_5030111_total + $account_5030112_total  + $account_5030113_total + $account_5030114_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Gaji & Honor</td>';
				$print .= '	<td align="right" class="">'.($account_beban_gaji_konsolidasi < 0 ? "(".number_format(abs($account_beban_gaji_konsolidasi)).")" : number_format($account_beban_gaji_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_gaji_total[$branch] < 0 ? "(".number_format(abs($account_beban_gaji_total[$branch])).")" : number_format($account_beban_gaji_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//5080404 Beban Asuransi Jiwa
				$code = "5080404";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080404_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080404_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080404[$branch] = $account_5080404_debet[$branch] - $account_5080404_credit[$branch];
					$account_5080404_total += $account_5080404[$branch];
					$account_beban_operasi_total[$branch] += $account_5080404[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Asuransi Jiwa</td>';
				$print .= '	<td align="right" class="">'.($account_5080404_total < 0 ? "(".number_format(abs($account_5080404_total)).")" : number_format($account_5080404_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5080404[$branch] < 0 ? "(".number_format(abs($account_5080404[$branch])).")" : number_format($account_5080404[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//5040003 Beban Rekrutmen
				$code = "5040003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5040003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5040003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5040003[$branch] = $account_5040003_debet[$branch] - $account_5040003_credit[$branch];
					$account_5040003_total += $account_5040003[$branch];
					$account_beban_operasi_total[$branch] += $account_5040003[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Rekrutmen</td>';
				$print .= '	<td align="right" class="">'.($account_5040003_total < 0 ? "(".number_format(abs($account_5040003_total)).")" : number_format($account_5040003_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5040003[$branch] < 0 ? "(".number_format(abs($account_5040003[$branch])).")" : number_format($account_5040003[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Training
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Training</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//5030107 Insentif Operations
				$code = "5030107";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030107_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030107_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030107[$branch] = $account_5030107_debet[$branch] - $account_5030107_credit[$branch];
					$account_5030107_total += $account_5030107[$branch];
					$account_beban_operasi_total[$branch] += $account_5030107[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Insentif Operations</td>';
				$print .= '	<td align="right" class="">'.($account_5030107_total < 0 ? "(".number_format(abs($account_5030107_total)).")" : number_format($account_5030107_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5030107[$branch] < 0 ? "(".number_format(abs($account_5030107[$branch])).")" : number_format($account_5030107[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//5080501 Beban Rumah Tangga Pusat
				$code = "5080501";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080501_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080501_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080501[$branch] = $account_5080501_debet[$branch] - $account_5080501_credit[$branch];
					$account_5080501_total += $account_5080501[$branch];
					$account_beban_RT_pusat_total[$branch] += $account_5080501[$branch];
					$account_beban_operasi_total[$branch] += $account_5080501[$branch];
					
				}
				$code = "5080301";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080301_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080301_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080301[$branch] = $account_5080301_debet[$branch] - $account_5080301_credit[$branch];
					$account_5080301_total += $account_5080301[$branch];
					$account_beban_RT_pusat_total[$branch] += $account_5080301[$branch];
					$account_beban_operasi_total[$branch] += $account_5080301[$branch];
					
				}				
				
				$account_beban_RT_pusat_konsolidasi = $account_5080501_total + $account_5080301_total;
				$account_beban_RT_cabang_konsolidasi = $account_beban_RT_pusat_konsolidasi - $account_beban_RT_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Rumah Tangga Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_RT_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_RT_pusat_total[0])).")" : number_format($account_beban_RT_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_RT_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_RT_pusat_total[0])).")" : number_format($account_beban_RT_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				
				
				//Beban ATK Pusat : 5080201, 5080202, 5080203, 5080401
				$code = "5080201";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080201_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080201_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080201[$branch] = $account_5080201_debet[$branch] - $account_5080201_credit[$branch];
					$account_5080201_total += $account_5080201[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080201[$branch];
					$account_beban_operasi_total[$branch] += $account_5080201[$branch];
					
				}
				$code = "5080202";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080202_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080202_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080202[$branch] = $account_5080202_debet[$branch] - $account_5080202_credit[$branch];
					$account_5080202_total += $account_5080202[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080202[$branch];
					$account_beban_operasi_total[$branch] += $account_5080202[$branch];
					
				}
				$code = "5080203";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080203_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080203_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080203[$branch] = $account_5080203_debet[$branch] - $account_5080203_credit[$branch];
					$account_5080203_total += $account_5080203[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080203[$branch];
					$account_beban_operasi_total[$branch] += $account_5080203[$branch];
					
				}
				$code = "5080401";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080401_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080401_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080401[$branch] = $account_5080401_debet[$branch] - $account_5080401_credit[$branch];
					$account_5080401_total += $account_5080401[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080401[$branch];
					$account_beban_operasi_total[$branch] += $account_5080401[$branch];
					
				}				
				
				$account_beban_ATK_pusat_konsolidasi = $account_5080201_total + $account_5080202_total + $account_5080203_total + $account_5080401_total;
				$account_beban_ATK_cabang_konsolidasi = $account_beban_ATK_pusat_konsolidasi - $account_beban_ATK_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban ATK Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_ATK_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_ATK_pusat_total[0])).")" : number_format($account_beban_ATK_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_ATK_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_ATK_pusat_total[0])).")" : number_format($account_beban_ATK_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				
				//Beban Transportasi Pusat : 5080302, 5080303, 5080304

				$code = "5080302";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080302_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080302_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080302[$branch] = $account_5080302_debet[$branch] - $account_5080302_credit[$branch];
					$account_5080302_total += $account_5080302[$branch];
					$account_beban_transportasi_pusat_total[$branch] += $account_5080302[$branch];
					$account_beban_operasi_total[$branch] += $account_5080302[$branch];
					
				}
				$code = "5080303";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080303_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080303_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080303[$branch] = $account_5080303_debet[$branch] - $account_5080303_credit[$branch];
					$account_5080303_total += $account_5080303[$branch];
					$account_beban_transportasi_pusat_total[$branch] += $account_5080303[$branch];
					$account_beban_operasi_total[$branch] += $account_5080303[$branch];
					
				}
				$code = "5080304";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080304_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080304_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080304[$branch] = $account_5080304_debet[$branch] - $account_5080304_credit[$branch];
					$account_5080304_total += $account_5080304[$branch];
					$account_beban_transportasi_pusat_total[$branch] += $account_5080304[$branch];
					$account_beban_operasi_total[$branch] += $account_5080304[$branch];
					
				}					
				
				$account_beban_transportasi_pusat_konsolidasi = $account_5080302_total + $account_5080303_total + $account_5080304_total ;
				$account_beban_transportasi_cabang_konsolidasi = $account_beban_transportasi_pusat_konsolidasi - $account_beban_transportasi_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Transportasi Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_transportasi_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_transportasi_pusat_total[0])).")" : number_format($account_beban_transportasi_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_transportasi_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_transportasi_pusat_total[0])).")" : number_format($account_beban_transportasi_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				
				//Beban Perawatan Pusat
				$code = "5060000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5060000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5060000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5060000[$branch] = $account_5060000_debet[$branch] - $account_5060000_credit[$branch];
					$account_5060000_total += $account_5060000[$branch];
					$account_beban_operasi_total[$branch] += $account_5060000[$branch];	
					
				}
				$account_5060000_cabang = $account_5060000_total - $account_5060000[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Perawatan Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_5060000[0]  < 0 ? "(".number_format(abs($account_5060000[0] )).")" : number_format($account_5060000[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_5060000[0]  < 0 ? "(".number_format(abs($account_5060000[0] )).")" : number_format($account_5060000[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				//Beban Listrik, Telp, Air : 5080101, 5080102, 5080103

				$code = "5080101";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080101[$branch] = $account_5080101_debet[$branch] - $account_5080101_credit[$branch];
					$account_5080101_total += $account_5080101[$branch];
					$account_beban_listrik_air_telp_pusat_total[$branch] += $account_5080101[$branch];
					$account_beban_operasi_total[$branch] += $account_5080101[$branch];
					
				}
				$code = "5080102";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080102[$branch] = $account_5080102_debet[$branch] - $account_5080102_credit[$branch];
					$account_5080102_total += $account_5080102[$branch];
					$account_beban_listrik_air_telp_pusat_total[$branch] += $account_5080102[$branch];
					$account_beban_operasi_total[$branch] += $account_5080102[$branch];
					
				}
				$code = "5080103";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080103[$branch] = $account_5080103_debet[$branch] - $account_5080103_credit[$branch];
					$account_5080103_total += $account_5080103[$branch];
					$account_beban_listrik_air_telp_pusat_total[$branch] += $account_5080103[$branch];
					$account_beban_operasi_total[$branch] += $account_5080103[$branch];
					
				}					
				
				$account_beban_listrik_air_telp_pusat_konsolidasi = $account_5080101_total + $account_5080102_total + $account_5080103_total ;
				$account_beban_listrik_air_telp_cabang_konsolidasi = $account_beban_listrik_air_telp_pusat_konsolidasi - $account_beban_listrik_air_telp_pusat_total[0] ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Listrik, Air, Telepon dan Internet Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_pusat_total[0])).")" : number_format($account_beban_listrik_air_telp_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_pusat_total[0])).")" : number_format($account_beban_listrik_air_telp_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				//Beban Lain-lain Pusat : 5080104, 5080402, 5080403, 5080406, 5080502, 5080503, 5080504
				

				/*
				$code = "5080104";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080104_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080104_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080104[$branch] = $account_5080104_debet[$branch] - $account_5080104_credit[$branch];
					$account_5080104_total += $account_5080104[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080104[$branch];
					$account_beban_operasi_total[$branch] += $account_5080104[$branch];
					
				}
				$code = "5080402";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080402_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080402_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080402[$branch] = $account_5080402_debet[$branch] - $account_5080402_credit[$branch];
					$account_5080402_total += $account_5080402[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080402[$branch];
					$account_beban_operasi_total[$branch] += $account_5080402[$branch];
					
				}
				$code = "5080403";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080403_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080403_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080403[$branch] = $account_5080403_debet[$branch] - $account_5080403_credit[$branch];
					$account_5080403_total += $account_5080403[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080403[$branch];
					$account_beban_operasi_total[$branch] += $account_5080403[$branch];
					
				}					
				$code = "5080406";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080406_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080406_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080406[$branch] = $account_5080406_debet[$branch] - $account_5080406_credit[$branch];
					$account_5080406_total += $account_5080406[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080406[$branch];
					$account_beban_operasi_total[$branch] += $account_5080406[$branch];
					
				}
				$code = "5080502";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080502_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080502_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080502[$branch] = $account_5080502_debet[$branch] - $account_5080502_credit[$branch];
					$account_5080502_total += $account_5080502[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080502[$branch];
					$account_beban_operasi_total[$branch] += $account_5080502[$branch];
					
				}
				$code = "5080503";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080503_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080503_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080503[$branch] = $account_5080503_debet[$branch] - $account_5080503_credit[$branch];
					$account_5080503_total += $account_5080503[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080503[$branch];
					$account_beban_operasi_total[$branch] += $account_5080503[$branch];
					
				}
				*/
				
				
				$branch = 0;
				$code = "5080402";
					$account_5080402_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080402_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080402[$branch] = $account_5080402_debet[$branch] - $account_5080402_credit[$branch];
					$account_5080402_total += $account_5080402[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080402[$branch];
					$account_beban_operasi_total[$branch] += $account_5080402[$branch];
				$code = "5080503";
					$account_5080503_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080503_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080503[$branch] = $account_5080503_debet[$branch] - $account_5080503_credit[$branch];
					$account_5080503_total += $account_5080503[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080503[$branch];
					$account_beban_operasi_total[$branch] += $account_5080503[$branch];
				$code = "5090001";
					$account_5090001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090001[$branch] = $account_5090001_debet[$branch] - $account_5090001_credit[$branch];
					$account_5090001_total += $account_5090001[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5090001[$branch];
					$account_beban_operasi_total[$branch] += $account_5090001[$branch];
				$code = "5090005";
					$account_5090005_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090005_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090005[$branch] = $account_5090005_debet[$branch] - $account_5090005_credit[$branch];
					$account_5090005_total += $account_5090005[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5090005[$branch];
					$account_beban_operasi_total[$branch] += $account_5090005[$branch];
				
				$code = "5080504";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080504_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080504_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080504[$branch] = $account_5080504_debet[$branch] - $account_5080504_credit[$branch];
					$account_5080504_total += $account_5080504[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080504[$branch];
					$account_beban_operasi_total[$branch] += $account_5080504[$branch];
					
				}
				$account_beban_lainlain_pusat_konsolidasi = $account_5080402_total + $account_5080503_total + $account_5090001_total + $account_5090005_total + $account_5080504_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Lain-lain Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_lainlain_pusat_konsolidasi < 0 ? "(".number_format(abs($account_beban_lainlain_pusat_konsolidasi)).")" : number_format($account_beban_lainlain_pusat_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_lainlain_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_lainlain_pusat_total[$branch])).")" : number_format($account_beban_lainlain_pusat_total[$branch])).'</td>';
				
				}
				$print .= '</tr>';
				
				//Beban Rumah Tangga Seluruh Cabang
				$account_beban_RT_cabang_konsolidasi = $account_beban_RT_pusat_konsolidasi - $account_beban_RT_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Rumah Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_RT_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_RT_cabang_konsolidasi)).")"  : number_format($account_beban_RT_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=1; $total_branch <= 0; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_RT_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_RT_pusat_total[$branch])).")" : number_format($account_beban_RT_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban ATK Seluruh Cabang
				$account_beban_ATK_cabang_konsolidasi = $account_beban_ATK_pusat_konsolidasi - $account_beban_ATK_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban ATK Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_ATK_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_ATK_cabang_konsolidasi)).")" : number_format($account_beban_ATK_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_ATK_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_ATK_pusat_total[$branch])).")" : number_format($account_beban_ATK_pusat_total[$branch])).'</td>';
				}
				
				
				//Beban Transportasi Seluruh Cabang
				$account_beban_transportasi_cabang_konsolidasi = $account_beban_transportasi_pusat_konsolidasi - $account_beban_transportasi_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Transportasi Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_transportasi_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_transportasi_cabang_konsolidasi)).")"  : number_format($account_beban_transportasi_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_transportasi_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_transportasi_pusat_total[$branch])).")" : number_format($account_beban_transportasi_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Perawatan Seluruh Cabang
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Perawatan Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_5060000_cabang < 0 ? "(".number_format(abs($account_5060000_cabang)).")" : number_format($account_5060000_cabang)).'</td>';
				$print .= '	<td align="right" class="">0</td>';	
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5060000[$branch] < 0 ? "(".number_format(abs($account_5060000[$branch])).")" : number_format($account_5060000[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Listrik, Air, Telepon dan Internet Seluruh Cabang
				$account_beban_listrik_air_telp_cabang_konsolidasi = $account_beban_listrik_air_telp_pusat_konsolidasi - $account_beban_listrik_air_telp_pusat_total[0] ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Listrik, Air, Telepon dan Internet Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_cabang_konsolidasi)).")" : number_format($account_beban_listrik_air_telp_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';	
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_pusat_total[$branch])).")" : number_format($account_beban_listrik_air_telp_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				/*//Beban Lain-lain Seluruh Cabang
				$account_beban_lainlain_cabang_konsolidasi = $account_beban_lainlain_pusat_konsolidasi - $account_beban_lainlain_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Lain-lain Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_lainlain_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_lainlain_cabang_konsolidasi)).")" : number_format($account_beban_lainlain_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';	
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_lainlain_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_lainlain_pusat_total[$branch])).")" : number_format($account_beban_lainlain_pusat_total[$branch])).'</td>';
				}
				*/
				
				$print .= '	</tr>';	
				//Beban Cleaning Service
				$code = "5080406";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080406_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080406_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080406[$branch] = $account_5080406_debet[$branch] - $account_5080406_credit[$branch];
					$account_5080406_total += $account_5080406[$branch];
					$account_beban_operasi_total[$branch] += $account_5080406[$branch];					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Cleaning Service</td>';
				$print .= '	<td align="right" class="">'.($account_5080406_total < 0 ? "(".number_format(abs($account_5080406_total)).")" : number_format($account_5080406_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5080406[$branch] < 0 ? "(".number_format(abs($account_5080406[$branch])).")" : number_format($account_5080406[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Keamanan dan Kebersihan
				$code = "5080405";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080405_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080405_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080405[$branch] = $account_5080405_debet[$branch] - $account_5080405_credit[$branch];
					$account_5080405_total += $account_5080405[$branch];
					$account_beban_operasi_total[$branch] += $account_5080405[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Keamanan dan Kebersihan</td>';
				$print .= '	<td align="right" class="">'.($account_5080405_total < 0 ? "(".number_format(abs($account_5080405_total)).")" : number_format($account_5080405_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5080405[$branch] < 0 ? "(".number_format(abs($account_5080405[$branch])).")" : number_format($account_5080405[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				
				
				//Beban Penyusutan
				$code = "5070000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5070000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5070000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5070000[$branch] = $account_5070000_debet[$branch] - $account_5070000_credit[$branch];
					$account_5070000_total += $account_5070000[$branch];
					$account_beban_operasi_total[$branch] += $account_5070000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Penyusutan</td>';
				$print .= '	<td align="right" class="">'.($account_5070000_total < 0 ? "(".number_format(abs($account_5070000_total)).")" : number_format($account_5070000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5070000[$branch] < 0 ? "(".number_format(abs($account_5070000[$branch])).")" : number_format($account_5070000[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Sewa Kantor Cabang : 5040001, 5040002
				
				$code = "5040001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5040001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5040001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5040001[$branch] = $account_5040001_debet[$branch] - $account_5040001_credit[$branch];
					$account_5040001_total += $account_5040001[$branch];
					$account_beban_sewa_kantor_cabang_total[$branch] += $account_5040001[$branch];
					$account_beban_operasi_total[$branch] += $account_5040001[$branch];
					
				}
				$code = "5040002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5040002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5040002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5040002[$branch] = $account_5040002_debet[$branch] - $account_5040002_credit[$branch];
					$account_5040002_total += $account_5040002[$branch];
					$account_beban_sewa_kantor_cabang_total[$branch] += $account_5040002[$branch];
					$account_beban_operasi_total[$branch] += $account_5040002[$branch];
					
				}				
				$account_beban_sewa_kantor_cabang_konsolidasi = $account_5040001_total + $account_5040002_total ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Sewa Kantor Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_sewa_kantor_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_sewa_kantor_cabang_konsolidasi)).")" : number_format($account_beban_sewa_kantor_cabang_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_sewa_kantor_cabang_total[$branch] < 0 ? "(".number_format(abs($account_beban_sewa_kantor_cabang_total[$branch])).")" : number_format($account_beban_sewa_kantor_cabang_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban MIS
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban MIS</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//Total Biaya Operasi	
				for($branch=0; $branch <= $total_branch; $branch++){				
					$account_beban_operasi_konsolidasi += $account_beban_operasi_total[$branch];
				}
				$print .= '	<tr><td align="left" >Total Biaya Operasi</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_beban_operasi_konsolidasi < 0 ? "(".number_format(abs($account_beban_operasi_konsolidasi)).")" : number_format($account_beban_operasi_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_beban_operasi_total[$branch] < 0 ? "(".number_format(abs($account_beban_operasi_total[$branch])).")" : number_format($account_beban_operasi_total[$branch])).'</td>';
				}
				$print .= '	</tr>';	
				
				$print .= '	<tr><td></td><td align="left" class="border_btm" colspan="8"> &nbsp;</td></tr>';
				
				//Laba (Rugi) Operasi
				$account_LR_operasi_konsolidasi = $account_labarugi_kotor_konsolidasi - $account_beban_operasi_konsolidasi;
				$print .= '	<tr><td align="left" ><b>Laba (Rugi) Operasi</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_operasi_konsolidasi < 0 ? "(".number_format(abs($account_LR_operasi_konsolidasi)).")" : number_format($account_LR_operasi_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_LR_operasi[$branch] = $account_labarugi_kotor_total[$branch] - $account_beban_operasi_total[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_operasi[$branch] < 0 ? "(".number_format(abs($account_LR_operasi[$branch])).")" : number_format($account_LR_operasi[$branch])).'</b></td>';
				}
				$print .= '	</tr>';	
			
				//---------------------------------------------------------------------------------------------
				//Pendapatan Diluar Usaha
				//---------------------------------------------------------------------------------------------
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Pendapatan & Beban Diluar Usaha</b></td>	<td colspan="8" ></td></tr>';
				$print .= '	<tr><td align="left" ><b>Pendapatan Diluar Usaha</b></td>	<td colspan="8" ></td></tr>';
				
				//Pendapatan Bunga Bank
				$code = "4030005";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030005_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030005_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030005[$branch] = $account_4030005_credit[$branch] - $account_4030005_debet[$branch];
					$account_4030005_total += $account_4030005[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030005[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Bunga Bank</td>';
				$print .= '	<td align="right" class="">'.($account_4030005_total < 0 ? "(".number_format(abs($account_4030005_total)).")" : number_format($account_4030005_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_4030005[$branch] < 0 ? "(".number_format(abs($account_4030005[$branch])).")" : number_format($account_4030005[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Pendapatan Lainnya : 4030001, 4030002, 4030003, 4030004, 4030006 
				
				$code = "4030001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030001[$branch] = $account_4030001_credit[$branch] - $account_4030001_debet[$branch];
					$account_4030001_total += $account_4030001[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030001[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030001[$branch];
					
				}
				$code = "4030002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030002[$branch] = $account_4030002_credit[$branch] - $account_4030002_debet[$branch];
					$account_4030002_total += $account_4030002[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030002[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030002[$branch];
					
				}
				$code = "4030003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030003[$branch] = $account_4030003_credit[$branch] - $account_4030003_debet[$branch];
					$account_4030003_total += $account_4030003[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030003[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030003[$branch];
					
				}					
				$code = "4030004";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030004_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030004_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030004[$branch] = $account_4030004_credit[$branch] - $account_4030004_debet[$branch];
					$account_4030004_total += $account_4030004[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030004[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030004[$branch];
					
				}
				$code = "4030006";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030006_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030006_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030006[$branch] = $account_4030006_credit[$branch] - $account_4030006_debet[$branch];
					$account_4030006_total += $account_4030006[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030006[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030006[$branch];
					
				}
				
				$account_pendapatan_lain_konsolidasi = $account_4030001_total + $account_4030002_total + $account_4030003_total + $account_4030004_total + $account_4030006_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Lainnya</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_lain_konsolidasi < 0 ? "(".number_format(abs($account_pendapatan_lain_konsolidasi)).")" : number_format($account_pendapatan_lain_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_lain_pusat_total[$branch] < 0 ? "(".number_format(abs($account_pendapatan_lain_pusat_total[$branch])).")" : number_format($account_pendapatan_lain_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Total Pendapatan Diluar Usaha
				$account_pendapatan_diluar_usaha_konsolidasi = $account_4030005_total + $account_pendapatan_lain_konsolidasi;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Pendapatan Diluar Usaha</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_diluar_usaha_konsolidasi < 0 ? "(".number_format(abs($account_pendapatan_diluar_usaha_konsolidasi)).")" : number_format($account_pendapatan_diluar_usaha_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_diluar_usaha_total[$branch] < 0 ? "(".number_format(abs($account_pendapatan_diluar_usaha_total[$branch])).")" : number_format($account_pendapatan_diluar_usaha_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//---------------------------------------------------------------------------------------------
				//Beban Diluar Usaha
				//---------------------------------------------------------------------------------------------
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Beban Diluar Usaha</b></td>	<td colspan="8" ></td></tr>';
				
				//Beban Bunga Bank
				$code = "5090004";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090004_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090004_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090004[$branch] = $account_5090004_debet[$branch] - $account_5090004_credit[$branch];
					$account_5090004_total += $account_5090004[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090004[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Bunga Bank</td>';
				$print .= '	<td align="right" class="">'.($account_5090004_total < 0 ? "(".number_format(abs($account_5090004_total)).")" : number_format($account_5090004_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5090004[$branch] < 0 ? "(".number_format(abs($account_5090004[$branch])).")" : number_format($account_5090004[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Merchant Discount Rate
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Merchant Discount Rate</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Kerugian Penghapusan Aktiva Tetap
				$code = "5090003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090003[$branch] = $account_5090003_debet[$branch] - $account_5090003_credit[$branch];
					$account_5090003_total += $account_5090003[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090003[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Kerugian Penghapusan Aktiva Tetap</td>';
				$print .= '	<td align="right" class="">'.($account_5090003_total < 0 ? "(".number_format(abs($account_5090003_total)).")" : number_format($account_5090003_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5090003[$branch] < 0 ? "(".number_format(abs($account_5090003[$branch])).")" : number_format($account_5090003[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Selisih kurs/Penerimaan /Pembayaran
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selisih kurs/Penerimaan /Pembayaran</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban PPh Pasal 21 Karyawan
				$code = "5050003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5050003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5050003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5050003[$branch] = $account_5050003_debet[$branch] - $account_5050003_credit[$branch];
					$account_5050003_total += $account_5050003[$branch];
					$account_pph_21_25[$branch] = $account_5050003[$branch];
					$account_pph_21_25_total += $account_5050003[$branch];
					$account_beban_lain_total[$branch] += $account_5050003[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5050003[$branch];					
				}
				//Beban PPh Pasal 25
				$code = "5050004";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5050004_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5050004_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5050004[$branch] += $account_5050004_debet[$branch] - $account_5050004_credit[$branch];
					$account_5050004_total += $account_5050004[$branch];
					$account_pph_21_25[$branch] += $account_5050004[$branch];
					$account_pph_21_25_total += $account_5050004[$branch];
					$account_beban_lain_total[$branch] += $account_5050004[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5050004[$branch];					
				}					
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban PPh Pasal 21 dan 25</td>';
				$print .= '	<td align="right" class="">'.($account_pph_21_25_total < 0 ? "(".number_format(abs($account_pph_21_25_total)).")" : number_format($account_pph_21_25_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_pph_21_25[$branch] < 0 ? "(".number_format(abs($account_pph_21_25[$branch])).")" : number_format($account_pph_21_25[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban PPh Pasal 4 Ayat (2) atas Sewa Ruangan
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban PPh Pasal 4 Ayat (2) atas Sewa Ruangan</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban PPh Pasal 4 Ayat (2) atas Bunga Bank
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban PPh Pasal 4 Ayat (2) atas Bunga Bank</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Lainnya: 5050001, 5050002, 5050005, 5090001, 5090002, 5090005
				/*
				$code = "5050001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5050001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5050001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5050001[$branch] = $account_5050001_debet[$branch] - $account_5050001_credit[$branch];
					$account_5050001_total += $account_5050001[$branch];
					$account_beban_lain_total[$branch] += $account_5050001[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5050001[$branch];
					
				}
				
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Pajak Motor</td>';
				$print .= '	<td align="right" class="">'.($account_5050002_total < 0 ? "(".number_format(abs($account_5050002_total)).")" : number_format($account_5050002_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5050002[$branch] < 0 ? "(".number_format(abs($account_5050002[$branch])).")" : number_format($account_5050002[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				$code = "5050004";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5050004_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5050004_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5050004[$branch] = $account_5050004_debet[$branch] - $account_5050004_credit[$branch];
					$account_5050004_total += $account_5050004[$branch];
					$account_beban_lain_total[$branch] += $account_5050004[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5050004[$branch];
					
				}
				$code = "5050005";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5050005_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5050005_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5050005[$branch] = $account_5050005_debet[$branch] - $account_5050005_credit[$branch];
					$account_5050005_total += $account_5050005[$branch];
					$account_beban_lain_total[$branch] += $account_5050005[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5050005[$branch];
					
				}					
				
				$code = "5090001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090001[$branch] = $account_5090001_debet[$branch] - $account_5090001_credit[$branch];
					$account_5090001_total += $account_5090001[$branch];
					$account_beban_lain_total[$branch] += $account_5090001[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090001[$branch];
					
				}
				$code = "5090002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090002[$branch] = $account_5090002_debet[$branch] - $account_5090002_credit[$branch];
					$account_5090002_total += $account_5090002[$branch];
					$account_beban_lain_total[$branch] += $account_5090002[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090002[$branch];
					
				}
				
				$code = "5090005";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090005_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090005_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090005[$branch] = $account_5090005_debet[$branch] - $account_5090005_credit[$branch];
					$account_5090005_total += $account_5090005[$branch];
					$account_beban_lain_total[$branch] += $account_5090005[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090005[$branch];
					
				}
				*/
				
				$code = "5050002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5050002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5050002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5050002[$branch] = $account_5050002_debet[$branch] - $account_5050002_credit[$branch];
					$account_5050002_total += $account_5050002[$branch];
					$account_beban_lain_total[$branch] += $account_5050002[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5050002[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Pajak Kendaraan</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_5050002_total < 0 ? "(".number_format(abs($account_5050002_total)).")" : number_format($account_5050002_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_5050002[$branch] < 0 ? "(".number_format(abs($account_5050002[$branch])).")" : number_format($account_5050002[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Total Beban Diluar Usaha
				$account_beban_lain_konsolidasi = $account_5050002_total + $account_5050003_total + $account_5050004_total + $account_5090004_total  + $account_5090003_total  ;
				
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_beban_diluar_usaha_konsolidasi += $account_beban_diluar_usaha_total[$branch];
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Beban Diluar Usaha</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_beban_diluar_usaha_konsolidasi < 0 ? "(".number_format(abs($account_beban_diluar_usaha_konsolidasi)).")" : number_format($account_beban_diluar_usaha_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_beban_diluar_usaha_total[$branch] < 0 ? "(".number_format(abs($account_beban_diluar_usaha_total[$branch])).")" : number_format($account_beban_diluar_usaha_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				
				$print .= '	<tr><td></td><td align="left" colspan="8" class="border_btm"> &nbsp;</td></tr>';
				
				
				//---------------------------------------------------------------------------------------------
				//Laba (Rugi) TOTAL
				//---------------------------------------------------------------------------------------------
				
				
				//Laba (Rugi) Sebelum Pajak
				$account_LR_sebelum_pajak_konsolidasi = $account_LR_operasi_konsolidasi + $account_pendapatan_diluar_usaha_konsolidasi- $account_beban_diluar_usaha_konsolidasi;
				$print .= '	<tr><td align="left" ><b>Laba (Rugi) Sebelum Pajak</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_sebelum_pajak_konsolidasi < 0 ? "(".number_format(abs($account_LR_sebelum_pajak_konsolidasi)).")" : number_format($account_LR_sebelum_pajak_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_LR_sebelum_pajak[$branch] = $account_LR_operasi[$branch] + $account_pendapatan_diluar_usaha_total[$branch]- $account_beban_diluar_usaha_total[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_sebelum_pajak[$branch] < 0 ? "(".number_format(abs($account_LR_sebelum_pajak[$branch])).")" : number_format($account_LR_sebelum_pajak[$branch])).'</b></td>';
				}
				$print .= '</tr>';
				
				
				$print .= '	<tr><td></td><td align="left" colspan="8" class="border_btm"></td></tr>';
				
				//Pajak Penghasilan Badan
				$code = "5050004";
				for($branch=0; $branch <= $total_branch; $branch++){
					//$account_5050004_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					//$account_5050004_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					//$account_5050004[$branch] = $account_5050004_debet[$branch] - $account_5050004_credit[$branch];
					$account_5050004[$branch] = 0;
					//$account_5050004_total += $account_5050004[$branch];
					$account_5050004_total =0;
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pajak Penghasilan Badan</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_5050004_total < 0 ? "(".number_format(abs($account_5050004_total)).")" : number_format($account_5050004_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_5050004[$branch] < 0 ? "(".number_format(abs($account_5050004[$branch])).")" : number_format($account_5050004[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				
				$print .= '	<tr><td></td><td align="left" colspan="8" class="border_btm"></td></tr>';
				
				//Laba (Rugi) Bersih
				$account_LR_bersih_konsolidasi = $account_LR_sebelum_pajak_konsolidasi - $account_5050004_total;
				
				$print .= '	<tr><td align="left" ><b>Laba (Rugi) Bersih</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_bersih_konsolidasi < 0 ? "(".number_format(abs($account_LR_bersih_konsolidasi)).")" : number_format($account_LR_bersih_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_LR_bersih_total[$branch] = $account_LR_sebelum_pajak[$branch] - $account_5050004[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_bersih_total[$branch] < 0 ? "(".number_format(abs($account_LR_bersih_total[$branch])).")" : number_format($account_LR_bersih_total[$branch])).'</b></td>';
				}
				$print .= '</tr>';
				
				
			$this->template	->set('menu_title', 'Laporan Keuangan - Laba Rugi')
							->set('menu_report', 'active')
							->set('print', $print)
							->build('accounting/labarugi_konsolidasi');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	
	//LABA RUGI
	public function laba_rugi_download()
	{
		if($this->session->userdata('logged_in'))
		{
			
			$total_branch = $this->branch_model->count_branch();

			$timestamp=date("Ymdhis");
			$tgl=date("d-M-Y");
			$filename="LAPORAN_LABA_RUGI_$timestamp";	
			$print = "<style> table tr td,table thead tr td, table tr th{ border-left:0; border-right:0; font-size:10px;} table thead tr td,table thead tr th,table tr th{ border-bottom: 0.5px solid #666; }</style>";
			$print .= '';
			//$print .= '<h1 align="center">Amartha Microfinance</h1>';
			//$print .= '<hr/>';
			$print .= '<h2 align="center">LAPORAN LABA RUGI</h2><br/>';
			$print .= '<table border="1" width="100%">';
			$print .= '<tr>
						<th width="350px"></th>
						<th class="text-center">Konsolidasi</th>
						<th class="text-center">Pusat</th>
						<th class="text-center">Ciseeng</th>
						<th class="text-center">Jasinga</th>
						<th class="text-center">Bojong Gede</th>
						<th class="text-center">Kemang</th>
						<th class="text-center">Tenjo</th>
						<th class="text-center">Cangkuang</th>
					  </tr>'; 
			
			
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
				//$date_start =$week_today[0];
				$date_start = "2015-01-01";
				$date_end   = date("Y-m-d");			
			}
			
			$date_end_before = strtotime($date_start);
			//$date_end_before = $date_start;
			$date_end_before = strtotime("-1 day", $date_end_before); 
			$date_end_before = date('Y-m-d', $date_end_before);			
			$date_start_before = "2014-01-01";
			//echo $date_start_before." ----- ".$date_end_before; 
			
				
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				$saldo_awal =0;
				
			//PENDAPATAN
			$print .= '	<tr><td align="left" ><b>Pendapatan</b></td>	<td colspan="8" ></td></tr>';
			
								
				//4010000 Pendapatan Pembiayaan
				$code = "4010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4010000[$branch] = $account_4010000_credit[$branch] - $account_4010000_debet[$branch];
					$account_4010000_total += $account_4010000[$branch];
					$account_pendapatan_total[$branch] += $account_4010000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Pembiayaan</td>';
				$print .= '	<td align="right" class="">'.($account_4010000_total < 0 ? "(".number_format(abs($account_4010000_total)).")" : number_format($account_4010000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_4010000[$branch] < 0 ? "(".number_format(abs($account_4010000[$branch])).")" : number_format($account_4010000[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
				
				//4020000 Pendapatan Jasa Administrasi
				$code = "4020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4020000[$branch] = $account_4020000_credit[$branch] - $account_4020000_debet[$branch];
					$account_4020000_total += $account_4020000[$branch];
					$account_pendapatan_total[$branch] += $account_4020000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Administrasi</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_4020000_total < 0 ? "(".number_format(abs($account_4020000_total)).")" : number_format($account_4020000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_4020000[$branch] < 0 ? "(".number_format(abs($account_4020000[$branch])).")" : number_format($account_4020000[$branch])).'</td>';
				}
				$print .= '</tr>';	
							
				//Jumlah Pendapatan			
				$account_pendapatan_konsolidasi = $account_4010000_total + $account_4020000_total;
				$print .= '	<tr><td align="left" >Total Pendapatan</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_konsolidasi < 0 ? "(".number_format(abs($account_pendapatan_konsolidasi)).")" : number_format($account_pendapatan_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_total[$branch] < 0 ? "(".number_format(abs($account_pendapatan_total[$branch])).")" : number_format($account_pendapatan_total[$branch])).'</td>';
				}
				$print .= '	</tr>';	
				
				//---------------------------------------------------------------------------------------------
				//---------------------------------------------------------------------------------------------
				//---------------------------------------------------------------------------------------------
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Biaya Langsung</b></td>	<td colspan="8" ></td></tr>';
				
				//Beban Dana Pinjaman Bank
				$code = "5010200";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5010200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5010200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5010200[$branch] = $account_5010200_debet[$branch] - $account_5010200_credit[$branch];
					$account_5010200_total += $account_5010200[$branch];
					$account_biaya_langsung_total[$branch] += $account_5010200[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Dana Pinjaman Bank</td>';
				$print .= '	<td align="right" class="">'.($account_5010200_total < 0 ? "(".number_format(abs($account_5010200_total)).")" : number_format($account_5010200_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5010200[$branch] < 0 ? "(".number_format(abs($account_5010200[$branch])).")" : number_format($account_5010200[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Dana Simpanan Berjangka
				$code = "5010100";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5010100_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5010100_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5010100[$branch] = $account_5010100_debet[$branch] - $account_5010100_credit[$branch];
					$account_5010100_total += $account_5010100[$branch];
					$account_biaya_langsung_total[$branch] += $account_5010100[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Dana Simpanan Berjangka</td>';
				$print .= '	<td align="right" class="">'.($account_5010100_total < 0 ? "(".number_format(abs($account_5010100_total)).")" : number_format($account_5010100_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5010100[$branch] < 0 ? "(".number_format(abs($account_5010100[$branch])).")" : number_format($account_5010100[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Bonus
				$code = "5020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5020000[$branch] = $account_5020000_debet[$branch] - $account_5020000_credit[$branch];
					$account_5020000_total += $account_5020000[$branch];
					$account_biaya_langsung_total[$branch] += $account_5020000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Bonus</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_5020000_total < 0 ? "(".number_format(abs($account_5020000_total)).")" : number_format($account_5020000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_5020000[$branch] < 0 ? "(".number_format(abs($account_5020000[$branch])).")" : number_format($account_5020000[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Total Biaya_langsung			
				$account_biaya_langsung_konsolidasi = $account_5010100_total + $account_5010200_total + $account_5020000_total;
				$print .= '	<tr><td align="left" >Total Biaya Langsung</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_biaya_langsung_konsolidasi < 0 ? "(".number_format(abs($account_biaya_langsung_konsolidasi)).")" : number_format($account_biaya_langsung_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_biaya_langsung_total[$branch] < 0 ? "(".number_format(abs($account_biaya_langsung_total[$branch])).")" : number_format($account_biaya_langsung_total[$branch])).'</td>';
				}
				$print .= '	</tr>';	
				
				$print .= '	<tr><td></td><td align="left" class="border_btm" colspan="8"> &nbsp;</td></tr>';
				
				//Laba Rugi Kotor		
				$account_labarugi_kotor_konsolidasi = $account_pendapatan_konsolidasi - $account_biaya_langsung_konsolidasi;
				$print .= '	<tr><td align="left" ><b>Laba (Rugi) Kotor</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_labarugi_kotor_konsolidasi < 0 ? "(".number_format(abs($account_labarugi_kotor_konsolidasi)).")" : number_format($account_labarugi_kotor_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_labarugi_kotor_total[$branch] = $account_pendapatan_total[$branch] - $account_biaya_langsung_total[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_labarugi_kotor_total[$branch] < 0 ? "(".number_format(abs($account_labarugi_kotor_total[$branch])).")" : number_format($account_labarugi_kotor_total[$branch])).'</b></td>';
				}
				$print .= '	</tr>';	
				
				//---------------------------------------------------------------------------------------------
				//BEBAN OPERASI
				//---------------------------------------------------------------------------------------------
				
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Biaya Operasi</b></td>	<td colspan="8" ></td></tr>';
				
				//Beban Lainnya: 5030101, 5030102, 5030103, 5030104, 5030105, 5030106, 5030108, 5030109, 5030110, 5030111, 5030112, 5030113, 5030114 
				
				$code = "5030101";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030101[$branch] = $account_5030101_debet[$branch] - $account_5030101_credit[$branch];
					$account_5030101_total += $account_5030101[$branch];
					$account_beban_gaji_total[$branch] += $account_5030101[$branch];
					$account_beban_operasi_total[$branch] += $account_5030101[$branch];
					
				}
				$code = "5030102";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030102[$branch] = $account_5030102_debet[$branch] - $account_5030102_credit[$branch];
					$account_5030102_total += $account_5030102[$branch];
					$account_beban_gaji_total[$branch] += $account_5030102[$branch];
					$account_beban_operasi_total[$branch] += $account_5030102[$branch];
					
				}
				$code = "5030103";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030103[$branch] = $account_5030103_debet[$branch] - $account_5030103_credit[$branch];
					$account_5030103_total += $account_5030103[$branch];
					$account_beban_gaji_total[$branch] += $account_5030103[$branch];
					$account_beban_operasi_total[$branch] += $account_5030103[$branch];
					
				}					
				$code = "5030104";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030104_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030104_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030104[$branch] = $account_5030104_debet[$branch] - $account_5030104_credit[$branch];
					$account_5030104_total += $account_5030104[$branch];
					$account_beban_gaji_total[$branch] += $account_5030104[$branch];
					$account_beban_operasi_total[$branch] += $account_5030104[$branch];
					
				}
				$code = "5030105";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030105_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030105_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030105[$branch] = $account_5030105_debet[$branch] - $account_5030105_credit[$branch];
					$account_5030105_total += $account_5030105[$branch];
					$account_beban_gaji_total[$branch] += $account_5030105[$branch];
					$account_beban_operasi_total[$branch] += $account_5030105[$branch];
					
				}
				
				$code = "5030106";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030106_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030106_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030106[$branch] = $account_5030106_debet[$branch] - $account_5030106_credit[$branch];
					$account_5030106_total += $account_5030106[$branch];
					$account_beban_gaji_total[$branch] += $account_5030106[$branch];
					$account_beban_operasi_total[$branch] += $account_5030106[$branch];
					
				}
				$code = "5030108";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030108_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030108_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030108[$branch] = $account_5030108_debet[$branch] - $account_5030108_credit[$branch];
					$account_5030108_total += $account_5030108[$branch];
					$account_beban_gaji_total[$branch] += $account_5030108[$branch];
					$account_beban_operasi_total[$branch] += $account_5030108[$branch];
					
				}
				$code = "5030109";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030109_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030109_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030109[$branch] = $account_5030109_debet[$branch] - $account_5030109_credit[$branch];
					$account_5030109_total += $account_5030109[$branch];
					$account_beban_gaji_total[$branch] += $account_5030109[$branch];
					$account_beban_operasi_total[$branch] += $account_5030109[$branch];
					
				}
				$code = "5030110";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030110_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030110_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030110[$branch] = $account_5030110_debet[$branch] - $account_5030110_credit[$branch];
					$account_5030110_total += $account_5030110[$branch];
					$account_beban_gaji_total[$branch] += $account_5030110[$branch];
					$account_beban_operasi_total[$branch] += $account_5030110[$branch];
					
				}					
				$code = "5030111";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030111_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030111_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030111[$branch] = $account_5030111_debet[$branch] - $account_5030111_credit[$branch];
					$account_5030111_total += $account_5030111[$branch];
					$account_beban_gaji_total[$branch] += $account_5030111[$branch];
					$account_beban_operasi_total[$branch] += $account_5030111[$branch];
					
				}
				$code = "5030112";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030112_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030112_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030112[$branch] = $account_5030112_debet[$branch] - $account_5030112_credit[$branch];
					$account_5030112_total += $account_5030112[$branch];
					$account_beban_gaji_total[$branch] += $account_5030112[$branch];
					$account_beban_operasi_total[$branch] += $account_5030112[$branch];
					
				}
				
				$code = "5030113";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030113_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030113_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030113[$branch] = $account_5030113_debet[$branch] - $account_5030113_credit[$branch];
					$account_5030113_total += $account_5030113[$branch];
					$account_beban_gaji_total[$branch] += $account_5030113[$branch];
					$account_beban_operasi_total[$branch] += $account_5030113[$branch];
					
				}
				
				
				$code = "5030114";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030114_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030114_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030114[$branch] = $account_5030114_debet[$branch] - $account_5030114_credit[$branch];
					$account_5030114_total += $account_5030114[$branch];
					$account_beban_gaji_total[$branch] += $account_5030114[$branch];
					$account_beban_operasi_total[$branch] += $account_5030114[$branch];
					
				}
				
				$account_beban_gaji_konsolidasi = $account_5030101_total + $account_5030102_total + $account_5030103_total + $account_5030104_total + $account_5030105_total  + $account_5030106_total + $account_5030108_total + $account_5030109_total + $account_5030110_total + $account_5030111_total + $account_5030112_total  + $account_5030113_total + $account_5030114_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Gaji & Honor</td>';
				$print .= '	<td align="right" class="">'.($account_beban_gaji_konsolidasi < 0 ? "(".number_format(abs($account_beban_gaji_konsolidasi)).")" : number_format($account_beban_gaji_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_gaji_total[$branch] < 0 ? "(".number_format(abs($account_beban_gaji_total[$branch])).")" : number_format($account_beban_gaji_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//5080404 Beban Asuransi Jiwa
				$code = "5080404";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080404_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080404_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080404[$branch] = $account_5080404_debet[$branch] - $account_5080404_credit[$branch];
					$account_5080404_total += $account_5080404[$branch];
					$account_beban_operasi_total[$branch] += $account_5080404[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Asuransi Jiwa</td>';
				$print .= '	<td align="right" class="">'.($account_5080404_total < 0 ? "(".number_format(abs($account_5080404_total)).")" : number_format($account_5080404_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5080404[$branch] < 0 ? "(".number_format(abs($account_5080404[$branch])).")" : number_format($account_5080404[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//5040003 Beban Rekrutmen
				$code = "5040003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5040003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5040003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5040003[$branch] = $account_5040003_debet[$branch] - $account_5040003_credit[$branch];
					$account_5040003_total += $account_5040003[$branch];
					$account_beban_operasi_total[$branch] += $account_5040003[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Rekrutmen</td>';
				$print .= '	<td align="right" class="">'.($account_5040003_total < 0 ? "(".number_format(abs($account_5040003_total)).")" : number_format($account_5040003_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5040003[$branch] < 0 ? "(".number_format(abs($account_5040003[$branch])).")" : number_format($account_5040003[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Training
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Training</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//5030107 Insentif Operations
				$code = "5030107";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030107_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030107_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030107[$branch] = $account_5030107_debet[$branch] - $account_5030107_credit[$branch];
					$account_5030107_total += $account_5030107[$branch];
					$account_beban_operasi_total[$branch] += $account_5030107[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Insentif Operations</td>';
				$print .= '	<td align="right" class="">'.($account_5030107_total < 0 ? "(".number_format(abs($account_5030107_total)).")" : number_format($account_5030107_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5030107[$branch] < 0 ? "(".number_format(abs($account_5030107[$branch])).")" : number_format($account_5030107[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//5080501 Beban Rumah Tangga Pusat
				$code = "5080501";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080501_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080501_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080501[$branch] = $account_5080501_debet[$branch] - $account_5080501_credit[$branch];
					$account_5080501_total += $account_5080501[$branch];
					$account_beban_RT_pusat_total[$branch] += $account_5080501[$branch];
					$account_beban_operasi_total[$branch] += $account_5080501[$branch];
					
				}
				$code = "5080301";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080301_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080301_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080301[$branch] = $account_5080301_debet[$branch] - $account_5080301_credit[$branch];
					$account_5080301_total += $account_5080301[$branch];
					$account_beban_RT_pusat_total[$branch] += $account_5080301[$branch];
					$account_beban_operasi_total[$branch] += $account_5080301[$branch];
					
				}				
				
				$account_beban_RT_pusat_konsolidasi = $account_5080501_total + $account_5080301_total;
				$account_beban_RT_cabang_konsolidasi = $account_beban_RT_pusat_konsolidasi - $account_beban_RT_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Rumah Tangga Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_RT_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_RT_pusat_total[0])).")" : number_format($account_beban_RT_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_RT_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_RT_pusat_total[0])).")" : number_format($account_beban_RT_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				
				
				//Beban ATK Pusat : 5080201, 5080202, 5080203, 5080401
				$code = "5080201";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080201_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080201_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080201[$branch] = $account_5080201_debet[$branch] - $account_5080201_credit[$branch];
					$account_5080201_total += $account_5080201[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080201[$branch];
					$account_beban_operasi_total[$branch] += $account_5080201[$branch];
					
				}
				$code = "5080202";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080202_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080202_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080202[$branch] = $account_5080202_debet[$branch] - $account_5080202_credit[$branch];
					$account_5080202_total += $account_5080202[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080202[$branch];
					$account_beban_operasi_total[$branch] += $account_5080202[$branch];
					
				}
				$code = "5080203";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080203_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080203_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080203[$branch] = $account_5080203_debet[$branch] - $account_5080203_credit[$branch];
					$account_5080203_total += $account_5080203[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080203[$branch];
					$account_beban_operasi_total[$branch] += $account_5080203[$branch];
					
				}
				$code = "5080401";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080401_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080401_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080401[$branch] = $account_5080401_debet[$branch] - $account_5080401_credit[$branch];
					$account_5080401_total += $account_5080401[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080401[$branch];
					$account_beban_operasi_total[$branch] += $account_5080401[$branch];
					
				}				
				
				$account_beban_ATK_pusat_konsolidasi = $account_5080201_total + $account_5080202_total + $account_5080203_total + $account_5080401_total;
				$account_beban_ATK_cabang_konsolidasi = $account_beban_ATK_pusat_konsolidasi - $account_beban_ATK_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban ATK Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_ATK_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_ATK_pusat_total[0])).")" : number_format($account_beban_ATK_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_ATK_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_ATK_pusat_total[0])).")" : number_format($account_beban_ATK_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				
				//Beban Transportasi Pusat : 5080302, 5080303, 5080304

				$code = "5080302";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080302_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080302_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080302[$branch] = $account_5080302_debet[$branch] - $account_5080302_credit[$branch];
					$account_5080302_total += $account_5080302[$branch];
					$account_beban_transportasi_pusat_total[$branch] += $account_5080302[$branch];
					$account_beban_operasi_total[$branch] += $account_5080302[$branch];
					
				}
				$code = "5080303";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080303_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080303_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080303[$branch] = $account_5080303_debet[$branch] - $account_5080303_credit[$branch];
					$account_5080303_total += $account_5080303[$branch];
					$account_beban_transportasi_pusat_total[$branch] += $account_5080303[$branch];
					$account_beban_operasi_total[$branch] += $account_5080303[$branch];
					
				}
				$code = "5080304";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080304_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080304_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080304[$branch] = $account_5080304_debet[$branch] - $account_5080304_credit[$branch];
					$account_5080304_total += $account_5080304[$branch];
					$account_beban_transportasi_pusat_total[$branch] += $account_5080304[$branch];
					$account_beban_operasi_total[$branch] += $account_5080304[$branch];
					
				}					
				
				$account_beban_transportasi_pusat_konsolidasi = $account_5080302_total + $account_5080303_total + $account_5080304_total ;
				$account_beban_transportasi_cabang_konsolidasi = $account_beban_transportasi_pusat_konsolidasi - $account_beban_transportasi_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Transportasi Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_transportasi_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_transportasi_pusat_total[0])).")" : number_format($account_beban_transportasi_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_transportasi_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_transportasi_pusat_total[0])).")" : number_format($account_beban_transportasi_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				
				//Beban Perawatan Pusat
				$code = "5060000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5060000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5060000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5060000[$branch] = $account_5060000_debet[$branch] - $account_5060000_credit[$branch];
					$account_5060000_total += $account_5060000[$branch];
					$account_beban_operasi_total[$branch] += $account_5060000[$branch];	
					
				}
				$account_5060000_cabang = $account_5060000_total - $account_5060000[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Perawatan Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_5060000[0]  < 0 ? "(".number_format(abs($account_5060000[0] )).")" : number_format($account_5060000[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_5060000[0]  < 0 ? "(".number_format(abs($account_5060000[0] )).")" : number_format($account_5060000[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				//Beban Listrik, Air, Telepon dan Internet Pusat : 5080101, 5080102, 5080103

				$code = "5080101";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080101[$branch] = $account_5080101_debet[$branch] - $account_5080101_credit[$branch];
					$account_5080101_total += $account_5080101[$branch];
					$account_beban_listrik_air_telp_pusat_total[$branch] += $account_5080101[$branch];
					$account_beban_operasi_total[$branch] += $account_5080101[$branch];
					
				}
				$code = "5080102";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080102[$branch] = $account_5080102_debet[$branch] - $account_5080102_credit[$branch];
					$account_5080102_total += $account_5080102[$branch];
					$account_beban_listrik_air_telp_pusat_total[$branch] += $account_5080102[$branch];
					$account_beban_operasi_total[$branch] += $account_5080102[$branch];
					
				}
				$code = "5080103";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080103[$branch] = $account_5080103_debet[$branch] - $account_5080103_credit[$branch];
					$account_5080103_total += $account_5080103[$branch];
					$account_beban_listrik_air_telp_pusat_total[$branch] += $account_5080103[$branch];
					$account_beban_operasi_total[$branch] += $account_5080103[$branch];
					
				}					
				
				$account_beban_listrik_air_telp_pusat_konsolidasi = $account_5080101_total + $account_5080102_total + $account_5080103_total ;
				$account_beban_listrik_air_telp_cabang_konsolidasi = $account_beban_listrik_air_telp_pusat_konsolidasi - $account_beban_listrik_air_telp_pusat_total[0] ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Listrik, Air, Telepon dan Internet Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_pusat_total[0])).")" : number_format($account_beban_listrik_air_telp_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_pusat_total[0])).")" : number_format($account_beban_listrik_air_telp_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				//Beban Lain-lain Pusat : 5080104, 5080402, 5080403, 5080406, 5080502, 5080503, 5080504
				
				$code = "5080104";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080104_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080104_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080104[$branch] = $account_5080104_debet[$branch] - $account_5080104_credit[$branch];
					$account_5080104_total += $account_5080104[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080104[$branch];
					$account_beban_operasi_total[$branch] += $account_5080104[$branch];
					
				}
				$code = "5080402";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080402_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080402_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080402[$branch] = $account_5080402_debet[$branch] - $account_5080402_credit[$branch];
					$account_5080402_total += $account_5080402[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080402[$branch];
					$account_beban_operasi_total[$branch] += $account_5080402[$branch];
					
				}
				$code = "5080403";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080403_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080403_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080403[$branch] = $account_5080403_debet[$branch] - $account_5080403_credit[$branch];
					$account_5080403_total += $account_5080403[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080403[$branch];
					$account_beban_operasi_total[$branch] += $account_5080403[$branch];
					
				}					
				$code = "5080406";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080406_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080406_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080406[$branch] = $account_5080406_debet[$branch] - $account_5080406_credit[$branch];
					$account_5080406_total += $account_5080406[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080406[$branch];
					$account_beban_operasi_total[$branch] += $account_5080406[$branch];					
				}
				$code = "5080502";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080502_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080502_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080502[$branch] = $account_5080502_debet[$branch] - $account_5080502_credit[$branch];
					$account_5080502_total += $account_5080502[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080502[$branch];
					$account_beban_operasi_total[$branch] += $account_5080502[$branch];
					
				}
				$code = "5080503";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080503_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080503_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080503[$branch] = $account_5080503_debet[$branch] - $account_5080503_credit[$branch];
					$account_5080503_total += $account_5080503[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080503[$branch];
					$account_beban_operasi_total[$branch] += $account_5080503[$branch];
					
				}
				$code = "5080504";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080504_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080504_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080504[$branch] = $account_5080504_debet[$branch] - $account_5080504_credit[$branch];
					$account_5080504_total += $account_5080504[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080504[$branch];
					$account_beban_operasi_total[$branch] += $account_5080504[$branch];
					
				}
				$account_beban_lainlain_pusat_konsolidasi = $account_5080104_total + $account_5080402_total + $account_5080403_total + $account_5080406_total + $account_5080502_total + $account_5080503_total + $account_5080504_total;
				$account_beban_lainlain_cabang_konsolidasi = $account_beban_lainlain_pusat_konsolidasi - $account_beban_lainlain_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Lain-lain Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_lainlain_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_lainlain_pusat_total[0])).")" : number_format($account_beban_lainlain_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_lainlain_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_lainlain_pusat_total[0])).")" : number_format($account_beban_lainlain_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				//Beban Rumah Tangga Seluruh Cabang
				$account_beban_RT_cabang_konsolidasi = $account_beban_RT_pusat_konsolidasi - $account_beban_RT_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Rumah Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_RT_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_RT_cabang_konsolidasi)).")"  : number_format($account_beban_RT_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=1; $total_branch <= 0; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_RT_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_RT_pusat_total[$branch])).")" : number_format($account_beban_RT_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban ATK Seluruh Cabang
				$account_beban_ATK_cabang_konsolidasi = $account_beban_ATK_pusat_konsolidasi - $account_beban_ATK_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban ATK Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_ATK_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_ATK_cabang_konsolidasi)).")" : number_format($account_beban_ATK_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_ATK_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_ATK_pusat_total[$branch])).")" : number_format($account_beban_ATK_pusat_total[$branch])).'</td>';
				}
				
				
				//Beban Transportasi Seluruh Cabang
				$account_beban_transportasi_cabang_konsolidasi = $account_beban_transportasi_pusat_konsolidasi - $account_beban_transportasi_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Transportasi Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_transportasi_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_transportasi_cabang_konsolidasi)).")"  : number_format($account_beban_transportasi_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_transportasi_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_transportasi_pusat_total[$branch])).")" : number_format($account_beban_transportasi_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Perawatan Seluruh Cabang
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Perawatan Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_5060000_cabang < 0 ? "(".number_format(abs($account_5060000_cabang)).")" : number_format($account_5060000_cabang)).'</td>';
				$print .= '	<td align="right" class="">0</td>';	
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5060000[$branch] < 0 ? "(".number_format(abs($account_5060000[$branch])).")" : number_format($account_5060000[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Listrik, Air, Telepon dan Internet Seluruh Cabang
				$account_beban_listrik_air_telp_cabang_konsolidasi = $account_beban_listrik_air_telp_pusat_konsolidasi - $account_beban_listrik_air_telp_pusat_total[0] ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Listrik, Air, Telepon dan Internet Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_cabang_konsolidasi)).")" : number_format($account_beban_listrik_air_telp_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';	
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_pusat_total[$branch])).")" : number_format($account_beban_listrik_air_telp_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Lain-lain Seluruh Cabang
				$account_beban_lainlain_cabang_konsolidasi = $account_beban_lainlain_pusat_konsolidasi - $account_beban_lainlain_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Lain-lain Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_lainlain_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_lainlain_cabang_konsolidasi)).")" : number_format($account_beban_lainlain_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';	
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_lainlain_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_lainlain_pusat_total[$branch])).")" : number_format($account_beban_lainlain_pusat_total[$branch])).'</td>';
				}
				
				
				//Beban Cleaning Service
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Cleaning Service</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Keamanan dan Kebersihan
				$code = "5080405";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080405_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080405_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080405[$branch] = $account_5080405_debet[$branch] - $account_5080405_credit[$branch];
					$account_5080405_total += $account_5080405[$branch];
					$account_biaya_langsung_total[$branch] += $account_5080405[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Keamanan dan Kebersihan</td>';
				$print .= '	<td align="right" class="">'.($account_5080405_total < 0 ? "(".number_format(abs($account_5080405_total)).")" : number_format($account_5080405_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5080405[$branch] < 0 ? "(".number_format(abs($account_5080405[$branch])).")" : number_format($account_5080405[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Penyusutan
				$code = "5070000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5070000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5070000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5070000[$branch] = $account_5070000_debet[$branch] - $account_5070000_credit[$branch];
					$account_5070000_total += $account_5070000[$branch];
					$account_biaya_operasi_total[$branch] += $account_5070000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Penyusutan</td>';
				$print .= '	<td align="right" class="">'.($account_5070000_total < 0 ? "(".number_format(abs($account_5070000_total)).")" : number_format($account_5070000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5070000[$branch] < 0 ? "(".number_format(abs($account_5070000[$branch])).")" : number_format($account_5070000[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Sewa Kantor Cabang : 5040001, 5040002
				
				$code = "5040001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5040001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5040001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5040001[$branch] = $account_5040001_debet[$branch] - $account_5040001_credit[$branch];
					$account_5040001_total += $account_5040001[$branch];
					$account_beban_sewa_kantor_cabang_total[$branch] += $account_5040001[$branch];
					$account_beban_operasi_total[$branch] += $account_5040001[$branch];
					
				}
				$code = "5040002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5040002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5040002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5040002[$branch] = $account_5040002_debet[$branch] - $account_5040002_credit[$branch];
					$account_5040002_total += $account_5040002[$branch];
					$account_beban_sewa_kantor_cabang_total[$branch] += $account_5040002[$branch];
					$account_beban_operasi_total[$branch] += $account_5040002[$branch];
					
				}				
				$account_beban_sewa_kantor_cabang_konsolidasi = $account_5040001_total + $account_5040002_total ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Sewa Kantor Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_sewa_kantor_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_sewa_kantor_cabang_konsolidasi)).")" : number_format($account_beban_sewa_kantor_cabang_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_sewa_kantor_cabang_total[$branch] < 0 ? "(".number_format(abs($account_beban_sewa_kantor_cabang_total[$branch])).")" : number_format($account_beban_sewa_kantor_cabang_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban MIS
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban MIS</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//Total Biaya Operasi	
				for($branch=0; $branch <= $total_branch; $branch++){				
					$account_beban_operasi_konsolidasi += $account_beban_operasi_total[$branch];
				}
				$print .= '	<tr><td align="left" >Total Biaya Operasi</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_beban_operasi_konsolidasi < 0 ? "(".number_format(abs($account_beban_operasi_konsolidasi)).")" : number_format($account_beban_operasi_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_beban_operasi_total[$branch] < 0 ? "(".number_format(abs($account_beban_operasi_total[$branch])).")" : number_format($account_beban_operasi_total[$branch])).'</td>';
				}
				$print .= '	</tr>';	
				
				$print .= '	<tr><td></td><td align="left" class="border_btm" colspan="8"> &nbsp;</td></tr>';
				
				//Laba (Rugi) Operasi
				$account_LR_operasi_konsolidasi = $account_labarugi_kotor_konsolidasi - $account_beban_operasi_konsolidasi;
				$print .= '	<tr><td align="left" ><b>Laba (Rugi) Operasi</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_operasi_konsolidasi < 0 ? "(".number_format(abs($account_LR_operasi_konsolidasi)).")" : number_format($account_LR_operasi_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_LR_operasi[$branch] = $account_labarugi_kotor_total[$branch] - $account_beban_operasi_total[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_operasi[$branch] < 0 ? "(".number_format(abs($account_LR_operasi[$branch])).")" : number_format($account_LR_operasi[$branch])).'</b></td>';
				}
				$print .= '	</tr>';	
			
				//---------------------------------------------------------------------------------------------
				//Pendapatan Diluar Usaha
				//---------------------------------------------------------------------------------------------
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Pendapatan & Beban Diluar Usaha</b></td>	<td colspan="8" ></td></tr>';
				$print .= '	<tr><td align="left" ><b>Pendapatan Diluar Usaha</b></td>	<td colspan="8" ></td></tr>';
				
				//Pendapatan Bunga Bank
				$code = "4030005";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030005_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030005_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030005[$branch] = $account_4030005_credit[$branch] - $account_4030005_debet[$branch];
					$account_4030005_total += $account_4030005[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030005[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Bunga Bank</td>';
				$print .= '	<td align="right" class="">'.($account_4030005_total < 0 ? "(".number_format(abs($account_4030005_total)).")" : number_format($account_4030005_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_4030005[$branch] < 0 ? "(".number_format(abs($account_4030005[$branch])).")" : number_format($account_4030005[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Pendapatan Lainnya : 4030001, 4030002, 4030003, 4030004, 4030006 
				
				$code = "4030001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030001[$branch] = $account_4030001_credit[$branch] - $account_4030001_debet[$branch];
					$account_4030001_total += $account_4030001[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030001[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030001[$branch];
					
				}
				$code = "4030002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030002[$branch] = $account_4030002_credit[$branch] - $account_4030002_debet[$branch];
					$account_4030002_total += $account_4030002[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030002[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030002[$branch];
					
				}
				$code = "4030003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030003[$branch] = $account_4030003_credit[$branch] - $account_4030003_debet[$branch];
					$account_4030003_total += $account_4030003[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030003[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030003[$branch];
					
				}					
				$code = "4030004";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030004_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030004_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030004[$branch] = $account_4030004_credit[$branch] - $account_4030004_debet[$branch];
					$account_4030004_total += $account_4030004[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030004[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030004[$branch];
					
				}
				$code = "4030006";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030006_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030006_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030006[$branch] = $account_4030006_credit[$branch] - $account_4030006_debet[$branch];
					$account_4030006_total += $account_4030006[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030006[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030006[$branch];
					
				}
				
				$account_pendapatan_lain_konsolidasi = $account_4030001_total + $account_4030002_total + $account_4030003_total + $account_4030004_total + $account_4030006_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Lainnya</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_lain_konsolidasi < 0 ? "(".number_format(abs($account_pendapatan_lain_konsolidasi)).")" : number_format($account_pendapatan_lain_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_lain_pusat_total[$branch] < 0 ? "(".number_format(abs($account_pendapatan_lain_pusat_total[$branch])).")" : number_format($account_pendapatan_lain_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Total Pendapatan Diluar Usaha
				$account_pendapatan_diluar_usaha_konsolidasi = $account_4030005_total + $account_pendapatan_lain_konsolidasi;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Pendapatan Diluar Usaha</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_diluar_usaha_konsolidasi < 0 ? "(".number_format(abs($account_pendapatan_diluar_usaha_konsolidasi)).")" : number_format($account_pendapatan_diluar_usaha_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_diluar_usaha_total[$branch] < 0 ? "(".number_format(abs($account_pendapatan_diluar_usaha_total[$branch])).")" : number_format($account_pendapatan_diluar_usaha_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//---------------------------------------------------------------------------------------------
				//Beban Diluar Usaha
				//---------------------------------------------------------------------------------------------
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Beban Diluar Usaha</b></td>	<td colspan="8" ></td></tr>';
				
				//Beban Bunga Bank
				$code = "5090004";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090004_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090004_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090004[$branch] = $account_5090004_debet[$branch] - $account_5090004_credit[$branch];
					$account_5090004_total += $account_5090004[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090004[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Bunga Bank</td>';
				$print .= '	<td align="right" class="">'.($account_5090004_total < 0 ? "(".number_format(abs($account_5090004_total)).")" : number_format($account_5090004_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5090004[$branch] < 0 ? "(".number_format(abs($account_5090004[$branch])).")" : number_format($account_5090004[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Merchant Discount Rate
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Merchant Discount Rate</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Kerugian Penghapusan Aktiva Tetap
				$code = "5090003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090003[$branch] = $account_5090003_debet[$branch] - $account_5090003_credit[$branch];
					$account_5090003_total += $account_5090003[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090003[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Kerugian Penghapusan Aktiva Tetap</td>';
				$print .= '	<td align="right" class="">'.($account_5090003_total < 0 ? "(".number_format(abs($account_5090003_total)).")" : number_format($account_5090003_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5090003[$branch] < 0 ? "(".number_format(abs($account_5090003[$branch])).")" : number_format($account_5090003[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Selisih kurs/Penerimaan /Pembayaran
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selisih kurs/Penerimaan /Pembayaran</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban PPh Pasal 21 Karyawan
				$code = "5050003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5050003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5050003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5050003[$branch] = $account_5050003_debet[$branch] - $account_5050003_credit[$branch];
					$account_5050003_total += $account_5050003[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5050003[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban PPh Pasal 21 Karyawan</td>';
				$print .= '	<td align="right" class="">'.($account_5050003_total < 0 ? "(".number_format(abs($account_5050003_total)).")" : number_format($account_5050003_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5050003[$branch] < 0 ? "(".number_format(abs($account_5050003[$branch])).")" : number_format($account_5050003[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban PPh Pasal 4 Ayat (2) atas Sewa Ruangan
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban PPh Pasal 4 Ayat (2) atas Sewa Ruangan</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban PPh Pasal 4 Ayat (2) atas Bunga Bank
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban PPh Pasal 4 Ayat (2) atas Bunga Bank</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Lainnya: 5050001, 5050002, 5050005, 5090001, 5090002, 5090005
				
				$code = "5050001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5050001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5050001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5050001[$branch] = $account_5050001_debet[$branch] - $account_5050001_credit[$branch];
					$account_5050001_total += $account_5050001[$branch];
					$account_beban_lain_total[$branch] += $account_5050001[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5050001[$branch];
					
				}
				$code = "5050002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5050002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5050002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5050002[$branch] = $account_5050002_debet[$branch] - $account_5050002_credit[$branch];
					$account_5050002_total += $account_5050002[$branch];
					$account_beban_lain_total[$branch] += $account_5050002[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5050002[$branch];
					
				}
				$code = "5050005";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5050005_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5050005_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5050005[$branch] = $account_5050005_debet[$branch] - $account_5050005_credit[$branch];
					$account_5050005_total += $account_5050005[$branch];
					$account_beban_lain_total[$branch] += $account_5050005[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5050005[$branch];
					
				}					
				$code = "5090001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090001[$branch] = $account_5090001_debet[$branch] - $account_5090001_credit[$branch];
					$account_5090001_total += $account_5090001[$branch];
					$account_beban_lain_total[$branch] += $account_5090001[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090001[$branch];
					
				}
				$code = "5090002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090002[$branch] = $account_5090002_debet[$branch] - $account_5090002_credit[$branch];
					$account_5090002_total += $account_5090002[$branch];
					$account_beban_lain_total[$branch] += $account_5090002[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090002[$branch];
					
				}
				
				$code = "5090005";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090005_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090005_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090005[$branch] = $account_5090005_debet[$branch] - $account_5090005_credit[$branch];
					$account_5090005_total += $account_5090005[$branch];
					$account_beban_lain_total[$branch] += $account_5090005[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090005[$branch];
					
				}
				
				$account_beban_lain_konsolidasi = $account_5050001_total + $account_5050002_total + $account_5050005_total + $account_5090001_total + $account_5090002_total  + $account_5090005_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Lainnya</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_beban_lain_konsolidasi < 0 ? "(".number_format(abs($account_beban_lain_konsolidasi)).")" : number_format($account_beban_lain_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_beban_lain_total[$branch] < 0 ? "(".number_format(abs($account_beban_lain_total[$branch])).")" : number_format($account_beban_lain_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Total Beban Diluar Usaha
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_beban_diluar_usaha_konsolidasi += $account_beban_diluar_usaha_total[$branch];
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Beban Diluar Usaha</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_beban_diluar_usaha_konsolidasi < 0 ? "(".number_format(abs($account_beban_diluar_usaha_konsolidasi)).")" : number_format($account_beban_diluar_usaha_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_beban_diluar_usaha_total[$branch] < 0 ? "(".number_format(abs($account_beban_diluar_usaha_total[$branch])).")" : number_format($account_beban_diluar_usaha_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				
				$print .= '	<tr><td></td><td align="left" colspan="8" class="border_btm"> &nbsp;</td></tr>';
				
				
				//---------------------------------------------------------------------------------------------
				//Laba (Rugi) TOTAL
				//---------------------------------------------------------------------------------------------
				
				
				//Laba (Rugi) Sebelum Pajak
				$account_LR_sebelum_pajak_konsolidasi = $account_LR_operasi_konsolidasi + $account_pendapatan_diluar_usaha_konsolidasi- $account_beban_diluar_usaha_konsolidasi;
				$print .= '	<tr><td align="left" ><b>Laba (Rugi) Sebelum Pajak</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_sebelum_pajak_konsolidasi < 0 ? "(".number_format(abs($account_LR_sebelum_pajak_konsolidasi)).")" : number_format($account_LR_sebelum_pajak_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_LR_sebelum_pajak[$branch] = $account_LR_operasi_total[$branch] + $account_pendapatan_diluar_usaha_total[$branch]- $account_beban_diluar_usaha_total[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_sebelum_pajak[$branch] < 0 ? "(".number_format(abs($account_LR_sebelum_pajak[$branch])).")" : number_format($account_LR_sebelum_pajak[$branch])).'</b></td>';
				}
				$print .= '</tr>';
				
				
				$print .= '	<tr><td></td><td align="left" colspan="8" class="border_btm"></td></tr>';
				
				//Pajak Penghasilan Badan
				$code = "5050004";
				for($branch=0; $branch <= $total_branch; $branch++){
					//$account_5050004_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					//$account_5050004_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					//$account_5050004[$branch] = $account_5050004_debet[$branch] - $account_5050004_credit[$branch];
					$account_5050004[$branch] = 0;
					$account_5050004_total += $account_5050004[$branch];
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pajak Penghasilan Badan</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_5050004_total < 0 ? "(".number_format(abs($account_5050004_total)).")" : number_format($account_5050004_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_5050004[$branch] < 0 ? "(".number_format(abs($account_5050004[$branch])).")" : number_format($account_5050004[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				
				$print .= '	<tr><td></td><td align="left" colspan="8" class="border_btm"></td></tr>';
				
				//Laba (Rugi) Bersih
				$account_LR_bersih_konsolidasi = $account_LR_sebelum_pajak_konsolidasi - $account_5050004_total;
				
				$print .= '	<tr><td align="left" ><b>Laba (Rugi) Bersih</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_bersih_konsolidasi < 0 ? "(".number_format(abs($account_LR_bersih_konsolidasi)).")" : number_format($account_LR_bersih_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_LR_bersih_total[$branch] = $account_LR_sebelum_pajak[$branch] - $account_5050004[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_bersih_total[$branch] < 0 ? "(".number_format(abs($account_LR_bersih_total[$branch])).")" : number_format($account_LR_bersih_total[$branch])).'</b></td>';
				}
				$print .= '</tr>';
				
				$print .= '</table>';
				
			$html .= $print;
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
	
	
	//NERACA
	public function neraca()
	{
		if($this->session->userdata('logged_in'))
		{
			$total_branch = $this->branch_model->count_branch();
			
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
				//$date_start =$week_today[0];
				$date_start = "2015-01-01";
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
			
			//----------------------------------------------------------------------------------------
			//ASET LANCAR
			//----------------------------------------------------------------------------------------
			
			$print .= '	<tr><td align="left" ><b>ASET</b></td>	<td colspan="8" ></td></tr>';
			$print .= '	<tr><td align="left" ><b>Aset Lancar</b></td>	<td colspan="8" ></td></tr>';
			
								
				//1010000 Kas Teller
				$code = "1010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1010000[$branch] = $account_1010000_credit[$branch] - $account_1010000_debet[$branch];
					$account_1010000_total += $account_1010000[$branch];
					$account_aset_lancar_total[$branch] += $account_1010000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kas Teller</td>';
				$print .= '	<td align="right" class="">'.($account_1010000_total < 0 ? "(".number_format(abs($account_1010000_total)).")" : number_format($account_1010000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_1010000[$branch] < 0 ? "(".number_format(abs($account_1010000[$branch])).")" : number_format($account_1010000[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
				
				
				//Bank Tabungan:  1020100, 1020200, 1020300, 1020400, 1020500, 1020600, 1020700, 1020900, 1021100, 1021200
				$code = "1020100";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1020100_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020100_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020100[$branch] = $account_1020100_debet[$branch] - $account_1020100_credit[$branch];
					$account_1020100_total += $account_1020100[$branch];
					$account_bank_tabungan_total[$branch] += $account_1020100[$branch];
					$account_aset_lancar_total[$branch] += $account_1020100[$branch];
					
				}
				$code = "1020200";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1020200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020200[$branch] = $account_1020200_debet[$branch] - $account_1020200_credit[$branch];
					$account_1020200_total += $account_1020200[$branch];
					$account_bank_tabungan_total[$branch] += $account_1020200[$branch];
					$account_aset_lancar_total[$branch] += $account_1020200[$branch];
					
				}
				$code = "1020300";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1020300_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020300_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020300[$branch] = $account_1020300_debet[$branch] - $account_1020300_credit[$branch];
					$account_1020300_total += $account_1020300[$branch];
					$account_bank_tabungan_total[$branch] += $account_1020300[$branch];
					$account_aset_lancar_total[$branch] += $account_1020300[$branch];
					
				}					
				$code = "1020400";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1020400_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020400_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020400[$branch] = $account_1020400_debet[$branch] - $account_1020400_credit[$branch];
					$account_1020400_total += $account_1020400[$branch];
					$account_bank_tabungan_total[$branch] += $account_1020400[$branch];
					$account_aset_lancar_total[$branch] += $account_1020400[$branch];
					
				}	
				$code = "1020500";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1020500_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020500_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020500[$branch] = $account_1020500_debet[$branch] - $account_1020500_credit[$branch];
					$account_1020500_total += $account_1020500[$branch];
					$account_bank_tabungan_total[$branch] += $account_1020500[$branch];
					$account_aset_lancar_total[$branch] += $account_1020500[$branch];
					
				}
				$code = "1020600";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1020600_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020600_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020600[$branch] = $account_1020600_debet[$branch] - $account_1020600_credit[$branch];
					$account_1020600_total += $account_1020600[$branch];
					$account_bank_tabungan_total[$branch] += $account_1020600[$branch];
					$account_aset_lancar_total[$branch] += $account_1020600[$branch];
					
				}
				$code = "1020700";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1020700_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020700_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020700[$branch] = $account_1020700_debet[$branch] - $account_1020700_credit[$branch];
					$account_1020700_total += $account_1020700[$branch];
					$account_bank_tabungan_total[$branch] += $account_1020700[$branch];
					$account_aset_lancar_total[$branch] += $account_1020700[$branch];
					
				}					
				$code = "1020900";
				$code_level1 = substr($code,0,9);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1020900_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020900_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020900[$branch] = $account_1020900_debet[$branch] - $account_1020900_credit[$branch];
					$account_1020900_total += $account_1020900[$branch];
					$account_bank_tabungan_total[$branch] += $account_1020900[$branch];
					$account_aset_lancar_total[$branch] += $account_1020900[$branch];
					
				}
				$code = "1021100";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1021100_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1021100_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1021100[$branch] = $account_1021100_debet[$branch] - $account_1021100_credit[$branch];
					$account_1021100_total += $account_1021100[$branch];
					$account_bank_tabungan_total[$branch] += $account_1021100[$branch];
					$account_aset_lancar_total[$branch] += $account_1021100[$branch];
					
				}					
				$code = "1021200";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1021200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1021200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1021200[$branch] = $account_1021200_debet[$branch] - $account_1021200_credit[$branch];
					$account_1021200_total += $account_1021200[$branch];
					$account_bank_tabungan_total[$branch] += $account_1021200[$branch];
					$account_aset_lancar_total[$branch] += $account_1021200[$branch];
					
				}
				
				$account_bank_tabungan_konsolidasi = $account_1020100_total + $account_1020200_total + $account_1020300_total + $account_1020400_total + $account_1020500_total + $account_1020600_total + $account_1020700_total + $account_1020900_total + $account_1021100_total + $account_1021200_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank Tabungan</td>';
				$print .= '	<td align="right" class="">'.($account_bank_tabungan_konsolidasi < 0 ? "(".number_format(abs($account_bank_tabungan_konsolidasi)).")" : number_format($account_bank_tabungan_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_bank_tabungan_total[$branch] < 0 ? "(".number_format(abs($account_bank_tabungan_total[$branch])).")" : number_format($account_bank_tabungan_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Bank Deposito
				$code = "1020800";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1020800_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020800_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1020800[$branch] = $account_1020800_credit[$branch] - $account_1020800_debet[$branch];
					$account_1020800_total += $account_1020800[$branch];
					$account_aset_lancar_total[$branch] += $account_1020800[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank Deposito</td>';
				$print .= '	<td align="right" class="">'.($account_1020800_total < 0 ? "(".number_format(abs($account_1020800_total)).")" : number_format($account_1020800_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_1020800[$branch] < 0 ? "(".number_format(abs($account_1020800[$branch])).")" : number_format($account_1020800[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
				
				//Piutang Pembiayaan 1030100, 1030200, 1030300 dikurangi 1070000
				
				$code = "1030100";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1030100_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030100_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030100[$branch] = $account_1030100_debet[$branch] - $account_1030100_credit[$branch];
					$account_1030100_total += $account_1030100[$branch];
					$account_piutang_pembiayaan_total[$branch] += $account_1030100[$branch];
					$account_aset_lancar_total[$branch] += $account_1030100[$branch];
					
				}
				$code = "1030200";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1030200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030200[$branch] = $account_1030200_debet[$branch] - $account_1030200_credit[$branch];
					$account_1030200_total += $account_1030200[$branch];
					$account_piutang_pembiayaan_total[$branch] += $account_1030200[$branch];
					$account_aset_lancar_total[$branch] += $account_1030200[$branch];
					
				}
				$code = "1030300";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1030300_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030300_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030300[$branch] = $account_1030300_debet[$branch] - $account_1030300_credit[$branch];
					$account_1030300_total += $account_1030300[$branch];
					$account_piutang_pembiayaan_total[$branch] += $account_1030300[$branch];
					$account_aset_lancar_total[$branch] += $account_1030300[$branch];
					
				}					
				$code = "1070000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1070000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1070000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1070000[$branch] = $account_1070000_debet[$branch] - $account_1070000_credit[$branch];
					$account_1070000_total += $account_1070000[$branch];
					$account_piutang_pembiayaan_total[$branch] -= $account_1070000[$branch];
					$account_aset_lancar_total[$branch] -= $account_1070000[$branch];
					
				}				
				
				$account_piutang_pembiayaan_konsolidasi = $account_1030100_total + $account_1030200_total + $account_1030300_total - $account_1070000_total ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Piutang Pembiayaan</td>';
				$print .= '	<td align="right" class="">'.($account_piutang_pembiayaan_konsolidasi < 0 ? "(".number_format(abs($account_piutang_pembiayaan_konsolidasi)).")" : number_format($account_piutang_pembiayaan_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_piutang_pembiayaan_total[$branch] < 0 ? "(".number_format(abs($account_piutang_pembiayaan_total[$branch])).")" : number_format($account_piutang_pembiayaan_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Pinjaman Karyawan
				$code = "1030500";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1030500_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030500_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030500[$branch] = $account_1030500_credit[$branch] - $account_1030500_debet[$branch];
					$account_1030500_total += $account_1030500[$branch];
					$account_aset_lancar_total[$branch] += $account_1030500[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pinjaman Karyawan</td>';
				$print .= '	<td align="right" class="">'.($account_1030500_total < 0 ? "(".number_format(abs($account_1030500_total)).")" : number_format($account_1030500_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_1030500[$branch] < 0 ? "(".number_format(abs($account_1030500[$branch])).")" : number_format($account_1030500[$branch])).'</td>';
				}
				$print .= '</tr>';	

				//Persediaan Barang Cetakan
				$code = "1060000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1060000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1060000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1060000[$branch] = $account_1060000_credit[$branch] - $account_1060000_debet[$branch];
					$account_1060000_total += $account_1060000[$branch];
					$account_aset_lancar_total[$branch] += $account_1060000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Persediaan Barang Cetakan</td>';
				$print .= '	<td align="right" class="">'.($account_1060000_total < 0 ? "(".number_format(abs($account_1060000_total)).")" : number_format($account_1060000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_1060000[$branch] < 0 ? "(".number_format(abs($account_1060000[$branch])).")" : number_format($account_1060000[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
				//Beban Dibayar Dimuka
				$code = "1050000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1050000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1050000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1050000[$branch] = $account_1050000_credit[$branch] - $account_1050000_debet[$branch];
					$account_1050000_total += $account_1050000[$branch];
					$account_aset_lancar_total[$branch] += $account_1050000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Dibayar Dimuka</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_1050000_total < 0 ? "(".number_format(abs($account_1050000_total)).")" : number_format($account_1050000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_1050000[$branch] < 0 ? "(".number_format(abs($account_1050000[$branch])).")" : number_format($account_1050000[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
				
				//Total Aset Lancar	
				$account_aset_lancar_konsolidasi = $account_1010000_total + $account_1020800_total + $account_piutang_pembiayaan_konsolidasi + $account_1030500_total + $account_1060000_total + $account_1050000_total;
				$print .= '	<tr><td align="left" >Total Aset Lancar</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_aset_lancar_konsolidasi < 0 ? "(".number_format(abs($account_aset_lancar_konsolidasi)).")" : number_format($account_aset_lancar_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_aset_lancar_total[$branch] < 0 ? "(".number_format(abs($account_aset_lancar_total[$branch])).")" : number_format($account_aset_lancar_total[$branch])).'</td>';
				}
				$print .= '	</tr>';	
				
				
			//----------------------------------------------------------------------------------------
			//ASET TIDAK LANCAR
			//----------------------------------------------------------------------------------------
			
			$print .= '	<tr><td align="left" ></td>	<td colspan="8" ></td></tr>';
			$print .= '	<tr><td align="left" ><b>Aset Tidak Lancar</b></td>	<td colspan="8" ></td></tr>';
			
				//Aset Lain-lain : 1090201 - 1090202
				$code = "1090201";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1090201_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1090201_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1090201[$branch] = $account_1090201_debet[$branch] - $account_1090201_credit[$branch];
					$account_1090201_total += $account_1090201[$branch];
					$account_aset_lain_total[$branch] += $account_1090201[$branch];
					
				}				
				$code = "1090202";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1090202_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1090202_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1090202[$branch] = $account_1090202_debet[$branch] - $account_1090202_credit[$branch];
					$account_1090202_total += $account_1090202[$branch];
					$account_aset_lain_total[$branch] -= $account_1090202[$branch];
					
				}
				$account_aset_lain_konsolidasi = $account_1090201_total - $account_1090202_total ;
				
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lain-lain</td>';
				$print .= '	<td align="right" class="">'.($account_aset_lain_konsolidasi < 0 ? "(".number_format(abs($account_aset_lain_konsolidasi)).")" : number_format($account_aset_lain_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_aset_lain_total[$branch] < 0 ? "(".number_format(abs($account_aset_lain_total[$branch])).")" : number_format($account_aset_lain_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
			//----------------------------------------------------------------------------------------
			//ASET TETAP
			//----------------------------------------------------------------------------------------
			
				$print .= '	<tr><td align="left" ></td>	<td colspan="8" ></td></tr>';
				$print .= '	<tr><td align="left" ><b>Aset Tetap</b></td>	<td colspan="8" ></td></tr>';
				
				
				//Aset Tanah : 1080101
				$code = "1080101";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1080101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1080101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1080101[$branch] = $account_1080101_debet[$branch] - $account_1080101_credit[$branch];
					$account_1080101_total += $account_1080101[$branch];
					$account_aset_tetap_total[$branch] += $account_1080101[$branch];
					
				}	
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanah</td>';
				$print .= '	<td align="right" class="">'.($account_1080101_total < 0 ? "(".number_format(abs($account_1080101_total)).")" : number_format($account_1080101_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_1080101[$branch] < 0 ? "(".number_format(abs($account_1080101[$branch])).")" : number_format($account_1080101[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Aset Bangunan : 1080201
				$code = "1080201";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1080201_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1080201_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1080201[$branch] = $account_1080201_debet[$branch] - $account_1080201_credit[$branch];
					$account_1080201_total += $account_1080201[$branch];
					$account_aset_tetap_total[$branch] += $account_1080201[$branch];
					
				}	
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bangunan</td>';
				$print .= '	<td align="right" class="">'.($account_1080201_total < 0 ? "(".number_format(abs($account_1080201_total)).")" : number_format($account_1080201_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_1080201[$branch] < 0 ? "(".number_format(abs($account_1080201[$branch])).")" : number_format($account_1080201[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Aset Kendaraan : 
				$code = "1080301";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1080301_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1080301_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1080301[$branch] = $account_1080301_debet[$branch] - $account_1080301_credit[$branch];
					$account_1080301_total += $account_1080301[$branch];
					$account_aset_tetap_total[$branch] += $account_1080301[$branch];
					
				}	
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kendaraan</td>';
				$print .= '	<td align="right" class="">'.($account_1080301_total < 0 ? "(".number_format(abs($account_1080301_total)).")" : number_format($account_1080301_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_1080301[$branch] < 0 ? "(".number_format(abs($account_1080301[$branch])).")" : number_format($account_1080301[$branch])).'</td>';
				}
				
				$print .= '</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Peralatan Rumah Sakit</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Furniture, Fixture & IT</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Peralatan Kantor</td>';
				$print .= '	<td align="right" class="">'.($account_1080301_total < 0 ? "(".number_format(abs($account_1080301_total)).")" : number_format($account_1080301_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_1080301[$branch] < 0 ? "(".number_format(abs($account_1080301[$branch])).")" : number_format($account_1080301[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				$account_aset_tetap_total_konsolidasi = $account_1080101_total + $account_1080201_total + $account_1080301_total ;				
				
				$print .= '	<tr><td align="left" >Total Aset Tetap</td>';
				$print .= '	<td align="right" class="">'.($account_aset_tetap_total_konsolidasi < 0 ? "(".number_format(abs($account_aset_tetap_total_konsolidasi)).")" : number_format($account_aset_tetap_total_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_aset_tetap_total[$branch] < 0 ? "(".number_format(abs($account_aset_tetap_total[$branch])).")" : number_format($account_aset_tetap_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
								
				$print .= '	<tr><td align="left" ></td>	<td colspan="8" ></td></tr>';
				
				//Akumulasi Penyusutan Aset Tetap : 1080302
				$code = "1080302";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1080302_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_1080302_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_1080302[$branch] = $account_1080302_debet[$branch] - $account_1080302_credit[$branch];
					$account_1080302_total += $account_1080302[$branch];
					$account_aset_tetap_bersih_total[$branch] -= $account_1080302[$branch];
					
				}	
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Akumulasi Penyusutan Aset Tetap</td>';
				$print .= '	<td align="right" class="">'.($account_1080302_total < 0 ? "(".number_format(abs($account_1080302_total)).")" : number_format($account_1080302_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_1080302[$branch] < 0 ? "(".number_format(abs($account_1080302[$branch])).")" : number_format($account_1080302[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				$account_aset_tetap_bersih_total_konsolidasi = $account_aset_tetap_total_konsolidasi - $account_1080302_total;
				$print .= '	<tr><td align="left" >Total Aset Tetap - Bersih</td>';
				$print .= '	<td align="right" class="">'.($account_aset_tetap_bersih_total_konsolidasi < 0 ? "(".number_format(abs($account_aset_tetap_bersih_total_konsolidasi)).")" : number_format($account_aset_tetap_bersih_total_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_aset_tetap_bersih_total[$branch] < 0 ? "(".number_format(abs($account_aset_tetap_bersih_total[$branch])).")" : number_format($account_aset_tetap_bersih_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//----------------------------------------------------------------------------------------
				//Total Aset Tidak Lancar
				//----------------------------------------------------------------------------------------
				$print .= '	<tr><td align="left" ></td>	<td colspan="8" class="border_btm"></td></tr>';
				$account_aset_tidak_lancar_total_konsolidasi = $account_aset_tetap_bersih_total_konsolidasi + $account_aset_lain_konsolidasi;
				$print .= '	<tr><td align="left" class=""><b>Total Aset Tidak Lancar</b></td>';
				$print .= '	<td align="right" class="border_btm">'.($account_aset_tidak_lancar_total_konsolidasi < 0 ? "(".number_format(abs($account_aset_tidak_lancar_total_konsolidasi)).")" : number_format($account_aset_tidak_lancar_total_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					
					$account_aset_tidak_lancar_total[$branch] = $account_aset_tetap_bersih_total[$branch] + $account_aset_lain[$branch];
					$print .= '	<td align="right" class="border_btm">'.($account_aset_tidak_lancar_total[$branch] < 0 ? "(".number_format(abs($account_aset_tidak_lancar_total[$branch])).")" : number_format($account_aset_tidak_lancar_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				//----------------------------------------------------------------------------------------
				//TOTAL ASET
				//----------------------------------------------------------------------------------------
				$print .= '	<tr><td align="left" ></td>	<td colspan="8" class="border_btm"></td></tr>';
				$account_aset_total_konsolidasi = $account_aset_tidak_lancar_total_konsolidasi + $account_aset_lancar_konsolidasi;
				$print .= '	<tr><td align="left" ><b>TOTAL ASET</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_aset_total_konsolidasi < 0 ? "(".number_format(abs($account_aset_total_konsolidasi)).")" : number_format($account_aset_total_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){					
					$account_aset_total[$branch] = $account_aset_tidak_lancar_total[$branch] + $account_aset_lancar_total[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_aset_total[$branch] < 0 ? "(".number_format(abs($account_aset_total[$branch])).")" : number_format($account_aset_total[$branch])).'</b></td>';
				}
				$print .= '</tr>';
				
			//----------------------------------------------------------------------------------------
			//KEWAJIBAN
			//----------------------------------------------------------------------------------------
				$print .= '	<tr><td align="left" ></td>	<td colspan="8" ></td></tr>';
				$print .= '	<tr><td align="left" ><b>KEWAJIBAN</b></td>';
				$print .= '	<tr><td align="left" ><b>Kewajiban Jangka Pendek</b></td>';
				
			//Simpanan anggota : 2010100, 2010200, 2010300, 2010400
	
				$code = "2010100";
				$code_level1 = substr($code,0,5);					
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_2010100_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010100_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010100[$branch] = $account_2010100_credit[$branch] - $account_2010100_debet[$branch];
					$account_2010100_total += $account_2010100[$branch];
					$account_simpanan_anggota_total[$branch] += $account_2010100[$branch];
					$account_kewajiban_lancar_total[$branch] += $account_2010100[$branch];
					
				}
				$code = "2010200";	
				$code_level1 = substr($code,0,5);				
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_2010200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010200[$branch] = $account_2010200_credit[$branch] - $account_2010200_debet[$branch];
					$account_2010200_total += $account_2010200[$branch];
					$account_simpanan_anggota_total[$branch] += $account_2010200[$branch];
					$account_kewajiban_lancar_total[$branch] += $account_2010200[$branch];
					
				}
				$code = "2010300";
				$code_level1 = substr($code,0,5);				
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_2010300_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010300_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010300[$branch] = $account_2010300_credit[$branch] - $account_2010300_debet[$branch];
					$account_2010300_total += $account_2010300[$branch];
					$account_simpanan_anggota_total[$branch] += $account_2010300[$branch];
					$account_kewajiban_lancar_total[$branch] += $account_2010300[$branch];
					
				}					
				$code = "2010400";	
				$code_level1 = substr($code,0,5);				
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_2010400_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010400_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2010400[$branch] = $account_2010400_credit[$branch] - $account_2010400_debet[$branch];
					$account_2010400_total += $account_2010400[$branch];
					$account_simpanan_anggota_total[$branch] += $account_2010400[$branch];
					$account_kewajiban_lancar_total[$branch] += $account_2010400[$branch];
					
				}
								
				$account_simpanan_anggota_konsolidasi = $account_2010100_total + $account_2010200_total + $account_2010300_total + $account_2010400_total ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Simpanan Anggota</td>';
				$print .= '	<td align="right" class="">'.($account_simpanan_anggota_konsolidasi < 0 ? "(".number_format(abs($account_simpanan_anggota_konsolidasi)).")" : number_format($account_simpanan_anggota_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_simpanan_anggota_total[$branch] < 0 ? "(".number_format(abs($account_simpanan_anggota_total[$branch])).")" : number_format($account_simpanan_anggota_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Simpanan berjangka : 2020000
				$code = "2020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_2020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2020000[$branch] = $account_2020000_credit[$branch] - $account_2020000_debet[$branch];
					$account_2020000_total += $account_2020000[$branch];
					$account_kewajiban_lancar_total[$branch] += $account_2020000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Simpanan Berjangka</td>';
				$print .= '	<td align="right" class="">'.($account_2020000_total < 0 ? "(".number_format(abs($account_2020000_total)).")" : number_format($account_2020000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_2020000[$branch] < 0 ? "(".number_format(abs($account_2020000[$branch])).")" : number_format($account_2020000[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
				
				//Utang lain-lain : 2030100, 2050000
	
				$code = "2030100";
				$code_level1 = substr($code,0,5);					
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_2030100_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2030100_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2030100[$branch] = $account_2030100_credit[$branch] - $account_debit_credit[$branch];
					$account_2030100_total += $account_2030100[$branch];
					$account_utang_lain_total[$branch] += $account_2030100[$branch];
					$account_kewajiban_lancar_total[$branch] += $account_2030100[$branch];
					
				}
				$code = "2050000";	
				$code_level1 = substr($code,0,3);				
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_2050000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2050000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2050000[$branch] = $account_2050000_credit[$branch] - $account_2050000_debit[$branch];
					$account_2050000_total += $account_2050000[$branch];
					$account_utang_lain_total[$branch] += $account_2050000[$branch];
					$account_kewajiban_lancar_total[$branch] += $account_2050000[$branch];
					
				}
				
								
				$account_utang_lain_konsolidasi = $account_2030100_total + $account_2050000_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Utang Lain-lain</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_utang_lain_konsolidasi < 0 ? "(".number_format(abs($account_utang_lain_konsolidasi)).")" : number_format($account_utang_lain_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_utang_lain_total[$branch] < 0 ? "(".number_format(abs($account_utang_lain_total[$branch])).")" : number_format($account_utang_lain_total[$branch])).'</td>';
				}
				$print .= '</tr>';			
				
				$account_kewajiban_lancar_konsolidasi = $account_simpanan_anggota_konsolidasi + $account_2020000_total + $account_utang_lain_konsolidasi;
				$print .= '	<tr><td align="left" >Total Kewajiban Lancar</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_kewajiban_lancar_konsolidasi < 0 ? "(".number_format(abs($account_kewajiban_lancar_konsolidasi)).")" : number_format($account_kewajiban_lancar_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_kewajiban_lancar_total[$branch] < 0 ? "(".number_format(abs($account_kewajiban_lancar_total[$branch])).")" : number_format($account_kewajiban_lancar_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
			//----------------------------------------------------------------------------------------
			//Kewajiban Jangka Panjang
			//----------------------------------------------------------------------------------------
				$print .= '	<tr><td align="left" ></td>	<td colspan="8" ></td></tr>';
				$print .= '	<tr><td align="left" ><b>Kewajiban Jangka Panjang</b></td>';
				
				//Utang Bank : 2030300
				$code = "2030300";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_2030300_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2030300_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2030300[$branch] = $account_2030300_credit[$branch] - $account_2030300_debet[$branch];
					$account_2030300_total += $account_2030300[$branch];
					$account_kewajiban_jangka_panjang_total[$branch] += $account_2030300[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Utang Bank</td>';
				$print .= '	<td align="right" class="">'.($account_2030300_total < 0 ? "(".number_format(abs($account_2030300_total)).")" : number_format($account_2030300_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_2030300[$branch] < 0 ? "(".number_format(abs($account_2030300[$branch])).")" : number_format($account_2030300[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
				//Utang Leasing : 2040000
				$code = "2040000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_2040000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2040000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_2040000[$branch] = $account_2040000_credit[$branch] - $account_2040000_debet[$branch];
					$account_2040000_total += $account_2040000[$branch];
					$account_kewajiban_jangka_panjang_total[$branch] += $account_2040000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Utang Leasing</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_2040000_total < 0 ? "(".number_format(abs($account_2040000_total)).")" : number_format($account_2040000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_2040000[$branch] < 0 ? "(".number_format(abs($account_2040000[$branch])).")" : number_format($account_2040000[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
				//Total Kewajiban Jangka Panjang
				$account_kewajiban_jangka_panjang_konsolidasi = $account_2030300_total + $account_2040000_total;
				$print .= '	<tr><td align="left" >Total Kewajiban Jangka Panjang</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_kewajiban_jangka_panjang_konsolidasi < 0 ? "(".number_format(abs($account_kewajiban_jangka_panjang_konsolidasi)).")" : number_format($account_kewajiban_jangka_panjang_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_kewajiban_jangka_panjang_total[$branch] < 0 ? "(".number_format(abs($account_kewajiban_jangka_panjang_total[$branch])).")" : number_format($account_kewajiban_jangka_panjang_total[$branch])).'</td>';
				}
				$print .= '</tr>';	
			
			//----------------------------------------------------------------------------------------
			//MODAL
			//----------------------------------------------------------------------------------------
				$print .= '	<tr><td align="left" ></td>	<td colspan="8" ></td></tr>';
				$print .= '	<tr><td align="left" ><b>MODAL</b></td>';
				
				//Simpanan Pokok : 3010102
				$code = "3010102";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_3010102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010102[$branch] = $account_3010102_credit[$branch] - $account_3010102_debet[$branch];
					$account_3010102_total += $account_3010102[$branch];
					$account_modal_total[$branch] += $account_3010102[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Simpanan Pokok</td>';
				$print .= '	<td align="right" class="">'.($account_3010102_total < 0 ? "(".number_format(abs($account_3010102_total)).")" : number_format($account_3010102_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_3010102[$branch] < 0 ? "(".number_format(abs($account_3010102[$branch])).")" : number_format($account_3010102[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Simpanan Wajib : 3010101
				$code = "3010101";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_3010101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010101[$branch] = $account_3010101_credit[$branch] - $account_3010101_debet[$branch];
					$account_3010101_total += $account_3010101[$branch];
					$account_modal_total[$branch] += $account_3010101[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Simpanan Wajib</td>';
				$print .= '	<td align="right" class="">'.($account_3010101_total < 0 ? "(".number_format(abs($account_3010101_total)).")" : number_format($account_3010101_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_3010101[$branch] < 0 ? "(".number_format(abs($account_3010101[$branch])).")" : number_format($account_3010101[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Hibah : 3010103
				$code = "3010103";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_3010103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010103[$branch] = $account_3010103_credit[$branch] - $account_3010103_debet[$branch];
					$account_3010103_total += $account_3010103[$branch];
					$account_modal_total[$branch] += $account_3010103[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hibah</td>';
				$print .= '	<td align="right" class="">'.($account_3010103_total < 0 ? "(".number_format(abs($account_3010103_total)).")" : number_format($account_3010103_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_3010103[$branch] < 0 ? "(".number_format(abs($account_3010103[$branch])).")" : number_format($account_3010103[$branch])).'</td>';
				}
				$print .= '</tr>';

				//Modal Saham Ditempatkan : 3010202
				$code = "3010202";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_3010202_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3010202_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3010202[$branch] = $account_3010202_credit[$branch] - $account_3010202_debet[$branch];
					$account_3010202_total += $account_3010202[$branch];
					$account_modal_total[$branch] += $account_3010202[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Modal Saham Ditempatkan</td>';
				$print .= '	<td align="right" class="">'.($account_3010202_total < 0 ? "(".number_format(abs($account_3010202_total)).")" : number_format($account_3010202_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_3010202[$branch] < 0 ? "(".number_format(abs($account_3010202[$branch])).")" : number_format($account_3010202[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Laba Ditahan : 3020001, 3020002
				$code = "3020001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_3020001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3020001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3020001[$branch] = $account_3020001_credit[$branch] - $account_3020001_debet[$branch];
					$account_3020001_total += $account_3020001[$branch];
					$account_laba_ditahan_total[$branch] += $account_3020001[$branch];
					$account_modal_total[$branch] += $account_3020001[$branch];
				}
				$code = "3020002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_3020002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_3020002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_3020002[$branch] = $account_3020002_credit[$branch] - $account_3020002_debet[$branch];
					$account_3020002_total += $account_3020002[$branch];
					$account_laba_ditahan_total[$branch] += $account_3020002[$branch];
					$account_modal_total[$branch] += $account_3020002[$branch];
				}
				$account_laba_ditahan_konsolidasi = $account_3020001_total + $account_3020002_total ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Modal Saham Ditempatkan</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_laba_ditahan_konsolidasi < 0 ? "(".number_format(abs($account_laba_ditahan_konsolidasi)).")" : number_format($account_laba_ditahan_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_laba_ditahan_total[$branch] < 0 ? "(".number_format(abs($account_laba_ditahan_total[$branch])).")" : number_format($account_laba_ditahan_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				$account_modal_konsolidasi = $account_3010101_total + $account_3010102_total + $account_3010103_total + $account_3010202_total + $account_laba_ditahan_konsolidasi;
				$print .= '	<tr><td align="left" >Total Modal</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_modal_konsolidasi < 0 ? "(".number_format(abs($account_modal_konsolidasi)).")" : number_format($account_modal_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_modal_total[$branch] < 0 ? "(".number_format(abs($account_modal_total[$branch])).")" : number_format($account_modal_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				$print .= '	<tr><td align="left" ></td>	<td colspan="8" class="border_btm"></td></tr>';
				$account_kewajiban_modal_konsolidasi = $account_kewajiban_lancar_konsolidasi + $account_kewajiban_jangka_panjang_konsolidasi + $account_modal_konsolidasi;
				$print .= '	<tr><td align="left" >TOTAL KEWAJIBAN & MODAL</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_kewajiban_modal_konsolidasi < 0 ? "(".number_format(abs($account_kewajiban_modal_konsolidasi)).")" : number_format($account_kewajiban_modal_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_kewajiban_modal_total[$branch]= $account_kewajiban_lancar_total[$branch] + $account_kewajiban_jangka_panjang_total[$branch] + $account_modal_total[$branch];
					$print .= '	<td align="right" class="border_btm">'.($account_kewajiban_modal_total[$branch] < 0 ? "(".number_format(abs($account_kewajiban_modal_total[$branch])).")" : number_format($account_kewajiban_modal_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
			$this->template	->set('menu_title', 'Laporan Keuangan - Neraca')
							->set('menu_konsolidasi', 'active')
							->set('print', $print)
							->build('accounting/neraca_konsolidasi');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	//NERACA
	public function neraca_old()
	{
		if($this->session->userdata('logged_in'))
		{
			//Cek User Branch
			$user_branch = $this->session->userdata('user_branch');
			if($this->input->post('branch')){
				$user_branch = $this->input->post('branch');
			}
			////if($user_branch == "0"){ $user_branch=NULL;}
			
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
			
			$date_end_before = strtotime($date_start);
			$date_end_before = strtotime("-1 day", $date_end_before);
			$date_end_before = date('Y-m-d', $date_end_before);	 


			$year_start_before = explode("-",$date_start);	 
			$year_start_before = $year_start_before[0];//echo $year_start_before;
			$date_start_before = $year_start_before."-01-01"; 
			//$date_start_before = "2015-01-01"; 

			
			$grand_total_debet=0;
			$grand_total_credit=0;
			$grand_total_before=0;
			
				
			//MODAL
			$accounting = $this->accounting_model->get_all_accounting_modal()->result();
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
					
					$account_saldo_before = $account_credit_before - $account_debet_before;				
					$account_saldo = $account_saldo_before - $account_debet + $account_credit + $laba_rugi;
					
					/*$neraca .= '<tr>     
								<td align="left" ><b>'.$c->accounting_code." ".$c->accounting_name.'</b></td>	
								<td class="text-right"><b>'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</b></td>
								<td class="text-right"><b>'.number_format($account_debet).'</b></td>
								<td class="text-right"><b>'.number_format($account_credit).'</b></td>
								<td class="text-right"><b>'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</b></td>								
								</tr>';*/
					$neraca .= '<tr>     
								<td align="left" ><b>'.$c->accounting_code." ".$c->accounting_name.'</b></td>	
								<td class="text-right"><b></b></td>
								<td class="text-right"><b></b></td>
								<td class="text-right"><b></b></td>
								<td class="text-right"><b></b></td> 								
								</tr>';
				}elseif($haschild == "1" AND $parent != "0"){
					//LEVEL 2					
					$account_debet = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start,$date_end,$user_branch);
					$account_credit = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start,$date_end,$user_branch);

					$account_debet_before = $this->jurnal_model->sum_account_parent_debet_by_date($code_level2,$date_start_before,$date_end_before,$user_branch);
					$account_credit_before = $this->jurnal_model->sum_account_parent_credit_by_date($code_level2,$date_start_before,$date_end_before,$user_branch);
					
					$account_saldo_before = $account_credit_before - $account_debet_before ;
					
					$account_saldo = $account_saldo_before - $account_debet + $account_credit;
					
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
					
					$account_saldo_before = $account_credit_before - $account_debet_before;
					
					if($c->accounting_code == "3020002"){
						$account_saldo_before = $laba_rugi_before;
						$account_saldo_before = 0;
						$account_credit = $laba_rugi;
					}elseif($c->accounting_code == "3020001"){
						$account_saldo_before = $account_credit_before - $account_debet_before;
					}
					$account_saldo = $account_saldo_before + $account_credit - $account_debet;
					//grand total dihitung dari total account level 3
					$grand_total_debet += $account_debet;
					$grand_total_credit += $account_credit;
					$grand_total_before += $account_saldo_before;
					  
						$grand_total_modal_debet += $account_debet;
						$grand_total_modal_credit += $account_credit;
						$grand_total_modal_before += $account_saldo_before;					
					
					//if($c->accounting_code == "3020001"){ echo "<b>$account_debet --- $account_credit</b>";}
					
					$neraca .= '<tr>     
								<td align="left" >  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$c->accounting_code." ".$c->accounting_name.'</td>	
								<td class="text-right">'.($account_saldo_before < 0 ? "(".number_format(abs($account_saldo_before)).")" : number_format($account_saldo_before)).'</td>
								<td class="text-right">'.($account_debet < 0 ? "(".number_format(abs($account_debet)).")" : number_format($account_debet)).'</td>
								<td class="text-right">'.($account_credit < 0 ? "(".number_format(abs($account_credit)).")" : number_format($account_credit)).'</td>
								<td class="text-right">'.($account_saldo < 0 ? "(".number_format(abs($account_saldo)).")" : number_format($account_saldo)).'</td>
								</tr>';					
				}	
				$code_level0_old = $code_level0;
			endforeach; 
				$grand_total_modal_saldo = $grand_total_modal_before-$grand_total_modal_debet+$grand_total_modal_credit;
				$neraca .= '<tr bgcolor="#eee">     
							<td align="left" ><b>TOTAL MODAL</b></td>	
							<td class="text-right"><b>'.($grand_total_modal_before < 0 ? "(".number_format(abs($grand_total_modal_before)).")" : number_format($grand_total_modal_before)).'</b></td>
							<td class="text-right"><b>'.($grand_total_modal_debet < 0 ? "(".number_format(abs($grand_total_modal_debet)).")" : number_format($grand_total_modal_debet)).'</b></td>
							<td class="text-right"><b>'.($grand_total_modal_credit < 0 ? "(".number_format(abs($grand_total_modal_credit)).")" : number_format($grand_total_modal_credit)).'</b></td>
							<td class="text-right"><b>'.($grand_total_modal_saldo < 0 ? "(".number_format(abs($grand_total_modal_saldo)).")" : number_format($grand_total_modal_saldo)).'</b></td>
							</tr>';	
				
				
				
				//-----------	
				//GRAND TOTAL
				//-----------				
				$grand_total_saldo = $grand_total_aktiva_saldo - $grand_total_kewajiban_saldo - $grand_total_modal_saldo;
				$grand_total_before = $grand_total_aktiva_before - $grand_total_kewajiban_before - $grand_total_modal_before;
					$grand_total_before =0;	
					$grand_total_debet =0;	
					$grand_total_credit =0;						
				$neraca .= '<tfoot bgcolor="#ddd"><tr">     
							<td align="left" ><b>GRAND TOTAL</b></td>	
							<td class="text-right"><b>'.($grand_total_before < 0 ? "(".number_format(abs($grand_total_before)).")" : number_format($grand_total_before)).'</b></td>
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
	
	//CASH FLOW
	public function cashflow()
	{
		if($this->session->userdata('logged_in'))
		{
			$total_branch = $this->branch_model->count_branch();
			
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
				//$date_start =$week_today[0];
				$date_start = "2015-01-01";
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
			
			//----------------------------------------------------------------------------------------
			// ARUS KAS OPERASI
			//----------------------------------------------------------------------------------------
			
			$print .= '	<tr><td align="left" ><b>LAPORAN ARUS KAS</b></td>	<td colspan="8" ></td></tr>';
			$print .= '	<tr><td align="left" ><b>ARUS KAS OPERASI</b></td>	<td colspan="8" ></td></tr>';
			
								
				//4010000 Bagi Hasil
				$code = "4010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4010000[$branch] = $account_4010000_credit[$branch] - $account_4010000_debet[$branch];
					$account_4010000_total += $account_4010000[$branch];
					$account_arus_kas_operasi_total[$branch] += $account_4010000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bagi Hasil</td>';
				$print .= '	<td align="right" class="">'.($account_4010000_total < 0 ? "(".number_format(abs($account_4010000_total)).")" : number_format($account_4010000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_4010000[$branch] < 0 ? "(".number_format(abs($account_4010000[$branch])).")" : number_format($account_4010000[$branch])).'</td>';
				}
				$print .= '</tr>';	
				

				
				
				//Pembiayaan 1030100, 1030200, 1030300 
				
				$code = "1030100";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1030100_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					//$account_1030100_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030100[$branch] = $account_1030100_debet[$branch];
					$account_1030100_total += $account_1030100[$branch];
					$account_penyaluran_pembiayaan_total[$branch] += $account_1030100[$branch];
					$account_arus_kas_operasi_total[$branch] += $account_1030100[$branch];
					
				}
				$code = "1030200";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1030200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					//$account_1030200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030200[$branch] = $account_1030200_debet[$branch];
					$account_1030200_total += $account_1030200[$branch];
					$account_penyaluran_pembiayaan_total[$branch] += $account_1030200[$branch];
					$account_arus_kas_operasi_total[$branch] += $account_1030200[$branch];
					
				}
				$code = "1030300";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_1030300_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					//$account_1030300_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030300[$branch] = $account_1030300_debet[$branch] ;
					$account_1030300_total += $account_1030300[$branch];
					$account_penyaluran_pembiayaan_total[$branch] += $account_1030300[$branch];
					$account_arus_kas_operasi_total[$branch] += $account_1030300[$branch];
					
				}			
				
				$account_penyaluran_pembiayaan_konsolidasi = $account_1030100_total + $account_1030200_total + $account_1030300_total ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Penyaluran Pembiayaan</td>';
				$print .= '	<td align="right" class="">'.($account_penyaluran_pembiayaan_konsolidasi < 0 ? "(".number_format(abs($account_penyaluran_pembiayaan_konsolidasi)).")" : number_format($account_penyaluran_pembiayaan_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_penyaluran_pembiayaan_total[$branch] < 0 ? "(".number_format(abs($account_penyaluran_pembiayaan_total[$branch])).")" : number_format($account_penyaluran_pembiayaan_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//Angsuran Pokok 1030100, 1030200, 1030300 
				
				$code = "1030100";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					//$account_1030100_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030100_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030100[$branch] = $account_1030100_credit[$branch];
					$account_1030100_total += $account_1030100[$branch];
					$account_angsuran_pokok_total[$branch] += $account_1030100[$branch];
					$account_arus_kas_operasi_total[$branch] += $account_1030100[$branch];
					
				}
				$code = "1030200";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					//$account_1030200_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030200_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030200[$branch] = $account_1030200_credit[$branch];
					$account_1030200_total += $account_1030200[$branch];
					$account_angsuran_pokok_total[$branch] += $account_1030200[$branch];
					$account_arus_kas_operasi_total[$branch] += $account_1030200[$branch];
					
				}
				$code = "1030300";
				$code_level1 = substr($code,0,5);
				for($branch=0; $branch <= $total_branch; $branch++){
					//$account_1030300_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030300_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_1030300[$branch] = $account_1030300_credit[$branch];
					$account_1030300_total += $account_1030300[$branch];
					$account_angsuran_pokok_total[$branch] += $account_1030300[$branch];
					$account_arus_kas_operasi_total[$branch] += $account_1030300[$branch];
					
				}			
				
				$account_angsuran_pokok_konsolidasi = $account_1030100_total + $account_1030200_total + $account_1030300_total ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Angsuran Pokok</td>';
				$print .= '	<td align="right" class="">'.($account_angsuran_pokok_konsolidasi < 0 ? "(".number_format(abs($account_angsuran_pokok_konsolidasi)).")" : number_format($account_angsuran_pokok_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_angsuran_pokok_total[$branch] < 0 ? "(".number_format(abs($account_angsuran_pokok_total[$branch])).")" : number_format($account_angsuran_pokok_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//4020000 Pendapatan Lain-lain
				$code = "4020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4020000[$branch] = $account_4020000_credit[$branch] - $account_4020000_debet[$branch];
					$account_4020000_total += $account_4020000[$branch];
					$account_arus_kas_operasi_total[$branch] += $account_4020000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Lain-lain</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_4020000_total < 0 ? "(".number_format(abs($account_4020000_total)).")" : number_format($account_4020000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_4020000[$branch] < 0 ? "(".number_format(abs($account_4020000[$branch])).")" : number_format($account_4020000[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
			$this->template	->set('menu_title', 'Laporan Keuangan - Arus Kas')
							->set('menu_konsolidasi', 'active')
							->set('print', $print)
							->build('accounting/neraca_konsolidasi');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	
	//LABA RUGI
	public function shu()
	{
		if($this->session->userdata('logged_in'))
		{
			
			$total_branch = $this->branch_model->count_branch();

			
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
				//$date_start =$week_today[0];
				$date_start = "2015-01-01";
				$date_end   = date("Y-m-d");			
			}
			
			$date_end_before = strtotime($date_start);
			//$date_end_before = $date_start;
			$date_end_before = strtotime("-1 day", $date_end_before); 
			$date_end_before = date('Y-m-d', $date_end_before);			
			$date_start_before = "2014-01-01";
			//echo $date_start_before." ----- ".$date_end_before; 
			
				
				$account_debet = 0;
				$account_credit = 0;
				$account_saldo = 0;
				$saldo_awal =0;
				
			//PENDAPATAN
			$print .= '	<tr><td align="left" ><b>Pendapatan</b></td>	<td colspan="8" ></td></tr>';
			
								
				//4010000 Pendapatan Pembiayaan
				$code = "4010000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4010000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4010000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4010000[$branch] = $account_4010000_credit[$branch] - $account_4010000_debet[$branch];
					$account_4010000_total += $account_4010000[$branch];
					$account_pendapatan_total[$branch] += $account_4010000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Pembiayaan</td>';
				$print .= '	<td align="right" class="">'.($account_4010000_total < 0 ? "(".number_format(abs($account_4010000_total)).")" : number_format($account_4010000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_4010000[$branch] < 0 ? "(".number_format(abs($account_4010000[$branch])).")" : number_format($account_4010000[$branch])).'</td>';
				}
				$print .= '</tr>';	
				
				
				//4020000 Pendapatan Jasa Administrasi
				$code = "4020000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4020000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4020000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_4020000[$branch] = $account_4020000_credit[$branch] - $account_4020000_debet[$branch];
					$account_4020000_total += $account_4020000[$branch];
					$account_pendapatan_total[$branch] += $account_4020000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Administrasi</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_4020000_total < 0 ? "(".number_format(abs($account_4020000_total)).")" : number_format($account_4020000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_4020000[$branch] < 0 ? "(".number_format(abs($account_4020000[$branch])).")" : number_format($account_4020000[$branch])).'</td>';
				}
				$print .= '</tr>';	
							
				//Jumlah Pendapatan			
				$account_pendapatan_konsolidasi = $account_4010000_total + $account_4020000_total;
				$print .= '	<tr><td align="left" >Total Pendapatan</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_konsolidasi < 0 ? "(".number_format(abs($account_pendapatan_konsolidasi)).")" : number_format($account_pendapatan_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_total[$branch] < 0 ? "(".number_format(abs($account_pendapatan_total[$branch])).")" : number_format($account_pendapatan_total[$branch])).'</td>';
				}
				$print .= '	</tr>';	
				
				//---------------------------------------------------------------------------------------------
				//---------------------------------------------------------------------------------------------
				//---------------------------------------------------------------------------------------------
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Beban Pembiayaan</b></td>	<td colspan="8" ></td></tr>';

				//---------------------------------------------------------------------------------------------
				//BEBAN OPERASI
				//---------------------------------------------------------------------------------------------
				
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Biaya Operasi</b></td>	<td colspan="8" ></td></tr>';
				
				//Beban Lainnya: 5030101, 5030102, 5030103, 5030104, 5030105, 5030106, 5030108, 5030109, 5030110, 5030111, 5030112, 5030113, 5030114 
				
				$code = "5030101";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030101[$branch] = $account_5030101_debet[$branch] - $account_5030101_credit[$branch];
					$account_5030101_total += $account_5030101[$branch];
					$account_beban_gaji_total[$branch] += $account_5030101[$branch];
					$account_beban_operasi_total[$branch] += $account_5030101[$branch];
					
				}
				$code = "5030102";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030102[$branch] = $account_5030102_debet[$branch] - $account_5030102_credit[$branch];
					$account_5030102_total += $account_5030102[$branch];
					$account_beban_gaji_total[$branch] += $account_5030102[$branch];
					$account_beban_operasi_total[$branch] += $account_5030102[$branch];
					
				}
				$code = "5030103";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030103[$branch] = $account_5030103_debet[$branch] - $account_5030103_credit[$branch];
					$account_5030103_total += $account_5030103[$branch];
					$account_beban_gaji_total[$branch] += $account_5030103[$branch];
					$account_beban_operasi_total[$branch] += $account_5030103[$branch];
					
				}					
				$code = "5030104";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030104_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030104_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030104[$branch] = $account_5030104_debet[$branch] - $account_5030104_credit[$branch];
					$account_5030104_total += $account_5030104[$branch];
					$account_beban_gaji_total[$branch] += $account_5030104[$branch];
					$account_beban_operasi_total[$branch] += $account_5030104[$branch];
					
				}
				$code = "5030105";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030105_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030105_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030105[$branch] = $account_5030105_debet[$branch] - $account_5030105_credit[$branch];
					$account_5030105_total += $account_5030105[$branch];
					$account_beban_gaji_total[$branch] += $account_5030105[$branch];
					$account_beban_operasi_total[$branch] += $account_5030105[$branch];
					
				}
				
				$code = "5030106";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030106_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030106_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030106[$branch] = $account_5030106_debet[$branch] - $account_5030106_credit[$branch];
					$account_5030106_total += $account_5030106[$branch];
					$account_beban_gaji_total[$branch] += $account_5030106[$branch];
					$account_beban_operasi_total[$branch] += $account_5030106[$branch];
					
				}
				$code = "5030108";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030108_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030108_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030108[$branch] = $account_5030108_debet[$branch] - $account_5030108_credit[$branch];
					$account_5030108_total += $account_5030108[$branch];
					$account_beban_gaji_total[$branch] += $account_5030108[$branch];
					$account_beban_operasi_total[$branch] += $account_5030108[$branch];
					
				}
				$code = "5030109";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030109_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030109_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030109[$branch] = $account_5030109_debet[$branch] - $account_5030109_credit[$branch];
					$account_5030109_total += $account_5030109[$branch];
					$account_beban_gaji_total[$branch] += $account_5030109[$branch];
					$account_beban_operasi_total[$branch] += $account_5030109[$branch];
					
				}
				$code = "5030110";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030110_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030110_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030110[$branch] = $account_5030110_debet[$branch] - $account_5030110_credit[$branch];
					$account_5030110_total += $account_5030110[$branch];
					$account_beban_gaji_total[$branch] += $account_5030110[$branch];
					$account_beban_operasi_total[$branch] += $account_5030110[$branch];
					
				}					
				$code = "5030111";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030111_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030111_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030111[$branch] = $account_5030111_debet[$branch] - $account_5030111_credit[$branch];
					$account_5030111_total += $account_5030111[$branch];
					$account_beban_gaji_total[$branch] += $account_5030111[$branch];
					$account_beban_operasi_total[$branch] += $account_5030111[$branch];
					
				}
				$code = "5030112";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030112_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030112_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030112[$branch] = $account_5030112_debet[$branch] - $account_5030112_credit[$branch];
					$account_5030112_total += $account_5030112[$branch];
					$account_beban_gaji_total[$branch] += $account_5030112[$branch];
					$account_beban_operasi_total[$branch] += $account_5030112[$branch];
					
				}
				
				$code = "5030113";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030113_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030113_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030113[$branch] = $account_5030113_debet[$branch] - $account_5030113_credit[$branch];
					$account_5030113_total += $account_5030113[$branch];
					$account_beban_gaji_total[$branch] += $account_5030113[$branch];
					$account_beban_operasi_total[$branch] += $account_5030113[$branch];
					
				}
				
				
				$code = "5030114";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030114_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030114_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030114[$branch] = $account_5030114_debet[$branch] - $account_5030114_credit[$branch];
					$account_5030114_total += $account_5030114[$branch];
					$account_beban_gaji_total[$branch] += $account_5030114[$branch];
					$account_beban_operasi_total[$branch] += $account_5030114[$branch];
					
				}
				
				$account_beban_gaji_konsolidasi = $account_5030101_total + $account_5030102_total + $account_5030103_total + $account_5030104_total + $account_5030105_total  + $account_5030106_total + $account_5030108_total + $account_5030109_total + $account_5030110_total + $account_5030111_total + $account_5030112_total  + $account_5030113_total + $account_5030114_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Gaji & Honor</td>';
				$print .= '	<td align="right" class="">'.($account_beban_gaji_konsolidasi < 0 ? "(".number_format(abs($account_beban_gaji_konsolidasi)).")" : number_format($account_beban_gaji_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_gaji_total[$branch] < 0 ? "(".number_format(abs($account_beban_gaji_total[$branch])).")" : number_format($account_beban_gaji_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//5080404 Beban Asuransi Jiwa
				$code = "5080404";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080404_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080404_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080404[$branch] = $account_5080404_debet[$branch] - $account_5080404_credit[$branch];
					$account_5080404_total += $account_5080404[$branch];
					$account_beban_operasi_total[$branch] += $account_5080404[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Asuransi Jiwa</td>';
				$print .= '	<td align="right" class="">'.($account_5080404_total < 0 ? "(".number_format(abs($account_5080404_total)).")" : number_format($account_5080404_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5080404[$branch] < 0 ? "(".number_format(abs($account_5080404[$branch])).")" : number_format($account_5080404[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//5040003 Beban Rekrutmen
				$code = "5040003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5040003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5040003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5040003[$branch] = $account_5040003_debet[$branch] - $account_5040003_credit[$branch];
					$account_5040003_total += $account_5040003[$branch];
					$account_beban_operasi_total[$branch] += $account_5040003[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Rekrutmen</td>';
				$print .= '	<td align="right" class="">'.($account_5040003_total < 0 ? "(".number_format(abs($account_5040003_total)).")" : number_format($account_5040003_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5040003[$branch] < 0 ? "(".number_format(abs($account_5040003[$branch])).")" : number_format($account_5040003[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Training
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Training</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//5030107 Insentif Operations
				$code = "5030107";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5030107_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5030107_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5030107[$branch] = $account_5030107_debet[$branch] - $account_5030107_credit[$branch];
					$account_5030107_total += $account_5030107[$branch];
					$account_beban_operasi_total[$branch] += $account_5030107[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Insentif Operations</td>';
				$print .= '	<td align="right" class="">'.($account_5030107_total < 0 ? "(".number_format(abs($account_5030107_total)).")" : number_format($account_5030107_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5030107[$branch] < 0 ? "(".number_format(abs($account_5030107[$branch])).")" : number_format($account_5030107[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//5080501 Beban Rumah Tangga Pusat
				$code = "5080501";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080501_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080501_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080501[$branch] = $account_5080501_debet[$branch] - $account_5080501_credit[$branch];
					$account_5080501_total += $account_5080501[$branch];
					$account_beban_RT_pusat_total[$branch] += $account_5080501[$branch];
					$account_beban_operasi_total[$branch] += $account_5080501[$branch];
					
				}
				$code = "5080301";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080301_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080301_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080301[$branch] = $account_5080301_debet[$branch] - $account_5080301_credit[$branch];
					$account_5080301_total += $account_5080301[$branch];
					$account_beban_RT_pusat_total[$branch] += $account_5080301[$branch];
					$account_beban_operasi_total[$branch] += $account_5080301[$branch];
					
				}				
				
				$account_beban_RT_pusat_konsolidasi = $account_5080501_total + $account_5080301_total;
				$account_beban_RT_cabang_konsolidasi = $account_beban_RT_pusat_konsolidasi - $account_beban_RT_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Rumah Tangga Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_RT_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_RT_pusat_total[0])).")" : number_format($account_beban_RT_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_RT_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_RT_pusat_total[0])).")" : number_format($account_beban_RT_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				
				
				//Beban ATK Pusat : 5080201, 5080202, 5080203, 5080401
				$code = "5080201";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080201_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080201_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080201[$branch] = $account_5080201_debet[$branch] - $account_5080201_credit[$branch];
					$account_5080201_total += $account_5080201[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080201[$branch];
					$account_beban_operasi_total[$branch] += $account_5080201[$branch];
					
				}
				$code = "5080202";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080202_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080202_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080202[$branch] = $account_5080202_debet[$branch] - $account_5080202_credit[$branch];
					$account_5080202_total += $account_5080202[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080202[$branch];
					$account_beban_operasi_total[$branch] += $account_5080202[$branch];
					
				}
				$code = "5080203";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080203_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080203_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080203[$branch] = $account_5080203_debet[$branch] - $account_5080203_credit[$branch];
					$account_5080203_total += $account_5080203[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080203[$branch];
					$account_beban_operasi_total[$branch] += $account_5080203[$branch];
					
				}
				$code = "5080401";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080401_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080401_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080401[$branch] = $account_5080401_debet[$branch] - $account_5080401_credit[$branch];
					$account_5080401_total += $account_5080401[$branch];
					$account_beban_ATK_pusat_total[$branch] += $account_5080401[$branch];
					$account_beban_operasi_total[$branch] += $account_5080401[$branch];
					
				}				
				
				$account_beban_ATK_pusat_konsolidasi = $account_5080201_total + $account_5080202_total + $account_5080203_total + $account_5080401_total;
				$account_beban_ATK_cabang_konsolidasi = $account_beban_ATK_pusat_konsolidasi - $account_beban_ATK_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban ATK Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_ATK_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_ATK_pusat_total[0])).")" : number_format($account_beban_ATK_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_ATK_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_ATK_pusat_total[0])).")" : number_format($account_beban_ATK_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				
				//Beban Transportasi Pusat : 5080302, 5080303, 5080304

				$code = "5080302";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080302_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080302_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080302[$branch] = $account_5080302_debet[$branch] - $account_5080302_credit[$branch];
					$account_5080302_total += $account_5080302[$branch];
					$account_beban_transportasi_pusat_total[$branch] += $account_5080302[$branch];
					$account_beban_operasi_total[$branch] += $account_5080302[$branch];
					
				}
				$code = "5080303";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080303_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080303_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080303[$branch] = $account_5080303_debet[$branch] - $account_5080303_credit[$branch];
					$account_5080303_total += $account_5080303[$branch];
					$account_beban_transportasi_pusat_total[$branch] += $account_5080303[$branch];
					$account_beban_operasi_total[$branch] += $account_5080303[$branch];
					
				}
				$code = "5080304";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080304_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080304_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080304[$branch] = $account_5080304_debet[$branch] - $account_5080304_credit[$branch];
					$account_5080304_total += $account_5080304[$branch];
					$account_beban_transportasi_pusat_total[$branch] += $account_5080304[$branch];
					$account_beban_operasi_total[$branch] += $account_5080304[$branch];
					
				}					
				
				$account_beban_transportasi_pusat_konsolidasi = $account_5080302_total + $account_5080303_total + $account_5080304_total ;
				$account_beban_transportasi_cabang_konsolidasi = $account_beban_transportasi_pusat_konsolidasi - $account_beban_transportasi_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Transportasi Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_transportasi_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_transportasi_pusat_total[0])).")" : number_format($account_beban_transportasi_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_transportasi_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_transportasi_pusat_total[0])).")" : number_format($account_beban_transportasi_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				
				//Beban Perawatan Pusat
				$code = "5060000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5060000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5060000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5060000[$branch] = $account_5060000_debet[$branch] - $account_5060000_credit[$branch];
					$account_5060000_total += $account_5060000[$branch];
					$account_beban_operasi_total[$branch] += $account_5060000[$branch];	
					
				}
				$account_5060000_cabang = $account_5060000_total - $account_5060000[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Perawatan Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_5060000[0]  < 0 ? "(".number_format(abs($account_5060000[0] )).")" : number_format($account_5060000[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_5060000[0]  < 0 ? "(".number_format(abs($account_5060000[0] )).")" : number_format($account_5060000[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				//Beban Lain-lain : 5080101, 5080102, 5080103

				$code = "5080101";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080101_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080101_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080101[$branch] = $account_5080101_debet[$branch] - $account_5080101_credit[$branch];
					$account_5080101_total += $account_5080101[$branch];
					$account_beban_listrik_air_telp_pusat_total[$branch] += $account_5080101[$branch];
					$account_beban_operasi_total[$branch] += $account_5080101[$branch];
					
				}
				$code = "5080102";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080102_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080102_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080102[$branch] = $account_5080102_debet[$branch] - $account_5080102_credit[$branch];
					$account_5080102_total += $account_5080102[$branch];
					$account_beban_listrik_air_telp_pusat_total[$branch] += $account_5080102[$branch];
					$account_beban_operasi_total[$branch] += $account_5080102[$branch];
					
				}
				$code = "5080103";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080103_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080103_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080103[$branch] = $account_5080103_debet[$branch] - $account_5080103_credit[$branch];
					$account_5080103_total += $account_5080103[$branch];
					$account_beban_listrik_air_telp_pusat_total[$branch] += $account_5080103[$branch];
					$account_beban_operasi_total[$branch] += $account_5080103[$branch];
					
				}					
				
				$account_beban_listrik_air_telp_pusat_konsolidasi = $account_5080101_total + $account_5080102_total + $account_5080103_total ;
				$account_beban_listrik_air_telp_cabang_konsolidasi = $account_beban_listrik_air_telp_pusat_konsolidasi - $account_beban_listrik_air_telp_pusat_total[0] ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Listrik, Air, Telepon dan Internet Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_pusat_total[0])).")" : number_format($account_beban_listrik_air_telp_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_pusat_total[0])).")" : number_format($account_beban_listrik_air_telp_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				//Beban Lain-lain Pusat : 5080104, 5080402, 5080403, 5080406, 5080502, 5080503, 5080504
				
				$code = "5080104";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080104_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080104_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080104[$branch] = $account_5080104_debet[$branch] - $account_5080104_credit[$branch];
					$account_5080104_total += $account_5080104[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080104[$branch];
					$account_beban_operasi_total[$branch] += $account_5080104[$branch];
					
				}
				$code = "5080402";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080402_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080402_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080402[$branch] = $account_5080402_debet[$branch] - $account_5080402_credit[$branch];
					$account_5080402_total += $account_5080402[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080402[$branch];
					$account_beban_operasi_total[$branch] += $account_5080402[$branch];
					
				}
				$code = "5080403";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080403_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080403_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080403[$branch] = $account_5080403_debet[$branch] - $account_5080403_credit[$branch];
					$account_5080403_total += $account_5080403[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080403[$branch];
					$account_beban_operasi_total[$branch] += $account_5080403[$branch];
					
				}					
				$code = "5080406";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080406_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080406_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080406[$branch] = $account_5080406_debet[$branch] - $account_5080406_credit[$branch];
					$account_5080406_total += $account_5080406[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080406[$branch];
					$account_beban_operasi_total[$branch] += $account_5080406[$branch];
					
				}
				$code = "5080502";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080502_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080502_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080502[$branch] = $account_5080502_debet[$branch] - $account_5080502_credit[$branch];
					$account_5080502_total += $account_5080502[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080502[$branch];
					$account_beban_operasi_total[$branch] += $account_5080502[$branch];
					
				}
				$code = "5080503";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080503_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080503_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080503[$branch] = $account_5080503_debet[$branch] - $account_5080503_credit[$branch];
					$account_5080503_total += $account_5080503[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080503[$branch];
					$account_beban_operasi_total[$branch] += $account_5080503[$branch];
					
				}
				$code = "5080504";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080504_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080504_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080504[$branch] = $account_5080504_debet[$branch] - $account_5080504_credit[$branch];
					$account_5080504_total += $account_5080504[$branch];
					$account_beban_lainlain_pusat_total[$branch] += $account_5080504[$branch];
					$account_beban_operasi_total[$branch] += $account_5080504[$branch];
					
				}
				$account_beban_lainlain_pusat_konsolidasi = $account_5080104_total + $account_5080402_total + $account_5080403_total + $account_5080406_total + $account_5080502_total + $account_5080503_total + $account_5080504_total;
				$account_beban_lainlain_cabang_konsolidasi = $account_beban_lainlain_pusat_konsolidasi - $account_beban_lainlain_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Lain-lain Pusat</td>';
				$print .= '	<td align="right" class="">'.($account_beban_lainlain_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_lainlain_pusat_total[0])).")" : number_format($account_beban_lainlain_pusat_total[0])).'</td>';
				$print .= '	<td align="right" class="">'.($account_beban_lainlain_pusat_total[0] < 0 ? "(".number_format(abs($account_beban_lainlain_pusat_total[0])).")" : number_format($account_beban_lainlain_pusat_total[0])).'</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">0</td>';
				}
				$print .= '</tr>';
				
				//Beban Rumah Tangga Seluruh Cabang
				$account_beban_RT_cabang_konsolidasi = $account_beban_RT_pusat_konsolidasi - $account_beban_RT_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Rumah Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_RT_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_RT_cabang_konsolidasi)).")"  : number_format($account_beban_RT_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=1; $total_branch <= 0; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_RT_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_RT_pusat_total[$branch])).")" : number_format($account_beban_RT_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban ATK Seluruh Cabang
				$account_beban_ATK_cabang_konsolidasi = $account_beban_ATK_pusat_konsolidasi - $account_beban_ATK_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban ATK Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_ATK_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_ATK_cabang_konsolidasi)).")" : number_format($account_beban_ATK_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_ATK_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_ATK_pusat_total[$branch])).")" : number_format($account_beban_ATK_pusat_total[$branch])).'</td>';
				}
				
				
				//Beban Transportasi Seluruh Cabang
				$account_beban_transportasi_cabang_konsolidasi = $account_beban_transportasi_pusat_konsolidasi - $account_beban_transportasi_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Transportasi Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_transportasi_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_transportasi_cabang_konsolidasi)).")"  : number_format($account_beban_transportasi_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_transportasi_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_transportasi_pusat_total[$branch])).")" : number_format($account_beban_transportasi_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Perawatan Seluruh Cabang
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Perawatan Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_5060000_cabang < 0 ? "(".number_format(abs($account_5060000_cabang)).")" : number_format($account_5060000_cabang)).'</td>';
				$print .= '	<td align="right" class="">0</td>';	
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5060000[$branch] < 0 ? "(".number_format(abs($account_5060000[$branch])).")" : number_format($account_5060000[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Listrik, Air, Telepon dan Internet Seluruh Cabang
				$account_beban_listrik_air_telp_cabang_konsolidasi = $account_beban_listrik_air_telp_pusat_konsolidasi - $account_beban_listrik_air_telp_pusat_total[0] ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Listrik, Air, Telepon dan Internet Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_cabang_konsolidasi)).")" : number_format($account_beban_listrik_air_telp_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';	
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_listrik_air_telp_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_listrik_air_telp_pusat_total[$branch])).")" : number_format($account_beban_listrik_air_telp_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Lain-lain Seluruh Cabang
				$account_beban_lainlain_cabang_konsolidasi = $account_beban_lainlain_pusat_konsolidasi - $account_beban_lainlain_pusat_total[0];
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Lain-lain Seluruh Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_lainlain_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_lainlain_cabang_konsolidasi)).")" : number_format($account_beban_lainlain_cabang_konsolidasi)).'</td>';
				$print .= '	<td align="right" class="">0</td>';	
				for($branch=1; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_lainlain_pusat_total[$branch] < 0 ? "(".number_format(abs($account_beban_lainlain_pusat_total[$branch])).")" : number_format($account_beban_lainlain_pusat_total[$branch])).'</td>';
				}
				
				
				//Beban Cleaning Service
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Cleaning Service</td>';
				$print .= '	<td align="right" class="">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Keamanan dan Kebersihan
				$code = "5080405";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5080405_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5080405_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5080405[$branch] = $account_5080405_debet[$branch] - $account_5080405_credit[$branch];
					$account_5080405_total += $account_5080405[$branch];
					$account_biaya_langsung_total[$branch] += $account_5080405[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Keamanan dan Kebersihan</td>';
				$print .= '	<td align="right" class="">'.($account_5080405_total < 0 ? "(".number_format(abs($account_5080405_total)).")" : number_format($account_5080405_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5080405[$branch] < 0 ? "(".number_format(abs($account_5080405[$branch])).")" : number_format($account_5080405[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Penyusutan
				$code = "5070000";
				$code_level1 = substr($code,0,3);
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5070000_debet[$branch]  = $this->jurnal_model->sum_account_parent_debet_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5070000_credit[$branch] = $this->jurnal_model->sum_account_parent_credit_by_date($code_level1,$date_start,$date_end,$branch);
					$account_5070000[$branch] = $account_5070000_debet[$branch] - $account_5070000_credit[$branch];
					$account_5070000_total += $account_5070000[$branch];
					$account_biaya_operasi_total[$branch] += $account_5070000[$branch];
					
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Penyusutan</td>';
				$print .= '	<td align="right" class="">'.($account_5070000_total < 0 ? "(".number_format(abs($account_5070000_total)).")" : number_format($account_5070000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_5070000[$branch] < 0 ? "(".number_format(abs($account_5070000[$branch])).")" : number_format($account_5070000[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban Sewa Kantor Cabang : 5040001, 5040002
				
				$code = "5040001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5040001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5040001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5040001[$branch] = $account_5040001_debet[$branch] - $account_5040001_credit[$branch];
					$account_5040001_total += $account_5040001[$branch];
					$account_beban_sewa_kantor_cabang_total[$branch] += $account_5040001[$branch];
					$account_beban_operasi_total[$branch] += $account_5040001[$branch];
					
				}
				$code = "5040002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5040002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5040002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5040002[$branch] = $account_5040002_debet[$branch] - $account_5040002_credit[$branch];
					$account_5040002_total += $account_5040002[$branch];
					$account_beban_sewa_kantor_cabang_total[$branch] += $account_5040002[$branch];
					$account_beban_operasi_total[$branch] += $account_5040002[$branch];
					
				}				
				$account_beban_sewa_kantor_cabang_konsolidasi = $account_5040001_total + $account_5040002_total ;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Sewa Kantor Cabang</td>';
				$print .= '	<td align="right" class="">'.($account_beban_sewa_kantor_cabang_konsolidasi < 0 ? "(".number_format(abs($account_beban_sewa_kantor_cabang_konsolidasi)).")" : number_format($account_beban_sewa_kantor_cabang_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_sewa_kantor_cabang_total[$branch] < 0 ? "(".number_format(abs($account_beban_sewa_kantor_cabang_total[$branch])).")" : number_format($account_beban_sewa_kantor_cabang_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				//Beban MIS
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban MIS</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_9999999_total < 0 ? "(".number_format(abs($account_9999999_total)).")" : number_format($account_9999999_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_9999999[$branch] < 0 ? "(".number_format(abs($account_9999999[$branch])).")" : number_format($account_9999999[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				//Total Biaya Operasi	
				for($branch=0; $branch <= $total_branch; $branch++){				
					$account_beban_operasi_konsolidasi += $account_beban_operasi_total[$branch];
				}
				$print .= '	<tr><td align="left" >Jumlah Biaya Operasi</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_beban_operasi_konsolidasi < 0 ? "(".number_format(abs($account_beban_operasi_konsolidasi)).")" : number_format($account_beban_operasi_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_beban_operasi_total[$branch] < 0 ? "(".number_format(abs($account_beban_operasi_total[$branch])).")" : number_format($account_beban_operasi_total[$branch])).'</td>';
				}
				$print .= '	</tr>';	
				
				$print .= '	<tr><td></td><td align="left" class="border_btm" colspan="8"> &nbsp;</td></tr>';
				
				//Laba (Rugi) Operasi
				$account_LR_operasi_konsolidasi = $account_labarugi_kotor_konsolidasi - $account_beban_operasi_konsolidasi;
				$print .= '	<tr><td align="left" ><b>Sisa Hasil Usaha Operasional</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_operasi_konsolidasi < 0 ? "(".number_format(abs($account_LR_operasi_konsolidasi)).")" : number_format($account_LR_operasi_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_LR_operasi[$branch] = $account_labarugi_kotor_total[$branch] - $account_beban_operasi_total[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_operasi[$branch] < 0 ? "(".number_format(abs($account_LR_operasi[$branch])).")" : number_format($account_LR_operasi[$branch])).'</b></td>';
				}
				$print .= '	</tr>';	
			
				//---------------------------------------------------------------------------------------------
				//Pendapatan Diluar Usaha
				//---------------------------------------------------------------------------------------------
				
				$print .= '	<tr><td align="left" colspan="9"> &nbsp;</td></tr>';				
				$print .= '	<tr><td align="left" ><b>Pendapatan (beban) lainnya</b></td>	<td colspan="8" ></td></tr>';
				
				//Pendapatan Lainnya : 4030001, 4030002, 4030003, 4030004, 4030005, 4030006 
				
				
				
				
				$code = "4030001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030001[$branch] = $account_4030001_credit[$branch] - $account_4030001_debet[$branch];
					$account_4030001_total += $account_4030001[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030001[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030001[$branch];
					
				}
				$code = "4030002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030002[$branch] = $account_4030002_credit[$branch] - $account_4030002_debet[$branch];
					$account_4030002_total += $account_4030002[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030002[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030002[$branch];
					
				}
				$code = "4030003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030003[$branch] = $account_4030003_credit[$branch] - $account_4030003_debet[$branch];
					$account_4030003_total += $account_4030003[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030003[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030003[$branch];
					
				}					
				$code = "4030004";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030004_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030004_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030004[$branch] = $account_4030004_credit[$branch] - $account_4030004_debet[$branch];
					$account_4030004_total += $account_4030004[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030004[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030004[$branch];
					
				}
				//Pendapatan Bunga Bank
				$code = "4030005";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030005_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030005_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030005[$branch] = $account_4030005_credit[$branch] - $account_4030005_debet[$branch];
					$account_4030005_total += $account_4030005[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030005[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030005[$branch];
					
				}
				
				$code = "4030006";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_4030006_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_4030006_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_4030006[$branch] = $account_4030006_credit[$branch] - $account_4030006_debet[$branch];
					$account_4030006_total += $account_4030006[$branch];
					$account_pendapatan_lain_pusat_total[$branch] += $account_4030006[$branch];
					$account_pendapatan_diluar_usaha_total[$branch] += $account_4030006[$branch];
					
				}
				
				$account_pendapatan_lain_konsolidasi = $account_4030001_total +  $account_4030002_total + $account_4030003_total + $account_4030004_total + $account_4030005_total + $account_4030006_total;
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Lain-lain</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_lain_konsolidasi < 0 ? "(".number_format(abs($account_pendapatan_lain_konsolidasi)).")" : number_format($account_pendapatan_lain_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_pendapatan_lain_pusat_total[$branch] < 0 ? "(".number_format(abs($account_pendapatan_lain_pusat_total[$branch])).")" : number_format($account_pendapatan_lain_pusat_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
								
				//---------------------------------------------------------------------------------------------
				//(beban) lainnya
				//---------------------------------------------------------------------------------------------
				
				//5090001
				$code = "5090001";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090001_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090001_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090001[$branch] = $account_5090001_debet[$branch] - $account_5090001_credit[$branch];
					$account_5090001_total += $account_5090001[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090001[$branch];
					
				}
				
				//5090002
				$code = "5090002";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090002_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090002_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090002[$branch] = $account_5090002_debet[$branch] - $account_5090002_credit[$branch];
					$account_5090002_total += $account_5090002[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090002[$branch];
					
				}
				
				//5090003
				$code = "5090003";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090003_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090003_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090003[$branch] = $account_5090003_debet[$branch] - $account_5090003_credit[$branch];
					$account_5090003_total += $account_5090003[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090003[$branch];
					
				}
				
				//5090004
				$code = "5090004";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090004_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090004_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090004[$branch] = $account_5090004_debet[$branch] - $account_5090004_credit[$branch];
					$account_5090004_total += $account_5090004[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090004[$branch];
					
				}
				
				//5090005
				$code = "5090005";
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_5090005_debet[$branch]  = $this->jurnal_model->sum_account_debet_by_date($code,$date_start,$date_end,$branch);
					$account_5090005_credit[$branch] = $this->jurnal_model->sum_account_credit_by_date($code,$date_start,$date_end,$branch);
					$account_5090005[$branch] = $account_5090005_debet[$branch] - $account_5090005_credit[$branch];
					$account_5090005_total += $account_5090005[$branch];
					$account_beban_diluar_usaha_total[$branch] += $account_5090005[$branch];
					
				}
				
				//Total Beban Diluar Usaha
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_beban_diluar_usaha_konsolidasi += $account_beban_diluar_usaha_total[$branch];
				}
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Beban Lain-Lain</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_beban_diluar_usaha_konsolidasi < 0 ? "(".number_format(abs($account_beban_diluar_usaha_konsolidasi)).")" : number_format($account_beban_diluar_usaha_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_beban_diluar_usaha_total[$branch] < 0 ? "(".number_format(abs($account_beban_diluar_usaha_total[$branch])).")" : number_format($account_beban_diluar_usaha_total[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_jumlah_lainlain[$branch] = $account_pendapatan_lain_pusat_total[$branch] - $account_beban_diluar_usaha_total[$branch]; 
				}
				$account_jumlah_lainlain_konsolidasi = $account_pendapatan_lain_konsolidasi - $account_beban_diluar_usaha_konsolidasi;
				
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah</td>';
				$print .= '	<td align="right" class="border_btm">'.($account_jumlah_lainlain_konsolidasi < 0 ? "(".number_format(abs($account_jumlah_lainlain_konsolidasi)).")" : number_format($account_jumlah_lainlain_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="border_btm">'.($account_jumlah_lainlain[$branch] < 0 ? "(".number_format(abs($account_jumlah_lainlain[$branch])).")" : number_format($account_jumlah_lainlain[$branch])).'</td>';
				}
				$print .= '</tr>';
				
				
				
				$print .= '	<tr><td></td><td align="left" colspan="8" class="border_btm"> &nbsp;</td></tr>';
				
				
				//---------------------------------------------------------------------------------------------
				//Laba (Rugi) TOTAL
				//---------------------------------------------------------------------------------------------
				
				

				
				//Laba (Rugi) Bersih
				$account_LR_bersih_konsolidasi = $account_labarugi_kotor_konsolidasi - $account_jumlah_lainlain_konsolidasi;
				
				$print .= '	<tr><td align="left" ><b>Sisa hasil usaha bersih</b></td>';
				$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_bersih_konsolidasi < 0 ? "(".number_format(abs($account_LR_bersih_konsolidasi)).")" : number_format($account_LR_bersih_konsolidasi)).'</b></td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$account_LR_bersih_total[$branch] = $account_LR_operasi[$branch] - $account_jumlah_lainlain[$branch];
					$print .= '	<td align="right" class="border_btm"><b>'.($account_LR_bersih_total[$branch] < 0 ? "(".number_format(abs($account_LR_bersih_total[$branch])).")" : number_format($account_LR_bersih_total[$branch])).'</b></td>';
				}
				$print .= '</tr>';
				
				
			$this->template	->set('menu_title', 'Laporan Keuangan - Sisa Hasil Usaha')
							->set('menu_report', 'active')
							->set('print', $print)
							->build('accounting/labarugi_konsolidasi');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
}