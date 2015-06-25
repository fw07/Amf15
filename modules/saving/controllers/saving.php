<?php

class Saving extends Front_Controller{
	
	private $per_page 	= '15';
	private $title 		= 'Saving';
	private $module 	= 'saving';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('tabwajib_model');
		$this->load->model('tabwajib_tr_model');
		$this->load->model('tabsukarela_model');
		$this->load->model('tabsukarela_tr_model');
		
		$this->load->library('pagination');		
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			redirect('saving/tabwajib', 'refresh');
		}
		else
		{
			 //If no session, redirect to login page
			 redirect('login', 'refresh');
		}
	}
	
	public function tabwajib($page='0')
	{
		if($this->session->userdata('logged_in'))
		{
		
			$user_branch = $this->session->userdata('user_branch');
			$total_rows = $this->tabwajib_model->count_all($this->input->post('q'),$user_branch);
				
			//pagination
			$config['base_url']     = site_url($this->module.'/tabwajib');
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
			
			//$this->pagination->initialize($config); 
			$no =  $this->uri->segment(3);
		
			$search_key = $this->input->post('key');
			$clients = $this->tabwajib_model->get_all_clients( $config['per_page'] , $page, $this->input->post('q'), $search_key, $user_branch);

			$this->template	->set('menu_title', 'Tabungan Wajib')
							->set('menu_saving', 'active')
							->set('clients', $clients)
							->set('list', $list)
							->set('no', $no)
							->set('config', $config)
							->build('tabwajib');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	public function tabwajib_view(){
		$client_account =  $this->uri->segment(3);
		$total_rows = $this->tabwajib_model->count_all_transaction($client_account);
				
			//pagination
			$config['base_url']     = site_url($this->module.'/tabwajib_view/'.$client_account);
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 15; 
			$config['uri_segment']  = 4;
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
			
			
		//GET DETAILS OF TAB WAJIB
		
		$data = $this->tabwajib_model->get_transaction($client_account);
		//$data = $data[0];
		
		$client = $this->tabwajib_model->get_account($client_account);		
		$client = $client[0];
 
		$this->template	->set('data', $data)
						->set('menu_title', 'Tabungan Wajib')
						->set('menu_saving', 'active')
						->set('list', $list)
						->set('no', $no)
						->set('config', $config)
						->set('client', $client)
						->set('client_account', $client_account)
						->build('tabwajib_history');	
	}
	
	public function tabwajib_download(){
		$client_account =  $this->uri->segment(3);
		
		$data = $this->tabwajib_model->get_transaction($client_account);
		
		$client = $this->tabwajib_model->get_account($client_account);		
		$client = $client[0];
 
		$timestamp=date("Ymdhis");
			$tgl=date("d-M-Y");
			$filename="MUTASI_TAB_WAJIB_".$client->client_account."_$timestamp";	
			$html = "<style> table tr td,table thead tr td, table tr th{ border-left:0; border-right:0;} table thead tr td,table thead tr th,table tr th{ border-bottom: 2px solid #000; }</style>";
			$html .= '';
			$no=1;
				$html .= '<h2 align="center">MUTASI TABUNGAN WAJIB</h2>';
				$html .= "<p align='center'>".$client->client_fullname." - ".$client->client_account."</p><br/>";
				$html .= '<table border="1" width="100%">';
				////$html .= '<thead>';                 
				$html .= '<tr>';
				$html .= '<th>NO</th>';
				$html .= '<th align="left" class="text-left">KODE TRANSAKSI</th>';
				$html .= '<th align="left" class="text-left">TANGGAL</th>';
				$html .= '<th align="right" class="text-right">DEBET</th>';
				$html .= '<th align="right" class="text-right">KREDIT</th>';
				$html .= '<th align="right" class="text-right">SALDO</th>';
				$html .= '<th align="left" class="text-left">KETERANGAN</th>';
				$html .= '</tr> ';                 
			foreach($data as $c):
				
				$html .= '<tr> ';
				$html .= '<td align="center">'.$no.'</td>';
				$html .= '<td>'.$c->tr_topsheet_code.'</td>	';
				$html .= '<td>'.date("Y-m-d", strtotime($c->tr_date)).'</td>';
				$html .= '<td align="right" class="text-right">'.number_format($c->tr_debet).'</td>';
				$html .= '<td align="right" class="text-right">'.number_format($c->tr_credit).'</td>';
				$html .= '<td align="right" class="text-right">'.number_format($c->tr_saldo).'</td>';
				$html .= '<td>'. $c->tr_remark.'</td>';
				$html .= '</tr>';
							
				$no++; 
			endforeach;
			$html .= '</table>';	
			$this->load->library('mpdf');
			$mpdf=new mPDF('utf-8', 'A4-L');
			$mpdf->SetHeader("Amartha Microfinance".'||'.$tgl.'|'); 
			$mpdf->SetFooter("Mutasi Tabungan Wajib".'||{PAGENO}|'); 
			$mpdf->WriteHTML($html);
			//echo $html;
			//$this->mpdf->Output();
			$pdfFilePath = FCPATH."downloads/mutasi/$filename.pdf";
			$pdffile = base_url()."downloads/mutasi/$filename.pdf";
			$mpdf->Output($pdfFilePath,'F');
			
			redirect($pdffile, 'refresh');
		
		
	}

	public function tabwajib_edit(){
		$client_account =  $this->uri->segment(3);
		$total_rows = $this->tabwajib_model->count_all_transaction($client_account);
				
			//pagination
			$config['base_url']     = site_url($this->module.'/tabwajib_view');
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
			
			
		//GET DETAILS OF TAB WAJIB
		
		$data = $this->tabwajib_model->get_transaction($client_account,$config['per_page'] , $page, $this->input->post('q'));
		//$data = $data[0];
		
		$this->template	->set('data', $data)
						->set('menu_title', 'Tabungan Wajib')
						->set('menu_saving', 'active')
						->set('list', $list)
						->set('no', $no)
						->set('config', $config)
						->build('tabwajib_edit');	
	}
	public function tabwajib_add(){
		if($this->save_tabwajib()){
			$this->session->set_flashdata('message', 'success|Transaksi telah ditambahkan');
			redirect($this->module.'/');
		}
		$client_account =  $this->uri->segment(3);
		$data = $this->tabwajib_model->get_account($client_account);		
		$data = $data[0];
		$this->template	->set('data', $data)
						->set('menu_title', 'Tabungan Wajib')
						->set('menu_saving', 'active')
						->set('list', $list)
						->set('no', $no)
						->set('data', $data)
						->set('config', $config)
						->build('tabwajib_form');
		
	}
	
	
	private function save_tabwajib(){
		
		$id =  $this->uri->segment(4);
		//Cek User Branch
		$user_branch = $this->session->userdata('user_branch');
		$user_id = $this->session->userdata('user_id');
		$timestamps = date(("Y-m-d H:i:s"));
		
		//set form validation
		$this->form_validation->set_rules('tabwajib_date', 'Tanggal', 'required');
		$this->form_validation->set_rules('tabwajib_account', 'Nomor Rekening', 'required');
		$this->form_validation->set_rules('tabwajib_remark', 'Remark', 'required');
		$this->form_validation->set_rules('tabwajib_debet', 'Debet', 'required');
		$this->form_validation->set_rules('tabwajib_credit', 'Kredit', 'required');
	
		if($this->form_validation->run() === TRUE){
			//process the form
			$account = $this->input->post('tabwajib_account');
			$today = date("Ymdhis");
			$tr_code= $today.$this->input->post('tabwajib_client');
			$tr_saldo = $this->input->post('tabwajib_saldo') + $this->input->post('tabwajib_debet') - $this->input->post('tabwajib_credit');
			
			if($tr_saldo){
				$tabwajib_debet  = $this->input->post('tabwajib_total_debet')  + $this->input->post('tabwajib_debet');
				$tabwajib_credit = $this->input->post('tabwajib_total_credit') + $this->input->post('tabwajib_credit');
				$data = array(
						'tr_account'       	=> $this->input->post('tabwajib_account'),
						'tr_date' 			=> $this->input->post('tabwajib_date'),	
						'tr_transactioncode' => $tr_code,	
						'tr_client' 		=> $this->input->post('tabwajib_client'),	
						'tr_debet' 			=> $this->input->post('tabwajib_debet'),	
						'tr_credit' 		=> $this->input->post('tabwajib_credit'),		
						'tr_saldo' 			=> $tr_saldo,
						'tr_remark' 		=> $this->input->post('tabwajib_remark'),
						'created_by'	   		=> $user_id,
						'created_on'	   		=> $timestamps,
				);
				$data2 = array(
						'tabwajib_account'      => $this->input->post('tabwajib_account'),
						'tabwajib_date' 		=> $this->input->post('tabwajib_date'),	
						'tabwajib_client' 		=> $this->input->post('tabwajib_client'),	
						'tabwajib_debet' 		=> $tabwajib_debet,	
						'tabwajib_credit' 		=> $tabwajib_credit,		
						'tabwajib_saldo' 		=> $tr_saldo,
						'modified_by'	   		=> $user_id,
						'modified_on'	   		=> $timestamps,
						
				);
				if(!$id){
					$this->tabwajib_model->update($account, $data2);
					return $this->tabwajib_tr_model->insert($data);
					
					//Jurnal Tab.Wajib
					if($this->input->post('tabwajib_debet')){
						$jurnal_account_credit = "2010300"; //Simpanan Wajib Kelompok
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $this->input->post('tabwajib_debet');				
						$data_jurnal = array(
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,	
							'jurnal_remark' 		=> "Tab Wajib $tr_code",
							'jurnal_branch' 		=> $user_branch,
							'created_by'	   		=> $user_id,
							'created_on'	   		=> $timestamps,
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					
					//Jurnal Tab.Wajib
					if($this->input->post('tabwajib_credit')){
						$jurnal_account_credit = "1010001"; //Kas Teller
						$jurnal_account_debet = "2010300";  //Simpanan Wajib Kelompok
						$nominal = $this->input->post('tabwajib_credit');				
						$data_jurnal = array(
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,	
							'jurnal_remark' 		=> "Tab Wajib $tr_code",
							'jurnal_branch' 		=> $user_branch,
							'created_by'	   		=> $user_id,
							'created_on'	   		=> $timestamps,
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
				}else{
					$this->tabwajib_model->update($account, $data2);
					return $this->tabwajib_tr_model->update($id, $data);
				}
			}else{
				$this->session->set_flashdata('message', 'success| Saldo tidak cukup');
				redirect($this->module.'/tabwajib');
			}
		}
	}	
		
	public function tabsukarela($page='0')
	{
		if($this->session->userdata('logged_in'))
		{
			$user_branch = $this->session->userdata('user_branch');
		
			$total_rows = $this->tabsukarela_model->count_all($this->input->post('q'),$user_branch);
				
			//pagination
			$config['base_url']     = site_url($this->module.'/tabsukarela');
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
			
			//$this->pagination->initialize($config); 
			$no =  $this->uri->segment(3);
		
			$search_key = $this->input->post('key');
			$clients = $this->tabsukarela_model->get_all_clients( $config['per_page'] , $page, $this->input->post('q'), $search_key, $user_branch);

			$this->template	->set('menu_title', 'Tabungan Sukarela')
							->set('menu_saving', 'active')
							->set('clients', $clients)
							->set('list', $list)
							->set('no', $no)
							->set('config', $config)
							->build('tabsukarela');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	

	
	public function tabsukarela_view(){
		$client_account =  $this->uri->segment(3);
		$total_rows = $this->tabsukarela_model->count_all_transaction($client_account);
				
			//pagination
			$config['base_url']     = site_url($this->module.'/tabsukarela_view/'.$client_account);
			$config['total_rows']   = $total_rows;
			$config['per_page']     = 15; 
			$config['uri_segment']  = 4;
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
			
			
		//GET DETAILS OF TAB SUKARELA
		
		$data = $this->tabsukarela_model->get_transaction($client_account);
		//$data = $data[0];
		
		$client = $this->tabsukarela_model->get_account($client_account);		
		$client = $client[0];
 
		$this->template	->set('data', $data)
						->set('menu_title', 'Tabungan Sukarela')
						->set('menu_saving', 'active')
						->set('list', $list)
						->set('no', $no)
						->set('config', $config)
						->set('client', $client)
						->set('client_account', $client_account)
						->build('tabsukarela_history');	
	}

	public function tabsukarela_download(){
		$client_account =  $this->uri->segment(3);		
		$data = $this->tabsukarela_model->get_transaction($client_account);
		//$data = $data[0];
		
		$client = $this->tabsukarela_model->get_account($client_account);		
		$client = $client[0];
		
		$timestamp=date("Ymdhis");
			$tgl=date("d-M-Y");
			$filename="MUTASI_TAB_SUKARELA_".$client->client_account."_$timestamp";	
			$html = "<style> table tr td,table thead tr td, table tr th{ border-left:0; border-right:0;} table thead tr td,table thead tr th,table tr th{ border-bottom: 2px solid #000; }</style>";
			$html .= '';
			$no=1;
				$html .= '<h2 align="center">MUTASI TABUNGAN SUKARELA</h2>';
				$html .= "<p align='center'>".$client->client_fullname." - ".$client->client_account."</p><br/>";
				$html .= '<table border="1" width="100%">';
				////$html .= '<thead>';                 
				$html .= '<tr>';
				$html .= '<th>NO</th>';
				$html .= '<th align="left" class="text-left">KODE TRANSAKSI</th>';
				$html .= '<th align="left" class="text-left">TANGGAL</th>';
				$html .= '<th align="right" class="text-right">DEBET</th>';
				$html .= '<th align="right" class="text-right">KREDIT</th>';
				$html .= '<th align="right" class="text-right">SALDO</th>';
				$html .= '<th align="left" class="text-left">KETERANGAN</th>';
				$html .= '</tr> ';                 
			foreach($data as $c):
				
				$html .= '<tr> ';
				$html .= '<td align="center">'.$no.'</td>';
				$html .= '<td>'.$c->tr_topsheet_code.'</td>	';
				$html .= '<td>'.date("Y-m-d", strtotime($c->tr_date)).'</td>';
				$html .= '<td align="right" class="text-right">'.number_format($c->tr_debet).'</td>';
				$html .= '<td align="right" class="text-right">'.number_format($c->tr_credit).'</td>';
				$html .= '<td align="right" class="text-right">'.number_format($c->tr_saldo).'</td>';
				$html .= '<td>'. $c->tr_remark.'</td>';
				$html .= '</tr>';
							
				$no++; 
			endforeach;
			$html .= '</table>';	
			$this->load->library('mpdf');
			$mpdf=new mPDF('utf-8', 'A4-L');
			$mpdf->SetHeader("Amartha Microfinance".'||'.$tgl.'|'); 
			$mpdf->SetFooter("Mutasi Tabungan Sukarela".'||{PAGENO}|'); 
			$mpdf->WriteHTML($html);
			//echo $html;
			//$this->mpdf->Output();
			$pdfFilePath = FCPATH."downloads/mutasi/$filename.pdf";
			$pdffile = base_url()."downloads/mutasi/$filename.pdf";
			$mpdf->Output($pdfFilePath,'F');
			
			redirect($pdffile, 'refresh');
	}
	
	public function tabsukarela_edit(){
		$client_account =  $this->uri->segment(3);
		$total_rows = $this->tabsukarela_model->count_all_transaction($client_account);
				
			//pagination
			$config['base_url']     = site_url($this->module.'/tabsukarela_view');
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
			
			
		//GET DETAILS OF TAB WAJIB
		
		$data = $this->tabsukarela_model->get_transaction($client_account,$config['per_page'] , $page, $this->input->post('q'));
		//$data = $data[0];
		
		$this->template	->set('data', $data)
						->set('menu_title', 'Tabungan Sukarela')
						->set('menu_saving', 'active')
						->set('list', $list)
						->set('no', $no)
						->set('config', $config)
						->build('tabsukarela_edit');	
	}
	public function tabsukarela_add(){
		if($this->save_tabsukarela()){
			$this->session->set_flashdata('message', 'success|Transaksi telah ditambahkan');
			redirect('saving/tabsukarela');
		}
		$client_account =  $this->uri->segment(3);
		$data = $this->tabsukarela_model->get_account($client_account);		
		$data = $data[0];
		$this->template	->set('data', $data)
						->set('menu_title', 'Tabungan Sukarela')
						->set('menu_saving', 'active')
						->set('list', $list)
						->set('no', $no)
						->set('data', $data)
						->set('config', $config)
						->build('tabsukarela_form');
		
	}
	private function save_tabsukarela(){
		
		$id =  $this->uri->segment(4);
		//Cek User Branch
		$user_branch = $this->session->userdata('user_branch');
		$user_id = $this->session->userdata('user_id');
		$timestamps = date(("Y-m-d H:i:s"));
		//set form validation
		$this->form_validation->set_rules('tabsukarela_date', 'Tanggal', 'required');
		$this->form_validation->set_rules('tabsukarela_account', 'Nomor Rekening', 'required');
		$this->form_validation->set_rules('tabsukarela_remark', 'Remark', 'required');
		$this->form_validation->set_rules('tabsukarela_debet', 'Debet', 'required');
		$this->form_validation->set_rules('tabsukarela_credit', 'Kredit', 'required');
	
		if($this->form_validation->run() === TRUE){
			//process the form
			$account = $this->input->post('tabsukarela_account');
			$today = date("Ymdhis");
			$tr_code= $today.$this->input->post('tabsukarela_client');
			$tr_saldo = $this->input->post('tabsukarela_saldo') + $this->input->post('tabsukarela_debet') - $this->input->post('tabsukarela_credit');
			if($tr_saldo >= 0){
				$tabsukarela_debet  = $this->input->post('tabsukarela_total_debet')  + $this->input->post('tabsukarela_debet');
				$tabsukarela_credit = $this->input->post('tabsukarela_total_credit') + $this->input->post('tabsukarela_credit');
				$data = array(
						'tr_account'       	=> $this->input->post('tabsukarela_account'),
						'tr_date' 			=> $this->input->post('tabsukarela_date'),	
						'tr_transactioncode' => $tr_code,	
						'tr_client' 		=> $this->input->post('tabsukarela_client'),	
						'tr_debet' 			=> $this->input->post('tabsukarela_debet'),	
						'tr_credit' 		=> $this->input->post('tabsukarela_credit'),		
						'tr_saldo' 			=> $tr_saldo,
						'tr_remark' 		=> $this->input->post('tabsukarela_remark'),
						'created_by'	   		=> $user_id,
						'created_on'	   		=> $timestamps,
				);
				$data2 = array(
						'tabsukarela_account'       => $this->input->post('tabsukarela_account'),
						'tabsukarela_date' 			=> $this->input->post('tabsukarela_date'),	
						'tabsukarela_client' 		=> $this->input->post('tabsukarela_client'),	
						'tabsukarela_debet' 		=> $tabsukarela_debet,	
						'tabsukarela_credit' 		=> $tabsukarela_credit,		
						'tabsukarela_saldo' 		=> $tr_saldo,
						'modified_by'	   		=> $user_id,
						'modified_on'	   		=> $timestamps,
						
				);
				if(!$id){
					$this->tabsukarela_model->update($account, $data2);
					return $this->tabsukarela_tr_model->insert($data);
					
					/*
					//Jurnal Tab.Sukarela Debet
					if($this->input->post('tabsukarela_debet') ){
						$jurnal_account_credit = "2010100"; //Simpanan Sukarela
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $this->input->post('tabsukarela_debet');				
						$data_jurnal = array(
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,	
							'jurnal_remark' 		=> "Tab Sukarela $tr_code",
							'jurnal_branch' 		=> $user_branch,
							'created_by'	   		=> $user_id,
							'created_on'	   		=> $timestamps,
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					
					//Jurnal Tab.Sukarela Kredit
					if($this->input->post('tabsukarela_credit') ){
						$jurnal_account_credit = "1010001"; //Kas Teller
						$jurnal_account_debet = "2010100";  //Simpanan Sukarela
						$nominal = $this->input->post('tabsukarela_credit');				
						$data_jurnal = array(
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,	
							'jurnal_remark' 		=> "Tab Sukarela $tr_code",
							'jurnal_branch' 		=> $user_branch,
							'created_by'	   		=> $user_id,
							'created_on'	   		=> $timestamps,
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					*/ 
				}else{
					$this->tabsukarela_model->update($account, $data2);
					return $this->tabsukarela_tr_model->update($id, $data);
				}
				
				
				
			}else{
				$this->session->set_flashdata('message', 'success| Saldo tidak cukup');
				redirect($this->module.'/tabsukarela');
			}
		}
	}
	
}