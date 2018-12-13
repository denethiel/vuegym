<?php defined('BASEPATH') OR exit('No direct script access allowed');

class My_Model extends CI_Model
{
    protected $messages;

    protected $errors;

    public $tables = array();

    public $join = array();

    public function __construct()
    {
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