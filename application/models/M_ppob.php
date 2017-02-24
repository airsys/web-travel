<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_ppob extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('subquery');
	}
	
	function get_product(){
		$this->db->select('*')
			 ->from('`product`')
			 ->where('`kode`',post('nominal'))
			 ->order_by('nilai');		 
		$data = $this->db->get()->row();
		return $data;
	}
	
	function insert_pulsa($trxid,$nomer,$productk){
		$nama = NULL; $contact = NULL; $email = NULL;
		if(! empty(post('contact'))){
			$nama = post('nama'); 
			$contact = post('contact'); 
			$email = post('email');
		}
		$data = array(
			'product'=>$productk,
			'ref trxid'=>$trxid,
			'company'=>$this->session->userdata('company'),
			'msisdn'=>$nomer,		
			'nama'=>$nama,			
			'contact'=>$contact,			
			'email'=>$email,			
		);
		$this->db->insert('`ppob trx`',$data);
		$insert_id = $this->db->insert_id();
		
		return $insert_id;
	}
	
	function update_pulsa($data_f){
		//pr($data_f);
		if(!empty($data_f['base_price'])){
			$data = array('trxid'=>$data_f['trxid'], 
						  'created'=>now(),
						  'price'=>$data_f['price'],
						  '`base price`'=>$data_f['base_price'],
						  '`net price`'=>$data_f['net_price'] );
			$this->db->where('`ref trxid`', $data_f['ref_trxid']);
			$this->db->update('`ppob trx`', $data);
		}
		$msg = '';
		switch($data_f['status']){
			case 1111:
				$msg = 'prosessing';
				break;
			case 0:
				$msg = 'succes';
				break;
			case 2222:
				$msg = 'waiting SN';
				break;
			case 1001:
				$msg = 'refund';
				break;
			default:
				$msg = 'failed';
				break;
		}
		
		$this->change_status($data_f['ref_trxid'],$msg,$data_f['message']);
	}
	
	function change_status($ref_trxid=0,$status='processing',$message=NULL){
		$this->db->select('id')
			 ->from('`ppob trx`')
			 ->where('`ref trxid`',$ref_trxid); 
		$id = $this->db->get()->row();
		$id = $id->id;
		$user = 0;
		if($this->session->userdata('user_id')!=NULL){
			$user = $this->session->userdata('user_id');
		}
		
		$data2 = array('id trx'=>$id,
			'status'=>$status,
			'user'=>$user,
			'created'=>now(),
			'note'=>$message,
		);
		$this->db->insert('`ppob status`',$data2);
		$insert_id = $this->db->insert_id();
		
		return $insert_id;
	}
	
	function change_nilai($id, $nilai_now){
		$data = array('harga'=>$nilai_now);
		$this->db->where('id', $id);
		$this->db->update('`product`', $data);
	}
	
	function issued($id,$harga_pulsa){
		$saldo = saldo();
		//$harga_pulsa = hargaPulsa($kode);
			$data=array(
					"company"=>$this->session->userdata('company'),
					"nominal"=>$harga_pulsa,
					"created"=>now(),
					"code"=>'DP',
					"pay for"=>$id,
					"balance"=>$saldo-$harga_pulsa,
			);
			$this->db->insert('acc balance',$data); //<-menambah row di payment topup
		
	}
	
	function refund($ref_trxid){
		$this->db->select('id,company')
			 ->from('`ppob trx`')
			 ->where('`ref trxid`',$ref_trxid); 
		$id = $this->db->get()->row();
		$company = $id->company;
		$id = $id->id;
		
		$this->db->select('nominal')
			 ->from('`acc balance`')
			 ->where('`code`','DP') 
			 ->where('`pay for`',$id); 
		$harga = $this->db->get()->row();
		$harga = $harga->nominal;
		
		$saldo = saldo($company);
		$data=array(
				"company"=>$company,
				"nominal"=>$harga,
				"created"=>now(),
				"code"=>'CP',
				"pay for"=>$id,
				"balance"=>$saldo+$harga,
		);
		$this->db->insert('acc balance',$data); //<-menambah row di payment topup
	
	}

	// TAGIHAN TELKOM
	function insert_tagihan($trxid,$nomer,$productk,$contact,$email){
		$data = array(
			'product'=>$productk,
			'ref trxid'=>$trxid,
			'company'=>$this->session->userdata('company'),
			'msisdn'=>$nomer,
			'contact'=> $contact,
			'email'=> $email,
			
		);
		$this->db->insert('`ppob trx`',$data);
		$insert_id = $this->db->insert_id();
		
		return $insert_id;
	}
	
	function update_tagihan($data_f){
		//pr($data_f);
		if(!empty($data_f['base_price'])){
			$data = array('nama'=>$data_f['message'], 
						  'trxid'=>$data_f['trxid'], 
						  'created'=>now(),
						  'price'=>$data_f['price'],
						  '`net price`'=>$data_f['net_price'],
						  '`base price`'=>$data_f['base_price'] );
			$this->db->where('`ref trxid`', $data_f['ref_trxid']);
			$this->db->update('`ppob trx`', $data);
		}
		$msg = '';
		switch($data_f['status']){
			case 1111:
				$msg = 'prosessing';
				break;
			case 0:
				$msg = 'succes';
				break;
			case 2222:
				$msg = 'waiting SN';
				break;
			case 1001:
				$msg = 'refund';
				break;
			default:
				$msg = 'failed';
				break;
		}
		
		$this->change_status($data_f['ref_trxid'],$msg,$data_f['message']);
	}
	
	function finance($id){
		$this->db->select("*")
			 ->from('`ppob trx`')
			 ->where('`id`',$id)
			 ->where('`company`',$this->session->userdata('company'));
		$r = $this->db->get()->row();
		return $r ;
	}
	
	/* Markup From Indsiti to All Companies */
	function markupFindsiti($to_company=NULL,$id=0){
		$this->db->select("*")
			->from('markup')
			->order_by("`company`");
		
		$to_companyS = "OR `company` = $to_company";
		if($to_company == NULL){
			$to_companyS = '';
		}
		
		if($id==0){
			$this->db->where("(`company`=0 $to_companyS) 
					 AND `markup for` = 'internal' ");
		}else{
			$this->db->where("id",$id);
		}
		$this->db->where("active",'1');
		$data = $this->db->get()->result();
		$r = [];
		foreach($data as $val){
			$r[$val->product] = array(
					"value"=>$val->value,
					"tipe_data"=>$val->{'type'},
					"idFindsiti"=>$val->id,
			);
		}
		return $r;		
	}
	
	/* Markup From Company to All Buyers */
	function markupTbuyer($to_buyer=NULL, $id=0){
		$this->db->select("*")
			->from('markup');
		
		$to_buyerS = "OR `company` = $to_buyer";
		if($to_buyer == NULL){
			$to_buyerS = '';
		}
				
		if($id==0){
			$this->db->where("(`company` =0 $to_buyerS) 
					 AND `markup for` = 'member' ");
		}else{
			$this->db->where("id",$id);
		}
		$this->db->where("active",'1');
		$data = $this->db->get()->result();
		$r = [];
		foreach($data as $val){
			$r[$val->product] = array(
					"value"=>$val->value,
					"tipe_data"=>$val->{'type'},
					"idTbuyer"=>$val->id,
			);
		}
		return $r;
	}
	
	function get_products($idFindsiti=0, $idTbuyer=0, $id=0){
		$company = $this->session->userdata('company');
		if(!$this->session->userdata('company')){
			$company = NULL;
		}
		$markupFindsiti = $this->markupFindsiti($company, $idFindsiti);
		$markupTbuyer = $this->markupTbuyer($company, $idTbuyer);
		
		$this->db->select('id,kode, harga as nilai')
				 ->from('`product`')
				 ->where('`group`','ppob')
				 ->order_by('harga');
		if($id != 0) $this->db->where('`id`',$id);
		$data = $this->db->get()->result();
		$r = [];
		//pr($markupTbuyer,TRUE);
		
		foreach($data as $val){
			$penambahMIndsiti  = $markupFindsiti[$val->id]['value'];
			if($markupFindsiti[$val->id]['tipe_data']!='decimal'){
				$penambahMIndsiti = $markupFindsiti[$val->id]['value']/100*$val->nilai;
			}
			$base_price = $val->nilai+$penambahMIndsiti;
			$penambahMCompany  = $markupTbuyer[$val->id]['value'];
			if($markupTbuyer[$val->id]['tipe_data']!='decimal'){
				$penambahMCompany = ($markupTbuyer[$val->id]['value'])/100*$base_price;
			}
			$price = $base_price+$penambahMCompany;
			
			$r[] = array(
				"id" => $val->id,
				"kode" => $val->kode,
				"price" =>$price,
				"base_price" =>$base_price,
				"FT" => $markupFindsiti[$val->id]['idFindsiti']."|".$markupTbuyer[$val->id]['idTbuyer'],
				
				"penambahanDariCompany" => $penambahMCompany,
				"penambahanDariIndsiti" => $penambahMIndsiti, //sementara
				"harga_asli"=>$val->nilai, //hanya untuk pembantu
			);
		}
		return $r;		
	}
	
}