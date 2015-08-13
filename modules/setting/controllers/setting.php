<?php

class Setting extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Setting';
	private $module 	= 'Setting';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('group_model');
		$this->load->model('officer_model');
		$this->load->model('area_model');
		$this->load->model('target_model');
		$this->load->model('branch_model');
		$this->load->model('users_model');
		$this->load->library('pagination');	
	}
	
	public function index($page='0'){
		if($this->session->userdata('logged_in'))
		{
			redirect('setting/branch', 'refresh');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function branch($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$total_rows = $this->branch_model->count_all($this->input->post('q'));
			
			//pagination
			$config['base_url']     = site_url($this->module.'/branch');
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
			
			$branch = $this->branch_model->get_all( $config['per_page'] ,$page,$this->input->post('q'));			
				
			$this->template	->set('menu_title', 'Branch Office')
							->set('menu_setting', 'active')
							->set('group_total',$config['total_rows'])
							->set('branch', $branch)
							->set('no', $no)
							->set('config', $config)
							->build('branch');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}	
	
	public function branch_register(){
		if($this->save_branch()){
			$this->session->set_flashdata('message', 'success|Cabang telah ditambahkan');
			redirect($this->module.'/');
		}
			
			$area = $this->area_model->get_all()->result();			
			
			$this->template	->set('menu_title', 'Add New Branch')
							->set('area', $area)
							->set('menu_setting', 'active')
							->build('branch_form');	
		
	}
	
	public function branch_edit(){
		$id =  $this->uri->segment(3);
		//$data = $this->group_model->find($id);
		
		if($this->save_branch()){
			$this->session->set_flashdata('message', 'success|Cabang telah diedit');
			redirect($this->module.'/');
		}
		//GET SPECIFIC PROJECT
		$data = $this->branch_model->get_branch($id)->result();
		$data = $data[0];
		$area = $this->area_model->get_all()->result();			
		$this->template	->set('data', $data)
						->set('menu_title', 'Edit Branch')
						->set('area', $area)
						->set('menu_setting', 'active')
						->build('branch_form');	
	}
	
	
	
	public function branch_view(){
		$id =  $this->uri->segment(3);
		
		//GET SPECIFIC PROJECT
		$data = $this->group_model->get_group($id)->result();
		$data = $data[0];
		$this->template	->set('data', $data)
						->set('menu_title', 'View Branch')
						->set('menu_setting', 'active')
						->build('branch_view');	
	}
	
	
	public function branch_delete($id = '0'){
		$this->module = "branch";
		$id =  $this->uri->segment(3);
			if($this->branch_model->delete($id)){
				$this->session->set_flashdata('message', 'success|Cabang telah dihapus');
				redirect('setting/branch');
				exit;
			}
	}	
	
	private function save_branch(){
		
		//set form validation
		$this->form_validation->set_rules('branch_area', 'Area', 'required');
		$this->form_validation->set_rules('branch_name', 'Password', 'required');
		$this->form_validation->set_rules('branch_code', 'Branch Code', 'required');
		$this->form_validation->set_rules('branch_number', 'Branch Number', 'required');
		$this->form_validation->set_rules('branch_location', 'Location', 'required');
		$this->form_validation->set_rules('branch_leader', 'Leader', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('branch_id');
			
			//process the form
			$data = array(
					'branch_area'       	=> $this->input->post('branch_area'),
					'branch_code'       	=> $this->input->post('branch_code'),
					'branch_name'       	=> $this->input->post('branch_name'),
					'branch_number' 		=> $this->input->post('branch_number'),
					'branch_location' 		=> $this->input->post('branch_location'),	
					'branch_leader'	    	=> $this->input->post('branch_leader'),
					
			);
				
			if(!$id){
				return $this->branch_model->insert($data);
			}else{
				return $this->branch_model->update($id, $data);
			} 
		}
	}
	
	// #TARGET
	public function target_ops($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$total_rows = $this->target_model->count_all_target($this->input->post('q'));
			
			//pagination
			$config['base_url']     	= site_url($this->module.'/target_ops');
			$config['total_rows']   	= $total_rows;
			$config['per_page']     	= 15; 
			$config['uri_segment']  	= 3;
			$config['suffix'] 			= '?' . http_build_query($_GET, '', "&");
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
			
			$target = $this->target_model->get_some_target_per_officer( $config['per_page'] ,$page,$this->input->post('q'));
			//var_dump($target);			
			$this->template	->set('menu_title', 'Target Operasional')
							->set('menu_setting', 'active')
							->set('target', $target)
							->set('no', $no)
							->set('config', $config)
							->build('target_ops');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}	
	
	public function target_ops_register(){
		if($this->save_target_ops()){
			$this->session->set_flashdata('message', 'success|Target Parameter telah ditambahkan');
			redirect('setting/target_ops');
		}
		$officer_list = $this->officer_model->get_list_officer(); //var_dump($officer_list);
		$branch_list  = $this->branch_model->get_all_branch(); //var_dump($branch_list);
			
		$this->template->set('menu_title', 'Add New Target Parameter')
					   ->set('menu_setting', 'active')
					   ->set('officer', $officer_list)
					   ->set('branch', $branch_list)
					   ->set('form_type', 'registration')
				       ->build('target_ops_form');	
		
	}
	
	public function target_ops_edit(){
		
		if($this->save_target_ops()){
			$this->session->set_flashdata('message', 'success|Target Parameter telah diedit');
			redirect('setting/target_ops');
			exit;
		}
		//GET SPECIFIC TARGET
		$id     =  $this->uri->segment(3);
		$target =  $this->target_model->get_target_by_id($id);
		$officer_list = $this->officer_model->get_list_officer(); 
		$branch_list  = $this->branch_model->get_all_branch(); 

		$this->template	->set('target', $target)
						->set('menu_title', 'Edit Target Parameter')
						->set('menu_setting', 'active')
						->set('officer', $officer_list)
					    ->set('branch', $branch_list)
						->set('form_type', 'edit')
						->build('target_ops_form');	
	}
	
		
	public function target_ops_delete($id = '0'){
		$tid = $this->uri->segment(3);
			if($this->target_model->delete($tid)){
			   $this->session->set_flashdata('message', 'success|Target Parameter telah dihapus');
				redirect('setting/target_ops');
			}
	}	
	
	private function save_target_ops(){
		//set form validation
		$this->form_validation->set_rules('target_category', 'Kategori Target', 'required');
		$this->form_validation->set_rules('target_item', 'Item Target', 'required');
		$this->form_validation->set_rules('target_bydate', 'Jatuh Tempo Target', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$data = array(
					'target_category'   => $this->input->post('target_category'),
					'target_item'       => $this->input->post('target_item'),
					'target_officer'    => $this->input->post('target_officer'),
					'target_branch'     => $this->input->post('target_branch'),
					'target_amount'     => $this->input->post('target_amount'),
					'target_bydate'	    => $this->input->post('target_bydate'),
					'target_remarks'    => $this->input->post('target_remarks'),
					'created_by'        => $this->session->userdata('user_id')
			);
			$tid = $this->input->post('tid');
			if(!$tid){
				return $this->db->insert('tbl_target', $data);
			}else{
				$where = array('target_id' => $tid);
				return $this->db->update('tbl_target', $data, $where);
			} 
		}
	}

	public function area($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$total_rows = $this->area_model->count_all_area($this->input->post('q'));
			
			//pagination
			$config['base_url']     = site_url($this->module.'/area');
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
			
			$branch = $this->area_model->get_all_area( $config['per_page'] ,$page,$this->input->post('q'));			
				
			$this->template	->set('menu_title', 'Area')
							->set('menu_setting', 'active')
							->set('group_total',$config['total_rows'])
							->set('branch', $branch)
							->set('no', $no)
							->set('config', $config)
							->build('area');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}	
	
	public function area_register(){
		if($this->save_area()){
			$this->session->set_flashdata('message', 'success|Area telah ditambahkan');
			redirect('setting/area');
		}
			
			
			$this->template	->set('menu_title', 'Add New Area')
							->set('menu_setting', 'active')
							->build('area_form');	
		
	}
	
	public function area_edit(){
		$id =  $this->uri->segment(3);
		//$data = $this->group_model->find($id);
		
		if($this->save_area()){
			$this->session->set_flashdata('message', 'success|Area telah diedit');
			redirect('setting/area');
		}
		//GET SPECIFIC PROJECT
		$data = $this->area_model->get_area($id)->result();
		$data = $data[0];
		$this->template	->set('data', $data)
						->set('menu_title', 'Edit Area')
						->set('menu_setting', 'active')
						->build('area_form');	
	}
	
		
	public function area_delete($id = '0'){
		$this->module = "area";
		$id =  $this->uri->segment(3);
			if($this->area_model->delete($id)){
				$this->session->set_flashdata('message', 'success|Area telah dihapus');
				redirect('setting/area');
				exit;
			}
	}	
	
	private function save_area(){
		
		//set form validation
		$this->form_validation->set_rules('area_name', 'Area Name', 'required');
		$this->form_validation->set_rules('area_code', 'Area Code', 'required');
		$this->form_validation->set_rules('area_leader', 'Leader', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('area_id');
			
			//process the form
			$data = array(
					'area_name'       	=> $this->input->post('area_name'),
					'area_code'       	=> $this->input->post('area_code'),
					'area_leader'	    => $this->input->post('area_leader'),
					
			);
				
			if(!$id){
				return $this->area_model->insert($data);
			}else{
				return $this->area_model->update($id, $data);
			} 
		}
	}
}