<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class clients_model extends MY_Model {

    protected $table        = 'tbl_clients';
    protected $key          = 'client_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	
	public function get_client_active($gid)
	{
		$this->db->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left');
		$this->db->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left');
		$this->db->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id', 'left');
		$this->db->where('group_id', $gid);	
		$this->db->where('client_status', 1); // status : aktif	
		$this->db->order_by('client_fullname', 'asc');
		return $this->db->get($this->table);
	}
	
	public function get_client_notactive($gid)
	{
		$this->db->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left');
		$this->db->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left');
		$this->db->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id', 'left');
		$this->db->join('tbl_alasankeluar', 'tbl_alasankeluar.alasan_id = tbl_clients.client_reason', 'left');
		$this->db->where('group_id', $gid);	
		$this->db->where('client_status', 0); // status : keluar
		$this->db->order_by('client_fullname', 'asc');
		return $this->db->get($this->table);
	}
	
	public function count_clients_absensi_h($search)
	{
		return $this->db->select("sum(tr_absen_h) as numrows")
						->from("tbl_transaction")
						->where('tr_client',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_a($search)
	{
		return $this->db->select("sum(tr_absen_a) as numrows")
						->from("tbl_transaction")
						->where('tr_client',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_s($search)
	{
		return $this->db->select("sum(tr_absen_s) as numrows")
						->from("tbl_transaction")
						->where('tr_client',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_i($search)
	{
		return $this->db->select("sum(tr_absen_i) as numrows")
						->from("tbl_transaction")
						->where('tr_client',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_c($search)
	{
		return $this->db->select("sum(tr_absen_c) as numrows")
						->from("tbl_transaction")
						->where('tr_client',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
}