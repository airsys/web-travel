<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ppob extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('admin/m_ppob');
		if (!$this->ion_auth->is_admin())
		{
			redirect('admin/payment/topup_list', 'refresh');
		}
	}
	
	function transaction(){
	 	$array_range = NULL;
	 	if($this->input->get('range')){
			$range = str_replace(' ', '', get('range'));
			$range = (explode("-",$range));
			$rangf = strtotime(str_replace('/', '-', $range[0]));
			$rangt = strtotime(str_replace('/', '-', $range[1]))+86399;
			$array_range = "t.`created` BETWEEN $rangf AND $rangt";
			//echo date("Y-m-d H:i:s",$rangf).'|'.date("Y-m-d H:i:s",$rangt);die();
		}else{
			redirect ('admin/ppob/transaction?range='.date('d/m/Y', strtotime('-30 days')).' - '.date('d/m/Y'),'redirect');
		}
	 	$data_table = $this->m_ppob->transaction($array_range);
	 	$data = array('content'=>'ppob/transaction',
					  'data_table'=>$data_table,
					  'date_range'=>$this->input->get('range'),
					);
	 	$this->load->view("admin/index",$data);
	 }
	
	function finance($id=0){
		$d = $this->m_ppob->finance($id);
		$this->load->helper('dropdown');
		$data = array('content'=>'ppob/finance',
					  'data'=>$d,
					  'product'=>listDataCustom('product','id','kode,product'),
					  'status'=>listDataCustom('ppob status','status',"note,created","where `id trx`=$id order by created desc"),
					  );
		$this->load->view("admin/index",$data);
	}
	
	function refund(){
		if($this->m_ppob->refund()){
			$data_r = array('status'=>'changed');
			return $this->output
	            ->set_content_type('application/json')
	            ->set_status_header(200)
	            ->set_output(json_encode($data_r));
		}
	}
	
	function cek_saldo_ppob(){	
		$saldo = NULL;
		$message = NULL;
		//$this->db->order_by('date','desc');
		//$data_table = $this->db->get("`ppob saldo`",50,0)->result();
		
		if(! $this->input->post('saldo')){
			$saldo = NULL;
		}else{
			$this->load->helper('ppob');
			$saldo = cekSaldoPpob();
			if($saldo !=NULL){
				//$this->db->insert("`ppob saldo`",array('saldo'=>$saldo,'date'=>now()));
				$message = "Saldo sekarang sebesar <strong>Rp ".number_format($saldo)."</strong>";
			}else{
				/*$message = "Belum mendapat respon dari server<br>
			    Saldo terakhir sebesar Rp ".number_format($data_table[0]->saldo);*/
			    $message = "Pengecekan saldo gagal ! <br> silahkan coba 5-10 menit lagi.";
			}
		}
		
	 	$data = array('content'=>'ppob/ppob_datacell',
	 				  'saldo' => $saldo,
	 				  //'data_table' => $data_table,
	 				  'message' => $message,
					);
	 	$this->load->view("admin/index",$data);
	 }
}