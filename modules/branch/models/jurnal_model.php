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
	
	public function get_all_jurnal($limit, $offset, $search='', $key='')
	{
		if($search != '')		{
			return $this->db->select('*')
						->from('tbl_jurnal')
						->join('tbl_accounting_debet', 'tbl_accounting_debet.accounting_debet_code = tbl_jurnal.jurnal_account_debet', 'left')
						->join('tbl_accounting_credit', 'tbl_accounting_credit.accounting_credit_code = tbl_jurnal.jurnal_account_credit', 'left')
						->where('tbl_jurnal.deleted','0')
						->like('jurnal_'.$key,$search)
						->limit($limit,$offset)
						->order_by('jurnal_id','DESC')
						->get()
						->result();
		}else{		
			return $this->db->select('*')
						->from('tbl_jurnal')
						->join('tbl_accounting_debet', 'tbl_accounting_debet.accounting_debet_code = tbl_jurnal.jurnal_account_debet', 'left')
						->join('tbl_accounting_credit', 'tbl_accounting_credit.accounting_credit_code = tbl_jurnal.jurnal_account_credit', 'left')
						->where('tbl_jurnal.deleted','0')
						->limit($limit,$offset)
						->order_by('jurnal_id','DESC')
						->get()
						->result();
		}
	}
	
	public function count_all_jurnal($search, $key)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_jurnal')
						->where('deleted','0')
						->like($key,$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
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
}