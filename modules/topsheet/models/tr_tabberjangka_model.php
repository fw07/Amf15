<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Tab Bejangka Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	08 December 2014
 */
 
class tr_tabberjangka_model extends MY_Model {

    protected $table        = 'tbl_tr_tabberjangka';
    protected $key          = 'tr_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
		
	
	
}