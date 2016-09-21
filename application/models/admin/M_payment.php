<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_payment extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('subquery');
	}
	
	function topup_list(){
		$this->db->select(" t.id, t.company, u.company, t.nominal, t.`unique`, t.bank from, t.bank to, s.time status, s.`status`")
				 ->from("acc topup as t, acc topup status as s, auth users as u")
				 ->where("s.id topup = t.id")
				 ->where("u.company = t.company")
				 ->where("s.status",'submit')
				 ->order_by('s.time status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time status')->from('acc topup status')->where('`id topup` = t.id');
		$this->subquery->end_subquery('s.time status');
		return $this->db->get()->result();
	}
	
   function topup_list_detail($id_topup){
		$data = [];
		$this->db->select(" t.id, t.company, t.nominal, t.`unique`, t.bank from, t.bank to")
				 ->from("acc topup as t")
				 ->where("t.id",$id_topup);
		$data['topup']= $this->db->get()->result();
		$this->db->select("status,time status")
				 ->from("acc topup status")
				 ->where("id topup",$id_topup)
				 ->order_by('time status', 'desc');
		$data['status']=$this->db->get()->result();
		return $data;
	}
	
	private function _set_status_topup($id_topup,$status){
		$date = date_create();
		$data=array(
				"status"=>$status,
				"time status"=>$date->getTimestamp(),
				"id topup"=>$id_topup,
		);
		$this->db->insert('acc topup status',$data);
		if($status=='confirm'){
			$get_nominal = $this->db->where("id",$id_topup)->get("acc topup")->row();
			$this->_change_saldo($id_topup,$get_nominal->nominal,$get_nominal->company,'CT');
		}
		//$this->_change_saldo($id_topup,$status);
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
	
	private function _change_saldo($id_payfor,$nominal,$company,$code){
		$balance=0;
		$date = date_create();
		$get_balance = $this->db->where("company",$company)
					   ->order_by('created','desc')
					   ->limit(0,1)->get("acc balance")->row();
		if($get_balance!==NULL){
			$balance = $get_balance->balance;
		}
		$data = array(
			'code'=>$code,
			'company'=>$company,
			'nominal'=>$nominal,
			'balance'=>$balance+$nominal,
			'pay for'=>$id_payfor,
			'created'=>$date->getTimestamp(),
		);
		$this->db->insert('acc balance',$data);
	}
	
	function topup_change_status($status=''){
		return $this->_set_status_topup($this->input->post('id'),$status);
	}
}