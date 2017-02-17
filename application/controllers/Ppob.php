<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Ppob extends CI_Controller {
	
	function __construct() {
	     parent::__construct();
	     $this->load->helper('ppob');
	     $this->load->model('m_ppob');
	     /*if (!$this->ion_auth->logged_in())
		{
			redirect('airlines/', 'refresh');
		}*/
	 }
	
	function index($sn=''){
		$data = array('content'=>'ppob/index',
			  );
		$this->load->view("index",$data);	
	}
	
	function tagihan(){
		$data = array('content'=>'ppob/tagihan',
					  );
		$this->load->view("index",$data);		
	}
	
	function pulsa()
	{
		$data = array('content'=>'ppob/pulsa',
					  );
		$this->load->view("index",$data);		
	}
	function pln()
	{
		$data = array('content'=>'ppob/pln',
					  );
		$this->load->view("index",$data);		
	}
	
	
	
	function sn(){
		if(file_get_contents('php://input')=='')
		{
		    // THROW EXCEPTION
		    echo "No data";die();
		}
		else
		{   
		    // get read-only stream for read raw data from the request body
		    $strRequest = file_get_contents('php://input');
		    //echo $strRequest;      
		}
		$this->db->insert("`system log`", array('code'=>'coba','log'=>$strRequest));
		$data = xml2array($strRequest);
		$sn = explode(":",$data['msg']);
		$sn = filter_var($sn[2], FILTER_SANITIZE_NUMBER_INT);
		//echo $sn;die();
		$data_update = array('sn operator'=>$sn);
		$this->db->where('trxid', $data['trxid']);
		$this->db->update('`ppob trx`', $data_update);
		
		$this->m_ppob->change_status($data['ref_trxid'],'success',$data['msg']);
		
		echo $sn;
		
	}
	
	//http://indsiti.com/ppob/refund?resultcode=1001&msisdn=62816888999&message=Refund&trxid=7552974&ref_trxid=54321
	function refund(){
		$this->m_ppob->update_pulsa(array('message'=>get('message'), 'trxid'=>get('trxid'), 
							'ref_trxid'=>get('ref_trxid'), 'status'=>get('resultcode')));
		$this->m_ppob->refund(get('ref_trxid'));
		echo "oke";
	}
	
	function bayar(){
		$productk = explode('_',post('nominal'));
		$ids = explode('|',$productk[1]);
		$productk = $productk[0];		
		
		$product = $this->m_ppob->get_products($ids[0],$ids[1],$productk);
		$product = array_shift($product);
		//pr($product,TRUE);
		$nta = $product['harga_asli'] + $product['penambahanDariIndsiti'];
		$base_price = $nta +  $product['penambahanDariCompany'];
		
		$price = $base_price;
		$return = [];
		$msg=''; $msgt='';
		//$saldoserver = cekSaldoPpob();
		if(saldo() > $price){
			if($price > 1){
				$my_trxid = now().'_'.RandomString(3);
				$nomer = post('nomer');
				
				$id = $this->m_ppob->insert_pulsa($my_trxid,$nomer,$productk);				
				$return = ppobxml($nomer,$product['kode'],'charge',$my_trxid);
				//pr($return);
				if($return['resultcode']!=0){
					$msg = explode(".",$return['message']);
					$this->m_ppob->update_pulsa(array('message'=>$msg[0], 'trxid'=>$return['trxid'], 
							'ref_trxid'=>$my_trxid, 'status'=>$return['resultcode'],
							'base_pricex'=>0, 'nta'=>0));
					$return = array('message'=>'Pulsa gagal diisi'."<br> message_sementara:$return[message]",
							'code'=>1);
				}else{
					$msg = explode(".",$return['message']);	
					$nilai_now = preg_replace("/[^0-9,.]/", "", $msg[2]);
					
					$nta = $nilai_now+$product['penambahanDariIndsiti'];
					$nta_old = $product['harga_asli'] + $product['penambahanDariIndsiti'];
					$base_price = $nta +$product['penambahanDariCompany'];
					
					if($nilai_now != $product['harga_asli']){
						$msgt = "<br><strong>Ada perubahan harga dari server, <br>
								Harga semula $nta_old berubah menjadi $nta</strong>";
						$this->m_ppob->change_nilai($productk, $nilai_now);
						//die();
					}
					
					$this->m_ppob->update_pulsa(array('message'=>$msg[0]."<br>".$nilai_now."".$msgt,
									'trxid'=>$return['trxid'], 'ref_trxid'=>$my_trxid, 'status'=>2222,
									"base_pricex"=>$base_price,'nta'=>$nta));
					
					$this->m_ppob->issued($id,$nta);
					$return = array('message'=>$msg[0]."<br>".$nilai_now."".$msgt,);
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
	
	function get_products(){
		$return = $this->m_ppob->get_products();
		//pr($return,TRUE);
		return $this->output
	            ->set_content_type('application/json')
	            ->set_status_header(200)
	            ->set_output(json_encode($return,JSON_NUMERIC_CHECK));
	}
	
	function cek_saldo_indsiti(){
		pr(cekSaldoPpob());
	}
	
	function markupFindsiti(){
		$data = $this->m_ppob->markupFindsiti();
		//pr($data,TRUE);
	}
	
	function cek_tagihan(){
				$return = [];
				$my_trxid = now().'_'.RandomString(3);
				$idpelanggan = post('idpelanggan');	
				$oprcode = post('oprcode');
				$id = $this->m_ppob->insert_tagihan($my_trxid);				
				$return = ppobxml($idpelanggan,$oprcode,'charge','1',$my_trxid);
				$this->m_ppob->update_tagihan(array('message'=>$return['message'], 'trxid'=>$return['trxid'], 
							'ref_trxid'=>$my_trxid, 'status'=>$return['resultcode']));


		//$result = json_encode(ppobxml(get('idpelanggan'),get('oprcode'),'charge','1'));
		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($return));
    }

    function telkom(){
	$data = array('content'=>'ppob/telkom',
					  );
	$this->load->view("index",$data);		
	}
	function bayarTelkom(){
		$productk = explode('_',post('nominal'));
		$ids = explode('|',$productk[1]);
		$productk = $productk[0];		
		
		$product = $this->m_ppob->get_products($ids[0],$ids[1],$productk);
		$product = array_shift($product);
		//pr($product,TRUE);
		$nta = $product['harga_asli'] + $product['penambahanDariIndsiti'];
		$base_price = $nta +  $product['penambahanDariCompany'];
		
		$price = $base_price;
		$return = [];
		$msg=''; $msgt='';
		//$saldoserver = cekSaldoPpob();
		if(saldo() > $price){
			if($price > 1){
				$my_trxid = now().'_'.RandomString(3);
				$nomer = post('nomer');
				$contact = post('contact');
				$email = post('email');
				$id = $this->m_ppob->insert_tagihan($my_trxid,$nomer,$productk,$contact,$email);				
				$return = ppobxml($nomer,$product['kode'],'charge',$my_trxid);
				//pr($return);
				if($return['resultcode']!=0){
					$msg = explode(".",$return['message']);
					$this->m_ppob->update_pulsa(array('message'=>$msg[0], 'trxid'=>$return['trxid'], 
							'ref_trxid'=>$my_trxid, 'status'=>$return['resultcode'],
							'base_pricex'=>0, 'nta'=>0));
					$return = array('message'=>'Transaksi gagal '."<br> message_sementara:$return[message]",
							'code'=>1);
					
				}else{
					$msg = explode(".",$return['message']);	
					$msgt = explode(" ",$msg[0]);
					$nilai_now = post('nominalbayar');
					$nta = $nilai_now+$product['penambahanDariIndsiti'];
					$nta_old = $product['harga_asli'] + $product['penambahanDariIndsiti'];
					$base_price = $nta +$product['penambahanDariCompany'];
					
					
					$this->m_ppob->update_tagihan(array('message'=>$msgt[4],
									'trxid'=>$return['trxid'], 'ref_trxid'=>$my_trxid, 'status'=>2222,
									"base_pricex"=>$base_price,'nta'=>$nta));
					
					$this->m_ppob->issued($id,$nta);
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
	
	function finance($id=0){
		$d = $this->m_ppob->finance($id);
		$this->load->helper('dropdown');
		$data = array('content'=>'ppob/finance',
					  'data'=>$d,
					  'product'=>listDataCustom('ppob product','kode','operator,nilai,markup,markup_default'),
					  'status'=>listDataCustom('ppob status','status',"note,created","where id_ppob=$id order by created desc"),
					  );
		$this->load->view("index",$data);
	}
		
}