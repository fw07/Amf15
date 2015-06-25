<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Finance Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	23 December 2014
 */
 
class finance_model extends MY_Model {

    protected $table        = 'tbl_clients';
    protected $key          = 'client_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	//COUNT CLIENTS
	public function count_clients_daily($branch,$date)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_reg_date',$date)
						->where('tbl_clients.client_status','1')
						->get()
						->row()
						->numrows;
	}
	
	public function count_clients_unreg_daily($branch,$date)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_unreg_date',$date)
						->where('tbl_clients.client_status','0')
						->get()
						->row()
						->numrows;
	}
	
	
	//PENCAIRAN
	public function count_daily_pencairan($branch,$date)
	{
		return $this->db	->select("sum(data_plafond) as numrows")
							->from('tbl_pembiayaan')
							->join('tbl_clients', 'tbl_clients.client_id = tbl_pembiayaan.data_client', 'left')
							->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
							->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
							->where('tbl_branch.branch_id',$branch)
							->where('tbl_pembiayaan.data_status','1')
							->where('tbl_pembiayaan.data_status_pengajuan','v')
							->where('tbl_pembiayaan.deleted','0')
							->where('tbl_pembiayaan.data_date_accept',$date)
							->where('tbl_pembiayaan.deleted','0')
							->get()
							->row()
							->numrows;
	}
	
	
	//SETORAN
	public function count_daily_setoran_pokok($branch,$date)
	{
		return $this->db->select("sum(tr_angsuranpokok) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	public function count_daily_setoran_margin($branch,$date)
	{
		return $this->db->select("sum(tr_profit) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	public function count_daily_setoran_adm($branch,$date)
	{
		return $this->db->select("sum(tr_adm) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	public function count_daily_setoran_asuransi($branch,$date)
	{
		return $this->db->select("sum(tr_asuransi) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	public function count_daily_setoran_butab($branch,$date)
	{
		return $this->db->select("sum(tr_butab) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	public function count_daily_setoran_lwk($branch,$date)
	{
		return $this->db->select("sum(tr_lwk) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	
	//Tabungan
	
	public function count_daily_tab_tabwajib_debet($branch,$date)
	{
		return $this->db->select("sum(tr_tabwajib_debet) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	public function count_daily_tab_tabwajib_credit($branch,$date)
	{
		return $this->db->select("sum(tr_tabwajib_credit) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	public function count_daily_tab_tabsukarela_debet($branch,$date)
	{
		return $this->db->select("sum(tr_tabsukarela_debet) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	public function count_daily_tab_tabsukarela_credit($branch,$date)
	{
		return $this->db->select("sum(tr_tabsukarela_credit) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	public function count_daily_tab_tabberjangka_debet($branch,$date)
	{
		return $this->db->select("sum(tr_tabberjangka_debet) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	public function count_daily_tab_tabberjangka_credit($branch,$date)
	{
		return $this->db->select("sum(tr_tabberjangka_credit) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date',$date)
						->get()
						->row()
						->numrows;
	}
	
	
	//TS DAILY	
	public function get_tsdaily($branch, $date)
	{
		
			return $this->db->select('*')  
						->from('tbl_tsdaily')
						->join('tbl_group', 'tbl_group.group_id = tbl_tsdaily.tsdaily_groupid', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->join('tbl_officer', 'tbl_officer.officer_id = tbl_group.group_tpl', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_tsdaily.deleted','0')
						->where('tbl_tsdaily.tsdaily_date', $date)
						->order_by('tbl_officer.officer_name', 'ASC')
						->order_by('tbl_group.group_name', 'ASC')
						->get()
						->result();
	
	}
	
	public function count_daily_pencairan_by_group($group,$date)
	{
		return $this->db	->select("sum(data_plafond) as numrows")
							->from('tbl_pembiayaan')
							->join('tbl_clients', 'tbl_clients.client_id = tbl_pembiayaan.data_client', 'left')
							->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
							->where('tbl_group.group_id',$group)
							->where('tbl_pembiayaan.data_status','1')
							->where('tbl_pembiayaan.data_status_pengajuan','v')
							->where('tbl_pembiayaan.deleted','0')
							->where('tbl_pembiayaan.data_date_accept',$date)
							->where('tbl_pembiayaan.deleted','0')
							->get()
							->row()
							->numrows;
	}
	
	
	
	
	
	
	
	
	
	//COUNT GROUPS
	public function count_majelis()
	{
		$query= $this->db->query('SELECT * FROM  `tbl_group` WHERE deleted="0"');
		return $query->num_rows();  
	}
	public function count_majelis_by_branch($branch)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_group')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_group.deleted','0')
						->get()
						->row()
						->numrows;
	}
	
	
	//COUNT BRANCHS
	public function count_cabang()
	{
		$query= $this->db->query('SELECT * FROM  `tbl_branch`');
		return $query->num_rows();  
	}
	
	//COUNT OFFICERS
	public function count_officer()
	{
		$query= $this->db->query("SELECT * FROM  tbl_officer WHERE deleted='0'");
		return $query->num_rows();  
	}
	
	public function count_officer_by_branch($branch)
	{
		$query= $this->db->query("SELECT * FROM  tbl_officer WHERE deleted='0' AND officer_branch='$branch'");
		return $query->num_rows();  
	}
	
	
	//COUNT GRAPHIC ANGGOTA
	public function count_weeklyclients($startdate, $enddate)
	{
		
		
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','1')
						->where('tbl_clients.client_reg_date >= "'.$startdate.'"')
						->where('tbl_clients.client_reg_date <= "'.$enddate.'"')
						->get()
						->row()
						->numrows;
	}
	
	public function count_weeklyclients_by_branch($branch, $startdate, $enddate)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','1')
						->where('tbl_clients.client_reg_date >= "'.$startdate.'"')
						->where('tbl_clients.client_reg_date <= "'.$enddate.'"')
						->get()
						->row()
						->numrows;
	}
	
	public function count_weeklyunregclients($startdate, $enddate)
	{
		
		
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','0')
						->where('tbl_clients.client_unreg_date >= "'.$startdate.'"')
						->where('tbl_clients.client_unreg_date <= "'.$enddate.'"')
						->get()
						->row()
						->numrows;
	}
	
	public function count_totalweeklyclients($startdate, $enddate)
	{
		
		
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','1')
						//->where('tbl_clients.client_reg_date >= "'.$startdate.'"')
						->where('tbl_clients.client_reg_date <= "'.$enddate.'"')
						->get()
						->row()
						->numrows;
	}
	
	public function count_totalweeklyclients_by_branch($branch, $startdate, $enddate)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_clients')
						->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_clients.deleted','0')
						->where('tbl_clients.client_status','1')
						//->where('tbl_clients.client_reg_date >= "'.$startdate.'"')
						->where('tbl_clients.client_reg_date < "'.$enddate.'"')
						->get()
						->row()
						->numrows;
	}
	
	public function count_weekly_pembiayaan_by_branch($branch, $startdate, $enddate)
	{
		return $this->db->select("count(*) as numrows")
						->from('tbl_pembiayaan')
						->join('tbl_clients', 'tbl_clients.client_id = tbl_pembiayaan.data_client', 'left')
						->where('tbl_pembiayaan.deleted','0')
						->where('tbl_pembiayaan.data_status','1')
						->where('tbl_pembiayaan.data_date_accept >= "'.$startdate.'"')
						->where('tbl_pembiayaan.data_date_accept <= "'.$enddate.'"')
						->like('tbl_clients.client_branch',$branch)
						->get()
						->row()
						->numrows;
	}
	
	public function count_pembiayaan_aktif($ke, $branch='')
	{
		$query= $this->db->query("SELECT * FROM  tbl_pembiayaan JOIN tbl_clients ON tbl_clients.client_id=tbl_pembiayaan.data_client WHERE tbl_clients.client_branch LIKE '%$branch%' AND tbl_pembiayaan.deleted='0' AND tbl_pembiayaan.data_status='1' AND tbl_pembiayaan.data_ke='$ke'");
		return $query->num_rows();  
	}
	
	public function sum_pembiayaan_aktif($ke, $branch='')
	{
		$query= $this->db->query("SELECT SUM(data_plafond) as totals FROM  tbl_pembiayaan JOIN tbl_clients ON tbl_clients.client_id=tbl_pembiayaan.data_client WHERE tbl_clients.client_branch LIKE '%$branch%' AND tbl_pembiayaan.deleted='0' AND tbl_pembiayaan.data_status='1' AND tbl_pembiayaan.data_ke='$ke'");
		return $query->row()->totals;  
	}
	public function sum_margin($ke, $branch='')
	{
		$query= $this->db->query("SELECT SUM(data_margin) as totals FROM  tbl_pembiayaan JOIN tbl_clients ON tbl_clients.client_id=tbl_pembiayaan.data_client WHERE tbl_clients.client_branch LIKE '%$branch%' AND tbl_pembiayaan.deleted='0' AND tbl_pembiayaan.data_status='1' AND tbl_pembiayaan.data_ke='$ke'");
		return $query->row()->totals;  
	}
	public function count_anggota_aktif_pembiayaan($branch='')
	{
		$query= $this->db->query("SELECT * FROM  tbl_clients WHERE client_pembiayaan_status = '1' AND client_branch LIKE '%$branch%' AND client_status='1' AND deleted='0'");
		return $query->num_rows();  
	}
	public function count_monitoring_pembiayaan($branch='')
	{
		$query= $this->db->query("SELECT * FROM tbl_clients JOIN tbl_pembiayaan ON tbl_pembiayaan.data_id=tbl_clients.client_pembiayaan_id WHERE tbl_clients.client_pembiayaan_status = '1' AND tbl_clients.client_branch LIKE '%$branch%' AND tbl_clients.client_status='1' AND tbl_clients.deleted='0' AND tbl_pembiayaan.data_monitoring_pembiayaan='1'");
		return $query->num_rows();  
	}
	public function count_anggota_aktif_menabung($branch='')
	{
		$query= $this->db->query("SELECT * FROM  tbl_clients WHERE client_pembiayaan_status = '0' AND client_branch = '$branch' AND client_status='1' AND deleted='0'");
		return $query->num_rows();  
	}
	
	public function count_anggota_keluar($branch='')
	{
		$query= $this->db->query("SELECT * FROM  tbl_clients WHERE client_branch LIKE '%$branch%' AND client_status='0' AND deleted='0'");
		return $query->num_rows();  
	}
	
	
	public function count_sektor_pembiayaan($sector,$branch='')
	{
		$query= $this->db->query("SELECT * FROM  tbl_pembiayaan JOIN tbl_clients ON tbl_clients.client_id=tbl_pembiayaan.data_client WHERE tbl_clients.client_branch LIKE '%$branch%' AND tbl_pembiayaan.deleted='0' AND tbl_pembiayaan.data_status='1' AND  tbl_pembiayaan.data_sector='$sector'");
		return $query->num_rows();  
	}
	
	
	public function count_par($par,$branch='')
	{
		//$query= $this->db->query("SELECT * FROM tbl_pembiayaan JOIN tbl_clients ON tbl_clients.client_pembiayaan_id=tbl_pembiayaan.data_id WHERE tbl_clients.client_branch LIKE '%$branch%' AND tbl_pembiayaan.deleted='0' AND tbl_pembiayaan.data_status='1' AND  tbl_pembiayaan.data_par='$par' AND tbl_clients.client_pembiayaan_status='1' ");
		$query= $this->db->query("SELECT * FROM  tbl_clients JOIN tbl_pembiayaan ON tbl_pembiayaan.data_id=tbl_clients.client_pembiayaan_id WHERE client_pembiayaan_status = '1' AND client_branch LIKE '%$branch%' AND client_status='1' AND tbl_pembiayaan.deleted='0' AND  tbl_pembiayaan.data_par='$par'");
		return $query->num_rows();  
	}
	public function count_par_13($par,$branch='')
	{
		$query= $this->db->query("SELECT * FROM tbl_pembiayaan JOIN tbl_clients ON tbl_clients.client_id=tbl_pembiayaan.data_client WHERE tbl_clients.client_branch LIKE '%$branch%' AND tbl_pembiayaan.deleted='0' AND tbl_pembiayaan.data_status='1' AND  tbl_pembiayaan.data_par > '12' AND tbl_clients.client_pembiayaan_status='1' ");
		return $query->num_rows();  
	}
	
	public function get_pembiayaan_par($branch='')
	{
		return $this->db->select('*')
					->from('tbl_pembiayaan')
					->join('tbl_clients', 'tbl_clients.client_id = tbl_pembiayaan.data_client', 'left')
					->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
					->where("tbl_group.group_branch LIKE '%$branch%'")
					->where('tbl_pembiayaan.deleted','0')
					->where('tbl_pembiayaan.data_par != 0')
					->where('tbl_clients.client_pembiayaan_status','1')
					->where('tbl_pembiayaan.data_status','1')
					->order_by('client_id','DESC')
					->get()
					->result();
	}
	public function get_pembiayaan_par_13($branch='')
	{
		return $this->db->select('*')
					->from('tbl_pembiayaan')
					->join('tbl_clients', 'tbl_clients.client_id = tbl_pembiayaan.data_client', 'left')
					->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
					->where("tbl_group.group_branch LIKE '%$branch%'")
					->where('tbl_pembiayaan.deleted','0')
					->where('tbl_pembiayaan.data_par > 12')
					->where('tbl_clients.client_pembiayaan_status','1')
					->where('tbl_pembiayaan.data_status','1')
					->order_by('client_id','DESC')
					->get()
					->result();
	}
	
	public function count_weekly_transaction($startdate, $enddate)
	{
		return $this->db->select("count(tr_id) as numrows")
						->from('tbl_transaction')
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date >= "'.$startdate.'"')
						->where('tbl_transaction.tr_date < "'.$enddate.'"')
						->get()
						->row()
						->numrows;
	}
	
	public function count_weekly_kehadiran($startdate, $enddate)
	{
		return $this->db->select("sum(tr_absen_h) as numrows")
						->from('tbl_transaction')
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date >= "'.$startdate.'"')
						->where('tbl_transaction.tr_date < "'.$enddate.'"')
						->get()
						->row()
						->numrows;
	}
	
	public function count_weekly_kehadiran_by_branch($branch, $startdate, $enddate)
	{
		return $this->db->select("sum(tr_absen_h) as numrows")
						->from('tbl_transaction')
						->join('tbl_group', 'tbl_group.group_id = tbl_transaction.tr_group', 'left')
						->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
						->where('tbl_branch.branch_id',$branch)
						->where('tbl_transaction.deleted','0')
						->where('tbl_transaction.tr_date >= "'.$startdate.'"')
						->where('tbl_transaction.tr_date < "'.$enddate.'"')
						->get()
						->row()
						->numrows;
	}
	
	public function count_weekly_saving()
	{
		return $this->db->select("sum(Tab_Wajib) as numrows")
						->from('view_regpyd')
						
						->get()
						->row()
						->numrows;
	}
	
	//get pangajuan
	public function count_pengajuan($startdate,$enddate)
	{
		return $this->db	->select("count(data_id) as numrows")
							->from('tbl_pembiayaan')
							->where('tbl_pembiayaan.data_status','2')
							->where('tbl_pembiayaan.deleted','0')
							->where('tbl_pembiayaan.data_tgl >=',$startdate)
							->where('tbl_pembiayaan.data_tgl <=',$enddate)
							->where('tbl_pembiayaan.deleted','0')
							->get()
							->row()
							->numrows;
	}
	
	public function count_total_pengajuan($startdate,$enddate)
	{
		return $this->db	->select("sum(data_plafond) as numrows")
							->from('tbl_pembiayaan')
							->where('tbl_pembiayaan.data_status','2')
							->where('tbl_pembiayaan.deleted','0')
							->where('tbl_pembiayaan.data_tgl >=',$startdate)
							->where('tbl_pembiayaan.data_tgl <=',$enddate)
							->where('tbl_pembiayaan.deleted','0')
							->get()
							->row()
							->numrows;
	}
	
	//get pencairan
	public function count_pencairan($startdate,$enddate)
	{
		return $this->db	->select("count(data_id) as numrows")
							->from('tbl_pembiayaan')
							->where('tbl_pembiayaan.data_status','1')
							->where('tbl_pembiayaan.data_status_pengajuan','v')
							->where('tbl_pembiayaan.deleted','0')
							->where('tbl_pembiayaan.data_date_accept >=',$startdate)
							->where('tbl_pembiayaan.data_date_accept <=',$enddate)
							->where('tbl_pembiayaan.deleted','0')
							->get()
							->row()
							->numrows;
	}
	public function count_total_pencairan($startdate,$enddate)
	{
		return $this->db	->select("sum(data_plafond) as numrows")
							->from('tbl_pembiayaan')
							->where('tbl_pembiayaan.data_status','1')
							->where('tbl_pembiayaan.data_status_pengajuan','v')
							->where('tbl_pembiayaan.deleted','0')
							->where('tbl_pembiayaan.data_date_accept >=',$startdate)
							->where('tbl_pembiayaan.data_date_accept <=',$enddate)
							->where('tbl_pembiayaan.deleted','0')
							->get()
							->row()
							->numrows;
	}
	
}