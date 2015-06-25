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