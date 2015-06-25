<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Jurnal Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 February 2013
 */
 
class jurnal_model extends MY_Model {

    protected $table        = 'tbl_jurnal';
    protected $key          = 'jurnal_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function get_all(){
		$this->db->join('tbl_accounting_debet', 'tbl_accounting_debet.accouting_debet_code= tbl_jurnal.jurnal_account_debet', 'left')
				 ->join('tbl_accounting_credit', 'tbl_accounting_credit.accouting_credit_code = tbl_jurnal.jurnal_account_credit', 'left')
				 ->where('tbl_jurnal.deleted', '0')
				 ->order_by('jurnal_id','DESC');
        return $this->db->get($this->table);    
    }
	public function get_jurnal($id){
		$this->db->where('jurnal_id', $id);
        return $this->db->get($this->table);    
    }
	
	public function get_all_jurnal($limit, $offset, $search='', $key='', $branch, $date_start, $date_end)
	{
		if($search != '' AND $date_start != '' AND $date_end != ''){
			return $this->db->select('*')
						->from('tbl_jurnal')
						->join('tbl_accounting_debet', 'tbl_accounting_debet.accounting_debet_code = tbl_jurnal.jurnal_account_debet', 'left')
						->join('tbl_accounting_credit', 'tbl_accounting_credit.accounting_credit_code = tbl_jurnal.jurnal_account_credit', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_jurnal.jurnal_branch', 'left')
						->where('tbl_jurnal.deleted','0')
						->where("tbl_jurnal.jurnal_date >= '".$date_start."'")
						->where("tbl_jurnal.jurnal_date <= '".$date_end."'")
						->like('jurnal_'.$key,$search)
						->like('jurnal_branch',$branch)
						//->limit($limit,$offset)
						->order_by('jurnal_date','DESC')
						->order_by('jurnal_id','DESC')
						->get()
						->result();
		}elseif($search != '' AND $date_start == '' AND $date_end == ''){
			return $this->db->select('*')
						->from('tbl_jurnal')
						->join('tbl_accounting_debet', 'tbl_accounting_debet.accounting_debet_code = tbl_jurnal.jurnal_account_debet', 'left')
						->join('tbl_accounting_credit', 'tbl_accounting_credit.accounting_credit_code = tbl_jurnal.jurnal_account_credit', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_jurnal.jurnal_branch', 'left')
						->where('tbl_jurnal.deleted','0')
						->like('jurnal_'.$key,$search)
						->like('jurnal_branch',$branch)
						//->limit($limit,$offset)
						->order_by('jurnal_date','DESC')
						->order_by('jurnal_id','DESC')
						->get()
						->result();
		}elseif($search == '' AND $date_start != '' AND $date_end != ''){ 
			return $this->db->select('*')
						->from('tbl_jurnal')
						->join('tbl_accounting_debet', 'tbl_accounting_debet.accounting_debet_code = tbl_jurnal.jurnal_account_debet', 'left')
						->join('tbl_accounting_credit', 'tbl_accounting_credit.accounting_credit_code = tbl_jurnal.jurnal_account_credit', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_jurnal.jurnal_branch', 'left')
						->where('tbl_jurnal.deleted','0')
						->where("tbl_jurnal.jurnal_date >= '".$date_start."'")
						->where("tbl_jurnal.jurnal_date <= '".$date_end."'")
						->like('jurnal_branch',$branch)
						//->limit($limit,$offset)
						->order_by('jurnal_date','DESC')
						->order_by('jurnal_id','DESC')
						->get()
						->result();
		}else{		
			return $this->db->select('*')
						->from('tbl_jurnal')
						->join('tbl_accounting_debet', 'tbl_accounting_debet.accounting_debet_code = tbl_jurnal.jurnal_account_debet', 'left')
						->join('tbl_accounting_credit', 'tbl_accounting_credit.accounting_credit_code = tbl_jurnal.jurnal_account_credit', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_jurnal.jurnal_branch', 'left')
						->where('tbl_jurnal.deleted','0')
						->like('jurnal_branch',$branch)
						->limit($limit,$offset)
						->order_by('jurnal_date','DESC')
						->order_by('jurnal_id','DESC')
						->get()
						->result();
		}
	}
	
	
	
	public function count_all_jurnal($search, $key,$branch, $date_start, $date_end)
	{
		if($search != '' AND $date_start != '' AND $date_end != ''){
			return $this->db->select("count(*) as numrows")
							->from('tbl_jurnal')
							->where('deleted','0')
							->where("tbl_jurnal.jurnal_date >= '".$date_start."'")
							->where("tbl_jurnal.jurnal_date <= '".$date_end."'")
							->like('jurnal_'.$key,$search)
							->like('jurnal_branch',$branch)
							->get()
							->row()
							->numrows;
		}elseif($search != '' AND $date_start == '' AND $date_end == ''){
			return $this->db->select("count(*) as numrows")
							->from('tbl_jurnal')
							->where('deleted','0')
							->like('jurnal_'.$key,$search)
							->like('jurnal_branch',$branch)
							->get()
							->row()
							->numrows;
		}elseif($search == '' AND $date_start != '' AND $date_end != ''){ 
			return $this->db->select("count(*) as numrows")
							->from('tbl_jurnal')
							->where('deleted','0')
							->where("tbl_jurnal.jurnal_date >= '".$date_start."'")
							->where("tbl_jurnal.jurnal_date <= '".$date_end."'")
							->like('jurnal_'.$key,$search)
							->like('jurnal_branch',$branch)
							->get()
							->row()
							->numrows;
		}else{	
			return $this->db->select("count(*) as numrows")
							->from('tbl_jurnal')
							->where('deleted','0')
							->like('jurnal_branch',$branch)
							->get()
							->row()
							->numrows;
		}
	}
	
	// SUM ACCOUNT
	public function sum_account_debet($id)
	{
		return $this->db->select("sum(jurnal_debet) as total")
						->from('tbl_jurnal')
						->where('jurnal_account_debet',$id)
						->where('deleted','0')
						->get()
						->row()
						->total;
	}
	public function sum_account_credit($id)
	{
		return $this->db->select("sum(jurnal_credit) as total")
						->from('tbl_jurnal')
						->where('jurnal_account_credit',$id)
						->where('deleted','0')
						->get()
						->row()
						->total;
	}
	
	public function sum_account_parent_debet($id)
	{
		return $this->db->select("sum(jurnal_debet) as total")
						->from('tbl_jurnal')
						->where('deleted','0')
						->like('jurnal_account_debet',$id,'after')
						->get()
						->row()
						->total;
	}
	
	public function sum_account_parent_credit($id)
	{
		return $this->db->select("sum(jurnal_credit) as total")
						->from('tbl_jurnal')
						->where('deleted','0')
						->like('jurnal_account_credit',$id,'after')
						->get()
						->row()
						->total;
	}
	
	// SUM ACCOUNT BY DATE
	public function sum_account_debet_by_date($id,$date_start,$date_end,$branch)
	{
		return $this->db->select("sum(jurnal_debet) as total")
		//return $this->db->select("sum(jurnal_id) as total")
						->from('tbl_jurnal')
						->where('jurnal_account_debet',$id)
						->where('deleted','0')
						->where('jurnal_date >=',$date_start)
						->where('jurnal_date <=',$date_end)
						->where('jurnal_branch',$branch)
						->get()
						->row()
						->total;
	}
	public function sum_account_credit_by_date($id,$date_start,$date_end,$branch)
	{
		return $this->db->select("sum(jurnal_credit) as total")
						->from('tbl_jurnal')
						->where('jurnal_account_credit',$id)
						->where('deleted','0')
						->where('jurnal_date >=',$date_start)
						->where('jurnal_date <=',$date_end)
						->like('jurnal_branch',$branch)
						->get()
						->row()
						->total;
	}
	
	public function sum_account_parent_debet_by_date($id,$date_start,$date_end,$branch)
	{
		return $this->db->select("sum(jurnal_debet) as total")
						->from('tbl_jurnal')
						->where('deleted','0')
						->where('jurnal_date >=',$date_start)
						->where('jurnal_date <=',$date_end)
						->like('jurnal_account_debet',$id,'after')
						->like('jurnal_branch',$branch)
						->get()
						->row()
						->total;
	}
	
	public function sum_account_parent_credit_by_date($id,$date_start,$date_end,$branch)
	{
		return $this->db->select("sum(jurnal_credit) as total")
						->from('tbl_jurnal')
						->where('deleted','0')
						->where('jurnal_date >=',$date_start)
						->where('jurnal_date <=',$date_end)
						->like('jurnal_account_credit',$id,'after')
						->like('jurnal_branch',$branch)
						->get()
						->row()
						->total;
	}
	
	
	
	public function get_all_jurnal_by_account($search='',$branch,$date_start,$date_end)
	{
		if($search != '')
		{
			$where = "(tbl_jurnal.deleted='0' AND tbl_jurnal.jurnal_branch LIKE '%".$branch."%' ) AND (jurnal_account_debet LIKE '%".$search."%' OR jurnal_account_credit LIKE '%".$search."%') AND jurnal_date >= '".$date_start."' AND jurnal_date <= '".$date_end."'";
			return $this->db->select('*')
						->from('tbl_jurnal')
						->join('tbl_accounting_debet', 'tbl_accounting_debet.accounting_debet_code = tbl_jurnal.jurnal_account_debet', 'left')
						->join('tbl_accounting_credit', 'tbl_accounting_credit.accounting_credit_code = tbl_jurnal.jurnal_account_credit', 'left')
						->where($where)
						//->like('tbl_jurnal.jurnal_branch',$branch)
						//->like('jurnal_account_debet',$search)
						//->or_like('jurnal_account_credit',$search)
						//->limit($limit,$offset)
						->order_by('jurnal_date','ASC')
						->get()
						->result();
		}else{		
			return $this->db->select('*')
						->from('tbl_jurnal')
						->join('tbl_accounting_debet', 'tbl_accounting_debet.accounting_debet_code = tbl_jurnal.jurnal_account_debet', 'left')
						->join('tbl_accounting_credit', 'tbl_accounting_credit.accounting_credit_code = tbl_jurnal.jurnal_account_credit', 'left')
						->where($where)
						//->like('tbl_jurnal.jurnal_branch',$branch)
						//->like('jurnal_account_debet',$search)
						//->or_like('jurnal_account_credit',$search)
						//->limit($limit,$offset)
						->order_by('jurnal_date','DESC')
						->get()
						->result();
		}
	}
	
	public function count_all_jurnal_by_account($search,$branch,$date_start,$date_end)
	{
			$where = "(tbl_jurnal.deleted='0' AND tbl_jurnal.jurnal_branch LIKE '%".$branch."%' ) AND (jurnal_account_debet LIKE '%".$search."%' OR jurnal_account_credit LIKE '%".$search."%') AND jurnal_date >= '".$date_start."' AND jurnal_date <= '".$date_end."'";
			
			return $this->db->select("count(*) as numrows")
							->from('tbl_jurnal')
							->where($where )
							//->like('jurnal_branch',$branch)
							//->like('jurnal_account_debet',$search)
							//->or_like('jurnal_account_credit',$search)
							->get()
							->row()
							->numrows;
		
	}
	
	public function get_kaskecil_detail($code){
		$this->db->where('kaskecil_code', $code);
        return $this->db->get('tbl_kaskecil');    
    }
}