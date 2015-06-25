<?php

class Topsheet extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Topsheet';
	private $module 	= 'topsheet';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('group_model');
		$this->load->model('clients_model');
		$this->load->model('officer_model');
		$this->load->model('tsdaily_model');
		$this->load->model('transaction_model');
		$this->load->model('saving_model');
		$this->load->model('clients_pembiayaan_model');
		$this->load->model('branch_model');
		$this->load->model('topsheet_model');
		$this->load->model('tabwajib_model');
		$this->load->model('tabsukarela_model');
		$this->load->model('tabberjangka_model');
		$this->load->model('tr_tabwajib_model');
		$this->load->model('tr_tabsukarela_model');
		$this->load->model('tr_tabberjangka_model');
		$this->load->model('jurnal_model');
		$this->load->model('risk_model');
		
		$this->load->library('pagination');	
	}
	
	public function index($page='0'){
		if($this->session->userdata('logged_in'))
		{
			redirect($this->module.'/list_topsheet', 'refresh');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function list_topsheet($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			if($user_branch != 0){
				$total_rows = $this->group_model->count_all_by_branch($this->input->get('q'),$user_branch);
			}else{
				$total_rows = $this->group_model->count_all($this->input->get('q'));
			}
			/*
			//pagination
			$config['base_url']     = site_url($this->module.'/list_topsheet');
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
			
			$this->pagination->initialize($config);*/
			$no =  $this->uri->segment(3);
			
			//$group = $this->group_model->get_group()->result();	
			if($user_branch != 0){
				$group = $this->group_model->get_all_group_by_branch($config['per_page'] ,$page, $this->input->get('q'),$user_branch);			
			}else{
				$group = $this->group_model->get_all_group( $config['per_page'] ,$page,$this->input->get('q'));	
			
			}
			
			//Get All Group (for filter button)
			$listgroup = $this->group_model->get_all_group_by_branch($total_rows ,0,$this->input->get('q'),$user_branch);
			
			$this->template	->set('menu_title', 'Top Sheet')
							->set('menu_transaksi', 'active')
							->set('group_total',$config['total_rows'])
							->set('group', $group)
							->set('listgroup', $listgroup)
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
	
	public function ts_filter(){	
		$group_id = $this->input->post('key');
		redirect($this->module.'/ts_entry/'.$group_id);
	}
	
	public function ts_entry(){
		if($this->session->userdata('logged_in'))
		{
		
			if($this->save_topsheet()){
				$this->session->set_flashdata('message', 'success|Topsheet telah ditambahkan');
				redirect($this->module.'/tsdaily'); 
			}
			
			$group_id =  $this->uri->segment(3);
			//Get group details
			$group = $this->group_model->get_group($group_id)->result();	
			$group = $group[0];	
			//Get total client per group
			$total_client = $this->clients_model->count_client_by_group($group_id);			
			//Get client detail
			$clients = $this->clients_model->get_pembiayaan_by_group($group_id);	
			
			//Get All Group (for filter button)
			$listgroup = $this->group_model->get_all_group();
			
			//Count TR per group
			$group_tr = $this->clients_pembiayaan_model->count_tr_by_group($group_id);	
			
			$this->template	->set('menu_title', 'Entri Top Sheet')
							->set('menu_transaksi', 'active')
							->set('group', $group)
							->set('clients', $clients)
							->set('total_client', $total_client)
							->set('listgroup', $listgroup)
							->set('group_tr', $group_tr)
							->set('no', $no)
							->build('topsheet');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function ts_entry_std(){
		if($this->session->userdata('logged_in'))
		{
		
			if($this->save_topsheet()){
				$this->session->set_flashdata('message', 'success|Topsheet telah ditambahkan');
				redirect($this->module.'/tsdaily'); 
			}
			
			$group_id =  $this->uri->segment(3);
			//Get group details
			$group = $this->group_model->get_group($group_id)->result();	
			$group = $group[0];	
			//Get total client per group
			$total_client = $this->clients_model->count_client_by_group($group_id);			
			//Get client detail
			$clients = $this->clients_model->get_pembiayaan_by_group($group_id);	
			
			//Get All Group (for filter button)
			$listgroup = $this->group_model->get_all_group();
			
			//Count TR per group
			$group_tr = $this->clients_pembiayaan_model->count_tr_by_group($group_id);	
			
			$this->template	->set('menu_title', 'Entri Top Sheet')
							->set('menu_transaksi', 'active')
							->set('group', $group)
							->set('clients', $clients)
							->set('total_client', $total_client)
							->set('listgroup', $listgroup)
							->set('group_tr', $group_tr)
							->set('no', $no)
							->build('topsheet_std');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function tsdaily($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			$total_rows = $this->tsdaily_model->count_all($this->input->get('q'), $user_branch);
			
			//pagination
			$config['base_url']     = site_url($this->module.'/tsdaily');
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
			
			if($user_branch == 0){ $user_branch = ""; }
			//$group = $this->group_model->get_group()->result();	
			$tsdaily = $this->tsdaily_model->get_all( $config['per_page'] , $page, $this->input->get('q'), $user_branch);			
				
			$this->template	->set('menu_title', 'Rekap Topsheet')
							->set('menu_transaksi', 'active')
							->set('group_total',$config['total_rows'])
							->set('tsdaily', $tsdaily)
							->set('no', $no)
							->set('config', $config)
							->build('tsdaily');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function tsdaily_group($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			$group_id =  $this->uri->segment(3);
			$total_rows = $this->tsdaily_model->count_history_by_group($user_branch, $group_id);

			
			if($user_branch == 0){ $user_branch = ""; }
			//$group = $this->group_model->get_group()->result();	
			$tsdaily = $this->tsdaily_model->get_history_by_group($user_branch, $group_id);			
				
			$this->template	->set('menu_title', 'History Topsheet')
							->set('menu_transaksi', 'active')
							->set('group_total',$config['total_rows'])
							->set('tsdaily', $tsdaily)
							->set('no', $no)
							->set('total_rows', $total_rows)
							->build('tsdaily');
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
			$this->template	->set('menu_title', 'Registrasi Majelis')
							->set('officer', $officer)
							->set('menu_group', 'active')
							->build('group_form');	
		
	}
	
	/*
	public function ts_edit(){
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
		$this->template	->set('data', $data)
						->set('menu_title', 'Edit Majelis')
						->set('officer', $officer)
						->set('menu_group', 'active')
						->build('group_form');	
	}
	*/
	
	public function ts_edit(){
		$id =  $this->uri->segment(3);
		
		//GET SPECIFIC PROJECT
		$topsheet = $this->topsheet_model->get_tsdaily($id);
		$topsheet = $topsheet[0];
		$group_id = $topsheet->tsdaily_groupid;		
		$ts_id = $topsheet->tsdaily_topsheet_code;
		$freq = $topsheet->tsdaily_freq;
		
		$group = $this->group_model->get_group($group_id)->result();	
		$group = $group[0];	
		//Get total client per group
		$total_client = $this->clients_model->count_client_by_group($group_id);		
		
		$data = $this->topsheet_model->get_topsheet($id);
		//$data = $data[0];
		$this->template	->set('data', $data)
						->set('group', $group)
						->set('freq', $freq)
						->set('topsheet', $topsheet)
						->set('total_client', $total_client)
						->set('menu_title', 'View Topsheet')
						->set('ts_id', $ts_id)
						->set('menu_transaksi', 'active')
						->build('topsheet_edit');	
	}
	
	public function ts_delete(){
		$id =  $this->uri->segment(3);				
		$data = $this->topsheet_model->get_transaction($id);
		
		foreach($data as $c){
			$tr_id 			= $c->tr_id;
			$tr_pembiayaan 	= $c->tr_pembiayaan;
			$tr_freq 		= $c->tr_freq;
			$tr_angs_pokok 	= $c->tr_angsuranpokok;
			$tr_angs_profit = $c->tr_profit;
			$tr_account		= $c->client_account;
			$tr_tabwajib_debet 		= $c->tr_tabwajib_debet;
			$tr_tabwajib_credit 	= $c->tr_tabwajib_credit;
			$tr_tabsukarela_debet 	= $c->tr_tabsukarela_debet;
			$tr_tabsukarela_credit 	= $c->tr_tabsukarela_credit;
		
			//UPDATE tbl_pembiayaan			
				//Get Pembiayaan Actual
				$detail_pembiayaan = $this->clients_pembiayaan_model->get_pembiayaan($tr_pembiayaan)->result();
				$detail_pembiayaan = $detail_pembiayaan[0];
				//Set Pembiayaan Value
				$angsuranke = $detail_pembiayaan->data_angsuranke - $tr_freq;
				if($angsuranke < 0) { $angsuranke = 0;}
				$pertemuanke = $detail_pembiayaan->data_pertemuanke - 1;
				if($pertemuanke < 0) { $pertemuanke = 0;}
				$sisaangsuran = $detail_pembiayaan->data_sisaangsuran + $tr_angs_pokok;
				//Update Table
				$data_pembiayaan = array(
					'data_angsuranke'	=>  $angsuranke,
					'data_pertemuanke'	=>  $pertemuanke,
					'data_sisaangsuran'	=>  $sisaangsuran,
				);
				$this->clients_pembiayaan_model->update_pembiayaan($tr_pembiayaan, $data_pembiayaan);			
			
			
			//UPDATE tbl_tabwajib	
				//Get Tabwajib Actual
				$detail_tabwajib = $this->tabwajib_model->get_tabwajib($tr_account);
				$detail_tabwajib = $detail_tabwajib[0];
				//Set Tabwajib Value
				$tabwajib_debet  = $detail_tabwajib->tabwajib_debet  - $tr_tabwajib_debet;
				$tabwajib_credit = $detail_tabwajib->tabwajib_credit - $tr_tabwajib_credit;
				$tabwajib_saldo  = $detail_tabwajib->tabwajib_saldo  - $tr_tabwajib_debet + $tr_tabwajib_credit;
				//Update Table
				$saving_date = date("Y-m-d H:i:s");
				$data_tabwajib = array(
						'tabwajib_date'      =>  $saving_date,
						'tabwajib_debet'     =>  $tabwajib_debet,
						'tabwajib_credit'    =>  $tabwajib_credit,
						'tabwajib_saldo'     =>  $tabwajib_saldo
				);				
				$this->tabwajib_model->update($tr_account, $data_tabwajib);
			
			//UPDATE tbl_tabsukarela
				//Get TabSukarela Actual
				$detail_tabsukarela = $this->tabsukarela_model->get_tabsukarela($tr_account);
				$detail_tabsukarela = $detail_tabsukarela[0];
				//Set TabSukarela Value
				$tabsukarela_debet  = $detail_tabsukarela->tabsukarela_debet  - $tr_tabsukarela_debet;
				$tabsukarela_credit = $detail_tabsukarela->tabsukarela_credit - $tr_tabsukarela_credit;
				$tabsukarela_saldo  = $detail_tabsukarela->tabsukarela_saldo  - $tr_tabsukarela_debet + $tr_tabsukarela_credit;
				//Update Table
				$saving_date = date("Y-m-d H:i:s");
				$data_tabsukarela = array(
						'tabsukarela_date'      =>  $saving_date,
						'tabsukarela_debet'     =>  $tabsukarela_debet,
						'tabsukarela_credit'    =>  $tabsukarela_credit,
						'tabsukarela_saldo'     =>  $tabsukarela_saldo
				);				
				$this->tabsukarela_model->update($tr_account, $data_tabsukarela);
				
			
			
			
		}
		//DELETE TRANSACTION
		$data_transaction = array( 'deleted'      =>  1	);		 
		$this->transaction_model->delete_transaction($id,$data_transaction);
		$this->transaction_model->delete_tr_tabwajib($id,$data_transaction);
		$this->transaction_model->delete_tr_tabsukarela($id,$data_transaction);
		$this->transaction_model->delete_tsdaily($id,$data_transaction);
		$this->transaction_model->delete_jurnal($id,$data_transaction);
		redirect($this->module.'/tsdaily', 'refresh');
	}
	
	public function ts_view(){
		$id =  $this->uri->segment(3);
		
		//GET SPECIFIC PROJECT
		$topsheet = $this->topsheet_model->get_tsdaily($id);
		$topsheet = $topsheet[0];
		$group_id = $topsheet->tsdaily_groupid;		
		$ts_id = $topsheet->tsdaily_topsheet_code;
		$freq = $topsheet->tsdaily_freq;
		
		$group = $this->group_model->get_group($group_id)->result();	
		$group = $group[0];	
		//Get total client per group
		$total_client = $this->clients_model->count_client_by_group($group_id);		
		
		$data = $this->topsheet_model->get_topsheet($id);
		//$data = $data[0];
		$this->template	->set('data', $data)
						->set('group', $group)
						->set('freq', $freq)
						->set('topsheet', $topsheet)
						->set('total_client', $total_client)
						->set('menu_title', 'View Topsheet')
						->set('ts_id', $ts_id)
						->set('menu_transaksi', 'active')
						->build('topsheet_view');	
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
	
	private function save_topsheet(){
		$user_branch = $this->session->userdata('user_branch');	
		
		//set form validation
		$this->form_validation->set_rules('ts_date', 'Tanggal', 'required');
		//$this->form_validation->set_rules('ts_freq', 'Pertemuan ke', 'required');	
	
		if($this->form_validation->run() === TRUE){
			$this->db->trans_start();
			
			$no = $this->input->post('no');
			$total_absen_h=0; 
			$total_absen_s=0;
			$total_absen_c=0;
			$total_absen_i=0;
			$total_absen_a=0;
			$timestamp= date("Ymdhis");
			$group_id = $this->input->post('group_id');
			
			$topsheet_code= $timestamp.$group_id;
			$ts_freq_total=0;
			for($i=1; $i<=$no; $i++){
			
				
				//process the form
				
				//ABSEN
				$absen=$this->input->post("data_absen_".$i);
				$columnabsen="tr_absen_".$absen;
				if($absen == "h") { $tr_absen_h = 1; $total_absen_h ++; }else{ $tr_absen_h = 0;}
				if($absen == "s") { $tr_absen_s = 1; $total_absen_s ++; }else{ $tr_absen_s = 0;}
				if($absen == "c") { $tr_absen_c = 1; $total_absen_c ++; }else{ $tr_absen_c = 0;}
				if($absen == "i") { $tr_absen_i = 1; $total_absen_i ++; }else{ $tr_absen_i = 0;}
				if($absen == "a") { $tr_absen_a = 1; $total_absen_a ++; }else{ $tr_absen_a = 0;}
				
				// TS FREQ
				$ts_freq = $this->input->post("ts_freq");
				if($ts_freq==""){$ts_freq=0;}
				
				// ANGSURAN FREQ
				$frek = $this->input->post("data_freq_".$i);
				$angsuranke = $this->input->post("data_angsuranke_".$i)-1; //dikurangi 1 karena di view sudah di +1
				if($angsuranke < 0) { $angsuranke = 0;}
				$pertemuanke = $this->input->post("data_pertemuanke_".$i);
				if($frek>=1){				
					$angsuranke = $angsuranke + $frek;
					$ts_freq_total += $frek;
				}
				
				//TANGGUNG RENTENG
				$data_tr = $this->input->post("data_tr_".$i);
				$client_tr = $this->input->post("client_tr_".$i);
				$data_tr_today = $this->input->post("data_tr_today_".$i);
				if($data_tr_today=="1"){
					$data_tr = $data_tr + 1;
					$client_tr = $client_tr + 1;
				}
				$data_id = $this->input->post("data_id_".$i);
				if(empty($data_id)){ $data_id = 0 ;}
				//$tr_tabwajib_debet = $this->input->post("data_freq_".$i) * 1000;
				$tr_tabwajib_debet = $this->input->post("data_tabwajib_debet_".$i)*1000;
				$tr_tabwajib_credit = $this->input->post("data_tabwajib_credit_".$i)*1000;
				$data_transaction = array(
						'tr_date'       		  => $this->input->post("ts_date"),
						'tr_account'       		  => $this->input->post("data_account_".$i),
						'tr_topsheet_code'        => $topsheet_code,
						'tr_client'       		  => $this->input->post("data_client_".$i),
						'tr_pembiayaan'    		  => $data_id ,
						'tr_group'       		  => $group_id,
						"$columnabsen"			  => '1',
						'tr_freq'       		  => $this->input->post("data_freq_".$i),
						'tr_angsuranke'       	  => $angsuranke,
						'tr_pertemuanke'       	  => $pertemuanke,
						'tr_angsuranpokok'        => $this->input->post("data_totalangsuranpokok_".$i)*1000,
						'tr_profit'        		  => $this->input->post("data_totalangsuranprofit_".$i)*1000,
						'tr_tabunganwajib'    	  => $tr_tabwajib_debet,
						'tr_tabsukarela_debet'    => ($this->input->post("data_tabsukarela_debet_".$i)*1000),
						'tr_tabsukarela_credit'   => ($this->input->post("data_tabsukarela_credit_".$i)*1000),
						'tr_tabberjangka_debet'   => ($this->input->post("data_tabberjangka_debet_".$i)*1000),
						'tr_tabberjangka_credit'  => ($this->input->post("data_tabberjangka_credit_".$i)*1000),
						'tr_tabwajib_debet'   	  => $tr_tabwajib_debet,
						'tr_tabwajib_credit'   	  => $tr_tabwajib_credit,
						'tr_absen_h'   	  => $tr_absen_h,
						'tr_absen_s'   	  => $tr_absen_s,
						'tr_absen_c'   	  => $tr_absen_c,
						'tr_absen_i'   	  => $tr_absen_i,
						'tr_absen_a'   	  => $tr_absen_a,
						'tr_tanggungrenteng'   	  => $data_tr_today,
						'tr_adm'          => ($this->input->post("data_adm_".$i)*1000),
						'tr_butab'        => ($this->input->post("data_butab_".$i)*1000),
						'tr_asuransi'     => ($this->input->post("data_asuransi_".$i)*1000),
						'tr_lwk'          => ($this->input->post("data_lwk_".$i)*1000),
				);
				
				$total_adm += ($this->input->post("data_adm_".$i)*1000);
				$total_butab += ($this->input->post("data_butab_".$i)*1000);
				$total_asuransi += ($this->input->post("data_asuransi_".$i)*1000);
				$total_lwk += ($this->input->post("data_lwk_".$i)*1000);
				$total_lainlain += $total_adm+$total_butab+$total_asuransi+$total_lwk;
				
				//$data_id = $this->input->post("data_id_".$i);
				$detail_pembiayaan = $this->clients_pembiayaan_model->get_pembiayaan($data_id)->result();
				$detail_pembiayaan = $detail_pembiayaan[0];
				$akad=$detail_pembiayaan->data_akad;
				
				//ANGSURAN
				//$data_sisaangsuran = $this->input->post("data_sisaangsuran_".$i) - ( $this->input->post("data_freq_".$i) * ($this->input->post("data_totalangsuran_".$i) * 1000) );
				$data_sisaangsuran = $this->input->post("data_sisaangsuran_".$i) - ( $this->input->post("data_totalangsuranpokok_".$i) * 1000 );				
				$data_angsuran_pokok = ( $this->input->post("data_totalangsuranpokok_".$i) * 1000 );
				$data_angsuran_profit = ( $this->input->post("data_totalangsuranprofit_".$i) * 1000 );
				
				//Total Angsuran
				$total_angsuran_pokok += ( $this->input->post("data_totalangsuranpokok_".$i) * 1000 );
				$total_angsuran_profit += ( $this->input->post("data_totalangsuranprofit_".$i) * 1000 );
				//Total Angsuran per Akad
				if($akad == "MYR"){ 
					$total_angsuran_pokok_Musyarakah += $data_angsuran_pokok ; 
					$total_angsuran_profit_Musyarakah += $data_angsuran_profit ;
				}elseif($akad == "AHA"){ 
					$total_angsuran_pokok_Ijarah +=  $data_angsuran_pokok ; 
					$total_angsuran_profit_Ijarah += $data_angsuran_profit ;
				}elseif($akad == "MBA"){ 
					$total_angsuran_pokok_Murabahah +=  $data_angsuran_pokok ; 
					$total_angsuran_profit_Murabahah += $data_angsuran_profit ;
				}elseif($akad == "IJR"){ 
					$total_angsuran_pokok_Ijarah +=  $data_angsuran_pokok ; 
					$total_angsuran_profit_Ijarah += $data_angsuran_profit ;
				}
				//$par =0;
				$par = $this->input->post("data_par_".$i);
				// PAR / RISK
				if($this->input->post("data_freq_".$i) == 0 AND $data_id != 0 AND $angsuranke >0 AND $angsuranke <=50){ 
					$par = $this->input->post("data_par_".$i) + 1;
					$tr_risk = array(					
							'risk_client'  		=>  $this->input->post("data_client_".$i),
							'risk_pembiayaan'   =>  $data_id,
							'risk_ke'   		=>  $angsuranke + 1,
							'risk_date'   		=>  $this->input->post("ts_date")
					);
					$this->risk_model->insert($tr_risk);
					
				}
				
				//PEMBAYARAN PAR
				if($this->input->post("data_freq_".$i) > 1 AND $par>0){
					$par = $this->input->post("data_par_".$i) - $this->input->post("data_freq_".$i) + 1;
				}
				if($par < 0){ $par = 0; }
				
				$data_pembiayaan = array(
						'data_angsuranke'       =>  $angsuranke,
						'data_sisaangsuran'     =>  $data_sisaangsuran,
						'data_tr'     			=>  $data_tr,
						'data_par'     			=>  $par
				);
				
				$saving_date= date("Ymdhis");
				$transactioncode= $saving_date.$this->input->post("data_client_".$i);
				
				if($data_id){
					$tabwajib_saldo = $this->input->post("data_tabwajib_saldo_".$i) + $tr_tabwajib_debet - $tr_tabwajib_credit;
					$tabwajib_debet = $tr_tabwajib_debet;
					$tabwajib_totaldebet = $this->input->post("data_tabwajib_totaldebet_".$i) + $tr_tabwajib_debet;
					$tabwajib_totalcredit = $this->input->post("data_tabwajib_totalcredit_".$i) + $tr_tabwajib_credit;
				}else{
					$tabwajib_saldo = $this->input->post("data_tabwajib_saldo_".$i);
					$tabwajib_debet = 0;
					$tabwajib_totaldebet = $this->input->post("data_tabwajib_totaldebet_".$i);
				}
				$tabwajib_totalcredit = $this->input->post("data_tabwajib_totalcredit_".$i) + ($this->input->post("data_tabwajib_credit_".$i)*1000);
				
				$tabsukarela_saldo = $this->input->post("data_tabsukarela_saldo_".$i) + ($this->input->post("data_tabsukarela_debet_".$i)*1000) - ($this->input->post("data_tabsukarela_credit_".$i)*1000);
				$tabsukarela_totaldebet = $this->input->post("data_tabsukarela_totaldebet_".$i) + ($this->input->post("data_tabsukarela_debet_".$i)*1000);
				$tabsukarela_totalcredit = $this->input->post("data_tabsukarela_totalcredit_".$i) + ($this->input->post("data_tabsukarela_credit_".$i)*1000);
				
				$tabberjangka_saldo = $this->input->post("data_tabberjangka_saldo_".$i) + ($this->input->post("data_tabberjangka_debet_".$i)*1000) - ($this->input->post("data_tabberjangka_credit_".$i)*1000);
				$tabberjangka_totaldebet = $this->input->post("data_tabberjangka_totaldebet_".$i) + ($this->input->post("data_tabberjangka_debet_".$i)*1000);
				$tabberjangka_totalcredit = $this->input->post("data_tabberjangka_totalcredit_".$i) + ($this->input->post("data_tabberjangka_credit_".$i)*1000);
				
				
				/*
				if($tabsukarela_saldo < 10000){
					$this->session->set_flashdata('message', "success|Tab. Sukarela ".$this->input->post('data_account_'.$i)." < 10000 ");
					redirect('topsheet/ts_entry/172');
					return false;
				}
				*/
				
				$total_tabwajib += $tabwajib_saldo;
				$total_tabsukarela = $total_tabsukarela + $tabsukarela_saldo;
								
				$tabsukarela_debet = $this->input->post("data_tabsukarela_debet_".$i)*1000;
				$tabsukarela_credit = $this->input->post("data_tabsukarela_credit_".$i)*1000;
				
				
				$tabwajib_credit = $this->input->post("data_tabwajib_credit_".$i)*1000;
				
				$total_tabwajib_debet += $tabwajib_debet;
				$total_tabwajib_credit += $tabwajib_credit;
				
				$total_tabsukarela_debet += $tabsukarela_debet;				
				$total_tabsukarela_credit += $tabsukarela_credit;
				
				//Tab Berjangka
				$tabberjangka_debet = $this->input->post("data_tabberjangka_debet_".$i)*1000;
				$tabberjangka_credit = $this->input->post("data_tabberjangka_credit_".$i)*1000;
				$total_tabberjangka_debet += $tabberjangka_debet;				
				$total_tabberjangka_credit += $tabberjangka_credit;
				
								
				$account_number = $this->input->post("data_account_".$i);
				$data_tabwajib = array(
						'tabwajib_date'      =>  $saving_date,
						'tabwajib_account'   =>  $this->input->post("data_account_".$i),
						'tabwajib_client'    =>  $this->input->post("data_client_".$i),
						'tabwajib_debet'     =>  $tabwajib_totaldebet,
						'tabwajib_credit'    =>  $tabwajib_totalcredit,
						'tabwajib_saldo'     =>  $tabwajib_saldo
				);
				$tr_tabwajib = array(					
						'tr_topsheet_code'  =>  $topsheet_code,
						'tr_date'   		=>  $saving_date,
						'tr_account'   		=>  $this->input->post("data_account_".$i),
						'tr_client'   		=>  $this->input->post("data_client_".$i),
						'tr_debet'    		=>  $tabwajib_debet,
						'tr_saldo'    		=>  $tabwajib_saldo,
						'tr_remark'    		=>  "TS ".$topsheet_code
				);
				$data_tabsukarela = array(
						'tabsukarela_date'      =>  $saving_date,
						'tabsukarela_account'   =>  $this->input->post("data_account_".$i),
						'tabsukarela_client'    =>  $this->input->post("data_client_".$i),
						'tabsukarela_debet'     =>  $tabsukarela_totaldebet,
						'tabsukarela_credit'    =>  $tabsukarela_totalcredit,
						'tabsukarela_saldo'     =>  $tabsukarela_saldo
				);
				$tr_tabsukarela = array(					
						'tr_topsheet_code'  =>  $topsheet_code,
						'tr_date'   		=>  $saving_date,
						'tr_account'   		=>  $this->input->post("data_account_".$i),
						'tr_client'   		=>  $this->input->post("data_client_".$i),
						'tr_debet'    		=>  ($this->input->post("data_tabsukarela_debet_".$i)*1000),
						'tr_credit'    		=>  ($this->input->post("data_tabsukarela_credit_".$i)*1000),
						'tr_saldo'    		=>  $tabsukarela_saldo,
						'tr_remark'    		=>  "TS ".$topsheet_code
				);
				
				$tab_berjangka_data = $this->tabberjangka_model->get_account($this->input->post("data_account_".$i));
				$tab_berjangka_data = $tab_berjangka_data[0];
				$tabberjangka_tr_angsuranke = $tab_berjangka_data->tabberjangka_angsuranke + 1;
				
				$data_tabberjangka = array(
						'tabberjangka_date'      =>  $saving_date,
						'tabberjangka_account'   =>  $this->input->post("data_account_".$i),
						'tabberjangka_client'    =>  $this->input->post("data_client_".$i),
						'tabberjangka_debet'     =>  $tabberjangka_totaldebet,
						'tabberjangka_credit'    =>  $tabberjangka_totalcredit,
						'tabberjangka_saldo'     =>  $tabberjangka_saldo,
						'tabberjangka_angsuranke'   =>  $tabberjangka_tr_angsuranke
				);
				$tr_tabberjangka = array(					
						'tr_topsheet_code'  =>  $topsheet_code,
						'tr_date'   		=>  $saving_date,
						'tr_account'   		=>  $this->input->post("data_account_".$i),
						'tr_client'   		=>  $this->input->post("data_client_".$i),
						'tr_debet'    		=>  ($this->input->post("data_tabberjangka_debet_".$i)*1000),
						'tr_credit'    		=>  ($this->input->post("data_tabberjangka_credit_".$i)*1000),
						'tr_saldo'    		=>  $tabberjangka_saldo,
						'tr_angsuranke'    	=>  $tabberjangka_tr_angsuranke,
						'tr_remark'    		=>  "TS ".$topsheet_code
				);	
				
				

				
				//INSERT TO DATABASE
				$this->transaction_model->insert($data_transaction);
				$this->clients_pembiayaan_model->update_pembiayaan($data_id, $data_pembiayaan);
				
				if($data_id){ 
					$this->tr_tabwajib_model->insert($tr_tabwajib); 
					$this->tabwajib_model->update($account_number, $data_tabwajib);
				}
				
				if( ($this->input->post("data_tabsukarela_debet_".$i)  != 0) OR ($this->input->post("data_tabsukarela_credit_".$i) != 0) ){
					$this->tr_tabsukarela_model->insert($tr_tabsukarela);				
					$this->tabsukarela_model->update($account_number, $data_tabsukarela);
				}
				
				if( ($this->input->post("data_tabberjangka_debet_".$i)  != 0) OR ($this->input->post("data_tabberjangka_credit_".$i) != 0) ){
					$this->tr_tabberjangka_model->insert($tr_tabberjangka);				
					$this->tabberjangka_model->update($account_number, $data_tabberjangka);
				}
				 
			}
			
			
			$group_id = $this->input->post("group_id");
			$group_name = $this->input->post("group_name");
			$grand_total = $total_angsuran_pokok+$total_angsuran_profit+$total_tabwajib_debet-$total_tabwajib_credit+$total_tabsukarela_debet-$total_tabsukarela_credit+$total_tabberjangka_debet-$total_tabberjangka_credit+$total_adm+$total_butab+$total_asuransi+$total_lwk;
			$grand_total_tabungan = $total_tabwajib_debet-$total_tabwajib_credit+$total_tabsukarela_debet-$total_tabsukarela_credit+$total_tabberjangka_debet-$total_tabberjangka_credit;
			$grand_total_rf = $total_angsuran_pokok+$total_angsuran_profit+$total_adm+$total_butab+$total_asuransi+$total_lwk;
			
			$data_tsdaily = array(
						'tsdaily_group'      	=>  "$group_name",						
						'tsdaily_topsheet_code' =>  $topsheet_code,
						'tsdaily_groupid'   	=>  $group_id,
						'tsdaily_date'    		=>  $this->input->post("ts_date"),
						'tsdaily_freq'    		=>  $ts_freq_total,
						'tsdaily_angsuranpokok' =>  $total_angsuran_pokok,
						'tsdaily_profit'    	=>  $total_angsuran_profit,
						'tsdaily_tabwajib'    	=>  $total_tabwajib_debet-$total_tabwajib_credit,
						'tsdaily_tabungan_debet'    	=> $total_tabsukarela_debet ,
						'tsdaily_tabungan_credit'    	=> $total_tabsukarela_credit,
						'tsdaily_tabungan_berjangka_debet'    	=> $total_tabberjangka_debet ,
						'tsdaily_tabungan_berjangka_credit'    	=> $total_tabberjangka_credit,
						'tsdaily_total'    		 =>  $grand_total,
						'tsdaily_total_tabungan' =>  $grand_total_tabungan,
						'tsdaily_total_rf'    	 =>  $grand_total_rf,
						'tsdaily_absen_h'    	 =>  $total_absen_h,
						'tsdaily_absen_s'    	 =>  $total_absen_s,
						'tsdaily_absen_c'    	 =>  $total_absen_c,
						'tsdaily_absen_i'    	 =>  $total_absen_i,
						'tsdaily_absen_a'    	 =>  $total_absen_a,
						'tsdaily_adm'    	 	 =>  $total_adm,
						'tsdaily_asuransi'    	 =>  $total_asuransi,
						'tsdaily_bukutabungan'   =>  $total_butab,
						'tsdaily_lwk'    		 =>  $total_lwk,
				);
				
			 $this->tsdaily_model->insert($data_tsdaily);
			 
			//------------------------------------------------------------ 
			//ADD JURNAL 
			//------------------------------------------------------------
			
				//Jurnal Tab.Wajib Debet
				if($total_tabwajib_debet != 0){
					$jurnal_account_credit = "2010300"; //Simpanan Wajib Kelompok
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_tabwajib_debet;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Wajib"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Tab.Wajib Credit
				if($total_tabwajib_credit != 0){
					$jurnal_account_credit = "1010001"; //Kas Teller
					$jurnal_account_debet = "2010300";  //Simpanan Wajib Kelompok
					$nominal = $total_tabwajib_credit;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,	
						'jurnal_tscode'  		=> $topsheet_code,
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Wajib"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Tab.Sukarela Debet
				if($total_tabsukarela_debet != 0){
					$jurnal_account_credit = "2010100"; //Simpanan Sukarela
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_tabsukarela_debet;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Sukarela"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Tab.Sukarela Kredit
				if($total_tabsukarela_credit != 0){
					$jurnal_account_credit = "1010001"; //Simpanan Sukarela
					$jurnal_account_debet = "2010100";  //Kas Teller
					$nominal = $total_tabsukarela_credit;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Sukarela"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				
				//Jurnal Tab.Berjangka Debet
				if($total_tabberjangka_debet != 0){
					$jurnal_account_credit = "2010400"; //Simpanan berjangka
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_tabberjangka_debet;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Berjangka"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Tab.Berjangka Kredit
				if($total_tabberjangka_credit != 0){
					$jurnal_account_credit = "1010001"; //Simpanan berjangka
					$jurnal_account_debet = "2010400";  //Kas Teller
					$nominal = $total_tabberjangka_credit;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Berjangka"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Adm
				if($total_adm > 0){
					$jurnal_account_credit = "4020003"; //JASA ADMINISTRASI PELAYANAN
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_adm;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Adm"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				//Jurnal Butab
				if($total_butab > 0){
					$jurnal_account_credit = "4020006"; //JASA ADMINISTRASI GANTI BUKU TABUNGAN
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_butab;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Butab"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Asuransi
				if($total_asuransi > 0){
					$jurnal_account_credit = "2050201"; //TITIPAN ASURANSI
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_asuransi;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Asuransi"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal LWK
				if($total_lwk > 0){
					$jurnal_account_credit = "4020008"; //JASA ADMINISTRASI LATIHAN WAJIB KELOMPOK
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_lwk;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - LWK"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Angsuran Pokok
					//Jurnal Angsuran Pokok Musyarakah
					if($total_angsuran_pokok_Musyarakah > 0){
						$jurnal_account_credit = "1030101"; //PIUTANG PEMBIAYAAN ANGGOTA MUSYARAKAH
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_pokok_Musyarakah;				
						$data_jurnal = array(
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,
							'jurnal_tscode'  		=> $topsheet_code,	
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Pokok Musyarakah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					//Jurnal Angsuran Pokok Murabahah
					if($total_angsuran_pokok_Murabahah > 0){
						$jurnal_account_credit = "1030102"; //PIUTANG PEMBIAYAAN ANGGOTA MURABAHAH
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_pokok_Murabahah;				
						$data_jurnal = array(
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,
							'jurnal_tscode'  		=> $topsheet_code,	
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Pokok Murabahah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					//Jurnal Angsuran Pokok Ijarah
					if($total_angsuran_pokok_Ijarah > 0){
						$jurnal_account_credit = "1030103"; //PIUTANG PEMBIAYAAN ANGGOTA Ijarah
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_pokok_Ijarah;				
						$data_jurnal = array(
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,
							'jurnal_tscode'  		=> $topsheet_code,	
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Pokok Ijarah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					
				//Jurnal Angsuran Profit
					//Jurnal Angsuran Pokok Musyarakah
					if($total_angsuran_profit_Musyarakah > 0){
						$jurnal_account_credit = "4010101"; //PIUTANG PEMBIAYAAN ANGGOTA MUSYARAKAH
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_profit_Musyarakah;				
						$data_jurnal = array(							
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,
							'jurnal_tscode'  		=> $topsheet_code,	
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Profit Musyarakah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					//Jurnal Angsuran Pokok Murabahah
					if($total_angsuran_profit_Murabahah > 0){
						$jurnal_account_credit = "4010102"; //PIUTANG PEMBIAYAAN ANGGOTA MURABAHAH
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_profit_Murabahah;				
						$data_jurnal = array(
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,
							'jurnal_tscode'  		=> $topsheet_code,	
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Profit Murabahah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					//Jurnal Angsuran Pokok Ijarah
					if($total_angsuran_profit_Ijarah > 0){
						$jurnal_account_credit = "4010103"; //PIUTANG PEMBIAYAAN ANGGOTA Ijarah
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_profit_Ijarah;				
						$data_jurnal = array(
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,	
							'jurnal_tscode'  		=> $topsheet_code,
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Profit Ijarah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
				
				$this->db->trans_complete();
				return true;
		}
	}
	
	private function update_topsheet(){		
		$data = array(
				);
				
			 
		return $this->tsdaily_model->update($id, $data_tsdaily);
	
	}
	
	public function download(){			
			$user_branch = $this->session->userdata('user_branch');
			$branch = $this->branch_model->get_branch($user_branch)->result();	
			$branch_name=$branch[0]->branch_name;			
			
			$group_id =  $this->uri->segment(3);
			//Get group details
			$group = $this->group_model->get_group($group_id)->result();	
			$group = $group[0];	
			//Get total client per group
			$total_client = $this->clients_model->count_client_by_group($group_id);	
			
			//Get client detail
			$clients = $this->clients_model->get_pembiayaan_by_group($group_id);
			
			//Count TR per group
			$group_tr = $this->clients_pembiayaan_model->count_tr_by_group($group_id);		
			
			$timestamp=date("Ymdhis");
			$filename="Topsheet_$timestamp";			
			
			$html = "";
			$html .= '<style>
						@page{ margin-top: 1cm; margin-bottom: 1cm; margin-left: 1cm; margin-right: 1cm;}
						body{ font-size: 9pt;} 
						.tbl{border-collapse: collapse;border: none;font-size: 9pt;}
						.tbl thead{border-bottom: 2px solid #000;}
						.tbl td, .tbl th{padding: 0 3px;border: 1px solid #333;}
						.clear{float: none;clear: both}
						#topsheet{width: 100%;float: none;clear: both;padding-bottom: 20px;}
						.topsheet_head2{width: 20%;float: left;font-size: 9pt;}
						.topsheet_head{width: 25%;float: left;font-size: 9pt;}
						.topsheet_head td,.topsheet_head2 td{ border: none;}
						.tbl tr td.bdr_btm, .tbl tr th.bdr_btm{ border: none; border-left: none; border-right: none;border-bottom: 1px solid #000;}
						.tbl tr td.bdr_leftbtm, .tbl tr th.bdr_leftbtm{ border: none; border-left: 1px solid #000; border-right: none;border-bottom: 1px solid #000;}
						.tbl tr td.bdr_btm_bold, .tbl tr th.bdr_btm_bold{ border: none; border-left: none; border-right: none;border-bottom: 2px solid #000;}
						.tbl tr td.bdr_leftbtm_bold, .tbl tr th.bdr_leftbtm_bold{ border: none; border-left: 1px solid #000;; border-right: none;border-bottom: 2px solid #000;}
						.tbl tr td.nobdr, .tbl tr th.nobdr{border: none;}
						.tbl tr td.border_bold{border: 2px solid #000;}
					</style>';
			$html .= "<div style='float:left;position:absolute;left:35px;top:10px;'><img src='/files/logo_amartha.png' /></div>"; 
			$html .= "<div style='float:right;position:absolute;right:35px;top:20px;'><small>TS ".$timestamp."</small></div>"; 
			$html .= '<h2 align="center">TOPSHEET</h2>';			
			//$html .= '<div id="topsheet"><div class="topsheet_head2"><table border="0"><tr><td>Area</td><td>: Bogor Barat</td></tr><tr><td>Cabang</td><td>: 101 Ciseeng</td></tr><tr><td>Majelis</td><td>: <b>Melati</b></td></tr></table></div><div class="topsheet_head"><table border="0"><tr><td>Kampung</td><td>: Blok Sukun</td></tr><tr><td>Desa</td><td>: Cibeuntang</td></tr><tr><td>Jumlah Anggota</td><td>: 21</td></tr></table></div><div class="topsheet_head"><table border="0"><tr><td>Pertemuan Ke</td><td>: 32</td></tr><tr><td>Tanggal</td><td>: 01/10/2014</td></tr><tr><td>Ketua</td><td>: Elsah</td></tr></table></div><div class="topsheet_head"><table border="0"><tr><td>Tanggung Renteng</td><td>: Ada / Tidak</td></tr><tr><td>Akumulasi TR</td><td>: 2</td></tr><tr><td>Pendamping</td><td>: Linda</td></tr></table></div><div class="clear"></div></div><table class="tbl" width="100%" cellspacing="0"><thead><tr><th rowspan="2">No</th><th rowspan="2">Rekening</th><th rowspan="2">Nama</th><th colspan="5"><b>Kehadiran</b></th><th colspan="5"><b>Pembiayaan</b></th><th colspan="2"><b>Keterlambatan</b></th><th colspan="3"><b>Tabungan Sukarela</b></th><th colspan="5"><b>Tabungan Berjangka</b></th><th rowspan="2">Ket</th></tr><tr><td align="center">S</td><td align="center">C</td><td align="center">I</td><td align="center">A</td><td align="center">V</td><td align="center">Sisa<br/>Pokok</td><td align="center">Sisa<br/>Profit</td><td align="center">F</td><td align="center">P</td><td align="center">Total<br/>Angsur</td><td align="center">F</td><td align="center">Total<br/>Angsur</td><td align="center">Saldo</td><td align="center">Setor</td><td align="center">Tarik</td><td align="center">V</td><td align="center">P</td><td align="center">Saldo</td><td align="center">Setor</td><td align="center">Tarik</td></tr></thead>';
			$html .= '<div id="topsheet">';
			$html .= '<div class="topsheet_head2">';
			$html .= '<table border="0">';
			$html .= '<tr><td>Area</td><td>: '.$group->area_name.'</td></tr>';
			$html .= '<tr><td>Cabang</td><td>: '.$group->area_code.$group->branch_code.' '.$group->branch_name.'</td></tr>';
			$html .= '<tr><td>Majelis</td><td>: <b>'.$group->group_name.'</b></td></tr>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<div class="topsheet_head">';
			$html .= '<table border="0">';
			$html .= '<tr><td>Kampung</td><td>: '.$group->group_kampung.'</td></tr>';
			$html .= '<tr><td>Desa</td><td>: '.$group->group_desa.'</td></tr>';
			$html .= '<tr><td>Jumlah Anggota</td><td>: '.$total_client.'</td></tr>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<div class="topsheet_head">';
			$html .= '<table border="0">';
			$html .= '<tr><td>Pertemuan Ke</td><td>: __</td></tr>';
			$html .= '<tr><td>Tanggal</td><td>: __/__/_____</td></tr>';
			$html .= '<tr><td>Ketua</td><td>: '.$group->group_leader.'</td></tr>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<div class="topsheet_head">';
			$html .= '<table border="0">';
			$html .= '<tr><td>Tanggung Renteng</td><td>: Ada / Tidak</td></tr>';
			$html .= '<tr><td>Akumulasi TR</td><td>: '.$group_tr.'</td></tr>';
			$html .= '<tr><td>Pendamping</td><td>: '.$group->officer_name.'</td></tr>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<div class="clear"></div>';
			$html .= '</div>';
			$html .= "<div><i>* Semua angka dalam ribuan ('000)</i></div>";
			$html .= '<div class="clear"></div>';
			$html .= '<table class="tbl" width="100%" cellspacing="0">';
			//$html .= '<thead>';
			$html .= '<tr>';
			$html .= '	<th rowspan="2" align="left" class="bdr_btm_bold">No</th>';
			$html .= '	<th rowspan="2" align="left" class="bdr_btm_bold">Rekening</th>';
			$html .= '	<th rowspan="2" align="left" class="bdr_btm_bold">Nama</th>';
			$html .= '	<th colspan="5" class="bdr_btm_bold"><b>Absensi</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th colspan="9" class="bdr_btm_bold"><b>Pembiayaan</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th colspan="2" class="bdr_btm_bold"><b>Keterlambatan</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th colspan="3" class="bdr_btm_bold"><b>Tab Wajib</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th colspan="3" class="bdr_btm_bold"><b>Tab Sukarela</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th colspan="4" class="bdr_btm_bold"><b>Tab Berjangka</b></th>';
			$html .= '	<th rowspan="2" class="nobdr" width="2px">&nbsp;</th>';
			$html .= '	<th rowspan="2" class="bdr_btm_bold">Ket</th>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '	<td align="center" class="bdr_btm_bold">S</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">C</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">I</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">A</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">H</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">TR</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">AK</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">PAR</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">F</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">M</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">P</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Sisa<br/>Pokok</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Sisa<br/>Profit</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Total<br/>Angsur</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Hari</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Total<br/>Angsuran</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Saldo</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Setor</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Tarik</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Saldo</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Setor</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Tarik</td>';
			
			$html .= '	<td align="center" class="bdr_btm_bold">P</td>';
			$html .= '	<td align="center" class="bdr_btm_bold">Saldo</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Setor</td>';
			$html .= '	<td align="center" class="bdr_leftbtm_bold">Tarik</td>';
			
			$html .= '</tr>';
			//$html .= '</thead>';			
			$html .= '<tbody>';
			$no=1;
			$today=date("Y-m-d");
			foreach($clients as $c):
			if($c->data_status != 4){
				$margin=0;
				$angsuranke=0;
				$angsuranke_sekarang = 0;
				$angsuran_pokok=0;
				$angsuran_profit=0;
				$sisa_pokok=0;
				$sisa_profit=0;
				if($c->data_status == 1){
					$id_pembiayaan = $c->data_id;
					$margin = $c->data_margin;
					$angsuranke= $c->data_angsuranke;
					$angsuranke_sekarang= $c->data_angsuranke;
					//$pertemuanke_sekarang = $c->data_pertemuanke + 1;
					$date_tagihan_pertama = $c->data_date_first;
					$diff = strtotime($today, 0) - strtotime($date_tagihan_pertama, 0);
					$pertemuanke_sekarang= floor($diff / 604800)  + 2;
					
					$angsuran_pokok=  $c->data_angsuranpokok;
					$angsuran_profit= $c->data_margin / 50 ;
					$totalangsuran = $c->data_totalangsuran;
					$sisa_pokok  = ((50-$angsuranke) * $angsuran_pokok)/1000;
					$sisa_profit = ((50-$angsuranke) * $angsuran_profit)/1000;
					$total_tabwajib += $c->data_tabunganwajib;
					$grand_totalangsuran += $totalangsuran;					
					$data_par = $c->data_par;
				}
				$absen_s=0;
				$absen_c=0;
				$absen_i=0;
				$absen_a=0;
				if($id_pembiayaan!="" OR $id_pembiayaan!=0){
					$absen_s = $this->clients_model->count_absen_s($id_pembiayaan);
					$absen_c = $this->clients_model->count_absen_c($id_pembiayaan);
					$absen_i = $this->clients_model->count_absen_i($id_pembiayaan);
					$absen_a = $this->clients_model->count_absen_a($id_pembiayaan);
				}else{ 
					$absen_s=0;
					$absen_c=0;
					$absen_i=0;
					$absen_a=0;
				}
				$data_tr = $c->data_tr; 
				if($data_tr == 0){$data_tr = "-";}
				
				$html .= '<tr>';
				$html .= '<td align="center" class="bdr_btm">'.$no.'</td>';
				$html .= '<td class="bdr_btm">'.$c->client_account.'</td>';
				$html .= '<td class="bdr_btm">'.$c->client_fullname.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$absen_s.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$absen_c.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$absen_i.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$absen_a.'</td>';
				$html .= '<td class="bdr_btm">&nbsp;</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="bdr_btm">&nbsp;</td>';
				$html .= '<td align="center" class="bdr_btm">'.$data_tr.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$data_par.'</td>';
				$html .= '<td class="bdr_btm">&nbsp;</td>';
				$html .= '<td align="center" class="bdr_btm">'.$pertemuanke_sekarang.'</td>';
				$html .= '<td align="center" class="bdr_btm">'.$angsuranke_sekarang.'</td>';
				if($c->data_status == 1){ $data_sisa_pokok=number_format($sisa_pokok,1); }else{ $data_sisa_pokok = "-";} ;
				if($c->data_status == 1){ $data_sisa_profit=number_format($sisa_profit,1); }else{ $data_sisa_profit = "-";} ;
				$html .= '<td align="right" class="bdr_btm">'.$data_sisa_pokok.'</td>';
				$html .= '<td align="right" class="bdr_btm">'.$data_sisa_profit.'</td>';
				if($c->data_status == 1){ $data_totalangsuran=number_format(($c->data_totalangsuran/1000),1); }else{ $data_totalangsuran = "-";} 
				$html .= '<td align="right" class="bdr_btm">'.$data_totalangsuran.'</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="bdr_btm">-</td>';
				$html .= '<td class="bdr_btm">-</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				if($c->tabwajib_saldo){ $data_tabwajib=number_format(($c->tabwajib_saldo/1000),1);}else{ $data_tabwajib="0"; }
				$html .= '<td class="bdr_btm" align="right">'.$data_tabwajib.'</td>';
				$html .= '<td class="bdr_leftbtm">&nbsp;</td>';
				$html .= '<td class="bdr_leftbtm">&nbsp;</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				if($c->tabsukarela_saldo){ $data_tabsukarela=number_format(($c->tabsukarela_saldo/1000),1);}else{ $data_tabsukarela="0"; }
				$html .= '<td align="right" class="bdr_btm">'.$data_tabsukarela.'</td>';
				$html .= '<td class="bdr_leftbtm">&nbsp;</td>';
				$html .= '<td class="bdr_leftbtm">&nbsp;</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td align="center" class="bdr_btm"> </td>';
				
				if($c->tabberjangka_saldo){ $data_tabberjangka=number_format(($c->tabberjangka_saldo/1000),1);}else{ $data_tabberjangka="0"; }
				$html .= '<td align="right" class="bdr_btm">'.$data_tabberjangka.'</td>';
				$html .= '<td align="right" class="bdr_leftbtm"></td>';
				$html .= '<td class="bdr_leftbtm">&nbsp;</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="bdr_btm">&nbsp;</td>';
				
				$html .= '</tr>';
			$no++;
			}//endif
			endforeach;
			$html .= '<tr>';
				$html .= '<td align="center" class="nobdr"> </td>';
				$html .= '<td colspan="7"  valign="top" class="nobdr"></td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td colspan="8" class="bdr_btm_bold">Sub Total</td>';
				$html .= '<td align="right" class="bdr_btm_bold"><b>'.number_format(($grand_totalangsuran/1000),1).'</b></td>';
				$html .= '<td colspan="3" class="bdr_btm_bold"></td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td colspan="7" class="bdr_btm_bold"></td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td colspan="2" class="nobdr">&nbsp;</td>';
				$html .= '<td colspan="2" class="bdr_btm_bold">&nbsp;</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="bdr_btm_bold">&nbsp;</td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td colspan="9" rowspan="6" class="nobdr"></td>';
				$html .= '<td colspan="4" rowspan="6" class="nobdr" align="center">RF</td>';
				$html .= '<td colspan="5" class="bdr_btm_bold">Setoran</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td colspan="2" rowspan="6" class="nobdr" align="center">TAB</td>';
				$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="6" class="bdr_btm_bold">Tab Wajib</td>';
				$html .= '<td align="right" class="bdr_btm_bold"><b>'.number_format(($total_tabwajib/1000),1).'</b></td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td colspan="5" class="bdr_btm_bold">Adm</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="7" class="bdr_btm_bold">Tab Sukarela</td>';
			$html .= '</tr>';
			
			
			$html .= '<tr>';
				$html .= '<td colspan="5" class="bdr_btm_bold">Butab</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="7" class="bdr_btm_bold">UMB Tab Sukarela</td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td colspan="5" class="bdr_btm_bold">LWK</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="7" class="bdr_btm_bold" >Tab Berjangka</td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td colspan="5" class="bdr_btm_bold">Gagal Dropping</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="7" class="bdr_btm_bold"></td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td colspan="5" class="bdr_btm_bold">Total</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="nobdr"></td>';
				$html .= '<td colspan="6" class="bdr_btm_bold">Total</td>';
				$html .= '<td class="nobdr" colspan="1" width="2px">&nbsp;</td>';
				$html .= '<td colspan="2" class="nobdr"></td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td class="nobdr" colspan="2" align="center">Ketua Majelis</td>';
				$html .= '<td class="nobdr" width="2px">&nbsp;</td>';
				$html .= '<td colspan="3" class="nobdr" align="center">Pendamping</td>';
				$html .= '<td class="nobdr" width="2px" colspan="2">&nbsp;</td>';
				$html .= '<td colspan="20" class="border_bold"><b>TOTAL</b></td>'; 
				$html .= '<td class="nobdr" colspan="2" width="2px">&nbsp;</td>';
				$html .= '<td colspan="2" class="nobdr" align="center">Validator</td>';
			$html .= '</tr>';
			
			
			
			$html .= '</tbody></table>';
			$html;
			//echo $html;
			$this->load->library('mpdf');
			$mpdf=new mPDF('utf-8', 'A4-L');
			//$mpdf->SetFooter("Top Sheet".'||{PAGENO}|'); 
			$mpdf->WriteHTML($html);
			//$this->mpdf->Output();
			$pdfFilePath = FCPATH."downloads/topsheet/$filename.pdf";
			$pdffile = base_url()."downloads/topsheet/$filename.pdf";
			$mpdf->Output($pdfFilePath,'F');
			redirect($pdffile, 'refresh');
			//echo $html;
	}
	
	public function ts_entry2(){
		if($this->session->userdata('logged_in'))
		{
		
			if($this->save_topsheet()){
				$this->session->set_flashdata('message', 'success|Topsheet telah ditambahkan');
				redirect($this->module.'/tsdaily');
			}
			
			$group_id =  $this->uri->segment(3);
			//Get group details
			$group = $this->group_model->get_group($group_id)->result();	
			$group = $group[0];	
			//Get total client per group
			$total_client = $this->clients_model->count_client_by_group($group_id);			
			//Get client detail
			$clients = $this->clients_model->get_pembiayaan_by_group($group_id);	
			
			//Get All Group (for filter button)
			$listgroup = $this->group_model->get_all_group();
			
			$this->template	->set('menu_title', 'Entri Top Sheet')
							->set('menu_transaksi', 'active')
							->set('group', $group)
							->set('clients', $clients)
							->set('total_client', $total_client)
							->set('listgroup', $listgroup)
							->set('no', $no)
							->build('topsheet2');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	
	public function tsdaily_report($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			$total_rows = $this->tsdaily_model->count_all_daily_report($this->input->get('q'), $user_branch);
			
			//pagination
			$config['base_url']     = site_url($this->module.'/tsdaily_report');
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
			
			if($user_branch == 0){ $user_branch = ""; }
			//$group = $this->group_model->get_group()->result();	
			$tsdaily = $this->tsdaily_model->get_all_daily_report( $config['per_page'] , $page, $this->input->get('q'), $user_branch);			
				
			$this->template	->set('menu_title', 'Rekap Harian')
							->set('menu_transaksi', 'active')
							->set('group_total',$config['total_rows'])
							->set('tsdaily', $tsdaily)
							->set('no', $no)
							->set('config', $config)
							->build('tsdaily_report');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function tsdaily_report_view($page='0'){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			$date =  $this->uri->segment(3);
			$total_rows = $this->tsdaily_model->count_all_daily_report_bydate($user_branch,$date);

			
			if($user_branch == 0){ $user_branch = ""; }
			//$group = $this->group_model->get_group()->result();	
			$tsdaily = $this->tsdaily_model->get_all_daily_report_bydate($user_branch, $date);			
				
			$this->template	->set('menu_title', 'Rekap Topsheet')
							->set('menu_transaksi', 'active')
							->set('group_total',$config['total_rows'])
							->set('tsdaily', $tsdaily)
							->set('no', $no)
							->set('config', $config)
							->build('tsdaily_report_view');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	

	public function tsdaily_apel(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			$total_rows = $this->tsdaily_model->count_all($this->input->get('q'), $user_branch);
			
			//pagination
			$config['base_url']     = site_url($this->module.'/tsdaily');
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
			if($user_branch == 0){ $user_branch = ""; }
			
			//FILTER DATE
			$date_start = $this->input->post('date_start');
			$date_end = $this->input->post('date_end');
			if($date_start AND $date_end){
				$date_start = $this->input->post('date_start');
				$date_end = $this->input->post('date_end');
			}else{
				$date = date("Y-m-d");	
				$date_day = date('l',strtotime($date));	
				if($date_day == "Monday"){
					function week_range($date) {
						$ts = strtotime($date);
						$start = strtotime("-7 day", $ts);
						//$start = date('Y-m-d', $start);	echo $start;
						return array(date('Y-m-d', $start),
									 date('Y-m-d', strtotime('next saturday', $start)));
					}
					list($date_start, $date_end) = week_range($date);
				}else{
					function week_range($date) {
						$ts = strtotime($date);
						$start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
						return array(date('Y-m-d', $start),
									 date('Y-m-d', strtotime('next saturday', $start)));
					}
					list($date_start, $date_end) = week_range($date);
				}
			}
			
			
			$tsdaily = $this->tsdaily_model->get_all_by_week( $user_branch, $date_start, $date_end);			
				
			$this->template	->set('menu_title', 'Rekap Topsheet')
							->set('menu_transaksi', 'active')
							->set('group_total',$config['total_rows'])
							->set('tsdaily', $tsdaily)
							->set('no', $no)
							//->set('config', $config)
							->build('tsdaily_apel');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function schedule(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			
			//Get All Group (for filter button)
			$listgroup = $this->group_model->get_all_group_by_branch($total_rows ,0,$this->input->get('q'),$user_branch);
			
			//Get All Group (by day)
			$group_senin = $this->group_model->get_schedule($user_branch, "Senin");
			$group_selasa = $this->group_model->get_schedule($user_branch, "Selasa");
			$group_rabu = $this->group_model->get_schedule($user_branch, "Rabu");
			$group_kamis = $this->group_model->get_schedule($user_branch, "Kamis");
			$group_jumat = $this->group_model->get_schedule($user_branch, "Jumat");
			
			$this->template	->set('menu_title', 'Jadwal Pelayanan')
							->set('menu_transaksi', 'active')
							->set('group_senin', $group_senin)
							->set('group_selasa', $group_selasa)
							->set('group_rabu', $group_rabu)
							->set('group_kamis', $group_kamis)
							->set('group_jumat', $group_jumat)
							->set('listgroup', $listgroup)
							->set('no', $no)
							//->set('config', $config)
							->build('schedule');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function namahari($date){
				$namahari=date('l',strtotime($date));
				if ($namahari == "Sunday") $namahari = "Minggu";
				else if ($namahari == "Monday") $namahari = "Senin";
				else if ($namahari == "Tuesday") $namahari = "Selasa";
				else if ($namahari == "Wednesday") $namahari = "Rabu";
				else if ($namahari == "Thursday") $namahari = "Kamis";
				else if ($namahari == "Friday") $namahari = "Jumat";
				else if ($namahari == "Saturday") $namahari = "Sabtu";
				 
				return $namahari;
			}
			
	public function tsdaily_apel_download(){
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');	
			$total_rows = $this->tsdaily_model->count_all($this->input->get('q'), $user_branch);
			$branch_name = str_replace(' ', '', $this->session->userdata('user_branch_name'));
			
			$no =  $this->uri->segment(3);			
			if($user_branch == 0){ $user_branch = ""; }
			
			

			//FILTER DATE
			$date_start=$this->uri->segment(3); 
			$date_end=$this->uri->segment(4);
			if($date_start AND $date_end){
			$date_start=$this->uri->segment(3); 
			$date_end=$this->uri->segment(4);
			}else{
				$date = date("Y-m-d");	
				$date_day = date('l',strtotime($date));	
				if($date_day == "Monday"){
					function week_range($date) {
						$ts = strtotime($date);
						$start = strtotime("-7 day", $ts);
						//$start = date('Y-m-d', $start);	echo $start;
						return array(date('Y-m-d', $start),
									 date('Y-m-d', strtotime('next saturday', $start)));
					}
					list($date_start, $date_end) = week_range($date);
				}else{
					function week_range($date) {
						$ts = strtotime($date);
						$start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
						return array(date('Y-m-d', $start),
									 date('Y-m-d', strtotime('next saturday', $start)));
					}
					list($date_start, $date_end) = week_range($date);
				}
			}
			
			
			$tsdaily = $this->tsdaily_model->get_all_by_week( $user_branch, $date_start, $date_end);			
				
			//load our new PHPExcel library
			$this->load->library('excel');
		 
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Amartha MIS");
			$objPHPExcel->getProperties()->setLastModifiedBy("Amartha MIS");
			$objPHPExcel->getProperties()->setTitle("Rekap Topsheet");
			$objPHPExcel->getProperties()->setSubject("Rekap Topsheet");
			$objPHPExcel->getProperties()->setDescription("Rekap Topsheet");
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Rekap Topsheet');
			
			//TITLE
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Amartha Microfinance");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Cabang $branch_name");
			$objPHPExcel->getActiveSheet()->mergeCells("A1:O1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:O2");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true, 'size'  => 16)));
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
			//TOP ROW
			$objPHPExcel->getActiveSheet()->getStyle("A4:O5")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("C4:J5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$objPHPExcel->getActiveSheet()->setCellValue("A4", "NO");
				$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(4);
				$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
			$objPHPExcel->getActiveSheet()->setCellValue("B4", "MAJELIS");
				$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
				$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
			$objPHPExcel->getActiveSheet()->setCellValue("C4", "ANGSURAN POKOK");
				$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(15);
				$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
			$objPHPExcel->getActiveSheet()->setCellValue("D4", "ANGSURAN PROFIT");
				$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
				$objPHPExcel->getActiveSheet()->mergeCells("D4:D5");
			$objPHPExcel->getActiveSheet()->setCellValue("E4", "TABUNGAN WAJIB");
				$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(15);
				$objPHPExcel->getActiveSheet()->mergeCells("E4:E5");
			$objPHPExcel->getActiveSheet()->setCellValue("F4", "TABUNGAN SUKARELA");
				$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(15);
				$objPHPExcel->getActiveSheet()->mergeCells("F4:G4");
				$objPHPExcel->getActiveSheet()->setCellValue("F5", "KREDIT");
				$objPHPExcel->getActiveSheet()->setCellValue("G5", "DEBET");
			$objPHPExcel->getActiveSheet()->setCellValue("H4", "TOTAL RF");
				$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(15);
				$objPHPExcel->getActiveSheet()->mergeCells("H4:H5");
			$objPHPExcel->getActiveSheet()->setCellValue("I4", "TOTAL TABUNGAN");
				$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(15);
				$objPHPExcel->getActiveSheet()->mergeCells("I4:I5");
			$objPHPExcel->getActiveSheet()->setCellValue("J4", "GRAND TOTAL");
				$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(15);
				$objPHPExcel->getActiveSheet()->mergeCells("J4:J5");
			$objPHPExcel->getActiveSheet()->setCellValue("K4", "ABSENSI");
				$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(4);
				$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(4);
				$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(4);
				$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(4);
				$objPHPExcel->getActiveSheet()->getColumnDimension("O")->setWidth(4);
				$objPHPExcel->getActiveSheet()->mergeCells("K4:O4");
				$objPHPExcel->getActiveSheet()->setCellValue("K5", "H");
				$objPHPExcel->getActiveSheet()->setCellValue("L5", "S");
				$objPHPExcel->getActiveSheet()->setCellValue("M5", "C");
				$objPHPExcel->getActiveSheet()->setCellValue("N5", "I");
				$objPHPExcel->getActiveSheet()->setCellValue("O5", "A");
			
			$objPHPExcel->getActiveSheet()->getStyle('A4:O5')->getAlignment()->setWrapText(true); 
			
			$objPHPExcel->getActiveSheet()->getStyle("K4:O5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$no=1;
			$cell_no=6;
			foreach($tsdaily as $c):
			
				$tgl_start = $c->tsdaily_date;
								
				if($tgl_start != $tgl_end AND $no==1){
					$objPHPExcel->getActiveSheet()->mergeCells("B$cell_no:O$cell_no");
					$header_day = $this->namahari($c->tsdaily_date);
					$header = $header_day.", ".date('d-M-Y',strtotime($c->tsdaily_date));
					$objPHPExcel->getActiveSheet()->setCellValue("B$cell_no", $header);	
					$objPHPExcel->getActiveSheet()->getStyle("B$cell_no")->applyFromArray(array("font" => array( "bold" => true)));				
					$cell_no++;
				}elseif($tgl_start != $tgl_end AND $no!=1){
					$objPHPExcel->getActiveSheet()->setCellValue("B$cell_no", "");
					$objPHPExcel->getActiveSheet()->setCellValue("C$cell_no", $total_angsuranpokok);
					$objPHPExcel->getActiveSheet()->setCellValue("D$cell_no", $total_profit);
					$objPHPExcel->getActiveSheet()->setCellValue("E$cell_no", $total_tabwajib);
					$objPHPExcel->getActiveSheet()->setCellValue("F$cell_no", $total_tabungan_debet);
					$objPHPExcel->getActiveSheet()->setCellValue("G$cell_no", $total_tabungan_credit);
					$objPHPExcel->getActiveSheet()->setCellValue("H$cell_no", $total_total_rf);
					$objPHPExcel->getActiveSheet()->setCellValue("I$cell_no", $total_total_tabungan);
					$objPHPExcel->getActiveSheet()->setCellValue("J$cell_no", $total_total_tabungan + $total_total_rf);
					$objPHPExcel->getActiveSheet()->setCellValue("K$cell_no", $total_absen_h);
					$objPHPExcel->getActiveSheet()->setCellValue("L$cell_no", $total_absen_s);
					$objPHPExcel->getActiveSheet()->setCellValue("M$cell_no", $total_absen_c);
					$objPHPExcel->getActiveSheet()->setCellValue("N$cell_no", $total_absen_i);
					$objPHPExcel->getActiveSheet()->setCellValue("O$cell_no", $total_absen_a);
					$objPHPExcel->getActiveSheet()->getStyle("B$cell_no:O$cell_no")->applyFromArray(array("font" => array( "bold" => true)));
					$objPHPExcel->getActiveSheet()->getStyle("C$cell_no:J$cell_no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle("K$cell_no:O$cell_no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$cell_no++;
					$objPHPExcel->getActiveSheet()->mergeCells("B$cell_no:O$cell_no");
					$header_day = $this->namahari($c->tsdaily_date);
					$header = $header_day.", ".date('d-M-Y',strtotime($c->tsdaily_date));
					$objPHPExcel->getActiveSheet()->setCellValue("B$cell_no", $header);
					$objPHPExcel->getActiveSheet()->getStyle("B$cell_no")->applyFromArray(array("font" => array( "bold" => true)));
					
					$cell_no++;
					$total_angsuranpokok 	= 0;
					$total_profit  			= 0;
					$total_tabwajib  		= 0;
					$total_tabungan_debet  	= 0;
					$total_tabungan_credit  = 0;
					$total_total_rf  		= 0;
					$total_total_tabungan  	= 0;
					$total_absen_h	= 0;
					$total_absen_s	= 0;
					$total_absen_c	= 0;
					$total_absen_i	= 0; 
					$total_absen_a	= 0;
				}
					
				$total_angsuranpokok += $c->tsdaily_angsuranpokok;
				$total_profit += $c->tsdaily_profit;
				$total_tabwajib += $c->tsdaily_tabwajib;
				$total_tabungan_debet += $c->tsdaily_tabungan_debet;
				$total_tabungan_credit += $c->tsdaily_tabungan_credit;
				$total_total_rf += $c->tsdaily_total_rf;
				$total_total_tabungan += $c->tsdaily_total_tabungan;
				$total_absen_h	+= $c->tsdaily_absen_h;
				$total_absen_s	+= $c->tsdaily_absen_s;
				$total_absen_c	+= $c->tsdaily_absen_c;
				$total_absen_i	+= $c->tsdaily_absen_i;
				$total_absen_a	+= $c->tsdaily_absen_a; 
				
				$objPHPExcel->getActiveSheet()->setCellValue("A$cell_no", $no);
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell_no", $c->tsdaily_group);
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell_no", $c->tsdaily_angsuranpokok);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell_no", $c->tsdaily_profit);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell_no", $c->tsdaily_tabwajib);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell_no", $c->tsdaily_tabungan_debet);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell_no", $c->tsdaily_tabungan_credit);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell_no", $c->tsdaily_total_rf);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell_no", $c->tsdaily_total_tabungan);
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell_no", $c->tsdaily_total_tabungan + $c->tsdaily_total_rf);
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell_no", $c->tsdaily_absen_h);
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell_no", $c->tsdaily_absen_s);
				$objPHPExcel->getActiveSheet()->setCellValue("M$cell_no", $c->tsdaily_absen_c);
				$objPHPExcel->getActiveSheet()->setCellValue("N$cell_no", $c->tsdaily_absen_i);
				$objPHPExcel->getActiveSheet()->setCellValue("O$cell_no", $c->tsdaily_absen_a);
				$objPHPExcel->getActiveSheet()->getStyle("C$cell_no:J$cell_no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("K$cell_no:O$cell_no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

						
				$cell_no++;
				$tgl_end = $c->tsdaily_date; 
				$no++;
			endforeach;
			
				$objPHPExcel->getActiveSheet()->setCellValue("B$cell_no", "");
				$objPHPExcel->getActiveSheet()->setCellValue("C$cell_no", $total_angsuranpokok);
				$objPHPExcel->getActiveSheet()->setCellValue("D$cell_no", $total_profit);
				$objPHPExcel->getActiveSheet()->setCellValue("E$cell_no", $total_tabwajib);
				$objPHPExcel->getActiveSheet()->setCellValue("F$cell_no", $total_tabungan_debet);
				$objPHPExcel->getActiveSheet()->setCellValue("G$cell_no", $total_tabungan_credit);
				$objPHPExcel->getActiveSheet()->setCellValue("H$cell_no", $total_total_rf);
				$objPHPExcel->getActiveSheet()->setCellValue("I$cell_no", $total_total_tabungan);
				$objPHPExcel->getActiveSheet()->setCellValue("J$cell_no", $total_total_tabungan + $total_total_rf);
				$objPHPExcel->getActiveSheet()->setCellValue("K$cell_no", $total_absen_h);
				$objPHPExcel->getActiveSheet()->setCellValue("L$cell_no", $total_absen_s);
				$objPHPExcel->getActiveSheet()->setCellValue("M$cell_no", $total_absen_c);
				$objPHPExcel->getActiveSheet()->setCellValue("N$cell_no", $total_absen_i);
				$objPHPExcel->getActiveSheet()->setCellValue("O$cell_no", $total_absen_a);
				$objPHPExcel->getActiveSheet()->getStyle("B$cell_no:O$cell_no")->applyFromArray(array("font" => array( "bold" => true)));
				$objPHPExcel->getActiveSheet()->getStyle("C$cell_no:J$cell_no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle("K$cell_no:O$cell_no")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$cell_no++;
			
			//Set Column Format Accounting
			$objPHPExcel->getActiveSheet()->getStyle("C7:J$cell_no")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_ACCOUNTING);
			
			//Set Column Auto Width
			foreach(range('B','J') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			//EXPORT	
			$filename = "Rekap_Topsheet_".$branch_name."_" . time() . '.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			
			redirect('accounting/neraca', 'refresh');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
}