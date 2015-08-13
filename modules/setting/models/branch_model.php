<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
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

    public function get_all_branch(){
    	return $this->db->select('branch_id, branch_name')
    				->get($this->table)->result();
    } 
	
	public function get_branch($param){
		$this->db	->join('tbl_area', 'tbl_area.area_id = tbl_branch.branch_area', 'left')
					->where('branch_id', $param);
        return $this->db->get($this->table);    
    }
	
	public function count_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->like('branch_name',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function get_all($limit, $offset, $search='')
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_branch')
							->join('tbl_area', 'tbl_area.area_id =  tbl_branch.branch_area', 'left')
							->where('tbl_branch.deleted','0')
							->like('branch_name',$search)
							->limit($limit,$offset)
							->order_by('branch_number','asc')
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
							->from('tbl_branch')
							->join('tbl_area', 'tbl_area.area_id =  tbl_branch.branch_area', 'left')
							->where('tbl_branch.deleted','0')
							->limit($limit,$offset)
							->order_by('branch_number','asc')
							->get()
							->result();
		}
	}
	
	
}