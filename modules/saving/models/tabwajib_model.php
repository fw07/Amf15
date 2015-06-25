<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Tabungan Wajib Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 January 2013
 */
 
class tabwajib_model extends MY_Model {

    protected $table        = 'tbl_tabwajib';
    protected $key          = 'tabwajib_account';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
		
	
	public function get_all_clients($limit, $offset, $search='', $key='',$user_branch)
	{
		if($search != '')
		{
			if($key != 'group')
			{
				return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_account = tbl_clients.client_account', 'left')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_branch',$user_branch) 
						->like("client_$key",$search)
						->limit($limit,$offset)
						->order_by('client_id','DESC')
						->get()
						->result();
			}else{	
				return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_account = tbl_clients.client_account', 'left')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_branch',$user_branch) 
						->like("group_name",$search)
						->limit($limit,$offset)
						->order_by('client_id','DESC')
						->get()
						->result();			
			}
		}else{		
			return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_account = tbl_clients.client_account', 'left')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_branch',$user_branch) 
						->limit($limit,$offset)
						->order_by('client_id','DESC')
						->get()
						->result();
		}
	}
	
	public function count_all($search,$user_branch)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->where('deleted','0')
						->where('tbl_clients.client_branch',$user_branch)
						->like('client_fullname',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function get_transaction($id, $limit, $offset, $search='')
	{
		return $this ->db->select("*")
					->from('tbl_tr_tabwajib')	
					->where('tr_account', $id)
					->where('deleted', '0')	
					->limit($limit,$offset)
					->order_by('tr_id', 'desc')
					->get()
					->result();
		
		//return $this->db->get('tbl_tr_tabwajib');
	}
	
	public function count_all_transaction($id)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_tr_tabwajib')
						->where('tr_account', $id)
					->where('deleted', '0')		
						->get()
						->row()
						->numrows;
	}
	
	public function get_account($id)
	{
		return $this ->db->select("*")
					->from('tbl_tabwajib')	
					->join('tbl_clients', 'tbl_clients.client_account = tbl_tabwajib.tabwajib_account', 'left')
					->where('tabwajib_account', $id)	
					->get()
					->result();
		
		//return $this->db->get('tbl_tr_tabwajib');
	}
}