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
}