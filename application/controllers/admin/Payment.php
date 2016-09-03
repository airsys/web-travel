<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {
	 function __construct() {
	     parent::__construct();
	     $this->load->library(array('form_validation'));
		 $this->load->helper('dropdown');
		 $this->load->model('admin/m_payment');
	 }
	 
	 function topup_list($id_topup='00'){
	 	if($id_topup=='00' || $id_topup==NULL){
			$data_select = $data_select = $this->m_payment->topup_list();
		 	$data = array('content'=>'payment/topup_list',
		 				  'data_table'=>$data_select,
		 				  'bank'=>listDataCustom('payment_bank','id','rek_number,bank,account_name'),
		 			);
		}elseif($id_topup!=NULL){	
			$data_select = $this->m_payment->topup_list_detail($id_topup);
		 	$data = array('content'=>'payment/topup_list_detail',
		 				  'data_topup'=>$data_select['topup'][0],
		 				  'data_status'=>$data_select['status'],
		 				  'bank'=>listDataCustom('payment_bank','id','rek_number,bank,account_name'),
		 			);
		}
		$this->load->view("admin/index",$data);
	 }
	 
	 function topup_change_status($status=''){
	 	$status_array = array('confirm','reject');	 	
	 	if (in_array($status, $status_array)){
		 	if ($this->m_payment->topup_change_status($status)){
				$hasil['message'] = 'status Changed';
				$hasil['data']=1;
				$code = 200;
			}else{
				$hasil['message'] = 'status Not Changed';
				$hasil['data']=0;
				$code = 400;
			}
		}else{
			$hasil['message'] = 'Status not found';
			$hasil['data']=0;
			$code = 400;
		}
	 	
	 	return $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($hasil));
	 }

}