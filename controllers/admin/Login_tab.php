<?php

/*
* @Author			: Geetha
* @Created Date		: 18/06/2018
* @Version			: 3.1.10
* @Description		: login based admin and staff used web service
* Controller Name   : login.php
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_tab extends MY_Controller {

	/**
	 * Login constructor.
	 */
	public function __construct() {		
		parent::__construct();		
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
		$this->IsAlreadyAdminLogged();
		$campus 	=  	$this->campus_list();
		$this->load->view('admin/tab_login' ,array('campus' => $campus));
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

	public function staff_register()
	{
		$this->IsAlreadyAdminLogged();
		$contact_no	=	$this->input->post('contact_no');
		$api_urls 	= 	$this->config->item('api_urls');
		$url 		= 	$api_urls['staff_register'];
		$method = 'POST';
		$userData = array(
			'contact_no' 	=> 	trim($contact_no)
		);
		$result = $this->call_api($url,$method,$userData);
		if($result['status'] === 'success')
		{
			$config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 25,
            'smtp_user' => 'testn9726@gmail.com', // change it to yours
            'smtp_pass' => 'Karthick@03', // change it to yours
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );
        $email = $result['message']['official_mailid'];
        $name = $result['message']['staff_name'];
        $message = "Your pin no : " . $result['message']['pin_no'];
        $this->load->library('email', $config);
        $this->email->from('testn9726@gmail.com', "Admin Team");
        $this->email->to($email);
        $this->email->subject("Hi".$name);
        $this->email->message($message);
        if ($this->email->send()) {
        	$result['status']  = 'success';
            $result['message'] = "Mail sent...";
			
			
        }
        else
        {	$result['status']  = 'error';
        	$result['message'] = "Sorry Unable to send email...";
        }
		
		}
		echo json_encode($result);
		//print_r($result);
	}

	public function pin_verfication()
	{
		$submit = $this->input->post('pin_submit');
		if(isset($submit))
		{
		$api_urls = $this->config->item('api_urls');
		$url = $api_urls['edit_staff_pin'];
		$method = 'PUT';
		$user_data = array (
                'pin' => trim ( $this->input->post ( 'title' ) ) 
            );
		$result = $this->call_api($url,$method,false,false);
		}
		$this->load->view('admin/pin_verfication');
	}

	
}
