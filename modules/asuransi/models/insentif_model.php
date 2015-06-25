<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Insentif Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	15 November 2014
 */
 
class insentif_model extends MY_Model {

    protected $table        = 'tbl_transaction';
    protected $key          = 'tr_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	
	public function count_transaksi($officer,$date)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_transaction')
						//->join('tbl_clients', 'tbl_clients.client_id = tbl_transaction.tr_client', 'left')
						->join('tbl_group', "tbl_group.group_id = tbl_transaction.tr_group", 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	public function count_anggota($officer,$date)
	{
		return $this->db->select("count(distinct tr_client) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', "tbl_group.group_id = tbl_transaction.tr_group", 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	

	public function count_majelis($officer,$date)
	{
		return $this->db->select("count(distinct tr_group) as numrows")
						->from('tbl_transaction')
						//->join('tbl_clients', 'tbl_clients.client_id = tbl_transaction.tr_client', 'left')
						->join('tbl_group', "tbl_group.group_id = tbl_transaction.tr_group", 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	public function count_pembiayaan($officer,$date)
	{
		return $this->db->select("count(distinct tr_pembiayaan) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_pembiayaan != 0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	public function count_tabsukarela($officer,$date)
	{
		return $this->db->select("count(distinct tr_client) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_tabsukarela_debet != 0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_par($officer,$date)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_pembiayaan != 0')
						->where('tbl_transaction.tr_freq','0')	
						->where('tbl_transaction.tr_angsuranpokok','0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	
	public function sum_par($officer,$date)
	{
		return $this->db->select("sum((50-data_angsuranke) * data_plafond/50) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_transaction.tr_pembiayaan', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_pembiayaan != 0')
						->where('tbl_transaction.tr_freq','0')	
						->where('tbl_transaction.tr_angsuranpokok','0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_tabwajib_debet($officer,$date)
	{
		return $this->db->select("sum(tr_tabwajib_debet) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	public function count_tabwajib_credit($officer,$date)
	{
		return $this->db->select("sum(tr_tabwajib_credit) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_tabsukarela_debet($officer,$date)
	{
		return $this->db->select("sum(tr_tabsukarela_debet) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	public function count_tabsukarela_credit($officer,$date)
	{
		return $this->db->select("sum(tr_tabsukarela_credit) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_transaction.deleted','0')			
						->like('tbl_transaction.tr_date',$date)				
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_tabsukarela_saldo($officer)
	{
		return $this->db->select("sum(tabsukarela_saldo) as numrows")
						->from('tbl_tabsukarela')
						->join('tbl_clients', 'tbl_clients.client_id = tbl_tabsukarela.tabsukarela_client', 'left')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_clients.deleted','0')			
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_tabwajib_saldo($officer)
	{
		return $this->db->select("sum(tabwajib_saldo) as numrows")
						->from('tbl_tabwajib')
						->join('tbl_clients', 'tbl_clients.client_id = tbl_tabwajib.tabwajib_client', 'left')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_clients.deleted','0')			
						->get()
						->row()
						->numrows;
	}
	
	
	public function count_pembiayaan_saldo($officer)
	{
		return $this->db->select("sum((50-data_angsuranke) * data_plafond/50) as numrows")
						->from('tbl_pembiayaan')
						->join('tbl_clients', 'tbl_clients.client_id = tbl_pembiayaan.data_client', 'left')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->where('tbl_group.group_tpl',$officer)	
						->where('tbl_clients.deleted','0')			
						->get()
						->row()
						->numrows;
	}
}