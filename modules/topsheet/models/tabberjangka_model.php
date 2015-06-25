<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Tabungan Berjangka Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	08 December 2014
 */
 
class tabberjangka_model extends MY_Model {

    protected $table        = 'tbl_tabberjangka';
    protected $key          = 'tabberjangka_account';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
		
	
	public function get_saving_by_account($param)
	{
		$this->db	->where('tabberjangka_account', $param)
					->where('deleted', '0')
					->order_by('tabberjangka_id', 'desc')
					->limit(1);
        return $this->db->get($this->table);    
	}
	
	public function insert_saving($data){
        //$this->db->where('user_id', $user_id);
        $this->db->insert($this->table, $data);
    }  
	
	public function get_account($id)
	{
		return $this ->db->select("*")
					->from('tbl_tabberjangka')	
					->join('tbl_clients', 'tbl_clients.client_account = tbl_tabberjangka.tabberjangka_account', 'left')
					->where('tabberjangka_account', $id)	
					->get()
					->result();
		
	}
	
	public function get_tabberjangka($param)
	{
		return $this->db->select('*')
					->where('tabberjangka_account', $param)
					->where('deleted', '0')		
					->get('tbl_tabberjangka')
					->result();
	}
}