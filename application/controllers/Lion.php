<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lion extends CI_Controller {
	private $url ;
	 function __construct() {
	        parent::__construct();
	    $this->load->library('curl');		
		$this->load->library('form_validation');
		$this->curl->http_header('token', '4e3c1905241d447a9dc23512b8067811');
		$this->curl->option('TIMEOUT', 70000);
		$this->url = 'http://52.36.25.143:8989/lion';	
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
			//print_r($array);die();
			
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
		$this->form_validation->set_rules('key', 'KEY', 'required');
		
		$json = $this->curl->simple_get("$this->url/get_price?flight_key=$data[key]");
		//$json = $this->jsondata();
		
		$array = json_decode ($json);
		$hasil = array();
		$code = 200; //$this->form_validation->run() 
		if ($this->form_validation->run()== FALSE)
		{
			$hasil =  validation_errors();
			$code = 400;
		}else{
			if( ( empty($array) || $array->code==404) ){
				$code = 404;
				$hasil = 'tidak ada penerbangan';
			} else{
				$hasil = array('fare'=>$array->results->fare, 
								'tax'=>$array->results->tax, 
								'total_price'=>$array->results->total_price);
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
		$data = $this->input->get();
		//parse_str(utf8_decode(urldecode($data['data'])), $output);
		$hasil = array();
		$hasil['key'] = '';
		foreach($data as $key => $val){
			for($i = 1; $i <= sizeof($data['flightid']); $i++){
				if(!empty($data[$key][$i])){
					$hasil['seat'][$i][$key] = $data[$key][$i];
					if($key == 'key'){
						$hasil['key'] .= $data['key'][$i]. '|';
					}
				}else{
					$hasil[$key] = $data[$key];
				}
			}			
		}
		if($bestprice != 0){
			$hasil['key'] = $data['key'][1];
		}
		$hasil['segmen'] = sizeof($data['flightid']);
		//print_r($hasil);
		$data = array('content'=>'lion/booking', 
					  'data'=>$hasil
					  );
		$this->load->view("index",$data);
	}
	
	function booking_save(){
		$data = $this->input->post();	
		//echo "<pre>"; print_r($json);
		$this->form_validation->set_rules('contact_title', 'contact title', 'required');
		$this->form_validation->set_rules('contact_name', 'contact name', 'required');
		$this->form_validation->set_rules('contact_phone', 'contact phone', 'required');
		
		$hasil = '';
		$code = 200; //$this->form_validation->run() 
		if ($this->form_validation->run()  == FALSE)
		{
			$hasil =  validation_errors();
			$code = 400;
		}else{
			
			$json = $this->curl->simple_post("$this->url/book", $data, array(CURLOPT_BUFFERSIZE => 10, CURLOPT_TIMEOUT=>800000));
			//$json = $this->jsonbooking();
			
			$array = json_decode ($json);
			
			if( ( empty($array) || $array->code==404 || $array->code==204) ){
				$code = $array->code;
				$hasil = 'terjadi error saat input';
			} else{
				$hasil = $array->results->booking_code;
			}
			
		}
		//echo $hasil;die();
	return $this->output
            ->set_content_type('text/html')
            ->set_status_header($code)
            ->set_output($hasil);
	}
	
	function booking_detail($code=00){
		$json = $this->_boking_detail($code);
		$array = json_decode($json);
		//print_r($array);die();
		$data = array('content'=>'lion/booking_detail',
					  'data'=>$array->results,
					);
		
		$this->load->view("index",$data);
	}
	
	private function _boking_detail($code){
		$this->url = 'http://52.36.25.143:8989';
		$json = $this->curl->simple_get("$this->url/manage/book/$code");
		return $json;
	}
	
	function index(){
		$data = array('content'=>'lion/search');
		$this->load->view("index",$data);
	}
	function search_bestprice(){
		$data = array('content'=>'lion/search_bestprice');
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
		return '
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
}


