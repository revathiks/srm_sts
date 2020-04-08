<?php

// Create file application/core/MY_Controller.php
class MY_Controller extends CI_Controller {

    /**
     * REST_Controller constructor.
     */
    function __construct()
    {
        parent::__construct();
        
    }

    /**
     *
     */
    function IsLogged()
    {
        /*if ( (!$this->session->userdata('is_logged_in_staff')) && (!$this->session->userdata('is_logged_in_admin')))
        {
            redirect('/' , 'refresh');
        }*/

        if (!$this->session->userdata('is_logged_in_staff'))
        {
           redirect('/' , 'refresh');
        }
    }

    /**
     *
     */
    function IsAlreadyLogged()
    {
    	
        if ($this->session->userdata('is_logged_in_staff'))
        {
            redirect('dashboard', 'refresh');
        }
        elseif($this->session->userdata('is_logged_in_admin'))
        {
            redirect('admin/dashboard');
        }
    }


    /**
     *
     */
    function IsAdminLogged()
    {
        if (!$this->session->userdata('is_logged_in_admin'))
        {
            redirect('/');
        }
    }

    /**
     *
     */
    function IsAlreadyAdminLogged()
    {
        if ($this->session->userdata('is_logged_in_admin'))
        {
            redirect('admin/dashboard');
        }

    }

