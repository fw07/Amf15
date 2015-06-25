<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class tsdaily_model extends MY_Model {

    protected $table        = 'tbl_tsdaily';
    protected $key          = 'tsdaily_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
		
	
	public function get_all($limit, $offset, $search='', $branch='')
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_tsdaily')
							->where('tbl_tsdaily.deleted','0')
							->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
							->like('tsdaily_group',$search)
							->like('group_branch',$branch)
							->limit($limit,$offset)
							->order_by('tsdaily_id','desc')
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_tsdaily')
						->where('tbl_tsdaily.deleted','0')
						->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
						->like('group_branch',$branch)
						->limit($limit,$offset)
						->order_by('tsdaily_id','desc')
						->get()
						->result();
		}
	}
	
	public function count_all($search,$branch)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
						->like('group_branch',$branch)
						->like('tsdaily_group',$search)
						->where('tbl_tsdaily.deleted','0')
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	
	public function get_all_daily_report($limit, $offset, $search='', $branch='')
	{
		if($search != '')
		{
			return $this->db->select('*,SUM(tsdaily_angsuranpokok) as total_angsuranpokok,
										SUM(tsdaily_profit) as total_profit,
										SUM(tsdaily_tabwajib) as total_tabwajib,
										SUM(tsdaily_tabungan_debet) as total_tabungan_debet,
										SUM(tsdaily_tabungan_credit) as total_tabungan_credit,
										SUM(tsdaily_total_rf) as total_total_rf,
										SUM(tsdaily_total_tabungan) as total_total_tabungan')
							->from('tbl_tsdaily')
							->where('tbl_tsdaily.deleted','0')
							->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
							->like('tsdaily_group',$search)
							->like('group_branch',$branch)
							->group_by('tsdaily_date')
							->limit($limit,$offset)
							->order_by('tsdaily_id','desc')
							->get()
							->result();
		}else
		{		
			return $this->db->select('*,SUM(tsdaily_angsuranpokok) as total_angsuranpokok,
										SUM(tsdaily_profit) as total_profit,
										SUM(tsdaily_tabwajib) as total_tabwajib,
										SUM(tsdaily_tabungan_debet) as total_tabungan_debet,
										SUM(tsdaily_tabungan_credit) as total_tabungan_credit,
										SUM(tsdaily_total_rf) as total_total_rf,
										SUM(tsdaily_total_tabungan) as total_total_tabungan')
						->from('tbl_tsdaily')
						->where('tbl_tsdaily.deleted','0')
						->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
						->like('group_branch',$branch)
						->group_by('tsdaily_date')
						->limit($limit,$offset)
						->order_by('tsdaily_id','desc')
						->get()
						->result();
		}
	}
	
	public function count_all_daily_report($search,$branch)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
						->like('group_branch',$branch)
						->like('tsdaily_group',$search)
						->group_by('tsdaily_date')
						->where('tbl_tsdaily.deleted','0')
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function get_all_daily_report_bydate($branch='',$tgl='' )
	{
		
			return $this->db->select('*')
						->from('tbl_tsdaily')
						->where('tbl_tsdaily.deleted','0')
						->where('tbl_tsdaily.tsdaily_date',$tgl)
						->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
						->like('group_branch',$branch)
						->order_by('tsdaily_id','desc')
						->get()
						->result();
	
	}
	
	
	public function get_all_daily_report_summary_bydate($date_start, $date_end)
	{
		
			return $this->db->select('SUM(tsdaily_angsuranpokok) AS total_angsuranpokok, 
										SUM(tsdaily_profit) AS total_angsuranprofit,
										SUM((tsdaily_tabungan_debet - tsdaily_tabungan_credit)) AS total_tabungan_sukarela,
										SUM((tsdaily_tabungan_berjangka_debet - tsdaily_tabungan_berjangka_credit)) AS total_tabungan_berjangka,
										COUNT(DISTINCT tsdaily_groupid) AS total_majelis'
										
										)  
						->from('tbl_tsdaily')
						->where('tbl_tsdaily.deleted','0')
						->where('tbl_tsdaily.tsdaily_date >= "'.$date_start.'"')
						->where('tbl_tsdaily.tsdaily_date <= "'.$date_end.'"')
						->get()
						->result();
	
	}
	
	public function count_all_daily_report_bydate($branch,$date)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
						->like('group_branch',$branch)
						->like('tsdaily_group',$search)
						->where('tbl_tsdaily.deleted','0')
						->where('tbl_tsdaily.tsdaily_date',$date)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function get_all_by_week($branch='', $date_start='', $date_end='')
	{
		return $this->db->select('*')
						->from('tbl_tsdaily')
						->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
						->where('tbl_tsdaily.deleted','0')
						->where('tbl_tsdaily.tsdaily_date >= "'.$date_start.'"')
						->where('tbl_tsdaily.tsdaily_date <= "'.$date_end.'"')  
						->like('group_branch',$branch)						
						->order_by('tsdaily_date','asc')
						->get()
						->result();
	}
	
	public function check_entry_topsheet($groupid, $date_start='', $date_end='')
	{
		
			return $this->db->select("count(*) as numrows")
							->from('tbl_tsdaily')
							->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
							->where('tbl_tsdaily.deleted','0')
							->where('tbl_tsdaily.tsdaily_date >= "'.$date_start.'"')
							->where('tbl_tsdaily.tsdaily_date <= "'.$date_end.'"')
							->where('tsdaily_groupid',$groupid)
							->get()
							->row()
							->numrows;
		
	}
	
	public function get_history_by_group($branch='',$group='')
	{
		return $this->db->select('*')
						->from('tbl_tsdaily')
						->where('tbl_tsdaily.deleted','0')
						->where('tbl_tsdaily.tsdaily_groupid',$group)
						->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
						->like('group_branch',$branch)
						->order_by('tsdaily_id','desc')
						->get()
						->result();
	}
	
	public function count_history_by_group($branch,$group)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
						->like('group_branch',$branch)
						->where('tbl_tsdaily.deleted','0')
						->where('tbl_tsdaily.tsdaily_groupid',$group)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
}