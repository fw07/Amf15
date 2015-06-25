<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class area_model extends MY_Model {

    protected $table        = 'tbl_area';
    protected $key          = 'area_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }  
	
	public function get_all(){
		$this->db->where('deleted', '0')
				 ->order_by('area_name', 'ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_all_by_branch($branch){
		$this->db	->join('tbl_branch', 'tbl_branch.branch_area = tbl_area.area_id', 'left')
					->where('tbl_area.deleted', '0')
					->where('tbl_branch.branch_id', $branch)
					->order_by('area_name', 'ASC');
        return $this->db->get($this->table);    
    }
	
	
	public function get_area($param){
		$this->db->where('area_id', $param);
        return $this->db->get($this->table);    
    }
	
	
	
}