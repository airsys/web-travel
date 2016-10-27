<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ceking extends CI_Controller {
	 function __construct() {
	     parent::__construct();
		 $this->load->helper('dropdown');
		 $this->load->library('subquery');
	 }
	 
	 function name(){
	 	$data = array('content'=>'ceking/nama');
	 	$this->load->view("admin/index",$data);
	 }
	
	 function get_name($offset=0, $limit=1){
	 	$data_r = [];
	 	$this->db->select("b.id, airline,`booking code`,`booking time`, p.name, `status`,time status")
				 ->from("booking AS b, booking status AS s, booking passenger AS p")
				 ->where("b.id = s.id booking")
				 ->where('b.id = p.id booking')
				 ->where('status', 'booking')
				 ->order_by('s.time status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time status')->from('booking status')->where('`id booking` = b.id');
		$this->subquery->end_subquery('s.time status');
		$data = $this->db->get()->result();
		
		foreach($data as $val){
			$passanger[$val->id][] = $val->{'name'};
			$data_r[$val->id] = array('airline'=>$val->airline,
									'booking_code'=>$val->{'booking code'},
									'booking_time'=>$val->{'booking time'},
									'status'=>$val->{'status'},
									'passangers'=>$passanger[$val->id],
								);
		}
		print_r($data_r);
	 }
	 
	 function get_name2($limit=0,$offset=0){
	 	$data_r = [];
	 	$this->db->select("b.id, airline,`booking code`,`booking time`, `status`,time status, 
	 					   `area depart`, `area arrive`")
				 ->from("booking AS b, booking status AS s")
				 ->where("b.id = s.id booking")
				 ->where("status = 'booking'")
				 ->order_by('s.time status','desc')
				 ->limit($limit,$offset);
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time status')->from('booking status')->where('`id booking` = b.id');
		$this->subquery->end_subquery('s.time status');
		$data = $this->db->get()->result();
		$passengers = $this->_get_passengers();
		foreach($data as $val){
			$data_r[$val->id] = array('airline'=>$val->airline,
									'booking_code'=>$val->{'booking code'},
									'booking_time'=>$val->{'booking time'},
									'from'=>$val->{'area depart'},
									'to'=>$val->{'area arrive'},
									'status'=>$val->{'status'},
									'passenger'=>$passengers[$val->id],
									'id'=>$val->id,
								);
		}
		$data_r = json_encode($data_r);
		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($data_r);
		
	 }
	 
	 private function _get_passengers(){
	 	$data_r = [];
	 	$this->db->select("p.`id booking`, `status`,name")
				 ->from("booking passenger AS p, booking status AS s")
				 ->where("p.id booking = s.id booking")
				 ->where("status = 'booking'")
				 ->order_by('s.time status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time status')->from('booking status')->where('`id booking` = s.`id booking`');
		$this->subquery->end_subquery('s.time status');
		$data = $this->db->get()->result();
		
		foreach($data as $val){
			$data_r[$val->{'id booking'}][] = $val->name;
		}
		return $data_r;
	 }
	 
	 function valid(){
	 	$id = $this->input->post('id');
	 	$note = $this->input->post('note');
	 	$status = $this->input->post('status');
	 	$date = date_create();
	 	$data=array(
			"status"=>$status,
			"time status"=>$date->getTimestamp(),
			"id booking"=>$id,
			"note"=>$note,
			"user"=>$this->session->userdata('user_id'),
		);
		$this->db->insert('booking status',$data);	 	
	 	
	 	return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($id);
	 }
}