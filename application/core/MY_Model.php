<?php defined('BASEPATH') OR exit('No direct script access allowed');

class My_Model extends CI_Model
{
    /* 
    * Success Messages
     */
    protected $messages;

    /* 
    * Error Messages
     */
    protected $errors;

    /* 
    DB Tables Collections
    */
    public $tables = array();

    /* 
    Relations Keys Collections
    */
    public $join = array();

    /*
    DB Pointer 
     */
    protected $db;

    /* 
    DB Response
    */
    protected $response = NULL;

    // Table Where
    public $_where = array();
    
    //Table select
    public $_select = array();

    //Table like
    public $_like = array();

    //Table Limit
    public $_limit = NULL;

    //Table Limit
    public $_offset = NULL;

    //Table Order By
    public $_order_by = NULL;
    
    //Tanble Order aka DESC ASC RANDOM
    public $_order = NULL;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->db = $CI->db;

        $this->messages = array();

        $this->errors = array();

        $this->tables = array(
            'members' => 'socios',
            'users' => 'usuarios',
            'users_groups' => 'usuarios_grupos',
            'users_plans' => 'usuarios_planes',
            'groups' => 'grupos',
            'reading' => 'mediciones',
            'plans' => 'planes',
            'routines' => 'rutinas',
            'users_routines' => 'rutinas_usuario',
            'users_metrics' => 'medida',
            'assists' => 'asistencias',
            'images' => 'imagenes',
            'page_images' => 'imagenes_pagina'
        );

        $this->join = array(
            'users' => 'usuario_id',
            'groups' => 'grupo_id',
            'plans' => 'plan_id',
            'rutines' => 'rutinas_id',
            'users_plans' => 'usuarios_planes_id',
            'metrics' => 'mediciones_id'
        );

    }

    /* SHARED FUNCTIONS */

    protected function _filter_data($table, $data)
    {
        $filtered_data = array();
        $columns = $this->db->list_fields($table);
        //var_dump($columns);

        if(is_array($data))
        {
            foreach($columns as $column)
            {
                if(array_key_exists($column, $data))
                    $filtered_data[$column] = $data[$column];
            }
        }

        return $filtered_data;

    }
    public function add_to_group($group_id, $user_id )
    {
        return $this->db->insert($this->tables['users_groups'],
                                 array(
                                    $this->join['users'] => (float) $user_id,
                                    $this->join['groups'] => (float) $group_id
                                ));
    }

    public function remove_from_group($group_id = FALSE, $user_id = FALSE)
    {
        if(empty($user_id))
        {
            return FALSE;
        }

        if(!empty($group_id))
        {
            $this->db->delete($this->tables['users_groups'],
                                array($this->join['groups'] => (float) $user_id,
                                      $this->join['users'] => (float) $group_id
                              ));
            $return = TRUE;
        }else
        {
            $return = $this->db->delete($this->tables['users_groups'], array(
                                        $this->join['users'] => (float) $user_id
            ));
        }

        return $return;
    }

    public function hash_password($password)
    {
        return password_hash($password,PASSWORD_DEFAULT);
    }

    /* END SHARED FUNCTIONS */

    public function apply_filters(){
        if(isset($this->_select) && !empty($this->_select))
		{
			foreach($this->_select as $select)
			{
				$this->db->select($select);
			}

			$this->_select = array();
		}

		if(isset($this->_where) && !empty($this->_where))
		{
			foreach($this->_where as $where)
			{
				$this->db->where($where);
			}

			$this->_where = array();
		}

		if(isset($this->_like) && !empty($this->_like))
		{
			foreach ($this->_like as $like) {
				$this->db->or_like($like['like'], $like['value'], $like['position']);
			}

			$this->_like = array();
		}

		if(isset($this->_limit) && isset($this->_offset))
		{
			$this->db->limit($this->_limit, $this->_offset);
			$this->_limit = NULL;
			$this->_offset = NULL;
		}
		else if(isset($this->_limit))
		{
			$this->db->limit($this->_limit);
			$this->_limit = NULL;
		}

		if(isset($this->_order_by) && isset($this->_order))
		{
			$this->db->order_by($this->_order_by, $this->_order);

			$this->_order_by = NULL;
			$this->_order = NULL;
		}
    }

    public function limit($limit)
    {
        $this->_limit = $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->_offset = $offset;
        return $this;
    }


    public function where($where, $value = NULL)
    {
        if (!is_array($where))
        {
            $where = array($where => $value);
        }

        array_push($this->_where, $where);

        return $this;
    }


    public function like($like, $value = NULL, $position = 'both')
    {
        array_push($this->_like, array(
            'like'    => $like,
            'value'   => $value,
            'position'=> $position
        ));

        return $this;
    }

    public function select($select)
    {
        $this->_select[] = $select;
        return $this;
    }

    public function order_by($by, $order='desc')
    {
        $this->_order_by = $by;
        $this->_order    = $order;

        return $this;
    }

    public function row()
    {
        $row = $this->response->row();
        return $row;
    }

    public function row_array()
    {
        $row = $this->response->row_array();

        return $row;
    }

    public function result()
    {
        $result = $this->response->result();

        return $result;
    }

    public function result_array()
    {
        $result = $this->response->result_array();

        return $result;
    }

    public function num_rows()
    {
        $result = $this->response->num_rows();

        return $result;
    }

    public function set_message($message)
    {
        $this->messages[] = $message;
        return $message;
    }

    public function messages()
    {
        if(!empty($this->messages))
        {
            $_output = '<div class="alert alert-success" role="alert"><ul>';
            foreach($this->messages as $message)
            {
                $_output .= '<li>' . $message . '</li>';
            }
            $_output .= '</ul></div>';
            return $_output;
        }
        else
        {
            return '';
        }        
        

    }

    public function clear_messages()
    {
        $this->messages = array();
        return TRUE;
    }
    
    public function set_error($error)
    {
        $this->errors[] = $error;
        return $error;
    }

    public function errors()
    {
        if(!empty($this->errors))
        {
            $_output = '<div class="alert alert-danger" role="alert"><ul>';
            foreach($this->errors as $error)
            {
                $_output .= '<li>' . $error . '</li>';
            }
            $_output .= '</ul></div>';
            return $_output;
        }else
        {
            return '';
        }
    }

    public function clear_errors(){
        $this->errors = array();
        return TRUE;
    }
}