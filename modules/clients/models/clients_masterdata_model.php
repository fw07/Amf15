<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class clients_masterdata_model extends MY_Model {

    protected $table        = 'tbl_clients';
    protected $key          = 'data_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function get_clients($num, $offset)
	{
		//$this->db->join('tbl_group', 'tbl_group.group_id = tbl_masterdata.client_group', 'left');		
		//$this->db->join('tbl_branch', 'tbl_branch.branch_id = tbl_masterdata.client_branch', 'left');	
		return $this->db->get($this->table, $num, $offset);
	}
	
	public function count_anggota()
	{
		return $this->db->count_all('tbl_clients');
		
	}
	
	public function get_anggota($id)
	{
		$this->db->where('data_id', $id);		
		return $this->db->get($this->table);
	}
}