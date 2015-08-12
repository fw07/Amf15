<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Lenders Model
 * 
 * @package	amartha
 * @author 	afahmi@amartha.co.id
 * @since	7 July 2015
 */
 
class lenders_model extends MY_Model {

    protected $table          = 'tbl_lenders';
    protected $key            = 'lender_id';
    protected $key2           = 'lender_code';
    protected $soft_deletes   = true;
    protected $date_format    = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    

    public function create_investor_details($data){
    	return $this->db->insert($this->table, $data);
    }
	
	public function get_all_lenders()
	{
		return $this->db->get($this->table);
	}

	public function get_all_active_lenders($search='', $key='')
	{
		return $this->db->select('*')
						->from($this->table)
						->where('deleted','0')
		//				->where($search, $key)
						->order_by('lender_id','ASC')
						->get()
						->result();
	}

	public function get_all_lenders_attributes()
	{
		return $this->db->select('lender_id', 'lender_code', 'lender_name')
						->from('tbl_lenders')
						->where('tbl_lenders.deleted','0')
						->order_by('client_id','ASC')
						->get()
						->result();
	}

	public function get_some_lenders($limit, $offset, $search='', $key='')
	{
		return $this->db->select('*')
						->from($this->table)
						->where('deleted','0')
		//				->where($search, $key)
						->limit($limit,$offset)
						->order_by('lender_id','ASC')
						->get()
						->result();
	}
	
	public function get_single_lender($id, $limit=1, $offset=0)
	{
		return $this->db->get_where($this->table, array(
						$this->key => $id,
						'deleted' => '0'
						), $limit, $offset);
	}

	public function update_investor_details($id, $data){
    	$this->db->where('lender_id', $id);
		return $this->db->update($this->table, $data);
    }

    public function delete_one_lender($id){
    	$data = array('deleted' => '1');
    	$this->db->where('lender_id', $id);
		return $this->db->update($this->table, $data);
    }
	
	public function count_all_lenders()
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}

	public function count_max_id(){
		$this->db->select_max('lender_id', 'lid');
		return $this->db->get($this->table)->row()->lid;
		//SELECT MAX(lender_id) as id FROM tbl_lenders
	}
}