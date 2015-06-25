<?php

class Home extends Front_Controller{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			redirect('dashboard', 'refresh');
		}
		else
		{
			redirect('login', 'refresh');  
		}
	}
	
}
?>