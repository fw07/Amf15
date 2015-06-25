<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Material Model
 * @module	materialmanagement
 * @package	ImagineERP
 * @author 	fikriwirawan
 * @since	24 June 2014
 */
 
class Clients_model extends CI_Model {

    protected $table        = 'tbl_clients';
    protected $key          = 'client_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
		
	public function get_all($limit, $offset, $search='')
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from($this->table)
							->join('tbl_group','tbl_group.group_id= tbl_clients.client_group', 'left')
							->join('tbl_branch','tbl_branch.branch_id = tbl_clients.client_branch', 'left')
							->where('tbl_clients.deleted','0')
							//->where('tbl_clients.client_status','0')
							->like('client_fullname',$search) 
							->or_like('client_account',$search) 
							->limit($limit,$offset)
							->order_by('tbl_clients.client_unreg_date','desc') 
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
							->from($this->table)
							->join('tbl_group','tbl_group.group_id= tbl_clients.client_group', 'left')
							->join('tbl_branch','tbl_branch.branch_id = tbl_clients.client_branch', 'left')
							->where('tbl_clients.deleted','0')
							//->where('tbl_clients.client_status','0')
							->limit($limit,$offset)
							->order_by('tbl_clients.client_unreg_date','desc')
							->get()
							->result();
		}
	}
	
	public function count_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						//->where('tbl_clients.client_status','0')
						->like('client_fullname',$search)
						->or_like('client_account',$search)
						->get()
						->row()
						->numrows;
	}
	function update_status($id,$from_data)
		{
			$this->db->where('client_id',$from_data); //Hanya akan melakukan update sesuai dengan condition yang sudah ditentukan
        	$this->db->update('tbl_clients',$id);

        	if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
 
			return FALSE;
		
		}
	}