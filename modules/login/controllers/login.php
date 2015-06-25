<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Front_Controller {

	public function __construct()
	{
		parent::__construct();		
		$this->template->set_layout('signin');
		$this->load->model('user_model');
	}

	public function index()
	{
		$this->template	->set('menu_title', '')
						->set('project', $project )
						->set('module', 'login' )
						->build('login');	
		
	}
  
	public function checklogin()
	{
		//Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
		
		if($this->form_validation->run() == FALSE)
		{
			//Field validation failed.  User redirected to login page
			$this->template->set('menu_title', 'Login')
			   			   ->build('login');	
		}
		else
		{
			//Field validation succeeded.  Validate against database
			$username = $this->input->post('username');
			$password = md5($this->input->post('password'));
			
			//query the database
			$result = $this->user_model->login($username, $password);
				
			if($result)
			{
					$user_fullname = $result[0]->fullname;
					$user_branch = $result[0]->user_branch;
					$user_level = $result[0]->user_level;
					$user_branch_name = $result[0]->branch_name;
					$user_id = $result[0]->user_id;
					
					$this->session->set_userdata('logged_in', TRUE);
					$this->session->set_userdata('user_fullname', $user_fullname);
					$this->session->set_userdata('user_branch', $user_branch);
					$this->session->set_userdata('user_branch_name', $user_branch_name);
					$this->session->set_userdata('user_id', $user_id);
					$this->session->set_userdata('user_level', $user_level);
				
				//Go to Success Page
				redirect('dashboard', 'refresh');
			}
			else
			{
				//Go to Login Page
				$this->session->set_flashdata('message', 'Invalid username or password');			
				redirect('login', 'refresh');
			}
				 
		}
	}
  

  
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('login', 'refresh');
	}

}

?>