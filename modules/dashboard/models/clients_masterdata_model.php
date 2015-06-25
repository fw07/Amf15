<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class clients_masterdata_model extends MY_Model {

    protected $table        = 'tbl_masterdata';
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
		return $this->db->count_all('tbl_masterdata');		
	}
	
	public function count_majelis()
	{
		$query= $this->db->query('SELECT * FROM  `tbl_group`');
		return $query->num_rows();  
	}
	
	public function count_cabang()
	{
		$query= $this->db->query('SELECT * FROM  `tbl_branch`');
		return $query->num_rows();  
	}
	
	public function count_tpl()
	{
		$query= $this->db->query('SELECT DISTINCT (data_tpl) FROM  `tbl_masterdata`');
		return $query->num_rows();  
	}
}