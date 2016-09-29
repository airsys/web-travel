<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Helper extends CI_Controller {
	function __construct() {
	   parent::__construct();
	}
	
	function log ($json){	 	
		if($json=='view'){
			$data =	$this->db->select('*')
					->order_by('id','desc')
					->get('system log')
					->result();
			echo'<pre>';print_r($data);
		}else{
			$data = array(
				'log'=>$json,
			);
			$this->db->insert('system log',$data);
		}
	 }
	 
	 /**
	 	"no":123456,
		"":"HLP",
		"name_airport":"Halim Perdana",
		"city":"Jakarta",
		"country":"Indonesia",
		"id_country":"ID",
		"code_country":832,
		"Type":"Domestik"
	 */
	 function json_airport(){
	 	$data =	$this->db->select('iata as code_route, name as name_airport,
	 							  city')
	 				->where('country',"Indonesia")
	 				->where('active',"1")
	 				->where('iata!=',"")
					->order_by('iata','asc')
					->get('airport')
					->result();
		//echo '</pre>';print_r($data);
		echo json_encode($data);
	 }
}