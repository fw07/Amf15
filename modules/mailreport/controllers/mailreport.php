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
			
			
			$body = "<table class='std' width='320px'>";
			$body .= "";
			$body .= "<tr><td width='180px'>TOTAL ANGGOTA  </td><td>".number_format($total_anggota)."</td></tr>"; 
			$body .= "<tr><td>TOTAL MAJELIS  </td><td>".number_format($total_majelis)."</td></tr>"; 
			$body .= "<tr><td colspan='2'><hr/></td></tr>";
			$body .= "<tr><td>ANGGOTA BARU </td><td>".$total_clients_weekly."</td></tr>";
			$body .= "<tr><td>ANGGOTA KELUAR </td><td>".$total_unreg_clients_weekly."</td></tr>";
			$body .= "<tr><td colspan='2'><hr/></td></tr>";
			$body .= "<tr><td>JUMLAH TRANSAKSI </td><td>".$total_transaksi."</td></tr>";
			$body .= "<tr><td>JUMLAH TOPSHEET </td><td>".$tsdaily->total_majelis."</td></tr>";
			$body .= "<tr><td>TINGKAT KEHADIRAN </td><td>".round($total_kehadiran_persen)."%</td></tr>";
			$body .= "<tr><td colspan='2'><hr/></td></tr>";
			$body .= "<tr><td>ANGSURAN POKOK  </td><td>Rp ".number_format($tsdaily->total_angsuranpokok)."</td></tr>";  
			$body .= "<tr><td>ANGSURAN PROFIT </td><td>Rp ".number_format($tsdaily->total_angsuranprofit)."</td></tr>";  
			$body .= "<tr><td>TABUNGAN SUKARELA </td><td>Rp ".number_format($tsdaily->total_tabungan_sukarela)."</td></tr>"; 
			$body .= "<tr><td>TABUNGAN BERJANGKA </td><td>Rp ".number_format($tsdaily->total_tabungan_berjangka)."</td></tr>"; 
			$body .= "<tr><td colspan='2'><hr/></td></tr>";
			$body .= "<tr><td>JUMLAH PENGAJUAN</td><td>".$total_pengajuan."</td></tr>"; 
			$body .= "<tr><td>DANA PENGAJUAN</td><td>Rp ".number_format($total_uang_pengajuan)."</td></tr>"; 
			$body .= "<tr><td>JUMLAH PENCAIRAN</td><td>".$total_pencairan."</td></tr>";
			$body .= "<tr><td>DANA DICAIRKAN</td><td>Rp ".number_format($total_uang_pencairan)."</td></tr>";
			$body .= "</table>";
			
			$html ='<html lang="en">
							<head>
							  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
							  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
							  <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
							  <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
							  <title>Amartha Microfinance</title>

							  <style type="text/css">
								body {
								  margin: 0;
								  padding: 0;
								  -ms-text-size-adjust: 100%;
								  -webkit-text-size-adjust: 100%;
								}

								table {
								  border-spacing: 0;
								}

								table td {
								  border-collapse: collapse;
								  
								}

								.ExternalClass {
								  width: 100%;
								}

								.ExternalClass,
								.ExternalClass p,
								.ExternalClass span,
								.ExternalClass font,
								.ExternalClass td,
								.ExternalClass div {
								  line-height: 100%;
								}

								.ReadMsgBody {
								  width: 100%;
								  background-color: #ebebeb;
								}

								table {
								  mso-table-lspace: 0pt;
								  mso-table-rspace: 0pt;
								}

								img {
								  -ms-interpolation-mode: bicubic;
								}

								.yshortcuts a {
								  border-bottom: none !important;
								}
						
								@media screen and (max-width: 599px) {
								  table[class="force-row"],
								  table[class="container"] {
									width: 100% !important;
									max-width: 100% !important;
								  }
								}
								@media screen and (max-width: 400px) {
								  td[class*="container-padding"] {
									padding-left: 12px !important;
									padding-right: 12px !important;
								  }
								}
								.ios-footer a {
								  color: #aaaaaa !important;
								  text-decoration: underline;
								}
								.std{
									font-size: 12px;
									
								}
								hr{
									border: 0;
									border-bottom: 1px dashed #ccc;
									background: #999;
								}
								table.std td{
									padding: 4px 0;
								}
								.purple{ color #704390; }
								</style>

							</head>
							<body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

							<!-- 100% background wrapper (grey background) -->
							<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
							  <tr>
								<td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

								  <br>

								  <!-- 600px container (white background) -->
								  <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
									<tr>
									  <td class="container-padding header" align="left" style="padding-bottom:12px;color:#99cc00;padding-left:24px;padding-right:24px">
									   <br/><img src="http://amartha.co.id/themes/default/img/logo-black.png" />
									  
									  </td>
									</tr>
									<tr>
									  <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
										<br>
										<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#704390">Weeky Transaction Report ('.$date_now.')</div>
										<br>

										<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
											'.$body.'
											<br><br>
											<small style="color:#666;"><i>The numbers are calculated based on transaction entry by admin staff at Amartha Microfinance.<br/>Generated at '.$timestamp.'</i></small>
											
											<br><br>
										</div>

									  </td>
									</tr>
									<tr>
									  <td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
										<br>
										<strong>Amartha Microfinance</strong><br>
										<span class="ios-footer">
										 <a href="mailto:info@amartha.co.id" style="color:#aaaaaa;text-decoration: none;">info@amartha.co.id</a><br/>
										</span>
										<a href="http://www.amartha.co.id" style="color:#aaaaaa;text-decoration: none;">www.amartha.co.id</a><br>

										<br><br>

									  </td>
									</tr>
								  </table>


								</td>
							  </tr>
							</table>

							</body>
							</html>';
							echo $html; 
			
			//UPDATE EMAIL	
			$this->load->library('email');
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'mail.amartha.com',
				'smtp_port' => '25',
				'smtp_user' => 'mis@amartha.com', // change it to yours
				'smtp_pass' => 'MISamartha', // change it to yours
				'mailtype' => 'html',
				'charset' => 'utf-8',
				'wordwrap' => FALSE,
				'newline' => "\r\n"
			);

			$this->email->initialize($config);

			$this->email->from('mis@amartha.com','Amartha MIS'); 
			$this->email->to('fikri@amartha.co.id, ataufan@amartha.co.id, ywibawa@amartha.co.id, shardono@amartha.co.id, intanps@amartha.co.id'); 
			$this->email->bcc('mis@amartha.com'); 
			$this->email->subject('[Amartha MIS] Weekly Transaction Report ('.$date_now.')'); 
			$messagebody =  $html;	
			$this->email->message($messagebody); 
			$this->email->send();		
	}
	
	

}