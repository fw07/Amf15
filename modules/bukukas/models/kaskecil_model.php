<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Jurnal Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 February 2013
 */
 
class kaskecil_model extends MY_Model {

    protected $table        = 'tbl_kaskecil';
    protected $key          = 'kaskecil_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	
	public function count_all($search,$branch)
	{
		if($search != '')		{
			return $this->db->select("count(*) as numrows")
							->from('tbl_kaskecil')
							->where('deleted','0')
							->where('kaskecil_cabang',$branch)
							->like('kaskecil'.$key,$search)
							->get()
							->row()
							->numrows;
		}else{	
			return $this->db->select("count(*) as numrows")
							->from('tbl_kaskecil')
							->where('deleted','0')
							->where('kaskecil_cabang',$branch)
							->get()
							->row()
							->numrows;
		}
	}
	
	
	public function get_all($limit, $offset, $search='', $branch='')
	{
	
			return $this->db->select('*')
						->from('tbl_kaskecil')
						->join('tbl_accounting', 'tbl_accounting.accounting_code = tbl_kaskecil.kaskecil_account', 'left')
						->where('tbl_kaskecil.deleted','0')
						->where('tbl_kaskecil.kaskecil_cabang',$branch)
						->limit($limit,$offset)
						->order_by('kaskecil_date','DESC')
						->order_by('kaskecil_id','DESC')
						->get()
						->result();

	}
	public function get_kaskecil_id($id){
		$this->db->where('kaskecil_id', $id);
        return $this->db->get($this->table);    
    }
}