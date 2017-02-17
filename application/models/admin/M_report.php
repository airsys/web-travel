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
				 ->where("`status` != 'verified'")
				 ->where("`status` != 'unverified'")
				 ->order_by('s.time status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time status')->from('booking status')
				->where('`id booking` = b.id')
				->where("`status` != 'verified'")
				->where("`status` != 'unverified'");
		$this->subquery->end_subquery('s.time status');
		return $this->db->get()->result();
	}
	
	function get_status_booking($booking_code, $id_flight){
		$this->db->select(" `status`,time status")
				 ->from("booking as b, `booking status` as s")
				 ->where("`b`.`id` = s.`id booking`")
				 ->where("`booking code`",$booking_code)
				 ->where("`id flight`",$id_flight)
				 ->where("`status` != 'verified'")
				 ->where("`status` != 'unverified'")
				 ->order_by('s.`time status`','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('booking time')->from('booking')
				->where('`booking code`',$booking_code)
				->where("`status` != 'verified'")
				->where("`status` != 'unverified'");
		$this->subquery->end_subquery('booking time');
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
							if(`code`='CT' OR `code`='CP',nominal,0)as credit,
							if(`code`='DI' OR `code`='DP',nominal,0)as debet,
							`pay for`,from_unixtime(b.created  ,'%d-%m-%Y %h:%i:%s') as created")
				 ->from("acc balance AS b, auth company AS c")
				 ->where('b.company = c.id');
		return $this->db->get()->result();
	}
	
	function finance_payfor($from , $to){
		$payfor=[];
		$payfor['CT']=listData('acc topup','id','unique',"where `created` BETWEEN $from AND $to");
		$payfor['DI']=listData('booking','id','booking code',"where `booking time` BETWEEN $from AND $to");
		$payfor['DP']=listData('ppob trx','id','msisdn',"where `created` BETWEEN $from AND $to");
		$payfor['CP']=$payfor['DP'];
		return $payfor;
	}
	
	function topup_detail($id_topup){
		$data = [];
		$this->db->select(" t.id, t.company, t.nominal, t.`unique`, t.bank from, t.bank to")
				 ->from("acc topup AS t")
				 ->where("t.id",$id_topup);
		$data['topup']= $this->db->get()->result();
		$this->db->select("status,time status")
				 ->from("acc topup status")
				 ->where("`id topup`",$id_topup)
				 ->order_by('time status', 'desc');
		$data['status']=$this->db->get()->result();
		return $data;
	}
	
	function sales_list($data_or=NULL,$betwen=NULL,$data_where=NULL){
		if($data_or!=NULL){
			foreach ($data_or as $val){
				$this->db->like($val['key'], $val['val'],FALSE);
			}
		}

		if($data_where!=NULL){
			foreach ($data_where as $key=> $val2){
				$this->db->where($key, $val2,FALSE);
			}
		}

		if($betwen!=NULL){
			$this->db->where($betwen);
		}
		$this->db->select(" b.*, `status`,time status, brand")
				 ->from("booking AS b, booking status AS s, auth company AS c")
				 ->where("b.id = s.id booking")
				 ->where("c.id = b.company")
				 ->where("status",'issued')
				 ->order_by('s.time status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time status')->from('booking status')->where('`id booking` = b.id');
		$this->subquery->end_subquery('s.time status');
		return $this->db->get()->result();
	}
	
	function sales_ppob($data_or=NULL,$betwen=NULL,$data_where=NULL){
		if($betwen!=NULL){
			$this->db->where($betwen);
		}
		$this->db->select(" p.*,operator, nilai, markup, markup_default, brand")
				 ->from("ppob pulsa AS p, ppob status AS s, ppob product AS pr, auth company as c")
				 ->where("p.id = s.id_ppob")
				 ->where("p.product = pr.kode")
				 ->where("status != 'failed'")
				 ->where("status != 'refund'")
				 ->where('c.id = p.company')
				 ->order_by('s.created','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('created')->from('ppob status')->where('`id_ppob` = p.id');
		$this->subquery->end_subquery('s.created');
		return $this->db->get()->result();
	}
	
}