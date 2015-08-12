<?php

class Operation_branch extends Front_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('operation_model');
	}

	public function overview($bid='1'){
		if($this->session->userdata('logged_in'))
		{
		  //Cek User Login Branch
		  $user_level = $this->session->userdata('user_level');
		  //Accessible only from Admin?
		  if($user_level=='1'){
				if($this->input->post('filter') == '1'){
					$branch = $this->input->post('branch');
					if($this->input->post('startdate') == '')
						{ $startdate = date('Y-m-d',strtotime('last day of previous month')); }
					else 
						{ $startdate = $this->input->post('startdate'); }
					if($this->input->post('enddate') == '')
						{ $enddate = date('Y-m-d',strtotime('now')); }
					else 
						{ $enddate = $this->input->post('enddate'); }
				}
				else{
					$branch = $bid;
					$startdate = date('Y-m-d',strtotime('last day of previous month'));
					$enddate = date('Y-m-d',strtotime('now'));
				}

				$total_anggota_awal  = $this->operation_model->count_clients_by_branch_by_date($branch, $startdate);
				$total_anggota_akhir = $this->operation_model->count_clients_by_branch_by_date($branch, $enddate);
				$total_anggota_all   = $this->operation_model->count_clients_by_branch_by_date($branch, date('Y-m-d',strtotime('now')));

				$total_majelis_awal  = $this->operation_model->count_majelis_by_branch_by_date($branch, $startdate);
				$total_majelis_akhir = $this->operation_model->count_majelis_by_branch_by_date($branch, $enddate);
				$total_majelis_all   = $this->operation_model->count_majelis_by_branch_by_date($branch, date('Y-m-d',strtotime('now')));

				$total_cabang  = $this->operation_model->count_all_cabang();
				$total_officer = $this->operation_model->count_all_officer();
				$total_officer_cabang = $this->operation_model->count_all_officer_by_branch($branch);

				$total_outstanding_pinjaman_awal = $this->operation_model->sum_all_outstanding_pinjaman_by_branch_by_date($branch, $startdate);
				$total_outstanding_pinjaman_akhir = $this->operation_model->sum_all_outstanding_pinjaman_by_branch_by_date($branch, $enddate);

				$total_saldo_tabsukarela_awal = $this->operation_model->sum_tabsukarela_by_branch_by_date($branch, $startdate);
				$total_saldo_tabsukarela_akhir = $this->operation_model->sum_tabsukarela_by_branch_by_date($branch, $enddate);
				
				$total_saldo_tabwajib_awal = $this->operation_model->sum_tabwajib_by_branch_by_date($branch, $startdate);
				$total_saldo_tabwajib_akhir = $this->operation_model->sum_tabwajib_by_branch_by_date($branch, $enddate);
				
				$total_saldo_tabberjangka_awal = $this->operation_model->sum_tabberjangka_by_branch_by_date($branch, $startdate);
				$total_saldo_tabberjangka_akhir = $this->operation_model->sum_tabberjangka_by_branch_by_date($branch, $enddate);

				$branch_name   = $this->operation_model->get_cabang_name($branch);
				$officer_list  = $this->operation_model->list_all_officer_by_branch($branch);

				/*
				$total_par_minggu1 = $this->operation_model->count_par_per_branch_per_week('0', $startdate, $enddate, '1');
				$total_par_minggu2 = $this->operation_model->count_par_per_branch_per_week('0', $startdate, $enddate, '2');
				$total_par_minggu3 = $this->operation_model->count_par_per_branch_per_week('0', $startdate, $enddate, '3');
				$total_par_minggu4 = $this->operation_model->count_par_per_branch_per_week('0', $startdate, $enddate, '4');

				$sum_par_minggu1 = $this->operation_model->sum_par_per_branch_per_week('0', $startdate, $enddate, '1');
				$sum_par_minggu2 = $this->operation_model->sum_par_per_branch_per_week('0', $startdate, $enddate, '2');
				$sum_par_minggu3 = $this->operation_model->sum_par_per_branch_per_week('0', $startdate, $enddate, '3');
				$sum_par_minggu4 = $this->operation_model->sum_par_per_branch_per_week('0', $startdate, $enddate, '4');
				*/
				//var_dump($officer_list);
				//echo $officer_list[0]['officer_name'];
				for($n = 0; $n<count($officer_list); $n++)
				{
					$officer_list[$n]['no_clients_awal'] = $this->operation_model->count_clients_per_officer_per_branch($branch, $officer_list[$n]['officer_id'], $startdate);
					$officer_list[$n]['no_majelis_awal'] = $this->operation_model->count_majelis_per_officer_per_branch($branch, $officer_list[$n]['officer_id'], $startdate);
					$officer_list[$n]['no_clients_akhir'] = $this->operation_model->count_clients_per_officer_per_branch($branch, $officer_list[$n]['officer_id'], $enddate);
					$officer_list[$n]['no_majelis_akhir'] = $this->operation_model->count_majelis_per_officer_per_branch($branch, $officer_list[$n]['officer_id'], $enddate);
					//echo $officer_list[$n]['no_clients_awal'].'-'.$officer_list[$n]['no_majelis_awal'].'<br/>';
					//echo $branch.'b-'.$startdate.'s-'.$enddate.'e-'.'1'.'w-'.$officer_list[$n]['officer_id'].'o';
					//echo "<br/>";
					
					$total_par_per_cabang_minggu1_per_officer[$n] = $this->operation_model->count_par_per_branch_per_week_per_officer($branch, $startdate, $enddate, '1', $officer_list[$n]['officer_id']);
					$total_par_per_cabang_minggu2_per_officer[$n] = $this->operation_model->count_par_per_branch_per_week_per_officer($branch, $startdate, $enddate, '2', $officer_list[$n]['officer_id']);
					$total_par_per_cabang_minggu3_per_officer[$n] = $this->operation_model->count_par_per_branch_per_week_per_officer($branch, $startdate, $enddate, '3', $officer_list[$n]['officer_id']);
					$total_par_per_cabang_minggu4_per_officer[$n] = $this->operation_model->count_par_per_branch_per_week_per_officer($branch, $startdate, $enddate, '4', $officer_list[$n]['officer_id']);

					$sum_par_per_cabang_minggu1_per_officer[$n] = $this->operation_model->sum_par_per_branch_per_week_per_officer($branch, $startdate, $enddate, '1', $officer_list[$n]['officer_id']);
					$sum_par_per_cabang_minggu2_per_officer[$n] = $this->operation_model->sum_par_per_branch_per_week_per_officer($branch, $startdate, $enddate, '2', $officer_list[$n]['officer_id']);
					$sum_par_per_cabang_minggu3_per_officer[$n] = $this->operation_model->sum_par_per_branch_per_week_per_officer($branch, $startdate, $enddate, '3', $officer_list[$n]['officer_id']);
					$sum_par_per_cabang_minggu4_per_officer[$n] = $this->operation_model->sum_par_per_branch_per_week_per_officer($branch, $startdate, $enddate, '4', $officer_list[$n]['officer_id']);
					
				}

				$log_data = array(

						'activity_userid' 	    => $this->session->userdata['user_id'],
						'activity_userbranch'   => $this->session->userdata['user_branch'],
						'activity_module' 		=> $this->router->fetch_module(),
						'activity_controller'   => $this->router->fetch_class(),
						'activity_method'       => $this->router->fetch_method(),
						'activity_data'         => 'log_data_opbranch',
						'activity_remarks'      => 'log_remarks_opbranch'
				);
				

				$log = $this->access_control->log_activity($log_data);
				
				$this->template->set('menu_title', 'Review Report')
					 ->set('total_all_anggota_awal', $total_anggota_awal)
					 ->set('total_all_anggota_akhir', $total_anggota_akhir)
					 ->set('total_all_anggota', $total_anggota_all)
					 ->set('total_all_majelis_awal', $total_majelis_awal)
					 ->set('total_all_majelis_akhir', $total_majelis_akhir)
					 ->set('total_all_majelis', $total_majelis_all)
					 ->set('total_outstanding_pinjaman_awal', $total_outstanding_pinjaman_awal)
					 ->set('total_outstanding_pinjaman_akhir', $total_outstanding_pinjaman_akhir)
					 ->set('total_par_per_cabang_minggu1_per_officer', $total_par_per_cabang_minggu1_per_officer)
					 ->set('total_par_per_cabang_minggu2_per_officer', $total_par_per_cabang_minggu2_per_officer)
					 ->set('total_par_per_cabang_minggu3_per_officer', $total_par_per_cabang_minggu3_per_officer)
					 ->set('total_par_per_cabang_minggu4_per_officer', $total_par_per_cabang_minggu4_per_officer)
					 //->set('total_par_minggu1', $total_par_minggu1)
					 //->set('total_par_minggu2', $total_par_minggu2)
					 //->set('total_par_minggu3', $total_par_minggu3)
					 //->set('total_par_minggu4', $total_par_minggu4)
					 ->set('sum_par_per_cabang_minggu1_per_officer', $sum_par_per_cabang_minggu1_per_officer)
					 ->set('sum_par_per_cabang_minggu2_per_officer', $sum_par_per_cabang_minggu2_per_officer)
					 ->set('sum_par_per_cabang_minggu3_per_officer', $sum_par_per_cabang_minggu3_per_officer)
					 ->set('sum_par_per_cabang_minggu4_per_officer', $sum_par_per_cabang_minggu4_per_officer)
					 //->set('sum_par_minggu1', $sum_par_minggu1)
					 //->set('sum_par_minggu2', $sum_par_minggu2)
					 //->set('sum_par_minggu3', $sum_par_minggu3)
					 //->set('sum_par_minggu4', $sum_par_minggu4)
					 ->set('total_saldo_tabsukarela_awal', $total_saldo_tabsukarela_awal)		
					 ->set('total_saldo_tabsukarela_akhir', $total_saldo_tabsukarela_akhir)
					 ->set('total_saldo_tabwajib_awal', $total_saldo_tabwajib_awal)		
					 ->set('total_saldo_tabwajib_akhir', $total_saldo_tabwajib_akhir)
					 ->set('total_saldo_tabberjangka_awal', $total_saldo_tabberjangka_awal)		
					 ->set('total_saldo_tabberjangka_akhir', $total_saldo_tabberjangka_akhir)			
					 ->set('total_all_cabang',  $total_cabang)
					 ->set('total_all_officer', $total_officer)
					 ->set('total_all_officer_cabang', $total_officer_cabang)
					 ->set('officer_list', $officer_list)
					 ->set('branch_name', $branch_name)
					 ->set('date_awal', date("d F Y", strtotime($startdate))) 
					 ->set('date_akhir', date("d F Y", strtotime($enddate)))
					 ->build('operation/operation_branch');
					 //->build('review/review');
			}else{
				redirect('/', 'refresh');
			}

		}else{
			redirect('login', 'refresh');
		}
	}

}