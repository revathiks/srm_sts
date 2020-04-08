<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Swagger\Annotations as SWG;

require APPPATH . '/core/API_Controller.php';

/**
 * Class Student
 */

class Student extends API_Controller {

    public function __construct() { 
        parent::__construct();\
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        // Load the user model
        $this->load->model('user');
    }

    /**
     * Student list API
     * --------------------
     * @method : Get
     * @link: api/student
     * @author
     */

    public function student_list_get($page = false, $limit = 1)
    {        
        header("Access-Control-Allow-Origin: *");
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
            'requireAuthorization' => true,
        ]);

        $table = STUDENT_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = STUDENT_TB.'.student_id,'.
        STUDENT_TB.'.name,'.
        STUDENT_TB.'.email,'.
        STUDENT_TB.'.reg_no,'.       
        STUDENT_TB.'.address,'.
        STUDENT_TB.'.district,'.
        STUDENT_TB. '.phone,'.
        DEPARTMENT_TB.'.department_name,'.
        STUDENT_TB.'.status,'.
        STATE_TB.'.state_name,'.
        CATEGORY_TB.'.category_name';
        $category_id = $this->input->get('category_id');
        $department = $this->input->get('department');
        $joins = array(
            array(
                'table' => CATEGORY_TB,
                'condition' => CATEGORY_TB.'.category_id = '.STUDENT_TB.'.category_id',
                'jointype' => 'LEFT'
            ),
            array(
                'table' => DEPARTMENT_TB,
                'condition' => DEPARTMENT_TB.'.department_id = '.STUDENT_TB.'.department',
                'jointype' => 'LEFT'
            ),
            array(
                'table' => STATE_TB,
                'condition' => STATE_TB.'.state_id = '.STUDENT_TB.'.state_id',
                'jointype' => 'LEFT'
            ),
        );       
                
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['page'] = $page;
        $params['limit'] = $limit;
        $params['columns'] = $columns;
        $params['pk'] = 'student_id';
        $params['table_prefix'] = $table_prefix;
        $params['joins'] = $joins;
        $params['id'] = '';
        if(isset($category_id))
        {
            $cond[CATEGORY_TB.'.category_id'] = $category_id;
            $params['cond'] = $cond;
        }
        if(isset($department))
        {
            $cond[STUDENT_TB.'.department'] = $department;
            $params['cond'] = $cond;
        }
        $this->_select_table($params);
    }
    //view student
    public function viewstudent_get($page = false, $limit = false)
    {
        header("Access-Control-Allow-Origin: *");
        $student_id     =    $this->uri->segment(3);
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
            'requireAuthorization' => true,
        ]);
        $table = STUDENT_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = STUDENT_TB.'.student_id,'.
            STUDENT_TB.'.name,'.
            STUDENT_TB.'.email,'.
            STUDENT_TB.'.address,'.
            STUDENT_TB.'.district,'.
            STUDENT_TB. '.phone,'.
            STUDENT_TB. '.reg_no,'.
            STUDENT_TB. '.year_of_study,'.
            STUDENT_TB. '.department,'.
            STUDENT_TB. '.category_id,'.
            STUDENT_TB.'.status,'.
            CATEGORY_TB.'.category_name';  
        
        $joins = array(
            array(
                'table' => CATEGORY_TB,
                'condition' => CATEGORY_TB.'.category_id = '.STUDENT_TB.'.category_id',
                'jointype' => 'LEFT'
            )
        ); 
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['page'] = $page;
        $params['limit'] = $limit;
        $params['columns'] = $columns;
        $params['pk'] = 'student_id';
        $params['table_prefix'] = $table_prefix;
        $params['joins'] = $joins;
        $params['id'] = $student_id;
        $params['id_col'] = 'student_id';
        $this->_select_table($params);
    }
    /*View all student End*/
    
    
    //edit student
    public function editstudent_put ()
    {
        $user_data = $this->_RESTConfig([
            'methods' => ['PUT'],
            'requireAuthorization' => true,
        ]);
        $student_id     =    $this->uri->segment(3);
        $table = STUDENT_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $columns = 'student_id,name,email,address,district,phone,reg_no,year_of_study,department,category_id,state_id,
        status';
        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[500]');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[500]|callback_email_check[' . $student_id . ']');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[500]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('district', 'District', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('district', 'District', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('reg_no','Register No','trim|required|max_length[100]');
        $this->form_validation->set_rules('year_of_study','Year of Study','trim|required|numeric');
        $this->form_validation->set_rules('department','Department','trim|required|numeric');
        $this->form_validation->set_rules('category_id','Category','trim|required|numeric');
        $this->form_validation->set_rules('state_id','state','trim|required|numeric');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|max_length[50]');
        //print_r($this->form_validation);
        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Errors
            $message = array(
                'status' => 'error',
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );
            $this->response($message, API_Controller::HTTP_OK);
        }
        else {
            $params = array();
            $params['table'] = $table;
            $params['function_name'] = $function_name;
            $params['columns'] = $columns;
            $params['table_prefix'] = $table_prefix;
            $params['id'] = $student_id;
            $params['id_col'] = 'student_id';
            $params['success_message'] = STUDENT_UPDATE;
            $this->_update_common_table($params);
        }
        
    }
    
    public function email_check($email, $id,$page=false,$limit=FALSE) {
        $email_id = trim($email);
        header("Access-Control-Allow-Origin: *");
        $student_id     =    $this->uri->segment(3);
        $table = STUDENT_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = '*';
            $params = array();
                       
            $params['table'] = $table;
            $params['function_name'] = $function_name;
            $params['page'] = $page;
            $params['limit'] = $limit;
            $params['columns'] = $columns;
            $params['pk'] = 'student_id';
            $params['table_prefix'] = $table_prefix;           
            $params['id'] = '';
            //$params['id_col'] = 'student_id';
            $cond[STUDENT_TB.'.email'] = $email;
            $params['cond'] = $cond;
            $cond[STUDENT_TB.'.email'] = $email_id;
            $params['notin_cond'] = $cond; 
            $emailCheck=  $this->_select_table($params);
           
        if (!empty($emailCheck['data'])) {
            //$this->form_validation->set_message('email_check', "The %s field must contain a unique value.");
            return false;
        }else{
            return true;
        }
    }
    
    //create 
    public function createstudent_post()
    {
        $user_data = $this->_RESTConfig([
            'methods' => ['POST'],
            'requireAuthorization' => true,
        ]);
        $table = STUDENT_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $columns = 'student_id,name,email,address,district,phone,
            reg_no,year_of_study,department,category_id,
            status,state_id';
        //$this->form_validation->set_data($this->put());
        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique[student_tb.email]|max_length[500]');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[500]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('district', 'District', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('reg_no','Register No','trim|required|is_unique[student_tb.reg_no]|max_length[100]');
        $this->form_validation->set_rules('year_of_study','Year of Study','trim|required|numeric');
        $this->form_validation->set_rules('department','Department','trim|required|numeric');
        $this->form_validation->set_rules('category_id','Category','trim|required|numeric');
        $this->form_validation->set_rules('state_id','state','trim|required|numeric');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|max_length[50]');
        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Errors
            $message = array(
                'status' => 'error',
                'message' => validation_errors()
            );
            $this->response($message, API_Controller::HTTP_OK);
        }
        else {
            $params = array();
            $params['table'] = $table;
            $params['function_name'] = $function_name;
            $params['columns'] = $columns;
            $params['table_prefix'] = $table_prefix;
            $params['success_message'] = STUDENT_INSERT;
            $this->_create_common_table($params);
        }
    }
    //end create
    /**
     * delete  tips API
     * --------------------
     * @method : get
     * @link: api/delete_tips/id
     * @author
     */
    
    public function deletestudent_get()
    {
        $student_id     =    $this->uri->segment(3);
        $table = STUDENT_TB;
        $table_prefix = '';
        $columns = '';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['columns'] = $columns;
        $params['table_prefix'] = $table_prefix;
        $params['id'] = $student_id;
        $params['id_col'] = 'student_id';
        $params['success_message'] = STUDENT_DELETE;
        $this->_delete_common_table($params);
    }
    //end edit student    
    
    public function multi_delete_students_get()
    {
        $student_id     =    $this->get('student_id');
        $students_id     =     explode(',', $student_id);
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
            'requireAuthorization' => true,
        ]);
        $table = STUDENT_TB;
        $table_prefix = '';
        $columns = '';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $params = array();
        $in_cond[STUDENT_TB.'.student_id'] = $students_id; 
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['columns'] = $columns;
        $params['table_prefix'] = $table_prefix;
        $params['in_cond'] = $in_cond;
        $params['id'] = $student_id;
        $params['id_col'] = 'student_id';
        $params['success_message'] = STUDENT_DELETE;
        $this->_delete_common_table($params);
    }
    //category
    public function category_get($page = false, $limit = false)
    {       
        header("Access-Control-Allow-Origin: *");
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
            'requireAuthorization' => true,
        ]);
        $table = CATEGORY_TB;
        $table_prefix = '';
        $cond = array ('status' => '1');
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = 'category_id,category_name,status';
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['page'] = $page;
        $params['limit'] = $limit;
        $params['columns'] = $columns;
        $params['pk'] = 'category_id';
        $params['table_prefix'] = $table_prefix;
        $params['cond'] = $cond;
        $this->_select_table($params);
    }
    /* View all category End*/
    //state
    public function state_get($page = false, $limit = 100)
    {
        header("Access-Control-Allow-Origin: *");
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
            'requireAuthorization' => true,
        ]);
        $table = STATE_TB;
        $table_prefix = '';
        $cond = array ('status' => '1');
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = '*';
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['page'] = $page;
        $params['limit'] = $limit;
        $params['columns'] = $columns;
        $params['pk'] = 'state_id';
        
        $params['table_prefix'] = $table_prefix;
        $params['cond'] = $cond;
        $this->_select_table($params);        
    }
    
}