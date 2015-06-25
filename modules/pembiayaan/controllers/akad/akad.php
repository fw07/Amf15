<?php

class Akad extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Ts_download';
	private $module 	= 'ts_download';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('clients_pembiayaan_model');
		
		$this->load->library('pagination');	
	}
	
	
	public function download(){			
			
			$data_id =  $this->uri->segment(4);
			$pembiayaan = $this->clients_pembiayaan_model->get_pembiayaan($data_id)->result();
			$pembiayaan = $pembiayaan[0];
			//print_r($pembiayaan);
			
			$html = "";
			$html .= '<style>
						@page{ margin-top: 1cm; margin-bottom: 1cm; margin-left: 1cm; margin-right: 1cm;}
						body, p{ font-family: Helvetica, Arial;font-size: 12px;line-height: 130%;} 
						.tbl{border-collapse: collapse;border: none;}
						.tbl thead{border-bottom: 1px solid #000;}
						.tbl td, .tbl th{padding: 3px;border: none;}
						.clear{float: none;clear: both}
						h2{ line-height: 130%; }
					</style>';
			$html .= "<div style=''>";
			$html .= '<h2 align="center">PERNYATAAN PEMBIAYAAN<br/>"AL IJARAH (JASA) / AL HIWALAH (PENALANGAN)"<br/>KOPERASI AMARTHA INDONESIA</h2>';		
			$html .= '<hr/>';
			$html .= '<p>Yang bertanda tangan di bawah ini :</p>';
			
			$html .= '<table class="tbl">';
			$html .= '<tr><td width="30px">1.</td><td width="80px">Nama</td><td width="250px"> : <b>'.$pembiayaan->officer_name.'</b></td><td width="80px"> </td><td width="200px"></td></tr>';
			$html .= '<tr><td></td><td>Jabatan</td><td colspan="3"> : <b>Field Officer Amartha Indonesia</b></td></tr>';
			$html .= '<tr><td></td><td colspan="4">Bertindak atas nama Koperasi Amartha Indonesia untuk selanjutnya Pihak Pertama</td></tr>';
			$html .= '<tr><td></td><td colspan="4"> </td></tr>';
			
			$html .= '<tr><td>2.</td><td>Nama</td><td> : <b>'.$pembiayaan->client_fullname.'</b></td><td>Majelis</td><td> : <b>'.$pembiayaan->group_name.'</b></td></tr>';
			$html .= '<tr><td></td><td>Alamat</td><td> : <b>'.$pembiayaan->client_kampung.', '.$pembiayaan->client_desa.''.$pembiayaan->client_kecamatan.'</b></td><td>Pekerjaan</td><td> : <b>'.$pembiayaan->client_job.'</b></td></tr>';
			$html .= '<tr><td></td><td>No. KTP</td><td> : <b>'.$pembiayaan->client_ktp.'</b></td><td></td><td></td></tr>';
			$html .= '<tr><td></td><td colspan="4">Dalam hal ini bertindak atas nama pribadi, selanjutnya dalam perjanjian ini disebut Pihak Kedua.</td></tr>';
			$html .= '</table>';
			$html .= '<br/>';
			$html .= '<p>Menerangkan telah sepakat untuk membuat persetujuan aqad Al Ijaroh /Al Hiwalah sebagaimana tercantum di bawah ini  :</p>';
			
			$html .= '<table class="tbl">';
			$html .= '<tr><td width="30px" valign="top">1.</td><td>Pihak Kedua mengajukan Pembiayaan untuk <b>'.$pembiayaan->data_keterangan.'</b> sebesar <b>Rp '.number_format($pembiayaan->data_plafond).'</b>.</td></tr>';
			$html .= '<tr><td width="30px" valign="top">2.</td><td>Pihak Pertama bersedia memfasilitasi pengajuan Pihak Kedua untuk keperluan itu (butir 1) dan Pihak Pertama mewakilkan kepada Pihak Kedua untuk melakukan proses yang berkaitan dengan kepentingan sesuai butir 1 sebesar <b>Rp '.number_format($pembiayaan->data_plafond).'</b>.</td></tr>';
			$html .= '<tr><td width="30px" valign="top">3.</td><td>Jangka waktu pembiayaan yang diberikan oleh Pihak Pertama kepada Pihak Kedua sebesar tersebut di atas telah disepakati kedua belah pihak selama 50 minggu.</td></tr>';
			$html .= '<tr><td width="30px" valign="top">4.</td><td>Terhadap pembiayaan ini Pihak Kedua pada hakekatnya mengaku berhutang kepada Pihak Pertama dan semata-mata akan digunakan untuk keperluan sebagaimana yang tersebut dalam butir 1 (satu) di atas.</td></tr>';
			$html .= '<tr><td width="30px" valign="top">5.</td><td>Atas pembiayaan tersebut, Pihak Kedua bersedia :</td></tr>';
			$html .= '<tr><td width="30px"></td><td>';
			$html .= '<table class="tbl">';
			$html .= '<tr><td width="20px" valign="top">a)</td><td colspan="2">Memberikan ujrah kepada Pihak Pertama sebesar <b>Rp'.number_format($pembiayaan->data_margin).'</b> yang akan diangsur selama 50 minggu sebesar <b>Rp'.number_format($pembiayaan->data_margin/50).'</b></td></tr>';
			$html .= '<tr><td width="20px" valign="top">b)</td><td colspan="2">Melunasi hutang tersebut dengan cara membayar angsuran setiap minggu sebesar   <b>Rp '.number_format($pembiayaan->data_angsuranpokok + $pembiayaan->data_margin/50 + 1000).'</b>,  dengan rincian sebagai berikut:</td></tr>';
			$html .= '<tr><td width="20px"></td><td width="100px">Pokok </td><td width="">: <b>Rp '.number_format($pembiayaan->data_angsuranpokok).'</b></td></tr>';
			$html .= '<tr><td width="20px"></td><td width="">Ujroh </td><td width="">: <b>Rp '.number_format($pembiayaan->data_margin/50).'</b></td></tr>';
			$html .= '<tr><td width="20px"></td><td width="">Tab Wajib </td><td width="">: <b>Rp '.number_format(1000).'</b></td></tr>';
			$html .= '</table>';
			
			$html .= '</td></tr>';
			
			$html .= '<tr><td width="30px" valign="top">6.</td><td>Pihak Kedua bersedia membayar kepada Pihak Kesatu, biaya administrasi sebesar <b>Rp '.number_format($pembiayaan->data_plafond * 1/100).'</b></td></tr>';
			$html .= "<tr><td width='30px' valign='top'>7.</td><td>Pihak Kedua dalam kedudukannya sebagai pengguna dana harus memenuhi syarat usaha ataupun keperluan yang dijalankan oleh Pihak Kedua hendaknya halal menurut syara' serta tidak bertentangan dengan undang-undang dan hukum yang berlaku.</td></tr>";
			$html .= '<tr><td width="30px" valign="top">8.</td><td>Jika dikemudian hari ternyata terdapat kesalahan di dalam perjanjian ini dan atau terjadi perselisihan antara kedua belah pihak berkaitan dengan perjanjian ini akan di selesaikan dengan cara musyawarah mufakat yang dilandasi <i>ukhuwah islamiyyah</i>.</td></tr>';
			$html .= '<tr><td width="30px"></td><td></td></tr>';
			
			$html .= '</table>';
			$html .= '<p>Demikian perjanjian ini disepakati dan ditandatangani pada hari ...................... tanggal ........................ di ........................</p>';
			$html .= "<div align='center'><i>Walhamdulillaahirabbil'aalamiin</i></div>";
			$html .= "</div>";
			$html .= "<br/><br/>";
			$html .= '<table class="tbl" width="200%">';
			$html .= '<tr>';
			$html .= '<td width="33%" align="center">Pihak Pertama<br/><br/><br/><br/><br/><br/>('.$pembiayaan->officer_name.')</td>';
			$html .= '<td width="33%" align="center">Pihak Kedua<br/><br/><br/><br/><br/><br/>('.$pembiayaan->client_fullname.')</td>';
			$html .= '<td width="33%" align="center">Ketua Majelis<br/><br/><br/><br/><br/><br/>('.$pembiayaan->group_leader.')</td>';
			$html .= '</tr>';
			$html .= '</table>';
			
			//echo $html;
			
			$filename = "AKAD_".$pembiayaan->client_account;
			$this->load->library('mpdf');
			$mpdf=new mPDF('utf-8', 'A4');
			$mpdf->SetFooter("Top Sheet".'||{PAGENO}|'); 
			$mpdf->WriteHTML($html);
			
			$pdfFilePath = FCPATH."downloads/topsheet/$filename.pdf";
			$pdffile = base_url()."downloads/topsheet/$filename.pdf";
			$mpdf->Output($pdfFilePath,'F');
			redirect($pdffile, 'refresh');
			
	}
	
	
}