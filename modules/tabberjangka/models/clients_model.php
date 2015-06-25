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
						//->where('deleted', '0')
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
	
	public function get_all_clients_by_branch($limit, $offset, $search='', $key='',$branch)
	{
		if($search != '')
		{
			return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
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
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->limit($limit,$offset)
						->order_by('client_id','DESC')
						->get()
						->result();
		}
	}
	
	public function count_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_clients.deleted','0')
						->like('client_fullname',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function count_all_by_branch($search,$branch)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->like('client_fullname',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	
	public function get_where_data($table,$where)
	{
			$query=$this->db->get_where($table,$where);
			return $query->result_array();
	}
	
	public function update_pembiayaan($id, $data){
        $this->db->where('client_id', $id);
        $this->db->update($this->table, $data);
    }
	
	public function get_unregclient_where_data($table,$where)
	{
			$query=$this->db->get_where($table,$where);
			return $query->result_array();
	}
	
	
	public function get_client_detail($id)
	{
		$this->db->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left');
		$this->db->join('tbl_officer', 'tbl_officer.officer_id = tbl_clients.client_officer', 'left');
		$this->db->where('tbl_clients.client_id', $id);		
		$this->db->where('tbl_clients.deleted', 0);		
		$this->db->where('tbl_clients.client_status', 1);	
		return $this->db->get($this->table);
	} 
	
	
	public function get_all_unregclients_by_branch($limit, $offset, $search='', $key='',$branch)
	{
		if($search != '')
		{
			return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_alasankeluar', 'tbl_alasankeluar.alasan_id = tbl_clients.client_reason', 'left')
						->join('tbl_officer', 'tbl_officer.officer_id = tbl_clients.client_pewawancara', 'left')
						->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_account = tbl_clients.client_account', 'left')
						->join('tbl_tabsukarela', 'tbl_tabsukarela.tabsukarela_account = tbl_clients.client_account', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','0') 
						->like("client_$key",$search)
						->limit($limit,$offset)
						->order_by('client_unreg_date','DESC')
						->get()
						->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_alasankeluar', 'tbl_alasankeluar.alasan_id = tbl_clients.client_reason', 'left')
						->join('tbl_officer', 'tbl_officer.officer_id = tbl_clients.client_pewawancara', 'left')
						->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_account = tbl_clients.client_account', 'left')
						->join('tbl_tabsukarela', 'tbl_tabsukarela.tabsukarela_account = tbl_clients.client_account', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','0')
						->limit($limit,$offset)
						->order_by('client_unreg_date','DESC')
						->get()
						->result();
		}
	}
	
	public function get_all_unregclients($limit, $offset, $search='', $key='')
	{
		if($search != '')
		{
			return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_alasankeluar', 'tbl_alasankeluar.alasan_id = tbl_clients.client_reason', 'left')
						->join('tbl_officer', 'tbl_officer.officer_id = tbl_clients.client_pewawancara', 'left')
						->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_account = tbl_clients.client_account', 'left')
						->join('tbl_tabsukarela', 'tbl_tabsukarela.tabsukarela_account = tbl_clients.client_account', 'left')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','0')
						->like("client_$key",$search)
						->limit($limit,$offset)
						->order_by('client_unreg_date','DESC')
						->get()
						->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_alasankeluar', 'tbl_alasankeluar.alasan_id = tbl_clients.client_reason', 'left')
						->join('tbl_officer', 'tbl_officer.officer_id = tbl_clients.client_pewawancara', 'left')
						->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_account = tbl_clients.client_account', 'left')
						->join('tbl_tabsukarela', 'tbl_tabsukarela.tabsukarela_account = tbl_clients.client_account', 'left')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','0')
						->limit($limit,$offset)
						->order_by('client_unreg_date','DESC')
						->get()
						->result();
		}
	}
	
	public function count_all_unreg($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','0')
						->like('client_fullname',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function count_all_unreg_by_branch($search,$branch)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','0')
						->like('client_fullname',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	
}