<?php

class Simpanan extends Front_Controller{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		if($this->session->userdata('logged_in'))
		{
			$this->template	->set('menu_title', 'Simpanan')
						->build('simpanan');
		}
		else
		{
		  //If no session, redirect to login page
		  redirect('login', 'refresh');
		}
	}
	
}