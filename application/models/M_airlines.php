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
		$data = array(
	        'id_flight' => $data['id_flight'],
	        'booking_time' => $data['booking_time']
		);
		$this->db->where('id_user', $id_user)
				 ->where('booking_code', $booking_code);
		$this->db->update('booking_save', $data);
	}
}