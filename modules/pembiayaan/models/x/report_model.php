<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Report Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 January 2013
 */
 
class report_model extends MY_Model {

    protected $table        = 'tbl_report';
    protected $key          = 'branch_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	
	
	public function get_all_report($limit, $offset, $search='',$branch)
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_report')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_report.report_branch', 'left')
							->where('tbl_report.deleted','0')
							->like('report_branch',$search)
							->limit($limit,$offset)
							->order_by('report_id','desc')
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_report')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_report.report_branch', 'left')
						->where('tbl_report.deleted','0')
						->limit($limit,$offset)
						->order_by('report_id','desc')
						->get()
						->result();
		}
	}
	
	public function get_branch_report($limit, $offset, $search='',$branch)
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_report')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_report.report_branch', 'left')
							->where('tbl_report.deleted','0')
							->where('tbl_report.report_branch',$branch)
							->like('report_branch',$search)
							->limit($limit,$offset)
							->order_by('report_id','desc')
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_report')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_report.report_branch', 'left')
						->where('tbl_report.deleted','0')
						->where('tbl_report.report_branch',$branch)
						->limit($limit,$offset)
						->order_by('report_id','desc')
						->get()
						->result();
		}
	}
}