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

	function create(){
		echo json_encode(array("id"=>$this->crud_model->create()));
	}

	function update(){
		$id= $this->input->post("id");
		$value= $this->input->post("value");
		$modul= $this->input->post("modul");
		$this->m_markup->update($id,$value,$modul);
		echo "{}";
	}
	function updatemember(){
		$id= $this->input->post("id");
		$value= $this->input->post("value");
		$modul= $this->input->post("modul");
		$company= $this->session->userdata('company');
		$product= 77;
		
			$this->m_markup->insertmember($id,$value,$modul,$company,$product);
			
		echo "{}";
	}
	
	function delete(){
		$id= $this->input->post("id");
		$this->m_markup->delete($id);
		echo "{}";
	}
	function tambahData(){
        $data = array(
            'product' => $this->input->post('product'),
            '`markup for`' => $this->input->post('markupFor'),
            'value' => $this->input->post('value'),
            'type' => $this->input->post('type')
        );
        $this->m_markup->tambah($data);
        $this->session->set_flashdata('notif','<div class="alert alert-success" role="alert"> Markup Berhasil ditambahkan <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/markup/listMarkup');
    }
    

}