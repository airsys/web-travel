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