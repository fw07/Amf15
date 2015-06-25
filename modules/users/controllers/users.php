<?php

class Users extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Users';
	private $module 	= 'users';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('group_model');
		$this->load->model('officer_model');
		$this->load->model('area_model');
		$this->load->model('branch_model');
		$this->load->model('users_model');
		$this->load->library('pagination');	
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			redirect('users/browse', 'refresh');
		}
		else
		{
			 //If no session, redirect to login page
			 redirect('login', 'refresh');
		}
	}
	
	
	public function browse($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$total_rows = $this->users_model->count_all($this->input->post('q'));
			
			//pagination
			$config['base_url']     = site_url($this->module.'/browse/');
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
			$no =  $this->uri->segment(3);
			
			$users = $this->users_model->get_all_user( $config['per_page'] ,$no,$this->input->post('q'));			
				
			$this->template	->set('menu_title', 'User Account')
							->set('menu_setting', 'active')
							->set('group_total',$config['total_rows'])
							->set('users', $users)
							->set('no', $no)
							->set('config', $config)
							->build('users');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	
	
	public function register(){
		if($this->save_users()){
			$this->session->set_flashdata('message', 'success|User telah ditambahkan');
			redirect($this->module.'/');
		}
			
			$branch = $this->branch_model->get_all()->result();			
			
			$this->template	->set('menu_title', 'Add New User')
							->set('branch', $branch)
							->set('menu_setting', 'active')
							->build('users_form');	
		
	}
	
	public function edit(){
		$id =  $this->uri->segment(3);
		//$data = $this->group_model->find($id);
		
		if($this->save_users()){
			$this->session->set_flashdata('message', 'success|User telah diedit');
			redirect($this->module.'/');
		}
		//GET SPECIFIC PROJECT
		$data = $this->users_model->get_user($id)->result();
		$data = $data[0];
		$branch = $this->branch_model->get_all()->result();			
		$this->template	->set('data', $data)
						->set('menu_title', 'Edit User')
						->set('branch', $branch)
						->set('menu_setting', 'active')
						->build('users_form');	
	}
	
	public function setting_user(){
		$id =  $this->uri->segment(3);
		//$data = $this->group_model->find($id);
		
		if($this->save_users()){
			$this->session->set_flashdata('message', 'success|User telah diedit');
			redirect('dashboard');
		}
		//GET SPECIFIC PROJECT
		$data = $this->users_model->get_user($id)->result();
		$data = $data[0];
		$branch = $this->branch_model->get_all()->result();			
		$this->template	->set('data', $data)
						->set('menu_title', 'Edit User')
						->set('branch', $branch)
						->set('menu_setting', 'active')
						->build('users_setting_form');	
	}
	
	public function view(){
		$id =  $this->uri->segment(3);
		
		//GET SPECIFIC PROJECT
		$data = $this->group_model->get_group($id)->result();
		$data = $data[0];
		$this->template	->set('data', $data)
						->set('menu_title', 'View User')
						->set('menu_setting', 'active')
						->build('group_view');	
	}
	
	
	public function delete($id = '0'){
		$this->module = "users";
		$id =  $this->uri->segment(3);
			if($this->users_model->delete($id)){
				$this->session->set_flashdata('message', 'success|User telah dihapus');
				redirect('users/');
				exit;
			}
	}	
	
	private function save_users(){
		$user_level = $this->session->userdata('user_level');
		//set form validation
		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required');
		//$this->form_validation->set_rules('user_level', 'Level', 'required');
		//$this->form_validation->set_rules('user_branch', 'Branch', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('user_id');
			if($user_level==1){ 
				$data = array(
					'username'       	=> $this->input->post('username'),
					'password'       	=> md5($this->input->post('password')),
					'fullname' 			=> $this->input->post('fullname'),
					'user_level' 		=> $this->input->post('user_level'),	
					'user_branch'	    => $this->input->post('user_branch'),
					
				);
			}else{
				$data = array(
					'password'       	=> md5($this->input->post('password')),
					'fullname' 			=> $this->input->post('fullname'),					
				);
			}
			
				
			if(!$id){
				return $this->users_model->insert($data);
			}else{
				return $this->users_model->update($id, $data);
			} 
		}
	}
}