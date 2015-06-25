<?php

class Pembiayaan extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Pembiayaan';
	private $module 	= 'pembiayaan';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('clients_pembiayaan_model');
		$this->load->model('repayment_model');
		$this->load->model('par_model');
		
		$this->load->library('pagination');	
	}
	
	public function index($page='0'){
		if($this->session->userdata('logged_in'))
		{
			redirect('pembiayaan/pembiayaanaktif', 'refresh');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	//PAR
	public function par($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			if($user_branch != 0){				
				$total_rows = $this->clients_pembiayaan_model->count_pembiayaan_par($this->input->get('q'),$user_branch);
			}else{
				$total_rows = $this->clients_pembiayaan_model->count_pembiayaan_par($this->input->get('q'));
			}
			//pagination
			$config['base_url']     = site_url($this->module.'/client');
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 15; 
			$config['uri_segment']  = 3;
			//$config['suffix'] 		= '?' . http_build_query($_GET, '', "&");
			$config['first_url'] 	= $config['base_url'] . $config['suffix'];
			$config['num_links'] = 2;
			$config['full_tag_open'] = '<li>';
			$config['full_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li><a href="#"><b>';
			$config['cur_tag_close'] = '</b></a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			
			$this->pagination->initialize($config); 
			$no =  $this->uri->segment(3);
			if($user_branch != 0){	
				$clients = $this->clients_pembiayaan_model->get_pembiayaan_par( $config['per_page'] , $page, $this->input->post('q'), $this->input->post('key'), $user_branch);
			}else{
				$clients = $this->clients_pembiayaan_model->get_pembiayaan_par( $config['per_page'] , $page, $this->input->post('q'), $this->input->post('key'));
			}
			$this->template	->set('menu_title', 'Portfolio At Risk')
							->set('menu_client', 'active')
							->set('clients', $clients)
							->set('list', $list)
							->set('no', $no)
							->set('config', $config)
							->build('par');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	//PAR FILTER
	public function par_filter(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			if($user_branch == 0){ $user_branch =="";}
			$par = $this->uri->segment(3);
			$clients = $this->clients_pembiayaan_model->get_pembiayaan_par_filter($par,$user_branch);
			$this->template	->set('menu_title', 'Portfolio At Risk')
							->set('menu_client', 'active')
							->set('clients', $clients)
							->set('list', $list)
							->set('no', $no)
							->set('config', $config)
							->build('par');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	//PAR HISTORY
	public function par_history(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			if($user_branch == 0){ $user_branch =="";}
			$user_id = $this->uri->segment(3);
			$clients = $this->par_model->get_par_history($user_id,$user_branch);
			$this->template	->set('menu_title', 'Portfolio At Risk')
							->set('menu_client', 'active')
							->set('clients', $clients)
							->set('list', $list)
							->set('no', $no)
							->set('config', $config)
							->build('par_history');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function pembiayaanaktif($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			//Get Total Pengajuan 
			if($user_branch != 0){
				$total_rows = $this->clients_pembiayaan_model->count_all_approved_pengajuan_by_branch($this->input->get('q'),$user_branch);
			}else{
				$total_rows = $this->clients_pembiayaan_model->count_all_approved_pengajuan($this->input->get('q'));
			}			
			
				
			//pagination
			$config['base_url']     = site_url($this->module.'/pembiayaanaktif');
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 15; 
			$config['uri_segment']  = 3;
			//$config['suffix'] 		= '?' . http_build_query($_GET, '', "&");
			$config['first_url'] 	= $config['base_url'] . $config['suffix'];
			$config['num_links'] = 2;
			$config['full_tag_open'] = '<li>';
			$config['full_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li><a href="#"><b>';
			$config['cur_tag_close'] = '</b></a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			
			$this->pagination->initialize($config); 
			$no =  $this->uri->segment(3);
			
			if($user_branch != 0){
				$clients = $this->clients_pembiayaan_model->get_all_approved_pengajuan_by_branch( $config['per_page'] , $page, $this->input->post('q'), $this->input->post('key'), $user_branch);
			}else{
				$clients = $this->clients_pembiayaan_model->get_all_approved_pengajuan( $config['per_page'] , $page, $this->input->post('q'), $this->input->post('key'));
			}			
		
			$this->template	->set('menu_title', 'Pembiayaan Aktif')
							->set('menu_client', 'active')
							->set('clients', $clients)
							->set('no', $no)
							->set('config', $config)
							->build('pembiayaan');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function angsuran(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			$id =  $this->uri->segment(3); 
			$repayment = $this->repayment_model->get_repayment($id);
			$client = $repayment[0]; 
			$this->template	->set('menu_title', 'Angsuran Pembiayaan')
							->set('menu_client', 'active')
							->set('repayment', $repayment)
							->set('client', $client)
							->set('no', $no)
							->set('config', $config)
							->build('repayment');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
}