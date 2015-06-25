<?php

class Bukukas extends Front_Controller{
	
	private $per_page 	= '15';
	private $title 		= 'Bukukas';
	private $module 	= 'bukukas';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('accounting_model');	
		$this->load->model('kaskecil_model');
		$this->load->model('jurnal_model');		
		$this->load->library('pagination');		
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			redirect('bukukas/kaskecil', 'refresh');
		}
		else
		{
			 //If no session, redirect to login page
			 redirect('login', 'refresh');
		}
	}
	
	public function kaskecil($page='0')
	{
		if($this->session->userdata('logged_in'))
		{
			//Cek User Branch
			$user_branch = $this->session->userdata('user_branch');
			
			//Get kaskecil
			$total_rows = $this->kaskecil_model->count_all($this->input->get('q'),$user_branch);
			
			
			//pagination
			$config['base_url']     = site_url($this->module.'/kaskecil');
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
			
			$kaskecil = $this->kaskecil_model->get_all($config['per_page'] ,$page, $this->input->get('q'),$user_branch);
			//Build
			$this->template	->set('menu_title', 'Kas Kecil')
							->set('menu_jurnal', 'active')
							->set('jurnal', $kaskecil)
							->set('no', $no)
							->set('config', $config)
							->build('kaskecil');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}

	public function kaskecil_add()
	{
		
		//Cek User Branch
		$user_branch = $this->session->userdata('user_branch');
		if($this->save_kaskecil()){
			$this->session->set_flashdata('message', 'success|Kas kecil telah ditambahkan');
			redirect($this->module.'/kaskecil');
		}		
		$account = $this->accounting_model->get_all_accounting_beban_child()->result();
		
		$this->template	->set('data', $data)
						->set('menu_title', 'Tambah Kas Kecil')
						->set('menu_jurnal', 'active')
						->set('account', $account)
						->set('user_branch', $user_branch)
						->build('kaskecil_form');	
	}
	
	public function kaskecil_edit()
	{
		if($this->save_kaskecil()){
			$this->session->set_flashdata('message', 'success|Kas kecil telah diedit');
			redirect($this->module.'/kaskecil');
		}
		$id =  $this->uri->segment(3);
		$data = $this->kaskecil_model->get_kaskecil_id($id)->result();
		$data = $data[0];
		$account = $this->accounting_model->get_all_accounting_beban_child()->result();
		$this->template	->set('data', $data)
						->set('menu_title', 'Edit Kas Kecil')
						->set('menu_jurnal', 'active')
						->set('account', $account)
						->build('kaskecil_form');	
	}
	
	public function kaskecil_delete($id = '0'){
		$id =  $this->uri->segment(3);
		$data = $this->kaskecil_model->get_kaskecil_id($id)->result();
		$data = $data[0];
		$remark = $data->kaskecil_code;
			if($this->kaskecil_model->delete($id)){
				$data_jurnal = array(	
						'deleted' 		=> "1",
					);	
				$this->jurnal_model->delete_jurnal($remark,$data_jurnal );
				$this->session->set_flashdata('message', 'success|Kas kecil telah dihapus');
				redirect('bukukas/kaskecil');
				exit;
			}
	}
	
	private function save_kaskecil()
	{		
		//set form validation
		$this->form_validation->set_rules('kaskecil_date', 'Tanggal', 'required');
		$this->form_validation->set_rules('kaskecil_cabang', 'Cabang', 'required');
		$this->form_validation->set_rules('kaskecil_remark', 'Keterangan', 'required');
		$this->form_validation->set_rules('kaskecil_hargasatuan', 'Harga Satuan', 'required');
		$this->form_validation->set_rules('kaskecil_qty', 'Qty', 'required');
	
	
		if($this->form_validation->run() === TRUE){
			$id = $this->input->post('kaskecil_id');
			$user_branch = $this->session->userdata('user_branch');
			$kaskecil_hargasatuan = $this->input->post("kaskecil_hargasatuan");
			$kaskecil_hargasatuan = str_replace(".","",$kaskecil_hargasatuan);
			$kaskecil_total = $kaskecil_hargasatuan * $this->input->post('kaskecil_qty');
			$timestamp= date("Ymdhis");	
			//process the form
				
					
			$user_id = $this->session->userdata('user_id');
			$timestamps = date(("Y-m-d H:i:s"));	
		
			if(!$id){
				//Add to Jurnal
					$jurnal_account_debet = $this->input->post('kaskecil_account');
					$jurnal_account_credit = "1010004";  //Kas Teller
					$nominal = $kaskecil_total;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $this->input->post('kaskecil_cabang'),
						'jurnal_date'    	 	=> $this->input->post('kaskecil_date'),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,	
						'jurnal_remark' 		=> "KK-$timestamp",
						'jurnal_nobukti_kode'	=> $this->input->post('kaskecil_nobukti_kode'),
						'jurnal_nobukti_nomor'	=> $this->input->post('kaskecil_nobukti_nomor'),	
						'created_by'	   		=> $user_id,
						'created_on'	   		=> $timestamps,
					);				
					$this->jurnal_model->insert($data_jurnal);
					
				//Add to KasKecil
					$data = array(
						'kaskecil_date'       	=> $this->input->post('kaskecil_date'),
						'kaskecil_cabang'       => $this->input->post('kaskecil_cabang'),
						'kaskecil_remark'       => $this->input->post('kaskecil_remark'),
						'kaskecil_account'      => $this->input->post('kaskecil_account'),
						'kaskecil_hargasatuan'  => $kaskecil_hargasatuan,
						'kaskecil_qty'       	=> $this->input->post('kaskecil_qty'),
						'kaskecil_total'       	=> $kaskecil_total,
						'kaskecil_code'       	=> "KK-$timestamp",	
						'kaskecil_nobukti_kode'	=> $this->input->post('kaskecil_nobukti_kode'),
						'kaskecil_nobukti_nomor'	=> $this->input->post('kaskecil_nobukti_nomor'),	
						'created_by'	   		=> $user_id,
						'created_on'	   		=> $timestamps,				
					);		
				return $this->kaskecil_model->insert($data);
			}else{
				$data = $this->kaskecil_model->get_kaskecil_id($id)->result();
				$data = $data[0];
				$remark = $data->kaskecil_code;
				//Edit to Jurnal
					$jurnal_account_debet = $this->input->post('kaskecil_account');
					$jurnal_account_credit = "1010004";  //Kas Teller
					$nominal = $kaskecil_total;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $this->input->post('kaskecil_cabang'),
						'jurnal_date'    	 	=> $this->input->post('kaskecil_date'),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_nobukti_kode'	=> $this->input->post('kaskecil_nobukti_kode'),
						'jurnal_nobukti_nomor'	=> $this->input->post('kaskecil_nobukti_nomor'),	
						'created_by'	   		=> $user_id,
						'created_on'	   		=> $timestamps,
					);				
					$this->jurnal_model->update_jurnal($remark,$data_jurnal);
					
				//Edit KasKecil
					$data = array(
						'kaskecil_date'       	=> $this->input->post('kaskecil_date'),
						'kaskecil_cabang'       => $this->input->post('kaskecil_cabang'),
						'kaskecil_remark'       => $this->input->post('kaskecil_remark'),
						'kaskecil_account'      => $this->input->post('kaskecil_account'),
						'kaskecil_hargasatuan'  => $kaskecil_hargasatuan,
						'kaskecil_qty'       	=> $this->input->post('kaskecil_qty'),
						'kaskecil_total'       	=> $kaskecil_total,	
						'kaskecil_nobukti_kode'	=> $this->input->post('kaskecil_nobukti_kode'),
						'kaskecil_nobukti_nomor'	=> $this->input->post('kaskecil_nobukti_nomor'),	
						'modified_by'	   		=> $user_id,
						'modified_on'	   		=> $timestamps,				
					);		
				return $this->kaskecil_model->update($id, $data);
			} 
		}
	}
	
}