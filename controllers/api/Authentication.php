<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the API Controller 
require APPPATH . '/core/API_Controller.php';

class Authentication extends API_Controller {

    public function __construct() { 
        parent::__construct();\
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        // Load the user model
        $this->load->model('common');
    }
    
    /**
     * User Login API
     * --------------------
     * @param: email 
     * @param: password
     * --------------------------
     * @method : POST
     * @link: api/login
     * @author
     */

     public function login_post()
    {
        header("Access-Control-Allow-Origin: *"); 
        # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)  
        $_POST      =  $this->securityXSSClean($this->post() , 'POST');
        $role_id    =  $this->input->post('role_id');
		$this->form_validation->set_rules('role_id', 'Role', 'trim|numeric|required|max_length[50]');
        $params = array();
        # Form Validation
        if($role_id == 1)
        {
            $this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[100]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[50]');
        }
        else
        {
			$this->form_validation->set_rules('phone_no', 'Phone', 'required|numeric|trim');
			$this->form_validation->set_rules('pin_no', 'Pin', 'required|numeric|trim|max_length[6]');
        }
       
        
        if ($this->form_validation->run() == FALSE)
        {   
              $set_message    =  strip_tags(validation_errors());
              $this->response_error_message('error',$set_message);
        }
        else
        {
            if($role_id == 2)
            {
                // Check if any user exists with the given credentials
                $cond = array(
                            'contactno' => $this->post('phone_no'),
                            'pin' => $this->post('pin_no'),
                            'status' => '1'
				);
                $params['cond']                 =   $cond;
                $params['table']                =   STAFF_TB;
                $params['role_id']              =   $role_id;
                $params['pk']                   =   'staff_id';
                $params['return_type']          =   'single';
                $params['table_prefix']         =   '';
                $params['columns']              =   'staff_id,official_mailid,contactno,emp_id,staff_name';
                $params['message']              =    INVALID_STAFF_CREDENTIAL;
                $params['returnresponse']       =   TRUE;
                $output = $this->_select_table($params);
                $output['data']['staff_id'] = $output['data']['staff_id'];
                $output['data']['email'] = $output['data']['official_mailid'];
                $output['data']['contactno'] = $output['data']['contactno'];
                $output['data']['emp_id'] = $output['data']['emp_id'];
                $output['data']['staff_name'] = $output['data']['staff_name'];
                //$output['data']['user_id'] = $output['data']['staff_id'];
               // $output['data']['user_id'] = $output['data']['staff_id'];
                //unset($output['data']['staff_id']);
                if(!empty($output['data']))
                {
                    $data = array_merge($output['data'], array("role_id"=> $role_id));
                    //Generate Token
                    $this->generate_jwt_token($data);
                }
            }
            else
            {
                // Check if any user exists with the given credentials
                $cond = array(
                            'user_name' => $this->post('username'),
                            'password' => md5($this->post('password')),
                            'status' => '1'
                            );
							
                $params['cond']                 =   $cond;
                $params['table']                =   USER_TB;
                $params['role_id']              =   $role_id;
                $params['pk']                   =   'user_id';
                $params['return_type']          =   'single';
                $params['table_prefix']         =   '';
                $params['columns']              =   'user_id';
                $params['message']              =    INVALID_ADMIN_CREDENTIAL;
                $params['returnresponse']       =   TRUE;
                $output = $this->_select_table($params);
                //$output['data']['users_id'] = $output['data']['user_id'];
                //unset($output['data']['user_id']);
                if(!empty($output['data']))
                {
                    $data = array_merge($output['data'], array("role_id"=> $role_id));
                    //Generate Token
                    $this->generate_jwt_token($data);
                }
            }
        }  
    }

    /*Generate JWT Token*/

