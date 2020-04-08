<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Student_health extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $role_id 			= $this->session->userdata('sess_user_details');
        if(!isset($role_id))
        {
            $this->IsAdminLogged();
        }
        $this->load->library ( 'Ajax_pagination' );        
    }
    
    public function list()
    {        
        $session_id 		= $this->session->userdata('access_token');
        $role_id 			= $this->session->userdata('sess_user_details')->role_id;
        $department 	=  	$this->department_list();
        $category 	=  	$this->category_list();
        $state 	=  	$this->state_list();
        $healthLevel=$this->health_level_list();
        $api_urls = $this->config->item('api_urls');
        $keywords = $this->input->post('search_text');
        $filterCategory = $this->input->post('category_id'); 
        $health_level_id = $this->input->post('health_level_id');
        $filterDept = $this->input->post('department'); 
        if($role_id == 1)
        {
            $url = $api_urls['student_health'];
            if(isset($keywords))
            {
                $keywords = urlencode($keywords);
                $url = $api_urls['student_health']."?keywords=".$keywords;
            }
            if(isset($filterCategory) && $filterCategory>0)
            {
                $filterCategory = urlencode($filterCategory);
                $url .= "&category_id=".$filterCategory;
            }
            if(isset($health_level_id) && $health_level_id > 0)
            {
                $health_level_id = urlencode($health_level_id);
                $url .= "&health_level_id=".$health_level_id;
            }
            if(isset($filterDept) && $filterDept>0)
            {
                $filterDept = urlencode($filterDept);
                $url .= "&department=".$filterDept;
            }
        }
        else
        {
            $staff_id 			= $this->session->userdata('sess_user_details')->staff_id;
            $url = $api_urls['student_health'].'?staff_id='.$staff_id ;
            if(isset($keywords))
            {
                $keywords = urlencode($keywords);
                $url = $api_urls['student_health']."?keywords=".$keywords."&staff_id=".$staff_id;
            }
        }
        //echo $url;
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api($url,$method,false,$header);
      
        //echo "<pre>";print_r($result);echo "</pre>";
        if(isset($result['data']))
        {
            $config[ 'target' ] = '#postList';
            $config[ 'total_rows' ] = $result[ 'total' ];
            $config[ 'per_page' ] = $result[ 'limit' ];
            if(!empty($filterCategory)){
                $config[ 'category_id' ] = $filterCategory;
            }
            if(!empty($health_level_id)){
                $config['health_level_id' ] = $health_level_id;
            }
            if(!empty($filterDept)){
                $config[ 'department' ] = $filterDept;
            }
            $config[ 'base_url' ] = base_url () . 'student_health/ajax_student_health_pagination';
            $this->ajax_pagination->initialize ( $config );
        }
          $this->load->view ( 'admin/student_health/list' , array ( 'result' => $result,'categories'=>$category ,'department'=>$department,'level'=>$healthLevel) );
    }
    
    public function ajax_student_health_pagination ()
    {
        $page = $this->input->post ( 'page' );
        $limit = $this->input->post ( 'limit' );
        $session_id 		= $this->session->userdata('access_token');
        $api_urls = $this->config->item('api_urls');
        $result_url = $api_urls['student_health'];
        $keywords = $this->input->post('keywords');
        $filterCategory = $this->input->get('category_id');
        $health_level_id = $this->input->get('health_level_id');
        $filterDept = $this->input->post('department'); 
        $url = $result_url  . '/' . $page . '/' . $limit;
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];
        /*if(isset($keywords))
         {
         $keywords = urlencode($keywords);
         $url = $result_url  .'/'. $page . '/' . $limit . "?keywords=".$keywords;
         }
         if(isset($filterCategory) && $filterCategory>0)
         {
         $filterCategory = urlencode($filterCategory);
         $url .= "&category_id=".$filterCategory;
         }*/
        
        $query_data = array();
        if ( !empty( $this->input->post ( 'keywords', true ) ) ) {
            $keywords = $this->input->post ( 'keywords', true );
            $keywords = trim($keywords);
            $keywords = urlencode($keywords);
            $query_data['keywords'] = $keywords;
        }
        if(!empty($filterCategory)){
            $query_data['category_id'] = $filterCategory;
        }
        if(!empty($health_level_id)){
            $query_data['health_level_id'] = $health_level_id;
        } 
        if(!empty($filterDept)){
            $query_data['department'] = $filterDept;
        }        
        
        $query = http_build_query($query_data);
        $url = $url .'?'.$query;
        $result = $this->call_api($url,$method,false,$header);
        
        if(isset($result['data']))
        {
            $config[ 'target' ] = '#postList';
            $config[ 'total_rows' ] = $result[ 'total' ];
            $config[ 'per_page' ] = $result[ 'limit' ];
            if(!empty($filterCategory)){
                $config[ 'category_id' ] = $filterCategory;
            }
            if(!empty($health_level_id)){
                $config['health_level_id' ] = $health_level_id;
            }
            if(!empty($filterDept)){
                $config[ 'department' ] = $filterDept;
            }
            $config[ 'base_url' ] = base_url () . 'student_health/ajax_student_health_pagination';
            $this->ajax_pagination->initialize ( $config );
        }
        $this->load->view ( 'admin/student_health/ajax_list' , array ( 'result' => $result ) );
    }
    //multi update
    public function multi_update_health_level(){
        $trackids	=	trim($this->input->post('trackid_list'));
        $healthLevel	=	trim($this->input->post('level'));
        $cat=	trim($this->input->post('category_id'));
        $session_id = $this->session->userdata('access_token');
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['multi_update_students_health'];
        $userdata=array(
            'health_level_id'=>$healthLevel,
            'health_track_id'=>$trackids
        );
        $method = 'PUT';
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api($url,$method,$userdata,$header);
        if($result['status'] == 'success')
        {
            $this->session->set_flashdata($result['status'], $result['message']);
            echo 1;
        }
        else
        {
            $this->session->set_flashdata($result['status'], $result['message']);
            echo 0;
        }
    }
    //view student
    public function view_student_health($id)
    {
        $session_id 		= $this->session->userdata('access_token');
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['view_student_health']."/".$id;
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api($url,$method,false,$header);
        $this->load->view ( 'admin/student_health/view' , array ( 'result' => $result['data'] ));
    }
    //end view
    
    //edit student
    public function edit_student($id)
    {
        $session_id 		= $this->session->userdata('access_token');
        $submit		= $this->input->post('submit_edit');
        if(isset($submit))
        {
            $api_urls = $this->config->item('api_urls');
            $url = $api_urls['edit_student']."/".$id;
            $method = 'PUT';
            $header['headers']['Authorization'] = $session_id['message'];
            $user_data = array (
                'name' => trim ( $this->input->post ( 'name' ) ) ,
                'email' => trim ( $this->input->post ( 'email' ) ) ,
                'phone' => trim ( $this->input->post ( 'phone' ) ) ,
                'district' => trim ( $this->input->post ( 'district' ) ) ,
                'address' => trim ( $this->input->post ( 'address' ) ) ,
                'status' => trim ( $this->input->post ( 'status' ) )
            );
            $result = $this->call_api($url,$method,$user_data,$header);
            $this->session->set_flashdata($result['status'], $result['message']);
            redirect('admin/student');
        }
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['view_student']."/".$id;
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api($url,$method,false,$header);
        $this->load->view ( 'admin/student/edit' , array ( 'result' => $result['data'] ));
    }
    //end edit student
    
    //add student
    public function create_student_health()
    {             
        $session_id 		= $this->session->userdata('access_token');
        $submit		= $this->input->post('submit_create');
        $category 	=  	$this->category_list();
        $state 	=  	$this->state_list(); 
        $healthLevel=$this->health_level_list();
        if(isset($submit))
        {
            $api_urls = $this->config->item('api_urls');
            $url = $api_urls['create_student_health'];
            $method = 'POST';
            $header['headers']['Authorization'] = $session_id['message'];
            $user_data = array (
                'category_id' => trim ( $this->input->post ( 'category_id' ) ) ,
                'student_id' => trim ( $this->input->post ( 'student_id' ) ) ,
                'health_level_id' => trim ( $this->input->post ( 'health_level_id' ) ) ,
                'notes' => trim ( $this->input->post ( 'notes' ) ) ,
                'status' => trim ( $this->input->post ( 'status' ) )
            );
            
            $result = $this->call_api($url,$method,$user_data,$header);
            if($result['status'] == 'success')
            {                
                $this->session->set_flashdata($result['status'], $result['message']);
                redirect('admin/student_health');
            }
            else
            {
                $this->session->set_flashdata($result['status'], $result['message']);
            }
        }
        $this->load->view ( 'admin/student_health/create',array('categories'=>$category,'state'=>$state,'healthlevel'=>$healthLevel));
    }
    //end add student
    
    public function student_list_new() {
        
        parent::student_list_new();
    }
    
    public function delete_student_health($id)
    {
        $session_id 		= $this->session->userdata('access_token');
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['delete_health']."/".$id;
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];        
        $result = $this->call_api($url,$method,false,$header);
        $this->session->set_flashdata($result['status'], $result['message']);
        $filterCategory=0;
        $redirecturl="";        
        redirect('admin/student_health'.$redirecturl);
    }
}