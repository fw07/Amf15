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
	
		
	
	public function get_saving_by_account($param)
	{
		$this->db	->where('tabwajib_account', $param)
					->where('deleted', '0')
					->order_by('tabwajib_id', 'desc')
					->limit(1);
        return $this->db->get($this->table);    
	}
	
	public function insert_saving($data){
        //$this->db->where('user_id', $user_id);
        $this->db->insert($this->table, $data);
    }  
	
	public function get_tabwajib($param)
	{
		return $this->db->select('*')
					->where('tabwajib_account', $param)
					->where('deleted', '0')		
					->get('tbl_tabwajib')
					->result();
	}
	
}