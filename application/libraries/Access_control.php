<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Access_control
*
* Author: Ali Fahmi PN
*		  afahmi@amartha.co.id
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/Amartha-MF/mis
*
* Created:  17.07.2015
*
* Description:  Provide Access Control List & Logging Overview of AMARTHA MIS.
*
* Requirements: PHP5 or above
*
*/

class Access_control {

	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->model('access_control_model', 'access_model');
	}

	public function hello(){
		return 'Hello, World';
	}

	public function log_activity($data=NULL)
    {
      	return $this->CI->access_model->activity_logging($data);
    }
 
    public function check_access($user_level, $mod, $cont, $func)
    {
        return $this->CI->access_model->access_checking($user_level, $mod, $cont, $func);
    }

}