    public function generate_jwt_token($datas)
    { 
        $this->load->library('Authorization_Token');
        if($datas['role_id'] == 1)
        {
            $token_data['user_id'] = $datas['user_id'];
            $token_data['role_id'] = $datas['role_id']; 
        }
        else
        {
            $token_data['staff_id'] = $datas['staff_id'];
            $token_data['role_id'] = $datas['role_id']; 
            $token_data['email'] = $datas['email']; 
            $token_data['contactno'] = $datas['contactno']; 
            $token_data['emp_id'] = $datas['emp_id']; 
            $token_data['staff_name'] = $datas['staff_name']; 
        }
       
        $user_token['access_token'] = $this->authorization_token->generateToken($token_data);
        if(!empty( $user_token['access_token'] ))
            {
                $message = array(
                        'status' => TRUE,
                        'message' => $user_token['access_token']
                );
                return $this->response_successdata($message,false);
            }
        else
        {
             $this->response_error_message('error',ERROR_COMMON);
        }
       
    }

    /*Login End*/

    /* Register SMS */

    public function reset_pin_post()
    {
        $phone_no   =   $this->input->post('contact_no');    
        $this->form_validation->set_rules('contact_no', 'Phone No', 'trim|numeric|required|max_length[10]|min_length[10]');
        if ($this->form_validation->run() == FALSE)
        {   
                $set_message    =  strip_tags(validation_errors());
                $this->response_error_message('error',$set_message);
        }
        else
        {
                $cond = array(
                            'contactno' => $phone_no,
                            'status' => '1'
                            );
                $params['cond']                 =   $cond;
                $params['table']                =   STAFF_TB;
                $params['pk']                   =   'staff_id';
                $params['return_type']          =   'single';
                $params['table_prefix']         =   '';
                $params['columns']              =   'staff_id,pin,official_mailid,staff_name';
                $params['message']              =    ERROR_PIN_NO;
                $params['returnresponse']       =   TRUE;
                $output = $this->_select_table($params);
                $reset_link = sha1(mt_rand(10000,99999).time().$output['data']['staff_id']);
                $datas = array_merge($output['data'], array("reset_link"=> $reset_link));
                 $message = array(
                    'status' => 'success',
                    'message' => $datas
             );
                return $this->response_successdata($message,false);                 
                
        }
    }

    /* Register SMS End */

    /**
     *  Update Staff
     * --------------------
     * Load campus list in login page 
     * Show active campus list
     * @author
     */

    public function edit_staff_pin_put ()
    {
        $staff_id     =    $this->uri->segment(3);
        $this->form_validation->set_data($this->put());
        if($this->put('check_mail') == 1)
        {
        $this->form_validation->set_rules('pin', 'PIN No', 'trim|numeric|required|max_length[6]|min_length[6]');
        $this->form_validation->set_rules('confirm_pin_no', 'Pin Confirmation', 'trim|required|matches[pin]');
        }
        else
        {
            $this->form_validation->set_rules('reset_pin', 'Reset No', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE)
        {   
                $set_message    =  strip_tags(validation_errors());
                $this->response_error_message('error',$set_message);
        }
        else
        {
        $table = STAFF_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $columns = 'reset_pin,pin';
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['columns'] = $columns;
        $params['table_prefix'] = $table_prefix;
        $params['id'] = $staff_id;
        $params['id_col'] = 'staff_id';
        $params['success_message'] = COMMON_STAFF_UPDATE;
        $this->_update_common_table($params);
        }
    }


    /**
     *  Check Staff
     * --------------------
     * Load campus list in login page 
     * Show active campus list
     * @author
     */

    public function check_reset_pin_get ()
    {
        $reset_pin     =    $this->uri->segment(3);
        $table = STAFF_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $columns = 'reset_pin,staff_id';
        $cond = array('status' => '1');
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['columns'] = $columns;
        $params['table_prefix'] = $table_prefix;
        $params['id'] = $reset_pin;
        $params['id_col'] = 'reset_pin';
        $params['cond']                 =   $cond;
        $params['pk']                   =   'staff_id';
        $this->_select_table($params);
    }

    /**
     *  Update Staff
     * --------------------
     * Load campus list in login page 
     * Show active campus list
     * @author
     */

    public function reset_pin_no_put ()
    {
        $reset_pin     =    $this->uri->segment(3);
        $table = STAFF_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $columns = 'reset_pin,pin';
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['columns'] = $columns;
        $params['table_prefix'] = $table_prefix;
        $params['id'] = $reset_pin;
        $params['id_col'] = 'reset_pin';
        $params['success_message'] = COMMON_STAFF_UPDATE;
        $this->_update_common_table($params);
    }
    
    
   


   

}