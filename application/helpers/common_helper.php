<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

function airline(){
	$CI =& get_instance();
	if($CI->input->post('tipe')){
		return $CI->input->post('tipe');
	}else return 'lion';
}