<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/core/API_Controller.php';

/**
 * Class Department
 */

class Department extends API_Controller {

    public function __construct() { 
        parent::__construct();\
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }
    
    /**
     * Department list API
     * --------------------
     * @method : Get
     * @link: api/department
     * @author
     */

     public function department_list_get($page = false, $limit = false)
    {

        header("Access-Control-Allow-Origin: *");   
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
           'requireAuthorization' => true,
        ]);
        $table = DEPARTMENT_TB;
        $table_prefix = '';
        $cond = array ('status' => '1');
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = 'department_id,department_name,status';
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['page'] = $page;
        $params['limit'] = $limit;
        $params['columns'] = $columns;
        $params['pk'] = 'department_id';
        $params['table_prefix'] = $table_prefix;
        $params['cond'] = $cond;
        $this->_select_table($params);
    }
    /* View all department End*/

	/**
     * View Single department API
     * --------------------
     * @method : Get
     * @link: api/department/id
     * @author
     */

     public function view_department_get($facid,$page = false, $limit = false)
    {
       header("Access-Control-Allow-Origin: *");   
        //Call _apiConfig From REST_Controller
        $user_data = $this->_RESTConfig([
            'methods' => ['GET'],
            'requireAuthorization' => true,
        ]);
        $table = DEPARTMENT_TB;
        $table_prefix = '';
        $cond = array ('status' => '1','faculty_id' => $facid);
        $function_name = $this->getFunctionName(__FUNCTION__);
        $columns = 'faculty_id,department_id,department_name,status';
        $params = array();
        $params['table'] = $table;
        $params['function_name'] = $function_name;
        $params['page'] = $page;
        $params['limit'] = $limit;
        $params['columns'] = $columns;
        $params['pk'] = 'department_id';
        $params['table_prefix'] = $table_prefix;
        $params['cond'] = $cond;
        $this->_select_table($params);
    }
    /*View Single department End*/
}