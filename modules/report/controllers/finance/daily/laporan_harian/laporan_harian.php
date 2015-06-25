<?php

class Laporan_harian extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Laporan_harian';
	private $module 	= 'Laporan_harian';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('group_model');
		$this->load->model('officer_model');
		$this->load->model('area_model');
		$this->load->model('branch_model');
		$this->load->model('report_model');
		$this->load->library('pagination');	
	
	}
	

	public function index($page='0'){
		$user_level = $this->session->userdata('user_level');
		if($this->session->userdata('logged_in') AND $user_level == 1)
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			$branch = $this->branch_model->get_all()->result();
			
			//Build
			$this->template	->set('menu_title', 'Finance Report')
							->set('menu_report', 'active')
							->set('branch', $branch)
							->build('finance_browse');
			
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function browse(){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			
			//Build
			$this->template	->set('menu_title', 'Laporan Mingguan')
							->set('menu_branch', 'active')
							->set('group_total',$config['total_rows'])
							->set('report', $report)
							->set('no', $no)
							->set('config', $config)
							->build('report');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	

}