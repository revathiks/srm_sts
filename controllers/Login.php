<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends MY_Controller {
	/**
	 * Login constructor.
	 */
	public function __construct() {		
		parent::__construct();
		$this->IsAlreadyLogged();
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{		
		$input 		= 	$this->input->post('login_submit');
		$api_urls 	= 	$this->config->item('api_urls');
		$url 		= 	$api_urls['login'];
		//$campus 	=  	$this->campus_list();
		if(isset($input))
		{
		
			$method = 'POST';
			if($this->input->post('role_id') == 1)
			{
					$userData = array(
						'username' 	=> 	trim($this->input->post('username')),
						'password' 	=> 	trim($this->input->post('password')),
						'role_id'	=>	trim($this->input->post('role_id'))
					);
			}
			else
			{
				$userData = array(
						'phone_no' 	=> 	trim($this->input->post('phone_no')),
						'pin_no' 	=> 	trim($this->input->post('pin_no')),
						'role_id'	=>	trim($this->input->post('role_id'))
					);
			}
                        
			$result = $this->call_api($url,$method,$userData);

                     
			if ($result['status'] === TRUE)
			{
				$this->session->set_flashdata('success', LOGIN_SUCCESS);
				$this->session->set_userdata(array(
					'access_token' => $result
				));
				$session_data	=	$this->get_token_data();
				$sess_user_details = $this->session->userdata('sess_user_details');
				$role_id	=	$sess_user_details->role_id;				
				if($role_id == 1)
				{
					$user_id	=	$sess_user_details->user_id;
					if($user_id == 1)
					{
						$this->session->set_userdata('is_logged_in_admin', 'true');
						redirect('admin/dashboard', 'refresh');	
					}
					else
					{
						$this->session->set_userdata('is_logged_in_admin', 'true');
						redirect('admin/student_teacher_map_list', 'refresh');
					}
				}
				else
				{
						$this->session->set_userdata('is_logged_in_staff', 'true');
						redirect('dashboard', 'refresh');	
				}
				
			}
			else
			{
				$this->session->set_flashdata('error', $result['message']);
				redirect('/', 'refresh');
			}
		}
		
		$this->load->view('login',array('campus' => 1));
	}

	/**
     *  Campus list
     * --------------------
     * Load campus list in login page 
     * Show active campus list
     * @author
     */

	public function campus_list()
	{
		$api_urls = $this->config->item('api_urls');
		$url = $api_urls['campus'];
		$method = 'GET';
		$result = $this->call_api($url,$method,false,false);
		return $result;
	}

	/**
     * Reset PIn
     * --------------------
     * reset pin    
     * @author
     */

	 public function reset_pin($id = false)
	 {
	 	$submit_pin	=	$this->input->post('pin_submit');
	 	if(isset($submit_pin))
	 	{	 	
		$contact_no	=	$this->input->post('phone_no');
		$api_urls 	= 	$this->config->item('api_urls');
		$url 		= 	$api_urls['reset_pin'];
		$method = 'POST';
		$userData = array(
			'contact_no' 	=> 	trim($contact_no)
		);
		$result = $this->call_api($url,$method,$userData);
			if($result['status'] === 'success')
			{
				$reset_link = $result['message']['reset_link'];
		        $email  = ADMIN_MAIL_ID;
		        $name   =  "testname";
		       	$url = $api_urls['edit_staff_pin']."/".$result['message']['staff_id'];
		       	$method = 'PUT';
		       	$user_data = array (
							'reset_pin' => $reset_link,
							'check_mail'  => 0
						);

					$config = Array(
		            'protocol' => 'smtp',
		            'smtp_host' => 'smtp.gmail.com',
		            'smtp_port' => 25,
		            'smtp_user' => ADMIN_MAIL_ID, // change it to yours
		            'smtp_pass' => 'Karthick@03', // change it to yours
		            'mailtype' => 'html',
		            'charset' => 'iso-8859-1',
		            'wordwrap' => TRUE
		        );
		       	$email = $result['message']['official_mailid'];
		        $name = $result['message']['staff_name'];
		        $message = "<a href=".base_url()."reset_pin_no/".$reset_link.">Click Here to reset pin </a>";
		        $this->load->library('email', $config);
		        $this->email->from(ADMIN_MAIL_ID, "Admin Team");
		        $this->email->to($email);
		        $this->email->subject("Your pin verfication SMS");
		        $this->email->message("Hi"." ".$name.",<br/><br/>".$message);
		        if ($this->email->send()) {
		        	$result = $this->call_api($url,$method,$user_data);
					$this->session->set_flashdata('success', SEND_RESET_PIN_MAIL);
					redirect('/');
		        }
		        else
		        {	
		        	$this->session->set_flashdata('error', ERROR_COMMON);
		        	redirect('reset_pin');
		        }
			}
			else
			{
				$this->session->set_flashdata('error', $result['message']);
				redirect('reset_pin');
			}
	 	}
		$this->load->view('staff/reset_pin');
	 }


	 /**
     * Reset PIn when mail
     * --------------------
     * reset link activation link    
     * @author
     */

	 public function reset_pin_no($id = false)
	 {
	 	$api_urls 	= 	$this->config->item('api_urls');
	 	$url = $api_urls['check_reset_pin']."/".$id;
       	$method = 'GET';
       	$result = $this->call_api($url,$method);
       	if($result['data'])
       	{
       			$link_id	=	$result['data']['reset_pin'];
       			$submit = $this->input->post('pin_submit');
			 	if(isset($submit))
		       	{
		       	  $url_edit = $api_urls['edit_staff_pin']."/".$result['data']['staff_id'];
		       	  $method = 'PUT';
		       			$user_data = array (
							'reset_pin' => " ",
							'pin' => $this->input->post('pin_no'),
							'confirm_pin_no' => $this->input->post('confirm_pin_no'),
							'check_mail'  => 1
						);
					$result = $this->call_api($url_edit,$method,$user_data);					
					if ($result['status'] === 'success')
					{
						$this->session->set_flashdata('success', PIN_RESET);
						redirect('/');
					}
					else
					{
						$this->session->set_flashdata('error', $result['message']);
						redirect(base_url()."reset_pin_no/".$id);
					}
		       	}
       		$this->load->view('staff/reset_pin_no',array('reset_pin' => $link_id ));
       	}
       	else
       	{
       		$this->session->set_flashdata('error', LINK_EXPIRY);
       		redirect('/');
       	}
      
	 }

	




	
}
