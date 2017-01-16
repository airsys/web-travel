<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_ppob extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('subquery');
	}
	
	function get_price(){
		$this->db->select('*')
			 ->from('`ppob product`')
			 ->where('`kode`',post('nominal'))
			 ->order_by('nilai');		 
		$data = $this->db->get()->row();
		$price=0;
		if($data!=NULL){
			$price = $data->nilai + $data->markup;
		}
		return $price;
	}
}