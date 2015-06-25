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