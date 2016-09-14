<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->helper(array('dropdown'));
		$this->load->model('admin/m_setting');
	}
	
	/* BANK */
	function bank($action=''){
		$data_view = array(
			'content'=>'setting/bank',
			'bank'=>listDataCustom('payment_bank','id','rek_number,bank,account_name,enable',"where type=0 order by id desc"),
		);		
		$this->load->view("admin/index",$data_view);	
	}
	
	function bank_add(){
		if($this->input->post()){
			if($this->m_setting->insert_bank()){
				redirect('admin/setting/bank','refresh');
			}
		}
		$data_view = array(
			'content'=>'setting/bank_add',
		);		
		$this->load->view("admin/index",$data_view);	
	}
	
	function bank_detail($id){
		if(isset($_POST) && !empty($_POST)){
			$this->load->model('m_payment');
			$this->m_setting->change_status_bank();
			redirect('admin/setting/bank/','refresh');
		} else{
			$data_view = array(
						'content'=>'setting/bank_detail',
						'bank'=> listDataCustom('payment_bank','id','bank,account_name,rek_number,enable',"where type = 0 and id = $id"),
						'id'=>$id,
			);
			$this->load->view("admin/index",$data_view);	
		}
	}
}