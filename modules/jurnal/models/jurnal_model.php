<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Clients Model
 * 
 * @package	amartha
 * @author 	fikriwirawan
 * @since	1 December 2013
 */
 
class jurnal_model extends MY_Model {

    protected $table        = 'tbl_jurnal';
    protected $key          = 'jurnal_id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    
    public function __construct()
	{
        parent::__construct();
    }    
	
	
	public function get_all($limit, $offset, $search='')
	{
		if($search != '')
		{
			return $this->db->select('*')
							->from('tbl_jurnal')
							->like('jurnal_desc',$search)
							->limit($limit,$offset)
							->where('deleted','0')
							->order_by('jurnal_id','asc')
							->get()
							->result();
		}else
		{		
			return $this->db->select('*')
						->from('tbl_jurnal')
						->limit($limit,$offset)
						->where('deleted','0')
						->order_by('jurnal_id','asc')
						->get()
						->result();
		}
	}
	
	public function count_all($search)
	{
		return $this->db->select("count(*) as numrows")
						->from($this->table)
						->where('deleted','0')
						->like('jurnal_desc',$search)
						//->or_like('content',$search)
						->get()
						->row()
						->numrows;
	}
}