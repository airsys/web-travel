<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

function airline(){
	$CI =& get_instance();
	switch($CI->input->post('tipe')){
		case 'lion':
			return 'lion';
			break;
		case 'sriwijaya':
			return 'sriwijaya';
			break;
		default:
			return 'lion';
			break;
	}
}

function post($name=''){
	$CI =& get_instance();
	return $CI->input->post($name);
}

function get($name=''){
	$CI =& get_instance();
	return $CI->input->get($name);
}

function session($name=''){
	$CI =& get_instance();
	return $CI->session->userdata($name);
}

function now(){
	$time =new DateTime();
	$time = $time->getTimestamp();
	return $time;
}


function RandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function pr($data='', $die=FALSE){
	$CI =& get_instance();
	if($die){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		die();
	}else{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
}
