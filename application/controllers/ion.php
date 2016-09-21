<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ion extends CI_Controller {
	 function __construct() {
	     parent::__construct();
	 }
	 
	 function coba(){
	 	$this->db->select('*')
	 			->where('id',1)
	 			->get('booking');
	 }
}