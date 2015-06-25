<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Asuransi Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class asuransi_model extends MY_Model {

    protected $table        = 'tbl_officer';
    protected $key          = 'officer_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
		
	public function get_all_asuransi($limit, $offset)
	{
		return $this->db->select('*')
						->from('view_rekap_asuransi')
						->order_by('Cabang','asc')
						->order_by('Majelis','asc')
						->order_by('Nama','asc')
						->limit($limit,$offset)
						->get()
						->result();
	}
	
	
	public function count_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from('view_rekap_asuransi')
						->get()
						->row()
						->numrows;
	}
	
}