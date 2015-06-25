<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Branch Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class branch_model extends MY_Model {

    protected $table        = 'tbl_branch';
    protected $key          = 'branch_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	
	
	public function get_all(){
		$this->db->where('deleted', '0')
				 ->order_by('branch_name', 'ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_branch($param){
		        return $this->db->select("*")
								->from('tbl_branch')	
								->where('tbl_branch.deleted', '0')
								->where('tbl_branch.branch_id', $param)
								->get()
								->result();
					   
    }
	
}