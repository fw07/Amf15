<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class area_model extends MY_Model {

    protected $table        = 'tbl_area';
    protected $key          = 'area_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }  
	
	public function get_all(){
		$this->db->where('deleted', '0')
				 ->order_by('area_name', 'ASC');
        return $this->db->get($this->table);    
    }
	
	public function get_area($param){
		$this->db->where('area_id', $param);
        return $this->db->get($this->table);    
    }
	
	public function count_all_area($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->like('area_name',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
	
	public function get_all_area($limit, $offset, $search='')
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_area')
							->where('tbl_area.deleted','0')
							->like('area_name',$search)
							->limit($limit,$offset)
							->order_by('area_code','asc')
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
							->from('tbl_area')
							->where('tbl_area.deleted','0')
							->limit($limit,$offset)
							->order_by('area_code','asc')
							->get()
							->result();
		}
	}
	
	
}