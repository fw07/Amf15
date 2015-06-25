<?php

class Mailreport extends Front_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('mailreport_model');
		$this->load->model('tsdaily_model');
	}
	
	public function index(){
			$timestamp = date('Y-m-d H:i:s');
			$day = date('w');
			$date_now = date('Y-m-d');
			$date_month  = date('m');
			$date_year   = date('Y');
			$date_start  = date("Y-m-d", strtotime('-'.$day.' days'));
			$date_start  = date("Y-m-d", strtotime($date_start . ' + 1 day'));
			$date_end    = date("Y-m-d", strtotime($date_start . ' + 7 day'));					
			
					
			$total_anggota=$this->mailreport_model->count_all_clients();
			$total_majelis=$this->mailreport_model->count_majelis();
				
			$total_clients_weekly = $this->mailreport_model->count_weeklyclients($date_start,$date_end);
			$total_unreg_clients_weekly = $this->mailreport_model->count_weeklyunregclients($date_start,$date_end);
			
			
			$total_transaksi = $this->mailreport_model->count_weekly_transaction($date_start, $date_end);
			$total_kehadiran = $this->mailreport_model->count_weekly_kehadiran($date_start, $date_end);
			$total_kehadiran_persen = $total_kehadiran / ($total_transaksi) * 100;
			
			$tsdaily = $this->tsdaily_model->get_all_daily_report_summary_bydate($date_start, $date_end);
			$tsdaily = $tsdaily[0]; 
			
			$total_pengajuan = $this->mailreport_model->count_pengajuan($date_start, $date_end);
			$total_pencairan = $this->mailreport_model->count_pencairan($date_start, $date_end);
			$total_uang_pengajuan = $this->mailreport_model->count_total_pengajuan($date_start, $date_end);
			$total_uang_pencairan = $this->mailreport_model->count_total_pencairan($date_start, $date_end);
}