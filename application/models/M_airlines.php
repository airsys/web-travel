<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_airlines extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('subquery');
	}
	
	function booking_save($data){
		$data = array(
	        'id_user' => $data['id_user'],
	        'identity' => $data['identity'],
	        'booking_code' => $data['booking_code'],
		);
		$this->db->insert('booking_save', $data);
	}
	
	private function _set_status_booking($id_flight,$status){
		$this->db->where('id_flight',$id_flight);
		$this->db->where('status',$status);
		$this->db->from('booking_status');
		if($this->db->count_all_results()<1){
			$date = date_create();
			$data=array(
					"status"=>$status,
					"time_status"=>$date->getTimestamp(),
					"id_flight"=>$id_flight,
			);
			$this->db->insert('booking_status',$data);
		}
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
	
	function booking_update($data, $id_user, $booking_code){
		$data_update = array(
	        'id_flight' => $data['id_flight'],
	        'booking_time' => date("Y-m-d H:i:s", $data['booking_time']),
	        'time_limit'=> date("Y-m-d H:i:s", $data['time_limit']),
			'base_fare'=> $data['base_fare'],
			'NTA'=> $data['NTA'],
			'name'=> $data['name'],
			'phone'=> $data['phone'],
			'area_depart'=> $data['area_depart'],
			'area_arrive'=> $data['area_arrive'],
			'payment_status'=> $data['payment_status'],
			'airline'=> $data['airline'],
			'infant'=> $data['infant'],
			'child'=> $data['child'],
			'adult'=> $data['adult'],
		);
		$this->db->where('id_user', $id_user)
				 ->where('booking_code', $booking_code);
		$this->db->update('booking_save', $data_update);
		
		$this->_insert_passenger_list($data['passenger_list'],$data['id_flight']);
		$this->_insert_flight_list($data['flight_list'],$data['id_flight']);		
		$this->_set_status_booking($data['id_flight'],'booking');
	}
	
	function retrieve_list($data_or=NULL, $id_flight=NULL){
		if($data_or!=NULL){
			foreach ($data_or as $val){
				$this->db->like($val['key'], $val['val']);
			}
		}
		if($id_flight!=NULL){
			$this->db->where('b.id_flight',$id_flight);
		}
		$this->db->select(" b.*, `status`,time_status")
				 ->from("booking_save b, booking_status s")
				 ->where("b.id_flight = s.id_flight")
				 ->where('id_user',$this->session->userdata('user_id'))
				 ->order_by('s.time_status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time_status')->from('booking_status')->where('id_flight = b.id_flight');
		$this->subquery->end_subquery('s.time_status');
		return $this->db->get()->result();
	}
	
	function get_status_booking($booking_code){
		$this->db->select(" `status`,time_status")
				 ->from("booking_save b, booking_status s")
				 ->where("b.id_flight = s.id_flight")
				 ->where("booking_code",$booking_code)
				 ->where('id_user',$this->session->userdata('user_id'))
				 ->order_by('s.time_status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('booking_time')->from('booking_save')->where('booking_code',$booking_code);
		$this->subquery->end_subquery('booking_time');
		return $this->db->get()->result();
	}
	
	private function _insert_passenger_list($data, $id_flight){
		$data_insert = [];
		$i=0;
		$this->db->where('id_flight',$id_flight);
		$this->db->from('passenger_list');
		if($this->db->count_all_results()<1){
			foreach($data as $val){
				$i++;
				$data_insert[$i]=array(
					'name'=>$val->name,
					'passenger_type'=>$val->passenger_type,
					'ticket_no'=>$val->ticket_no,
					'birth_date'=>$val->birth_date,
					'id_flight'=>$id_flight,
				);
			}
			$this->db->insert_batch('passenger_list',$data_insert);
		}
	}
	
	private function _insert_flight_list($data, $id_flight){
		$data_insert = [];
		$i=0;
		$this->db->where('id_flight',$id_flight);
		$this->db->from('flight_list');
		if($this->db->count_all_results()<1){
			foreach($data as $val){
				$i++;
				$data_insert[$i]=array(
					'date_arrive'=>$val->date_arrive,
					'date_depart'=>$val->date_depart,
					'area_depart'=>$val->area_depart,
					'area_arrive'=>$val->area_arrive,
					'time_depart'=>$val->time_depart,
					'time_arrive'=>$val->time_arrive,
					'id_flight'=>$id_flight,
					'flight_id'=>$val->flight_id,
				);
			}
			$this->db->insert_batch('flight_list',$data_insert);
		}
	}
}