<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Pembiayaan Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 January 2014
 */
 
class repayment_model extends MY_Model {

    protected $table        = 'tbl_transaction';
    protected $key          = 'tr_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function get_repayment($id)
	{
		
			return $this->db->select('*')
						->from('tbl_transaction')
						->join('tbl_clients', 'tbl_clients.client_id = tbl_transaction.tr_client', 'left')
						->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_transaction.tr_pembiayaan', 'left')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->where('tbl_transaction.tr_pembiayaan',$id)
						->where('tbl_transaction.deleted',0)
						->order_by('tr_id','ASC')
						->get()
						->result();
		
	}
}