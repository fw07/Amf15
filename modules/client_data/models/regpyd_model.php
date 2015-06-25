<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Regpyd Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class regpyd_model extends MY_Model {

    protected $table        = 'tbl_officer';
    protected $key          = 'officer_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
		
	public function get_all_data($limit, $offset, $search='')
	{
		return $this->db->select('*')
						->from('view_regpyd')
						->like('Cabang',$search)
						->or_like('Majelis',$search)
						->or_like('Nomor_Rekening',$search)
						->or_like('Nama',$search)
						->order_by('Cabang','asc')
						->order_by('Majelis','asc')
						->order_by('Nomor_Rekening','asc')
						->order_by('Nama','asc')
						//->limit(30,0)
						->get()
						->result();
	}
	
	
	public function count_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from('view_regpyd')
						->like('Cabang',$search)
						->or_like('Majelis',$search)
						->or_like('Nomor_Rekening',$search)
						->or_like('Nama',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function get_all_clients()
	{
		return $this->db->select('*')
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','1')
						->order_by('branch_name','ASC')
						->order_by('group_name','ASC')
						->order_by('client_fullname','ASC')
						->order_by('client_account','ASC')
						->get()
						->result();
	}
	public function get_pembiayaan_detail($id)
	{
		$this->db->join('tbl_sector', 'tbl_sector.sector_id = tbl_pembiayaan.data_sector', 'left')
				->where('data_id', $id);		
		return $this->db->get('tbl_pembiayaan');
	}
	
	public function get_tab_wajib($id)
	{
		$this->db->where('tabwajib_account', $id)
				 ->where('deleted', '0');		
		return $this->db->get('tbl_tabwajib');
	}
	
	
	public function get_tab_sukarela($id)
	{
		$this->db->where('tabsukarela_account', $id)
				 ->where('deleted', '0');		
		return $this->db->get('tbl_tabsukarela');
	}
	
	
	public function get_tab_berjangka($id)
	{
		$this->db->where('tabberjangka_account', $id)
				 ->where('deleted', '0');		
		return $this->db->get('tbl_tabberjangka');
	}
}