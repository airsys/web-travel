<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_payment extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('subquery');
	}
	
	function insert_bank(){
		$rek_number = preg_replace('/[^0-9]/', "", $this->input->post('rek_number'));
		$data=array(
				"company"=>$this->session->userdata('company'),
				"rek number"=> $rek_number,
				"bank"=>$this->input->post('bank'),
				"account name"=>$this->input->post('account_name'),
				"admin"=>1,
		);
		$this->db->insert('acc bank',$data);
		return ($this->db->affected_rows()>0) ? $this->db->insert_id() : FALSE;
	}
	
	function insert_topup($id_bank){
		$date = date_create();
		$data=array(
				"company"=>$this->session->userdata('company'),
				"nominal"=>$this->input->post('nominal'),
				"unique"=>$this->input->post('unique'),
				"bank to"=>$this->input->post('id_bank_to'),
				"created"=>$date->getTimestamp(),
				"bank from"=>$id_bank,
		);
		$this->db->insert('acc topup',$data);
		$this->_set_status_topup($this->db->insert_id(),'pending');
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
	
	private function _set_status_topup($id_topup,$status){
		$date = date_create();
		$data=array(
				"status"=>$status,
				"time status"=>$date->getTimestamp(),
				"id topup"=>$id_topup,
		);
		$this->db->insert('acc topup status',$data);
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
	
	private function _get_saldo(){
		$get_saldo = $this->db->where("company",$this->session->userdata('company'))
	 				 ->order_by('id','desc')
	 				 ->limit(0,1)
	 				 ->get("acc balance")->row();
	 	if(empty($get_saldo->balance)){
			return 0;
		}else return $get_saldo->balance;
	}
	
	function topup_list(){
		$this->db->select(" t.id, t.company, t.nominal, t.`unique`, t.`bank from`, t.bank to,s.time status, s.`status`")
				 ->from("acc topup AS t, acc topup status AS s")
				 ->where("s.id topup = t.id")
				 ->where('company',$this->session->userdata('company'))
				 ->where("status='pending'")
				 ->order_by('s.time status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time status')->from('acc topup status')->where('id topup = t.id');
		$this->subquery->end_subquery('s.time status');
		return $this->db->get()->result_array();
	}
	
	function topup_list_detail($id_topup){
		$data = [];
		$this->db->select(" t.id, t.company, t.nominal, t.`unique`, t.bank from, t.bank to")
				 ->from("acc topup AS t")
				 ->where("t.id",$id_topup)
				 ->where('t.company',$this->session->userdata('company'));
		$data['topup']= $this->db->get()->result();
		$this->db->select("status,time status")
				 ->from("acc topup status")
				 ->where("`id topup`",$id_topup)
				 ->order_by('time status', 'desc');
		$data['status']=$this->db->get()->result();
		return $data;
	}
	
	function topup_change_status($status=''){
		return $this->_set_status_topup($this->input->post('id'),$status);
	}
	
	function issued($id_booking , $nta){
		//dalam issued: merubah booking status & menambah row di acc balance dengan kode D
		$this->load->model('m_booking');
		if($this->m_booking->set_status_booking($id_booking,'issued')){ //<- merubah booking status
			$date = date_create();
			$saldo = $this->_get_saldo();
			$data=array(
					"company"=>$this->session->userdata('company'),
					"nominal"=>$nta,
					"created"=>$date->getTimestamp(),
					"code"=>'DI',
					"pay for"=>$id_booking,
					"balance"=>$saldo-$nta,
			);
			$this->db->insert('acc balance',$data); //<-menambah row di payment topup
		}
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
	
	function get_saldo(){
		return $this->_get_saldo();
	}
	
	function change_status_bank($id,$enable){
		//$data = ($this->input->post('status')=='false') ? 0 : 1;
		$this->db->where('id', $id)
				 ->where('id_user',$this->session->userdata('user_id'));
		$this->db->update('payment_bank', array('enable'=>$enable));
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}

	
}