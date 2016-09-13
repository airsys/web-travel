<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_setting extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/* BANK */
	function change_status_bank(){
		$data = ($this->input->post('status')=='false') ? 0 : 1;
		$this->db->where('id', $this->input->post('id_bank'));
		$this->db->update('payment_bank', array('enable'=>$data));
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
	
	function insert_bank(){
		$data = $this->input->post();
		$data['id_user']=0;
		$data['type']=0;
		$data['enable']=1;
		$this->db->insert('payment_bank', $data);
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
}