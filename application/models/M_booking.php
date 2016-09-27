<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_booking extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('subquery');
	}
	
	function booking_save($data){
		$data = array(
	        'company' => $data['company'],
	        'identity' => $data['identity'],
	        'booking code' => $data['booking code'],
		);
		$this->db->insert('booking', $data);
	}
	
	private function _set_status_booking($id_booking,$status){
		$this->db->where('`id booking`',$id_booking);
		$this->db->where('status',$status);
		$this->db->from('booking status');
		if($this->db->count_all_results()<1){
			$date = date_create();
			$data=array(
					"status"=>$status,
					"time status"=>$date->getTimestamp(),
					"id booking"=>$id_booking,
					"user"=>$this->session->userdata('user_id'),
			);
			$this->db->insert('booking status',$data);
		}
		return ($this->db->affected_rows()>0) ? TRUE : FALSE;
	}
	
	function set_status_booking($id,$status){
		return $this->_set_status_booking($id,$status);
	}
	
	function booking_update($data, $booking_code){
		$data_update = array(
	        'id flight' => $data['id flight'],
	        'booking time' =>  $data['booking time'],
	        'time limit'=> $data['time limit'],
			'base fare'=> $data['base fare'],
			'tax'=> $data['tax'],
			'NTA'=> $data['NTA'],
			'name'=> $data['name'],
			'phone'=> $data['phone'],
			'area depart'=> $data['area depart'],
			'area arrive'=> $data['area arrive'],
			'airline'=> $data['airline'],
			'infant'=> $data['infant'],
			'child'=> $data['child'],
			'adult'=> $data['adult'],
		);
		$this->db->where('company', $this->session->userdata('company'))
				 ->where("`booking code`", $booking_code)
				 ->order_by('id')
				 ->limit(0,1);
		$this->db->update('booking', $data_update);
		
		$this->db->where("`id flight`",$data['id flight']);
		$id_booking = $this->db->get('booking')->row()->id;
		
		$this->_insert_passenger_list($data['passenger list'],$id_booking);
		$this->_insert_flight_list($data['flight list'],$id_booking);		
		$this->_set_status_booking($id_booking,'booking');
		return $id_booking;
	}
	
	function retrieve_list($data_or=NULL, $data_where=NULL){
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
		$this->db->select(" b.*, `status`,time status")
				 ->from("booking AS b, booking status AS s")
				 ->where("b.id = s.id booking")
				 ->where('company',$this->session->userdata('company'))
				 ->order_by('s.time status','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('time status')->from('booking status')->where('`id booking` = b.id');
		$this->subquery->end_subquery('s.time status');
		return $this->db->get()->result();
	}
	
	function get_status_booking($booking_code){
		$this->db->select(" `status`,time status")
				 ->from("booking as b, `booking status` as s")
				 ->where("`b`.`id` = s.`id booking`")
				 ->where("`booking code`",$booking_code)
				 ->where('`user`',$this->session->userdata('user_id'))
				 ->order_by('s.`time status`','desc');
		$sub = $this->subquery->start_subquery('where');
		$sub->select_max('booking time')->from('booking')->where('`booking code`',$booking_code);
		$this->subquery->end_subquery('booking time');
		return $this->db->get()->result();
	}
	
	private function _insert_passenger_list($data, $id_booking){
		$data_insert = [];
		$i=0;
		$this->db->where('id booking',$id_booking);
		$this->db->from('booking passenger');
		if($this->db->count_all_results()<1){
			foreach($data as $val){
				$i++;
				$data_insert[$i]=array(
					'name'=>$val->name,
					'passenger type'=>$val->passenger_type,
					'ticket no'=>$val->ticket_no,
					'birth date'=>$val->birth_date,
					'id booking'=>$id_booking,
				);
			}
			$this->db->insert_batch('booking passenger',$data_insert);
		}
	}
	
	private function _insert_flight_list($data, $id_booking){
		$data_insert = [];
		$i=0;
		$this->db->where('id booking',$id_booking);
		$this->db->from('booking flight');
		if($this->db->count_all_results()<1){
			foreach($data as $val){
				//echo $val->date_arrive.' '.$val->time_arrive.":00";die();
				$i++;
				$data_insert[$i]=array(
					//'date arrive'=>$val->date_arrive,
					'class'=>$val->code,
					'airport depart'=>$val->area_depart,
					'airport arrive'=>$val->area_arrive,
					'time depart'=>strtotime($val->date_depart.' '.$val->time_depart.":00"),
					'time arrive'=>strtotime($val->date_arrive.' '.$val->time_arrive.":00"),
					'id booking'=>$id_booking,
					'flight number'=>$val->flight_id,
				);
			}
			$this->db->insert_batch('booking flight',$data_insert);
		}
	}
}