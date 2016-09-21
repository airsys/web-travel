<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
	function __construct() {
	     parent::__construct();
	     $this->load->model('m_report');
	 }
	 
	 function sales(){
	 	$data_table = $this->m_report->sales();
	 	$data = array('content'=>'report/sales',
					  'data_table'=>$data_table,
					  'data_detail'=>NULL,
					);
	 	$this->load->view("index",$data);
	 }
}