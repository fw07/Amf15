<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Group Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class users_model extends MY_Model {

    protected $table        = 'tbl_user';
    protected $key          = 'user_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function get_user($param){
		$this->db	->join('tbl_branch', 'tbl_branch.branch_id = tbl_user.user_branch', 'left')
					->where('user_id', $param);
        return $this->db->get($this->table);    
    }
	
	public function get_group_by_branch($id)
	{
		return $this->db->where('group_branch', $id)
					->order_by('group_id', 'desc')
					->limit(1)
					->get('tbl_group')
					->result();
	}

	
	public function get_all_user($limit, $offset, $search='')
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_user')
							->join('tbl_branch', 'tbl_branch.branch_id =  tbl_user.user_branch', 'left')
							->where('tbl_user.deleted','0')
							->like('fullname',$search)
							->limit($limit,$offset)
							->order_by('username','asc')
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
							->from('tbl_user')							
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_user.user_branch', 'left')
							->where('tbl_user.deleted','0')
							->limit($limit,$offset)
							->order_by('username','asc')
							->get()
							->result();
		}
	}
	
	public function count_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->like('fullname',$search)
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
}