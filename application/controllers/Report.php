<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
	function __construct() {
	     parent::__construct();
	     $this->load->model('m_report');
	 }
	 
	 function sales(){
	 	$array_range = NULL;
	 	if($this->input->get('range')){
			$range = str_replace(' ', '', $this->input->get('range'));
			$range = (explode("-",$range));
			$rangf = strtotime(str_replace('/', '-', $range[0]));
			$rangt = strtotime(str_replace('/', '-', $range[1]));
			$array_range = array(
				"`time status` BETWEEN $rangf AND $rangt",
			);
		}else{
			redirect ('report/sales?range='.date('d/m/Y', strtotime('-30 days')).' - '.date('d/m/Y'),'redirect');
		}
	 	$data_table = $this->m_report->sales($array_range);
	 	$data = array('content'=>'report/sales',
					  'data_table'=>$data_table,
					  'date_range'=>$this->input->get('range'),
					  'data_detail'=>NULL,
					);
	 	$this->load->view("index",$data);
	 }
}