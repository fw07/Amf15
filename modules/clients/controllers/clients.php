<?php

class Clients extends Front_Controller{
	
	private $per_page 	= '15';
	private $title 		= 'Clients';
	private $module 	= 'clients';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('clients_model');
		$this->load->model('clients_masterdata_model');
		$this->load->model('group_model');
		$this->load->model('officer_model');
		$this->load->model('clients_pembiayaan_model');
		$this->load->model('tabsukarela_model');
		$this->load->model('tabwajib_model');
		$this->load->model('sector_model');
		
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
		
			$clients = $this->clients_model->get_all_clients( $config['per_page'] , $page, $this->input->post('q'), $this->input->post('key'));

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
			$this->session->set_flashdata('message', 'success|Anggota Baru telah ditambahkan');
			redirect($this->module.'/anggota');
		}
		
		$officer = $this->officer_model->get_all_officer();
		$group = $this->group_model->get_all();
		$investor = $this->clients_model->get_all_investors_list();
		$this->template	->set('menu_title', 'Registrasi Anggota')
							->set('client', $data)
							->set('group', $group)
							->set('officer', $officer)
							->set('investor', $investor)
							->set('menu_client', 'active')
							->build('client_register');	
		
	}
	
	public function edit(){		
		
		if($this->save_client()){
			$this->session->set_flashdata('message', 'success|Data telah diedit');
			redirect($this->module.'/anggota');
		}
		
		//GET DETAILS ANGGOTA
		$client_id =  $this->uri->segment(3);
		$data = $this->clients_model->get_client($client_id)->result();
		$data = $data[0];
		
		$officer = $this->officer_model->get_all_officer();
		$group = $this->group_model->get_all();
		$investor = $this->clients_model->get_all_investors_list();
		$this->template	->set('client', $data)
						->set('group', $group)
						->set('officer', $officer)
						->set('investor', $investor)
						->set('menu_title', 'Edit Anggota')
						->set('menu_client', 'active')
						->build('client_register');	
	}
	
	public function view(){
		//GET DETAILS ANGGOTA
		$client_id =  $this->uri->segment(3);
		$data = $this->clients_model->get_client($client_id)->result();
		$data = $data[0];
		
		$this->template	->set('client', $data)
						->set('menu_title', 'View Anggota')
						->set('menu_client', 'active')
						->build('client_view');	
	}
	
	public function delete($id = '0'){
		$id =  $this->uri->segment(3);
			if($this->clients_model->delete($id)){
				$this->session->set_flashdata('message', 'success|Anggota telah dihapus');
				redirect('clients/anggota');
				exit;
			}
	}
	
	private function save_client(){
		//set form validation
		
		$this->form_validation->set_rules('client_group', 'Majelis', 'required');
		$this->form_validation->set_rules('client_fullname', 'Nama Lengkap', 'required');
		$this->form_validation->set_rules('client_birthdate', 'Tanggal Lahir', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('client_id');
			if($this->input->post('client_account') == "" OR $this->input->post('client_account') == NULL){				
				//GENERATE ACCOUNT NUMBER
				
					//1. GET GROUP NUMBER
					$group_id=$this->input->post('client_group');
					$group = $this->group_model->get_group($group_id)->result();
					$group_number = $group[0]->group_number;
					
					//2. GET CLIENT LAST ACCOUNT NUMBER
					$client_get_last_account = $this->clients_model->get_clientmax_by_group($group_id);
					$client_get_account = $client_get_last_account[0]->client_account;
					$client_get_no = $client_get_last_account[0]->client_no;
					
					//3. SET CLIENT ACCOUNT NUMBER
					if(!$client_get_account){
						$client_account = $group_number."001";
						$client_no = 1;
					}else{
						$client_no = $client_get_no+1;
						$client_account = ($group_number * 1000)+$client_no;
					}
					
				//END OF GENERATE ACCOUNT NUMBER	
			}else{
				$client_account=$this->input->post('client_account');
				$client_no=$this->input->post('client_no');
			}	
			
			//process the form
			$data = array(
					'client_group'	     		=> $this->input->post('client_group'),
					'client_subgroup'	   		=> $this->input->post('client_subgroup'),
					'client_officer'      		=> $this->input->post('client_officer'),
					'client_account'	    	=> $client_account,
					'client_no'	    			=> $client_no,
					'client_fullname'      		=> $this->input->post('client_fullname'),
					'client_simplename'	    	=> $this->input->post('client_simplename'),
					'client_martialstatus'		=> $this->input->post('client_martialstatus'),
					'client_birthplace'	    	=> $this->input->post('client_birthplace'),
					'client_birthdate'	    	=> $this->input->post('client_birthdate'),
					'client_rt'					=> $this->input->post('client_rt'),
					'client_rw'					=> $this->input->post('client_rw'),
					'client_kampung'			=> $this->input->post('client_kampung'),
					'client_desa'				=> $this->input->post('client_desa'),
					'client_kecamatan'			=> $this->input->post('client_kecamatan'),
					'client_ktp'				=> $this->input->post('client_ktp'),
					'client_religion'			=> $this->input->post('client_religion'),
					'client_education'			=> $this->input->post('client_education'),
					'client_job'				=> $this->input->post('client_job'),
					'client_comodity'			=> $this->input->post('client_comodity'),
					'client_phone'				=> $this->input->post('client_phone'),
					'client_pembiayaan_sumber'	=> $this->input->post('client_pembiayaan_sumber')
			);
			
			if(!$id)
				return $this->clients_model->insert($data);
			else
				return $this->clients_model->update($id, $data);
			 
		}
	}
	
	public function list_pembiayaan($page='0')
	{
		if($this->session->userdata('logged_in'))
		{
		
			$total_rows = $this->clients_model->count_all($this->input->get('q'));
				
			//pagination
			$config['base_url']     = site_url($this->module.'/list_pembiayaan');
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
		
			$pembiayaan = $this->clients_pembiayaan_model->get_all( $config['per_page'] , $page, $this->input->post('q'), $this->input->post('key'));

			$this->template	->set('menu_title', 'Pembiayaan')
							->set('menu_client', 'active')
							->set('pembiayaan', $pembiayaan)
							->set('list', $list)
							->set('no', $no)
							->set('config', $config)
							->build('client_list_pembiayaan');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function pembiayaan(){
		$data_id =  $this->uri->segment(3);
		
		if($this->save_pembiayaan()){
			$this->session->set_flashdata('message', 'success|Data pembiayaan telah diupdate');
			redirect($this->module.'/pembiayaan/'.$data_id);
		}
		
		//GET PEMBIAYAAN
		$data_id =  $this->uri->segment(3);
		$data = $this->clients_pembiayaan_model->get_pembiayaan($data_id)->result();
		$data = $data[0];
		
		//GET DETAILS ANGGOTA
		$client_id =  $data->data_client; 
		$client = $this->clients_model->get_client($client_id)->result();
		$client = $client[0];
		//GET OFFICER LIST
		$officer = $this->officer_model->get_all_officer();
		//GET GROUP LIST
		$group = $this->group_model->get_all();
		
		$this->template	->set('menu_title', 'Pembiayaan')
						->set('client', $client)
						->set('group', $group)
						->set('officer', $officer)
						->set('data_client', $client_id)
						->set('data', $data)						
						->set('menu_client', 'active')
						->build('client_pembiayaan');		
	}
	
	public function pembiayaan_reg(){
		$data_id =  $this->uri->segment(3);
		if($this->save_pembiayaan()){
			$this->session->set_flashdata('message', 'success|Data pembiayaan telah ditambahkan');
			redirect($this->module.'/summary/'.$data_id);
		}
				
		//GET DETAILS ANGGOTA
		$client_id =  $this->uri->segment(3); 
		$client = $this->clients_model->get_client($client_id)->result();
		$client = $client[0];
		//GET OFFICER LIST
		$officer = $this->officer_model->get_all_officer();
		//GET GROUP LIST
		$group = $this->group_model->get_all();
		
		$this->template	->set('menu_title', 'Pembiayaan')
						->set('client', $client)
						->set('group', $group)
						->set('officer', $officer)
						->set('data_client', $client_id)
						->set('data', $data)						
						->set('menu_client', 'active')
						->build('client_pembiayaan');		
	}
	
	
	public function pembiayaan_edit(){
		$data_id =  $this->uri->segment(3);
		
		//GET PEMBIAYAAN
		$data_id =  $this->uri->segment(3);
		$data = $this->clients_pembiayaan_model->get_pembiayaan($data_id)->result();
		$data = $data[0];
		
		//GET DETAILS ANGGOTA
		$client_id =  $data->data_client; 
		$client = $this->clients_model->get_client($client_id)->result();
		$client = $client[0];
		//GET OFFICER LIST
		$officer = $this->officer_model->get_all_officer();
		//GET GROUP LIST
		$group = $this->group_model->get_all();
		$sector = $this->sector_model->get_all()->result();

		$investor = $this->clients_model->get_all_investors_list();
		
		if($this->save_pembiayaan()){
			$this->session->set_flashdata('message', 'success|Data pembiayaan telah diupdate');
			redirect($this->module.'/summary/'.$client_id);
		}
		
		$this->template	->set('menu_title', 'Pembiayaan')
						->set('client', $client)
						->set('group', $group)
						->set('officer', $officer)
						->set('investor', $investor)
						->set('data_client', $client_id)
						->set('data', $data)						
						->set('menu_client', 'active')				
						->set('sector', $sector)
						->build('client_pembiayaan');		
	}
	
	public function pembiayaan_view(){
		$data_id =  $this->uri->segment(3);		
		
		//GET PEMBIAYAAN
		$data_id =  $this->uri->segment(3);
		$data = $this->clients_pembiayaan_model->get_pembiayaan($data_id)->result();
		$data = $data[0];
		
		//GET DETAILS ANGGOTA
		$client_id =  $data->data_client;
		$client = $this->clients_model->get_client($client_id)->result();
		$client = $client[0];
		//GET OFFICER LIST
		$officer = $this->officer_model->get_all_officer();
		//GET GROUP LIST
		$group = $this->group_model->get_all();
		
		$this->template	->set('menu_title', 'Pembiayaan')
						->set('client', $client)
						->set('group', $group)
						->set('officer', $officer)
						->set('data_client', $client_id)
						->set('data', $data)						
						->set('menu_client', 'active')
						->build('client_pembiayaan_view');		
	}
	
	private function save_pembiayaan(){		
		
		//set form validation		
		$this->form_validation->set_rules('data_client', 'Client', 'required');	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('data_id');
			
			//CALCULATE POPI INDEX
				$score_popi=0;
				$data_popi_anggotart		= $this->input->post('data_popi_anggotart');
				$data_popi_masihsekolah		= $this->input->post('data_popi_masihsekolah');
				$data_popi_pendidikanistri	= $this->input->post('data_popi_pendidikanistri');
				$data_popi_pekerjaansuami	= $this->input->post('data_popi_pekerjaansuami');
				$data_popi_jenislantai		= $this->input->post('data_popi_jenislantai');
				$data_popi_jeniswc			= $this->input->post('data_popi_jeniswc');
				$data_popi_bahanbakar		= $this->input->post('data_popi_bahanbakar');
				$data_popi_gas				= $this->input->post('data_popi_gas');
				$data_popi_kulkas			= $this->input->post('data_popi_kulkas');
				$data_popi_motor			= $this->input->post('data_popi_motor');
				
				if($data_popi_anggotart=="A"){ $score_popi += 0 ; }
				elseif($data_popi_anggotart=="B"){ $score_popi += 5 ; }
				elseif($data_popi_anggotart=="C"){ $score_popi += 11 ; }
				elseif($data_popi_anggotart=="D"){ $score_popi += 18 ; }
				elseif($data_popi_anggotart=="E"){ $score_popi += 24 ; }
				elseif($data_popi_anggotart=="F"){ $score_popi += 37 ; }
				else{ $score_popi += 0 ; }
				
				if($data_popi_masihsekolah=="A"){ $score_popi += 0 ; }
				elseif($data_popi_masihsekolah=="B"){ $score_popi += 0 ; }
				elseif($data_popi_masihsekolah=="C"){ $score_popi += 2 ; }
				else{ $score_popi += 0 ; }
				
				if($data_popi_pendidikanistri=="A"){ $score_popi += 0 ; }
				elseif($data_popi_pendidikanistri=="B"){ $score_popi += 3 ; }
				elseif($data_popi_pendidikanistri=="C"){ $score_popi += 4 ; }
				elseif($data_popi_pendidikanistri=="D"){ $score_popi += 4 ; }
				elseif($data_popi_pendidikanistri=="E"){ $score_popi += 4 ; }
				elseif($data_popi_pendidikanistri=="F"){ $score_popi += 6 ; }
				elseif($data_popi_pendidikanistri=="G"){ $score_popi += 18 ; }
				else{ $score_popi += 0 ; }
				
				if($data_popi_pekerjaansuami=="A"){ $score_popi += 0 ; }
				elseif($data_popi_pekerjaansuami=="B"){ $score_popi += 0 ; }
				elseif($data_popi_pekerjaansuami=="C"){ $score_popi += 1 ; }
				elseif($data_popi_pekerjaansuami=="D"){ $score_popi += 3 ; }
				elseif($data_popi_pekerjaansuami=="E"){ $score_popi += 3 ; }
				elseif($data_popi_pekerjaansuami=="F"){ $score_popi += 6 ; }
				else{ $score_popi += 0 ; }
				
				if($data_popi_jenislantai=="A"){ $score_popi += 0 ; }
				elseif($data_popi_jenislantai=="B"){ $score_popi += 5 ; }
				else{ $score_popi += 0 ; }
				
				if($data_popi_jeniswc=="A"){ $score_popi += 0 ; }
				elseif($data_popi_jeniswc=="B"){ $score_popi += 1 ; }
				elseif($data_popi_jeniswc=="C"){ $score_popi += 4 ; }
				else{ $score_popi += 0 ; }
				
				if($data_popi_bahanbakar=="A"){ $score_popi += 0 ; }
				elseif($data_popi_bahanbakar=="B"){ $score_popi += 5 ; }
				else{ $score_popi += 0 ; }		
				
				if($data_popi_gas=="A"){ $score_popi += 0 ; }
				elseif($data_popi_gas=="B"){ $score_popi += 6 ; }
				else{ $score_popi += 0 ; }		
				
				if($data_popi_kulkas=="A"){ $score_popi += 0 ; }
				elseif($data_popi_kulkas=="B"){ $score_popi += 8 ; }
				else{ $score_popi += 0 ; }		
				
				if($data_popi_motor=="A"){ $score_popi += 0 ; }
				elseif($data_popi_motor=="B"){ $score_popi += 9 ; }
				else{ $score_popi += 0 ; }
				
				if($score_popi <= 25){ $kategori_popi = "D"; }
				elseif($score_popi > 25 AND $score_popi <= 50){ $kategori_popi = "C"; }
				elseif($score_popi > 50 AND $score_popi <= 75){ $kategori_popi = "B"; }
				elseif($score_popi > 75 AND $score_popi <= 100){ $kategori_popi = "A"; }
			
			//END OF CALCULATE POPI INDEX
			
			//CALCULATE RMC INDEX
				$score_rmc=0;
				$data_rmc_ukuranrumah		= $this->input->post('data_rmc_ukuranrumah');
				$data_rmc_kondisirumah		= $this->input->post('data_rmc_kondisirumah');
				$data_rmc_jenisatap			= $this->input->post('data_rmc_jenisatap');
				$data_rmc_jenisdinding		= $this->input->post('data_rmc_jenisdinding');
				$data_rmc_jenislantai		= $this->input->post('data_rmc_jenislantai');
				$data_rmc_listrik			= $this->input->post('data_rmc_listrik');
				$data_rmc_sumberair			= $this->input->post('data_rmc_sumberair');
				$data_rmc_kepemilikan		= $this->input->post('data_rmc_kepemilikan');
				$data_rmc_hargaperbulan		= $this->input->post('data_rmc_hargaperbulan');
				
				if($data_rmc_ukuranrumah=="A"){ $score_rmc += 3 ; }
				elseif($data_rmc_ukuranrumah=="B"){ $score_rmc += 1 ; }
				elseif($data_rmc_ukuranrumah=="C"){ $score_rmc += 0 ; }
				else{ $score_rmc += 0 ; }
				
				if($data_rmc_kondisirumah=="A"){ $score_rmc += 3 ; }
				elseif($data_rmc_kondisirumah=="B"){ $score_rmc += 1 ; }
				elseif($data_rmc_kondisirumah=="C"){ $score_rmc += 0 ; }
				else{ $score_rmc += 0 ; }
				
				if($data_rmc_jenisatap=="A"){ $score_rmc += 2 ; }
				elseif($data_rmc_jenisatap=="B"){ $score_rmc += 1 ; }
				elseif($data_rmc_jenisatap=="C"){ $score_rmc += 0 ; }
				else{ $score_rmc += 0 ; }
				
				if($data_rmc_jenisdinding=="A"){ $score_rmc += 2 ; }
				elseif($data_rmc_jenisdinding=="B"){ $score_rmc += 1 ; }
				elseif($data_rmc_jenisdinding=="C"){ $score_rmc += 0 ; }
				else{ $score_rmc += 0 ; }
				
				if($data_rmc_jenislantai=="A"){ $score_rmc += 2 ; }
				elseif($data_rmc_jenislantai=="B"){ $score_rmc += 1 ; }
				elseif($data_rmc_jenislantai=="C"){ $score_rmc += 0 ; }
				else{ $score_rmc += 0 ; }
				
				if($data_rmc_listrik=="A"){ $score_rmc += 2 ; }
				elseif($data_rmc_listrik=="B"){ $score_rmc += 1 ; }
				elseif($data_rmc_listrik=="C"){ $score_rmc += 0 ; }
				else{ $score_rmc += 0 ; }
				
				if($data_rmc_sumberair=="A"){ $score_rmc += 2 ; }
				elseif($data_rmc_sumberair=="B"){ $score_rmc += 1 ; }
				elseif($data_rmc_sumberair=="C"){ $score_rmc += 0 ; }
				else{ $score_rmc += 0 ; }
				
				if($score_rmc <= 8){ $kategori_rmc = "D"; }
				elseif($score_rmc > 8 AND $score_rmc <= 10){ $kategori_rmc = "C"; }
				elseif($score_rmc > 10 AND $score_rmc <= 11){ $kategori_rmc = "B"; }
				elseif($score_rmc > 11 AND $score_rmc <= 15){ $kategori_rmc = "A"; }
				
				
			//END OF CALCULATE RMC INDEX
			
			//process the form
			$data = array(
					//'data_client'	     			=> $this->input->post('data_client'),
					'data_ke'						=> $this->input->post('data_ke'),
					'data_pengajuan'						=> $this->input->post('data_pengajuan'),
					'data_tgl'						=> $this->input->post('data_tgl'),
					'data_date_accept'				=> $this->input->post('data_date_accept'),
					'data_date_first'				=> $this->input->post('data_date_first'),
					'data_tgl'						=> $this->input->post('data_tgl'),
					'data_tujuan'					=> $this->input->post('data_tujuan'),
					'data_plafond'					=> $this->input->post('data_plafond'),
					'data_jangkawaktu'				=> $this->input->post('data_jangkawaktu'),
					'data_akad'						=> $this->input->post('data_akad'),
					'data_totalangsuran'			=> $this->input->post('data_totalangsuran'),
					'data_angsuranpokok'			=> $this->input->post('data_angsuranpokok'),
					'data_tabunganwajib'			=> $this->input->post('data_tabunganwajib'),
					'data_margin'					=> $this->input->post('data_margin'),
					'data_angsuranke'				=> $this->input->post('data_angsuranke'),
					'data_status'					=> $this->input->post('data_status'),
					'data_sector'					=> $this->input->post('data_sector'),
					
					'data_pembiayaan1_nama'	     	=> $this->input->post('data_pembiayaan1_nama'),
					'data_pembiayaan1_lama'      	=> $this->input->post('data_pembiayaan1_lama'),
					'data_pembiayaan1_plafond'      => $this->input->post('data_pembiayaan1_plafond'),
					'data_pembiayaan1_total'	    => $this->input->post('data_pembiayaan1_total'),
					'data_pembiayaan1_status'		=> $this->input->post('data_pembiayaan1_status'),
					'data_pembiayaan2_nama'	     	=> $this->input->post('data_pembiayaan2_nama'),
					'data_pembiayaan2_lama'      	=> $this->input->post('data_pembiayaan2_lama'),
					'data_pembiayaan2_plafond'      => $this->input->post('data_pembiayaan2_plafond'),
					'data_pembiayaan2_total'	    => $this->input->post('data_pembiayaan2_total'),
					'data_pembiayaan2_status'		=> $this->input->post('data_pembiayaan2_status'),
					'data_pembiayaan3_nama'	     	=> $this->input->post('data_pembiayaan3_nama'),
					'data_pembiayaan3_lama'      	=> $this->input->post('data_pembiayaan3_lama'),
					'data_pembiayaan3_plafond'      => $this->input->post('data_pembiayaan3_plafond'),
					'data_pembiayaan3_total'	    => $this->input->post('data_pembiayaan3_total'),
					'data_pembiayaan3_status'		=> $this->input->post('data_pembiayaan3_status'),
					'data_pembiayaan4_nama'	     	=> $this->input->post('data_pembiayaan4_nama'),
					'data_pembiayaan4_lama'      	=> $this->input->post('data_pembiayaan4_lama'),
					'data_pembiayaan4_plafond'      => $this->input->post('data_pembiayaan4_plafond'),
					'data_pembiayaan4_total'	    => $this->input->post('data_pembiayaan4_total'),
					'data_pembiayaan4_status'		=> $this->input->post('data_pembiayaan4_status'),					
					
					'data_suami'					=> $this->input->post('data_suami'),
					'data_suami_tgllahir'			=> $this->input->post('data_suami_tgllahir'),
					'data_suami_pekerjaan'			=> $this->input->post('data_suami_pekerjaan'),
					'data_suami_komoditas'			=> $this->input->post('data_suami_komoditas'),
					'data_suami_pendidikan'			=> $this->input->post('data_suami_pendidikan'),
					'data_keluarga_anak'			=> $this->input->post('data_keluarga_anak'),
					'data_keluarga_belumsekolah'	=> $this->input->post('data_keluarga_belumsekolah'),
					'data_keluarga_tk'				=> $this->input->post('data_keluarga_tk'),
					'data_keluarga_tidaksekolah'	=> $this->input->post('data_keluarga_tidaksekolah'),
					'data_keluarga_tidaktamatsd'	=> $this->input->post('data_keluarga_tidaktamatsd'),
					'data_keluarga_sd'				=> $this->input->post('data_keluarga_sd'),
					'data_keluarga_smp'				=> $this->input->post('data_keluarga_smp'),
					'data_keluarga_sma'				=> $this->input->post('data_keluarga_sma'),
					'data_keluarga_kuliah'			=> $this->input->post('data_keluarga_kuliah'),
					'data_keluarga_tanggungan'		=> $this->input->post('data_keluarga_tanggungan'),
					
					'data_popi_anggotart'			=> $this->input->post('data_popi_anggotart'),
					'data_popi_masihsekolah'		=> $this->input->post('data_popi_masihsekolah'),
					'data_popi_pendidikanistri'		=> $this->input->post('data_popi_pendidikanistri'),
					'data_popi_pekerjaansuami'		=> $this->input->post('data_popi_pekerjaansuami'),
					'data_popi_jenislantai'			=> $this->input->post('data_popi_jenislantai'),
					'data_popi_jeniswc'				=> $this->input->post('data_popi_jeniswc'),
					'data_popi_bahanbakar'			=> $this->input->post('data_popi_bahanbakar'),
					'data_popi_gas'					=> $this->input->post('data_popi_gas'),
					'data_popi_kulkas'				=> $this->input->post('data_popi_kulkas'),
					'data_popi_motor'				=> $this->input->post('data_popi_motor'),
					'data_popi_total'				=> $score_popi ,
					'data_popi_kategori'			=> $kategori_popi ,
										
					'data_rmc_ukuranrumah'			=> $this->input->post('data_rmc_ukuranrumah'),
					'data_rmc_kondisirumah'			=> $this->input->post('data_rmc_kondisirumah'),
					'data_rmc_jenisatap'			=> $this->input->post('data_rmc_jenisatap'),
					'data_rmc_jenisdinding'			=> $this->input->post('data_rmc_jenisdinding'),
					'data_rmc_jenislantai'			=> $this->input->post('data_rmc_jenislantai'),
					'data_rmc_listrik'				=> $this->input->post('data_rmc_listrik'),
					'data_rmc_sumberair'			=> $this->input->post('data_rmc_sumberair'),
					'data_rmc_kepemilikan'			=> $this->input->post('data_rmc_kepemilikan'),
					'data_rmc_hargaperbulan'		=> $this->input->post('data_rmc_hargaperbulan'),
					'data_rmc_total'				=> $score_rmc ,
					'data_rmc_kategori'				=> $kategori_rmc ,
					
					'data_aset_lahan'				=> $this->input->post('data_aset_lahan'),					
					'data_aset_jumlahlahan'			=> $this->input->post('data_aset_jumlahlahan'),
					'data_aset_ternak'				=> $this->input->post('data_aset_ternak'),
					'data_aset_jumlahternak'		=> $this->input->post('data_aset_jumlahternak'),
					'data_aset_tabungan'			=> $this->input->post('data_aset_tabungan'),
					'data_aset_deposito'			=> $this->input->post('data_aset_deposito'),
					'data_aset_lain'				=> $this->input->post('data_aset_lain'),
					'data_aset_total'				=> $this->input->post('data_aset_total'),
					
					'data_pendapatan_suamijenisusaha'	=> $this->input->post('data_pendapatan_suamijenisusaha'),
					'data_pendapatan_suamilama'			=> $this->input->post('data_pendapatan_suamilama'),
					'data_pendapatan_suami'				=> $this->input->post('data_pendapatan_suami'),
					'data_pendapatan_istri'				=> $this->input->post('data_pendapatan_istri'),
					'data_pendapatan_istrijenisusaha'	=> $this->input->post('data_pendapatan_istrijenisusaha'),
					'data_pendapatan_istrilama'			=> $this->input->post('data_pendapatan_istrilama'),
					'data_pendapatan_lain'				=> $this->input->post('data_pendapatan_lain'),
					'data_pendapatan_lainjenisusaha'	=> $this->input->post('data_pendapatan_lainjenisusaha'),
					'data_pendapatan_lainlama'			=> $this->input->post('	data_pendapatan_lainlama'),
					'data_pendapatan_total'				=> $this->input->post('data_pendapatan_total'),
					
					'data_pengeluaran_dapur'			=> $this->input->post('data_pengeluaran_dapur'),
					'data_pengeluaran_rekening'			=> $this->input->post('data_pengeluaran_rekening'),
					'data_pengeluaran_pulsa'			=> $this->input->post('data_pengeluaran_pulsa'),
					'data_pengeluaran_kreditan'			=> $this->input->post('data_pengeluaran_kreditan'),
					'data_pengeluaran_arisan'			=> $this->input->post('data_pengeluaran_arisan'),
					'data_pengeluaran_pendidikan'		=> $this->input->post('data_pengeluaran_pendidikan'),
					'data_pengeluaran_umum'				=> $this->input->post('data_pengeluaran_umum'),
					'data_pengeluaran_angsuranlain'		=> $this->input->post('data_pengeluaran_angsuranlain'),
					'data_pengeluaran_total'			=> $this->input->post('data_pengeluaran_total'),
					'data_savingpower'					=> $this->input->post('data_savingpower'),
					'data_sumber_pembiayaan'			=> $this->input->post('client_pembiayaan_sumber')
					
			);
			
			if(!$id)
				return $this->clients_pembiayaan_model->insert($data);
			else
				return $this->clients_pembiayaan_model->update($id, $data);
			 
		}
	}
	
	public function pembiayaan_delete($id = '0'){
		$id =  $this->uri->segment(3);
		
		//GET PEMBIAYAAN
		$data_id =  $this->uri->segment(3);
		$data = $this->clients_pembiayaan_model->get_pembiayaan($data_id)->result();
		$data = $data[0];
		
		//GET DETAILS ANGGOTA
		$client_id =  $data->data_client; 
		
		
			if($this->clients_pembiayaan_model->delete($id)){
				
				
				$data = array(
					'data_status'	=> '0');
					
				$this->clients_pembiayaan_model->update($id, $data);
				
				$this->session->set_flashdata('message', 'success|Anggota telah dihapus');
				redirect('clients/summary/'.$client_id);
				exit;
			}
	}
	
	private function count_popi(){
		$score_popi=0;
		$data_popi_anggotart		= $this->input->post('data_popi_anggotart');
		$data_popi_masihsekolah		= $this->input->post('data_popi_masihsekolah');
		$data_popi_pendidikanistri	= $this->input->post('data_popi_pendidikanistri');
		$data_popi_pekerjaansuami	= $this->input->post('data_popi_pekerjaansuami');
		$data_popi_jenislantai		= $this->input->post('data_popi_jenislantai');
		$data_popi_jeniswc			= $this->input->post('data_popi_jeniswc');
		$data_popi_bahanbakar		= $this->input->post('data_popi_bahanbakar');
		$data_popi_gas				= $this->input->post('data_popi_gas');
		$data_popi_kulkas			= $this->input->post('data_popi_kulkas');
		$data_popi_motor			= $this->input->post('data_popi_motor');
		
		if($data_popi_anggotart=="A"){ $score_popi += 0 ; }
		elseif($data_popi_anggotart=="B"){ $score_popi += 5 ; }
		elseif($data_popi_anggotart=="C"){ $score_popi += 11 ; }
		elseif($data_popi_anggotart=="D"){ $score_popi += 18 ; }
		elseif($data_popi_anggotart=="E"){ $score_popi += 24 ; }
		elseif($data_popi_anggotart=="F"){ $score_popi += 37 ; }
		else{ $score_popi += 0 ; }
		
		if($data_popi_masihsekolah=="A"){ $score_popi += 0 ; }
		elseif($data_popi_masihsekolah=="B"){ $score_popi += 0 ; }
		elseif($data_popi_masihsekolah=="C"){ $score_popi += 2 ; }
		else{ $score_popi += 0 ; }
		
		if($data_popi_pendidikanistri=="A"){ $score_popi += 0 ; }
		elseif($data_popi_pendidikanistri=="B"){ $score_popi += 3 ; }
		elseif($data_popi_pendidikanistri=="C"){ $score_popi += 4 ; }
		elseif($data_popi_pendidikanistri=="D"){ $score_popi += 4 ; }
		elseif($data_popi_pendidikanistri=="E"){ $score_popi += 4 ; }
		elseif($data_popi_pendidikanistri=="F"){ $score_popi += 6 ; }
		elseif($data_popi_pendidikanistri=="G"){ $score_popi += 18 ; }
		else{ $score_popi += 0 ; }
		
		if($data_popi_pekerjaansuami=="A"){ $score_popi += 0 ; }
		elseif($data_popi_pekerjaansuami=="B"){ $score_popi += 0 ; }
		elseif($data_popi_pekerjaansuami=="C"){ $score_popi += 1 ; }
		elseif($data_popi_pekerjaansuami=="D"){ $score_popi += 3 ; }
		elseif($data_popi_pekerjaansuami=="E"){ $score_popi += 3 ; }
		elseif($data_popi_pekerjaansuami=="F"){ $score_popi += 6 ; }
		else{ $score_popi += 0 ; }
		
		if($data_popi_jenislantai=="A"){ $score_popi += 0 ; }
		elseif($data_popi_jenislantai=="B"){ $score_popi += 5 ; }
		else{ $score_popi += 0 ; }
		
		if($data_popi_jeniswc=="A"){ $score_popi += 0 ; }
		elseif($data_popi_jeniswc=="B"){ $score_popi += 1 ; }
		elseif($data_popi_jeniswc=="C"){ $score_popi += 4 ; }
		else{ $score_popi += 0 ; }
		
		if($data_popi_bahanbakar=="A"){ $score_popi += 0 ; }
		elseif($data_popi_bahanbakar=="B"){ $score_popi += 5 ; }
		else{ $score_popi += 0 ; }		
		
		if($data_popi_gas=="A"){ $score_popi += 0 ; }
		elseif($data_popi_gas=="B"){ $score_popi += 6 ; }
		else{ $score_popi += 0 ; }		
		
		if($data_popi_kulkas=="A"){ $score_popi += 0 ; }
		elseif($data_popi_kulkas=="B"){ $score_popi += 8 ; }
		else{ $score_popi += 0 ; }		
		
		if($data_popi_motor=="A"){ $score_popi += 0 ; }
		elseif($data_popi_motor=="B"){ $score_popi += 9 ; }
		else{ $score_popi += 0 ; }
		
		if($score_popi <= 25){ $kategori_popi = "D"; }
		elseif($score_popi > 25 AND $score_popi <= 50){ $kategori_popi = "C"; }
		elseif($score_popi > 50 AND $score_popi <= 75){ $kategori_popi = "B"; }
		elseif($score_popi > 75 AND $score_popi <= 100){ $kategori_popi = "A"; }
		
		$popi['score'] = $score_popi;
		$popi['kategori'] = $kategori_popi;
		
		return $popi;
	}
	
	public function summary(){
		$id =  $this->uri->segment(3);
		//GET PEMBIAYAAN
		$pembiayaan = $this->clients_pembiayaan_model->get_pembiayaan_by_client($id)->result();
		//$biaya = $pembiayaan[0];
		$pembiayaan_aktif = $this->clients_pembiayaan_model->get_pembiayaan_aktif($id)->result();
		$pembiayaan_aktif = $pembiayaan_aktif[0];
		
		//echo $pembiayaan_aktif[0];
		//print_r($pembiayaan);
		
		//GET DETAILS ANGGOTA
		$client = $this->clients_model->get_client($id)->result();
		$client = $client[0];
		$account_number = $client->client_account;
		
		//TABUNGAN SUKARELA
		$tabsukarela = $this->tabsukarela_model->get_account($account_number);
		$tabsukarela = $tabsukarela[0];
		
		//TABUNGAN WAJIB
		$tabwajib = $this->tabwajib_model->get_account($account_number);
		$tabwajib = $tabwajib[0];
		
		$this->template	->set('menu_title', 'Data Anggota')
						->set('client', $client)
						->set('data_client', $client_id)
						->set('pembiayaan', $pembiayaan)
						->set('pembiayaan_aktif', $pembiayaan_aktif)
						->set('tabsukarela', $tabsukarela)	
						->set('tabwajib', $tabwajib)	
						->set('id', $id)						
						->set('menu_client', 'active')
						->build('client_summary');		
	}
	
	public function saving_reg(){
		//process the form
			$data = array(
					'client_group'	     		=> $this->input->post('client_group'),
					'client_officer'      		=> $this->input->post('client_officer'),
					'client_account'	    	=> $client_account,
					'client_no'	    			=> $client_no,
					'client_fullname'      		=> $this->input->post('client_fullname'),
					'client_simplename'	    	=> $this->input->post('client_simplename'),
					'client_martialstatus'		=> $this->input->post('client_martialstatus'),
					'client_birthplace'	    	=> $this->input->post('client_birthplace'),
					'client_birthdate'	    	=> $this->input->post('client_birthdate'),
					'client_rt'					=> $this->input->post('client_rt'),
					'client_rw'					=> $this->input->post('client_rw'),
					'client_kampung'			=> $this->input->post('client_kampung'),
					'client_desa'				=> $this->input->post('client_desa'),
					'client_kecamatan'			=> $this->input->post('client_kecamatan'),
					'client_ktp'				=> $this->input->post('client_ktp'),
					'client_religion'			=> $this->input->post('client_religion'),
					'client_education'			=> $this->input->post('client_education'),
					'client_job'				=> $this->input->post('client_job'),
					'client_comodity'			=> $this->input->post('client_comodity')
			);
			
		
				
		return $this->clients_saving_model->insert($data);
	}

	
	
}