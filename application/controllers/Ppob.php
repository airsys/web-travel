<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Ppob extends CI_Controller {
	
	function __construct() {
	     parent::__construct();
	     $this->load->helper('ppob');
	     $this->load->model('m_ppob');
	     if (!$this->ion_auth->logged_in())
		{
			redirect('airlines/', 'refresh');
		}
	 }
	
	function index(){
		echo hargaPulsa('XL.5');
	}
	
	function tagihan(){
		$data = array('content'=>'ppob/tagihan',
					  );
		$this->load->view("index",$data);		
	}
	
	function pulsa(){
		$data = array('content'=>'ppob/pulsa',
					  );
		$this->load->view("index",$data);		
	}
	
	function bayar(){
		$price = $this->m_ppob->get_price();
		$return = [];
		//$saldoserver = cekSaldoPpob();
		if(saldo() > $price){
			if($price > 1){
				$my_trxid = now().'_'.RandomString(3);
				$nomer = post('nomer');	$nominal = post('nominal');
				$id = $this->m_ppob->insert_pulsa($my_trxid);				
				$return = ppobxml($nomer,$nominal,'charge',$my_trxid);
				if($return['resultcode']!=0){
					$this->m_ppob->update_pulsa(array('message'=>$return['message'], 'trxid'=>$return['trxid'], 
							'ref_trxid'=>$my_trxid, 'status'=>$return['resultcode']));
					$return = array('message'=>'Pulsa gagal diisi',
							'code'=>1);
				}else{
					$this->m_ppob->issued($id,$nominal);
					$this->m_ppob->update_pulsa(array('message'=>$return['message'], 'trxid'=>$return['trxid'], 
					 		'ref_trxid'=>$my_trxid, 'status'=>$return['resultcode']));
				}
			}else{
				$return = array('message'=>'Operator tidak terdaftar',
							'code'=>1);	
			}
		}else{
			$return = array('message'=>'saldo anda tidak cukup',
							'code'=>1);	
		}
		return $this->output
	            ->set_content_type('application/json')
	            ->set_status_header(200)
	            ->set_output(json_encode($return));
	}
	
	function no_prefix(){
		$str = file_get_contents(base_url().'assets/ajax/no_prefix.json');
		$no = json_decode($str,TRUE);
		$return = [];
		foreach($no as $val){
			$return[$val['number']] = $val;
		}
		return $this->output
	            ->set_content_type('application/json')
	            ->set_status_header(200)
	            ->set_output(json_encode($return));
	}
	
	function get_products($operator){
		$operator= strtolower($operator);
		$this->db->select('*')
				 ->from('`ppob product`')
				 ->where('`operator`',$operator)
				 ->order_by('nilai');
		$return = json_encode($this->db->get()->result());
		return $this->output
	            ->set_content_type('application/json')
	            ->set_status_header(200)
	            ->set_output($return);
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