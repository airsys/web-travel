<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_payment extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('subquery');
	}
	
	function topup_list(){
		$this->db->select(" t.id, t.id_user, u.full_name, t.nominal, t.`unique`, t.id_bank, t.id_bank_to, s.time_status, s.`status`")
				 ->from("payment_topup t, payment_status_topup s, users u")
				 ->where("s.id_topup = t.id")
				 ->where("u.id = t.id_user")
				 ->where("s.status",'submit')
				 ->order_by('s.time_status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time_status')->from('payment_status_topup')->where('id_topup = t.id');
		$this->subquery->end_subquery('s.time_status');
		return $this->db->get()->result();
	}
	
   function topup_list_detail($id_topup){
		$data = [];
		$this->db->select(" t.id, t.id_user, t.nominal, t.`unique`, t.id_bank, t.id_bank_to")
				 ->from("payment_topup t")
				 ->where("t.id",$id_topup);
		$data['topup']= $this->db->get()->result();
		$this->db->select("status,time_status")
				 ->from("payment_status_topup")
				 ->where("id_topup",$id_topup)
				 ->order_by('time_status', 'desc');
		$data['status']=$this->db->get()->result();
		return $data;
	}
	
	private function _set_status_topup($id_topup,$status){
		$date = date_create();
		$data=array(
				"status"=>$status,
				"time_status"=>$date->getTimestamp(),
				"id_topup"=>$id_topup,
		);
		$this->db->insert('payment_status_topup',$data);
		$this->_change_saldo($id_topup,$status);
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
	
	private function _change_saldo($id_topup,$status){
		$get_saldo=0;
		$get_saldo = $this->db->where("id",$id_topup)->get("payment_topup")->row();
		if($status=='confirm'){
			$jml_saldo = $get_saldo->unique+$get_saldo->nominal+$get_saldo->saldo;
			$data_update=array('saldo'=>$jml_saldo);	
			$this->db->where('id',$id_topup);
			$this->db->update('payment_topup',$data_update);		
		}
	}
	
	function topup_change_status($status=''){
		return $this->_set_status_topup($this->input->post('id'),$status);
	}
}