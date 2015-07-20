<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Report Review Model
 * 
 * @package	amartha
 * @author 	afahmi
 * @since	9 July 2015
 */
 
class review_outstanding_model extends MY_Model {
	//SELECT SUM(pinjaman.outstanding) FROM (
		//(SELECT (data_jangkawaktu-data_angsuranke)*(data_plafond/data_jangkawaktu) AS outstanding FROM tbl_pembiayaan) 
		//AS pinjaman)
	//COUNT OS PINJAMAN
	public function sum_all_outstanding_pinjaman()
	{
		$today = date('Y-m-d', strtotime('now'));
		$wheredate = "DATE(tbl_pembiayaan.data_jatuhtempo) >= "."'".$today."'";
		//SELECT SUM(pinjaman.outstanding) 
		//FROM ((SELECT (data_jangkawaktu-data_angsuranke)*(data_plafond/data_jangkawaktu) 
		//AS outstanding FROM tbl_pembiayaan WHERE data_jatuhtempo >= '2015-07-15') AS pinjaman)
		$q = "SELECT SUM(pinjaman.outstanding) AS os_pinjaman ";
		$q = $q."FROM ((SELECT (data_jangkawaktu-data_angsuranke)*(data_plafond/data_jangkawaktu) ";
		$q = $q."AS outstanding FROM tbl_pembiayaan ";
		$q = $q."WHERE ".$wheredate;
		$q = $q.") AS pinjaman)";
		return $this->db->query($q)->result_array();
		//return $this->db->select_sum("count(*) as os_pinjaman")
		//				->from('tbl_clients')
		//				->where('tbl_clients.deleted','0')
		//				->where('tbl_clients.client_status','1')
		//				->get()
		//				->row()
		//				->os_pinjaman;
	}

