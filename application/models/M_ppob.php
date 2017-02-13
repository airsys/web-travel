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
	
	function insert_pulsa($trxid,$nomer){
		$data = array('product'=>post('nominal'),
			'ref_trxid'=>$trxid,
			'user'=>$this->session->userdata('user_id'),
			'company'=>$this->session->userdata('company'),
			'msisdn'=>$nomer,
		);
		$this->db->insert('`ppob transaksi`',$data);
		$insert_id = $this->db->insert_id();
		
		return $insert_id;
	}
	
	function update_pulsa($data_f){
		//pr($data_f);
		$data = array('trxid'=>$data_f['trxid'], 'created'=>now(),
					  'base_price'=>$data_f['base_pricex'],'nta'=>$data_f['nta'] );
		$this->db->where('ref_trxid', $data_f['ref_trxid']);
		$this->db->update('`ppob transaksi`', $data);
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
			 ->from('`ppob transaksi`')
			 ->where('`ref_trxid`',$ref_trxid); 
		$id = $this->db->get()->row();
		$id = $id->id;
		$user = 0;
		if($this->session->userdata('user_id')!=NULL){
			$user = $this->session->userdata('user_id');
		}
		
		$data2 = array('id_ppob'=>$id,
			'status'=>$status,
			'user'=>$user,
			'created'=>now(),
			'note'=>$message,
		);
		$this->db->insert('`ppob status`',$data2);
		$insert_id = $this->db->insert_id();
		
		return $insert_id;
	}
	
	function change_nilai($nominal, $nilai_now){
		$data = array('nilai'=>$nilai_now);
		$this->db->where('kode', $nominal);
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
			 ->from('`ppob pulsa`')
			 ->where('`ref_trxid`',$ref_trxid); 
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
	function insert_tagihan($trxid){
		$data = array(
			'ref_trxid'=>$trxid,
			'product'=> post('oprcode'),

		);
		//print_r($_POST);die();
		$this->db->insert('`ppob tagihan`',$data);
		return $this->db->insert_id();
	}
	function update_tagihan($data_f){
		$data = array('message'=>$data_f['message'], 'trxid'=>$data_f['trxid'], 
					  'status'=>$data_f['status'], 'created'=>now());
		$this->db->where('ref_trxid', $data_f['ref_trxid']);
		$this->db->update('`ppob tagihan`', $data);
	}
	function issuedTagihan($id,$kode){
		$saldo = saldo();
		$nominal = post('nominal');
			$data=array(
					"company"=>$this->session->userdata('company'),
					"nominal"=>$nominal,
					"created"=>now(),
					"code"=>'CP',
					"pay for"=>$id,
					"balance"=>$saldo-$nominal,
			);
			$this->db->insert('acc balance',$data); //<-menambah row di payment topup
		
	}
	function insert_idTelkom(){
		$time = now();
		$idTelkom = post('idpelanggan');
		$informasi = post('informasi');
		$data = array(
			'idTelkom'=> $idTelkom,
			'tgl'=> $time,
			'informasi'=> $informasi,
		);
		$this->db->replace('`ppob idTelkom`',$data);
		return $this->db->insert_id();

	}
	
	function finance($id){
		$this->db->select("*")
			 ->from('`ppob pulsa`')
			 ->where('`id`',$id)
			 ->where('`company`',$this->session->userdata('company'));
		$r = $this->db->get()->row();
		return $r ;
	}
	
	/* Markup From Indsiti to All Companies */
	function markupFindsiti($to_company=NULL,$id=0){
		$this->db->select("*")
			->from('markup')
			->order_by("`internal markup`");
		if($id==0){
			$this->db->where("company",0);
			if($to_company != NULL){
				$this->db->or_where("`internal markup`",$to_company);
			}
		}else{
			$this->db->where("id",$id);
		}
		
		$data = $this->db->get()->result();
		$r = [];
		foreach($data as $val){
			$r[$val->product] = array(
					"value"=>$val->value,
					"tipe_data"=>$val->{'tipe data'},
					"idFindsiti"=>$val->id,
			);
		}
		return $r;		
	}
	
	/* Markup From Company to All Buyers */
	function markupTbuyer($to_buyer=NULL, $id=0){
		$this->db->select("*")
			->from('markup');
		if($id==0){
			$this->db->where("(`company` !=0 OR `company` IS NULL OR `company` = $to_buyer) 
					 AND `value` is NOT NULL ");
		}else{
			$this->db->where("id",$id);
		}
		
		$data = $this->db->get()->result();
		$r = [];
		foreach($data as $val){
			$r[$val->product] = array(
					"value"=>$val->value,
					"tipe_data"=>$val->{'tipe data'},
					"idTbuyer"=>$val->id,
			);
		}
		return $r;
	}
	
	function get_products($idFindsiti=0, $idTbuyer=0){
		$company = $this->session->userdata('company');
		$markupFindsiti = $this->markupFindsiti($company, $idFindsiti);
		$markupTbuyer = $this->markupTbuyer($company, $idTbuyer);
		
		$this->db->select('id,kode, nilai')
				 ->from('`product`')
				 ->where('`type`','ppob')
				 ->order_by('nilai');
		$data = $this->db->get()->result();
		$r = [];
		//pr($markupTbuyer,TRUE);
		
		foreach($data as $val){
			$penambahMIndsiti  = $markupFindsiti[$val->id]['value'];
			if($markupFindsiti[$val->id]['tipe_data']!='decimal'){
				$penambahMIndsiti = $markupFindsiti[$val->id]['value']/100*$val->nilai;
			}
			$nta = $val->nilai+$penambahMIndsiti;
			$penambahMCompany  = $markupTbuyer[$val->id]['value'];
			if($markupTbuyer[$val->id]['tipe_data']!='decimal'){
				$penambahMCompany = ($markupTbuyer[$val->id]['value'])/100*$nta;
			}
			$base_price = $nta+$penambahMCompany;
			
			$r[] = array(
				"id" => $val->id,
				"kode" => $val->kode,
				"base_price" =>$base_price,
				"nta" =>$nta,
				"FT" => $markupFindsiti[$val->id]['idFindsiti']."|".$markupTbuyer[$val->id]['idTbuyer'],
				
				"penambahanDariCompany" => $penambahMCompany,
				"penambahanDariIndsiti" => $penambahMIndsiti, //sementara
				"harga_asli"=>$val->nilai, //hanya untuk pembantu
			);
		}
		return $r;		
	}
}