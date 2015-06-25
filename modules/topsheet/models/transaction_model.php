<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Transaction Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	30 December 2013
 */
 
class transaction_model extends MY_Model {

    protected $table        = 'tbl_transaction';
    protected $key          = 'tr_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function delete_transaction($id, $data){
        $this->db->where("tr_topsheet_code",$id);
        $this->db->update("tbl_transaction", $data);
    } 
	public function delete_tr_tabwajib($id, $data){
        $this->db->where("tr_topsheet_code",$id);
        $this->db->update("tbl_tr_tabwajib", $data);
    }
	public function delete_tr_tabsukarela($id, $data){
        $this->db->where("tr_topsheet_code",$id);
        $this->db->update("tbl_tr_tabsukarela", $data);
    }
	public function delete_tr_tabberjangka($id, $data){
        $this->db->where("tr_topsheet_code",$id);
        $this->db->update("tbl_tr_tabberjangka", $data);
    }
	public function delete_jurnal($id, $data){
        $this->db->where("jurnal_tscode",$id);
        $this->db->update("tbl_jurnal", $data);
    }
	public function delete_tsdaily($id, $data){
        $this->db->where("tsdaily_topsheet_code",$id);
        $this->db->update("tbl_tsdaily", $data);
    }
	
	public function get_last_tr_date_by_group($group_id){
       $this->db ->select_max('tbl_transaction.tr_date')
				 ->where('tbl_transaction.deleted', '0')
				 ->where('tbl_transaction.tr_group', $group_id);
        return $this->db->get($this->table);    
    } 
}