<?php

class Operation extends Front_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('operation_model');
	}

	public function index(){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_level = $this->session->userdata('user_level');
			//Accessible only from Admin
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
					$branch = '0';
					$startdate = date('Y-m-d',strtotime('last day of previous month'));
					$enddate = date('Y-m-d',strtotime('now'));
				}

				$total_anggota_awal  = $this->operation_model->count_clients_by_branch_by_date($branch, $startdate);
				$total_anggota_akhir = $this->operation_model->count_clients_by_branch_by_date($branch, $enddate);

				$total_majelis_awal  = $this->operation_model->count_majelis_by_branch_by_date($branch, $startdate);
				$total_majelis_akhir = $this->operation_model->count_majelis_by_branch_by_date($branch, $enddate);

				$total_cabang  = $this->operation_model->count_all_cabang();
				$total_officer = $this->operation_model->count_all_officer();

				$total_outstanding_pinjaman_awal = $this->operation_model->sum_all_outstanding_pinjaman_by_branch_by_date($branch, $startdate);
				$total_outstanding_pinjaman_akhir = $this->operation_model->sum_all_outstanding_pinjaman_by_branch_by_date($branch, $enddate);

				$total_saldo_tabsukarela_awal = $this->operation_model->sum_tabsukarela_by_branch_by_date($branch, $startdate);
				$total_saldo_tabsukarela_akhir = $this->operation_model->sum_tabsukarela_by_branch_by_date($branch, $enddate);
				
				$total_saldo_tabwajib_awal = $this->operation_model->sum_tabwajib_by_branch_by_date($branch, $startdate);
				$total_saldo_tabwajib_akhir = $this->operation_model->sum_tabwajib_by_branch_by_date($branch, $enddate);
				
				$total_saldo_tabberjangka_awal = $this->operation_model->sum_tabberjangka_by_branch_by_date($branch, $startdate);
				$total_saldo_tabberjangka_akhir = $this->operation_model->sum_tabberjangka_by_branch_by_date($branch, $enddate);

				$list_cabang   = $this->operation_model->list_cabang();

				for($i=0; $i<count($list_cabang); $i++){
					$total_anggota_per_cabang_awal[$i] = $this->operation_model->count_clients_by_branch_by_date($list_cabang[$i]['branch_id'], $startdate);
					$total_anggota_per_cabang_akhir[$i] = $this->operation_model->count_clients_by_branch_by_date($list_cabang[$i]['branch_id'], $enddate);

					$total_majelis_per_cabang_awal[$i] = $this->operation_model->count_majelis_by_branch_by_date($list_cabang[$i]['branch_id'], $startdate);
					$total_majelis_per_cabang_akhir[$i] = $this->operation_model->count_majelis_by_branch_by_date($list_cabang[$i]['branch_id'], $enddate);

					$total_outstanding_pinjaman_per_cabang_awal[$i] = $this->operation_model->sum_all_outstanding_pinjaman_by_branch_by_date($list_cabang[$i]['branch_id'], $startdate);
					$total_outstanding_pinjaman_per_cabang_akhir[$i] = $this->operation_model->sum_all_outstanding_pinjaman_by_branch_by_date($list_cabang[$i]['branch_id'], $enddate);
					
					$total_saldo_tabsukarela_per_cabang_awal[$i] = $this->operation_model->sum_tabsukarela_by_branch_by_date($list_cabang[$i]['branch_id'], $startdate);
					$total_saldo_tabsukarela_per_cabang_akhir[$i] = $this->operation_model->sum_tabsukarela_by_branch_by_date($list_cabang[$i]['branch_id'], $enddate);

					$total_saldo_tabwajib_per_cabang_awal[$i] = $this->operation_model->sum_tabwajib_by_branch_by_date($list_cabang[$i]['branch_id'], $startdate);
					$total_saldo_tabwajib_per_cabang_akhir[$i] = $this->operation_model->sum_tabwajib_by_branch_by_date($list_cabang[$i]['branch_id'], $enddate);

					$total_saldo_tabberjangka_per_cabang_awal[$i] = $this->operation_model->sum_tabberjangka_by_branch_by_date($list_cabang[$i]['branch_id'], $startdate);
					$total_saldo_tabberjangka_per_cabang_akhir[$i] = $this->operation_model->sum_tabberjangka_by_branch_by_date($list_cabang[$i]['branch_id'], $enddate);
					
					$total_officer_per_cabang[$i] = $this->operation_model->count_all_officer_by_branch($list_cabang[$i]['branch_id']);

				}
				
				$this->template	->set('menu_title', 'Review Report')
								->set('total_all_anggota_awal', $total_anggota_awal)
								->set('total_all_anggota_akhir', $total_anggota_akhir)
								->set('total_anggota_per_cabang_awal', $total_anggota_per_cabang_awal)
								->set('total_anggota_per_cabang_akhir', $total_anggota_per_cabang_akhir)
				//				->set()
								->set('total_all_majelis_awal', $total_majelis_awal)
								->set('total_all_majelis_akhir', $total_majelis_akhir)
								->set('total_majelis_per_cabang_awal', $total_majelis_per_cabang_awal)
								->set('total_majelis_per_cabang_akhir', $total_majelis_per_cabang_akhir)
				//				->set()
								->set('total_outstanding_pinjaman_awal', $total_outstanding_pinjaman_awal)
								->set('total_outstanding_pinjaman_akhir', $total_outstanding_pinjaman_akhir)
								->set('total_outstanding_pinjaman_per_cabang_awal', $total_outstanding_pinjaman_per_cabang_awal)	
								->set('total_outstanding_pinjaman_per_cabang_akhir', $total_outstanding_pinjaman_per_cabang_akhir)
				//				->set(tabsukarela)
								->set('total_saldo_tabsukarela_awal', $total_saldo_tabsukarela_awal)		
								->set('total_saldo_tabsukarela_akhir', $total_saldo_tabsukarela_akhir)
								->set('total_saldo_tabsukarela_per_cabang_awal', $total_saldo_tabsukarela_per_cabang_awal)
								->set('total_saldo_tabsukarela_per_cabang_akhir', $total_saldo_tabsukarela_per_cabang_akhir)
				//				->set(tabwajib)
								->set('total_saldo_tabwajib_awal', $total_saldo_tabwajib_awal)		
								->set('total_saldo_tabwajib_akhir', $total_saldo_tabwajib_akhir)
								->set('total_saldo_tabwajib_per_cabang_awal', $total_saldo_tabwajib_per_cabang_awal)
								->set('total_saldo_tabwajib_per_cabang_akhir', $total_saldo_tabwajib_per_cabang_akhir)
				//				->set(tabberjangka)
								->set('total_saldo_tabberjangka_awal', $total_saldo_tabberjangka_awal)		
								->set('total_saldo_tabberjangka_akhir', $total_saldo_tabberjangka_akhir)
								->set('total_saldo_tabberjangka_per_cabang_awal', $total_saldo_tabberjangka_per_cabang_awal)
								->set('total_saldo_tabberjangka_per_cabang_akhir', $total_saldo_tabberjangka_per_cabang_akhir)	
				//				->set()			
								->set('total_all_cabang',  $total_cabang)
								->set('total_all_officer', $total_officer)
								->set('total_officer_per_cabang', $total_officer_per_cabang)
								->set('list_cabang', $list_cabang)
								->set('date_awal', date("d F Y", strtotime($startdate))) 
								->set('date_akhir', date("d F Y", strtotime($enddate)))
								->build('operation/operation');
								//->build('review/review');
			}else{
				redirect('/', 'refresh');
			}

		}else{
			redirect('login', 'refresh');
		}
	}

}