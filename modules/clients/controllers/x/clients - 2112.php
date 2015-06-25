<?php

class Clients extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Clients';
	private $module 	= 'clients';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('clients_model');
		$this->load->model('clients_masterdata_model');
		$this->load->model('group_model');
		$this->load->model('officer_model');
		$this->load->library('pagination');		
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			$clients = $this->clients_masterdata_model->get_clients()->result();			
				
			$this->template	->set('menu_title', 'Data Anggota')
							->set('menu_client', 'active')
							->set('clients', $clients)
							->build('clients');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function anggota($page='0')
	{
		if($this->session->userdata('logged_in'))
		{
		
			$total_rows = $this->clients_model->count_all($this->input->get('q'));
				
			//pagination
			$config['base_url']     = site_url($this->module.'/anggota');
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
		
			$clients = $this->clients_model->_getAll( $config['per_page'] ,$page,$this->input->get('q'));

			$this->template	->set('menu_title', 'Data Anggota')
							->set('menu_client', 'active')
							->set('clients', $clients)
							->set('list', $list)
							->set('no', $no)
							->set('config', $config)
							->build('clients');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function register(){
		if($this->save_client()){
			$this->session->set_flashdata('message', 'success|Anggota telah ditambahkan');
			redirect($this->module.'/register');
		}
		
		$officer = $this->officer_model->get_all_officer();
		$group = $this->group_model->get_all();
		$this->template	->set('menu_title', 'Registrasi Anggota')
							->set('client', $data)
							->set('group', $group)
							->set('officer', $officer)
							->set('menu_client', 'active')
							->build('client_register');	
		
	}
	
	public function edit(){		
		
		if($this->save_client()){
			$this->session->set_flashdata('message', 'success|Data telah diedit');
			redirect($this->module.'/');
		}
		
		//GET DETAILS ANGGOTA
		$anggota_id =  $this->uri->segment(3);
		$data = $this->clients_masterdata_model->get_anggota($anggota_id)->result();
		$data = $data[0];
		
		$this->template	->set('data', $data)
						->set('projectid', $project_id)
						->set('menu_title', 'Edit Anggota')
						->set('menu_client', 'active')
						->set('project', $project )
						->build('client_form');	
	}
	/*
	public function view(){
		$project_id =  $this->uri->segment(4);
		$data = $this->project_model->find($project_id);
		
		if($this->save_client()){
			$this->session->set_flashdata('message', 'success|Project telah diedit');
			redirect('admin/'.$this->module.'/');
		}
		//GET SPECIFIC PROJECT
		$project = $this->project_model->get_project($project_id)->result();
		$project = $project[0];
		$this->template	->set('data', $data)
						->set('projectid', $project_id)
						->set('menu_title', 'View Project')
						->set('project', $project )
						->build('admin/project_form');	
	}
	*/
	public function delete($id = '0'){
			$this->module = "clients";
				if($this->clients_model->delete($id)){
					$this->session->set_flashdata('message', 'success|Anggota telah dihapus');
					redirect('clients/anggota');
					exit;
				}
	}
	
	
	private function save_client(){
		//set form validation
		$this->form_validation->set_rules('client_branch', 'Cabang', 'required');
		$this->form_validation->set_rules('client_group', 'Majelis', 'required');
		$this->form_validation->set_rules('client_fullname', 'Nama Lengkap', 'required');
		$this->form_validation->set_rules('client_birthdate', 'Tanggal Lahir', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('client_id');
	
			//process the form
			$data = array(
					'client_branch'	     	=> $this->input->post('client_branch'),
					'client_group'	     	=> $this->input->post('client_group'),
					'client_officer'      	=> $this->input->post('client_officer'),
					'client_fullname'      	=> $this->input->post('client_fullname'),
					'client_simplename'	    => $this->input->post('client_simplename'),
					'client_martialstatus'	=> $this->input->post('client_martialstatus'),
					'client_birthplace'	    => $this->input->post('client_birthplace'),
					'client_birthdate'	    => $this->input->post('client_birthdate'),
					'client_rt'				=> $this->input->post('client_rt'),
					'client_rw'				=> $this->input->post('client_rw'),
					'client_kampung'		=> $this->input->post('client_kampung'),
					'client_desa'			=> $this->input->post('client_desa'),
					'client_kecamatan'		=> $this->input->post('client_kecamatan'),
					'client_ktp'			=> $this->input->post('client_ktp'),
					'client_religion'		=> $this->input->post('client_religion'),
					'client_education'		=> $this->input->post('client_education'),
					'client_job'			=> $this->input->post('client_job'),
					'client_comodity'		=> $this->input->post('client_comodity')
			);
			
			if(!$id)
				return $this->clients_model->insert($data);
			else
				return $this->clients_model->update($id, $data);
			 
		}
	}
}