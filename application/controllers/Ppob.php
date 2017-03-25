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
	
	function pln()
	{
		$data = array('content'=>'ppob/pln',
					  );
		$this->load->view("index",$data);		
	}
	
	
	
	function sn(){
		$strRequest = "";		
		$change_status = FALSE;
		
		if(file_get_contents('php://input')=='')
		{
		    // THROW EXCEPTION
		    echo "No data";
		}
		else
		{   
		    // get read-only stream for read raw data from the request body
		    $strRequest = file_get_contents('php://input');
		    $data = xml2array($strRequest);
			$sn = explode(":",$data['msg']);
			$sn = filter_var($sn[2], FILTER_SANITIZE_NUMBER_INT);
			//echo $sn;die();
			
			$change_status = $this->m_ppob->change_status($data['ref_trxid'],'success',$data['msg']);
			if($change_status){
				$data_update = array('sn operator'=>$sn);
				$this->db->where('trxid', $data['trxid']);
				$this->db->update('`ppob trx`', $data_update);
			} else{
				echo "<strong>ref_trxid</strong> tidak ditemukan - change status fail !<br>";
			}			
			echo "SN:".$sn;
		        
		}
		
		$this->db->insert("`system log`", array('code'=>'coba','log'=>$strRequest."|-IP:".$this->input->ip_address()));
		
	}
	
	//http://indsiti.com/ppob/refund?resultcode=1001&msisdn=62816888999&message=Refund&trxid=7552974&ref_trxid=54321
	function refund(){
		$this->m_ppob->update_pulsa(array('message'=>get('message'), 'trxid'=>get('trxid'), 
							'ref_trxid'=>get('ref_trxid'), 'status'=>get('resultcode')));
		$this->m_ppob->refund(get('ref_trxid'));
		echo "oke";
	}
	
	/**
	* 
	* 
	* BAYAR PULSA
	*/
	//VIEW
	function pulsa($page=''){
		$content = 'ppob/pulsa';
		if($page != '') $content = 'ppob/paket_data';		
		$data = array('content'=>$content,
					  );
		$this->load->view("index",$data);		
	}
	//KONFIRMASI
	function confirm($page=''){
		$content = 'ppob/confirm/pulsa';
		if($page != '') $content = 'ppob/confirm/paket_data';
		
		if($this->input->post()){
			$nominal = post('nominal');
			$nomer = post('nomer');
			$data_pulsa = NULL;
		}elseif($this->session->flashdata('data_pulsa')){
			$data_pulsa = $this->session->flashdata('data_pulsa');
			$nominal = $data_pulsa['nominal'];
			$nomer = $data_pulsa['nomer'];
		}else{
			redirect('ppob/pulsa','refersh');
		}
		
		$productk = explode('_',$nominal);
		$ids = explode('|',$productk[1]);
		$productk = $productk[0];		
		
		$product = $this->m_ppob->get_products($ids[0],$ids[1],$productk);
		$product = array_shift($product);
		//pr($product,TRUE);
		$nta = $product['harga_asli'] + $product['penambahanDariIndsiti'];
		$base_price = $nta +  $product['penambahanDariCompany'];
		
		$price = $base_price;
		
		$data = array('content'=>$content,
					  'price' => $base_price,
					  'nominal' => $nominal,
					  'nomer' => $nomer,
					  'kode' => $product['kode'],
					  'name_product' => $product['name'],
					  'data_pulsa' => $data_pulsa,
						  );
		$this->load->view("index",$data);
	}
	//BAYAR
	function bayar($page=''){
		$content = 'ppob/confirm/';
		if($page != '') $content = 'ppob/confirm/paket_data';
		
		$login['data'] = 0; 
		$login['message'] = '';
		if(! $this->ion_auth->logged_in() && post('position')=='lo'){
			$login = $this->_login();
		}
		if(! $this->ion_auth->logged_in() && post('position')=='re'){
			$login = $this->_register();
		}
		if($login['data']==1 || $this->ion_auth->logged_in()){
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
						
						if(preg_match("/tidak cukup/",$msg[0])) $msg[0] = "Kesalahan server";
						
						$this->m_ppob->update_pulsa(array('message'=>$msg[0], 'trxid'=>$return['trxid'], 
								'ref_trxid'=>$my_trxid, 'status'=>$return['resultcode'],
								'base_price'=>0, 'net_price'=>0, 'price'=>0));
						$return = array('message'=>$login_message.$msg[0].'<p>Gagal Pembayaran'."<br> Telah terjadi kesalahan sistem, 
									silakan hubungi customer service kami untuk informasi lebih lanjut</p>",
								'code'=>1,
								'login'=>$login['data'],
								'nomer'=>post('nomer'),
								'nominal'=>post('nominal'));
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
										'nomer'=>post('nomer'),
										'nominal'=>post('nominal')
								        );
					}
				}else{
					$return = array('message'=>$login_message.'Operator tidak terdaftar',
								'code'=>1,
								'login'=>$login['data'],
								'nomer'=>post('nomer'),
								'nominal'=>post('nominal'),);	
				}
			}else{
				$return = array('message'=>$login_message.'saldo anda tidak cukup',
								'code'=>1,
								'login'=>$login['data'],
								'nomer'=>post('nomer'),
								'nominal'=>post('nominal'));	
			}
		}else{
			$return = array('message'=>$login['message'],
							'code'=>1,
							'login'=>$login['data'],
							'nomer'=>post('nomer'),
							'nominal'=>post('nominal'));	
		}
			
		$this->session->set_flashdata('data_pulsa', $return);
		redirect($content, 'refresh');
		
		/*return $this->output
	            ->set_content_type('application/json')
	            ->set_status_header(200)
	            ->set_output(json_encode($return));*/
	}	
	/* END BAYAR PULSA */
	
	/*======================================================*/
		
	
	/**
	* 
	* 
	* BAYAR TAGIHAN
	*/
	//VIEW
	function tagihan(){
		$products=$this->m_ppob->get_products();
		$vProducts = [];
		foreach($products as $va){
			if(preg_match("/BAYAR/",$va['kode'])){
				$vProducts[$va['id'].'_'.$va['FT']] = $va['kode'];
			}
		}
		
		$data = array('content'=>'ppob/telkom',
					  'products'=>$vProducts,
						  );
		$this->load->view("index",$data);		
	}
	//KONFIRMASI
	function confirm_tagihan(){
		if($this->input->post()){
			$oprcode = post('oprcode');
			$nomer = post('nomer');
			$data_pulsa = NULL;
		}elseif($this->session->flashdata('data_pulsa')){
			$data_pulsa = $this->session->flashdata('data_pulsa');
			$oprcode = $data_pulsa['oprcode'];
			$nomer = $data_pulsa['nomer'];
		}else{
			redirect('ppob/tagihan','refersh');
		}
		
		$return = []; $data=[];
		$my_trxid = now().'_'.RandomString(3);
		$idpelanggan = $nomer;
		$productk = explode('_',$oprcode);
		$ids = explode('|',$productk[1]);
		$productk = $productk[0];		
		
		$product = $this->m_ppob->get_products($ids[0],$ids[1],$productk);
		$product = array_shift($product);
		
		$sProduct = explode('.',$product['kode']);
		$kode_cek = $this->_cek_products($sProduct[1]);
		
		$data = ppobxml($idpelanggan,$kode_cek,'charge',$my_trxid);
		//pr($data,TRUE);
		//$data['message'] = 'Tagihan TELKOM a/n PT.ASTEL 0213863333 adalah sebesar 63360.Untuk Bayar ketik: BAYAR.TELKOM.WEB.Pin.0218672720.63360.NoHpPlg atau Email';
				
		if($data['resultcode']==999){
			//echo "dari database";
			$data = json_decode("".$this->_get_cache($idpelanggan)."",TRUE);
		}else{
			//echo "dari server";
			$this->_store_cache($nomer,$data);
		}
		
		if( preg_match("/gagal|sudah ada|tidak cukup/",strtolower($data['message']))){
			$return['message'] = $data['message'];
			$return['jenis'] = $sProduct[1];
			$return['harga_tagihan'] = 0;
		}else{
			$harga = filter_var(GetBetween($idpelanggan.'','.',$data['message']), FILTER_SANITIZE_NUMBER_INT);
			$return['harga_tagihan'] = $harga;
			  $harga = 2000+$harga; //markup 2000 dari datasel		
			$return['harga_server'] = $harga;
			$return['harga'] = $harga+$product['penambahanDariIndsiti']; //harga dari indsisti
			$return['harga_konsumen'] = $return['harga']+$product['penambahanDariCompany'];	//harga ke buyer
			$return['nama'] = GetBetween('a/n ',' '.$idpelanggan,$data['message']);
			$return['jenis'] = $sProduct[1];
			$return['message'] = "Tagihan $return[jenis] a/n $return[nama] <br>
			  					 sebesar Rp ".number_format($return['harga'])."<br>
			  					 biaya admin Rp ".number_format($return['harga_konsumen']-$return['harga'])."<br>
			  					 Harga Total Rp ".number_format($return['harga_konsumen']);	
		}
		$data = array('content'=>'ppob/confirm/tagihan',
					  'product' => $oprcode,
					  'kode' => $product['kode'],
					  'idpelanggan' => $idpelanggan,
					  'costumer'=>$return,
					  'data_pulsa'=>$data_pulsa,
					  //'kode' => $product['kode'],
				);
		$this->load->view("index",$data);
		
    }    
    //BAYAR
    function bayar_tagihan(){
    	$login['data'] = 0; 
		$login['message'] = '';
		
		if(! $this->ion_auth->logged_in() && post('position')=='lo'){
			$login = $this->_login();
		}
		if(! $this->ion_auth->logged_in() && post('position')=='re'){
			$login = $this->_register();
		}
		
		if($login['data']==1 || $this->ion_auth->logged_in()){
			$login_message = $login['message'] ;
			$productk = explode('_',post('product'));
			$ids = explode('|',$productk[1]);
			$productk = $productk[0];		
			
			$product = $this->m_ppob->get_products($ids[0],$ids[1],$productk);
			$product = array_shift($product);
			
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
						if(preg_match("/tidak cukup/",$msg[0])) $msg[0] = "Kesalahan server";
												
						$this->m_ppob->update_pulsa(array('message'=>$msg[0], 'trxid'=>$return['trxid'], 
								'ref_trxid'=>$my_trxid, 'status'=>$return['resultcode'],
								'base_price'=>0, 'net_price'=>0, 'price'=>0));
						$return = array('message'=>$login_message.$msg[0].'<p>Transaksi gagal '."<br> Telah terjadi kesalahan sistem, 
									silakan hubungi customer service kami untuk informasi lebih lanjut</p>",
								'code'=>1,
								'nomer'=>post('nomer'),
							    'oprcode'=>post('product'),);
						
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
						$return = array('message'=>$login_message.'Transaksi Berhasil'."<br>",
										'code'=>0,
										'login'=>$login['data'],
										'id' => $id,
										'nomer'=>post('nomer'),
										'oprcode'=>post('product'),
								        );
					}
				}else{
					$return = array('message'=>$login_message.'Operator tidak terdaftar',
								'code'=>1,
								'nomer'=>post('nomer'),
							    'oprcode'=>post('product'),);	
				}
			}else{
				$return = array('message'=>$login_message.'saldo anda tidak cukup',
								'code'=>1,
								'login'=>$login['data'],
								'nomer'=>post('nomer'),
							    'oprcode'=>post('product'),);	
			}
		}else{
			$return = array('message'=>$login['message'],
							'code'=>1,
							'login'=>$login['data'],
							'nomer'=>post('nomer'),
							'oprcode'=>post('product'),);
		}
		
		$this->session->set_flashdata('data_pulsa', $return);
		redirect('ppob/confirm_tagihan', 'refresh');
		
		/*return $this->output
	            ->set_content_type('application/json')
	            ->set_status_header(200)
	            ->set_output(json_encode($return)); */
	}
	//CEK TAGIHAN
	private function _cek_products($string){
		$data = array('telkom'=>'CEK.TELKOM',
				'pln' => 'CEK.PLN'
		);
		$r = $data[strtolower($string)];	
		return $r;
	}
	// GET CACHE
	private function _get_cache($nomor=0){
		$this->db->select("data")
			     ->where("msisdn",$nomor)
			     ->from("ppob cache");
		$data = $this->db->get()->row_array();
		return $data['data'];
	}
	// STORE CACHE
	private function _store_cache($nomor=0,$data=''){
		$this->m_ppob->store_cache($nomor,$data);
	}
	
	/* END BAYAR TAGIHAN */
	
	/*======================================================*/
	
	
	
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
	
	function finance($id=0){
		if(!$this->ion_auth->logged_in()) redirect(base_url().'ppob#login', 'refresh');
		$d = $this->m_ppob->finance($id);
		$this->load->helper('dropdown');
		$data = array('content'=>'ppob/finance',
					  'data'=>$d,
					  'product'=>listDataCustom('product','id','kode,product'),
					  'status'=>listDataCustom('ppob status','status',"note,created","where `id trx`=$id order by created desc"),
					  );
		$this->load->view("index",$data);
	}
	
	//LOGIN
	private function _login(){
		//validate form input
		$this->load->library('form_validation');
		$this->load->helper('language');
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
		
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
		}else{
			$hasil['message'] = validation_errors();
			$hasil['data']=0;			
		}
		return $hasil;
	}
	
	//REGISTER
	private function _register(){
		$this->load->library('form_validation');
		$this->load->helper('language');
		$data_post = NULL;
		$message = '';
		$hasil['data']=0;
		$hasil['message']='error';
		if($this->input->post()){
			$data_post = $this->input->post();
			$tables = $this->config->item('tables','ion_auth');
	        $identity_column = $this->config->item('identity','ion_auth');
	        $this->data['identity_column'] = $identity_column;
	        
	        // validate form input
		    $this->form_validation->set_rules('full_name', $this->lang->line('create_user_validation_fname_label'), 'required');
		    if($identity_column!=='email')
		    {
		        $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
		        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
		    }
		    else
		    {
		        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
		    }
		    $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim|numeric');
		    $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim|required');
		    $this->form_validation->set_rules('password_register', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		    $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		    if ($this->form_validation->run() == true)
		    {
		        $email    = strtolower($this->input->post('email'));
		        $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
		        $password = $this->input->post('password_register');

		        $additional_data = array(
		            'full name' => $this->input->post('full_name'),
		            'phone'      => $this->input->post('phone'),
		        );
		    }
		    if ($this->form_validation->run() == true && 
		    	$this->ion_auth->register($identity, $password, $email, $additional_data))
		    {
		        $message =  $this->ion_auth->messages();
		        $this->ion_auth->login($identity, $password,FALSE);        
		    	$hasil['message'] = $message;
				$hasil['data']=1;
		    }
		    else
		    {
		        // display the create user form
		        // set the flash data error message if there is one
		        $message = (validation_errors() ? validation_errors() : 
		        						  ($this->ion_auth->errors() ? $this->ion_auth->errors() : 
		        						  $this->session->flashdata('message')));
		    	$hasil['message'] = $message;
				$hasil['data']=0;
		    }
		}
		return $hasil;		
	}
		
}