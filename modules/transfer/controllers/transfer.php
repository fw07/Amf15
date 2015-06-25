<?php

class Transfer extends Front_Controller{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->template	->set('menu_title', 'Transfer')
						->build('transfer');	
	}
	
}