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
				$print .= '	<td align="right" class="">'.($account_beban_lain_konsolidasi < 0 ? "(".number_format(abs($account_beban_lain_konsolidasi)).")" : number_format($account_beban_lain_konsolidasi)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_beban_lain_total[$branch] < 0 ? "(".number_format(abs($account_beban_lain_total[$branch])).")" : number_format($account_beban_lain_total[$branch])).'</td>';
				}
				$print .= '</tr>';