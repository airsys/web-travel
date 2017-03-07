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
		$login['data'] = 1; 
		$login['message'] = ''; 
		if(! $this->ion_auth->logged_in()){
			$login = $this->_login();
		}
		if($login['data']==1){
			$login_message = $login['message'] ;
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
								'base_price'=>0, 'net_price'=>0, 'price'=>0));
						$return = array('message'=>$login_message.'Gagal Pembayaran'."<br> Telah terjadi kesalahan sistem, 
									silakan hubungi customer service kami untuk informasi lebih lanjut",
								'code'=>1,
								'login'=>$login['data'],);
					}else{
						$msg = explode(".",$return['message']);	
						$nilai_now = preg_replace("/[^0-9,.]/", "", $msg[2]);
						
						$base_price = $nilai_now+$product['penambahanDariIndsiti'];
						$base_price_old = $product['harga_asli'] + $product['penambahanDariIndsiti'];
						$price = $base_price +$product['penambahanDariCompany'];
						
						if($nilai_now != $product['harga_asli']){
							$msgt = "<br><strong>Ada perubahan harga dari server, <br>
									Harga semula $base_price_old berubah menjadi $base_price</strong>";
							$this->m_ppob->change_nilai($productk, $nilai_now);
							//die();
						}
						
						$this->m_ppob->update_pulsa(array('message'=>$msg[0]."<br>".$nilai_now."".$msgt,
										'trxid'=>$return['trxid'], 'ref_trxid'=>$my_trxid, 'status'=>2222,
										"base_price"=>$base_price,'price'=>$price, 'net_price'=>$price));
						
						$this->m_ppob->issued($id,$base_price);
						$return = array('message'=>$login_message.'Transaksi Berhasil'."<br>".$msgt,
										'code'=>0,
										'login'=>$login['data'],
										'id' => $id,
								        );
					}
				}else{
					$return = array('message'=>$login_message.'Operator tidak terdaftar',
								'code'=>1,
								'login'=>$login['data'],);	
				}
			}else{
				$return = array('message'=>$login_message.'saldo anda tidak cukup',
								'code'=>1,
								'login'=>$login['data'],);	
			}
		}else{
			$return = array('message'=>$login['message'],
							'code'=>1,
							'login'=>$login['data'],);	
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
		$return = []; $data=[];
		$my_trxid = now().'_'.RandomString(3);
		$idpelanggan = post('idpelanggan');	
		$productk = explode('_',post('product'));
		$ids = explode('|',$productk[1]);
		$productk = $productk[0];		
		
		$product = $this->m_ppob->get_products($ids[0],$ids[1],$productk);
		$product = array_shift($product);
				
		$data = ppobxml($idpelanggan,'CEK.TELKOM','charge',$my_trxid);
		
		if( preg_match("/gagal|sudah ada|tidak cukup/",strtolower($data['message']))){
			$return['message'] = $data['message'];
		}else{
			$harga = GetBetween($idpelanggan.'.','.',$data['message']);
			$return['harga_tagihan'] = $harga;
			  $harga = 2000+$harga; //markup 2000 dari datasel		
			$return['harga_server'] = $harga;
			$return['harga'] = $harga+$product['penambahanDariIndsiti']; //harga dari indsisti
			$return['harga_konsumen'] = $return['harga']+$product['penambahanDariCompany'];	//harga ke buyer
			$return['nama'] = GetBetween('a/n ',' '.$idpelanggan,$data['message']);
			$return['jenis'] = GetBetween('Tagihan ',' a/n',$data['message']);
			$return['message'] = "Tagihan $return[jenis] a/n $return[nama] <br>
			  					 sebesar Rp ".number_format($return['harga'])."<br>
			  					 Harga ke konsumen Rp ".number_format($return['harga_konsumen']);	
		}
		
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
		$productk = explode('_',post('product'));
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
		
		$nomer = post('nomer');
		$contact = post('contact');
		$harga_tagihan = post('harga_tagihan');
		$msisdn = "$nomer.$harga_tagihan.$contact";
		
		if(saldo() > $price){
			if($price > 1){
				$my_trxid = now().'_'.RandomString(3);
				$id = $this->m_ppob->insert_pulsa($my_trxid,$nomer,$productk);				
				$return = ppobxml($msisdn,$product['kode'],'charge',$my_trxid);
				//pr($return);
				if($return['resultcode']!=0){
					$msg = explode(".",$return['message']);
					$this->m_ppob->update_pulsa(array('message'=>$msg[0], 'trxid'=>$return['trxid'], 
							'ref_trxid'=>$my_trxid, 'status'=>$return['resultcode'],
							'base_price'=>0, 'net_price'=>0, 'price'=>0));
					$return = array('message'=>'Transaksi gagal '."<br> message_sementara:$return[message]",
							'code'=>1);
					
				}else{
					//Pembayaran Tagihan TELKOM a/n PT.ASTEL 0213863333 sebesar 63360. BERHASIL.Kode Ref: RRYAgQ110686. Saldo: Rp 1113204. No: 0213863333.Tgl 13092011 Jam 10:00 WIB, Transaksi Lancar
					$msg = explode("Saldo:",$return['message']);	
					//$msgt = explode(" ",$msg[0]);
					
					$harga_server = $harga_tagihan+2000;
					$base_price = $harga_server+$product['penambahanDariIndsiti'];
					$price = $base_price +$product['penambahanDariCompany'];					
					
					$this->m_ppob->update_pulsa(array('message'=>$msg[0],
									'trxid'=>$return['trxid'], 'ref_trxid'=>$my_trxid, 'status'=>2222,
									"base_price"=>$base_price,'price'=>$price, 'net_price'=>$price));
					
					$this->m_ppob->issued($id,$base_price);
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
	
	function confirm(){
		$productk = explode('_',post('nominal'));
		$ids = explode('|',$productk[1]);
		$productk = $productk[0];		
		
		$product = $this->m_ppob->get_products($ids[0],$ids[1],$productk);
		$product = array_shift($product);
		//pr($product,TRUE);
		$nta = $product['harga_asli'] + $product['penambahanDariIndsiti'];
		$base_price = $nta +  $product['penambahanDariCompany'];
		
		$price = $base_price;
		
		$data = array('content'=>'ppob/confirm/pulsa',
					  'price' => $base_price,
					  'nominal' => post('nominal'),
					  'nomer' => post('nomer'),
					  'kode' => $product['kode'],
						  );
		$this->load->view("index",$data);
	}
	
	function finance($id=0){
		$d = $this->m_ppob->finance($id);
		$this->load->helper('dropdown');
		$data = array('content'=>'ppob/finance',
					  'data'=>$d,
					  'product'=>listDataCustom('product','id','kode,product'),
					  'status'=>listDataCustom('ppob status','status',"note,created","where `id trx`=$id order by created desc"),
					  );
		$this->load->view("index",$data);
	}

	private function _login(){
		//validate form input
		$this->load->library('form_validation');
		$this->load->helper('language');
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
		
		$code = 400;
		$hasil['data']=0;
		$hasil['message']='error';
		if ($this->form_validation->run() == true)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				$code = 200;
				$hasil['message'] = $this->ion_auth->messages();
				$hasil['data']=1;
			}
			else
			{
				// if the login was un-successful
				$hasil['message'] = $this->ion_auth->errors();
				$hasil['data']=0;
				$hasil['message'] .= "Email atau Password salah !";
			}
		}
		return $hasil;
	}
		
}