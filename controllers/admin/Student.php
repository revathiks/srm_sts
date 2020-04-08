<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Student extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $role_id 			= $this->session->userdata('sess_user_details');
        if(!isset($role_id))
        {
            $this->IsAdminLogged();
        }
        $this->load->library ( 'Ajax_pagination' );
        //ini_set('display_errors', 0);
    }
    
    public function list()
    {        
        $filterCategory=0;$keywords="";
        if(isset($_GET['cat']) && ($_GET['cat'])>0){
            $filterCategory=$_GET['cat'];            
        }
        $department 	=  	$this->department_list();
        $category 	=  	$this->category_list();
        $session_id 		= $this->session->userdata('access_token');
        $role_id 			= $this->session->userdata('sess_user_details')->role_id;
        $api_urls = $this->config->item('api_urls');
        if($this->input->post('search_text')){       
        $keywords = $this->input->post('search_text');        
        }
        $filterDept = $this->input->post('department');       
       
        if($role_id == 1)
        {
            $url = $api_urls['student'];
            if(isset($keywords))
            {
                $keywords = urlencode($keywords);
                $url = $api_urls['student']."?keywords=".$keywords;
            }
            if(isset($filterCategory) && $filterCategory>0)
            {
                $filterCategory = urlencode($filterCategory);
                $url .= "&category_id=".$filterCategory;
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
            $url = $api_urls['student'].'?staff_id='.$staff_id ;
            if(isset($keywords))
            {
                $keywords = urlencode($keywords);
                $url = $api_urls['student']."?keywords=".$keywords."&staff_id=".$staff_id;
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
            if(!empty($filterDept)){
                $config[ 'department' ] = $filterDept;
            }            
            $config[ 'base_url' ] = base_url () . 'student/ajax_student_pagination';
            $this->ajax_pagination->initialize ( $config );
        } 
        $this->load->view ( 'admin/student/list' , array ( 'result' => $result,'categories'=>$category,'department'=>$department ) );
    }
    
    public function ajax_student_pagination ()
    {    
        // print_r($_POST);
        //print_r($_GET);
       
        $filterCategory=0;$keywords="";        
        if(isset($_GET['category_id']) && isset($_GET['category_id'])>0){
            $filterCategory=$_GET['category_id'];
        }
        $department 	=  	$this->department_list();
        $category 	=  	$this->category_list();
        $state 	=  	$this->state_list();
        $page = $this->input->post ( 'page' );
        $limit = $this->input->post ( 'limit' );       
        $session_id 		= $this->session->userdata('access_token');
        $api_urls = $this->config->item('api_urls');
        $result_url = $api_urls['student'];
        if(isset($submit)){
        $keywords = $this->input->post('keywords');       
        //$filterCategory = $this->input->get('category_id');
        }
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
        $filterDept = $this->input->get('department');
        if(!empty($filterDept)){
            $query_data['department'] = $filterDept;
        }
        $query = http_build_query($query_data);
         $url = $url .'?'.$query;
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
            if(!empty($filterDept)){
                $config[ 'department' ] = $filterDept;
            }
            $config[ 'base_url' ] = base_url () . 'student/ajax_student_pagination';
            $this->ajax_pagination->initialize ( $config );
        }
        $this->load->view ( 'admin/student/ajax_list' , array ( 'result' => $result ,'department'=>$department,'state'=>$state) );
    }
    
    //view student
    public function view_student($id)
    {
        $session_id 		= $this->session->userdata('access_token');
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['view_student']."/".$id;
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api($url,$method,false,$header);
        $this->load->view ( 'admin/student/view' , array ( 'result' => $result['data'] ));
    }
    //end view
    
    //edit student
    public function edit_student($id)
    {
        
        //print_r($_POST);die;
        $session_id 		= $this->session->userdata('access_token');
        $submit		= $this->input->post('submit_edit');
        $department 	=  	$this->department_list();
        $category 	=  	$this->category_list();
        $state 	=  	$this->state_list();     
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
                'reg_no' => trim ( $this->input->post ( 'reg_no' ) ) ,
                'year_of_study' => trim ( $this->input->post ( 'year_of_study' ) ) ,
                'department' => trim ( $this->input->post ( 'department' ) ) ,
                'category_id' => trim ( $this->input->post ( 'category_id' ) ) ,
                'state_id' => trim ( $this->input->post ( 'state_id' ) ) ,
                'status' => trim ( $this->input->post ( 'status' ) )
            );
            $result = $this->call_api($url,$method,$user_data,$header);
            $this->session->set_flashdata($result['status'], $result['message']);
            $catid=trim($this->input->post ('category_id' ));
            $redirecturl="";
            if(isset($catid) && ($catid)>0){
                $catFromUrl=$catid;
                $redirecturl="?cat=".$catFromUrl;
            }            
            if($result['status'] == 'success')
            {
                 redirect('admin/student'.$redirecturl);
            }else{
                $this->session->set_flashdata($result['status'], $result['message']);
                
            }
           
        }
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['view_student']."/".$id;
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api($url,$method,false,$header);
        $this->load->view ( 'admin/student/edit' , array ( 'result' => $result['data'],'category'=>$category,'department'=>$department,'state'=>$state ));
    }
    //end edit student
    
    //add student
    public function create_student()
    {
        $session_id 		= $this->session->userdata('access_token');
        $submit		= $this->input->post('submit_create');
        $department 	=  	$this->department_list();
        $category 	=  	$this->category_list();
        $state 	=  	$this->state_list();
        if(isset($submit))
        {
            $api_urls = $this->config->item('api_urls');
            $url = $api_urls['create_student'];
            $method = 'POST';
            $header['headers']['Authorization'] = $session_id['message'];
            $user_data = array (  
                'name' => trim ( $this->input->post ( 'name' ) ) ,
                'email' => trim ( $this->input->post ( 'email' ) ) ,
                'phone' => trim ( $this->input->post ( 'phone' ) ) ,
                'district' => trim ( $this->input->post ( 'district' ) ) ,
                'address' => trim ( $this->input->post ( 'address' ) ) ,
                'reg_no' => trim ( $this->input->post ( 'reg_no' ) ) ,
                'year_of_study' => trim ( $this->input->post ( 'year_of_study' ) ) ,
                'department' => trim ( $this->input->post ( 'department' ) ) ,
                'category_id' => trim ( $this->input->post ( 'category_id' ) ) ,
                'state_id' => trim ( $this->input->post ( 'state_id' ) ) ,
                'status' => trim ( $this->input->post ( 'status' ) )
            );
            $result = $this->call_api($url,$method,$user_data,$header);            
            $catidfromForm=trim ( $this->input->post ( 'category_id' ) ) ;
            $redirecturl="";
            if(isset($catidfromForm) && ($catidfromForm)>0){
                $catFromUrl=$catidfromForm;
                $redirecturl="?cat=".$catFromUrl;
            }
            if($result['status'] == 'success')
            {
                
                $this->session->set_flashdata($result['status'], $result['message']);
                redirect('admin/student'.$redirecturl);
            }
            else
            {
                $this->session->set_flashdata($result['status'], $result['message']);
                //redirect('admin/student'.$redirecturl);
            }
        }
        
        $this->load->view ( 'admin/student/create',array('category'=>$category,'department'=>$department,'state'=>$state));
    }
    //end add student
    
    public function delete_student($id)
    {  
        $cat=$this->input->get('cat');
        $session_id 		= $this->session->userdata('access_token');
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['delete_student']."/".$id;
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api($url,$method,false,$header);        
        $this->session->set_flashdata($result['status'], $result['message']);
        $filterCategory=0;
        $redirecturl="";
        if(isset($cat) && ($cat)>0){
            $filterCategory=$cat;
            $redirecturl="?cat=".$filterCategory;
        }
        redirect('admin/student'.$redirecturl);
    }
    
    public function multi_delete_student()
    {
        $to_student_id	=	trim($this->input->post('to_student_id'));
        $cat=	trim($this->input->post('category_id'));
        $session_id = $this->session->userdata('access_token');
        $api_urls = $this->config->item('api_urls');
        $url = $api_urls['multi_delete_students']."?student_id=".$to_student_id;
        $method = 'GET';
        $header['headers']['Authorization'] = $session_id['message'];
        $result = $this->call_api($url,$method,false,$header);
        if($result['status'] == 'success')
        {
            $redirecturl="";
            if(isset($cat) && $cat>0){
                $redirecturl="?cat=".$cat;
            }
            $this->session->set_flashdata($result['status'], $result['message']);
           // redirect('admin/student'.$redirecturl);
           echo 1;
        }
        else
        {
            $this->session->set_flashdata($result['status'], $result['message']);
            echo 0;
        }
    }
    function studentByCategory()
    {        
        $session_id 		= $this->session->userdata('access_token');
        $role_id 			= $this->session->userdata('sess_user_details')->role_id;
        $api_urls = $this->config->item('api_urls');        
        $filterCategory = $this->input->post('category_id');
        $select='<select class="form-control" id="student_id" name="student_id">
        <option value="0">Select category first</option>
        </select>';
        if(isset($filterCategory) && $filterCategory>0)
        {
            $filterCategory = urlencode($filterCategory);
            $url = $api_urls['student']."?category_id=".$filterCategory;
        
            // echo $url;die;
            $method = 'GET';
            $header['headers']['Authorization'] = $session_id['message'];
            $result = $this->call_api($url,$method,false,$header);
          
            $select="";
            if(!empty($result['data'])){
                $select.="<select class=\"form-control valid\" name='student_id' id='student_id'>";
                $select.="<option value='0'>Select Student</option>";
                foreach($result['data'] as $stu){
                    $select.="<option value='".$stu['student_id']."'>".$stu['name']."</option>";
                }
                $select.="</select>";
            }
        }
        echo $select;
    }
    
}