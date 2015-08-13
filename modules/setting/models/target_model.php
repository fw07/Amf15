<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Target Model
 * 
 * @package	amartha
 * @author 	afahmi
 * @since	11 August 2013
 */
 
class target_model extends MY_Model {

    protected $table        = 'tbl_target';
    protected $key          = 'target_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }  
	
	public function get_all(){
		$this->db->where('deleted', '0')
				 ->order_by('target_category', 'ASC');
        return $this->db->get($this->table)->result();    
    }

    public function get_target_by_id($tid){
		$this->db->where('target_id', $tid)
				 ->where('deleted', '0');
        return $this->db->get($this->table)->result_array();    
    }
	
	public function get_target_by_category($param){
		$this->db->where('deleted', '0')
				 ->where('target_category', $param);
        return $this->db->get($this->table)->result();    
    }

    public function get_target_by_officer($param){
		$this->db->where('deleted', '0')
				 ->where('target_officer', $param);
        return $this->db->get($this->table)->result();    
    }

    public function get_target_by_branch($param){
		$this->db->where('deleted', '0')
				 ->where('target_branch', $param);
        return $this->db->get($this->table)->result();    
    }
	
	public function count_all_target($search='')
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						//->like('area_name',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function get_some_target_per_officer($limit, $offset, $key='', $search_officer='', $search_branch='')
	{
		if($key == 'o')
		{
			return $this->db->select('target_id, target_category, target_item, target_amount, target_bydate, target_remarks, target_officer, target_branch, officer_name, branch_name')
							->from($this->table)
							->join('tbl_officer', 'tbl_officer.officer_id = tbl_target.target_officer', 'left')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_target.target_branch', 'left')
							->where('tbl_target.deleted','0')
							->like('target_officer', $search_officer)
							->limit($limit,$offset)
							->order_by('target_category','ASC')
							->get()
							->result();
		}else if($key == 'b')
		{
			return $this->db->select('target_id, target_category, target_item, target_amount, target_bydate, target_remarks, officer_name, branch_name')
							->from($this->table)
							->where('tbl_target.deleted','0')
							->like('target_branch', $search_branch)
							->limit($limit,$offset)
							->order_by('target_category','ASC')
							->get()
							->result();
		}else
		{		
			return $this->db->select('target_id, target_category, target_item, target_amount, target_bydate, target_remarks, officer_name, branch_name')
							->from($this->table)
							->join('tbl_officer', 'tbl_officer.officer_id = tbl_target.target_officer', 'left')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_target.target_branch', 'left')
							->where('tbl_target.deleted','0')
							->limit($limit,$offset)
							->order_by('target_category','ASC')
							->get()
							->result();
		}
	}
	
	
}