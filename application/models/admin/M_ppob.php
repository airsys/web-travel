<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_ppob extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('subquery');
	}
	
	function transaction($betwen =NULL,$data_where=NULL){
		if($data_where!=NULL){
			foreach ($data_where as $key=> $val2){
				$this->db->where($key, $val2,FALSE);
			}
		}
		if($betwen!=NULL){
			$this->db->where($betwen);
		}
		
		
		$this->db->select(" c.brand, p.*, s.status, s.note, from_unixtime(p.created  ,'%d-%m-%Y %h:%i:%s') as created2")
				 ->from("`ppob pulsa` AS p, auth company AS c, ppob status as s")
				 ->where('p.company = c.id')
				 ->where('s.`id_ppob` = p.id');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('created')->from('ppob status')
				->where('`id_ppob` = p.id');
		$this->subquery->end_subquery('s.created');
		return $this->db->get()->result();
	}
	
	function finance($id){
		$this->db->select("p.*,c.brand, from_unixtime(created, '%d-%m-%Y %h:%i:%s') as date")
			 ->from('`ppob pulsa` as p, `auth company` as c')
			 ->where('`p.id`',$id)
			 ->where('p.company=c.id');
		$r = $this->db->get()->row();
		return $r ;
	}
	
	function update_pulsa($data_f){
		$data = array('trxid'=>$data_f['trxid'], 
					  'status'=>$data_f['status'], 'created'=>now());
		$this->db->where('ref_trxid', $data_f['ref_trxid']);
		$this->db->update('`ppob pulsa`', $data);
		$msg = '';
		switch($data_f['status']){
			case 1111:
				$msg = 'prosessing';
				break;
			case 0:
				$msg = 'succes';
				break;
			case 2222:
				$msg = 'waiting SN';
				break;
			case 1001:
				$msg = 'refund';
				break;
			default:
				$msg = 'failed';
				break;
		}
		
		$this->change_status($data_f['ref_trxid'],$msg,$data_f['message']);
	}
	
	function change_status($ref_trxid=0,$status='processing',$message=NULL){
		$this->db->select('id')
			 ->from('`ppob pulsa`')
			 ->where('`ref_trxid`',$ref_trxid); 
		$id = $this->db->get()->row();
		$id = $id->id;
		$user = 0;
		if($this->session->userdata('user_id')!=NULL){
			$user = $this->session->userdata('user_id');
		}
		
		$data2 = array('id_ppob'=>$id,
			'status'=>$status,
			'user'=>$user,
			'created'=>now(),
			'note'=>$message,
		);
		$this->db->insert('`ppob status`',$data2);
		$insert_id = $this->db->insert_id();
		
		return $insert_id;
	}
	
	function refund(){
		$this->db->select('id,company')
			 ->from('`ppob pulsa`')
			 ->where('`ref_trxid`',post('ref_trxid')); 
		$id = $this->db->get()->row();
		$company = $id->company;
		$id = $id->id;
		
		$this->db->select('nominal')
			 ->from('`acc balance`')
			 ->where('`code`','DP') 
			 ->where('`pay for`',$id); 
		$harga = $this->db->get()->row();
		$harga = $harga->nominal;
		
		$saldo = saldo($company);
		$data=array(
				"company"=>$company,
				"nominal"=>$harga,
				"created"=>now(),
				"code"=>'CP',
				"pay for"=>$id,
				"balance"=>$saldo+$harga,
		);
		$this->change_status(post('ref_trxid'),'refund',post('note'));
		if ($this->db->insert('acc balance',$data)){
			return TRUE;
		}else{
			return FALSE;
		}; 
	}
	
}