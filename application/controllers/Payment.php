<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {
	 function __construct() {
	     parent::__construct();
	     $this->load->library(array('form_validation'));	     
		 $this->load->model('m_payment');
	 }
	 
	 function topup(){
	 	$unique = mt_rand(1,999);
	 	$message = '';
	 	$id_bank=FALSE;
	 	if(!$this->ion_auth->logged_in()){
			redirect('auth2/login', 'refresh');
		}
	 	if($this->input->post()){
	 		$data = $this->input->post();
			$this->form_validation->set_rules('nominal', 'nominal', 'required|is_natural_no_zero');
			$this->form_validation->set_rules('unique', 'unique number', 'required|is_natural_no_zero');
			$this->form_validation->set_rules('id_bank_to', 'transfer to', 'required|is_natural');
			if($this->input->post('bank_account')==0){
				$this->form_validation->set_rules('rek_number', 'rekening number', 'required');
				$this->form_validation->set_rules('bank', 'bank', 'required');
				$this->form_validation->set_rules('account_name', 'account name', 'required');
			}
			if ($this->form_validation->run()  == FALSE){
				$message =  validation_errors();
			}else{
				if($this->input->post('bank_account')==0){
					$id_bank = $this->m_payment->insert_bank();
				}else{
					$id_bank = $this->input->post('bank_account');
				}
				if($id_bank!==FALSE){
					if($this->m_payment->insert_topup($id_bank)){
						$message= 'success topup';
						redirect('payment/topup_list','refresh');
					}else{
						$message= 'fail topup';
					}
				}
			}
		}
		$data = array('content'=>'payment/topup',
					  'unique'=>$unique,
					  'message'=>$message,
					  'bank'=>listDataCustom('payment_bank','id','rek_number,bank,account_name','where type=0'),
					  'bank_account'=>listDataCustom('payment_bank','id','rek_number,bank,account_name',"where id_user= ".$this->session->userdata('user_id')),
					);
		$this->load->view("index",$data);
				
	 }
	 
	 function topup_list(){
	 	if(!$this->ion_auth->logged_in()){
			redirect('auth2/login', 'refresh');
		}
	 	$data_select = $this->m_payment->topup_list();
	 	$data = array('content'=>'payment/topup_list',
	 				  'data_table'=>$data_select,
	 				  'bank'=>listDataCustom('payment_bank','id','rek_number,bank,account_name'),
	 			);
	 	$this->load->view("index",$data);
	 }
}