<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Sector Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class sector_model extends MY_Model {

    protected $table        = 'tbl_sector';
    protected $key          = 'sector_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }  
	
	public function get_all(){
		$this->db->where('deleted', '0')
				 ->order_by('sector_name', 'ASC');
        return $this->db->get($this->table);    
    }
	
	
	
}