    /**
     * common function for call API by curl library
     *
     * @param string $url API URL
     * @param string $method POST/GET/PUT
     * @param array $userData parameters pass by array
     * @return array response of the API Call
     */
    public function call_api($url, $method, $userData = false ,$header =false)
    {
        $this->load->library('Curl');
        //$result = $this->curl->simple_get('http://localhost/bmw_japan/api/user/10');
        $this->curl->create($url);
        // , array(), array('timeout' => 30, 'returntransfer' => TRUE, 'httpheader' => array("X-API-KEY: " . $apiKey) , 'userpwd' => "$username:$password")
        $this->curl->option(CURLOPT_TIMEOUT, 30);
        $this->curl->option(CURLOPT_RETURNTRANSFER, 1);

        // if($userData['auth_user']!="" && $userData['auth_pwd']!=""){
        if (isset($userData['auth_user']) && isset($userData['auth_pwd'])) {
            $this->curl->option(CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            $this->curl->http_login($userData['auth_user'], $userData['auth_pwd']);
        }

        if (isset($header['headers'])) {
            foreach ($header['headers'] as $name => $value) {
                $this->curl->http_header($name, $value);
            }
        }

        if ($method === 'GET') {
            $this->curl->get();
        } elseif ($method === 'POST') {
            $this->curl->post($userData);
        } elseif ($method === 'PUT') {
            $this->curl->put($userData);
        }
        $result = $this->curl->execute();
        if ($this->curl->error_code) {
        }
        $data = json_decode($result, TRUE);

        return $data;
    }


    /**
     * Validate Token decode
     *
     * @param string Accesstoken
     */

    public function get_token_data()
    {
        $session_id         = $this->session->userdata('access_token');
        $session_data       = array();
        $token = array(
                        'status' => TRUE,
                        'token' => $session_id['message']
                );
        $decode_data = $this->authorization_token->web_validate_token($token);
        $this->session->set_userdata(array(
                    'sess_user_details' => $decode_data['data']
                ));
    }
    
    //a2304 modified
    
    public function category_list()
    {
        $session_id         = $this->session->userdata('access_token');
        $header['headers']['Authorization'] = $session_id['message'];
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['category'];
        $method = 'GET';
        $result = $this->call_api($url,$method,false,$header);        
        return $result;
    }
    public function department_list()
    {
        $session_id         = $this->session->userdata('access_token');
        $header['headers']['Authorization'] = $session_id['message'];
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['department'];
        $method = 'GET';
        $result = $this->call_api($url,$method,false,$header);
        return $result;
    }
    public function state_list()
    {
        $session_id         = $this->session->userdata('access_token');
        $header['headers']['Authorization'] = $session_id['message'];
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['state'];
        $method = 'GET';
        $result = $this->call_api($url,$method,false,$header);
        return $result;
    }
    public function health_level_list()
    {
        $session_id         = $this->session->userdata('access_token');
        $header['headers']['Authorization'] = $session_id['message'];
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['health_levels'];
        $method = 'GET';
        $result = $this->call_api($url,$method,false,$header);
        return $result;
    }
    public function getExisitingUserHealth($catid){
        $session_id         = $this->session->userdata('access_token');
        $api_urls = $this->config->item ( 'api_urls' );
        $url = $api_urls['existingstudents_health'];
        $query_data = array();        
        $page = 1;
        if ( !empty( $this->input->get ( 'page', true ) ) ) {
            $page = $this->input->get ( 'page', true );
        }
        $limit = 100000;
       
        $method = 'GET';
        if ( !empty($catid) ) {            
            $query_data['category_id'] = $catid;
        }        
        
        $query = http_build_query($query_data);        
       // $url = $url .'/'.$page.'/'.$limit;
        $url = $url .'?'.$query;
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api ( $url , $method,false,$header );
        
        return $result;
    }
    public function student_list_new() {        
        $session_id         = $this->session->userdata('access_token');
        $api_urls = $this->config->item ( 'api_urls' );
        $sess_user_details = $this->session->userdata('sess_user_details');
        $role_id    =   $sess_user_details->role_id;
        $category_id = $this->input->get ( 'category_id', true ); 
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];
        //$exisitingStudents=$this->getExisitingUserHealth($category_id);
        //$exisitingStudentsArr=array();  
        
        //$url = $api_urls['pendingstudents_health'];
        $url = $api_urls['student'];
        $query_data = array();       
        
        $page = 1;
        if ( !empty( $this->input->get ( 'page', true ) ) ) {
            $page = $this->input->get ( 'page', true );
        }
        $limit = PAGE_LIMIT;
        if ( !empty( $this->input->get ( 'limit', true ) ) ) {
            $limit = $this->input->get ( 'limit', true );
        }        
      
        if ( !empty( $this->input->get ( 'category_id', true ) ) ) {
            $category_id = $this->input->get ( 'category_id', true );           
            $query_data['category_id'] = $category_id;
        }
        if ( !empty( $this->input->get ( 'keywords', true ) ) ) {
            $keywords = $this->input->get ( 'keywords', true );
            $keywords = trim($keywords);
            $keywords = urlencode($keywords);
            $query_data['keywords'] = $keywords;
        }
        if ( !empty( $this->input->get ( 'sort_order', true ) ) && !empty( $this->input->get ( 'sort_by', true ) ) ) {
            $query_data['sort_order'] = $sort_order;
            $query_data['sort_by'] = $sort_by;
        }
        $query = http_build_query($query_data);
        
        $url = $url .'/'.$page.'/'.$limit;
        $url = $url .'?'.$query;
        /*if(!empty($exisitingStudents['data'])){
            foreach ($exisitingStudents['data'] as $student){
                $exisitingStudentsArr[]=$student['student_id'];
            }
            $exisitingStudents_strin=implode(",",$exisitingStudentsArr);
            //$url.='&existing_students='.$exisitingStudents_strin;
        }  */      
        $result = $this->call_api ( $url , $method,false,$header );        
        if($result) {
            foreach($result['data'] as $k=>$v) {
                $result['data'][$k]['id'] = $v['student_id'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
        $returnResponse = $this->output->get_output();        
        return $returnResponse;
    }
    
    //a2304 end

     /**
     *  Faculty list
     * --------------------
     * Load Faculty list in Assigned Student page and Create Staff Page 
     * Show active Assigned Student list & Create Staff list 
     */

    public function faculty_list()
    {
        $session_id         = $this->session->userdata('access_token'); 
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['faculty'];
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api($url,$method,false,$header);
        return $result;
    }

    public function faculty_list_new_old() {
        $session_id         = $this->session->userdata('access_token'); 
        $api_urls = $this->config->item ( 'api_urls' );
        $url = $api_urls[ 'faculty' ];
        $page = 1;
        if ( !empty( $this->input->get ( 'page', true ) ) ) {
            $page = $this->input->get ( 'page', true );
        }
        $limit = PAGE_LIMIT;
        if ( !empty( $this->input->get ( 'limit', true ) ) ) {
            $limit = $this->input->get ( 'limit', true );
        }
        
        $method = 'GET';
        $query_data = array();
        if ( !empty( $this->input->get ( 'keywords', true ) ) ) {
            $keywords = $this->input->get ( 'keywords', true );
            $keywords = trim($keywords);
            $keywords = urlencode($keywords);
            $query_data['keywords'] = $keywords;
        }
        if ( !empty( $this->input->get ( 'sort_order', true ) ) && !empty( $this->input->get ( 'sort_by', true ) ) ) {
            $query_data['sort_order'] = $sort_order;
            $query_data['sort_by'] = $sort_by;
        }
        $query = http_build_query($query_data);

        $url = $url .'/'.$page.'/'.$limit;
        $url = $url .'?'.$query;
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api ( $url , $method,false,$header );
        if($result) {
            foreach($result['data'] as $k=>$v) {
                $result['data'][$k]['id'] = $v['faculty_id'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
        $returnResponse = $this->output->get_output();
        return $returnResponse;
    }

    public function faculty_list_new() {
        $session_id         = $this->session->userdata('access_token'); 
        $api_urls = $this->config->item ( 'api_urls' );
        $sess_user_details = $this->session->userdata('sess_user_details');  
        $role_id    =   $sess_user_details->role_id;      
        $url = $api_urls[ 'faculty' ];
        $query_data = array();
        if($role_id == 1)
        {
            $user_id    =   $sess_user_details->user_id;

            if($user_id == 2)
            {
                $query_data['faculty_id'] = 2;
            }

            if($user_id == 3)
            {
                $query_data['faculty_id'] = 1;
            }

            if($user_id == 4)
            {
                $query_data['faculty_id'] = 3;
            }

            if($user_id == 5)
            {
                $query_data['faculty_id'] = 4;
            }

            if($user_id == 6)
            {
                $query_data['faculty_id'] = 5;
            }

            if($user_id == 7)
            {
                $query_data['faculty_id'] = 6;
            }
        }

        $page = 1;
        if ( !empty( $this->input->get ( 'page', true ) ) ) {
            $page = $this->input->get ( 'page', true );
        }
        $limit = PAGE_LIMIT;
        if ( !empty( $this->input->get ( 'limit', true ) ) ) {
            $limit = $this->input->get ( 'limit', true );
        }
        
        $method = 'GET';
        
        if ( !empty( $this->input->get ( 'keywords', true ) ) ) {
            $keywords = $this->input->get ( 'keywords', true );
            $keywords = trim($keywords);
            $keywords = urlencode($keywords);
            $query_data['keywords'] = $keywords;
        }
        if ( !empty( $this->input->get ( 'sort_order', true ) ) && !empty( $this->input->get ( 'sort_by', true ) ) ) {
            $query_data['sort_order'] = $sort_order;
            $query_data['sort_by'] = $sort_by;
        }
        $query = http_build_query($query_data);

        $url = $url .'/'.$page.'/'.$limit;
        $url = $url .'?'.$query;
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api ( $url , $method,false,$header );
        if($result) {
            foreach($result['data'] as $k=>$v) {
                $result['data'][$k]['id'] = $v['faculty_id'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
        $returnResponse = $this->output->get_output();
        return $returnResponse;
    }


    /**
     *  Department list
     * --------------------
     * Load Department list in Assigned Student page and Create Staff Page 
     * Show active Assigned Student  list and Create Staff list
     */

   
    public function department_list_new() {
        $session_id         = $this->session->userdata('access_token'); 
        $header['headers']['Authorization'] = $session_id['message'];
        $api_urls = $this->config->item ( 'api_urls' );
        $facid = $this->input->get ( 'facid', true );

        $url = $api_urls[ 'view_department' ];
        $url = $url .'/'. $facid;
        $page = 1;
        if ( !empty( $this->input->get ( 'page', true ) ) ) {
            $page = $this->input->get ( 'page', true );
        }
        $limit = PAGE_LIMIT;
        if ( !empty( $this->input->get ( 'limit', true ) ) ) {
            $limit = $this->input->get ( 'limit', true );
        }
        
        $method = 'GET';
        $query_data = array();
        if ( !empty( $this->input->get ( 'keywords', true ) ) ) {
            $keywords = $this->input->get ( 'keywords', true );
            $keywords = trim($keywords);
            $keywords = urlencode($keywords);
            $query_data['keywords'] = $keywords;
        }
        if ( !empty( $this->input->get ( 'sort_order', true ) ) && !empty( $this->input->get ( 'sort_by', true ) ) ) {
            $query_data['sort_order'] = $sort_order;
            $query_data['sort_by'] = $sort_by;
        }
        $query = http_build_query($query_data);

        $url = $url .'/'.$page.'/'.$limit;
        $url = $url .'?'.$query;
        $result = $this->call_api ( $url , $method,false,$header );
        if($result) {
            foreach($result['data'] as $k=>$v) {
                $result['data'][$k]['id'] = $v['department_id'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
        $returnResponse = $this->output->get_output();
        return $returnResponse;
    }

    public function staff_list_new() {
        $session_id         = $this->session->userdata('access_token'); 
        $header['headers']['Authorization'] = $session_id['message'];
        $api_urls = $this->config->item ( 'api_urls' );
        $dep_id = $this->input->get ( 'dep_id', true );
		$facid = $this->input->get ( 'facid', true );
		
        $url = $api_urls[ 'staff' ];
        $page = 1;
        if ( !empty( $this->input->get ( 'page', true ) ) ) {
            $page = $this->input->get ( 'page', true );
        }
        $limit = PAGE_LIMIT;
        if ( !empty( $this->input->get ( 'limit', true ) ) ) {
            $limit = $this->input->get ( 'limit', true );
        }
        
        $method = 'GET';
        $query_data = array();
        if ( !empty( $this->input->get ( 'keywords', true ) ) ) {
            $keywords = $this->input->get ( 'keywords', true );
            $keywords = trim($keywords);
            $keywords = urlencode($keywords);
            $query_data['keywords'] = $keywords;
        }
        if ( !empty( $this->input->get ( 'sort_order', true ) ) && !empty( $this->input->get ( 'sort_by', true ) ) ) {
            $query_data['sort_order'] = $sort_order;
            $query_data['sort_by'] = $sort_by;
        }
        $query_data['dep_id'] = $dep_id;
		$query_data['faculty_id'] = $facid;
        $query_data['select_drop']=1;
        $query = http_build_query($query_data);

        $url = $url .'/'.$page.'/'.$limit;
        $url = $url .'?'.$query;
        $result = $this->call_api ( $url , $method,false,$header );
        if($result) {
            foreach($result['data'] as $k=>$v) {
                $result['data'][$k]['id'] = $v['staff_id'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
        $returnResponse = $this->output->get_output();
        return $returnResponse;
    }


   /**
     *  Designation list
     * --------------------
     * Load Designation list in Assigned Student page and Create Staff Page 
     * Show active Assigned Designation  list and Create Staff list
     */

    public function designation_list()
    {
        $session_id         = $this->session->userdata('access_token'); 
        $header['headers']['Authorization'] = $session_id['message'];
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['designation'];
        $method = 'GET';
        $result = $this->call_api($url,$method,false,$header);
        return $result;
    }

    /*Insert Queue Mail function*/

    public function insert_queue_mail($user_data)
    {
       $session_id = $this->session->userdata('access_token'); 
       $api_urls = $this->config->item('api_urls');
       $url = $api_urls['insert_email_queue'];
       $method = 'POST';
       $header['headers']['Authorization'] = $session_id['message'];
       $insert_email_queue = $this->call_api($url,$method,$user_data,$header);
       return $insert_email_queue['status'];
    }

    /*Check Admin Or USer login*/

   /**
     *  Category list
     * --------------------
     * Load Category list in Create Feedback Page 
     * Show active Assigned Category list and Create Feedback list
     */

	
	public function newtip_list()
    {
        $session_id         = $this->session->userdata('access_token'); 
        $header['headers']['Authorization'] = $session_id['message'];
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['newtip'];
        $method = 'GET';
        $result = $this->call_api($url,$method,false,$header);
        return $result;
    }
}
?>