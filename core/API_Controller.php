<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load the Rest Controller library
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

// Create file application/core/MY_Controller.php
class API_Controller extends REST_Controller {
	/**
	 * REST_Controller constructor.
	 */
	function __construct() {
		parent::__construct();
		header('Content-Type: text/html; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->page_limit = PAGE_LIMIT;
		$this->page_offset = PAGE_OFFSET;
		// Load the user model
		$this->load->model('common');
	}

	/**
	 * get current function name
	 *
	 * @param string $functionName current function name
	 * @return string $functionName remove get/post and then return the function name
	 * @author
	 */
	protected function getFunctionName($functionName) {
		if ($functionName) {
			if (strpos($functionName, '_') !== false) {
				$arrFunctionName = explode('_', $functionName);
				return $arrFunctionName[0];
			} else {
				return $functionName;
			}
		}
	}

	/**
	 * common function for get master table detail/list
	 *
	 * @param string $table current table name
	 * @param string $tablePrefix current table prefix
	 * @param string $functionName current function name
	 * @param integer $page page number
	 * @param integer $limit limit to show the list
	 * @param array $columns column name of the table
	 * @param string $pk primary key column name of the table
	 * @param array $joins array of the joins
	 * @param array $cond conditions
	 * @param string $returnType single/something
	 * @return json response of list/detail by page & limit
	 * @author
	 */
	protected function _select_table($data = array()) {	 
		$page = false;
		$limit = false;
		$id = false;
		$columns = false;
		$pk = false;
		$joins =false;
		$id_col = false;
		$action = false;
		$cond = false;
		$return_type = false;
		$returnresponse = false;
		$hideempty=false;
		$message ="no data found";
		$no_limit_page = false;
		$group = false;
		$distinct = false;
		$in_cond =false;
		$notin_cond =false;
		if(empty($data)) {
			return false;	
		}
		extract($data);

		if(!$table) {
			return false;
		}
		if (!$columns) {
			$columns = $table_prefix . 'id as id, ' . $table_prefix . 'name  as name, ' . $table_prefix . 'description as description, ' . $table_prefix . 'created_at as created,' . $table_prefix . 'modified_at as modified, ' . $table_prefix . 'status as status';
		}

		$i_page = ($page) ? $page : 0;

		if(!$no_limit_page)
		{
			$limit = $ilimit = $limit ? $limit : $this->page_limit;
		}
		else
		{
			$limit =  PAGE_LIMIT_STUDENT_OBSERVATION_PAGE;
		}

		

		$count_con = array();
		$response = array();
		$con = array();
		$con['conditions'] = $id ? array($table_prefix . $id_col => $id) : $cond;
		
		$con['return_type'] = $id ? 'single' : $return_type;
		if ($id == '') {
			$count_con['return_type'] = 'count';
			
			$keywords = $this->get('keywords',true);
			$keywords = urldecode($keywords);

			if ($keywords) {

				$colum = explode(',', $columns);
				$searchcol = preg_replace('/ as.*/', '', $colum);
				if (!empty($searchcol)) {
					foreach ($searchcol as $value) {
						$value = trim($value);
						if ($keywords) {
							//if (strpos($value, 'description') === false) {
								$count_con['search']['keywords'][$value] = $con['search']['keywords'][$value] = $keywords;
							//}
						}
					}
				}
				// $count_con['search']['keywords'] = $con['search']['keywords'] =  $this->get('keywords');
			}

			$sort_by = $this->input->get('sort_by');
			$sort_order = $this->input->get('sort_order');
			if (!empty($sort_by)) {
				$count_con['search']['sort_by'] = $con['search']['sort_by'] = $table_prefix . $this->input->get('sort_by');
			}

			if (!empty($sort_order)) {
				$count_con['search']['sort_order'] = $con['search']['sort_order'] = $this->input->get('sort_order');
			}

			$count_con['pk'] = $table.'.'.$pk;

			$count_con[ 'conditions' ] = $cond;

            $count_con[ 'joins' ] = $joins;
            $count_con[ 'distinct' ] = $distinct;
            $count_con[ 'group' ] = $group;
            $count_con[ 'in_cond' ] = $in_cond;
            if(!empty($notin_cond)){
            $count_con[ 'notin_cond' ] = $notin_cond;
            }
			
			$count = $this->common->get_rows($table, $table_prefix, $columns, $count_con);


			$num_pages = ceil($count / $limit);

			if (!is_numeric($page)) {
				$page = 0;
			}

			if (!$page) {
				$page = 1;
			}
			$curr_offset = ($page - 1) * $limit;

			if (($curr_offset + $limit) < ($count - 1)) {
				$cur_count = $curr_offset + $limit;
			} else {
				$cur_count = $count;
			}
			if ($id == '' && $return_type === false) {
				$response['prev'] = '';
				if ($num_pages > 1 && ($i_page - 1) != 0) {
					$response['prev'] = base_url() . 'api/' . $function_name . '/' . ($i_page - 1) . '/' . $ilimit;
				}
			}
			$start = $curr_offset ? $curr_offset : $this->page_offset;

			$con['start'] = $start;

			$con['limit'] = $limit;
		}
		$con['pk'] = $table.'.'.$pk;
        $con[ 'joins' ] = $joins;
		$con[ 'distinct' ] = $distinct;
        $con[ 'group' ] = $group;
        $con[ 'in_cond' ] = $in_cond;
        if(!empty($notin_cond)){
        $con[ 'notin_cond' ] = $notin_cond;
        }
		$data = $this->common->get_rows($table, $table_prefix, $columns, $con);
		if ($id == '' && $return_type === false) {
			$response['next'] = '';
			if ($num_pages >= ($i_page + 1) && $i_page) {
				$response['next'] = base_url() . 'api/' . $function_name . '/' . ($i_page + 1) . '/' . $ilimit;
			}
			$response['total'] = $count;
			$response['pages'] = $num_pages;
			$response['page'] = $page;
			$response['limit'] = $limit;
			$response['base_url'] = base_url() . 'api/' . $function_name . '/';
			$response['sort_by'] = $sort_by ? $sort_by : $con['pk'];
			$response['sort_order'] = $sort_order ? $sort_order : 'desc';
			$response['keywords'] = ($keywords) ? $keywords : '';
		}
		$response['data'] = $data;
			// Check if the user data exists
			if (!empty($data)) {
				// Set the response and exit
				//OK (200) being the HTTP response code
				//$this->response($response, REST_Controller::HTTP_OK);
				return $this->response_successdata($response,$returnresponse);
			} else {
				// Set the response and exit
				//NOT_FOUND (404) being the HTTP response code
			    if(!$hideempty){
				return $this->response_error_message('error',$message);
			    }
			}
		
	}


	/**
	 * common function for update table
	 *
	 * @param string $table current table name
	 * @param string $tablePrefix current table prefix
	 * @param string $functionName current function name
	 * @param integer $page page number
	 * @param integer $limit limit to show the list
	 * @param array $columns column name of the table
	 * @param string $pk primary key column name of the table
	 * @param array $joins array of the joins
	 * @param array $cond conditions
	 * @param string $returnType single/something
	 * @return json response of list/detail by page & limit
	 * @author
	 */

	public function _update_common_table($data = array())
	{
		$page = false;
		$limit = false;
		$id = false;
		$columns = false;
		$pk = false;
		$joins =false;
		$id_col = false;
		$action = false;
		$cond = false;
		$return_type = false;
		$returnresponse = false;
		$in_cond =false;
		$notin_cond =false;
		$where = array();
		$message ="no data found";
		$success_message ='';
		if(empty($data)) {
			return false;	
		}
		extract($data);

		if(!$table) {
			return false;
		}
		if ( !$columns ) {
            $columns = 'name, description, slug, created_at,modified_at,status';
            //$columns = $tablePrefix.'name, '.$tablePrefix.'description, '.$tablePrefix.'slug, '.$tablePrefix.'created_at,'.$tablePrefix.'modified_at, '.$tablePrefix.'status';
        }
        $countcon = array();
        if(!empty($in_cond)){
            $countcon['in_cond']=$in_cond;
        }
        if(!empty($notin_cond)){
            $countcon['notin_cond']=$notin_cond;
        }
        $data = array ();
        $where[$id_col ] =  $id;
        $colum = explode ( ',' , $columns );
        foreach ($colum as $key => $value) {
        	$value = trim ( $value );        	
	            if($value === 'status')
				{
					$status=(string)$this->put ( $value );
					$data[$value ] = $status;
				}

				else
				{
					if (!empty( $this->put ( $value )))
					{
							$data[$value ] = $this->put ( $value );
					}
				}
        	
        }
        $update = $this->common->common_update ( $table , $table_prefix , $data , $where,$countcon );
        if ( $update ) {
        	$message = array(
                'status' => 'success',
                'message' => $success_message
            );

             return $this->response_successdata($message,$returnresponse);
        }
        else {
             $this->response_error_message('error',ERROR_COMMON);
        }

	}

	/**
	 * common function for delete table
	 *
	 * @param string $table current table name
	 * @param string $tablePrefix current table prefix
	 * @param string $functionName current function name
	 * @param integer $page page number
	 * @param integer $limit limit to show the list
	 * @param array $columns column name of the table
	 * @param string $pk primary key column name of the table
	 * @param array $joins array of the joins
	 * @param array $cond conditions
	 * @param string $returnType single/something
	 * @return json response of list/detail by page & limit
	 * @author
	 */

	public function _delete_common_table($data = array())
	{
		$id = false;
		$columns = false;
		$pk = false;
		$joins =false;
		$id_col = false;
		$action = false;
		$cond = false;
		$return_type = false;
		$returnresponse = false;
		$message ="no data found";
		$table_prefix='';
		$success_message = '';
		$where = array();
		if(empty($data)) {
			return false;	
		}
		extract($data);

		if(!$table) {
			return false;
		}

		if ( strpos ( $id , ',' ) !== false ) {
            $id = explode ( ',' , $id );
        }
        else {
            $id = array ( $id );
        }
        $where[$id_col] = $id;
        $delete = $this->common->common_delete ( $table , $where );
        if ( $delete ) {
        	$message = array(
                'status' => 'success',
                'message' => $success_message
            );
            return $this->response_successdata($message,$returnresponse);
        }
        else {
            $this->response_error_message('error',ERROR_COMMON);
        }
	}
	

	/**
	 * common function for create table
	 *
	 * @param string $table current table name
	 * @param string $tablePrefix current table prefix
	 * @param string $functionName current function name
	 * @param integer $page page number
	 * @param integer $limit limit to show the list
	 * @param array $columns column name of the table
	 * @param string $pk primary key column name of the table
	 * @param array $joins array of the joins
	 * @param array $cond conditions
	 * @param string $returnType single/something
	 * @return json response of list/detail by page & limit
	 * @author
	 */

	public function _create_common_table($data = array())
	{
		$page = false;
		$limit = false;
		$id = false;
		$columns = false;
		$pk = false;
		$joins =false;
		$id_col = false;
		$action = false;
		$cond = false;
		$return_type = false;
		$returnresponse = false;
		$message ="no data found";
		$success_message ='';
		if(empty($data)) {
			return false;	
		}
		extract($data);

		if(!$table) {
			return false;
		}
		if ( !$columns ) {
            $columns = 'name, description, slug, created_at,modified_at,status';
            //$columns = $tablePrefix.'name, '.$tablePrefix.'description, '.$tablePrefix.'slug, '.$tablePrefix.'created_at,'.$tablePrefix.'modified_at, '.$tablePrefix.'status';
        }
        $data = array ();
        
        $colum = explode ( ',' , $columns );
        foreach ($colum as $key => $value) {
            $value = trim ( $value );
           				if($value === 'status')
						{
							$status=(string)$this->post ( $value );
							$data[$value ] = $status;
						}
						else
						{
							if ( !empty( $this->post ( $value ) ) )
							{
								$data[$value ] = $this->post ( $value );
							}
						}
        }
        $insert = $this->common->common_insert ( $table , $table_prefix , $data );
        if ( $insert ) {
        	$message = array(
                'status' => 'success',
                'message' => $success_message,
        	    'lastinserted'=>$insert
            );

             return $this->response_successdata($message,$returnresponse);
        }
        else {
             $this->response_error_message('error',ERROR_COMMON);
        }

	}
  	

  	/**
	 * common function for success message
	 *
	 * @param string $response send message
	 * @param string $returnresponse return response or print response
	 * @author
	 */

    public function response_successdata($message,$returnresponse)
	{
		if($returnresponse)
		{
			return $message;
		}
		else
		{
			$this->response($message, API_Controller::HTTP_OK);
		}
	}


	/**
	 * common function for error message
	 *
	 * @param string $status send status success or failure
	 * @param string $message message send
	 * @author
	 */


	public function response_error_message($status,$message)
	{
		$this->response(
				[
					'status' => $status,
					'message' => $message,
				], API_Controller::HTTP_OK
			);
	}
}