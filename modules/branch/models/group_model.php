<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class group_model extends MY_Model {

    protected $table        = 'tbl_group';
    protected $key          = 'group_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function get_group($param){
		$this->db	->join('tbl_area', 'tbl_area.area_id = tbl_group.group_area', 'left')
					->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
					->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
					->where('group_id', $param)
					->order_by('group_name', 'asc');
        return $this->db->get($this->table);    
    }
	
	public function get_group_by_branch($id)
	{
		return $this->db->where('group_branch', $id)
					->order_by('group_name', 'asc')
					->limit(1)
					->get('tbl_group')
					->result();
	}

	public function get_last_group_by_branch($id)
	{
		return $this->db->where('group_branch', $id)
					->order_by('group_id', 'desc')
					->limit(1)
					->get('tbl_group')
					->result();
	}
	
	public function get_all_group_branch($limit, $offset, $search='', $branch)
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_group')
							->join('tbl_area', 'tbl_area.area_id = tbl_group.group_area', 'left')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
							->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
							->where('tbl_group.group_branch',$branch)
							->where('tbl_group.deleted','0')
							->like('group_name',$search)
							//->limit($limit,$offset)
							->order_by('group_name','asc')
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_group')
						->join('tbl_area', 'tbl_area.area_id = tbl_group.group_area', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
						->where('tbl_group.group_branch',$branch)
						->where('tbl_group.deleted','0')
						//->limit($limit,$offset)
						->order_by('group_name','asc')
						->get()
						->result();
		}
	}
	
	public function get_all_group($limit, $offset, $search='')
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_group')
							->join('tbl_area', 'tbl_area.area_id = tbl_group.group_area', 'left')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
							->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
							->where('tbl_group.deleted','0')
							->like('group_name',$search)
							//->limit($limit,$offset)
							->order_by('group_name','asc')
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_group')
						->join('tbl_area', 'tbl_area.area_id = tbl_group.group_area', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
						->where('tbl_group.deleted','0')
						//->limit($limit,$offset)
						->order_by('group_name','asc')
						->get()
						->result();
		}
	}
	
	public function count_all_group_branch($search,$branch)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('group_branch',$branch)
						->where('deleted','0')
						->like('group_name',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	public function count_all_group($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->like('group_name',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function count_group($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('group_branch',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
	public function get_list_group_by_branch($branch){
		$this->db	->where('group_branch', $branch)
					->where('deleted','0')
					->order_by('group_name','asc');
        return $this->db->get($this->table);    
    }
	
	public function get_list_group()
	{
		$this->db	->where('deleted','0')
					->order_by('group_id','desc');
        return $this->db->get($this->table); 
	}
	
	public function count_clients_on_group($search)
	{
		return $this->db->select("count(*) as numrows")
						->from("tbl_clients")
						->where('client_group',$search)
						->where('deleted','0')
						->where('client_status','1')
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_h($search)
	{
		$date_start = date('Y-m-d', strtotime('today - 30 days'));
		$date_end = date("Y-m-d");
		return $this->db->select("sum(tr_absen_h) as numrows")
						->from("tbl_transaction")
						->where('tr_group',$search)
						->where('deleted','0')
						->where("tr_date >= '".$date_start."'")
						->where("tr_date <= '".$date_end."'")
						->get()
						->row()
						->numrows;
	}
	
	public function count_clients_absensi_not_h($search)
	{
		$date_start = date('Y-m-d', strtotime('today - 30 days'));
		$date_end = date("Y-m-d");
		return $this->db->select("sum(tr_absen_s + tr_absen_c + tr_absen_i + tr_absen_a) as numrows")
						->from("tbl_transaction")
						->where('tr_group',$search)
						->where('deleted','0')
						->where("tr_date >= '".$date_start."'")
						->where("tr_date <= '".$date_end."'")
						->get()
						->row()
						->numrows;
	}
	
	public function count_clients_absensi_a($search)
	{
		$date_start = date('Y-m-d', strtotime('today - 30 days'));
		$date_end = date("Y-m-d");
		return $this->db->select("sum(tr_absen_a) as numrows")
						->from("tbl_transaction")
						->where('tr_group',$search)
						->where('deleted','0')
						->where("tr_date >= '".$date_start."'")
						->where("tr_date <= '".$date_end."'")
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_s($search)
	{
		$date_start = date('Y-m-d', strtotime('today - 30 days'));
		$date_end = date("Y-m-d");
		return $this->db->select("sum(tr_absen_s) as numrows")
						->from("tbl_transaction")
						->where('tr_group',$search)
						->where('deleted','0')
						->where("tr_date >= '".$date_start."'")
						->where("tr_date <= '".$date_end."'")
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_i($search)
	{
		$date_start = date('Y-m-d', strtotime('today - 30 days'));
		$date_end = date("Y-m-d");
		return $this->db->select("sum(tr_absen_i) as numrows")
						->from("tbl_transaction")
						->where('tr_group',$search)
						->where('deleted','0')
						->where("tr_date >= '".$date_start."'")
						->where("tr_date <= '".$date_end."'")
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_c($search)
	{
		$date_start = date('Y-m-d', strtotime('today - 30 days'));
		$date_end = date("Y-m-d");
		return $this->db->select("sum(tr_absen_c) as numrows")
						->from("tbl_transaction")
						->where('tr_group',$search)
						->where('deleted','0')
						->where("tr_date >= '".$date_start."'")
						->where("tr_date <= '".$date_end."'")
						->get()
						->row()
						->numrows;
	}
	
	public function count_clients_absensi_h_by_date($group_id,$date_start,$date_end)
	{
		$date_start = date('Y-m-d', strtotime('today - 30 days'));
		$date_end = date("Y-m-d");
		return $this->db->select("sum(tr_absen_h) as numrows")
						->from("tbl_transaction")
						->where('tr_group',$group_id)
						->where('deleted','0')
						->where("tr_date >= '".$date_start."'")
						->where("tr_date <= '".$date_end."'")
						->get()
						->row()
						->numrows;
	}
	
	public function count_clients_absensi_not_h_by_date($group_id,$date_start,$date_end)
	{
		return $this->db->select("sum(tr_absen_s + tr_absen_c + tr_absen_i + tr_absen_a) as numrows")
						->from("tbl_transaction")
						->where('tr_group',$group_id)
						->where('deleted','0')
						->where("tr_date >= '".$date_start."'")
						->where("tr_date <= '".$date_end."'")
						->get()
						->row()
						->numrows;
	}
	
}