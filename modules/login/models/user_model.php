<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class user_model extends MY_Model {

    protected $table        = 'tbl_user';
    protected $key          = 'user_id';
    protected $soft_deletes = true;
    
    public function __construct(){
        parent::__construct();
    }

    public function login($username, $password){
    	//$this->db->where('username', $username);
    	//$this->db->where('password', $password);
    	//return $this->db->get($this->table);
		
		return $this->db->query("SELECT * FROM tbl_user LEFT JOIN tbl_branch ON tbl_branch.branch_id=tbl_user.user_branch WHERE username='$username' AND password='$password' ")	
						->result();
    }


}
?>