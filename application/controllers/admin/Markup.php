<?php 

class Markup extends CI_Controller
{

	function __construct(){
		parent::__construct();
		$this->load->model('admin/m_markup');
		$this->load->helper('url');
		$this->load->database();
		$this->load->library(array('form_validation'));
		$this->load->helper('dropdown');
	}

function listMarkup(){
		 $this->load->helper('form_helper');
 
    	
		//$data["people"]=$this->m_markup->read();
		//$this->load->view("admin/payment/markup",$data);
			$data_select = $data_select = $this->m_markup->read();
		 	$data = array('content'=>'payment/markup',
		 		 		'markup'=>$data_select,
		 		 		//'markup'=> listDataCustom('markup','id','product,value,type',"where `markup for` like 'internal'"),
		 				'dd_product' => $this->m_markup->dd_product(),
            			'product_selected' => $this->input->post('product') ? $this->input->post('product') : '', // untuk edit ganti '' menjadi data dari database misalnya $row->provinsi
			
		 			);
		 	$this->load->view("admin/index",$data);
		
	}

/*
	function updatetes(){
		$id= $this->input->post("id");
		$value= $this->input->post("value");
		$modul= $this->input->post("modul");
		$this->m_markup->update($id,$value,$modul);
		echo "{}";
	}
*/
	function update(){
		$id= $this->input->post("id");
		$value= $this->input->post("value");
		$type=$this->input->post("type");
		$this->m_markup->updatetes($id,$value,$type);
		echo "{}";
	}
	function updatemember(){
		$id= $this->input->post("id");
		$value= $this->input->post("value");
		//$modul= $this->input->post("modul");
		$type=$this->input->post("type");
		$company= $this->session->userdata('company');
		$product= $this->input->post('product');  //bisa nol tapi tidak boleh kosong??
		
			$this->m_markup->insertmember($id,$value,$type,$company,$product);
			
		echo "{}";
	}
	
	function delete(){
		$id= $this->input->post("id");
		$this->m_markup->delete($id);
		echo "{}";
	}
	function deletemember(){
		$id= $this->input->post("id");
		$company= $this->session->userdata('company');
		$this->m_markup->deletemember($id,$company);
		echo "{}";
	}
	function tambahData(){
        $data = array(
            'product' => $this->input->post('product'),
            '`markup for`' => 'internal',
            'value' => $this->input->post('value'),
            'type' => $this->input->post('type'),
            'active' => '1',
        );
        $this->m_markup->tambah($data);


           $datamember = array(
            'product' => $this->input->post('product'),
            '`markup for`' => 'member',
            'value' => $this->input->post('value'),
            'type' => $this->input->post('type'),
            'active' => '1',
        );
        $this->m_markup->tambahmember($datamember);
        
        $this->session->set_flashdata('notif','<div class="alert alert-success" role="alert"> Markup Berhasil ditambahkan <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/markup/listMarkup');
    }
    

}