<?php 

class M_markup extends CI_Model
{

function __construct(){
		parent::__construct();		
	}

	function create(){
		$this->db->insert("member",array("nama"=>""));
		return $this->db->insert_id();
	}


	function read(){
		//$this->db->select("kode,value,type");
		//$this->db->order_by("id","desc");
		$this->db->where("`markup for`","internal");
		$this->db->join("product","markup.product=product.id");
		$query=$this->db->get("markup");
		return $query->result_array();
	}


	function update($id,$value,$modul){
		$this->db->where(array("id"=>$id));
		$this->db->update("markup",array($modul=>$value));
	}
	function updatemember($id,$value,$modul,$company){
		$this->db->where(array("id"=>$id));
		$this->db->where(array("company"=>$company));
		$this->db->update("markup",array($modul=>$value,
										'company'=>$company));
	}
	function insertmember($id,$value,$modul,$company,$product){
			$this->db->select('product')
			 		 ->from('`markup`')
				 	 ->where('`product`',$product)
				 	 ->where('`company`',$company); 
			$product_row = $this->db->get()->row();
			$product_row = $product_row->product;

		if ($product_row ==$product ) {
			
			$this->db->where(array("id"=>$id));
			$this->db->where(array("company"=>$company));
			$this->db->update("markup",array($modul=>$value,
											'company'=>$company));
		}else{
			$this->db->insert("markup",array($modul=>$value,
										'company'=>$company,
										'`markup for`'=>'member',
										'product'=>$product));
		}
	
	}


	function delete($id){
		$this->db->where("id",$id);
		$this->db->delete("markup");
	}
	function tambah($data){
       $this->db->insert('markup', $data);
       return TRUE;
    }
    function dd_product()
    {
        // ambil data dari db
        $this->db->order_by('product', 'asc');
        $result = $this->db->get('product');
        
        // bikin array
        // please select berikut ini merupakan tambahan saja agar saat pertama
        // diload akan ditampilkan text please select.
        $dd[''] = 'Please Select';
        if ($result->num_rows() > 0) {
            foreach ($result->result() as $row) {
            // tentukan value (sebelah kiri) dan labelnya (sebelah kanan)
                $dd[$row->id] = $row->kode;
            }
        }
        return $dd;
    }

}	