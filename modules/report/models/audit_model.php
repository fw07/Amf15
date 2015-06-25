<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Audit Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 January 2013
 */
 
class audit_model extends MY_Model {

    protected $table        = 'tbl_clients';
    protected $key          = 'client_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	//get last client
	public function get_new_client($branch,$startdate,$enddate)
	{
		$this->db	->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id', 'left')
					->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
					->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
					->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
					->like('tbl_group.group_branch',$branch)
					->where('tbl_clients.client_reg_date >=',$startdate)
					->where('tbl_clients.client_reg_date <=',$enddate)
					->where('tbl_clients.client_status','1')
					->where('tbl_clients.deleted','0');
        return $this->db->get("tbl_clients"); 
	}
		
	//get last client
	public function get_new_client_unreg($branch,$startdate,$enddate)
	{
		$this->db	->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id', 'left')
					->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
					->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
					->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
					->join('tbl_alasankeluar', 'tbl_alasankeluar.alasan_id = tbl_clients.client_reason', 'left')
					->like('tbl_group.group_branch',$branch)
					->where('tbl_clients.client_unreg_date >=',$startdate)
					->where('tbl_clients.client_unreg_date <=',$enddate)
					->where('tbl_clients.client_status','0')
					->where('tbl_clients.deleted','0');
        return $this->db->get("tbl_clients"); 
	}
	

	
	public function get_pyd_data($branch)
	{
		return $this->db->select('*')
						->from('view_regpyd')
						->like('Cabang',$branch)
						->order_by('Cabang','asc')
						->order_by('Majelis','asc')
						->order_by('Nomor_Rekening','asc')
						->order_by('Nama','asc')
						//->limit(30,0)
						->get()
						->result();
	}
	
	
	public function get_pyd_data_by_branch_by_date($branch, $date_start, $date_end)
	{
		return $this->db->select('*')
						->from('tbl_transaction')
						->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_transaction.tr_pembiayaan', 'left')
						->join('tbl_clients', 'tbl_clients.client_id = tbl_transaction.tr_client', 'left')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tr_date >=',$date_start)
						->where('tr_date <=',$date_end)
						//->where("tr_date <= $date_end")
						->where("tbl_transaction.deleted", '0')
						->where('branch_id',$branch)
						->order_by('branch_id','asc')
						->order_by('group_name','asc')
						->order_by('client_account','asc')
						->order_by('client_fullname','asc')
						//->limit(30,0)
						->get()
						->result();
	}
	
	/*
	//Get Report All
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
	
	//Get Report per Branch
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
	
	//Count Report All
	public function count_report_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->like('report_branch',$search)
						->get()
						->row()
						->numrows;
	}
	
	//Count Report All
	public function count_report_branch($search,$branch)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->where('report_branch',$branch)
						->like('report_branch',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function get_last_report_by_branch($branch){
		$this->db	->where('report_branch',$branch)
					->limit(1)
					->order_by('report_id','desc');
        return $this->db->get($this->table);    
    }
	
	//get last group
	public function get_new_group($branch,$startdate,$enddate)
	{
		$this->db	->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
					->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
					->like('tbl_group.group_branch',$branch)
					->where('tbl_group.group_date >=',$startdate)
					->where('tbl_group.group_date <=',$enddate)
					->where('tbl_group.deleted','0');
        return $this->db->get("tbl_group"); 
	}
	
	
	//get last pengajuan
	public function get_new_pengajuan($branch,$startdate,$enddate)
	{
		$this->db	->join('tbl_clients', 'tbl_clients.client_id = tbl_pembiayaan.data_client', 'left')
					->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
					->where('tbl_clients.client_branch',$branch)
					//->where('tbl_pembiayaan.data_status','2')
					->where('tbl_pembiayaan.deleted','0')
					->where('tbl_pembiayaan.data_tgl >=',$startdate)
					->where('tbl_pembiayaan.data_tgl <=',$enddate)
					->where('tbl_pembiayaan.deleted','0');
        return $this->db->get("tbl_pembiayaan"); 
	}
	
	//get last pencairan
	public function get_new_pencairan($branch,$startdate,$enddate)
	{
		$this->db	->join('tbl_clients', 'tbl_clients.client_id = tbl_pembiayaan.data_client', 'left')
					->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
					->join('tbl_sector', 'tbl_sector.sector_id = tbl_pembiayaan.data_sector', 'left')
					->where('tbl_clients.client_branch',$branch)
					->where('tbl_pembiayaan.data_status','1')
					->where('tbl_pembiayaan.data_status_pengajuan','v')
					->where('tbl_pembiayaan.deleted','0')
					->where('tbl_pembiayaan.data_date_accept >=',$startdate)
					->where('tbl_pembiayaan.data_date_accept <=',$enddate)
					->where('tbl_pembiayaan.deleted','0');
        return $this->db->get("tbl_pembiayaan"); 
	}
	
	
	//get last KAS
	public function get_new_kas($branch,$startdate,$enddate)
	{
		$this->db	->join('tbl_branch', 'tbl_branch.branch_id = tbl_kas.kas_branch', 'left')
					->where('tbl_kas.kas_branch',$branch)
					->where('tbl_kas.deleted','0')
					->where('tbl_kas.kas_date >=',$startdate)
					->where('tbl_kas.kas_date <=',$enddate);
        return $this->db->get("tbl_kas"); 
	}

	
	
	*/
	public function get_branch($param){
		$this->db	->join('tbl_area', 'tbl_area.area_id = tbl_branch.branch_area', 'left')
					->where('branch_id', $param);
        return $this->db->get('tbl_branch');    
    }
}