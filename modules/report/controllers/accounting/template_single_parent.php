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
				$print .= '	<tr><td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PSimpanan Berjangka</td>';
				$print .= '	<td align="right" class="">'.($account_2020000_total < 0 ? "(".number_format(abs($account_2020000_total)).")" : number_format($account_2020000_total)).'</td>';
				for($branch=0; $branch <= $total_branch; $branch++){
					$print .= '	<td align="right" class="">'.($account_2020000[$branch] < 0 ? "(".number_format(abs($account_2020000[$branch])).")" : number_format($account_2020000[$branch])).'</td>';
				}
				$print .= '</tr>';	