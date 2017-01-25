<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_ppob extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('subquery');
	}
	
	function transaction($betwen =NULL,$data_where=NULL){
		if($data_where!=NULL){
			foreach ($data_where as $key=> $val2){
				$this->db->where($key, $val2,FALSE);
			}
		}
		if($betwen!=NULL){
			$this->db->where($betwen);
		}
		$this->db->select(" c.brand, p.*,from_unixtime(p.created  ,'%d-%m-%Y %h:%i:%s') as created2")
				 ->from("`ppob pulsa` AS p, auth company AS c")
				 ->where('p.company = c.id');
		return $this->db->get()->result();
	}
	
}