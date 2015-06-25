<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
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
	
		
	public function get_group($param){
		$this->db	->join('tbl_area', 'tbl_area.area_id = tbl_group.group_area', 'left')
					->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
					->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
					->where('group_id', $param);
        return $this->db->get($this->table);    
    }
	
	public function get_group_by_branch($id)
	{
		return $this->db->select_max('group_number')
					->where('group_branch', $id)				
					->get('tbl_group')
					->result();
	}
	
	public function get_all_group($limit, $offset, $search='')
	{
		return $this->db->select('*')
							->from('tbl_group')
							->join('tbl_area', 'tbl_area.area_id = tbl_group.group_area', 'left')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
							->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
							->where('tbl_group.deleted','0')							
							//->limit($limit,$offset)
							->order_by('group_name','asc')
							->get()
							->result();
	}
	
	public function get_all_group_by_branch($limit, $offset, $search='', $branch)
	{
		return $this->db->select('*')
							->from('tbl_group')
							->join('tbl_area', 'tbl_area.area_id = tbl_group.group_area', 'left')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
							->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
							->where('tbl_group.deleted','0')
							->where('tbl_group.group_branch',$branch)							
							//->limit($limit,$offset)
							->order_by('group_name','asc')
							->get()
							->result();
	}
	
	public function count_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->like('group_name',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function count_all_by_branch($search,$branch)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('group_branch',$branch)
						->where('deleted','0')
						->like('group_name',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function get_schedule($branch='', $day='')
	{
		return $this->db->select('*')
							->from('tbl_group')
							->join('tbl_area', 'tbl_area.area_id = tbl_group.group_area', 'left')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
							->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
							->where('tbl_group.deleted','0')
							->like('tbl_group.group_branch',$branch)
							->like('tbl_group.group_schedule_day',$day)							
							//->limit($limit,$offset)
							->order_by('group_schedule_time','asc')
							->get()
							->result();
	}
}