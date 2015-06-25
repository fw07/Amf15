<?php

class Daily extends Front_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Daily';
	private $module 	= 'Daily';
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library('pagination');	
	
	}
	

	public function index($page='0'){
		echo "ok";
	}
	
	public function browse(){
		echo "ok";
	}
	

}