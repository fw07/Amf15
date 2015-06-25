<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Pembiayaan Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 January 2014
 */
 
class par_model extends MY_Model {

    protected $table        = 'tbl_risk';
    protected $key          = 'risk_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function get_par_history($pembiayaan_id)
	{
		
			return $this->db->select('*')
						->from('tbl_risk')
						->join('tbl_clients', 'tbl_clients.client_id = tbl_risk.risk_client', 'left')
						->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_risk.risk_pembiayaan', 'left')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->where('tbl_risk.risk_pembiayaan',$pembiayaan_id)
						->where('tbl_risk.deleted',0)
						->order_by('risk_id','ASC')
						->get()
						->result();
		
	}
}