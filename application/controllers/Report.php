<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
	
	function __construct() {
	     parent::__construct();
	     $this->load->model('m_report');
	     if (!$this->ion_auth->logged_in())
		{
			redirect('airlines/', 'refresh');
		}
	 }
	 
	 function sales(){
	 		$data_or = [];
			$array_range = [];
			$string = explode(",",$this->input->get('q'));
			for($i = 0; $i < count($string); $i++){
				$string2 = explode(":",$string[$i]);
				if(!empty($string2[1]) && !empty($string2[0] && $string2[1]!='')){

					if(preg_replace('/\s+/', '', $string2[0])=='bookingcode'){
					$data_or[$i]=array('val'=>$string2[1], 'key'=>'booking code');
					}
					if(preg_replace('/\s+/', '', $string2[0])=='datebooking'){
						$data_or[$i]=array('val'=>date("Y-m-d", strtotime($string2[1])), 'key'=>'FROM_UNIXTIME(`b`.`booking time`,"%Y-%m-%d")');
					}
					if(preg_replace('/\s+/', '', $string2[0])=='airline'){
						$data_or[$i]=array('val'=>$string2[1], 'key'=>'airline');
					}
					if(preg_replace('/\s+/', '', $string2[0])=='range'){	
						$range = str_replace(' ', '', $this->input->get('q'));
						$range = (preg_split("/[,:-]+/",$range));
						$rangf = strtotime(str_replace('/', '-', $range[1]));
						$rangt = strtotime(str_replace('/', '-', $range[2]))+86399;
						$array_range = "`time status` BETWEEN $rangf AND $rangt";
					}
				}else{
					redirect ('report/sales?q=range:'.date('d/m/Y', strtotime('11/01/2016')).' - '.date('d/m/Y'),'redirect');
				}
			}
			$data_table = $this->m_report->sales_list($data_or,$array_range);
			$data = array('content'=>'report/sales',
					  'data_table'=>$data_table,
					  'data_detail'=>NULL,
					  'date_range'=>$this->input->get('q'),
					);
		$this->load->view("index",$data);
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
			redirect ('report/finance?range='.date('d/m/Y', strtotime('11/01/2016')).' - '.date('d/m/Y'),'redirect');
		}
	 	$data_table = $this->m_report->finance($array_range);
	 	$data = array('content'=>'report/finance',
					  'data_table'=>$data_table,
					  //'payfor'=>$this->m_report->finance_payfor($rangf,$rangt),
					  'date_range'=>$this->input->get('range'),
					);
	 	$this->load->view("index",$data);
	 }
}