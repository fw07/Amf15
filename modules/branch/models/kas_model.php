<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Kas Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class kas_model extends MY_Model {

    protected $table        = 'tbl_kas';
    protected $key          = 'kas_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	

	public function get_all_kas_branch($limit, $offset, $search='', $branch)
	{
		return $this->db->select('*')
							->from('tbl_kas')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_kas.kas_branch', 'left')
							->where('tbl_kas.kas_branch',$branch)
							->where('tbl_kas.deleted','0')
							->limit($limit,$offset)
							->order_by('kas_id','desc')
							->get()
							->result();
	}
	
	public function get_all_kas($limit, $offset, $search='')
	{
		return $this->db->select('*')
							->from('tbl_kas')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_kas.kas_branch', 'left')
							->where('tbl_kas.deleted','0')
							->limit($limit,$offset)
							->order_by('kas_id','desc')
							->get()
							->result();
	}
	
	public function count_all_kas_branch($search,$branch)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('kas_branch',$branch)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	public function count_all_kas($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
}