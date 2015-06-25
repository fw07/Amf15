<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Topsheet Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class topsheet_model extends MY_Model {

    protected $table        = 'tbl_tsdaily';
    protected $key          = 'tsdaily_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function get_pembiayaan_by_group($id)
	{
		return $this->db->join('tbl_pembiayaan', 'tbl_pembiayaan.data_client = tbl_clients.client_id', 'left')
						->join('tbl_saving', 'tbl_saving.saving_account = tbl_clients.client_account', 'left')
						->where('client_group', $id)
						->where('tbl_clients.deleted', '0')
						->where('tbl_pembiayaan.deleted', '0')
						->where('tbl_pembiayaan.data_status', '1')
						->get('tbl_clients')
						->result();
	}
	
	public function get_topsheet($id)
	{
		return $this->db->select('*')
					->join('tbl_pembiayaan', 'tbl_pembiayaan.data_client = tbl_transaction.tr_client', 'left')
					->join('tbl_clients', 'tbl_clients.client_id = tbl_transaction.tr_client', 'left')
					->join('tbl_tabsukarela', 'tbl_tabsukarela.tabsukarela_client = tbl_transaction.tr_client', 'left')
					->where('tr_topsheet_code', $id)				
					->get('tbl_transaction')
					->result();
	}
	
	public function get_tsdaily($id)
	{
		return $this->db->select('*')
					->where('tsdaily_topsheet_code', $id)				
					->get('tbl_tsdaily')
					->result();
	}
	
	public function get_transaction($id)
	{
		return $this->db->select('*')
					->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_transaction.tr_pembiayaan', 'left')
					->join('tbl_clients', 'tbl_clients.client_id = tbl_transaction.tr_client', 'left')
					->join('tbl_tabsukarela', 'tbl_tabsukarela.tabsukarela_client = tbl_transaction.tr_client', 'left')
					->where('tr_topsheet_code', $id)				
					->get('tbl_transaction')
					->result();
	}
}