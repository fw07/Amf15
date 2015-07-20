<?php

class Review extends Front_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('review_model');
		$this->load->model('review_outstanding_model');
	}

	public function index(){
		if($this->session->userdata('logged_in'))
		{
			$total_anggota = $this->review_model->count_all_clients();
			$total_anggota_per_lastmonth = $this->review_model->count_all_clients_until_prevmonth();
			$total_majelis = $this->review_model->count_all_majelis();
			$total_majelis_per_lastmonth = $this->review_model->count_all_majelis_until_prevmonth();
			$total_cabang  = $this->review_model->count_all_cabang();
			$total_officer = $this->review_model->count_all_officer();

			$total_outstanding_pinjaman = $this->review_outstanding_model->sum_all_outstanding_pinjaman();
			$total_outstanding_pinjaman_per_lastmonth = $this->review_outstanding_model->sum_all_outstanding_pinjaman_per_lastmonth();

			$total_saldo_tabsukarela = $this->review_outstanding_model->sum_all_outstanding_tabungan_sukarela_until_currmonth();
			$total_saldo_tabsukarela_per_lastmonth = $this->review_outstanding_model->sum_all_outstanding_tabungan_sukarela_until_prevmonth();
			$total_saldo_tabwajib = $this->review_outstanding_model->sum_all_outstanding_tabungan_wajib_until_currmonth();
			$total_saldo_tabwajib_per_lastmonth = $this->review_outstanding_model->sum_all_outstanding_tabungan_wajib_until_prevmonth();
			$total_saldo_tabberjangka = $this->review_outstanding_model->sum_all_outstanding_tabungan_berjangka_until_currmonth();
			$total_saldo_tabberjangka_per_lastmonth = $this->review_outstanding_model->sum_all_outstanding_tabungan_berjangka_until_prevmonth();

			$list_cabang   = $this->review_model->list_cabang();

			for($i=0; $i<count($list_cabang); $i++){
				$total_anggota_per_cabang[$i] = $this->review_model->count_clients_by_branch($list_cabang[$i]['branch_id']);
				$total_anggota_per_cabang_per_lastmonth[$i] = $this->review_model->count_clients_by_branch_until_prevmonth($list_cabang[$i]['branch_id']);

				$total_majelis_per_cabang[$i] = $this->review_model->count_majelis_by_branch($list_cabang[$i]['branch_id']);
				$total_majelis_per_cabang_per_lastmonth[$i] = $this->review_model->count_majelis_by_branch_until_prevmonth($list_cabang[$i]['branch_id']);

				$total_outstanding_pinjaman_per_cabang[$i] = $this->review_outstanding_model->sum_all_outstanding_pinjaman_by_branch($list_cabang[$i]['branch_id']);
				$total_outstanding_pinjaman_per_cabang_per_lastmonth[$i] = $this->review_outstanding_model->sum_all_outstanding_pinjaman_by_branch_until_prevmonth($list_cabang[$i]['branch_id']);

				$total_saldo_tabwajib_per_cabang[$i] = $this->review_outstanding_model->sum_tabwajib_by_branch($list_cabang[$i]['branch_id']);
				$total_saldo_tabwajib_per_cabang_per_lastmonth[$i] = $this->review_outstanding_model->sum_tabwajib_by_branch_until_prevmonth($list_cabang[$i]['branch_id']);

				$total_saldo_tabsukarela_per_cabang[$i] = $this->review_outstanding_model->sum_tabsukarela_by_branch($list_cabang[$i]['branch_id']);
				$total_saldo_tabsukarela_per_cabang_per_lastmonth[$i] = $this->review_outstanding_model->sum_tabsukarela_by_branch_until_prevmonth($list_cabang[$i]['branch_id']);

				$total_saldo_tabberjangka_per_cabang[$i] = $this->review_outstanding_model->sum_tabberjangka_by_branch($list_cabang[$i]['branch_id']);
				$total_saldo_tabberjangka_per_cabang_per_lastmonth[$i] = $this->review_outstanding_model->sum_tabberjangka_by_branch_until_prevmonth($list_cabang[$i]['branch_id']);
				
				$total_officer_per_cabang[$i] = $this->review_model->count_all_officer_by_branch($list_cabang[$i]['branch_id']);

			}

			//var_dump($total_outstanding_pinjaman_per_cabang); 
			//var_dump($total_outstanding_pinjaman_per_cabang_per_lastmonth);

			$this->template	->set('menu_title', 'Review Report')
							->set('total_all_anggota', $total_anggota)
							->set('total_all_anggota_per_lastmonth', $total_anggota_per_lastmonth)
							->set('total_anggota_per_cabang', $total_anggota_per_cabang)
							->set('total_anggota_per_cabang_per_lastmonth', $total_anggota_per_cabang_per_lastmonth)
			//				->set()
							->set('total_all_majelis', $total_majelis)
							->set('total_all_majelis_per_lastmonth', $total_majelis_per_lastmonth)
							->set('total_majelis_per_cabang', $total_majelis_per_cabang)
							->set('total_majelis_per_cabang_per_lastmonth', $total_majelis_per_cabang_per_lastmonth)
			//				->set()
							->set('total_outstanding_pinjaman', $total_outstanding_pinjaman)
							->set('total_outstanding_pinjaman_per_lastmonth', $total_outstanding_pinjaman_per_lastmonth)
							->set('total_outstanding_pinjaman_per_cabang', $total_outstanding_pinjaman_per_cabang)	
							->set('total_outstanding_pinjaman_per_cabang_per_lastmonth', $total_outstanding_pinjaman_per_cabang)
			//				->set(tabwajib)
							->set('total_saldo_tabwajib', $total_saldo_tabwajib)		
							->set('total_saldo_tabwajib_per_lastmonth', $total_saldo_tabwajib_per_lastmonth)
							->set('total_saldo_tabwajib_per_cabang', $total_saldo_tabwajib_per_cabang)
							->set('total_saldo_tabwajib_per_cabang_per_lastmonth', $total_saldo_tabwajib_per_cabang_per_lastmonth)
			//				->set()
			//				->set(tabsukarela)
							->set('total_saldo_tabsukarela', $total_saldo_tabsukarela)		
							->set('total_saldo_tabsukarela_per_lastmonth', $total_saldo_tabsukarela_per_lastmonth)
							->set('total_saldo_tabsukarela_per_cabang', $total_saldo_tabsukarela_per_cabang)
							->set('total_saldo_tabsukarela_per_cabang_per_lastmonth', $total_saldo_tabsukarela_per_cabang_per_lastmonth)
			//				->set(tabberjangka)
							->set('total_saldo_tabberjangka', $total_saldo_tabberjangka)		
							->set('total_saldo_tabberjangka_per_lastmonth', $total_saldo_tabberjangka_per_lastmonth)
							->set('total_saldo_tabberjangka_per_cabang', $total_saldo_tabberjangka_per_cabang)
							->set('total_saldo_tabberjangka_per_cabang_per_lastmonth', $total_saldo_tabberjangka_per_cabang_per_lastmonth)	
			//				->set()				
							->set('total_all_cabang',  $total_cabang)
							->set('total_all_officer', $total_officer)
							->set('total_officer_per_cabang', $total_officer_per_cabang)
							->set('list_cabang', $list_cabang)
							->build('review/review');
		}else{
			redirect('login', 'refresh');
		}
	}

}
