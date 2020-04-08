<?php

/*
* @Author			: Geetha
* @Created Date		: 20/06/2018
* @Version			: 0.1
* @Description		: Dashboard Page
* Controller Name   : dashboard.php
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	/**
	 * Dashboard constructor.
	 */
	public function __construct() {		
		parent::__construct();
		$this->IsAdminLogged();

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
		$session_id = $this->session->userdata('access_token');	
		$api_urls = $this->config->item('api_urls');
                /*Get Critical Student count of this month*/	
                $result=array();
		$this->load->view('admin/dashboard', array ( 'result' => $result ));
	}


	/**
     * Logout
     * --------------------
     * Clear all Session     
     * @author
     */

	public function logout()
	{
		$this->session->sess_destroy();
		$this->session->set_flashdata('success', 'You have successfully logged out!');
		redirect('/', 'refresh');
	}


	
}
