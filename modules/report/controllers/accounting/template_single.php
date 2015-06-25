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