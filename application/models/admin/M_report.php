<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_report extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('subquery');
	}
	
	function sales($betwen =NULL,$data_where=NULL){
		if($data_where!=NULL){
			foreach ($data_where as $key=> $val2){
				$this->db->where($key, $val2,FALSE);
			}
		}
		if($betwen!=NULL){
			$this->db->where($betwen);
		}
		$this->db->select(" b.*, `brand`,time status")
				 ->from("booking AS b, booking status AS s, auth company AS c")
				 ->where("b.id = s.id booking")
				 ->where("b.company = c.id")
				 ->where("status",'issued')
				 ->order_by('s.time status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time status')->from('booking status')->where('`id booking` = b.id');
		$this->subquery->end_subquery('s.time status');
		return $this->db->get()->result();
	}
	
	function finance($betwen =NULL,$data_where=NULL){
		if($data_where!=NULL){
			foreach ($data_where as $key=> $val2){
				$this->db->where($key, $val2,FALSE);
			}
		}
		if($betwen!=NULL){
			$this->db->where($betwen);
		}
		$this->db->select(" b.id, `code`, brand,
							if(`code`='CT',nominal,0)as credit,
							if(`code`='DI',nominal,0)as debet,
							`pay for`,from_unixtime(b.created  ,'%d-%m-%Y %h:%i:%s') as created")
				 ->from("acc balance AS b, auth company AS c")
				 ->where('b.company = c.id');
		return $this->db->get()->result();
	}
	
	function finance_payfor($from , $to){
		$payfor=[];
		$payfor['CT']=listData('acc topup','id','unique',"where `created` BETWEEN $from AND $to");
		$payfor['DI']=listData('booking','id','booking code',"where `booking time` BETWEEN $from AND $to");
		
		return $payfor;
	}
}