<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Airlines extends CI_Controller {
	private $url ;
	 function __construct() {
	        parent::__construct();
	    $this->load->library('curl');		
		$this->load->library(array('form_validation'));
    	$this->config->load('api');
		$this->curl->http_header('token', $this->config->item('api-token'));
		$this->curl->option('TIMEOUT', 70000);
		$this->load->model('m_airlines');
		$this->url = 'http://52.36.25.143:8989/lion';	
		$this->url = $this->config->item('api-url') . 'lion';
	 }
	 
	 function search(){		
		$data = $this->input->post();
		$this->form_validation->set_rules('from', 'Asal Keberangkatan', 'required');
		$this->form_validation->set_rules('to', 'Tujuan', 'required');
		
		$hasil = array();
		$code = 200; //$this->form_validation->run() 
		if ($this->form_validation->run() == FALSE)
		{
			$hasil =  validation_errors();
			$code = 400;
		}else{
			$json = $this->curl->simple_get("$this->url/search?from=$data[from]&to=$data[to]&date=$data[date]&adult=$data[adult]&child=$data[child]&infant=$data[infant]");
			//$json = $this->jsondata();		
			$array = json_decode ($json);
			//print_r("$this->url/search?from=$data[from]&to=$data[to]&date=$data[date]&adult=$data[adult]&child=$data[child]&infant=$data[infant]");
			
			if( ( empty($array) || $array->code==404 || $array->code==204) ){
				$code = 404;
				$hasil = 'tidak ada penerbangan';
			} else{
				foreach ($array->results->data as $val){
					$segment = array();
					$i = 0;
					foreach ($val->detail as $val_detail){
							$seat = array();
							++$i;
							$segment[$i] =  array (
											'airline_icon' => $val_detail->airline_icon,
											'flight_id' => $val_detail->flight_id,
											'time_depart' => $val_detail->time_depart,
											'time_arrive' => $val_detail->time_arrive,
											'date_depart' => $val_detail->date_depart,
											'date_arrive' => $val_detail->date_arrive,
											'area_depart' => $val_detail->area_depart,
											'area_arrive' => $val_detail->area_arrive,
											'seat' => $seat,
										);
							foreach ($val_detail->seat as $val_seat){				
								$seat[$val_seat->code] = array(
													'available'=>$val_seat->available,
													'class'=>$val_seat->class,
													'flight_key'=>$val_seat->flight_key,
								);
								$segment[$i]['seat'] = $seat ;
								$hasil["$val->id_perjalanan"] = array ('flight_count' => $val->flight_count, 
																 'segment'=> $segment
														);
							}
					}
				}
				$hasil = json_encode($hasil);
			}
			
		}
	return $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output($hasil);
	}
	
	function get_bestprice(){		
		$data = $this->input->post();
		$this->form_validation->set_rules('from', 'Asal Keberangkatan', 'required');
		$this->form_validation->set_rules('to', 'Tujuan', 'required');
		
		$hasil = array();
		$code = 200; //$this->form_validation->run() 
		if ($this->form_validation->run()  == FALSE)
		{
			$hasil =  validation_errors();
			$code = 400;
		}else{
			$json = $this->curl->simple_get("$this->url/search/best_price?from=$data[from]&to=$data[to]&date=$data[date]&adult=$data[adult]&child=$data[child]&infant=$data[infant]");
			//$json = $this->jsonbestprice();		
			$array = json_decode ($json);
			//print_r("");die();
			
			if( ( empty($array) || $array->code==404 || $array->code==204) ){
				if(empty($array)){
					$code = 400;
				} else{
					$code = $array->code;
				}
				$hasil = 'terdapat kesalahan sistem';
			} else{
				foreach ($array->results->data as $key => $val){
					$hasil[$val->id_perjalanan] = array ('airline_icon'=>$val->detail[0]->airline_icon,
														 'area_depart'=>$val->detail[0]->area_depart,
														 'area_arrive'=>$val->detail[0]->area_arrive,
														 'time_depart'=>$val->detail[0]->time_depart,
														 'time_arrive'=>$val->detail[0]->time_arrive,
														 'flight_count'=>$val->flight_count,
														 'id_perjalanan'=>$val->id_perjalanan,
														 
														 'available'=>$val->detail[0]->seat[0]->available,
														 'class'=>$val->detail[0]->seat[0]->class,
														 'flight_key'=>$val->detail[0]->seat[0]->flight_key,
														 'fare'=>$val->detail[0]->seat[0]->best_price->fare,
														 'tax'=>$val->detail[0]->seat[0]->best_price->tax,
														 'segment'=>$val->detail[0]->flight_list,
					 							 );
				}
				//print_r($hasil);die();
				$hasil = json_encode($hasil);
			}
			
		}
	return $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output($hasil);
	}
	
	function get_fare(){
		$data = $this->input->post();
		$key = '';
		//$this->form_validation->set_rules('key[]', 'KEY[]', 'required');
		for($i = 1; $i <= count($data['key'])-1; $i++){
			$key .= '|'.$data['key'][$i];
		}
		$key = substr($key, 1);
		$json = $this->curl->simple_get("$this->url/get_price?flight_key=$key");
		//$json = $this->getfare();
		
		$array = json_decode ($json);
		$hasil = array();
		$code = 200; //$this->form_validation->run() 
		if (TRUE== FALSE)
		{
			$hasil =  validation_errors();
			$code = 400;
		}else{
			if( ( empty($array) || $array->code==404 || $array->code==204) ){
				$code = 404;
				$hasil = 'seat not available';
				if(empty($array)){
					$code = 404;
				}
			} else{
				$hasil = array('fare'=>$array->results->fare, 
								'tax'=>$array->results->tax, 
								'total_price'=>$array->results->total_price,
								'flight_key'=>$key,);
				$hasil = json_encode($hasil);
			}
			
		}
	return $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output($hasil);
		
	}
	
	function get_form(){	
		$hasil = $this->jsongetform();		
		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($hasil);
	}
	
	function booking($bestprice=0){
		$data = $this->input->post();
		//print_r($data);die();
		//parse_str(utf8_decode(urldecode($data['data'])), $output);
		$hasil = array();
		$hasil['key'] = '';
		foreach($data as $key => $val){
			/*for($i = 1; $i <= sizeof($data['flightid']); $i++){
				if(!empty($data[$key][$i])){
					$hasil['seat'][$i][$key] = $data[$key][$i];
					if($key == 'key'){
						$hasil['key'] .= $data['key'][$i]. '|';
					}
				}else */
				{
					$hasil[$key] = $data[$key];
				}
			//}			
		}
		if($bestprice != 0){
			$hasil['key'] = $data['key'][1];
		}
		//$hasil['segmen'] = sizeof($data['flightid']);
		//print_r($hasil);die();
		$data = array('content'=>'airlines/booking', 
					  'title'=>'Booking', 
					  'data'=>$hasil
					  );
		$this->load->view("index",$data);
	}
	
	function booking_save(){
		$data = $this->input->post();
		$tables = $this->config->item('tables','ion_auth');
		unset($data['identity']);
		unset($data['password']);
		unset($data['identity']);
		
		unset($data['full_name']);
		unset($data['email']);
		unset($data['phone']);
		unset($data['password']);
		unset($data['password_confirm']);
		
		unset($data['position']);
		
		$this->form_validation->set_rules('contact_title', 'contact title', 'required');
		$this->form_validation->set_rules('contact_name', 'contact name', 'required');
		$this->form_validation->set_rules('contact_phone', 'contact phone', 'required');
		if (!$this->ion_auth->logged_in() && $this->input->post('position')=='lo'){
			$this->form_validation->set_rules('identity', 'email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'password', 'required');
		}
		if (!$this->ion_auth->logged_in() && $this->input->post('position')=='re'){
			$this->form_validation->set_rules('full_name', 'full_name', 'required');
			$this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
			$this->form_validation->set_rules('phone', 'phone', 'required|trim|numeric');
			$this->form_validation->set_rules('password_register', 'password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', 'password_confirm', 'required');
		}
		
		$hasil = '';
		$code = 200; //$this->form_validation->run() 
		if ($this->form_validation->run()  == FALSE)
		{
			$hasil =  validation_errors();
			$code = 400;
		}else{
			if (!$this->ion_auth->logged_in() && $this->input->post('position')=='lo'){
				$remember = (bool) $this->input->post('remember');
				$this->ion_auth->login($this->input->post('identity'), $this->input->post('password'),$remember);
				
			}
			if (!$this->ion_auth->logged_in() && $this->input->post('position')=='re'){
				$identity_column = $this->config->item('identity','ion_auth');
				$email    = strtolower($this->input->post('email'));
		        $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
		        $password = $this->input->post('password_register');

		        $additional_data = array(
		            'full_name' => $this->input->post('full_name'),
		            'phone'      => $this->input->post('phone'),
		        );
		        $this->ion_auth->register($identity, $password, $email, $additional_data);
		        $this->ion_auth->login($identity, $password, TRUE);
			}
			if ($this->ion_auth->logged_in()){
				$json = $this->curl->simple_post("$this->url/book", $data, array(CURLOPT_BUFFERSIZE => 10, CURLOPT_TIMEOUT=>800000));
				//$json = $this->jsonbooking();
				
				$array = json_decode ($json);
				//print_r($array);die();
				if( ( ! empty($array) && $array->code==200) ){
					$hasil = $array->results->booking_code;
					$data = array(
				        'id_user' => $this->session->userdata('user_id'),
				        'identity' => $this->session->userdata('identity'),
				        'booking_code' => $array->results->booking_code,
					);
					$this->m_airlines->booking_save($data);
					
				} else{
					$hasil = $array->results;
				}
				$code = $array->code;
			} else{
				$code = 400;
				$hasil = $this->ion_auth->errors();
			}
		}
		return $this->output
	            ->set_content_type('text/html')
	            ->set_status_header($code)
	            ->set_output($hasil);
	}
	
	function retrieve($code='00'){
		if(!$this->ion_auth->logged_in()){
			redirect('airlines','refresh');
		}
		$bandara = $this->_bandara();
		$array = NULL;
		$data_table = NULL;
		if($code != '00' && !$this->input->get()){
			$array = $this->_boking_detail($code);
			/* update booking */
			if($array != NULL && $this->ion_auth->logged_in()){
				$data_update = array(
			        'id_flight' => $array->id,
			        'booking_time' => $array->booking_time,
			        'time_limit'=> $array->time_limit,
					'base_fare'=> $array->base_fare,
					'tax'=> $array->tax,
					'NTA'=> $array->NTA,
					'name'=> $array->name,
					'phone'=> $array->phone,
					'area_depart'=> $array->area_depart,
					'area_arrive'=> $array->area_arrive,
					'payment_status'=> $array->payment_status,
					'airline'=> $array->airline,
					'flight_list'=> $array->flight_list,				
					'passenger_list'=> $array->passenger_list,				
					'child'=> $array->child,				
					'infant'=> $array->infant,				
					'adult'=> $array->adult,				
				);		
				$this->m_airlines->booking_update($data_update, $this->session->userdata('user_id'),$code);
			 }
			
			$data = array('content'=>'airlines/retrieve',
					  'data_detail'=>$array,
					  'status'=>$this->m_airlines->get_status_booking($code),
					  'data_table'=>NULL,
					  'bandara'=>$bandara,
					);
		}elseif(!$this->input->get()){
			$data_table = $this->m_airlines->retrieve_list();
			$data = array('content'=>'airlines/retrieve',
					  'data_table'=>$data_table,
					  'data_detail'=>NULL,
					);
		}else{
			$data_or = [];
			$string = explode(",",$this->input->get('q'));
			for($i = 0; $i < count($string); $i++){
				$string2 = explode(":",$string[$i]);
				if(!empty($string2[1]) && !empty($string2[0] && $string2[1]!='')){
					if(preg_replace('/\s+/', '', $string2[0])=='bookingcode'){
					$data_or[$i]=array('val'=>$string2[1], 'key'=>'booking_code');
					}
					if(preg_replace('/\s+/', '', $string2[0])=='contactname'){
						$data_or[$i]=array('val'=>$string2[1], 'key'=>'name');
					}
					if(preg_replace('/\s+/', '', $string2[0])=='datebooking'){
						$data_or[$i]=array('val'=>date("Y-m-d", strtotime($string2[1])), 'key'=>'booking_time');
					}
				}elseif($string2[1]!=''){
					$data_or[$i]=array('val'=>$string2[0], 'key'=>'booking_code');
				}
			}
			$data_table = $this->m_airlines->retrieve_list($data_or);
			$data = array('content'=>'airlines/retrieve',
					  'data_table'=>$data_table,
					  'data_detail'=>NULL,
					);
		}
				
		$this->load->view("index",$data);
	}
	
	private function _boking_detail($code){
		$plorp  = substr(strrchr($this->url,'/'), 1);
		$this->url = substr($this->url, 0, - strlen($plorp));
		$json = $this->curl->simple_get($this->url."manage/book/$code");
		$json = json_decode($json);
		if(empty($json->error))	return $json->results;
			else return NULL;
	}
	
	private function _bandara(){
		$str = file_get_contents(base_url().'assets/ajax/iata_bandara.json');
		$bandara = json_decode($str,TRUE);
		$return = array();
		foreach($bandara as $val){
			$return[$val['code_route']] = $val;
		}
		return $return;
	}
	
	function index(){
		$data = array(
					'content'=>'airlines/search',
					'title'=>'',		
				);
		$this->load->view("index",$data);
	}
	function search_bestprice(){
		$data = array('content'=>'airlines/search_bestprice');
		$this->load->view("index",$data);
	}
	
	
	
	function jsongetform(){
		return '{
  "code": 200,
  "results": {
    "contact_phone_other": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "adult_name_3": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ],
    "infant_special_request_1": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ],
    "adult_id_2": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "child_title_1": {
      "mandatory": true,
      "values": [
        "Mstr",
        "Miss"
      ],
      "field_type": "combobox"
    },
    "child_title_2": {
      "mandatory": true,
      "values": [
        "Mstr",
        "Miss"
      ],
      "field_type": "combobox"
    },
    "adult_id_1": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "contact_name": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ],
    "child_name_1": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ],
    "child_name_2": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ],
    "adult_id_3": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "adult_name_2": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ],
    "adult_name_1": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ],
    "child_special_request_2": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "child_special_request_1": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "infant_special_request_2": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ],
    "contact_phone": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ],
    "child_id_1": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "child_id_2": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "adult_title_3": {
      "mandatory": true,
      "values": [
        "Mr",
        "Mrs",
        "Ms"
      ],
      "field_type": "combobox"
    },
    "adult_title_2": {
      "mandatory": true,
      "values": [
        "Mr",
        "Mrs",
        "Ms"
      ],
      "field_type": "combobox"
    },
    "adult_title_1": {
      "mandatory": true,
      "values": [
        "Mr",
        "Mrs",
        "Ms"
      ],
      "field_type": "combobox"
    },
    "infant_id_2": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "infant_id_1": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "contact_title": {
      "mandatory": true,
      "values": [
        "Mr",
        "Mrs",
        "Ms"
      ],
      "field_type": "combobox"
    },
    "infant_title_1": {
      "mandatory": true,
      "values": [
        "Mstr",
        "Miss"
      ],
      "field_type": "combobox"
    },
    "infant_title_2": {
      "mandatory": true,
      "values": [
        "Mstr",
        "Miss"
      ],
      "field_type": "combobox"
    },
    "adult_special_request_1": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "adult_special_request_2": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "adult_special_request_3": {
      "mandatory": false,
      "values": "",
      "field_type": "textbox"
    },
    "infant_birth_date_2": [
      {
        "mandatory": true,
        "values": "dd-mm-yyyy",
        "field_type": "date"
      }
    ],
    "infant_birth_date_1": [
      {
        "mandatory": true,
        "values": "dd-mm-yyyy",
        "field_type": "date"
      }
    ],
    "infant_name_2": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ],
    "infant_name_1": [
      {
        "mandatory": true,
        "values": "",
        "field_type": "textbox"
      }
    ]
  }
}';
	}
	
	function jsondata(){
		return '{
  "code": 200,
  "results": {
    "data_ret": [],
    "data": [
      {
        "id_perjalanan": "lion_1",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "07:45",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "10:30",
            "flight_id": "JT 670",
            "area_arrive": "BPN",
            "seat": [
              {
                "available": 7,
                "code": "X",
                "flight_key": "R0dPc0RtUmtLMExqSzFaa0JLa0ZHR09zRG1Sa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTdThKVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd0Qxc1JXdW9UeWVwVFNqTEo0ZlpHTjZabU84CkZ5RHRBd3Bqc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtUmtLMExqSzFaa0JVa0ZHR09zRG1Sa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd0Qxc1JXdW9UeWVwVFNqTEo0ZlpHTjZabU84CkZ5RHRBd3Bqc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtUmtLMExqSzFaa0Eza0ZHR09zRG1Sa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd0Qxc1JXdW9UeWVwVFNqTEo0ZlpHTjZabU84CkZ5RHRBd3Bqc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUmtLMExqSzFaa0Fha0ZHR09zRG1Sa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd0Qxc1JXdW9UeWVwVFNqTEo0ZlpHTjZabU84CkZ5RHRBd3Bqc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUmtLMExqSzFaa0FLa0ZHR09zRG1Sa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd0Qxc1JXdW9UeWVwVFNqTEo0ZlpHTjZabU84CkZ5RHRBd3Bqc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUmtLMExqSzFaa0FVa0ZHR09zRG1Sa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd0Qxc1JXdW9UeWVwVFNqTEo0ZlpHTjZabU84CkZ5RHRBd3Bqc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUmtLMExqSzFaa1oza0ZHR09zRG1Sa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd0Qxc1JXdW9UeWVwVFNqTEo0ZlpHTjZabU84CkZ5RHRBd3Bqc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUmtLMExqSzFaa1pha0ZHR09zRG1Sa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd0Qxc1JXdW9UeWVwVFNqTEo0ZlpHTjZabU84CkZ5RHRBd3Bqc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUmtLMExqSzFaa1pLa0ZHR09zRG1Sa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd0Qxc1JXdW9UeWVwVFNqTEo0ZlpHTjZabU84CkZ5RHRBd3Bqc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUmtLMExqSzFaa1pVa0ZHR09zRG1Sa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd0Qxc1JXdW9UeWVwVFNqTEo0ZlpHTjZabU84CkZ5RHRBd3Bqc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUmtLMExqSzFaNXNTV0FaUzlRWkdTc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZBUUk4RHpTZm5KZ2pMS091b3Zqa1pRYm1aVWtYCklQTjJBbU84WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUmtLMExqSzFaNHNTV0FaUzlRWkdTc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZBUUk4RHpTZm5KZ2pMS091b3Zqa1pRYm1aVWtYCklQTjJBbU84WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUmtLMExqSzFaM3NTV0FaUzlRWkdTc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZBUUk4RHpTZm5KZ2pMS091b3Zqa1pRYm1aVWtYCklQTjJBbU84WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUmtLMExqSzFaMnNTV0FaUzlRWkdTc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZBUUk4RHpTZm5KZ2pMS091b3Zqa1pRYm1aVWtYCklQTjJBbU84WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUmtLMExqSzFaMXNTV0FaUzlRWkdTc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZBUUk4RHpTZm5KZ2pMS091b3Zqa1pRYm1aVWtYCklQTjJBbU84WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "11:20",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "BPN",
            "time_arrive": "16:00",
            "flight_id": "JT 933",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 5,
                "code": "X",
                "flight_key": "R0dPc0RtUmtLMExrSzFaa0JLa0ZHR09zRG1Sa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqMXNTdThKVWtYSVVrUExKa2NuM091cFRTaFlRUmtCd1Zqc1IxeU1UU2hWUmcxTEprdVZSNXVvS0hmClpHTDZaUU84RnlEdEJHWm1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtUmtLMExrSzFaa0JVa0ZHR09zRG1Sa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrUExKa2NuM091cFRTaFlRUmtCd1Zqc1IxeU1UU2hWUmcxTEprdVZSNXVvS0hmClpHTDZaUU84RnlEdEJHWm1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtUmtLMExrSzFaa0Eza0ZHR09zRG1Sa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrUExKa2NuM091cFRTaFlRUmtCd1Zqc1IxeU1UU2hWUmcxTEprdVZSNXVvS0hmClpHTDZaUU84RnlEdEJHWm1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUmtLMExrSzFaa0Fha0ZHR09zRG1Sa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrUExKa2NuM091cFRTaFlRUmtCd1Zqc1IxeU1UU2hWUmcxTEprdVZSNXVvS0hmClpHTDZaUU84RnlEdEJHWm1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUmtLMExrSzFaa0FLa0ZHR09zRG1Sa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrUExKa2NuM091cFRTaFlRUmtCd1Zqc1IxeU1UU2hWUmcxTEprdVZSNXVvS0hmClpHTDZaUU84RnlEdEJHWm1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUmtLMExrSzFaa0FVa0ZHR09zRG1Sa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrUExKa2NuM091cFRTaFlRUmtCd1Zqc1IxeU1UU2hWUmcxTEprdVZSNXVvS0hmClpHTDZaUU84RnlEdEJHWm1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUmtLMExrSzFaa1oza0ZHR09zRG1Sa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrUExKa2NuM091cFRTaFlRUmtCd1Zqc1IxeU1UU2hWUmcxTEprdVZSNXVvS0hmClpHTDZaUU84RnlEdEJHWm1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUmtLMExrSzFaa1pha0ZHR09zRG1Sa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrUExKa2NuM091cFRTaFlRUmtCd1Zqc1IxeU1UU2hWUmcxTEprdVZSNXVvS0hmClpHTDZaUU84RnlEdEJHWm1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUmtLMExrSzFaa1pLa0ZHR09zRG1Sa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrUExKa2NuM091cFRTaFlRUmtCd1Zqc1IxeU1UU2hWUmcxTEprdVZSNXVvS0hmClpHTDZaUU84RnlEdEJHWm1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUmtLMExrSzFaa1pVa0ZHR09zRG1Sa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrUExKa2NuM091cFRTaFlRUmtCd1Zqc1IxeU1UU2hWUmcxTEprdVZSNXVvS0hmClpHTDZaUU84RnlEdEJHWm1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUmtLMExrSzFaNXNTV0FaUzlRWkdTc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JXdW9UeWVwVFNqTEo0ZlpHUjZad084R0pJeExKNHRGM0l1b1RSdEd6U2dxRmprCkF3YmpaVWtYSVBONVptQThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUmtLMExrSzFaNHNTV0FaUzlRWkdTc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JXdW9UeWVwVFNqTEo0ZlpHUjZad084R0pJeExKNHRGM0l1b1RSdEd6U2dxRmprCkF3YmpaVWtYSVBONVptQThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUmtLMExrSzFaM3NTV0FaUzlRWkdTc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JXdW9UeWVwVFNqTEo0ZlpHUjZad084R0pJeExKNHRGM0l1b1RSdEd6U2dxRmprCkF3YmpaVWtYSVBONVptQThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUmtLMExrSzFaMnNTV0FaUzlRWkdTc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JXdW9UeWVwVFNqTEo0ZlpHUjZad084R0pJeExKNHRGM0l1b1RSdEd6U2dxRmprCkF3YmpaVWtYSVBONVptQThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUmtLMExrSzFaMXNTV0FaUzlRWkdTc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JXdW9UeWVwVFNqTEo0ZlpHUjZad084R0pJeExKNHRGM0l1b1RSdEd6U2dxRmprCkF3YmpaVWtYSVBONVptQThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_2",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "06:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "08:05",
            "flight_id": "JT 561",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 6,
                "code": "X",
                "flight_key": "R0dPc0RtSXNFd09zSG1SNXNTV0FaUzlRQUk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QWFrTHNTdThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtSXNFd09zSG1SNHNTV0FaUzlRQUk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtSXNFd09zSG1SM3NTV0FaUzlRQUk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtSXNFd09zSG1SMnNTV0FaUzlRQUk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtSXNFd09zSG1SMXNTV0FaUzlRQUk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtSXNFd09zSG1SMHNTV0FaUzlRQUk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtSXNFd09zSG1SbXNTV0FaUzlRQUk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtSXNFd09zSG1SbHNTV0FaUzlRQUk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtSXNFd09zSG1Sa3NTV0FaUzlRQUk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtSXNFd09zSG1SanNTV0FaUzlRQUk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtSXNFd09zSG15OEh4MGpLMFoxSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtSXNFd09zSG11OEh4MGpLMFoxSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtSXNFd09zSG1xOEh4MGpLMFoxSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtSXNFd09zSG1NOEh4MGpLMFoxSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtSXNFd09zSG1JOEh4MGpLMFoxSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "12:20",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "14:40",
            "flight_id": "JT 398",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 1,
                "code": "X",
                "flight_key": "R0dPc0RtSXNFd1NzSG1SNXNTV0FaUzlRQUk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004WktrTHNTdThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtSXNFd1NzSG1SNHNTV0FaUzlRQUk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtSXNFd1NzSG1SM3NTV0FaUzlRQUk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtSXNFd1NzSG1SMnNTV0FaUzlRQUk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtSXNFd1NzSG1SMXNTV0FaUzlRQUk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtSXNFd1NzSG1SMHNTV0FaUzlRQUk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtSXNFd1NzSG1SbXNTV0FaUzlRQUk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtSXNFd1NzSG1SbHNTV0FaUzlRQUk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtSXNFd1NzSG1Sa3NTV0FaUzlRQUk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtSXNFd1NzSG1SanNTV0FaUzlRQUk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtSXNFd1NzSG15OEh4MGpLMFoxSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYTEpndXBhRXVZUVJsQndWanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZBUU84CkZ5RHRabXg0c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtSXNFd1NzSG11OEh4MGpLMFoxSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYTEpndXBhRXVZUVJsQndWanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZBUU84CkZ5RHRabXg0c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtSXNFd1NzSG1xOEh4MGpLMFoxSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYTEpndXBhRXVZUVJsQndWanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZBUU84CkZ5RHRabXg0c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtSXNFd1NzSG1NOEh4MGpLMFoxSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYTEpndXBhRXVZUVJsQndWanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZBUU84CkZ5RHRabXg0c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtSXNFd1NzSG1JOEh4MGpLMFoxSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYTEpndXBhRXVZUVJsQndWanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZBUU84CkZ5RHRabXg0c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_3",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "06:00",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "07:10",
            "flight_id": "IW 1814",
            "area_arrive": "SUB",
            "seat": [
              {
                "available": 5,
                "code": "Q",
                "flight_key": "R0dPc0RtUmxLMExqSzFaa0Fha0ZHR09zRG1SbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqMXNTUzhIS2tXSTNrWG8ycWRMSmd1cGFFdVlRTjJCd05qc1NBMXB6U3ZMS3l1WVFOM0J3UmpzUnlLClZRUjRaR0U4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUmxLMExqSzFaa0FLa0ZHR09zRG1SbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtXSTNrWG8ycWRMSmd1cGFFdVlRTjJCd05qc1NBMXB6U3ZMS3l1WVFOM0J3UmpzUnlLClZRUjRaR0U4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUmxLMExqSzFaa0FVa0ZHR09zRG1SbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tXSTNrWG8ycWRMSmd1cGFFdVlRTjJCd05qc1NBMXB6U3ZMS3l1WVFOM0J3UmpzUnlLClZRUjRaR0U4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUmxLMExqSzFaa1oza0ZHR09zRG1SbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtXSTNrWG8ycWRMSmd1cGFFdVlRTjJCd05qc1NBMXB6U3ZMS3l1WVFOM0J3UmpzUnlLClZRUjRaR0U4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUmxLMExqSzFaa1pha0ZHR09zRG1SbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tXSTNrWG8ycWRMSmd1cGFFdVlRTjJCd05qc1NBMXB6U3ZMS3l1WVFOM0J3UmpzUnlLClZRUjRaR0U4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUmxLMExqSzFaa1pLa0ZHR09zRG1SbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtXSTNrWG8ycWRMSmd1cGFFdVlRTjJCd05qc1NBMXB6U3ZMS3l1WVFOM0J3UmpzUnlLClZRUjRaR0U4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUmxLMExqSzFaa1pVa0ZHR09zRG1SbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtXSTNrWG8ycWRMSmd1cGFFdVlRTjJCd05qc1NBMXB6U3ZMS3l1WVFOM0J3UmpzUnlLClZRUjRaR0U4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUmxLMExqSzFaNXNTV0FaUzlRWkdXc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUnlLc1JjaU0yY3VuMlNscVRSZlpRTDZaUU84SDNJbExKV3VySlJmWlFwNlpHTzhGSXB0ClpHdGtBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUmxLMExqSzFaNHNTV0FaUzlRWkdXc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUnlLc1JjaU0yY3VuMlNscVRSZlpRTDZaUU84SDNJbExKV3VySlJmWlFwNlpHTzhGSXB0ClpHdGtBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUmxLMExqSzFaM3NTV0FaUzlRWkdXc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUnlLc1JjaU0yY3VuMlNscVRSZlpRTDZaUU84SDNJbExKV3VySlJmWlFwNlpHTzhGSXB0ClpHdGtBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUmxLMExqSzFaMnNTV0FaUzlRWkdXc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUnlLc1JjaU0yY3VuMlNscVRSZlpRTDZaUU84SDNJbExKV3VySlJmWlFwNlpHTzhGSXB0ClpHdGtBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUmxLMExqSzFaMXNTV0FaUzlRWkdXc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUnlLc1JjaU0yY3VuMlNscVRSZlpRTDZaUU84SDNJbExKV3VySlJmWlFwNlpHTzhGSXB0ClpHdGtBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "11:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "SUB",
            "time_arrive": "16:00",
            "flight_id": "JT 973",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUmxLMExrSzFaa0Fha0ZHR09zRG1SbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUmxLMExrSzFaa0FLa0ZHR09zRG1SbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUmxLMExrSzFaa0FVa0ZHR09zRG1SbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUmxLMExrSzFaa1oza0ZHR09zRG1SbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUmxLMExrSzFaa1pha0ZHR09zRG1SbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUmxLMExrSzFaa1pLa0ZHR09zRG1SbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUmxLMExrSzFaa1pVa0ZHR09zRG1SbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUmxLMExrSzFaNXNTV0FaUzlRWkdXc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1NBMXB6U3ZMS3l1WVFSa0J3SGpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR0w2ClpRTzhGeUR0QkdwbXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUmxLMExrSzFaNHNTV0FaUzlRWkdXc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1NBMXB6U3ZMS3l1WVFSa0J3SGpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR0w2ClpRTzhGeUR0QkdwbXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUmxLMExrSzFaM3NTV0FaUzlRWkdXc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1NBMXB6U3ZMS3l1WVFSa0J3SGpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR0w2ClpRTzhGeUR0QkdwbXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUmxLMExrSzFaMnNTV0FaUzlRWkdXc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1NBMXB6U3ZMS3l1WVFSa0J3SGpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR0w2ClpRTzhGeUR0QkdwbXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUmxLMExrSzFaMXNTV0FaUzlRWkdXc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1NBMXB6U3ZMS3l1WVFSa0J3SGpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR0w2ClpRTzhGeUR0QkdwbXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_4",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "06:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "08:05",
            "flight_id": "JT 561",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 6,
                "code": "X",
                "flight_key": "R0dPc0RtcXNFd09zSG1SNXNTV0FaUzlRQTE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QWFrTHNTdThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtcXNFd09zSG1SNHNTV0FaUzlRQTE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtcXNFd09zSG1SM3NTV0FaUzlRQTE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtcXNFd09zSG1SMnNTV0FaUzlRQTE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtcXNFd09zSG1SMXNTV0FaUzlRQTE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtcXNFd09zSG1SMHNTV0FaUzlRQTE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtcXNFd09zSG1SbXNTV0FaUzlRQTE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtcXNFd09zSG1SbHNTV0FaUzlRQTE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtcXNFd09zSG1Sa3NTV0FaUzlRQTE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtcXNFd09zSG1SanNTV0FaUzlRQTE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtcXNFd09zSG15OEh4MGpLMFozSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtcXNFd09zSG11OEh4MGpLMFozSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtcXNFd09zSG1xOEh4MGpLMFozSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtcXNFd09zSG1NOEh4MGpLMFozSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtcXNFd09zSG1JOEh4MGpLMFozSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "12:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "15:10",
            "flight_id": "JT 382",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 4,
                "code": "X",
                "flight_key": "R0dPc0RtcXNFd1NzSG1SNXNTV0FaUzlRQTE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QVVrTHNTdThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtcXNFd1NzSG1SNHNTV0FaUzlRQTE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtcXNFd1NzSG1SM3NTV0FaUzlRQTE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtcXNFd1NzSG1SMnNTV0FaUzlRQTE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtcXNFd1NzSG1SMXNTV0FaUzlRQTE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtcXNFd1NzSG1SMHNTV0FaUzlRQTE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtcXNFd1NzSG1SbXNTV0FaUzlRQTE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtcXNFd1NzSG1SbHNTV0FaUzlRQTE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtcXNFd1NzSG1Sa3NTV0FaUzlRQTE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtcXNFd1NzSG1SanNTV0FaUzlRQTE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtcXNFd1NzSG15OEh4MGpLMFozSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYTEpndXBhRXVZUVJsQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHSDZaR084CkZ5RHRabXRsc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtcXNFd1NzSG11OEh4MGpLMFozSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYTEpndXBhRXVZUVJsQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHSDZaR084CkZ5RHRabXRsc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtcXNFd1NzSG1xOEh4MGpLMFozSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYTEpndXBhRXVZUVJsQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHSDZaR084CkZ5RHRabXRsc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtcXNFd1NzSG1NOEh4MGpLMFozSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYTEpndXBhRXVZUVJsQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHSDZaR084CkZ5RHRabXRsc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtcXNFd1NzSG1JOEh4MGpLMFozSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYTEpndXBhRXVZUVJsQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHSDZaR084CkZ5RHRabXRsc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_5",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "06:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "08:05",
            "flight_id": "JT 561",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 6,
                "code": "X",
                "flight_key": "R0dPc0RtQXNFd09zSG1SNXNTV0FaUzlRWjE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QWFrTHNTdThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtQXNFd09zSG1SNHNTV0FaUzlRWjE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtQXNFd09zSG1SM3NTV0FaUzlRWjE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtQXNFd09zSG1SMnNTV0FaUzlRWjE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtQXNFd09zSG1SMXNTV0FaUzlRWjE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtQXNFd09zSG1SMHNTV0FaUzlRWjE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtQXNFd09zSG1SbXNTV0FaUzlRWjE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtQXNFd09zSG1SbHNTV0FaUzlRWjE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtQXNFd09zSG1Sa3NTV0FaUzlRWjE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtQXNFd09zSG1SanNTV0FaUzlRWjE5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtQXNFd09zSG15OEh4MGpLMFptSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtQXNFd09zSG11OEh4MGpLMFptSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtQXNFd09zSG1xOEh4MGpLMFptSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtQXNFd09zSG1NOEh4MGpLMFptSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtQXNFd09zSG1JOEh4MGpLMFptSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "11:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "14:10",
            "flight_id": "JT 306",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtQXNFd1NzSG1SNHNTV0FaUzlRWjE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtQXNFd1NzSG1SM3NTV0FaUzlRWjE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtQXNFd1NzSG1SMnNTV0FaUzlRWjE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtQXNFd1NzSG1SMXNTV0FaUzlRWjE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtQXNFd1NzSG1SMHNTV0FaUzlRWjE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtQXNFd1NzSG1SbXNTV0FaUzlRWjE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtQXNFd1NzSG1SbHNTV0FaUzlRWjE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtQXNFd1NzSG1Sa3NTV0FaUzlRWjE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtQXNFd1NzSG1SanNTV0FaUzlRWjE5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtQXNFd1NzSG15OEh4MGpLMFptSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYTEpndXBhRXVZUVJrQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZaR084CkZ5RHRabU4yc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtQXNFd1NzSG11OEh4MGpLMFptSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYTEpndXBhRXVZUVJrQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZaR084CkZ5RHRabU4yc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtQXNFd1NzSG1xOEh4MGpLMFptSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYTEpndXBhRXVZUVJrQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZaR084CkZ5RHRabU4yc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtQXNFd1NzSG1NOEh4MGpLMFptSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYTEpndXBhRXVZUVJrQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZaR084CkZ5RHRabU4yc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtQXNFd1NzSG1JOEh4MGpLMFptSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYTEpndXBhRXVZUVJrQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZaR084CkZ5RHRabU4yc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_6",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "09:00",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "10:10",
            "flight_id": "IW 1844",
            "area_arrive": "SUB",
            "seat": [
              {
                "available": 1,
                "code": "Q",
                "flight_key": "R0dPc0RtUm1LMExqSzFaa0Fha0ZHR09zRG1SbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqa3NTUzhIS2tXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "Q"
              },
              {
                "available": 6,
                "code": "N",
                "flight_key": "R0dPc0RtUm1LMExqSzFaa0FLa0ZHR09zRG1SbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqMnNSNThHYWtXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUm1LMExqSzFaa0FVa0ZHR09zRG1SbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUm1LMExqSzFaa1oza0ZHR09zRG1SbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUm1LMExqSzFaa1pha0ZHR09zRG1SbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUm1LMExqSzFaa1pLa0ZHR09zRG1SbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUm1LMExqSzFaa1pVa0ZHR09zRG1SbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUm1LMExqSzFaNXNTV0FaUzlRWkdBc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUnlLc1JjaU0yY3VuMlNscVRSZlpReDZaUU84SDNJbExKV3VySlJmWkdONlpHTzhGSXB0ClpHdDBBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUm1LMExqSzFaNHNTV0FaUzlRWkdBc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUnlLc1JjaU0yY3VuMlNscVRSZlpReDZaUU84SDNJbExKV3VySlJmWkdONlpHTzhGSXB0ClpHdDBBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUm1LMExqSzFaM3NTV0FaUzlRWkdBc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUnlLc1JjaU0yY3VuMlNscVRSZlpReDZaUU84SDNJbExKV3VySlJmWkdONlpHTzhGSXB0ClpHdDBBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUm1LMExqSzFaMnNTV0FaUzlRWkdBc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUnlLc1JjaU0yY3VuMlNscVRSZlpReDZaUU84SDNJbExKV3VySlJmWkdONlpHTzhGSXB0ClpHdDBBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUm1LMExqSzFaMXNTV0FaUzlRWkdBc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUnlLc1JjaU0yY3VuMlNscVRSZlpReDZaUU84SDNJbExKV3VySlJmWkdONlpHTzhGSXB0ClpHdDBBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "11:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "SUB",
            "time_arrive": "16:00",
            "flight_id": "JT 973",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUm1LMExrSzFaa0Fha0ZHR09zRG1SbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUm1LMExrSzFaa0FLa0ZHR09zRG1SbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUm1LMExrSzFaa0FVa0ZHR09zRG1SbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUm1LMExrSzFaa1oza0ZHR09zRG1SbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUm1LMExrSzFaa1pha0ZHR09zRG1SbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUm1LMExrSzFaa1pLa0ZHR09zRG1SbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUm1LMExrSzFaa1pVa0ZHR09zRG1SbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrR3FLV3VMelM1TEZqa1pHYjFaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVIyCkJ3TmpzUmNIVlF4M1ozamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUm1LMExrSzFaNXNTV0FaUzlRWkdBc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1NBMXB6U3ZMS3l1WVFSa0J3SGpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR0w2ClpRTzhGeUR0QkdwbXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUm1LMExrSzFaNHNTV0FaUzlRWkdBc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1NBMXB6U3ZMS3l1WVFSa0J3SGpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR0w2ClpRTzhGeUR0QkdwbXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUm1LMExrSzFaM3NTV0FaUzlRWkdBc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1NBMXB6U3ZMS3l1WVFSa0J3SGpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR0w2ClpRTzhGeUR0QkdwbXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUm1LMExrSzFaMnNTV0FaUzlRWkdBc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1NBMXB6U3ZMS3l1WVFSa0J3SGpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR0w2ClpRTzhGeUR0QkdwbXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUm1LMExrSzFaMXNTV0FaUzlRWkdBc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1NBMXB6U3ZMS3l1WVFSa0J3SGpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR0w2ClpRTzhGeUR0QkdwbXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_7",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "06:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "08:05",
            "flight_id": "JT 561",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 6,
                "code": "X",
                "flight_key": "R0dPc0RtVmtLMExqSzFaa0JLa0ZHR09zRG1Wa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqMnNTdThKVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjJCd0hqc1JjdW4yU2xxVFJmWlF0NlpRSThGeUR0CkFHTGtzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtVmtLMExqSzFaa0JVa0ZHR09zRG1Wa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjJCd0hqc1JjdW4yU2xxVFJmWlF0NlpRSThGeUR0CkFHTGtzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtVmtLMExqSzFaa0Eza0ZHR09zRG1Wa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjJCd0hqc1JjdW4yU2xxVFJmWlF0NlpRSThGeUR0CkFHTGtzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtVmtLMExqSzFaa0Fha0ZHR09zRG1Wa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrWG8ycWRMSmd1cGFFdVlRTjJCd0hqc1JjdW4yU2xxVFJmWlF0NlpRSThGeUR0CkFHTGtzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtVmtLMExqSzFaa0FLa0ZHR09zRG1Wa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjJCd0hqc1JjdW4yU2xxVFJmWlF0NlpRSThGeUR0CkFHTGtzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtVmtLMExqSzFaa0FVa0ZHR09zRG1Wa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrWG8ycWRMSmd1cGFFdVlRTjJCd0hqc1JjdW4yU2xxVFJmWlF0NlpRSThGeUR0CkFHTGtzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtVmtLMExqSzFaa1oza0ZHR09zRG1Wa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjJCd0hqc1JjdW4yU2xxVFJmWlF0NlpRSThGeUR0CkFHTGtzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtVmtLMExqSzFaa1pha0ZHR09zRG1Wa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrWG8ycWRMSmd1cGFFdVlRTjJCd0hqc1JjdW4yU2xxVFJmWlF0NlpRSThGeUR0CkFHTGtzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtVmtLMExqSzFaa1pLa0ZHR09zRG1Wa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjJCd0hqc1JjdW4yU2xxVFJmWlF0NlpRSThGeUR0CkFHTGtzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtVmtLMExqSzFaa1pVa0ZHR09zRG1Wa0swTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjJCd0hqc1JjdW4yU2xxVFJmWlF0NlpRSThGeUR0CkFHTGtzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtVmtLMExqSzFaNXNTV0FaUzlRWndTc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JjaU0yY3VuMlNscVRSZlpRTDZBR084RnpTZUxLVzBMRmpqQlFiakFLa1hJUE4xCkF3Uzhad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtVmtLMExqSzFaNHNTV0FaUzlRWndTc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JjaU0yY3VuMlNscVRSZlpRTDZBR084RnpTZUxLVzBMRmpqQlFiakFLa1hJUE4xCkF3Uzhad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtVmtLMExqSzFaM3NTV0FaUzlRWndTc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JjaU0yY3VuMlNscVRSZlpRTDZBR084RnpTZUxLVzBMRmpqQlFiakFLa1hJUE4xCkF3Uzhad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtVmtLMExqSzFaMnNTV0FaUzlRWndTc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JjaU0yY3VuMlNscVRSZlpRTDZBR084RnpTZUxLVzBMRmpqQlFiakFLa1hJUE4xCkF3Uzhad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtVmtLMExqSzFaMXNTV0FaUzlRWndTc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JjaU0yY3VuMlNscVRSZlpRTDZBR084RnpTZUxLVzBMRmpqQlFiakFLa1hJUE4xCkF3Uzhad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "13:10",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "18:10",
            "flight_id": "JT 378",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "X",
                "flight_key": "R0dPc0RtVmtLMExrSzFaa0JLa0ZHR09zRG1Wa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTdThKVWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtVmtLMExrSzFaa0JVa0ZHR09zRG1Wa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtVmtLMExrSzFaa0Eza0ZHR09zRG1Wa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtVmtLMExrSzFaa0Fha0ZHR09zRG1Wa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtVmtLMExrSzFaa0FLa0ZHR09zRG1Wa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtVmtLMExrSzFaa0FVa0ZHR09zRG1Wa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtVmtLMExrSzFaa1oza0ZHR09zRG1Wa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtVmtLMExrSzFaa1pha0ZHR09zRG1Wa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtVmtLMExrSzFaa1pLa0ZHR09zRG1Wa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtVmtLMExrSzFaa1pVa0ZHR09zRG1Wa0swTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtVmtLMExrSzFaNXNTV0FaUzlRWndTc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JjdW4yU2xxVFJmWkdaNlpHTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtCUWJrClpVa1hJUE5tQW11OFp3SGdES0lhWUdWalpHTD0K",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtVmtLMExrSzFaNHNTV0FaUzlRWndTc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JjdW4yU2xxVFJmWkdaNlpHTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtCUWJrClpVa1hJUE5tQW11OFp3SGdES0lhWUdWalpHTD0K",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtVmtLMExrSzFaM3NTV0FaUzlRWndTc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JjdW4yU2xxVFJmWkdaNlpHTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtCUWJrClpVa1hJUE5tQW11OFp3SGdES0lhWUdWalpHTD0K",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtVmtLMExrSzFaMnNTV0FaUzlRWndTc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JjdW4yU2xxVFJmWkdaNlpHTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtCUWJrClpVa1hJUE5tQW11OFp3SGdES0lhWUdWalpHTD0K",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtVmtLMExrSzFaMXNTV0FaUzlRWndTc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JjdW4yU2xxVFJmWkdaNlpHTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtCUWJrClpVa1hJUE5tQW11OFp3SGdES0lhWUdWalpHTD0K",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_8",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "07:30",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "08:45",
            "flight_id": "JT 565",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 7,
                "code": "X",
                "flight_key": "R0dPc0RtTXNFd09zSG1SNXNTV0FaUzlRQXk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrTHNTdThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtTXNFd09zSG1SNHNTV0FaUzlRQXk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtTXNFd09zSG1SM3NTV0FaUzlRQXk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtTXNFd09zSG1SMnNTV0FaUzlRQXk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtTXNFd09zSG1SMXNTV0FaUzlRQXk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtTXNFd09zSG1SMHNTV0FaUzlRQXk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtTXNFd09zSG1SbXNTV0FaUzlRQXk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtTXNFd09zSG1SbHNTV0FaUzlRQXk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtTXNFd09zSG1Sa3NTV0FaUzlRQXk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtTXNFd09zSG1SanNTV0FaUzlRQXk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtTXNFd09zSG15OEh4MGpLMFoySzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtTXNFd09zSG11OEh4MGpLMFoySzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtTXNFd09zSG1xOEh4MGpLMFoySzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtTXNFd09zSG1NOEh4MGpLMFoySzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtTXNFd09zSG1JOEh4MGpLMFoySzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "12:20",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "14:40",
            "flight_id": "JT 398",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 1,
                "code": "X",
                "flight_key": "R0dPc0RtTXNFd1NzSG1SNXNTV0FaUzlRQXk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004WktrTHNTdThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtTXNFd1NzSG1SNHNTV0FaUzlRQXk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtTXNFd1NzSG1SM3NTV0FaUzlRQXk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtTXNFd1NzSG1SMnNTV0FaUzlRQXk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtTXNFd1NzSG1SMXNTV0FaUzlRQXk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtTXNFd1NzSG1SMHNTV0FaUzlRQXk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtTXNFd1NzSG1SbXNTV0FaUzlRQXk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtTXNFd1NzSG1SbHNTV0FaUzlRQXk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtTXNFd1NzSG1Sa3NTV0FaUzlRQXk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtTXNFd1NzSG1SanNTV0FaUzlRQXk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4RnpTZUxLVzBMRmprWndibFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd0RqCnNSY0hWUVo1QlVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtTXNFd1NzSG15OEh4MGpLMFoySzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYTEpndXBhRXVZUVJsQndWanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZBUU84CkZ5RHRabXg0c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtTXNFd1NzSG11OEh4MGpLMFoySzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYTEpndXBhRXVZUVJsQndWanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZBUU84CkZ5RHRabXg0c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtTXNFd1NzSG1xOEh4MGpLMFoySzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYTEpndXBhRXVZUVJsQndWanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZBUU84CkZ5RHRabXg0c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtTXNFd1NzSG1NOEh4MGpLMFoySzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYTEpndXBhRXVZUVJsQndWanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZBUU84CkZ5RHRabXg0c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtTXNFd1NzSG1JOEh4MGpLMFoySzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYTEpndXBhRXVZUVJsQndWanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZBUU84CkZ5RHRabXg0c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_9",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "07:30",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "08:45",
            "flight_id": "JT 565",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 7,
                "code": "X",
                "flight_key": "R0dPc0RtdXNFd09zSG1SNXNTV0FaUzlRQlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrTHNTdThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtdXNFd09zSG1SNHNTV0FaUzlRQlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtdXNFd09zSG1SM3NTV0FaUzlRQlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtdXNFd09zSG1SMnNTV0FaUzlRQlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtdXNFd09zSG1SMXNTV0FaUzlRQlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtdXNFd09zSG1SMHNTV0FaUzlRQlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtdXNFd09zSG1SbXNTV0FaUzlRQlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtdXNFd09zSG1SbHNTV0FaUzlRQlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtdXNFd09zSG1Sa3NTV0FaUzlRQlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtdXNFd09zSG1SanNTV0FaUzlRQlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtdXNFd09zSG15OEh4MGpLMFo0SzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtdXNFd09zSG11OEh4MGpLMFo0SzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtdXNFd09zSG1xOEh4MGpLMFo0SzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtdXNFd09zSG1NOEh4MGpLMFo0SzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtdXNFd09zSG1JOEh4MGpLMFo0SzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "12:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "15:10",
            "flight_id": "JT 382",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 4,
                "code": "X",
                "flight_key": "R0dPc0RtdXNFd1NzSG1SNXNTV0FaUzlRQlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QVVrTHNTdThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtdXNFd1NzSG1SNHNTV0FaUzlRQlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtdXNFd1NzSG1SM3NTV0FaUzlRQlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtdXNFd1NzSG1SMnNTV0FaUzlRQlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtdXNFd1NzSG1SMXNTV0FaUzlRQlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtdXNFd1NzSG1SMHNTV0FaUzlRQlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtdXNFd1NzSG1SbXNTV0FaUzlRQlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtdXNFd1NzSG1SbHNTV0FaUzlRQlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtdXNFd1NzSG1Sa3NTV0FaUzlRQlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtdXNFd1NzSG1SanNTV0FaUzlRQlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4RnpTZUxLVzBMRmprWndiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjFCd1JqCnNSY0hWUVo0WmFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtdXNFd1NzSG15OEh4MGpLMFo0SzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYTEpndXBhRXVZUVJsQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHSDZaR084CkZ5RHRabXRsc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtdXNFd1NzSG11OEh4MGpLMFo0SzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYTEpndXBhRXVZUVJsQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHSDZaR084CkZ5RHRabXRsc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtdXNFd1NzSG1xOEh4MGpLMFo0SzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYTEpndXBhRXVZUVJsQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHSDZaR084CkZ5RHRabXRsc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtdXNFd1NzSG1NOEh4MGpLMFo0SzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYTEpndXBhRXVZUVJsQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHSDZaR084CkZ5RHRabXRsc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtdXNFd1NzSG1JOEh4MGpLMFo0SzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYTEpndXBhRXVZUVJsQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHSDZaR084CkZ5RHRabXRsc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_10",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "07:30",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "08:45",
            "flight_id": "JT 565",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 7,
                "code": "X",
                "flight_key": "R0dPc0RtV3NFd09zSG1SNXNTV0FaUzlRWnk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrTHNTdThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtV3NFd09zSG1SNHNTV0FaUzlRWnk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtV3NFd09zSG1SM3NTV0FaUzlRWnk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtV3NFd09zSG1SMnNTV0FaUzlRWnk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtV3NFd09zSG1SMXNTV0FaUzlRWnk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtV3NFd09zSG1SMHNTV0FaUzlRWnk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtV3NFd09zSG1SbXNTV0FaUzlRWnk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtV3NFd09zSG1SbHNTV0FaUzlRWnk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtV3NFd09zSG1Sa3NTV0FaUzlRWnk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtV3NFd09zSG1SanNTV0FaUzlRWnk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtV3NFd09zSG15OEh4MGpLMFpsSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtV3NFd09zSG11OEh4MGpLMFpsSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtV3NFd09zSG1xOEh4MGpLMFpsSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtV3NFd09zSG1NOEh4MGpLMFpsSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtV3NFd09zSG1JOEh4MGpLMFpsSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "10:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "13:10",
            "flight_id": "JT 204",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 1,
                "code": "X",
                "flight_key": "R0dPc0RtV3NFd1NzSG1SNXNTV0FaUzlRWnk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004WktrTHNTdThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtV3NFd1NzSG1SNHNTV0FaUzlRWnk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtV3NFd1NzSG1SM3NTV0FaUzlRWnk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtV3NFd1NzSG1SMnNTV0FaUzlRWnk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtV3NFd1NzSG1SMXNTV0FaUzlRWnk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtV3NFd1NzSG1SMHNTV0FaUzlRWnk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtV3NFd1NzSG1SbXNTV0FaUzlRWnk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtV3NFd1NzSG1SbHNTV0FaUzlRWnk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtV3NFd1NzSG1Sa3NTV0FaUzlRWnk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtV3NFd1NzSG1SanNTV0FaUzlRWnk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtV3NFd1NzSG15OEh4MGpLMFpsSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYTEpndXBhRXVZUVJqQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHWjZaR084CkZ5RHRad04wc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtV3NFd1NzSG11OEh4MGpLMFpsSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYTEpndXBhRXVZUVJqQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHWjZaR084CkZ5RHRad04wc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtV3NFd1NzSG1xOEh4MGpLMFpsSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYTEpndXBhRXVZUVJqQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHWjZaR084CkZ5RHRad04wc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtV3NFd1NzSG1NOEh4MGpLMFpsSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYTEpndXBhRXVZUVJqQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHWjZaR084CkZ5RHRad04wc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtV3NFd1NzSG1JOEh4MGpLMFpsSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYTEpndXBhRXVZUVJqQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHWjZaR084CkZ5RHRad04wc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_11",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "07:30",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "08:45",
            "flight_id": "JT 565",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 7,
                "code": "X",
                "flight_key": "R0dPc0RtVmxLMExqSzFaa0JLa0ZHR09zRG1WbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTdThKVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd1pqc1JjdW4yU2xxVFJmWlF0NkFRSThGeUR0CkFHTDFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtVmxLMExqSzFaa0JVa0ZHR09zRG1WbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd1pqc1JjdW4yU2xxVFJmWlF0NkFRSThGeUR0CkFHTDFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtVmxLMExqSzFaa0Eza0ZHR09zRG1WbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd1pqc1JjdW4yU2xxVFJmWlF0NkFRSThGeUR0CkFHTDFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtVmxLMExqSzFaa0Fha0ZHR09zRG1WbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd1pqc1JjdW4yU2xxVFJmWlF0NkFRSThGeUR0CkFHTDFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtVmxLMExqSzFaa0FLa0ZHR09zRG1WbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd1pqc1JjdW4yU2xxVFJmWlF0NkFRSThGeUR0CkFHTDFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtVmxLMExqSzFaa0FVa0ZHR09zRG1WbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd1pqc1JjdW4yU2xxVFJmWlF0NkFRSThGeUR0CkFHTDFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtVmxLMExqSzFaa1oza0ZHR09zRG1WbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd1pqc1JjdW4yU2xxVFJmWlF0NkFRSThGeUR0CkFHTDFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtVmxLMExqSzFaa1pha0ZHR09zRG1WbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd1pqc1JjdW4yU2xxVFJmWlF0NkFRSThGeUR0CkFHTDFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtVmxLMExqSzFaa1pLa0ZHR09zRG1WbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd1pqc1JjdW4yU2xxVFJmWlF0NkFRSThGeUR0CkFHTDFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtVmxLMExqSzFaa1pVa0ZHR09zRG1WbEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd1pqc1JjdW4yU2xxVFJmWlF0NkFRSThGeUR0CkFHTDFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtVmxLMExqSzFaNXNTV0FaUzlRWndXc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZabU84RnpTZUxLVzBMRmpqQlFiMEFLa1hJUE4xCkF3SThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtVmxLMExqSzFaNHNTV0FaUzlRWndXc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZabU84RnpTZUxLVzBMRmpqQlFiMEFLa1hJUE4xCkF3SThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtVmxLMExqSzFaM3NTV0FaUzlRWndXc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZabU84RnpTZUxLVzBMRmpqQlFiMEFLa1hJUE4xCkF3SThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtVmxLMExqSzFaMnNTV0FaUzlRWndXc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZabU84RnpTZUxLVzBMRmpqQlFiMEFLa1hJUE4xCkF3SThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtVmxLMExqSzFaMXNTV0FaUzlRWndXc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZabU84RnpTZUxLVzBMRmpqQlFiMEFLa1hJUE4xCkF3SThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "13:10",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "18:10",
            "flight_id": "JT 378",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "X",
                "flight_key": "R0dPc0RtVmxLMExrSzFaa0JLa0ZHR09zRG1WbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTdThKVWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtVmxLMExrSzFaa0JVa0ZHR09zRG1WbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtVmxLMExrSzFaa0Eza0ZHR09zRG1WbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtVmxLMExrSzFaa0Fha0ZHR09zRG1WbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtVmxLMExrSzFaa0FLa0ZHR09zRG1WbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtVmxLMExrSzFaa0FVa0ZHR09zRG1WbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtVmxLMExrSzFaa1oza0ZHR09zRG1WbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtVmxLMExrSzFaa1pha0ZHR09zRG1WbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtVmxLMExrSzFaa1pLa0ZHR09zRG1WbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtVmxLMExrSzFaa1pVa0ZHR09zRG1WbEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrWExKZ3VwYUV1WVFSbUJ3UmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0Wm1wNHNRVjFZSFMxTWwwbFpRUjIK",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtVmxLMExrSzFaNXNTV0FaUzlRWndXc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JjdW4yU2xxVFJmWkdaNlpHTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtCUWJrClpVa1hJUE5tQW11OFp3SGdES0lhWUdWalpHTD0K",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtVmxLMExrSzFaNHNTV0FaUzlRWndXc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JjdW4yU2xxVFJmWkdaNlpHTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtCUWJrClpVa1hJUE5tQW11OFp3SGdES0lhWUdWalpHTD0K",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtVmxLMExrSzFaM3NTV0FaUzlRWndXc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JjdW4yU2xxVFJmWkdaNlpHTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtCUWJrClpVa1hJUE5tQW11OFp3SGdES0lhWUdWalpHTD0K",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtVmxLMExrSzFaMnNTV0FaUzlRWndXc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JjdW4yU2xxVFJmWkdaNlpHTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtCUWJrClpVa1hJUE5tQW11OFp3SGdES0lhWUdWalpHTD0K",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtVmxLMExrSzFaMXNTV0FaUzlRWndXc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JjdW4yU2xxVFJmWkdaNlpHTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtCUWJrClpVa1hJUE5tQW11OFp3SGdES0lhWUdWalpHTD0K",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_12",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "07:30",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "08:45",
            "flight_id": "JT 565",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 7,
                "code": "X",
                "flight_key": "R0dPc0RtRXNFd09zSG1SNXNTV0FaUzlRQVM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrTHNTdThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtRXNFd09zSG1SNHNTV0FaUzlRQVM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtRXNFd09zSG1SM3NTV0FaUzlRQVM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtRXNFd09zSG1SMnNTV0FaUzlRQVM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtRXNFd09zSG1SMXNTV0FaUzlRQVM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtRXNFd09zSG1SMHNTV0FaUzlRQVM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtRXNFd09zSG1SbXNTV0FaUzlRQVM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtRXNFd09zSG1SbHNTV0FaUzlRQVM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtRXNFd09zSG1Sa3NTV0FaUzlRQVM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtRXNFd09zSG1SanNTV0FaUzlRQVM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYm1aVWtYTEpndXBhRXVZUU40QndEMXNSY0hWUUgyCkFLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtRXNFd09zSG15OEh4MGpLMFowSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtRXNFd09zSG11OEh4MGpLMFowSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtRXNFd09zSG1xOEh4MGpLMFowSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtRXNFd09zSG1NOEh4MGpLMFowSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtRXNFd09zSG1JOEh4MGpLMFowSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3WmpzUmN1bjJTbHFUUmZaUXQ2QVFJOEZ5RHRBR0wxCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "11:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "14:10",
            "flight_id": "JT 306",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtRXNFd1NzSG1SNHNTV0FaUzlRQVM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtRXNFd1NzSG1SM3NTV0FaUzlRQVM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtRXNFd1NzSG1SMnNTV0FaUzlRQVM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtRXNFd1NzSG1SMXNTV0FaUzlRQVM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtRXNFd1NzSG1SMHNTV0FaUzlRQVM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtRXNFd1NzSG1SbXNTV0FaUzlRQVM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtRXNFd1NzSG1SbHNTV0FaUzlRQVM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtRXNFd1NzSG1Sa3NTV0FaUzlRQVM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtRXNFd1NzSG1SanNTV0FaUzlRQVM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4RnpTZUxLVzBMRmprWkdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjBCd1JqCnNSY0hWUVpqQWFqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtRXNFd1NzSG15OEh4MGpLMFowSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYTEpndXBhRXVZUVJrQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZaR084CkZ5RHRabU4yc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtRXNFd1NzSG11OEh4MGpLMFowSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYTEpndXBhRXVZUVJrQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZaR084CkZ5RHRabU4yc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtRXNFd1NzSG1xOEh4MGpLMFowSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYTEpndXBhRXVZUVJrQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZaR084CkZ5RHRabU4yc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtRXNFd1NzSG1NOEh4MGpLMFowSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYTEpndXBhRXVZUVJrQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZaR084CkZ5RHRabU4yc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtRXNFd1NzSG1JOEh4MGpLMFowSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYTEpndXBhRXVZUVJrQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHRDZaR084CkZ5RHRabU4yc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_13",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "12:20",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "14:20",
            "flight_id": "JT 277",
            "area_arrive": "BTH",
            "seat": [
              {
                "available": 1,
                "code": "X",
                "flight_key": "R0dPc0RtUjBLMExqSzFaa0JLa0ZHR09zRG1SMEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqa3NTdThKVWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtUjBLMExqSzFaa0JVa0ZHR09zRG1SMEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtUjBLMExqSzFaa0Eza0ZHR09zRG1SMEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUjBLMExqSzFaa0Fha0ZHR09zRG1SMEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUjBLMExqSzFaa0FLa0ZHR09zRG1SMEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUjBLMExqSzFaa0FVa0ZHR09zRG1SMEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUjBLMExqSzFaa1oza0ZHR09zRG1SMEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUjBLMExqSzFaa1pha0ZHR09zRG1SMEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUjBLMExqSzFaa1pLa0ZHR09zRG1SMEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUjBLMExqSzFaa1pVa0ZHR09zRG1SMEswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUjBLMExqSzFaNXNTV0FaUzlRWkdFc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JjaU0yY3VuMlNscVRSZlpHVjZad084RHpTMExKMGZaR0Q2WndPOEZ5RHRad3AzCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUjBLMExqSzFaNHNTV0FaUzlRWkdFc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JjaU0yY3VuMlNscVRSZlpHVjZad084RHpTMExKMGZaR0Q2WndPOEZ5RHRad3AzCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUjBLMExqSzFaM3NTV0FaUzlRWkdFc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JjaU0yY3VuMlNscVRSZlpHVjZad084RHpTMExKMGZaR0Q2WndPOEZ5RHRad3AzCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUjBLMExqSzFaMnNTV0FaUzlRWkdFc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JjaU0yY3VuMlNscVRSZlpHVjZad084RHpTMExKMGZaR0Q2WndPOEZ5RHRad3AzCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUjBLMExqSzFaMXNTV0FaUzlRWkdFc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JjaU0yY3VuMlNscVRSZlpHVjZad084RHpTMExKMGZaR0Q2WndPOEZ5RHRad3AzCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "15:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "BTH",
            "time_arrive": "17:10",
            "flight_id": "JT 989",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUjBLMExrSzFaa0Fha0ZHR09zRG1SMEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrUExLRXVvRmprQUdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjNCd1JqCnNSY0hWUXg0QktqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUjBLMExrSzFaa0FLa0ZHR09zRG1SMEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrUExLRXVvRmprQUdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjNCd1JqCnNSY0hWUXg0QktqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUjBLMExrSzFaa0FVa0ZHR09zRG1SMEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrUExLRXVvRmprQUdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjNCd1JqCnNSY0hWUXg0QktqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUjBLMExrSzFaa1oza0ZHR09zRG1SMEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrUExLRXVvRmprQUdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjNCd1JqCnNSY0hWUXg0QktqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUjBLMExrSzFaa1pha0ZHR09zRG1SMEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrUExLRXVvRmprQUdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjNCd1JqCnNSY0hWUXg0QktqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUjBLMExrSzFaa1pLa0ZHR09zRG1SMEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrUExLRXVvRmprQUdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjNCd1JqCnNSY0hWUXg0QktqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUjBLMExrSzFaa1pVa0ZHR09zRG1SMEswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrUExLRXVvRmprQUdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjNCd1JqCnNSY0hWUXg0QktqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUjBLMExrSzFaNXNTV0FaUzlRWkdFc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JXdXFUU2dZUVIxQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHcDZaR084CkZ5RHRCR3Q1c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUjBLMExrSzFaNHNTV0FaUzlRWkdFc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JXdXFUU2dZUVIxQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHcDZaR084CkZ5RHRCR3Q1c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUjBLMExrSzFaM3NTV0FaUzlRWkdFc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JXdXFUU2dZUVIxQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHcDZaR084CkZ5RHRCR3Q1c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUjBLMExrSzFaMnNTV0FaUzlRWkdFc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JXdXFUU2dZUVIxQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHcDZaR084CkZ5RHRCR3Q1c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUjBLMExrSzFaMXNTV0FaUzlRWkdFc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JXdXFUU2dZUVIxQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHcDZaR084CkZ5RHRCR3Q1c1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_14",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "06:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "08:05",
            "flight_id": "JT 561",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 6,
                "code": "X",
                "flight_key": "R0dPc0RtU3NFd09zSG1SNXNTV0FaUzlRWkk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QWFrTHNTdThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtU3NFd09zSG1SNHNTV0FaUzlRWkk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtU3NFd09zSG1SM3NTV0FaUzlRWkk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtU3NFd09zSG1SMnNTV0FaUzlRWkk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtU3NFd09zSG1SMXNTV0FaUzlRWkk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtU3NFd09zSG1SMHNTV0FaUzlRWkk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtU3NFd09zSG1SbXNTV0FaUzlRWkk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtU3NFd09zSG1SbHNTV0FaUzlRWkk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtU3NFd09zSG1Sa3NTV0FaUzlRWkk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtU3NFd09zSG1SanNTV0FaUzlRWkk5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4Rno5YW56U2VMS1cwTEZqakF3YjFaVWtYTEpndXBhRXVZUU40QndOMXNSY0hWUUgyClpLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtU3NFd09zSG15OEh4MGpLMFprSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtU3NFd09zSG11OEh4MGpLMFprSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtU3NFd09zSG1xOEh4MGpLMFprSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtU3NFd09zSG1NOEh4MGpLMFprSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtU3NFd09zSG1JOEh4MGpLMFprSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYbzJxZExKZ3VwYUV1WVFOMkJ3SGpzUmN1bjJTbHFUUmZaUXQ2WlFJOEZ5RHRBR0xrCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "10:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "13:10",
            "flight_id": "JT 204",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 1,
                "code": "X",
                "flight_key": "R0dPc0RtU3NFd1NzSG1SNXNTV0FaUzlRWkk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004WktrTHNTdThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtU3NFd1NzSG1SNHNTV0FaUzlRWkk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtU3NFd1NzSG1SM3NTV0FaUzlRWkk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtU3NFd1NzSG1SMnNTV0FaUzlRWkk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtU3NFd1NzSG1SMXNTV0FaUzlRWkk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtU3NFd1NzSG1SMHNTV0FaUzlRWkk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtU3NFd1NzSG1SbXNTV0FaUzlRWkk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtU3NFd1NzSG1SbHNTV0FaUzlRWkk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtU3NFd1NzSG1Sa3NTV0FaUzlRWkk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtU3NFd1NzSG1SanNTV0FaUzlRWkk5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4RnpTZUxLVzBMRmprWlFiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUm1Cd1JqCnNSY0hWUVZqQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtU3NFd1NzSG15OEh4MGpLMFprSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYTEpndXBhRXVZUVJqQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHWjZaR084CkZ5RHRad04wc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtU3NFd1NzSG11OEh4MGpLMFprSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYTEpndXBhRXVZUVJqQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHWjZaR084CkZ5RHRad04wc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtU3NFd1NzSG1xOEh4MGpLMFprSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYTEpndXBhRXVZUVJqQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHWjZaR084CkZ5RHRad04wc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtU3NFd1NzSG1NOEh4MGpLMFprSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYTEpndXBhRXVZUVJqQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHWjZaR084CkZ5RHRad04wc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtU3NFd1NzSG1JOEh4MGpLMFprSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYTEpndXBhRXVZUVJqQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHWjZaR084CkZ5RHRad04wc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_15",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "07:00",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "09:00",
            "flight_id": "JT 279",
            "area_arrive": "BTH",
            "seat": [
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtT3NFd09zSG1SNHNTV0FaUzlRWlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4Rno5YW56U2VMS1cwTEZqakFtYmpaVWtQTEtFdW9GampCR2JqWlVrWElQTmxBbXk4Clp3SGdES0lhWUdWalpHTD0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtT3NFd09zSG1SM3NTV0FaUzlRWlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4Rno5YW56U2VMS1cwTEZqakFtYmpaVWtQTEtFdW9GampCR2JqWlVrWElQTmxBbXk4Clp3SGdES0lhWUdWalpHTD0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtT3NFd09zSG1SMnNTV0FaUzlRWlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYmpaVWtQTEtFdW9GampCR2JqWlVrWElQTmxBbXk4Clp3SGdES0lhWUdWalpHTD0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtT3NFd09zSG1SMXNTV0FaUzlRWlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4Rno5YW56U2VMS1cwTEZqakFtYmpaVWtQTEtFdW9GampCR2JqWlVrWElQTmxBbXk4Clp3SGdES0lhWUdWalpHTD0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtT3NFd09zSG1SMHNTV0FaUzlRWlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4Rno5YW56U2VMS1cwTEZqakFtYmpaVWtQTEtFdW9GampCR2JqWlVrWElQTmxBbXk4Clp3SGdES0lhWUdWalpHTD0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtT3NFd09zSG1SbXNTV0FaUzlRWlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4Rno5YW56U2VMS1cwTEZqakFtYmpaVWtQTEtFdW9GampCR2JqWlVrWElQTmxBbXk4Clp3SGdES0lhWUdWalpHTD0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtT3NFd09zSG1SbHNTV0FaUzlRWlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYmpaVWtQTEtFdW9GampCR2JqWlVrWElQTmxBbXk4Clp3SGdES0lhWUdWalpHTD0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtT3NFd09zSG1Sa3NTV0FaUzlRWlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4Rno5YW56U2VMS1cwTEZqakFtYmpaVWtQTEtFdW9GampCR2JqWlVrWElQTmxBbXk4Clp3SGdES0lhWUdWalpHTD0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtT3NFd09zSG1SanNTV0FaUzlRWlM5VFpVa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4Rno5YW56U2VMS1cwTEZqakFtYmpaVWtQTEtFdW9GampCR2JqWlVrWElQTmxBbXk4Clp3SGdES0lhWUdWalpHTD0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtT3NFd09zSG15OEh4MGpLMFpqSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3TmpzUld1cVRTZ1lRTjVCd05qc1JjSFZRVjNCS2psCkFGMU9xSnBnWndOa0F0PT0K",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtT3NFd09zSG11OEh4MGpLMFpqSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3TmpzUld1cVRTZ1lRTjVCd05qc1JjSFZRVjNCS2psCkFGMU9xSnBnWndOa0F0PT0K",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtT3NFd09zSG1xOEh4MGpLMFpqSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3TmpzUld1cVRTZ1lRTjVCd05qc1JjSFZRVjNCS2psCkFGMU9xSnBnWndOa0F0PT0K",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtT3NFd09zSG1NOEh4MGpLMFpqSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3TmpzUld1cVRTZ1lRTjVCd05qc1JjSFZRVjNCS2psCkFGMU9xSnBnWndOa0F0PT0K",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtT3NFd09zSG1JOEh4MGpLMFpqSzBManNSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtYbzJxZExKZ3VwYUV1WVFOM0J3TmpzUld1cVRTZ1lRTjVCd05qc1JjSFZRVjNCS2psCkFGMU9xSnBnWndOa0F0PT0K",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "10:55",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "BTH",
            "time_arrive": "12:15",
            "flight_id": "JT 971",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 1,
                "code": "X",
                "flight_key": "R0dPc0RtT3NFd1NzSG1SNXNTV0FaUzlRWlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004WktrTHNTdThGeUU4RHpTMExKMGZaR042QUdJOEdKSXhMSjR0RjNJdW9UUnRHelNncUZqa1p3YmtBS2tYCklQTjVBbVM4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtT3NFd1NzSG1SNHNTV0FaUzlRWlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSnNTTThGeUU4RHpTMExKMGZaR042QUdJOEdKSXhMSjR0RjNJdW9UUnRHelNncUZqa1p3YmtBS2tYCklQTjVBbVM4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtT3NFd1NzSG1SM3NTV0FaUzlRWlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrSHNTRThGeUU4RHpTMExKMGZaR042QUdJOEdKSXhMSjR0RjNJdW9UUnRHelNncUZqa1p3YmtBS2tYCklQTjVBbVM4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtT3NFd1NzSG1SMnNTV0FaUzlRWlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrRXNTUzhGeUU4RHpTMExKMGZaR042QUdJOEdKSXhMSjR0RjNJdW9UUnRHelNncUZqa1p3YmtBS2tYCklQTjVBbVM4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtT3NFd1NzSG1SMXNTV0FaUzlRWlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQnNSNThGeUU4RHpTMExKMGZaR042QUdJOEdKSXhMSjR0RjNJdW9UUnRHelNncUZqa1p3YmtBS2tYCklQTjVBbVM4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtT3NFd1NzSG1SMHNTV0FaUzlRWlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrQXNSMThGeUU4RHpTMExKMGZaR042QUdJOEdKSXhMSjR0RjNJdW9UUnRHelNncUZqa1p3YmtBS2tYCklQTjVBbVM4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtT3NFd1NzSG1SbXNTV0FaUzlRWlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWnNSazhGeUU4RHpTMExKMGZaR042QUdJOEdKSXhMSjR0RjNJdW9UUnRHelNncUZqa1p3YmtBS2tYCklQTjVBbVM4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtT3NFd1NzSG1SbHNTV0FaUzlRWlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrWXNSZzhGeUU4RHpTMExKMGZaR042QUdJOEdKSXhMSjR0RjNJdW9UUnRHelNncUZqa1p3YmtBS2tYCklQTjVBbVM4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtT3NFd1NzSG1Sa3NTV0FaUzlRWlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrVnNSdThGeUU4RHpTMExKMGZaR042QUdJOEdKSXhMSjR0RjNJdW9UUnRHelNncUZqa1p3YmtBS2tYCklQTjVBbVM4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtT3NFd1NzSG1SanNTV0FaUzlRWlM5VFpLa1hHMHE4RjA1Q3NRUzhaVWpqc1FWMVdHVmpES0lhV0dWalp3TmtBYWpsQUZIbApaUlMxTWxIbFpRVmpaR004QTNrUHNSVzhGeUU4RHpTMExKMGZaR042QUdJOEdKSXhMSjR0RjNJdW9UUnRHelNncUZqa1p3YmtBS2tYCklQTjVBbVM4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtT3NFd1NzSG15OEh4MGpLMFpqSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NBOEgza1hJVWtQTEtFdW9GamtaUWIxQUtrQU1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSbEJ3UjFzUmNIClZReDNaS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtT3NFd1NzSG11OEh4MGpLMFpqSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1NxOEkza1hJVWtQTEtFdW9GamtaUWIxQUtrQU1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSbEJ3UjFzUmNIClZReDNaS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtT3NFd1NzSG1xOEh4MGpLMFpqSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JxOEUza1hJVWtQTEtFdW9GamtaUWIxQUtrQU1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSbEJ3UjFzUmNIClZReDNaS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtT3NFd1NzSG1NOEh4MGpLMFpqSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1JTOERLa1hJVWtQTEtFdW9GamtaUWIxQUtrQU1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSbEJ3UjFzUmNIClZReDNaS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtT3NFd1NzSG1JOEh4MGpLMFpqSzBMa3NSY0NFM2tZR3g5OFpLampzUU84WndIeVp3T09xSnB5WndObFpRUjJzUVYxV0dWagpES0lhV0dWalp3TmtBYWozc1N5OEpLa1hJVWtQTEtFdW9GamtaUWIxQUtrQU1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSbEJ3UjFzUmNIClZReDNaS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_16",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "12:20",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "14:20",
            "flight_id": "JT 277",
            "area_arrive": "BTH",
            "seat": [
              {
                "available": 1,
                "code": "X",
                "flight_key": "R0dPc0RtUjVLMExqSzFaa0JLa0ZHR09zRG1SNUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqa3NTdThKVWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtUjVLMExqSzFaa0JVa0ZHR09zRG1SNUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtUjVLMExqSzFaa0Eza0ZHR09zRG1SNUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUjVLMExqSzFaa0Fha0ZHR09zRG1SNUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUjVLMExqSzFaa0FLa0ZHR09zRG1SNUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUjVLMExqSzFaa0FVa0ZHR09zRG1SNUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUjVLMExqSzFaa1oza0ZHR09zRG1SNUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUjVLMExqSzFaa1pha0ZHR09zRG1SNUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUjVLMExqSzFaa1pLa0ZHR09zRG1SNUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUjVLMExqSzFaa1pVa0ZHR09zRG1SNUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrWG8ycWRMSmd1cGFFdVlRUmxCd1Zqc1JXdXFUU2dZUVIwQndWanNSY0hWUVYzCkEzamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUjVLMExqSzFaNXNTV0FaUzlRWkd5c0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JjaU0yY3VuMlNscVRSZlpHVjZad084RHpTMExKMGZaR0Q2WndPOEZ5RHRad3AzCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUjVLMExqSzFaNHNTV0FaUzlRWkd5c0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JjaU0yY3VuMlNscVRSZlpHVjZad084RHpTMExKMGZaR0Q2WndPOEZ5RHRad3AzCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUjVLMExqSzFaM3NTV0FaUzlRWkd5c0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JjaU0yY3VuMlNscVRSZlpHVjZad084RHpTMExKMGZaR0Q2WndPOEZ5RHRad3AzCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUjVLMExqSzFaMnNTV0FaUzlRWkd5c0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JjaU0yY3VuMlNscVRSZlpHVjZad084RHpTMExKMGZaR0Q2WndPOEZ5RHRad3AzCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUjVLMExqSzFaMXNTV0FaUzlRWkd5c0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JjaU0yY3VuMlNscVRSZlpHVjZad084RHpTMExKMGZaR0Q2WndPOEZ5RHRad3AzCnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "16:50",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "BTH",
            "time_arrive": "18:10",
            "flight_id": "JT 974",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "U",
                "flight_key": "R0dPc0RtUjVLMExrSzFabFpha0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTSThJS2tYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "U"
              },
              {
                "available": 7,
                "code": "O",
                "flight_key": "R0dPc0RtUjVLMExrSzFabFpLa0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSOThHM2tYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "O"
              },
              {
                "available": 7,
                "code": "X",
                "flight_key": "R0dPc0RtUjVLMExrSzFaa0JLa0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTdThKVWtYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtUjVLMExrSzFaa0JVa0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtUjVLMExrSzFaa0Eza0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUjVLMExrSzFaa0Fha0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUjVLMExrSzFaa0FLa0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUjVLMExrSzFaa0FVa0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUjVLMExrSzFaa1oza0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUjVLMExrSzFaa1pha0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUjVLMExrSzFaa1pLa0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUjVLMExrSzFaa1pVa0ZHR09zRG1SNUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrUExLRXVvRmprQXdiMVpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjRCd1JqCnNSY0hWUXgzQVVqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUjVLMExrSzFaNXNTV0FaUzlRWkd5c0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JXdXFUU2dZUVIyQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHdDZaR084CkZ5RHRCR3Awc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUjVLMExrSzFaNHNTV0FaUzlRWkd5c0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JXdXFUU2dZUVIyQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHdDZaR084CkZ5RHRCR3Awc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUjVLMExrSzFaM3NTV0FaUzlRWkd5c0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JXdXFUU2dZUVIyQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHdDZaR084CkZ5RHRCR3Awc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUjVLMExrSzFaMnNTV0FaUzlRWkd5c0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JXdXFUU2dZUVIyQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHdDZaR084CkZ5RHRCR3Awc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUjVLMExrSzFaMXNTV0FaUzlRWkd5c0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JXdXFUU2dZUVIyQndIanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHdDZaR084CkZ5RHRCR3Awc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_17",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "07:00",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "09:00",
            "flight_id": "JT 279",
            "area_arrive": "BTH",
            "seat": [
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtUmpLMExqSzFaa0JVa0ZHR09zRG1SakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd05qc1JXdXFUU2dZUU41QndOanNSY0hWUVYzCkJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtUmpLMExqSzFaa0Eza0ZHR09zRG1SakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd05qc1JXdXFUU2dZUU41QndOanNSY0hWUVYzCkJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUmpLMExqSzFaa0Fha0ZHR09zRG1SakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd05qc1JXdXFUU2dZUU41QndOanNSY0hWUVYzCkJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUmpLMExqSzFaa0FLa0ZHR09zRG1SakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd05qc1JXdXFUU2dZUU41QndOanNSY0hWUVYzCkJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUmpLMExqSzFaa0FVa0ZHR09zRG1SakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd05qc1JXdXFUU2dZUU41QndOanNSY0hWUVYzCkJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUmpLMExqSzFaa1oza0ZHR09zRG1SakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd05qc1JXdXFUU2dZUU41QndOanNSY0hWUVYzCkJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUmpLMExqSzFaa1pha0ZHR09zRG1SakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd05qc1JXdXFUU2dZUU41QndOanNSY0hWUVYzCkJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUmpLMExqSzFaa1pLa0ZHR09zRG1SakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd05qc1JXdXFUU2dZUU41QndOanNSY0hWUVYzCkJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUmpLMExqSzFaa1pVa0ZHR09zRG1SakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrWG8ycWRMSmd1cGFFdVlRTjNCd05qc1JXdXFUU2dZUU41QndOanNSY0hWUVYzCkJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUmpLMExqSzFaNXNTV0FaUzlRWkdPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZaUU84RHpTMExKMGZaUXg2WlFPOEZ5RHRad3A1CnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUmpLMExqSzFaNHNTV0FaUzlRWkdPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZaUU84RHpTMExKMGZaUXg2WlFPOEZ5RHRad3A1CnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUmpLMExqSzFaM3NTV0FaUzlRWkdPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZaUU84RHpTMExKMGZaUXg2WlFPOEZ5RHRad3A1CnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUmpLMExqSzFaMnNTV0FaUzlRWkdPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZaUU84RHpTMExKMGZaUXg2WlFPOEZ5RHRad3A1CnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUmpLMExqSzFaMXNTV0FaUzlRWkdPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JjaU0yY3VuMlNscVRSZlpRcDZaUU84RHpTMExKMGZaUXg2WlFPOEZ5RHRad3A1CnNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "14:40",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "BTH",
            "time_arrive": "16:00",
            "flight_id": "JT 973",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "X",
                "flight_key": "R0dPc0RtUmpLMExrSzFaa0JLa0ZHR09zRG1SakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTdThKVWtYSVVrUExLRXVvRmprQVFiMFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjJCd05qCnNSY0hWUXgzWjNqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "X"
              },
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtUmpLMExrSzFaa0JVa0ZHR09zRG1SakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtYSVVrUExLRXVvRmprQVFiMFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjJCd05qCnNSY0hWUXgzWjNqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtUmpLMExrSzFaa0Eza0ZHR09zRG1SakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtYSVVrUExLRXVvRmprQVFiMFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjJCd05qCnNSY0hWUXgzWjNqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUmpLMExrSzFaa0Fha0ZHR09zRG1SakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrUExLRXVvRmprQVFiMFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjJCd05qCnNSY0hWUXgzWjNqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUmpLMExrSzFaa0FLa0ZHR09zRG1SakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrUExLRXVvRmprQVFiMFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjJCd05qCnNSY0hWUXgzWjNqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUmpLMExrSzFaa0FVa0ZHR09zRG1SakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrUExLRXVvRmprQVFiMFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjJCd05qCnNSY0hWUXgzWjNqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUmpLMExrSzFaa1oza0ZHR09zRG1SakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrUExLRXVvRmprQVFiMFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjJCd05qCnNSY0hWUXgzWjNqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUmpLMExrSzFaa1pha0ZHR09zRG1SakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrUExLRXVvRmprQVFiMFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjJCd05qCnNSY0hWUXgzWjNqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUmpLMExrSzFaa1pLa0ZHR09zRG1SakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrUExLRXVvRmprQVFiMFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjJCd05qCnNSY0hWUXgzWjNqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUmpLMExrSzFaa1pVa0ZHR09zRG1SakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrUExLRXVvRmprQVFiMFpVa0FNSkV1b3ZPWXFKU2ZMRk9CTEoxMVlRUjJCd05qCnNSY0hWUXgzWjNqbEFGMU9xSnBnWndOa0F0PT0K",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUmpLMExrSzFaNXNTV0FaUzlRWkdPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1JXdXFUU2dZUVIwQndEanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHTDZaUU84CkZ5RHRCR3Btc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUmpLMExrSzFaNHNTV0FaUzlRWkdPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1JXdXFUU2dZUVIwQndEanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHTDZaUU84CkZ5RHRCR3Btc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUmpLMExrSzFaM3NTV0FaUzlRWkdPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1JXdXFUU2dZUVIwQndEanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHTDZaUU84CkZ5RHRCR3Btc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUmpLMExrSzFaMnNTV0FaUzlRWkdPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1JXdXFUU2dZUVIwQndEanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHTDZaUU84CkZ5RHRCR3Btc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUmpLMExrSzFaMXNTV0FaUzlRWkdPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1JXdXFUU2dZUVIwQndEanNSMXlNVFNoVlJnMUxKa3VWUjV1b0tIZlpHTDZaUU84CkZ5RHRCR3Btc1FWMVlIUzFNbDBsWlFSMgo=",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_18",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "09:00",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "10:10",
            "flight_id": "IW 1844",
            "area_arrive": "SUB",
            "seat": [
              {
                "available": 1,
                "code": "Q",
                "flight_key": "R0dPc0RtVm1LMExqSzFaa0Fha0ZHR09zRG1WbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqa3NTUzhIS2tXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "Q"
              },
              {
                "available": 6,
                "code": "N",
                "flight_key": "R0dPc0RtVm1LMExqSzFaa0FLa0ZHR09zRG1WbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqMnNSNThHYWtXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtVm1LMExqSzFaa0FVa0ZHR09zRG1WbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtVm1LMExqSzFaa1oza0ZHR09zRG1WbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtVm1LMExqSzFaa1pha0ZHR09zRG1WbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtVm1LMExqSzFaa1pLa0ZHR09zRG1WbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtVm1LMExqSzFaa1pVa0ZHR09zRG1WbUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtXSTNrWG8ycWRMSmd1cGFFdVlRTjVCd05qc1NBMXB6U3ZMS3l1WVFSakJ3UmpzUnlLClZRUjRBUUU4WndIZ0RLSWFZR1ZqWkdMPQo=",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtVm1LMExqSzFaNXNTV0FaUzlRWndBc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUnlLc1JjaU0yY3VuMlNscVRSZlpReDZaUU84SDNJbExKV3VySlJmWkdONlpHTzhGSXB0ClpHdDBBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtVm1LMExqSzFaNHNTV0FaUzlRWndBc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUnlLc1JjaU0yY3VuMlNscVRSZlpReDZaUU84SDNJbExKV3VySlJmWkdONlpHTzhGSXB0ClpHdDBBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtVm1LMExqSzFaM3NTV0FaUzlRWndBc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUnlLc1JjaU0yY3VuMlNscVRSZlpReDZaUU84SDNJbExKV3VySlJmWkdONlpHTzhGSXB0ClpHdDBBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtVm1LMExqSzFaMnNTV0FaUzlRWndBc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUnlLc1JjaU0yY3VuMlNscVRSZlpReDZaUU84SDNJbExKV3VySlJmWkdONlpHTzhGSXB0ClpHdDBBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtVm1LMExqSzFaMXNTV0FaUzlRWndBc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUnlLc1JjaU0yY3VuMlNscVRSZlpReDZaUU84SDNJbExKV3VySlJmWkdONlpHTzhGSXB0ClpHdDBBVWpsQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "Y"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "14:00",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "SUB",
            "time_arrive": "18:10",
            "flight_id": "JT 949",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtVm1LMExrSzFaa0Fha0ZHR09zRG1WbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tYSVVrR3FLV3VMelM1TEZqa0FRYmpaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVI0CkJ3UmpzUmNIVlF4MEJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtVm1LMExrSzFaa0FLa0ZHR09zRG1WbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtYSVVrR3FLV3VMelM1TEZqa0FRYmpaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVI0CkJ3UmpzUmNIVlF4MEJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtVm1LMExrSzFaa0FVa0ZHR09zRG1WbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tYSVVrR3FLV3VMelM1TEZqa0FRYmpaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVI0CkJ3UmpzUmNIVlF4MEJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtVm1LMExrSzFaa1oza0ZHR09zRG1WbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtYSVVrR3FLV3VMelM1TEZqa0FRYmpaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVI0CkJ3UmpzUmNIVlF4MEJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtVm1LMExrSzFaa1pha0ZHR09zRG1WbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tYSVVrR3FLV3VMelM1TEZqa0FRYmpaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVI0CkJ3UmpzUmNIVlF4MEJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtVm1LMExrSzFaa1pLa0ZHR09zRG1WbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtYSVVrR3FLV3VMelM1TEZqa0FRYmpaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVI0CkJ3UmpzUmNIVlF4MEJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtVm1LMExrSzFaa1pVa0ZHR09zRG1WbUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtYSVVrR3FLV3VMelM1TEZqa0FRYmpaVWtBTUpFdW92T1lxSlNmTEZPQkxKMTFZUVI0CkJ3UmpzUmNIVlF4MEJLamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtVm1LMExrSzFaNXNTV0FaUzlRWndBc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUmNIc1NBMXB6U3ZMS3l1WVFSMEJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0QkdENXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtVm1LMExrSzFaNHNTV0FaUzlRWndBc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUmNIc1NBMXB6U3ZMS3l1WVFSMEJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0QkdENXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtVm1LMExrSzFaM3NTV0FaUzlRWndBc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUmNIc1NBMXB6U3ZMS3l1WVFSMEJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0QkdENXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtVm1LMExrSzFaMnNTV0FaUzlRWndBc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUmNIc1NBMXB6U3ZMS3l1WVFSMEJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0QkdENXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtVm1LMExrSzFaMXNTV0FaUzlRWndBc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUmNIc1NBMXB6U3ZMS3l1WVFSMEJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3Q2ClpHTzhGeUR0QkdENXNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_19",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "09:45",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "11:00",
            "flight_id": "ID 6369",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUjJLMExqSzFaa0Fha0ZHR09zRG1SMkswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUjJLMExqSzFaa0FLa0ZHR09zRG1SMkswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUjJLMExqSzFaa0FVa0ZHR09zRG1SMkswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUjJLMExqSzFaa1oza0ZHR09zRG1SMkswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUjJLMExqSzFaa1pha0ZHR09zRG1SMkswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUjJLMExqSzFaa1pLa0ZHR09zRG1SMkswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUjJLMExqSzFaa1pVa0ZHR09zRG1SMkswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUjJLMExqSzFaNXNTV0FaUzlRWkdNc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUjJLMExqSzFaNHNTV0FaUzlRWkdNc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUjJLMExqSzFaM3NTV0FaUzlRWkdNc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUjJLMExqSzFaMnNTV0FaUzlRWkdNc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUjJLMExqSzFaMXNTV0FaUzlRWkdNc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "Y"
              },
              {
                "available": 3,
                "code": "Z",
                "flight_key": "R0dPc0RtUjJLMExqSzFaMHNTV0FaUzlRWkdNc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FBOEpha25zUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "Z"
              },
              {
                "available": 5,
                "code": "I",
                "flight_key": "R0dPc0RtUjJLMExqSzFabXNTV0FaUzlRWkdNc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FJOEZLa1dzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "I"
              },
              {
                "available": 7,
                "code": "D",
                "flight_key": "R0dPc0RtUjJLMExqSzFabHNTV0FaUzlRWkdNc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEVVa1JzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "D"
              },
              {
                "available": 7,
                "code": "C",
                "flight_key": "R0dPc0RtUjJLMExqSzFaanNTV0FaUzlRWkdNc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEQza1FzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "C"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "15:00",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "17:20",
            "flight_id": "ID 6890",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 6,
                "code": "V",
                "flight_key": "R0dPc0RtUjJLMExrSzFaa0JVa0ZHR09zRG1SMkswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqMnNTTThJYWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtUjJLMExrSzFaa0Eza0ZHR09zRG1SMkswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUjJLMExrSzFaa0Fha0ZHR09zRG1SMkswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUjJLMExrSzFaa0FLa0ZHR09zRG1SMkswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUjJLMExrSzFaa0FVa0ZHR09zRG1SMkswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUjJLMExrSzFaa1oza0ZHR09zRG1SMkswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUjJLMExrSzFaa1pha0ZHR09zRG1SMkswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUjJLMExrSzFaa1pLa0ZHR09zRG1SMkswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUjJLMExrSzFaa1pVa0ZHR09zRG1SMkswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUjJLMExrSzFaNXNTV0FaUzlRWkdNc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUjJLMExrSzFaNHNTV0FaUzlRWkdNc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUjJLMExrSzFaM3NTV0FaUzlRWkdNc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUjJLMExrSzFaMnNTV0FaUzlRWkdNc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUjJLMExrSzFaMXNTV0FaUzlRWkdNc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              },
              {
                "available": 2,
                "code": "Z",
                "flight_key": "R0dPc0RtUjJLMExrSzFaMHNTV0FaUzlRWkdNc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FXOEpha25zUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Z"
              },
              {
                "available": 4,
                "code": "I",
                "flight_key": "R0dPc0RtUjJLMExrSzFabXNTV0FaUzlRWkdNc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FFOEZLa1dzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "I"
              },
              {
                "available": 6,
                "code": "D",
                "flight_key": "R0dPc0RtUjJLMExrSzFabHNTV0FaUzlRWkdNc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FNOEVVa1JzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "D"
              },
              {
                "available": 7,
                "code": "C",
                "flight_key": "R0dPc0RtUjJLMExrSzFaanNTV0FaUzlRWkdNc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEQza1FzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "C"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_20",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "11:25",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "12:40",
            "flight_id": "ID 6363",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 7,
                "code": "V",
                "flight_key": "R0dPc0RtUjFLMExqSzFaa0JVa0ZHR09zRG1SMUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTTThJYWtXRVVrWG8ycWRMSmd1cGFFdVlRUmtCd1Yxc1JjdW4yU2xxVFJmWkdWNkFRTzhGSER0CkF3WjJaM2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtUjFLMExqSzFaa0Eza0ZHR09zRG1SMUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtXRVVrWG8ycWRMSmd1cGFFdVlRUmtCd1Yxc1JjdW4yU2xxVFJmWkdWNkFRTzhGSER0CkF3WjJaM2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUjFLMExqSzFaa0Fha0ZHR09zRG1SMUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tXRVVrWG8ycWRMSmd1cGFFdVlRUmtCd1Yxc1JjdW4yU2xxVFJmWkdWNkFRTzhGSER0CkF3WjJaM2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUjFLMExqSzFaa0FLa0ZHR09zRG1SMUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtXRVVrWG8ycWRMSmd1cGFFdVlRUmtCd1Yxc1JjdW4yU2xxVFJmWkdWNkFRTzhGSER0CkF3WjJaM2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUjFLMExqSzFaa0FVa0ZHR09zRG1SMUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tXRVVrWG8ycWRMSmd1cGFFdVlRUmtCd1Yxc1JjdW4yU2xxVFJmWkdWNkFRTzhGSER0CkF3WjJaM2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUjFLMExqSzFaa1oza0ZHR09zRG1SMUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtXRVVrWG8ycWRMSmd1cGFFdVlRUmtCd1Yxc1JjdW4yU2xxVFJmWkdWNkFRTzhGSER0CkF3WjJaM2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUjFLMExqSzFaa1pha0ZHR09zRG1SMUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tXRVVrWG8ycWRMSmd1cGFFdVlRUmtCd1Yxc1JjdW4yU2xxVFJmWkdWNkFRTzhGSER0CkF3WjJaM2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUjFLMExqSzFaa1pLa0ZHR09zRG1SMUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtXRVVrWG8ycWRMSmd1cGFFdVlRUmtCd1Yxc1JjdW4yU2xxVFJmWkdWNkFRTzhGSER0CkF3WjJaM2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUjFLMExqSzFaa1pVa0ZHR09zRG1SMUswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtXRVVrWG8ycWRMSmd1cGFFdVlRUmtCd1Yxc1JjdW4yU2xxVFJmWkdWNkFRTzhGSER0CkF3WjJaM2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUjFLMExqSzFaNXNTV0FaUzlRWkdJc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUnlSc1JjaU0yY3VuMlNscVRSZlpHUjZad0k4RnpTZUxLVzBMRmprWndiMFpVa1dFUE4yClptTG1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUjFLMExqSzFaNHNTV0FaUzlRWkdJc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUnlSc1JjaU0yY3VuMlNscVRSZlpHUjZad0k4RnpTZUxLVzBMRmprWndiMFpVa1dFUE4yClptTG1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUjFLMExqSzFaM3NTV0FaUzlRWkdJc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUnlSc1JjaU0yY3VuMlNscVRSZlpHUjZad0k4RnpTZUxLVzBMRmprWndiMFpVa1dFUE4yClptTG1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUjFLMExqSzFaMnNTV0FaUzlRWkdJc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUnlSc1JjaU0yY3VuMlNscVRSZlpHUjZad0k4RnpTZUxLVzBMRmprWndiMFpVa1dFUE4yClptTG1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUjFLMExqSzFaMXNTV0FaUzlRWkdJc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUnlSc1JjaU0yY3VuMlNscVRSZlpHUjZad0k4RnpTZUxLVzBMRmprWndiMFpVa1dFUE4yClptTG1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "Y"
              },
              {
                "available": 3,
                "code": "Z",
                "flight_key": "R0dPc0RtUjFLMExqSzFaMHNTV0FaUzlRWkdJc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FBOEpha25zUnlSc1JjaU0yY3VuMlNscVRSZlpHUjZad0k4RnpTZUxLVzBMRmprWndiMFpVa1dFUE4yClptTG1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "Z"
              },
              {
                "available": 5,
                "code": "I",
                "flight_key": "R0dPc0RtUjFLMExqSzFabXNTV0FaUzlRWkdJc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FJOEZLa1dzUnlSc1JjaU0yY3VuMlNscVRSZlpHUjZad0k4RnpTZUxLVzBMRmprWndiMFpVa1dFUE4yClptTG1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "I"
              },
              {
                "available": 7,
                "code": "D",
                "flight_key": "R0dPc0RtUjFLMExqSzFabHNTV0FaUzlRWkdJc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEVVa1JzUnlSc1JjaU0yY3VuMlNscVRSZlpHUjZad0k4RnpTZUxLVzBMRmprWndiMFpVa1dFUE4yClptTG1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "D"
              },
              {
                "available": 7,
                "code": "C",
                "flight_key": "R0dPc0RtUjFLMExqSzFaanNTV0FaUzlRWkdJc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEQza1FzUnlSc1JjaU0yY3VuMlNscVRSZlpHUjZad0k4RnpTZUxLVzBMRmprWndiMFpVa1dFUE4yClptTG1zUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "C"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "15:00",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "CGK",
            "time_arrive": "17:20",
            "flight_id": "ID 6890",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 6,
                "code": "V",
                "flight_key": "R0dPc0RtUjFLMExrSzFaa0JVa0ZHR09zRG1SMUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqMnNTTThJYWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "V"
              },
              {
                "available": 7,
                "code": "T",
                "flight_key": "R0dPc0RtUjFLMExrSzFaa0Eza0ZHR09zRG1SMUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTRThJVWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "T"
              },
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtUjFLMExrSzFaa0Fha0ZHR09zRG1SMUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtUjFLMExrSzFaa0FLa0ZHR09zRG1SMUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtUjFLMExrSzFaa0FVa0ZHR09zRG1SMUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtUjFLMExrSzFaa1oza0ZHR09zRG1SMUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtUjFLMExrSzFaa1pha0ZHR09zRG1SMUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtUjFLMExrSzFaa1pLa0ZHR09zRG1SMUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtUjFLMExrSzFaa1pVa0ZHR09zRG1SMUswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtXRVVrWExKZ3VwYUV1WVFSMUJ3TmpzUjF5TVRTaFZSZzFMSmt1VlI1dW9LSGZaR3A2Clp3TzhGSER0QXd0NVpVamxBRjFPcUpwZ1p3TmtBdD09Cg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtUjFLMExrSzFaNXNTV0FaUzlRWkdJc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtUjFLMExrSzFaNHNTV0FaUzlRWkdJc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtUjFLMExrSzFaM3NTV0FaUzlRWkdJc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtUjFLMExrSzFaMnNTV0FaUzlRWkdJc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtUjFLMExrSzFaMXNTV0FaUzlRWkdJc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Y"
              },
              {
                "available": 2,
                "code": "Z",
                "flight_key": "R0dPc0RtUjFLMExrSzFaMHNTV0FaUzlRWkdJc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FXOEpha25zUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "Z"
              },
              {
                "available": 4,
                "code": "I",
                "flight_key": "R0dPc0RtUjFLMExrSzFabXNTV0FaUzlRWkdJc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FFOEZLa1dzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "I"
              },
              {
                "available": 6,
                "code": "D",
                "flight_key": "R0dPc0RtUjFLMExrSzFabHNTV0FaUzlRWkdJc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FNOEVVa1JzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "D"
              },
              {
                "available": 7,
                "code": "C",
                "flight_key": "R0dPc0RtUjFLMExrSzFaanNTV0FaUzlRWkdJc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEQza1FzUnlSc1JjdW4yU2xxVFJmWkdINlpRTzhHSkl4TEo0dEYzSXVvVFJ0R3pTZ3FGamtBbWJsClpVa1dFUE4yQlF4anNRVjFZSFMxTWwwbFpRUjIK",
                "class": "C"
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_21",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "09:45",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "JOG",
            "time_arrive": "11:00",
            "flight_id": "ID 6369",
            "area_arrive": "CGK",
            "seat": [
              {
                "available": 7,
                "code": "Q",
                "flight_key": "R0dPc0RtVmpLMExqSzFaa0Fha0ZHR09zRG1WakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NTUzhIS2tXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "Q"
              },
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtVmpLMExqSzFaa0FLa0ZHR09zRG1WakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtVmpLMExqSzFaa0FVa0ZHR09zRG1WakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtVmpLMExqSzFaa1oza0ZHR09zRG1WakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtVmpLMExqSzFaa1pha0ZHR09zRG1WakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtVmpLMExqSzFaa1pLa0ZHR09zRG1WakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtVmpLMExqSzFaa1pVa0ZHR09zRG1WakswTGpzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtXRVVrWG8ycWRMSmd1cGFFdVlRTjVCd0Qxc1JjdW4yU2xxVFJmWkdSNlpRTzhGSER0CkF3WjJCS2psQUYxT3FKcGdad05rQXQ9PQo=",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtVmpLMExqSzFaNXNTV0FaUzlRWndPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtVmpLMExqSzFaNHNTV0FaUzlRWndPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtVmpLMExqSzFaM3NTV0FaUzlRWndPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtVmpLMExqSzFaMnNTV0FaUzlRWndPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtVmpLMExqSzFaMXNTV0FaUzlRWndPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "Y"
              },
              {
                "available": 3,
                "code": "Z",
                "flight_key": "R0dPc0RtVmpLMExqSzFaMHNTV0FaUzlRWndPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FBOEpha25zUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "Z"
              },
              {
                "available": 5,
                "code": "I",
                "flight_key": "R0dPc0RtVmpLMExqSzFabXNTV0FaUzlRWndPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FJOEZLa1dzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "I"
              },
              {
                "available": 7,
                "code": "D",
                "flight_key": "R0dPc0RtVmpLMExqSzFabHNTV0FaUzlRWndPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEVVa1JzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "D"
              },
              {
                "available": 7,
                "code": "C",
                "flight_key": "R0dPc0RtVmpLMExqSzFaanNTV0FaUzlRWndPc0V3TzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEQza1FzUnlSc1JjaU0yY3VuMlNscVRSZlpReDZBUUk4RnpTZUxLVzBMRmprWkdialpVa1dFUE4yClptTDVzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "C"
              }
            ]
          },
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "15:45",
            "date_arrive": "25-Aug-2016",
            "date_depart": "25-Aug-2016",
            "area_depart": "HLP",
            "time_arrive": "18:00",
            "flight_id": "ID 7015",
            "area_arrive": "KNO",
            "seat": [
              {
                "available": 7,
                "code": "N",
                "flight_key": "R0dPc0RtVmpLMExrSzFaa0FLa0ZHR09zRG1WakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSNThHYWtXRVVrWExKZ3VwYUV1VlJ1dW9UeWdWU095cHpFdW96U2VxS0Exb0pSZlpHSDZBUUk4CkdKSXhMSjR0RjNJdW9UUnRHelNncUZqa0JRYmpaVWtXRVBOM1pRUjFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "N"
              },
              {
                "available": 7,
                "code": "M",
                "flight_key": "R0dPc0RtVmpLMExrSzFaa0FVa0ZHR09zRG1WakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSMThHS2tXRVVrWExKZ3VwYUV1VlJ1dW9UeWdWU095cHpFdW96U2VxS0Exb0pSZlpHSDZBUUk4CkdKSXhMSjR0RjNJdW9UUnRHelNncUZqa0JRYmpaVWtXRVBOM1pRUjFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "M"
              },
              {
                "available": 7,
                "code": "L",
                "flight_key": "R0dPc0RtVmpLMExrSzFaa1oza0ZHR09zRG1WakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSazhHVWtXRVVrWExKZ3VwYUV1VlJ1dW9UeWdWU095cHpFdW96U2VxS0Exb0pSZlpHSDZBUUk4CkdKSXhMSjR0RjNJdW9UUnRHelNncUZqa0JRYmpaVWtXRVBOM1pRUjFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "L"
              },
              {
                "available": 7,
                "code": "K",
                "flight_key": "R0dPc0RtVmpLMExrSzFaa1pha0ZHR09zRG1WakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSZzhGM2tXRVVrWExKZ3VwYUV1VlJ1dW9UeWdWU095cHpFdW96U2VxS0Exb0pSZlpHSDZBUUk4CkdKSXhMSjR0RjNJdW9UUnRHelNncUZqa0JRYmpaVWtXRVBOM1pRUjFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "K"
              },
              {
                "available": 7,
                "code": "H",
                "flight_key": "R0dPc0RtVmpLMExrSzFaa1pLa0ZHR09zRG1WakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSdThGVWtXRVVrWExKZ3VwYUV1VlJ1dW9UeWdWU095cHpFdW96U2VxS0Exb0pSZlpHSDZBUUk4CkdKSXhMSjR0RjNJdW9UUnRHelNncUZqa0JRYmpaVWtXRVBOM1pRUjFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "H"
              },
              {
                "available": 7,
                "code": "B",
                "flight_key": "R0dPc0RtVmpLMExrSzFaa1pVa0ZHR09zRG1WakswTGtzUmNDRTNrWUd4OThaS2pqc1FPOFp3SHlad09PcUpweVp3TmxaUVIyc1FWMQpXR1ZqREtJYVdHVmpad05rQWFqM3NSVzhEYWtXRVVrWExKZ3VwYUV1VlJ1dW9UeWdWU095cHpFdW96U2VxS0Exb0pSZlpHSDZBUUk4CkdKSXhMSjR0RjNJdW9UUnRHelNncUZqa0JRYmpaVWtXRVBOM1pRUjFzUVYxWUhTMU1sMGxaUVIyCg==",
                "class": "B"
              },
              {
                "available": 7,
                "code": "S",
                "flight_key": "R0dPc0RtVmpLMExrSzFaNXNTV0FaUzlRWndPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEgza0dzUnlSc1JjdW4yU2xxVFJ0RlRTZm5KMHRIVElsTVRTaExKZzFwM0lnTEZqa0FHYjBBS2tBCk1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSNEJ3TmpzUnlSVlFwalpHSThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "S"
              },
              {
                "available": 7,
                "code": "W",
                "flight_key": "R0dPc0RtVmpLMExrSzFaNHNTV0FaUzlRWndPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEkza0tzUnlSc1JjdW4yU2xxVFJ0RlRTZm5KMHRIVElsTVRTaExKZzFwM0lnTEZqa0FHYjBBS2tBCk1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSNEJ3TmpzUnlSVlFwalpHSThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "W"
              },
              {
                "available": 7,
                "code": "G",
                "flight_key": "R0dPc0RtVmpLMExrSzFaM3NTV0FaUzlRWndPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEUza1VzUnlSc1JjdW4yU2xxVFJ0RlRTZm5KMHRIVElsTVRTaExKZzFwM0lnTEZqa0FHYjBBS2tBCk1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSNEJ3TmpzUnlSVlFwalpHSThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "G"
              },
              {
                "available": 7,
                "code": "A",
                "flight_key": "R0dPc0RtVmpLMExrSzFaMnNTV0FaUzlRWndPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOERLa09zUnlSc1JjdW4yU2xxVFJ0RlRTZm5KMHRIVElsTVRTaExKZzFwM0lnTEZqa0FHYjBBS2tBCk1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSNEJ3TmpzUnlSVlFwalpHSThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "A"
              },
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtVmpLMExrSzFaMXNTV0FaUzlRWndPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEpLa01zUnlSc1JjdW4yU2xxVFJ0RlRTZm5KMHRIVElsTVRTaExKZzFwM0lnTEZqa0FHYjBBS2tBCk1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSNEJ3TmpzUnlSVlFwalpHSThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "Y"
              },
              {
                "available": 3,
                "code": "Z",
                "flight_key": "R0dPc0RtVmpLMExrSzFaMHNTV0FaUzlRWndPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FBOEpha25zUnlSc1JjdW4yU2xxVFJ0RlRTZm5KMHRIVElsTVRTaExKZzFwM0lnTEZqa0FHYjBBS2tBCk1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSNEJ3TmpzUnlSVlFwalpHSThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "Z"
              },
              {
                "available": 6,
                "code": "I",
                "flight_key": "R0dPc0RtVmpLMExrSzFabXNTV0FaUzlRWndPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FNOEZLa1dzUnlSc1JjdW4yU2xxVFJ0RlRTZm5KMHRIVElsTVRTaExKZzFwM0lnTEZqa0FHYjBBS2tBCk1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSNEJ3TmpzUnlSVlFwalpHSThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "I"
              },
              {
                "available": 7,
                "code": "D",
                "flight_key": "R0dPc0RtVmpLMExrSzFabHNTV0FaUzlRWndPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEVVa1JzUnlSc1JjdW4yU2xxVFJ0RlRTZm5KMHRIVElsTVRTaExKZzFwM0lnTEZqa0FHYjBBS2tBCk1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSNEJ3TmpzUnlSVlFwalpHSThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "D"
              },
              {
                "available": 7,
                "code": "C",
                "flight_key": "R0dPc0RtVmpLMExrSzFaanNTV0FaUzlRWndPc0V3UzhGeDlVc1JnQkczamtzUU84WlVqbEFGSGxaUlMxTWxIbFpRVmpaR004WndIeQpad09PcUpweVp3TmxaUVIyc1FxOEQza1FzUnlSc1JjdW4yU2xxVFJ0RlRTZm5KMHRIVElsTVRTaExKZzFwM0lnTEZqa0FHYjBBS2tBCk1KRXVvdk9ZcUpTZkxGT0JMSjExWVFSNEJ3TmpzUnlSVlFwalpHSThad0hnREtJYVlHVmpaR0w9Cg==",
                "class": "C"
              }
            ]
          }
        ]
      }
    ],
    "airline": "lion"
  }
}
		';
	}

	function jsonbooking(){
		return '
		{
  "code": 200,
  "results": {
    "time_limit": 1467276060,
    "contact_details": {
      "phone": "087877654454",
      "nama": "yadi"
    },
    "passenger": [
      {
        "birth_date": "0-0-0",
        "passenger_type": 1,
        "full_name": "Mr yadi"
      }
    ],
    "flight_details": [
      {
        "flight_number": "ID 6196",
        "area_depart": "Jakarta",
        "time_arrive": "02:50",
        "time_depart": "23:30",
        "area_arrive": "Ujung Pandang",
        "flight_date": "2016-7-02"
      }
    ],
    "maskapai": "lion",
    "booking_code": "SVSKYY"
  }
}

		';
	}

	function jsonbestprice(){
		return '
		{
  "code": 200,
  "results": {
    "data_ret": [],
    "data": [
      {
        "id_perjalanan": "lion_1",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "06:50",
            "date_arrive": "19-Jul-2016",
            "date_depart": "19-Jul-2016",
            "area_depart": "BPN",
            "time_arrive": "12:20",
            "flight_list": [
              {
                "time_depart": "06:50",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "BPN",
                "time_arrive": "07:20",
                "flight_id": "JT 367",
                "area_arrive": "SUB"
              },
              {
                "time_depart": "11:10",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "SUB",
                "time_arrive": "12:20",
                "flight_id": "IW 1811",
                "area_arrive": "JOG"
              }
            ],
            "flight_id": "JT 367 / IW 1811",
            "area_arrive": "JOG",
            "seat": [
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtSXNFd09zSG1IeUEwQUFaUzlRQUk5VFpTOUdBS2tGR0dPc0RtSXNFd084RHlPQnNSY0NFM2psc1FPOFpVamtCRkhsWlJjMQpvUEhsWlFWalpHTThaR3h5WndPWHFKanlad05sWlFSMnNRcThKS2tNc1JjSHNSV3VvVHllcFRTakxKNGZaUUw2QUdPOEgzSWxMSld1CnJKUmZaUXA2WndPOEZ5RHRabUwzc1FSNVlIYzFvUDBsWlFSMgo=",
                "class": "Y",
                "best_price": {
                  "fare": 5293200,
                  "total_price": 5463200,
                  "tax": 170000
                }
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_2",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "06:30",
            "date_arrive": "19-Jul-2016",
            "date_depart": "19-Jul-2016",
            "area_depart": "BPN",
            "time_arrive": "10:35",
            "flight_list": [
              {
                "time_depart": "06:30",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "BPN",
                "time_arrive": "07:30",
                "flight_id": "ID 6251",
                "area_arrive": "CGK"
              },
              {
                "time_depart": "09:25",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "CGK",
                "time_arrive": "10:35",
                "flight_id": "ID 6364",
                "area_arrive": "JOG"
              }
            ],
            "flight_id": "ID 6251 / ID 6364",
            "area_arrive": "JOG",
            "seat": [
              {
                "available": 7,
                "code": "C",
                "flight_key": "R0dPc0RtQXNFd09zSG1OeUEwQUFaUzlRWjE5VFpTOUdaVWtGR0dPc0RtQXNFd084RHlPQnNSY0NFM2psc1FPOFpVamtCRkhsWlJjMQpvUEhsWlFWalpHTThaR3h5WndPWHFKanlad05sWlFSMnNRcThEM2tRc1J5UnNSV3VvVHllcFRTakxKNGZaUUw2Wm1POEZ6U2VMS1cwCkxGampBbWJtWlVrV0VQTjJad0hrc1FSNVlIYzFvUDBsWlFSMgo=",
                "class": "C",
                "best_price": {
                  "fare": 11462000,
                  "total_price": 11752000,
                  "tax": 290000
                }
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_3",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "08:15",
            "date_arrive": "19-Jul-2016",
            "date_depart": "19-Jul-2016",
            "area_depart": "BPN",
            "time_arrive": "12:20",
            "flight_list": [
              {
                "time_depart": "08:15",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "BPN",
                "time_arrive": "08:45",
                "flight_id": "JT 731",
                "area_arrive": "SUB"
              },
              {
                "time_depart": "11:10",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "SUB",
                "time_arrive": "12:20",
                "flight_id": "IW 1811",
                "area_arrive": "JOG"
              }
            ],
            "flight_id": "JT 731 / IW 1811",
            "area_arrive": "JOG",
            "seat": [
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtTXNFd09zSG1IeUEwQUFaUzlRQXk5VFpTOUdBS2tGR0dPc0RtTXNFd084RHlPQnNSY0NFM2psc1FPOFpVamtCRkhsWlJjMQpvUEhsWlFWalpHTThaR3h5WndPWHFKanlad05sWlFSMnNRcThKS2tNc1JjSHNSV3VvVHllcFRTakxKNGZaUXQ2WkdJOEgzSWxMSld1CnJKUmZaUXQ2QVFJOEZ5RHRBbVprc1FSNVlIYzFvUDBsWlFSMgo=",
                "class": "Y",
                "best_price": {
                  "fare": 5293200,
                  "total_price": 5463200,
                  "tax": 170000
                }
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_4",
        "flight_count": 1,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "18:30",
            "date_arrive": "19-Jul-2016",
            "date_depart": "19-Jul-2016",
            "area_depart": "BPN",
            "time_arrive": "19:15",
            "flight_list": [
              {
                "time_depart": "18:30",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "BPN",
                "time_arrive": "19:15",
                "flight_id": "JT 677",
                "area_arrive": "JOG"
              }
            ],
            "flight_id": "JT 677",
            "area_arrive": "JOG",
            "seat": [
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtV3NFd09zSG1JOEh4MGpLMFpsSzBManNSV0RHYWtYRzBxOFphampzUU84Wkd4eVp3T1hxSmp5WndObFpRUjJzUVI1V0dWagpGYUlmV0dWalp3TmtBYWozc1N5OEpLa1hJVWtQTEprY24zT3VwVFNoWVFSNEJ3WmpzUmNpTTJjdW4yU2xxVFJmWkd4NlpHSThGeUR0CkF3cDNzUVI1WUhjMW9QMGxaUVIyCg==",
                "class": "Y",
                "best_price": {
                  "fare": 3253800,
                  "total_price": 3413800,
                  "tax": 160000
                }
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_5",
        "flight_count": 2,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "09:15",
            "date_arrive": "19-Jul-2016",
            "date_depart": "19-Jul-2016",
            "area_depart": "BPN",
            "time_arrive": "12:20",
            "flight_list": [
              {
                "time_depart": "09:15",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "BPN",
                "time_arrive": "09:45",
                "flight_id": "JT 361",
                "area_arrive": "SUB"
              },
              {
                "time_depart": "11:10",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "SUB",
                "time_arrive": "12:20",
                "flight_id": "IW 1811",
                "area_arrive": "JOG"
              }
            ],
            "flight_id": "JT 361 / IW 1811",
            "area_arrive": "JOG",
            "seat": [
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtRXNFd09zSG1IeUEwQUFaUzlRQVM5VFpTOUdBS2tGR0dPc0RtRXNFd084RHlPQnNSY0NFM2psc1FPOFpVamtCRkhsWlJjMQpvUEhsWlFWalpHTThaR3h5WndPWHFKanlad05sWlFSMnNRcThKS2tNc1JjSHNSV3VvVHllcFRTakxKNGZaUXg2WkdJOEgzSWxMSld1CnJKUmZaUXg2QVFJOEZ5RHRabUxrc1FSNVlIYzFvUDBsWlFSMgo=",
                "class": "Y",
                "best_price": {
                  "fare": 5293200,
                  "total_price": 5463200,
                  "tax": 170000
                }
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_6",
        "flight_count": 1,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "11:40",
            "date_arrive": "19-Jul-2016",
            "date_depart": "19-Jul-2016",
            "area_depart": "BPN",
            "time_arrive": "12:25",
            "flight_list": [
              {
                "time_depart": "11:40",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "BPN",
                "time_arrive": "12:25",
                "flight_id": "JT 669",
                "area_arrive": "JOG"
              }
            ],
            "flight_id": "JT 669",
            "area_arrive": "JOG",
            "seat": [
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtU3NFd09zSG1JOEh4MGpLMFprSzBManNSV0RHYWtYRzBxOFphampzUU84Wkd4eVp3T1hxSmp5WndObFpRUjJzUVI1V0dWagpGYUlmV0dWalp3TmtBYWozc1N5OEpLa1hJVWtQTEprY24zT3VwVFNoWVFSa0J3RGpzUmNpTTJjdW4yU2xxVFJmWkdWNlp3SThGeUR0CkF3TDVzUVI1WUhjMW9QMGxaUVIyCg==",
                "class": "Y",
                "best_price": {
                  "fare": 3253800,
                  "total_price": 3413800,
                  "tax": 160000
                }
              }
            ]
          }
        ]
      },
      {
        "id_perjalanan": "lion_7",
        "flight_count": 1,
        "detail": [
          {
            "airline_icon": "http://52.36.25.143:8989/static/lion.png",
            "time_depart": "06:00",
            "date_arrive": "19-Jul-2016",
            "date_depart": "19-Jul-2016",
            "area_depart": "BPN",
            "time_arrive": "06:45",
            "flight_list": [
              {
                "time_depart": "06:00",
                "date_arrive": "19-Jul-2016",
                "date_depart": "19-Jul-2016",
                "area_depart": "BPN",
                "time_arrive": "06:45",
                "flight_id": "JT 667",
                "area_arrive": "JOG"
              }
            ],
            "flight_id": "JT 667",
            "area_arrive": "JOG",
            "seat": [
              {
                "available": 7,
                "code": "Y",
                "flight_key": "R0dPc0RtT3NFd09zSG1JOEh4MGpLMFpqSzBManNSV0RHYWtYRzBxOFphampzUU84Wkd4eVp3T1hxSmp5WndObFpRUjJzUVI1V0dWagpGYUlmV0dWalp3TmtBYWozc1N5OEpLa1hJVWtQTEprY24zT3VwVFNoWVFOMkJ3TmpzUmNpTTJjdW4yU2xxVFJmWlFMNkFRSThGeUR0CkF3TDNzUVI1WUhjMW9QMGxaUVIyCg==",
                "class": "Y",
                "best_price": {
                  "fare": 3253800,
                  "total_price": 3413800,
                  "tax": 160000
                }
              }
            ]
          }
        ]
      }
    ],
    "airline": "lion"
  }
}
		';
	}
	
	function getfare(){
		return '{"code": 200,
"results": {
"fare": 407000,
"total_price": 447000,
"tax": 40000
}
}';
	}

	function jsonbookingdetail(){
		
	}
}


