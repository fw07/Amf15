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
		$this->load->model('investment_model');
		$this->load->library('pagination');		
	}
	
	public function index($page='0'){
		if($this->session->userdata('logged_in'))
		{
			//$alllenders = $this->lenders_model->get_all_lenders()->result();
			$total_rows = $this->lenders_model->count_all_lenders();
			
			//pagination
			$config['base_url']     	= site_url($this->module.'/index');
			$config['total_rows']   	= $total_rows;
			$config['per_page']     	= 5; 
			$config['uri_segment']  	= 3;
			$config['first_url'] 		= $config['base_url'] . $config['suffix'];
			$config['num_links'] 		= 2;
			$config['full_tag_open'] 	= '<li>';
			$config['full_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li><a href="#"><b>';
			$config['cur_tag_close'] 	= '</b></a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			
			$this->pagination->initialize($config); 
			$no =  $this->uri->segment(3);
			$lenders = $this->lenders_model->get_some_lenders( $config['per_page'] , $page, $this->input->post('q'), $this->input->post('key'));
			
			$this->template->set('menu_title', 'Data Investor (Borrowers)')
						   ->set('menu_investor', 'active')
						   ->set('lenders', $lenders)
						   ->set('list', $list)
						   ->set('no', $no)
						   ->set('config', $config)
						   ->set('form_type', 'registration')
						   ->build('lenders');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
			
	}

	public function investment($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$total_rows = $this->investment_model->count_all_investments();
			
			//pagination
			$config['base_url']     	= site_url($this->module.'/investment');
			$config['total_rows']   	= $total_rows;
			$config['per_page']     	= 10; 
			$config['uri_segment']  	= 3;
			$config['first_url'] 		= $config['base_url'] . $config['suffix'];
			$config['num_links'] 		= 2;
			$config['full_tag_open'] 	= '<li>';
			$config['full_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li><a href="#"><b>';
			$config['cur_tag_close'] 	= '</b></a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			
			$this->pagination->initialize($config); 
			$no =  $this->uri->segment(3);
			$investments = $this->investment_model->get_some_investments($config['per_page'] , $page, $this->input->post('q'), $this->input->post('key'));
			//var_dump($investments);
			$this->template->set('menu_title', 'Catatan Investasi')
						   ->set('menu_investor', 'active')
						   ->set('investments', $investments)
						   ->set('list', $list)
						   ->set('no', $no)
						   ->set('config', $config)
						   ->set('form_type', 'investment_recap')
						   ->build('investments');
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

	public function investment_recap(){
		if($this->session->userdata('logged_in'))
		{
			$lenders = $this->lenders_model->get_all_active_lenders($search='', $key='');
			$this->template->set('menu_title', 'Rekap Investasi')
						   ->set('menu_investor', 'active')
						   ->set('form_type', 'registration')
						   ->set('investors', $lenders)
						   ->set('form_type', 'investment_recap')
						   ->build('lender_investment');	
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

	public function edit_investment(){
		if($this->session->userdata('logged_in'))
		{
			//GET DETAILS OF INVESTOR
			$lender_id      =  $this->uri->segment(3);
			$all_lenders    =  $this->lenders_model->get_all_active_lenders();
			$one_investment =  $this->investment_model->get_single_investment($lender_id)->row();
			//var_dump($one_investment);
			$this->template->set('menu_title', 'Edit Catatan Investasi')
						   ->set('menu_investor', 'active')
						   ->set('form_type', 'edit_investment')
						   ->set('lender_id', $lender_id)
						   ->set('all_lenders', $all_lenders)
						   ->set('one_investment', $one_investment)
						   ->build('lender_investment');	
		}else{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}

	public function delete(){
		$lender_id =  $this->uri->segment(3);
			if($this->lenders_model->delete_one_lender($lender_id)){
				$this->session->set_flashdata('message', 'success|Investor telah dihapus');
				redirect('lenders/');
				exit;
			}
	}

	public function delete_investment(){
		$investment_id =  $this->uri->segment(3);
			if($this->investment_model->delete_one_investment($investment_id)){
				$this->session->set_flashdata('message', 'success|Investasi telah dihapus');
				redirect('lenders/investment');
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
				redirect('lenders/index', 'refresh');
			}
			else{
				$this->session->set_flashdata('message', 'error|Data Investor gagal diperbaharui.');
				redirect('lenders/index', 'refresh');
			}
		}
	}

	public function save_investment(){
		//Set form validation
		
		$this->form_validation->set_rules('lender_id', 			'Kode Investor', 'required');
		$this->form_validation->set_rules('investment_type',    'Nama Investor', 'required');
		$this->form_validation->set_rules('investment_date',    'Tanggal Investasi', 'required');
		$this->form_validation->set_rules('investment_amount',  'Nilai Investasi', 'required');
		$this->form_validation->set_rules('investment_remarks', 'Catatan Investasi', 'required');
		
		if($this->form_validation->run() === true){
			$data = array(
					'lender_id'	     		=> $this->input->post('lender_id'),
					'investment_amount'	 	=> $this->input->post('investment_amount'),
					'investment_date'	   	=> $this->input->post('investment_date'),
					'investment_type'      	=> $this->input->post('investment_type'),
					'investment_remarks'    => $this->input->post('investment_remarks')
			);	
			
			//IS IT SAVE & UPDATE?
			//echo $this->input->post('iid').'<br/>'; var_dump($data);
			
			if($this->input->post('type') == 'investment_recap')
				$query = $this->investment_model->create_investment_details($data);
			else if($this->input->post('type') == 'edit_investment')
				$query = $this->investment_model->update_investment_details($this->input->post('iid'), $data);
			
			if($query){
				if($this->input->post('type') == 'edit')
					$this->session->set_flashdata('message', 'success|Data Investasi telah diupdate!');
				else if($this->input->post('type') == 'investment_recap')
					$this->session->set_flashdata('message', 'success|Data Investasi telah ditambahkan!');
				redirect('lenders/investment', 'refresh');
			}
			else{
				$this->session->set_flashdata('message', 'error|Data Investasi gagal diperbaharui.');
				redirect('lenders/investment', 'refresh');
			}
			
		}
	}


}	