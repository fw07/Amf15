<?php

/**
 * Lenders Controller
 * 
 * @package	amartha
 * @author 	afahmi@amartha.co.id
 * @since	7 July 2015
 */

class Lenders extends Front_Controller{
	
	private $per_page 	= '15';
	private $title 		= 'Lenders';
	private $module 	= 'lenders';
	
	public function __construct(){
		parent::__construct();	
		//$this->template->set_layout('otherthanindex'); 
		//layouts/otherthanindex.php
		$this->load->model('lenders_model');
		$this->load->library('pagination');		
	}
	
	public function index($page='0'){
		if($this->session->userdata('logged_in'))
		{
			//$alllenders = $this->lenders_model->get_all_lenders()->result();
			$total_rows = $this->lenders_model->count_all_lenders();
			
			//pagination
			$config['base_url']     = site_url($this->module.'/overview');
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 5; 
			$config['uri_segment']  = 3;
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
			$lenders = $this->lenders_model->get_some_lenders( $config['per_page'] , $page, $this->input->post('q'), $this->input->post('key'));
			
			$this->template->set('menu_title', 'Data Investor (Borrowers)')
						   ->set('menu_investor', 'active')
						   ->set('lenders', $lenders)
						   ->set('list', $list)
						   ->set('no', $no)
						   ->set('config', $config)
						   ->build('lenders');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
			
	}

	public function registration(){
		if($this->session->userdata('logged_in'))
		{
			$this->template->set('menu_title', 'Registrasi Investor')
						   ->set('menu_investor', 'active')
						   ->set('form_type', 'registration')
						   ->build('lender_registration');	
		}else{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}

	public function edit(){
		if($this->session->userdata('logged_in'))
		{
			//GET DETAILS OF INVESTOR
			$lender_id  =  $this->uri->segment(3);
			$lender_obj =  $this->lenders_model->get_single_lender($lender_id)->row();
			//echo $this->uri->segment(3).'<br/>'; 
			//var_dump($lender_obj);
			
			$this->template->set('menu_title', 'Registrasi Investor')
						   ->set('menu_investor', 'active')
						   ->set('form_type', 'edit')
						   ->set('lender_id', $lender_id)
						   ->set('lender_object', $lender_obj)
						   ->build('lender_registration');	
		}else{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}

	public function delete(){
		$lender_id =  $this->uri->segment(3);
			if($this->lenders_model->delete_one_lender($lender_id)){
				$this->session->set_flashdata('message', 'success|Investor telah dihapus');
				redirect('lenders/overview');
				exit;
			}
	}

	public function save_lender(){
		//Set form validation
		$this->form_validation->set_rules('lender_type', 'Tipe Investor', 'required');
		$this->form_validation->set_rules('lender_name', 'Nama Investor', 'required');
		$this->form_validation->set_rules('lender_address', 'Alamat Investor', 'required');
		$this->form_validation->set_rules('lender_phone', 'Telepon Investor', 'required');
		$this->form_validation->set_rules('lender_email', 'Email Investor', 'required');
		if($this->form_validation->run() === true){
			//Process the form
			//Set Lender Code
			$max_id = $this->lenders_model->count_max_id() + 1;
			$bit_padding_zeros = 3 - strlen($max_id);
			for($i=0; $i<$bit_padding_zeros; $i++) 
				$padding_zeros = $padding_zeros.'0';
			$lendercode = $this->input->post('lender_type').$padding_zeros.$max_id;	

			$data = array(
						'lender_code'	        => $lendercode,
						'lender_type'	     	=> $this->input->post('lender_type'),
						'lender_name'	     	=> $this->input->post('lender_name'),
						'lender_address'	   	=> $this->input->post('lender_address'),
						'lender_phone'      	=> $this->input->post('lender_phone'),
						'lender_email'      	=> $this->input->post('lender_email'),
						'lender_account_no'     => $this->input->post('lender_account_no'),
						'person_in_charge'		=> $this->input->post('person_in_charge'),
						'person_address'		=> $this->input->post('person_address'),
						'person_phone'			=> $this->input->post('person_phone'),
						'person_email'		    => $this->input->post('person_email'),
						'created_on'			=> date('Y-m-d H:i:s', strtotime('now')),
						'created_by'			=> $this->session->userdata['user_id'],
						'modified_by'			=> $this->session->userdata['user_id']
			);	

			//IS IT REG & UPDATE?
			if($this->input->post('type') == 'registration')
				$query = $this->lenders_model->create_investor_details($data);
			else if($this->input->post('type') == 'edit')
				$query = $this->lenders_model->update_investor_details($this->input->post('lid'), $data);
			
			if($query){
				if($this->input->post('type') == 'edit')
					$this->session->set_flashdata('message', 'success|Data Investor telah diupdate!');
				else if($this->input->post('type') == 'registration')
					$this->session->set_flashdata('message', 'success|Data Investor telah ditambahkan!');
				redirect('lenders/overview', 'refresh');
			}
			else{
				$this->session->set_flashdata('message', 'error|Data Investor gagal diperbaharui.');
				redirect('lenders/overview', 'refresh');
			}
		}
	}


}	