<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Risk Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	16 March 2014
 */
 
class risk_model extends MY_Model {

    protected $table        = 'tbl_risk';
    protected $key          = 'risk_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	
}