<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Report Review Model
 * 
 * @package	amartha
 * @author 	afahmi
 * @since	9 July 2015
 */
 
class review_model extends MY_Model {

	//COUNT GROUPS (MAJELIS)
	public function count_all_majelis()
	{
		return $this->db->query("SELECT * FROM  tbl_group WHERE deleted='0'")->num_rows();
	}

	public function count_all_majelis_until_prevmonth()
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_group.group_date) <= "."'".$lastday_prevmonth."'";
		return $this->db->query("SELECT * FROM  tbl_group WHERE deleted='0' and ".$wheredate." ")->num_rows();
	}

	public function count_majelis_by_branch($branch)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_group')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_group.deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	public function count_majelis_by_branch_by_currmonth($branch)
	{
		//$currmonth = date('m'); 
		//$curryear  = date('Y');
		return $this->db->select("count(*) as numrows")
						->from('tbl_group')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
		//				->where(MONTH('group_date'), $currmonth)
		//				->where(YEAR('group_date'), $curryear)
						->where('tbl_group.deleted','0')
						->get()
						->row()
						->numrows;
	}

	public function count_majelis_by_branch_until_currmonth($branch)
	{
		$today = date('Y-m-d');
		return $this->db->select("count(*) as numrows")
						->from('tbl_group')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_group.group_date <= "'.$today.'"')
						->where('tbl_group.deleted','0')
						->get()
						->row()
						->numrows;
	}

	public function count_majelis_by_branch_until_prevmonth($branch)
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_group.group_date) <= "."'".$lastday_prevmonth."'";
		return $this->db->select("count(*) as numrows")
						->from('tbl_group')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where($wheredate)
						->where('tbl_group.deleted','0')
						->get()
						->row()
						->numrows;
	}

	//COUNT ANGGOTA
	public function count_all_clients()
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','1')
						->get()
						->row()
						->numrows;
	}

	public function count_all_clients_until_prevmonth()
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_clients.client_reg_date) <= "."'".$lastday_prevmonth."'";
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','1')
						->where($wheredate)
						->get()
						->row()
						->numrows;
	}

	public function count_clients_by_branch($branch)
	{	
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','1')
						->get()
						->row()
						->numrows;
	}

	public function count_clients_by_branch_until_currmonth($branch)
	{
		$today = date('Y-m-d', strtotime('now'));
		$wheredate = "DATE(tbl_clients.client_reg_date) <= "."'".$today."'";
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','1')
						->where($wheredate)
						->get()
						->row()
						->numrows;
	}

	public function count_clients_by_branch_until_prevmonth($branch)
	{
		$lastday_prevmonth = date('Y-m-d', strtotime('last day of previous month'));
		$wheredate = "DATE(tbl_clients.client_reg_date) <= "."'".$lastday_prevmonth."'";
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','1')
						->where($wheredate)
						->get()
						->row()
						->numrows;
	}

	//COUNT CABANG
	public function count_all_cabang()
	{
		return $this->db->query("SELECT * FROM  tbl_branch WHERE deleted='0'")->num_rows();
	}

	public function list_cabang(){
		return $this->db->select('branch_id, branch_name')->from('tbl_branch')
						->where('deleted', '0')->get()->result_array();
	}

	//COUNT OFFICERS
	public function count_all_officer()
	{
		return $this->db->query("SELECT * FROM  tbl_officer WHERE deleted='0'")->num_rows(); 
	}

	//COUNT OFFICERS
	public function count_all_officer_by_branch($branch)
	{
		return $this->db
					->query("SELECT COUNT(tbl_officer.officer_name) AS no_officer_in_branch FROM  tbl_officer LEFT JOIN tbl_branch ON tbl_branch.branch_id = tbl_officer.officer_branch 
							 WHERE tbl_officer.deleted='0' AND tbl_officer.officer_branch = '".$branch."'")->row()->no_officer_in_branch; 
	}

}