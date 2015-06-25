<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * PYD Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	19 October 2014
 */
 
class regpyd_model extends MY_Model {

    protected $table        = 'tbl_clients';
    protected $key          = 'client_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function get_all($limit, $offset,$branch)
	{
		return $this->db->select('client_name')
						->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id', 'left')
						->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_client = tbl_clients.client_id', 'left')
						->join('tbl_tabsukarela', 'tbl_tabsukarela.tabsukarela_client = tbl_clients.client_id', 'left')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')						
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_sector', 'tbl_sector.sector_id = tbl_pembiayaan.data_sector', 'left')						
						->where('tbl_clients.client_status', '1')
						->where('tbl_clients.deleted', '0')
						->like('client_branch', $branch)
						->order_by('group_name','ASC')
						->order_by('client_account','ASC')
						->limit($limit,$offset)
						->get('tbl_clients')
						->result();
	}
	
}