<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Tab Sukarela Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	22 January 2013
 */
 
class tr_tabsukarela_model extends MY_Model {

    protected $table        = 'tbl_tr_tabsukarela';
    protected $key          = 'tr_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
		
	
	
}