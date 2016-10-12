<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_bkw extends CI_Controller {
	private $url;
	function __construct() {
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->config->load('api');
		$this->url = $this->config->item('bkw-url');
	}
	
	public function login_ajax(){
		$this->form_validation->set_rules('identity', 'User Name' , 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		$code = 400;
		$hasil['data']=0;
		$hasil['message']='error';
		if ($this->form_validation->run() == true)
		{
			$data = $this->input->post();
			$pass = md5($data['password']);
			echo $this->url."usercheck?user=$data[identity]&pass=$pass";die();
			$data_login = json_decode(file_get_contents($this->url."usercheck?user=$data[identity]&pass=$pass"));
			if($data_login->status==1){
				$reg = $this->register($data['identity'], $pass, 
							 			$data_login->data->email,
							 			$data_login->data->name, 
							 			$data_login->data->phone);					
				$hasil['message'] = 'Berhasil';
				$hasil['data']=1;
				$code = 200;
				$this->ion_auth->login($data['identity'], $pass,TRUE);
			}else{
				$hasil['message'] = $data_login->message;
				$code = 400;
			}
			
		}else{
			$hasil['message'] = validation_errors();
			$hasil['data']=0;
		}
		return $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($hasil));
	}
	
	// register
	public function register($identity, $password, $email, 
							 $full_name, $phone){
        $additional_data = array(
            'full name' => $full_name,
            'phone'      => $phone,
        );
     	return	$this->ion_auth->register($identity, $password, $email, $additional_data);		
	}
	
	function profile(){	
		$user = NULL;
		$message = '';
		$id = $this->session->userdata('id');
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth2/register/', 'refresh');
		}
		
		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();
		// validate form input
		$this->form_validation->set_rules('full_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		
		if (isset($_POST) && !empty($_POST))
		{
			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}
			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'full name' => $this->input->post('full_name'),
					'phone'      => $this->input->post('phone'),
				);

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}
				// check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data))
			    {
			    	$message = $this->ion_auth->messages();
			    }else{
					$message = $this->ion_auth->errors();
				}
			}
		}
		
		$user = $this->ion_auth->user($id)->row_array();
		$this->load->helper('dropdown');
		$data_view = array(
					'content'=>'auth/profile',
					'data_post'=> $user,
					'company'=> listData('auth company','id','brand',"where `id` = '".$this->session->userdata('company')."'"),
					'bank'=> listDataCustom('acc bank','id','bank,account name,rek number,enable',"where `company` = '".$this->session->userdata('company')."' order by enable desc"),
					'message'=> $message,		
				);
		$this->load->view("index",$data_view);
	}
	
	function bank_detail($id){
		if(isset($_POST) && !empty($_POST)){
			$this->load->model('m_payment');
			$this->m_payment->change_status_bank($this->input->post('id'),$this->input->post('status'));
			redirect('auth2/profile/','refresh');
		} else{
			$this->load->helper('dropdown');
			$data_view = array(
						'content'=>'auth/bank_detail',
						'bank'=> listDataCustom('acc bank','id','bank,account name,rek number,enable',"where company = '".$this->session->userdata('company')."' and id = $id"),
						'id'=>$id,
			);
			$this->load->view("index",$data_view);	
		}
		
	}
	
	// log the user out
	public function logout()
	{
		$this->data['title'] = "Logout";

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('airlines/', 'refresh');
	}
}
