<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Switchbranch extends Front_Controller {

	public function __construct()
	{
		parent::__construct();	
		$this->load->model('branch_model');	
	}

	public function index()
	{
		echo "asdasda";
	}
  

  public function cabang()
	{
		if($this->session->userdata('user_level') == 1){
			$user_branch =  $this->uri->segment(3); 
			$branch = $this->branch_model->get_branch($user_branch);		
			$user_branch_name = $branch[0]->branch_name;   
			
			$this->session->unset_userdata('user_branch');
			$this->session->unset_userdata('user_branch_name');
			$this->session->set_userdata('user_branch', $user_branch);
			$this->session->set_userdata('user_branch_name', $user_branch_name);
				
			//Go to dashboard
			redirect('dashboard', 'refresh');
		}else{
			//Go to dashboard
			redirect('dashboard', 'refresh');
		}
	}
}

?>