<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_setting extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/* BANK */
	function change_status_bank(){
		$data = ($this->input->post('status')==0) ? 0 : 1;
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('acc bank', array('enable'=>$data));
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
	
	function insert_bank(){
		$data['rek number']=$this->input->post('rek_number');
		$data['account name']=$this->input->post('account_name');
		$data['bank']=$this->input->post('bank');
		$data['company']=0;
		$data['admin']=0;
		$data['enable']=1;
		$this->db->insert('acc bank', $data);
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
}