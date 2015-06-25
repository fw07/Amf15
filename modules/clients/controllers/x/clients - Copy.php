<?php

class Clients extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Clients';
	private $module 	= 'clients';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('clients_model');
		
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			$clients = $this->clients_model->get_clients()->result();
			
				
			$this->template	->set('menu_title', 'Clients')
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
	
	public function register(){
		if($this->save_client()){
			$this->session->set_flashdata('message', 'success|Client telah ditambahkan');
			redirect($this->module.'/');
		}
		
			$this->template	->set('menu_title', 'Register Client')
							->set('menu_client', 'active')
							->build('client_form');	
		
	}
	/*
	public function edit(){
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
						->set('menu_title', 'Edit Project')
						->set('project', $project )
						->build('admin/project_form');	
	}
	
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
	
	public function delete($id = '0'){
			$this->module = "project";
				if($this->project_model->delete($id)){
					$this->session->set_flashdata('message', 'success|Project telah dihapus');
					redirect('admin/project/');
					exit;
				}
	}
	*/
	
	private function save_client(){
		//set form validation
		$this->form_validation->set_rules('client_firstname', 'First Name', 'required');
		$this->form_validation->set_rules('client_birth', 'Birth Date', 'required');
		$this->form_validation->set_rules('client_gender', 'Gender', 'required');
		$this->form_validation->set_rules('client_martialstatus', 'Martial Status', 'required');
		$this->form_validation->set_rules('client_branch', 'Branch Office', 'required');
		$this->form_validation->set_rules('client_group', 'Group', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('client_id');
	
			//process the form
			$data = array(
					'client_firstname'      => $this->input->post('client_firstname'),
					'client_lastname'	    => $this->input->post('client_lastname'),
					'client_birth'	     	=> $this->input->post('client_birth'),
					'client_gender'	 		=> $this->input->post('client_gender'),
					'client_martialstatus'	=> $this->input->post('client_martialstatus'),
					'client_branch'	     	=> $this->input->post('client_branch'),
					'client_group'	     	=> $this->input->post('client_group')
			);
			
			//try to upload image first
			try{
				$config['upload_path'] 		= 'files/clients/';
				$config['allowed_types'] 	= 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
				$config['max_size']			= '1000';
				$config['encrypt_name']	 	= TRUE;
				$this->load->library('upload', $config);
				if($this->upload->do_upload('image')){
					$upload 		= $this->upload->data();
					$data['project_preview']  = $upload['file_name'];
				}
			}catch(Exception $e){
				echo $e;
				exit;
			}
	
			if(!$id)
				return $this->clients_model->insert($data);
			else
				return $this->clients_model->update($id, $data);
			 
		}
	}
}