<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Investment Model
 * 
 * @package	amartha
 * @author 	afahmi@amartha.co.id
 * @since	7 July 2015
 */
 
class investment_model extends MY_Model {

    protected $table          = 'tbl_investment';
    protected $key            = 'investment_id';
    protected $soft_deletes   = true;
    protected $date_format    = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    

    public function create_investment_details($data){
    	return $this->db->insert($this->table, $data);
    }
	
	public function get_all_investment_details()
	{
		return $this->db->get($this->table);
	}

	public function get_all_active_investments($search='', $key='')
	{
		return $this->db->select('*')
						->from($this->table)
						->where('deleted','0')
		//				->where($search, $key)
						->order_by('lender_id','ASC')
						->get()
						->result();
	}

	public function get_all_investments_attributes()
	{
		return $this->db->select('lender_id, lender_code, lender_name, investment_id, investment_date, investment_amount, investment_type')
						->from($this->table)
						->join('tbl_lenders', 'tbl_lenders.lender_id = tbl_investment.lender_id', 'left')
						->where('tbl_investment.deleted','0')
						->where('tbl_lenders.deleted','0')
						->order_by('tbl_investment.lender_id','ASC')
						->get()
						->result();
	}

	public function get_some_investments($limit, $offset, $search='', $key='')
	{
		return $this->db->select('*')
						->from('tbl_investment')
						->join('tbl_lenders', 'tbl_lenders.lender_id = tbl_investment.lender_id', 'left')
						->where('tbl_investment.deleted','0')
		//				->where($search, $key)
						->limit($limit, $offset)
						->order_by('investment_id','ASC')
						->get()
						->result();
	}
	
	public function get_single_investment($id, $limit=1, $offset=0)
	{
		return $this->db->get_where($this->table, array(
						$this->key => $id,
						'deleted' => '0'
						), $limit, $offset);
	}

	public function update_investment_details($id, $data){
    	$this->db->where('investment_id', $id);
		return $this->db->update($this->table, $data);
		//return $this->db->update($this->table, $data, array('investment_id' => $id));
    }

    public function delete_one_investment($id){
    	$data = array('deleted' => '1');
    	$this->db->where('investment_id', $id);
		return $this->db->update($this->table, $data);
    }
	
	public function count_all_investments()
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}

	public function count_max_id(){
		$this->db->select_max('investment_id', 'lid');
		return $this->db->get($this->table)->row()->lid;
		//SELECT MAX(lender_id) as id FROM tbl_lenders
	}
}