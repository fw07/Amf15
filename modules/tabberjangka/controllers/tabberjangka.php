<?php

class Tabberjangka extends Front_Controller{
	
	private $per_page 	= '15';
	private $title 		= 'Tabberjangka';
	private $module 	= 'tabberjangka';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('tabwajib_model');
		$this->load->model('tabwajib_tr_model');
		$this->load->model('tabsukarela_model');
		$this->load->model('tabsukarela_tr_model');
		$this->load->model('tabberjangka_model');
		$this->load->model('tabberjangka_tr_model');
		$this->load->model('group_model');
		$this->load->model('clients_model');
		
		$this->load->library('pagination');		
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			redirect('tabberjangka/browse', 'refresh');
		}
		else
		{
			 //If no session, redirect to login page
			 redirect('login', 'refresh');
		}
	}
	
	public function browse($page='0')
	{
		if($this->session->userdata('logged_in'))
		{
		
			$user_branch = $this->session->userdata('user_branch');
			$total_rows = $this->tabberjangka_model->count_all($this->input->post('q'),$user_branch);
				
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
			$clients = $this->tabberjangka_model->get_all_clients( $config['per_page'] , $page, $this->input->post('q'), $search_key, $user_branch);

			$this->template	->set('menu_title', 'Tabungan Berjangka')
							->set('menu_saving', 'active')
							->set('clients', $clients)
							->set('list', $list)
							->set('no', $no)
							->set('config', $config)
							->build('tabberjangka');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
	
	public function register(){
		
		if($this->save_tabberjangka()){
			$this->session->set_flashdata('message', 'success|Tabungan berjangka telah ditambahkan');
			redirect($this->module.'/browse');
		}
		
		//Cek User Login Branch
		$user_branch = $this->session->userdata('user_branch');
		
		//Get Total Group Row 
		if($user_branch != 0){	
			$group = $this->group_model->get_list_group_by_branch($user_branch)->result();			
		}else{
			$group = $this->group_model->get_list_group()->result();	
		}
		
		$this->template	->set('menu_title', 'Registrasi Tabungan Berjangka')
						->set('group', $group)
						->set('menu_saving', 'active')
						->build('tabberjangka_register');	
		
	}
	
	private function save_tabberjangka(){
	
		$no = $this->input->post('no');
		if($no){
			for($i=1; $i<=$no; $i++){
				$client_reg 	= $this->input->post("client_reg_".$i);
				
				if($client_reg == "1"){					
					$client_id = $this->input->post("client_name_".$i);
					$paket = $this->input->post("client_paket_".$i);
					$waktu = $this->input->post("client_waktu_".$i);
					if($client_id AND $paket AND $waktu AND $this->input->post("client_date_".$i) ){
					
						$client_account = $this->clients_model->get_client($client_id)->result();
						$client_account = $client_account[0]->client_account;
						//INSERT PEMBIAYAAN
						$data = array(
								'tabberjangka_account'    	=> $client_account,
								'tabberjangka_client'    	=> $this->input->post("client_name_".$i),
								'tabberjangka_date'  		=> $this->input->post("client_date_".$i),
								'tabberjangka_plafond'    	=> $paket,
								'tabberjangka_minggu'    	=> $waktu,
								'tabberjangka_debet'    	=> 0,
								'tabberjangka_credit'    	=> 0,
								'tabberjangka_saldo'    	=> 0							
						);
						
						$check = $this->tabberjangka_model->get_account($client_account);
						$check = $check[0]->client_account;
						
						if(!$check){$this->tabberjangka_model->insert($data);}
						else{ return false;	 }
						
					
					}
					
				}
			} return true;	
		}
			 
	}
	
	
	
	
	
	public function tabberjangka_view(){
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
			
			
		//GET DETAILS OF TAB BERJANGKA
		
		$data = $this->tabberjangka_model->get_transaction($client_account);
		//$data = $data[0];
		
		$client = $this->tabberjangka_model->get_account($client_account);		
		$client = $client[0];
 
		$this->template	->set('data', $data)
						->set('menu_title', 'Tabungan Berjangka')
						->set('menu_saving', 'active')
						->set('list', $list)
						->set('no', $no)
						->set('config', $config)
						->set('client', $client)
						->set('client_account', $client_account)
						->build('tabberjangka_history');	
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

	
	//AJAX GET CLIENT
	public function getclient()
	{
			$groupid=$this->input->post('name');			
			$table='tbl_clients';
			$where=array('client_group' => $groupid, 'deleted' => 0, 'client_status' => 1);
			$data['sc_get']=$this->clients_model->get_where_data($table,$where);
			$sc=json_encode($data['sc_get']);
			echo $sc;
	}
	
	
	public function tabberjangka_edit(){
		$client_account =  $this->uri->segment(3);
		$total_rows = $this->tabberjangka_model->count_all_transaction($client_account);
				
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
			
			
		//GET DETAILS OF TAB BERJANGKA
		
		$data = $this->tabberjangka_model->get_transaction($client_account,$config['per_page'] , $page, $this->input->post('q'));
		//$data = $data[0];
		
		$this->template	->set('data', $data)
						->set('menu_title', 'Tabungan Berjangka')
						->set('menu_saving', 'active')
						->set('list', $list)
						->set('no', $no)
						->set('config', $config)
						->build('tabberjangka_edit');	
	}
	
	
	public function update_tabberjangka(){
		$user_branch = $this->session->userdata('user_branch');
		$clients = $this->tabberjangka_model->get_all_clients( $config['per_page'] , $page, $this->input->post('q'), $search_key, $user_branch);
		foreach($clients as $c){
			$client_account = $c->tabberjangka_account;
			$plafond = $c->tabberjangka_plafond;
			$tb_id = $c->tabberjangka_id;
			$history = $this->tabberjangka_model->get_transaction2($client_account);	
			echo $tb_id." ".$c->tabberjangka_account." <br/>";
				$saldo=0;
				$no=1;
				
				foreach($history as $h){
					$saldo_transaksi = $h->tr_saldo;
					$minggu = $h->tr_saldo / $plafond;
					
					$tr_id =  $h->tr_id ;
					
					echo $tr_id.". ".$h->tr_debet." -- ".$saldo_transaksi ." - $minggu<br/>";
					
						$data_tb = array(
								'tabberjangka_angsuranke'    	=> $minggu					
						);
						
						$data_tr = array(
								'tr_angsuranke'    	=> $minggu
						);
						
						//$this->tabberjangka_tr_model->update($tr_id,$data_tr);
						//$this->tabberjangka_model->update($client_account,$data_tb);
						$no++;
				}
			echo "<br/>";
		}
	}
}