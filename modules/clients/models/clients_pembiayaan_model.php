<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class clients_pembiayaan_model extends MY_Model {

    protected $table        = 'tbl_pembiayaan';
    protected $key          = 'data_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	public function get_all($limit, $offset, $search='', $key='')
	{
		if($search != '')
		{
			return $this->db->select('*')
						->from('tbl_pembiayaan')
						->join('tbl_clients', 'tbl_clients.client_id = tbl_pembiayaan.data_client', 'left')
						->where('tbl_pembiayaan.deleted','0')
						->like("client_$key",$search)
						->limit($limit,$offset)
						->order_by('client_id','DESC')
						->get()
						->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_pembiayaan')
						->join('tbl_clients', 'tbl_clients.client_id = tbl_pembiayaan.data_client', 'left')
						->where('tbl_pembiayaan.deleted','0')
						->limit($limit,$offset)
						->order_by('client_id','DESC')
						->get()
						->result();
		}
	}
	
	public function get_pembiayaan($id)
	{
		$this->db->where('data_id', $id);		
		return $this->db->get($this->table);
	}
	
	public function get_pembiayaan_by_client($id)
	{
		$this->db->where('data_client', $id)
				->where('deleted', '0');		
		return $this->db->get($this->table);
	}
	
	public function get_pembiayaan_aktif($id)
	{
		$this->db->where('data_client', $id)
				 ->where('data_status', '1');		
		return $this->db->get($this->table);
	}
	
		public function count_clients_absensi_h($search)
	{
		return $this->db->select("sum(tr_absen_h) as numrows")
						->from("tbl_transaction")
						->where('tr_pembiayaan',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_a($search)
	{
		return $this->db->select("sum(tr_absen_a) as numrows")
						->from("tbl_transaction")
						->where('tr_pembiayaan',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_s($search)
	{
		return $this->db->select("sum(tr_absen_s) as numrows")
						->from("tbl_transaction")
						->where('tr_pembiayaan',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_i($search)
	{
		return $this->db->select("sum(tr_absen_i) as numrows")
						->from("tbl_transaction")
						->where('tr_pembiayaan',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_clients_absensi_c($search)
	{
		return $this->db->select("sum(tr_absen_c) as numrows")
						->from("tbl_transaction")
						->where('tr_pembiayaan',$search)
						->where('deleted','0')
						->get()
						->row()
						->numrows;
	}
}