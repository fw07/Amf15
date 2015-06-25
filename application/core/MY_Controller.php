<?php


class Base_controller extends MX_Controller{
    
    public function __construct() {
        parent::__construct();
        
        if(ENVIRONMENT == 'development'){
            $this->output->enable_profiler(TRUE);
        }
        
        //load themes library
        $this->load->spark('template/1.9.0');
        
        //form validation for callback working properly
        //$this->load->library('form_validation');
        //$this->form_validation->CI =& $this;
    }
    
}

class Rest_Controller extends MX_Controller{
    
    private $_data;
    private $_format;
    public $xml_head = 'response';
    
    //private $_per_page;
    //public $_db_page;
   // public $_app_page;
    
    
    function __construct() {
        parent::__construct();
    }
    
    
    /**
     * Rest API Response
     * 
     * @param array $data
     * @return json/xml
     */
    public function getResponse($data,$format){
        $this->_data = $data;
        $this->_format = $format;
        switch($this->_format){
            case 'json':
                return $this->to_json();
                break;
            case 'xml':
                return $this->to_xml();
                break;
            default:
                return 'Error. Try Again';
        }
    }
    
    /**
     * Convert array to json
     * @param  $data 
     */
    private function to_json(){
        return json_encode($this->_data);
    }
    
    /**
     * Convert array to xml
     * param $data
     */  
    private function to_xml(){
        //print_r($this->_data);
        header("Content-Type: text/xml");
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= "<$this->xml_head>";
        $xml .= $this->xml_add_child($this->_data);
        $xml .= "</$this->xml_head>";
        return $xml;
    }
    
    private function xml_add_child($data=null,$pk=null){
        $xml = '';
        foreach($data as $k => $v){
            if(!is_numeric($k)){
                $xml .= "<{$k}>";
            }elseif($pk != "news"){
                $xml .= "<{$this->getSingular($pk)}>";
            }else{
                $xml .= "<{$pk}>";
            }
            if(is_array($v))
                $xml .= $this->xml_add_child($v,$k);
            else
                $xml .= "<![CDATA[{$v}]]>";
            
            if(!is_numeric($k)){
                $xml .= "</{$k}>";
            }elseif($pk != "news"){
                $xml .= "</{$this->getSingular($pk)}>";
            }else{
                $xml .= "</{$pk}>";
            }
        }
        return $xml;
    }
    
    public function __raiseStatus($code){
        return array(
                'code'    => $code,
                'message' => $this->__raiseMessage($code)
               );
    }
    
    public function __raiseMessage($code) {
        switch($code){
            case "200": $m = "OK";
                break;
            case "404": $m = "No Data Found";
                break;
            case "500": $m = "This error doesn't exist";
                break;
        }
        return $m;
    }
    
    private function getSingular($word){
        return substr($word,0,strlen($word)-1);
    }
    
}

class Front_Controller extends Base_Controller{
    
    public function __construct() {
        parent::__construct();
        
        $this->template->set_layout('index');		   
        $this->template->set_theme('default');
    }

	public function _breadcumbs($breadcumbs = array()){
    	$this->template->set('breadcumbs', $breadcumbs);
    }
}

class Admin_Controller extends Base_Controller{
    
    public function __construct() {
        parent::__construct();
        
        //pagination
        
        
        //set theme
        $this->template->set_layout('index');
        $this->template->set_theme('admin');
        
        //load library ion auth
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        //authentication first
        //$this->check_login();
    }
    
    public function check_login(){
        if(!$this->ion_auth->logged_in() && !$this->ion_auth->is_admin()){
            //redirect to login
            $this->session->set_flashdata('message', 'error|Please login first.');
            if($this->ion_auth->logout()){
                redirect('admin/login');
            }
        }
    }
    
    
    
}