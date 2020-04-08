<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Swagger\Annotations as SWG;

require APPPATH . '/core/API_Controller.php';

/**
 * Class Student
 */

class Student_health extends API_Controller {
    
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
    
    public function student_health_list_get($page = false, $limit = 2)
    {
        header("Access-Control-Allow-Origin: *");
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
            'requireAuthorization' => true,
        ]);
        $table = HEALTH_LEVEL_TRACK_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = HEALTH_LEVEL_TRACK_TB.'.health_track_id,'.
            HEALTH_LEVEL_TRACK_TB.'.health_level_id,'.
            HEALTH_LEVEL_TRACK_TB.'.notes,'.
            STUDENT_TB.'.student_id,'.
            STUDENT_TB.'.reg_no,'.
            STUDENT_TB.'.phone,'.
            STUDENT_TB.'.email,'.
            STUDENT_TB.'.name,'.
            STUDENT_TB.'.category_id,'.
            HEALTH_LEVEL_TB. '.level,'.
            HEALTH_LEVEL_TB.'.severity,'.
            HEALTH_LEVEL_TRACK_TB.'.status,'.
            DEPARTMENT_TB.'.department_name,'.
            CATEGORY_TB.'.category_name';
            $category_id = $this->input->get('category_id');
            $health_level_id = $this->input->get('health_level_id');
            $department = $this->input->get('department');
            $joins = array(
                array(
                    'table' => STUDENT_TB,
                    'condition' => STUDENT_TB.'.student_id = '.HEALTH_LEVEL_TRACK_TB.'.student_id',
                    'jointype' => 'LEFT'
                ),
                array(
                    'table' => DEPARTMENT_TB,
                    'condition' => DEPARTMENT_TB.'.department_id = '.STUDENT_TB.'.department',
                    'jointype' => 'LEFT'
                ),
                array(
                    'table' => HEALTH_LEVEL_TB,
                    'condition' => HEALTH_LEVEL_TB.'.health_level_id = '.HEALTH_LEVEL_TRACK_TB.'.health_level_id',
                    'jointype' => ''
                ),
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
            $params['id'] = '';
            if(isset($category_id))
            {
                $cond[CATEGORY_TB.'.category_id'] = $category_id;
                $params['cond'] = $cond;
            }
            if(isset($health_level_id))
            {
                $cond[HEALTH_LEVEL_TB.'.health_level_id'] = $health_level_id;
                $params['cond'] = $cond;
            }
            if(isset($department))
            {
                $cond[DEPARTMENT_TB.'.department_id'] = $department;
                $params['cond'] = $cond;
            }
            $this->_select_table($params);
    }
    
    public function multi_update_students_health_put()
    {
        $health_track_id= $this->put('health_track_id');  
        //health_track_id is student id-temporary
        $trackid_list    = explode(',', $health_track_id);
        $health_level_id     =    $this->put('health_level_id');
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['PUT'],
            'requireAuthorization' => true,
        ]);
        
        //insert into history table
        $explode_student=$trackid_list;
        $count_data = 0;
        $columns_history = 'student_id,health_level_id,notes';
        for($i=0;$i<count($explode_student);$i++)
        {
            $data = array (
                'student_id'=>$explode_student[$i],
                'health_level_id'=>$health_level_id,
                'notes'=>"Through bulk update"
            ); 
            $table=HEALTH_LEVEL_TRACK_HISTORY_TB;
            $table_prefix="";
            $insert = $this->common->common_insert ( $table , $table_prefix , $data );
            $count_data++;
        }
        //end insert history
        $table = HEALTH_LEVEL_TRACK_TB;
        $table_prefix = '';
        $columns = 'health_level_id';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $params = array();
        $in_cond[HEALTH_LEVEL_TRACK_TB.'.student_id'] = $trackid_list;
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['columns'] = $columns;
        $params['table_prefix'] = $table_prefix;
        $params['in_cond'] = $in_cond;
        $params['id'] = $trackid_list;
        $params['id_col'] = 'student_id';
        $params['success_message'] = HEALTH_UPDATE;
        $this->_update_common_table($params);        
    }
    //create
    public function createstudent_health_post()
    {
        $user_data = $this->_RESTConfig([
            'methods' => ['POST'],
            'requireAuthorization' => true,
        ]);
        $table = HEALTH_LEVEL_TRACK_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $columns = 'health_track_id,student_id,health_level_id,notes,status';
        //$this->form_validation->set_data($this->put());
        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('category_id', 'Category', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('student_id', 'Student', 'trim|required|max_length[500]');
        $this->form_validation->set_rules('health_level_id', 'Level', 'trim|required|max_length[500]');
        $this->form_validation->set_rules('notes', 'Notes', 'trim|required|max_length[150]');
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
            //check existing record
            $cond = array(
                'student_id' =>$this->input->post('student_id'),
                
            );
            $params=array();
            $params['cond']                 =   $cond;
            $params['table']                =   HEALTH_LEVEL_TRACK_TB;
            $params['pk']                   =   'health_track_id';
            $params['return_type']          =   'single';
            $params['table_prefix']         =   '';
            $params['columns']              =   'health_track_id,student_id';
            $params['message']              =    INVALID_STAFF_CREDENTIAL;
            $params['returnresponse']       =   TRUE;
            $params['hideempty']       =   TRUE;
            $output = $this->_select_table($params);
            //end checking
            
            //prepare history records
            //insert into history table
            $params_his = array();
            $columns2 = 'health_history_id,student_id,health_level_id,notes';
            $params_his['table'] = HEALTH_LEVEL_TRACK_HISTORY_TB;
            $params_his['function_name'] = $function_name;
            $params_his['columns'] = $columns2;
            $params_his['table_prefix'] = $table_prefix;
            $params_his['success_message'] = HEALTH_INSERT;
            $params_his['returnresponse']       =   TRUE;
            //end history record
            if(empty($output) && empty($output['data'])) {
                $params = array();
                $params['table'] = $table;
                $params['function_name'] = $function_name;
                $params['columns'] = $columns;
                $params['table_prefix'] = $table_prefix;
                $params['success_message'] = HEALTH_INSERT;
                $params['returnresponse']       =   TRUE;
                $insertresult =$this->_create_common_table($params);
                //insert into history also
                if($insertresult){
                    $insertresult2=$this->_create_common_table($params_his);
                    $message = array(
                        'status' => 'success',
                        'message' => HEALTH_INSERT
                    );
                    $this->response($message, API_Controller::HTTP_OK);
                }
            }else{
                //update health level at track master table
                $table=HEALTH_LEVEL_TRACK_TB;
                $data=array(
                    'health_level_id'=> $this->post('health_level_id'),
                    'notes'=>$this->post('notes')
                );
                $where=array(
                    'student_id'=>$this->post('student_id')
                    
                );
                $cond=array();
                $update = $this->common->common_update ( $table , '' , $data,$where,$cond );
                if($update){
                    $insertresult=$this->_create_common_table($params_his);
                    if($insertresult) {
                        $message = array(
                            'status' => 'success',
                            'message' => HEALTH_INSERT
                        );
                        $this->response($message, API_Controller::HTTP_OK);
                    }
                }else{
                    $message = array(
                        'status' => 'error',
                        'message' => 'Something went to wrong'
                    );
                    $this->response($message, API_Controller::HTTP_OK);
                    
                }
            }
            
        }
    }
    //end create
    
    //view student
    public function viewstudent_health_get($page = false, $limit = false)
    {
        header("Access-Control-Allow-Origin: *");
        $student_id     =    $this->uri->segment(3);
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
            'requireAuthorization' => true,
        ]);
        
        $table=HEALTH_LEVEL_TRACK_HISTORY_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = HEALTH_LEVEL_TRACK_HISTORY_TB.'.health_history_id,'.
            HEALTH_LEVEL_TRACK_HISTORY_TB.'.student_id,'.
            HEALTH_LEVEL_TRACK_HISTORY_TB.'.health_level_id,'.
            HEALTH_LEVEL_TRACK_HISTORY_TB.'.notes,'.
            HEALTH_LEVEL_TRACK_HISTORY_TB.'.created_at,'.
            STUDENT_TB.'.name,'.
            STUDENT_TB.'.reg_no,'.
            HEALTH_LEVEL_TB.'.level,'.
            HEALTH_LEVEL_TB.'.severity';
            
            $joins = array(
                array(
                    'table' => STUDENT_TB,
                    'condition' => STUDENT_TB.'.student_id = '.HEALTH_LEVEL_TRACK_HISTORY_TB.'.student_id',
                    'jointype' => 'LEFT'
                ),
                array(
                    'table' => HEALTH_LEVEL_TB,
                    'condition' => HEALTH_LEVEL_TB.'.health_level_id = '.HEALTH_LEVEL_TRACK_HISTORY_TB.'.health_level_id',
                    'jointype' => 'LEFT'
                )
            );
            $params = array();
            $params['table'] = $table;
            $params['function_name'] = $function_name;
            $params['page'] = $page;
            $params['limit'] = $limit;
            $params['columns'] = $columns;
            $params['pk'] = 'health_history_id';
            $params['table_prefix'] = $table_prefix;
            $params['joins'] = $joins;
            $params['id'] = '';
            $params['id_col'] ='student_health_track_history_tb.student_id';
            if(isset($student_id))
            {
                $cond[HEALTH_LEVEL_TRACK_HISTORY_TB.'.student_id'] = $student_id;
                $params['cond'] = $cond;
            }
            $this->_select_table($params);
    }
    /*View all student End*/
    
    
    public function existingstudents_health_get($page = false, $limit = false)
    {
        header("Access-Control-Allow-Origin: *");
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
            'requireAuthorization' => true,
        ]);
        $table = HEALTH_LEVEL_TRACK_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = HEALTH_LEVEL_TRACK_TB.'.student_id,'.
            HEALTH_LEVEL_TRACK_TB.'.health_level_id,'.
            HEALTH_LEVEL_TRACK_TB.'.notes,'.       
            STUDENT_TB.'.category_id,'.                     
            CATEGORY_TB.'.category_name';
            $category_id = $this->input->get('category_id');         
          
            $joins = array(
                array(
                    'table' => STUDENT_TB,
                    'condition' => STUDENT_TB.'.student_id = '.HEALTH_LEVEL_TRACK_TB.'.student_id',
                    'jointype' => 'LEFT'
                ),
                
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
            $params['id'] = '';
            if(isset($category_id))
            {
                $cond[CATEGORY_TB.'.category_id'] = $category_id;
                $params['cond'] = $cond;
            }
            
            $this->_select_table($params);
    }
    public function pendingstudents_health_get($page = false, $limit = false)
    {
         $existing_students = trim($this->input->get('existing_students'));
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
             if(isset($existing_students))
             {
                 $existing_students=explode(",",$existing_students);
                 $notincond[STUDENT_TB.'.student_id'] = $existing_students;
                 $params['notin_cond'] = $notincond;
             }             
             $this->_select_table($params);
           
    }
   
    /**
     * delete  tips API
     * --------------------
     * @method : get
     * @link: api/delete_tips/id
     * @author
     */
    
    public function deletestudent_health_get()
    {
        $student_id     = $this->uri->segment(3);
        $table = HEALTH_LEVEL_TRACK_TB;
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
        $params['success_message'] = HEALTH_DELETE;
        $params['returnresponse']       =   TRUE;
        $result=$this->_delete_common_table($params);
        $where=array(
            'student_id'=>$student_id
        );
        $delete = $this->common->common_delete ( $table , $where );
        
        //delete history        
        $table2 = HEALTH_LEVEL_TRACK_HISTORY_TB;
        $delete2 = $this->common->common_delete ( $table2 , $where );
        if($delete && $delete2){        
        $message = array(
            'status' => 'success',
            'message' => HEALTH_DELETE
        );
        $this->response($message, API_Controller::HTTP_OK);        
        }
    }
    
    //health level
    public function health_levels_get($page = false, $limit = false)
    {
        
        header("Access-Control-Allow-Origin: *");
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
            'requireAuthorization' => true,
        ]);
        $table = HEALTH_LEVEL_TB;
        $table_prefix = '';
        $cond = array ('status' => '1');
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = 'health_level_id,level,severity,status';
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['page'] = $page;
        $params['limit'] = $limit;
        $params['columns'] = $columns;
        $params['pk'] = 'health_level_id';
        $params['table_prefix'] = $table_prefix;
        $params['cond'] = $cond;
        $this->_select_table($params);
    }
    /* View all health level End*/
    
    //edit student health
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
        $columns = 'student_id,name,email,address,district,phone,status';
        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[500]');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[500]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('district', 'District', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|max_length[50]');
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
    
}