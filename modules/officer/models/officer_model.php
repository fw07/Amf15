<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Officer Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class officer_model extends MY_Model {

    protected $table        = 'tbl_officer';
    protected $key          = 'officer_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	
	public function get_officer($param){
		$this->db	->join('tbl_branch', 'tbl_branch.branch_id = tbl_officer.officer_branch', 'left')
					->join('tbl_area', 'tbl_area.area_id = tbl_branch.branch_area', 'left')
					->where('officer_id', $param);
        return $this->db->get($this->table);    
    }
	
	
	public function get_all_officer($limit, $offset, $search='')
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_officer')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_officer.officer_branch', 'left')
							->join('tbl_area', 'tbl_area.area_id = tbl_branch.branch_area', 'left')
							->where('tbl_officer.deleted','0')
							->order_by('officer_name','asc')
							->like('officer_name',$search)
							->limit($limit,$offset)
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_officer')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_officer.officer_branch', 'left')
						->join('tbl_area', 'tbl_area.area_id = tbl_branch.branch_area', 'left')
						->where('tbl_officer.deleted','0')
						->order_by('officer_name','asc')
						->limit($limit,$offset)
						->get()
						->result();
		}
	}
	
	
	public function get_all_officer_by_branch($limit, $offset, $search='', $branch)
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_officer')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_officer.officer_branch', 'left')
							->join('tbl_area', 'tbl_area.area_id = tbl_branch.branch_area', 'left')
							->where('tbl_officer.officer_branch',$branch)
							->where('tbl_officer.deleted','0')
							->like('officer_name',$search)
							->order_by('officer_name','asc')
							->limit($limit,$offset)
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_officer')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_officer.officer_branch', 'left')
						->join('tbl_area', 'tbl_area.area_id = tbl_branch.branch_area', 'left')
						->where('tbl_officer.officer_branch',$branch)
						->where('tbl_officer.deleted','0')
							->order_by('officer_name','asc')
						->limit($limit,$offset)
						->get()
						->result();
		}
	}
	
	public function count_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->like('officer_name',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function count_all_by_branch($search, $branch)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('officer_branch',$branch)
						->where('deleted','0')
						->like('officer_name',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
}