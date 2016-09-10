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
	
	function change_status_bank(){
		$hasil['message'] = 'any error';
		$hasil['data']=0;
		$code = 400;
		if($this->m_setting->change_status_bank()){
			$hasil['message'] = 'status changed';
			$hasil['data']=1;
			$code = 200;
		}
		return $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($hasil));
	}
}