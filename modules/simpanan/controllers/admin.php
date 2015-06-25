<?php

/**
 * Format SMS Administration
 *
 * @since       June 18, 2012
 * @package		bni4id
 * @version		1.0
 */

class Admin extends Admin_Controller{
	
	private $per_page 	= '10';
	private $title 		= 'Circle';
	private $module 	= 'project';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->template->set('toolbar_title', 'Tambah Item')
					   ->build('admin/home');
	
	}
	
}