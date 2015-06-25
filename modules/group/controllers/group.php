<?php

class Group extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Group';
	private $module 	= 'group';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('group_model');
		$this->load->model('officer_model');
		$this->load->model('area_model');
		$this->load->model('branch_model');
		$this->load->model('clients_model');
		$this->load->library('pagination');	
	}
	
	public function index($page='0'){
		if($this->session->userdata('logged_in'))
		{
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			//$total_rows = $this->group_model->count_all($this->input->post('q'));
			//Get Total Group Row 
			if($user_branch != 0){
				$total_rows = $this->group_model->count_all_group_branch($this->input->post('q'),$user_branch);
			}else{
				$total_rows = $this->group_model->count_all_group($this->input->post('q'));
			}
			
			//pagination
			$config['base_url']     = site_url($this->module.'/all');
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
			
			//$this->pagination->initialize($config);
			$no =  $this->uri->segment(3);
			
			//$group = $this->group_model->get_group()->result();	
			//$group = $this->group_model->get_all_group( $config['per_page'] ,$page,$this->input->post('q'));			
			
			if($user_branch != 0){	
				$group = $this->group_model->get_all_group_branch( $config['per_page'] ,$page,$this->input->post('q'),$user_branch);			
			}else{
				$group = $this->group_model->get_all_group( $config['per_page'] ,$page,$this->input->post('q'));	
			}	
			
			$this->template	->set('menu_title', 'Data Majelis')
							->set('menu_group', 'active')
							->set('group_total',$config['total_rows'])
							->set('group', $group)
							->set('no', $no)
							//->set('config', $config)
							->build('group');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function all($page='0'){
		if($this->session->userdata('logged_in'))
		{
			//$total_rows = $this->group_model->count_all($this->input->post('q'));
			//Cek User Login Branch
			$user_branch = $this->session->userdata('user_branch');
			
			//Get Total Group Row 
			if($user_branch != 0){
				$total_rows = $this->group_model->count_all_group_branch($this->input->post('q'),$user_branch);
			}else{
				$total_rows = $this->group_model->count_all_group($this->input->post('q'));
			}
			
			//pagination
			$config['base_url']     = site_url($this->module.'/all');
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
			
			//$group = $this->group_model->get_group()->result();	
			//$group = $this->group_model->get_all_group( $config['per_page'] ,$page,$this->input->post('q'));		
			
			if($user_branch != 0){	
				$group = $this->group_model->get_all_group_branch( $config['per_page'] ,$page,$this->input->post('q'),$user_branch);			
			}else{
				$group = $this->group_model->get_all_group( $config['per_page'] ,$page,$this->input->post('q'));	
			}	
			
			$this->template	->set('menu_title', 'Data Majelis')
							->set('menu_group', 'active')
							->set('group_total',$config['total_rows'])
							->set('group', $group)
							->set('no', $no)
							->set('config', $config)
							->build('group');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function register(){
		if($this->save_group()){
			$this->session->set_flashdata('message', 'success|Majelis telah ditambahkan');
			redirect($this->module.'/');
		}
			
			$officer = $this->officer_model->get_all_officer( $config['per_page'] ,$page,$this->input->get('q'));
			$branch = $this->branch_model->get_all()->result();			
			$area = $this->area_model->get_all()->result();
			
			$this->template	->set('menu_title', 'Registrasi Majelis')
							->set('officer', $officer)
							->set('branch', $branch)
							->set('area', $area)
							->set('menu_group', 'active')
							->build('group_form');	
		
	}
	
	public function edit(){
		$id =  $this->uri->segment(3);
		//$data = $this->group_model->find($id);
		
		if($this->save_group()){
			$this->session->set_flashdata('message', 'success|Majelis telah diedit');
			redirect($this->module.'/');
		}
		//GET SPECIFIC PROJECT
		$data = $this->group_model->get_group($id)->result();
		$data = $data[0];
		$officer = $this->officer_model->get_all_officer( $config['per_page'] ,$page,$this->input->get('q'));
		$branch = $this->branch_model->get_all()->result();			
		$area = $this->area_model->get_all()->result();
		$this->template	->set('data', $data)
						->set('menu_title', 'Edit Majelis')
						->set('officer', $officer)
						->set('branch', $branch)
						->set('area', $area)
						->set('menu_group', 'active')
						->build('group_form');	
	}
	
	public function view(){
		$id =  $this->uri->segment(3);
		
		//GET SPECIFIC PROJECT
		$data = $this->group_model->get_group($id)->result();
		$data = $data[0];
		
		$clients_active = $this->clients_model->get_client_active($id)->result();
		$clients_notactive = $this->clients_model->get_client_notactive($id)->result();
		
		$this->template	->set('data', $data)
						->set('menu_title', 'Majelis')
						->set('groupid', $id)
						->set('clients', $clients_active)
						->set('clients_out', $clients_notactive)
						->set('menu_branch', 'active')
						->build('group_view');	
	}
	
	
	public function delete($id = '0'){
		$this->module = "group";
		$id =  $this->uri->segment(3);
			if($this->group_model->delete($id)){
				$this->session->set_flashdata('message', 'success|Majelis telah dihapus');
				redirect('group/');
				exit;
			}
	}	
	
	private function save_group(){
		
		//set form validation
		$this->form_validation->set_rules('group_area', 'Area', 'required');
		$this->form_validation->set_rules('group_branch', 'Kantor Cabang', 'required');
		$this->form_validation->set_rules('group_tpl', 'Petugas Pendamping', 'required');
		$this->form_validation->set_rules('group_name', 'Nama Majelis', 'required');
		$this->form_validation->set_rules('group_date', 'Tanggal Terbentuk', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('group_id');
			
			//CHECK GROUP NUMBER
			if($this->input->post('group_number') == "" OR $this->input->post('group_number') == NULL){
				//GENERATE GROUP NUMBER
					//1. GET AREA & BRANCH NUMBER
					$area_id=$this->input->post('group_area');
					$area = $this->area_model->get_area($area_id)->result();
					$area_number = $area[0]->area_code;
					
					$branch_id=$this->input->post('group_branch');
					
					//2. GET GROUP LAST NUMBER
					$group_lets_get_number = $this->group_model->get_group_by_branch($branch_id);
					$group_get_number = $group_lets_get_number[0]->group_number;
					$group_get_no = $group_lets_get_number[0]->group_no;
					
					//3. GET GROUP YEAR
					$group_branch = $this->input->post('group_branch');				
					
					//4. GET GROUP YEAR
					$group_year = $this->input->post('group_date');
					$group_year = substr($group_year, -2);
					
					//5. SET GROUP NUMBER
					if(!$group_get_number){
						$group_number = $area_number.$group_branch.$group_year."001";
						$group_no = 1;
					}else{						
						$group_number = $group_get_number+1;
						$group_no = $group_get_no+1;
					}
				//END OF GENERATE ACCOUNT NUMBER	
			}else{
				$group_no=$this->input->post('group_no');
				$group_number=$this->input->post('group_number');
			}	
			
			//process the form
			$data = array(
					'group_name'       		=> $this->input->post('group_name'),
					'group_number' 			=> $group_number,	
					'group_no' 				=> $group_no,			
					'group_area'       		=> $this->input->post('group_area'),
					'group_branch'	    	=> $this->input->post('group_branch'),
					'group_leader'	    	=> $this->input->post('group_leader'),
					'group_leaderphone'	    => $this->input->post('group_leaderphone'),
					'group_date'	    	=> $this->input->post('group_date'),
					'group_kampung'	    	=> $this->input->post('group_kampung'),
					'group_desa'	    	=> $this->input->post('group_desa'),
					'group_rt'	    		=> $this->input->post('group_rt'),
					'group_tanggungrenteng'	=> '1',
					'group_frequency'  		=> $this->input->post('group_frequency'),
					'group_tpl'	 			=> $this->input->post('group_tpl'),
					'group_schedule_day'	=> $this->input->post('group_schedule_day'),
					'group_schedule_time'	=> $this->input->post('group_schedule_time'),
					'group_address'			=> $this->input->post('group_address'),
					'group_address_rt'		=> $this->input->post('group_address_rt'),
					'group_address_rw'		=> $this->input->post('group_address_rw'),
					'group_kecamatan'		=> $this->input->post('group_kecamatan'),
			);
				
			if(!$id){
				return $this->group_model->insert($data);
			}else{
				return $this->group_model->update($id, $data);
			} 
		}
	}
}