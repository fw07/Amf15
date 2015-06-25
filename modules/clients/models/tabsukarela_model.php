<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Tabungan Sukarela Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 January 2013
 */
 
class tabsukarela_model extends MY_Model {

    protected $table        = 'tbl_tabsukarela';
    protected $key          = 'tabsukarela_account';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    } 
	
	public function get_account($id)
	{
		return $this ->db->select("*")
					->from('tbl_tabsukarela')	
					->join('tbl_clients', 'tbl_clients.client_account = tbl_tabsukarela.tabsukarela_account', 'left')
					->where('tabsukarela_account', $id)	
					->get()
					->result();
		
		//return $this->db->get('tbl_tr_tabwajib');
	}
	
	
	public function avg_tabsukarela($search)
	{
		return $this->db->select("avg(tr_tabsukarela_debet) as numrows")
						->from("tbl_transaction")
						->where('tr_pembiayaan',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
}