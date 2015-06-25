<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Group Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class group_model extends MY_Model {

    protected $table        = 'tbl_group';
    protected $key          = 'group_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
		
	public function get_new_group($branch,$startdate,$enddate)
	{
		$this->db	->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
					->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
					->where('tbl_group.group_date >','2014-01-01')
					->where('tbl_group.group_date <','2014-01-06')
					->where('tbl_group.deleted','0');
        return $this->db->get($this->table); 
	}
	
	public function count_clients_on_group($search)
	{
		return $this->db->select("count(*) as numrows")
						->from("tbl_clients")
						->where('client_group',$search)
						->where('deleted','0')
						->where('client_status','1')
						->get()
						->row()
						->numrows;
	}
	
	
}