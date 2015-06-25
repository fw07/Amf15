<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * League Table Model
 * 
 * @package	bni4id
 * @author 	andriansandi
 * @since	17 December 2012
 */
class leaguetable_model extends MY_Model {

    protected $table        = 'tables';
    protected $key          = 'id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct(){
        parent::__construct();
    }
    
}