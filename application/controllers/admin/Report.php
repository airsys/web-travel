<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
	
	function __construct() {
	     parent::__construct();
	     $this->load->model('admin/m_report');
	 }
	 
	 function sales(){
	 	$array_range = NULL;
	 	if($this->input->get('range')){
			$range = str_replace(' ', '', $this->input->get('range'));
			$range = (explode("-",$range));
			$rangf = strtotime(str_replace('/', '-', $range[0]));
			$rangt = strtotime(str_replace('/', '-', $range[1]))+86399;
			$array_range = "`time status` BETWEEN $rangf AND $rangt";
		}else{
			redirect ('admin/report/sales?range='.date('d/m/Y', strtotime('-30 days')).' - '.date('d/m/Y'),'redirect');
		}
	 	$data_table = $this->m_report->sales($array_range);
	 	$data = array('content'=>'report/sales',
					  'data_table'=>$data_table,
					  'date_range'=>$this->input->get('range'),
					);
	 	$this->load->view("admin/index",$data);
	 }
	 
	 function finance(){
	 	$this->load->helper('dropdown');
	 	$array_range = NULL;
	 	if($this->input->get('range')){
			$range = str_replace(' ', '', $this->input->get('range'));
			$range = (explode("-",$range));
			$rangf = strtotime(str_replace('/', '-', $range[0]));
			$rangt = strtotime(str_replace('/', '-', $range[1]))+86399;
			$array_range = "`created` BETWEEN $rangf AND $rangt";
			//echo date("Y-m-d H:i:s",$rangf).'|'.date("Y-m-d H:i:s",$rangt);die();
		}else{
			redirect ('admin/report/finance?range='.date('d/m/Y', strtotime('-30 days')).' - '.date('d/m/Y'),'redirect');
		}
	 	$data_table = $this->m_report->finance($array_range);
	 	$data = array('content'=>'report/finance',
					  'data_table'=>$data_table,
					  'payfor'=>$this->m_report->finance_payfor($rangf,$rangt),
					  'date_range'=>$this->input->get('range'),
					);
	 	$this->load->view("admin/index",$data);
	 }
}