<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_airlines extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	function booking_save($data){
		$data = array(
	        'id_user' => $data['id_user'],
	        'identity' => $data['identity'],
	        'booking_code' => $data['booking_code'],
		);
		$this->db->insert('booking_save', $data);
	}
	
	function booking_update($data, $id_user, $booking_code){
		$data_update = array(
	        'id_flight' => $data['id_flight'],
	        'booking_time' => $data['booking_time'],
	        'time_limit'=> $data['time_limit'],
			'base_fare'=> $data['base_fare'],
			'NTA'=> $data['NTA'],
			'name'=> $data['name'],
			'phone'=> $data['phone'],
			'area_depart'=> $data['area_depart'],
			'area_arrive'=> $data['area_arrive'],
			'payment_status'=> $data['payment_status'],
			'airline'=> $data['airline'],
		);
		$this->db->where('id_user', $id_user)
				 ->where('booking_code', $booking_code);
		$this->db->update('booking_save', $data_update);
		
		$this->_insert_passenger_list($data['passenger_list'],$data['id_flight']);
		$this->_insert_flight_list($data['flight_list'],$data['id_flight']);
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