	public function sum_all_outstanding_pinjaman_per_lastmonth()
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_pembiayaan.data_jatuhtempo) >= "."'".$lastday_prevmonth."'";
		$q = "SELECT SUM(pinjaman.outstanding) AS os_pinjaman ";
		$q = $q."FROM ((SELECT (data_jangkawaktu-data_angsuranke)*(data_plafond/data_jangkawaktu) ";
		$q = $q."AS outstanding FROM tbl_pembiayaan ";
		$q = $q."WHERE ".$wheredate;
		$q = $q.") AS pinjaman)";
		return $this->db->query($q)->result_array();
	}

	public function sum_all_outstanding_pinjaman_by_branch($branch)
	{
		//SELECT SUM(outstanding) FROM ( ( SELECT (tbl_pembiayaan.data_jangkawaktu-tbl_pembiayaan.data_angsuranke)*(tbl_pembiayaan.data_plafond/tbl_pembiayaan.data_jangkawaktu) 
		//	AS outstanding FROM tbl_pembiayaan LEFT JOIN tbl_clients ON tbl_clients.client_id = tbl_pembiayaan.data_client LEFT JOIN tbl_branch ON tbl_branch.branch_id = tbl_clients.client_branch 
		//	WHERE tbl_pembiayaan.data_jatuhtempo >= '2015-07-15' AND tbl_clients.client_branch = '1' ) 
		//        AS pinjaman)
		
		//SELECT SUM(outstanding) 
		//FROM (
    	//		(
     				//SELECT (tbl_pembiayaan.data_jangkawaktu-tbl_pembiayaan.data_angsuranke)*(tbl_pembiayaan.data_plafond/tbl_pembiayaan.data_jangkawaktu) 
     				//AS outstanding FROM tbl_pembiayaan LEFT JOIN tbl_clients ON tbl_clients.client_id = tbl_pembiayaan.data_client LEFT JOIN tbl_branch ON tbl_branch.branch_id = tbl_clients.client_branch
     				//WHERE tbl_pembiayaan.data_jatuhtempo >= '2015-07-15' AND tbl_clients.client_branch = '1'
    	//		) 
    	//		AS pinjaman
    	//	)
		$today = date('Y-m-d', strtotime('now'));
		$wheredate = "DATE(tbl_pembiayaan.data_jatuhtempo) >= "."'".$today."'";
		$wherebranch = "tbl_clients.client_branch = '".$branch."'";
		$q = "SELECT SUM(outstanding) AS os_pinjaman ";
		$q = $q."FROM ((SELECT (tbl_pembiayaan.data_jangkawaktu-tbl_pembiayaan.data_angsuranke)*(tbl_pembiayaan.data_plafond/tbl_pembiayaan.data_jangkawaktu) ";
		$q = $q."AS outstanding FROM tbl_pembiayaan ";
		$q = $q."LEFT JOIN tbl_clients ON tbl_clients.client_id = tbl_pembiayaan.data_client LEFT JOIN tbl_branch ON tbl_branch.branch_id = tbl_clients.client_branch ";
		$q = $q."WHERE ".$wheredate.' AND '.$wherebranch;
		$q = $q.") AS pinjaman)";
		return $this->db->query($q)->row()->os_pinjaman;
	}

	public function sum_all_outstanding_pinjaman_by_branch_until_prevmonth($branch)
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_pembiayaan.data_jatuhtempo) >= "."'".$lastday_prevmonth."'";
		$wherebranch = "AND tbl_clients.client_branch = '".$branch."'";
		$q = "SELECT SUM(outstanding) AS os_pinjaman ";
		$q = $q."FROM ((SELECT (tbl_pembiayaan.data_jangkawaktu-tbl_pembiayaan.data_angsuranke)*(tbl_pembiayaan.data_plafond/tbl_pembiayaan.data_jangkawaktu) ";
		$q = $q."AS outstanding FROM tbl_pembiayaan ";
		$q = $q."LEFT JOIN tbl_clients ON tbl_clients.client_id = tbl_pembiayaan.data_client LEFT JOIN tbl_branch ON tbl_branch.branch_id = tbl_clients.client_branch ";
		$q = $q."WHERE ".$wheredate.' '.$wherebranch;
		$q = $q.") AS pinjaman)";
		return $this->db->query($q)->row()->os_pinjaman;
	}

	//SUM OS TABUNGAN
	//TAB SUKARELA
	//INACCURATTE - ALL CLIENTS BE THEIR STATUS 1 or 0 ARE ACCOUNTED->WRONG
	public function sum_all_outstanding_tabungan_sukarela_until_currmonth()
	{
		$today = date('Y-m-d', strtotime('now'));
		$wheredate = "DATE(tbl_tabsukarela.tabsukarela_date) <= "."'".$today."'";
		return $this->db->select('SUM(tabsukarela_saldo) as total_saldo')
						->from('tbl_tabsukarela')
						->where('tbl_tabsukarela.deleted','0')
						->where($wheredate)
						->get()
						->row()
						->total_saldo;
	}

	//INACCURATTE - ALL CLIENTS BE THEIR STATUS 1 or 0 ARE ACCOUNTED->WRONG
	public function sum_all_outstanding_tabungan_sukarela_until_prevmonth()
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_tabsukarela.tabsukarela_date) <= "."'".$lastday_prevmonth."'";
		return $this->db->select('SUM(tabsukarela_saldo) as total_saldo')
						->from('tbl_tabsukarela')
						->where('tbl_tabsukarela.deleted','0')
						->where($wheredate)
						->get()
						->row()
						->total_saldo;
	}

	public function sum_tabsukarela_by_branch($branch)
	{
		$today = date('Y-m-d', strtotime('now'));
		$wheredate = "DATE(tbl_tabsukarela.tabsukarela_date) <= "."'".$today."'";
		return $this->db->select('SUM(tabsukarela_saldo) as total_saldo')
						->from('tbl_tabsukarela')
						->join('tbl_clients', 'tbl_clients.client_account = tbl_tabsukarela.tabsukarela_account', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
						->where('tbl_tabsukarela.deleted','0')
						->where($wheredate)
						->where('tbl_clients.client_branch', $branch)
						->where('tbl_clients.client_status', '1')
						->get()
						->row()
						->total_saldo;
	}

	public function sum_tabsukarela_by_branch_until_prevmonth($branch)
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_tabsukarela.tabsukarela_date) <= "."'".$lastday_prevmonth."'";
		return $this->db->select('SUM(tabsukarela_saldo) as total_saldo')
						->from('tbl_tabsukarela')
						->join('tbl_clients', 'tbl_clients.client_account = tbl_tabsukarela.tabsukarela_account', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
						->where('tbl_tabsukarela.deleted','0')
						->where($wheredate)
						->where('tbl_clients.client_branch', $branch)
						->where('tbl_clients.client_status', '1')
						->get()
						->row()
						->total_saldo;
	}

	//TAB BERJANGKA
	//INACCURATTE - ALL CLIENTS BE THEIR STATUS 1 or 0 ARE ACCOUNTED->WRONG
	public function sum_all_outstanding_tabungan_berjangka_until_currmonth()
	{
		$today = date('Y-m-d', strtotime('now'));
		$wheredate = "DATE(tbl_tabberjangka.tabberjangka_date) <= "."'".$today."'";
		return $this->db->select('SUM(tabberjangka_saldo) as total_saldo')
						->from('tbl_tabberjangka')
						->where('tbl_tabberjangka.deleted','0')
						->where($wheredate)
						->get()
						->row()
						->total_saldo;
	}

	//INACCURATTE - ALL CLIENTS BE THEIR STATUS 1 or 0 ARE ACCOUNTED->WRONG
	public function sum_all_outstanding_tabungan_berjangka_until_prevmonth()
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_tabberjangka.tabberjangka_date) <= "."'".$lastday_prevmonth."'";
		return $this->db->select('SUM(tabberjangka_saldo) as total_saldo')
						->from('tbl_tabberjangka')
						->where('tbl_tabberjangka.deleted','0')
						->where($wheredate)
						->get()
						->row()
						->total_saldo;
	}

	public function sum_tabberjangka_by_branch($branch)
	{
		$today = date('Y-m-d', strtotime('now'));
		$wheredate = "DATE(tbl_tabberjangka.tabberjangka_date) <= "."'".$today."'";
		return $this->db->select('SUM(tabberjangka_saldo) as total_saldo')
						->from('tbl_tabberjangka')
						->join('tbl_clients', 'tbl_clients.client_account = tbl_tabberjangka.tabberjangka_account', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
						->where('tbl_tabberjangka.deleted','0')
						->where($wheredate)
						->where('tbl_clients.client_branch', $branch)
						->where('tbl_clients.client_status', '1')
						->get()
						->row()
						->total_saldo;
	}

	public function sum_tabberjangka_by_branch_until_prevmonth($branch)
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_tabberjangka.tabberjangka_date) <= "."'".$lastday_prevmonth."'";
		return $this->db->select('SUM(tabberjangka_saldo) as total_saldo')
						->from('tbl_tabberjangka')
						->join('tbl_clients', 'tbl_clients.client_account = tbl_tabberjangka.tabberjangka_account', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
						->where('tbl_tabberjangka.deleted','0')
						->where($wheredate)
						->where('tbl_clients.client_branch', $branch)
						->where('tbl_clients.client_status', '1')
						->get()
						->row()
						->total_saldo;
	}

	//TAB WAJIB
	//INACCURATTE - ALL CLIENTS BE THEIR STATUS 1 or 0 ARE ACCOUNTED->WRONG
	public function sum_all_outstanding_tabungan_wajib_until_currmonth()
	{
		$today = date('Y-m-d', strtotime('now'));
		$wheredate = "DATE(tbl_tabwajib.tabwajib_date) <= "."'".$today."'";
		return $this->db->select('SUM(tabwajib_saldo) as total_saldo')
						->from('tbl_tabwajib')
						->where('tbl_tabwajib.deleted','0')
						->where($wheredate)
						->get()
						->row()
						->total_saldo;
	}

	//INACCURATTE - ALL CLIENTS BE THEIR STATUS 1 or 0 ARE ACCOUNTED->WRONG
	public function sum_all_outstanding_tabungan_wajib_until_prevmonth()
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_tabwajib.tabwajib_date) <= "."'".$lastday_prevmonth."'";
		return $this->db->select('SUM(tabwajib_saldo) as total_saldo')
						->from('tbl_tabwajib')
						->where('tbl_tabwajib.deleted','0')
						->where($wheredate)
						->get()
						->row()
						->total_saldo;
	}

	public function sum_tabwajib_by_branch($branch)
	{
		$today = date('Y-m-d', strtotime('now'));
		$wheredate = "DATE(tbl_tabwajib.tabwajib_date) <= "."'".$today."'";
		return $this->db->select('SUM(tabwajib_saldo) as total_saldo')
						->from('tbl_tabwajib')
						->join('tbl_clients', 'tbl_clients.client_account = tbl_tabwajib.tabwajib_account', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
						->where('tbl_tabwajib.deleted','0')
						->where($wheredate)
						->where('tbl_clients.client_branch', $branch)
						->where('tbl_clients.client_status', '1')
						->get()
						->row()
						->total_saldo;
	}

	public function sum_tabwajib_by_branch_until_prevmonth($branch)
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_tabwajib.tabwajib_date) <= "."'".$lastday_prevmonth."'";
		return $this->db->select('SUM(tabwajib_saldo) as total_saldo')
						->from('tbl_tabwajib')
						->join('tbl_clients', 'tbl_clients.client_account = tbl_tabwajib.tabwajib_account', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
						->where('tbl_tabwajib.deleted','0')
						->where($wheredate)
						->where('tbl_clients.client_branch', $branch)
						->where('tbl_clients.client_status', '1')
						->get()
						->row()
						->total_saldo;
	}

}