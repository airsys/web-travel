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
