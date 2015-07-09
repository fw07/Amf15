<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class clients_model extends MY_Model {

    protected $table        = 'tbl_clients';
    protected $key          = 'client_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function get_clients()
	{
		$this->db->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left');		
		$this->db->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left');	
		return $this->db->get($this->table);
	}
	
	public function get_client($id)
	{
		$this->db->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left');		
		$this->db->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left');		
		$this->db->join('tbl_area', 'tbl_area.area_id = tbl_group.group_area', 'left');	
		$this->db->join('tbl_officer', 'tbl_officer.officer_id = tbl_clients.client_officer', 'left');
		$this->db->where('client_id', $id);		
		return $this->db->get($this->table);
	}
	
	public function get_client_by_group($id)
	{
		return $this->db->select_max('client_account')
					->where('client_group', $id)				
					->get('tbl_clients')
					->result();
	}
	
	public function get_clientmax_by_group($id)
	{
		return $this->db->where('client_group', $id)
						->order_by('client_no', 'desc')
						->limit(1)
						->get('tbl_clients')
						->result();
	}
	
	public function get_all_clients($limit, $offset, $search='', $key='')
	{
		if($search != '')
		{
			return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_clients.deleted','0')
						->like("client_$key",$search)
						->limit($limit,$offset)
						->order_by('client_id','DESC')
						->get()
						->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_clients.deleted','0')
						->limit($limit,$offset)
						->order_by('client_id','DESC')
						->get()
						->result();
		}
	}

	public function get_all_investors_list()
	{
		return $this->db->select('lender_id, lender_code, lender_name')
						->from('tbl_lenders')
						->where('tbl_lenders.deleted','0')
						->order_by('lender_id','ASC')
						->get()
						->result();
	}
	
	public function count_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->like('client_fullname',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
}