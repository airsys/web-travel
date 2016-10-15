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
			'bank'=>listDataCustom('acc bank','id','rek number,bank,account name,enable',"where admin=0 order by id desc"),
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
			$this->m_setting->change_status_bank();
			redirect('admin/setting/bank/','refresh');
		} else{
			$data_view = array(
						'content'=>'setting/bank_detail',
						'bank'=> listDataCustom('acc bank','id','bank,account name,rek number,enable',"where admin = 0 and id = $id"),
						'id'=>$id,
			);
			$this->load->view("admin/index",$data_view);	
		}
	}
	
	/**
	* SITE
	*/
	function site(){
		$config['upload_path'] = './assets/dist/img/';
		$config['allowed_types'] = 'jpg';
		$config['max_size']     = '2048';
		$config['max_width'] = '700';
		$config['max_height'] = '200';
		$config['overwrite'] = TRUE;
		$config['file_name'] = 'logo-company';
		$this->load->library('upload', $config);
		if($this->input->post()){
			//die();
			 if ( ! $this->upload->do_upload('logo'))
	        {
	            $error = array('error' => $this->upload->display_errors());
	            $print_r($error);die();
	            //$this->load->view('upload_form', $error);
	        }
	        else
	        {
			    $data = array('upload_data' => $this->upload->data());
			    //$this->load->view('upload_success', $data);
	        }
		}		
		
		$data_view = array(
			'content'=>'setting/site',
		);		
		$this->load->view("admin/index",$data_view);	
	}
}