<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name        : Access_Control Model
* Author      : Ali Fahmi PN
*		        afahmi@amartha.co.id
* Location    : http://github.com/Amartha-MF/mis2015
* Created     : 17.07.2015
* Description : Provide Access Control List & Logging Overview of AMARTHA MIS
**/

class access_control_model extends MY_Model
{
	protected $table_activity_log        = 'tbl_activity_log';
	protected $table_access_control      = 'tbl_access_control';
    protected $module                    = 'access_module';
    protected $controller                = 'access_controller';
    protected $method                    = 'access_method';
    protected $level          			 = 'access_level_max';

	public function __construct() {
		parent::__construct();
	}

	public function activity_logging($data=NULL)
    {
        return $this->db->insert($this->table_activity_log, $data);
    }
 
    public function access_checking($lvl, $mod, $cont, $func)
    {
        return $this->db->select('access_id, access_level_max, access_privilege, access_user_binding')
                        ->from($this->table_access_control)
                        ->where($this->module, $mod)
                        ->where($this->controller, $cont)
                        ->where($this->method, $func)
                        ->where('access_level_max >=', $lvl)
                        ->where('deleted', 0)
                        ->get()
                        ->row();
    }

}