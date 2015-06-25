<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Accounting Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 January 2013
 */
 
class accounting_model extends MY_Model {

    protected $table        = 'tbl_accounting';
    protected $key          = 'accounting_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	public function get_account($code){
		$this->db->where('accounting_code', $code);
        return $this->db->get($this->table);    
    }
	
	public function get_all(){
		$this->db->where('deleted', '0')
				 ->where("accounting_haschild",0)
				 ->order_by('accounting_code','ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_all_accounting(){
		$this->db->where('deleted', '0')
				 ->order_by('accounting_code','ASC');
        return $this->db->get($this->table);    
    }
	public function get_all_accounting_by_date(){
		$this->db->where('deleted', '0')
				 ->order_by('accounting_code','ASC');
        return $this->db->get($this->table);    
    }
	public function get_all_accounting_labarugi(){
		$this->db->where('deleted', '0')
				 ->like('accounting_code','4','after')
				 ->or_like('accounting_code','5','after')
				 ->order_by('accounting_code','ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_all_accounting_std(){
		$this->db->where('deleted', '0')
				 ->like('accounting_code','1','after')
				 ->or_like('accounting_code','2','after')
				 ->or_like('accounting_code','3','after')
				 ->order_by('accounting_code','ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_all_accounting_aset(){
		$this->db->where('deleted', '0')
				 ->like('accounting_code','1','after')
				 ->order_by('accounting_code','ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_all_accounting_kewajiban(){
		$this->db->where('deleted', '0')
				 ->like('accounting_code','2','after')
				 ->order_by('accounting_code','ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_all_accounting_modal(){
		$this->db->where('deleted', '0')
				 ->like('accounting_code','3','after')
				 ->order_by('accounting_code','ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_all_accounting_pendapatan(){
		$this->db->where('deleted', '0')
				 ->like('accounting_code','4','after')
				 ->order_by('accounting_code','ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_all_accounting_beban(){
		$this->db->where('deleted', '0')
				 ->like('accounting_code','5','after')
				 ->order_by('accounting_code','ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_all_account($limit, $offset, $search='')
	{
		if($search != '')		{
			return $this->db->select('*')
						->from('tbl_accounting')
						->where('deleted','0')
						->where("accounting_haschild",0)
						->like('accounting_name',$search)
						->limit($limit,$offset)
						->order_by('accounting_id','ASC')
						->get()
						->result();
		}else{		
			return $this->db->select('*')
						->from('tbl_accounting')
						->where('deleted','0')
						->where("accounting_haschild",0)
						->limit($limit,$offset)
						->order_by('accounting_id','ASC')
						->get()
						->result();
		}
	}
	
	public function count_all_account($search)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->where('deleted','0')
						->like('client_fullname',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
}