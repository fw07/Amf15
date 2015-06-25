<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Alasan Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	20 April 2014
 */
 
class alasan_model extends MY_Model {

    protected $table        = 'tbl_alasankeluar';
    protected $key          = 'alasan_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	
	public function get_all_alasan($param){
		$this->db	->where('deleted', '0');
        return $this->db->get($this->table);    
    }
	
	
	
}