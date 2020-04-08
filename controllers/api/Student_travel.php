<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Swagger\Annotations as SWG;

require APPPATH . '/core/API_Controller.php';

/**
 * Class Student
 */

class Student_travel extends API_Controller {
    
    public function __construct() {
        parent::__construct();\
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        // Load the user model
        $this->load->model('user');
    }
    
    //create
    public function createstudent_travel_post()
    {
        $user_data = $this->_RESTConfig([
            'methods' => ['POST'],
            'requireAuthorization' => true,
        ]);
        $table = TRAVEL_TRACK_TB;
        $table_prefix = '';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $columns = 'travel_track_id,student_id,place_from,place_to,information,travelled_on,status';
        //$this->form_validation->set_data($this->put());
        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('place_from', 'place_from', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('place_to', 'place_to', 'trim|required|max_length[500]');
        $this->form_validation->set_rules('information', 'information', 'trim|required|max_length[500]');
        $this->form_validation->set_rules('travelled_on', 'travelled_on', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('student_id', 'student_id', 'trim|required|max_length[150]');
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
            $params['table']                =   TRAVEL_TRACK_TB;
            $params['pk']                   =   'travel_track_id';
            $params['return_type']          =   'single';
            $params['table_prefix']         =   '';
            $params['columns']              =   'travel_track_id,student_id';
            $params['returnresponse']       =   TRUE;
            $params['hideempty']       =   TRUE;
            $output = $this->_select_table($params);
            //end checking
            
            //prepare history records
            //insert into history table
            $params_his = array();
            $columns2 = 'travel_id,student_id,place_from,place_to,travelled_on';
            $params_his['table'] = TRAVEL_HISTORY_TB;
            $params_his['function_name'] = $function_name;
            $params_his['columns'] = $columns2;
            $params_his['table_prefix'] = $table_prefix;
            $params_his['success_message'] = TRAVEL_INSERT;
            $params_his['returnresponse']       =   TRUE;
            //end history record
            
            if(empty($output) && empty($output['data'])) {
                $params = array();
                $params['table'] = $table;
                $params['function_name'] = $function_name;
                $params['columns'] = $columns;
                $params['table_prefix'] = $table_prefix;
                $params['success_message'] = TRAVEL_INSERT;
                $params['returnresponse']       =   TRUE;
                $insertresult =$this->_create_common_table($params);
                //insert into history also
                if($insertresult){
                    $insertresult2=$this->_create_common_table($params_his);
                    $message = array(
                        'status' => 'success',
                        'message' => TRAVEL_INSERT
                    );
                    $this->response($message, API_Controller::HTTP_OK);
                }
            }else{
                    $insertresult=$this->_create_common_table($params_his);
                    if($insertresult) {
                        $message = array(
                            'status' => 'success',
                            'message' => HEALTH_INSERT
                        );
                        $this->response($message, API_Controller::HTTP_OK);
                    }                
            }            
            
        }
    }
    //end create
    
    /**
     * Student list API
     * --------------------
     * @method : Get
     * @link: api/student
     * @author
     */
    
    public function student_travel_list_get($page = false, $limit = 1)
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
            STUDENT_TB.'.name,'.
            STUDENT_TB.'.category_id,'.
            HEALTH_LEVEL_TB. '.level,'.
            HEALTH_LEVEL_TB.'.severity,'.
            HEALTH_LEVEL_TRACK_TB.'.status,'.
            CATEGORY_TB.'.category_name';
            $category_id = $this->input->get('category_id');
            $health_level_id = $this->input->get('health_level_id');
            $joins = array(
                array(
                    'table' => STUDENT_TB,
                    'condition' => STUDENT_TB.'.student_id = '.HEALTH_LEVEL_TRACK_TB.'.student_id',
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
        $columns = 'student_id,name,email,address,district,phone,status';
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['page'] = $page;
        $params['limit'] = $limit;
        $params['columns'] = $columns;
        $params['pk'] = 'student_id';
        $params['table_prefix'] = $table_prefix;
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
    
    
    /**
     * delete  tips API
     * --------------------
     * @method : get
     * @link: api/delete_tips/id
     * @author
     */
    
    public function deletetip_get()
    {
        $tip_id     =    $this->uri->segment(3);
        $table = TIPS_TABLE;
        $table_prefix = '';
        $columns = '';
        $function_name = $this->getFunctionName ( __FUNCTION__ );
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['columns'] = $columns;
        $params['table_prefix'] = $table_prefix;
        $params['id'] = $tip_id;
        $params['id_col'] = 'tips_id';
        $params['success_message'] = TIPS_DELETE;
        $this->_delete_common_table($params);
    }
    //end edit student
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
    
}