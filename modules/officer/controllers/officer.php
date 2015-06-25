<?php

class Officer extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Group';
	private $module 	= 'group';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('officer_model');
		$this->load->model('branch_model');
		$this->load->library('pagination');	
	}
	
	public function index($page='0'){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			//Get Total Group Row 
			if($user_branch != 0){
				$total_rows = $this->officer_model->count_all_by_branch($this->input->post('q'), $user_branch);				
				$officer = $this->officer_model->get_all_officer_by_branch( $config['per_page'] ,$page, $this->input->post('q'), $user_branch);	
			}else{
				$total_rows = $this->officer_model->count_all($this->input->post('q'));				
				$officer = $this->officer_model->get_all_officer( $config['per_page'] ,$page, $this->input->post('q'));	
			}
			
			
			//$total_rows = $this->officer_model->count_all($this->input->post('q'));
			
			//pagination
			$config['base_url']     = site_url($this->module);
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 15; 
			$config['uri_segment']  = 3;
			$config['suffix'] 		= '?' . http_build_query($_GET, '', "&");
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
			
					
			$this->template	->set('menu_title', 'Tim Pendamping Lapangan')
							->set('menu_branch', 'active')
							->set('officer_total',$config['total_rows'])
							->set('officer', $officer)
							->build('officer');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	
	public function register(){
		if($this->save_officer()){
			$this->session->set_flashdata('message', 'success|Pendamping Lapangan telah ditambahkan');
			redirect('officer/');
		}
		$user_branch = $this->session->userdata('user_branch');
		if($user_branch != 0){	
			$branch = $this->branch_model->get_branch($user_branch)->result();
		}else{
			$branch = $this->branch_model->get_all()->result();
		}
			$this->template	->set('menu_title', 'Registrasi Pendamping Lapangan')
							->set('branch', $branch)
							->set('menu_branch', 'active')
							->build('officer_form');	
		
	}
	
	public function edit(){
		$id =  $this->uri->segment(3);
		//$data = $this->officer_model->find($id);
		
		if($this->save_officer()){
			$this->session->set_flashdata('message', 'success|Pendamping Lapangan telah diedit');
			redirect('officer/');
		}
		//GET SPECIFIC OFFICER
		$data = $this->officer_model->get_officer($id)->result();
		$data = $data[0];
		$user_branch = $this->session->userdata('user_branch');
		if($user_branch != 0){	
			$branch = $this->branch_model->get_branch($user_branch)->result();
		}else{
			$branch = $this->branch_model->get_all()->result();
		}
		$this->template	->set('data', $data)
						->set('branch', $branch)
						->set('menu_title', 'Edit Pendamping Lapangan ')
						->set('menu_branch', 'active')
						->build('officer_form');	
	}
	
	public function view(){
		$id =  $this->uri->segment(3);
		
		//GET SPECIFIC OFFICER
		$data = $this->officer_model->get_officer($id)->result();
		$data = $data[0];
		if($user_branch != 0){	
			$branch = $this->branch_model->get_branch($user_branch)->result();
		}else{
			$branch = $this->branch_model->get_all()->result();
		}
		$this->template	->set('data', $data)
						->set('branch', $branch)
						->set('menu_title', 'View Pendamping Lapangan')
						->set('menu_branch', 'active')
						->build('officer_view');	
	}
	
	
	
	public function delete($id = '0'){
		$id =  $this->uri->segment(3);
			if($this->officer_model->delete($id)){
				$this->session->set_flashdata('message', 'success|Pendamping Lapangan telah dihapus');
				redirect('officer/');
				exit;
			}
	}	
	
	private function save_officer(){
		
		//set form validation
		$this->form_validation->set_rules('officer_branch', 'Kantor Cabang', 'required');
		$this->form_validation->set_rules('officer_name', 'Nama Lengkap', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('officer_id');
	
			//process the form
			$data = array(
					'officer_branch'	    => $this->input->post('officer_branch'),
					'officer_name'       	=> $this->input->post('officer_name'),
					'officer_number'       	=> $this->input->post('officer_number'),
					'officer_bornplace'	    => $this->input->post('officer_bornplace'),
					'officer_borndate'		=> $this->input->post('officer_borndate'),
					'officer_sex'	    	=> $this->input->post('officer_sex'),
					'officer_phone'	    	=> $this->input->post('officer_phone')
			);
				
			if(!$id)
				return $this->officer_model->insert($data);
			else
				return $this->officer_model->update($id, $data);
			 
		}
	}
}