<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->helper(array('language'));
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
	}
	
	public function login(){
		if ($this->ion_auth->is_admin())
		{
			redirect('admin/payment/topup_list', 'refresh');
		}
		if($this->input->post()){			
		//validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
		
		$code = 400;
		$hasil['data']=0;
		$hasil['message']='error';
		if ($this->form_validation->run() == true)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'),$remember))
			{
				//if the login is successful
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$code = 200;
				$hasil['message'] = $this->ion_auth->messages();
				$hasil['data']=1;
				$hasil['user']=$this->session->userdata('identity');
			}
			else
			{
				// if the login was un-successful
				$hasil['message'] = $this->ion_auth->errors();
				$hasil['data']=0;
			}
		}else{
			$hasil['message'] = validation_errors();
			$hasil['data']=0;
		}
		return $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($hasil));
        } else{
			$this->load->view("admin/auth/login");
		}
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
					'full_name' => $this->input->post('full_name'),
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
		
		$user = $this->ion_auth->user($id)->row();
		
		$data_view = array(
					'content'=>'auth/profile',
					'data_post'=> $user,
					'message'=> $message,		
				);
		$this->load->view("index",$data_view);
	}
	
	// log the user out
	public function logout()
	{
		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('admin/auth/login', 'refresh');
	}
	
	function users($id=0){
		$data = NULL;
		if($id!=0){
			$this->db->select("u.id, username, email, `created on` as created_on, active, 
						 `full name` as full_name, phone, brand, 
						 from_unixtime(`created on`,  '%d-%m-%Y %h:%i:%s') as register_on,
						 from_unixtime(`last login`,  '%d-%m-%Y %h:%i:%s') as last_login"
						 )
				 ->from("auth users AS u, auth company AS c")
				 ->where("u.company = c.id")
				 ->where("u.id = $id");
			$data = $this->db->get()->row();
			$data_view = array(
				'content'=>'auth/user_detail',
				'data'=>$data,
			);
		}else{
			$data_view = array(
				'content'=>'auth/users',
			);
		}
		
		$this->load->view("admin/index",$data_view);
	}
	
	function user_status(){		
		$this->db->where('id', get('id'));
		$this->db->update('`auth users`', array('active'=>get('status'))); 
		$data_r['message'] = 'Status Changed';
		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data_r));
	}
	
	function get_username($limit,$offset){
		//print_r($_GET);
		switch(get('type')){
			case 'email':
				$val = get('value');
				$this->db->where("email = '$val'");
				break;
			case 'fullname':
				$val = get('value');
				$this->db->like('`full name`', $val);
				break;
			case 'registeron':
				$val = get('value');
				$from = strtotime($val)-25200;
				$to = (strtotime($val)+86400-25200) ;
				//echo $from .'--'. $to;
				$this->db->where("`created on` BETWEEN '$from' AND '$to'");
				break;
			default:
				break;
		}
		$this->db->select("u.id, username, email, `created on` as created_on, active, 
						 `full name` as full_name, phone, brand, from_unixtime(`created on`,  '%d-%m-%Y %h:%i:%s') as register_on")
				 ->from("auth users AS u, auth company AS c")
				 ->where("u.company = c.id")
				 ->order_by('u.`created on`','desc')
				 ->limit($limit,$offset);
		$data_r = json_encode($this->db->get()->result());
		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($data_r);
	}

}
