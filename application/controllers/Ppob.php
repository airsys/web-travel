<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Ppob extends CI_Controller {
	
	function __construct() {
	     parent::__construct();
	     $this->load->helper('ppob');
	     if (!$this->ion_auth->logged_in())
		{
			redirect('airlines/', 'refresh');
		}
	 }
	
	function index(){
		echo "PPOB page";
	}
	
	function tagihan(){
		$data = array('content'=>'ppob/tagihan',
					  );
		$this->load->view("index",$data);		
	}
	
	function cek_saldo_indsiti(){
		pr(cekSaldoPpob());
	}
	
	function cek_tagihan(){
		$result = json_encode(ppobxml(get('idpelanggan'),get('oprcode'),'charge','1'));
		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($result);
	}
		
}