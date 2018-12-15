<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Users extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model','UserModel');
    }

    /* 
    CREATE USERS
    PARAMS:::
    nombre:string
    paterno:string
    materno:string
    email:string
    clave:string
    usuario:string
    id_grupo:string
    */
    public function create_post()
    {
        header("Access-Control-Allow-Origin: *");


        $this->load->library('Authorization_Token');
        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
        {
        $_POST = $this->security->xss_clean($_POST);

        $this->form_validation->set_rules('nombre','Nombre','trim|required');
        $this->form_validation->set_rules('paterno','Apellido Paterno','trim|required');
        $this->form_validation->set_rules('materno','Apellido Materno','trim|required');
        $this->form_validation->set_rules('email','Correo Electronico','trim|required|valid_email|is_unique[usuarios.email]');
        $this->form_validation->set_rules('clave','Contraseña','required|min_length[8]');
        $this->form_validation->set_rules('usuario','Usuario','required|is_unique[usuarios.usuario]');
        $this->form_validation->set_rules('id_grupo','Grupo','required');

        if($this->form_validation->run() === FALSE){
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );

            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }else{
            $insert_data = [
                'nombre' => $this->input->post('nombre', TRUE),
                'paterno' => $this->input->post('paterno', TRUE),
                'materno' => $this->input->post('materno', TRUE),
                'email' => $this->input->post('email', TRUE),
                'clave' => $this->input->post('clave', TRUE),
                'usuario' => $this->input->post('usuario', TRUE),
                'fecha_creacion' => time(),
                'fecha_actualizacion' => time()
            ];
            $output = $this->UserModel->create_user($insert_data, $this->input->post('id_grupo'));

            if($output > 0 AND !empty($output))
            {
                $message = [
                    'status' => true, 
                    'message' => $this->UserModel->messages()
                ];

                $this->response($message, REST_Controller::HTTP_OK);
            }else
            {
                $message = [
                    'status' => FALSE,
                    'message' => $this->UserModel->error()
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }

            
        }

        
        

        }else{
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
        

    }

    public function update_put($id){
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
        {
            $group = $this->UserModel->get_user_group($id)->row();

            $this->form_validation->set_rules('nombre','Nombre','trim|required');
            $this->form_validation->set_rules('paterno','Apellido Paterno','trim|required');
            $this->form_validation->set_rules('materno','Apellido Materno','trim|required');
            $this->form_validation->set_rules('email','Correo Electronico','trim|required|valid_email');
            $this->form_validation->set_rules('rol','Seleccione el Rol','required');

            if($this->input->post('clave'))
            {
                $this->form_validation->set_rules('clave','Contraseña','required|min_length[8]|matches[clave_confirmacion]');
                $this->form_validation->set_rules('clave_confirmacion','Confirmar Contraseña','required');
            }

            if($this->form_validation->run() === TRUE){
                $update_data = [
                    'nombre' => $this->input->post('nombre'),
                    'paterno' => $this->input->post('paterno'),
                    'materno' => $this->input->post('materno'),
                    'email' => $this->input->post('email'),
                ];

                if($this->input->post('clave'))
                {
                    $update_data['clave'] = $this->input->post('clave');
                }

                $updateGroup = $this->input->post('rol');

                if($group->id != $updateGroup)
                {
                    $this->UserModel->remove_from_group('', $id);
                    $this->UserModel->add_to_group($updateGroup, $id);
                }

                if($this->UserModel->update_user($id, $update_data)){
                    $this->response(['status' => TRUE, 'message' => $this->UserModel->message()], REST_Controller::HTTP_OK);
                    
                }else{
                    $message = [
                        'status' => FALSE,
                        'message' => $this->UserModel->error()
                    ];
                    $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                }

                
            }

            $this->response($query, REST_Controller::HTTP_OK);
        }else
        {
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function users_get($id = null){
        // $result = [
        //     'hola' => 'Hola a todos'
        // ];
        // $this->load->database();
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
        {
            $search = $this->input->get('s');
            if($id != NULL AND $search != null){
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            }
            if($id != NULL){
                $query = $this->UserModel->user($id)->result();
            }else{
                if($search != null){
                    $this->UserModel->search($search);
                }
                $query = $this->UserModel->users()->result();
                
            }
            
            

            $this->response($query, REST_Controller::HTTP_OK);
        }else
        {
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
        

    }

    public function users_post(){
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
        {
            $query = [
                'method' => 'POST'
            ];

            $this->response($query, REST_Controller::HTTP_OK);
        }else
        {
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function users_put($id){
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        /**
         * User Token Validation
         */

        $search = $this->input->get('s');
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
        {
            $query = [
                'id' => $id,
                'search' => $search,
                'method' => 'PUT'
            ];

            $this->response($query, REST_Controller::HTTP_OK);
        }else
        {
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function login_post(){
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        $this->form_validation->set_rules('usuario','Usuario','required');
        $this->form_validation->set_rules('password','Contraseña','required');

        if($this->form_validation->run() === FALSE){
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );

            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {
            $output = $this->UserModel->login($this->input->post('usuario'),$this->input->post('password'));
            if(!empty($output) AND $output != FALSE)
            {
                $this->load->library('Authorization_Token');

                $token_data['id'] = $output->id;
                $token_data['usuario'] = $output->usuario;
                $token_data['email'] = $output->email;
                $token_data['time'] = time();

                $user_token = $this->authorization_token->generateToken($token_data);

                $return_data = [
                    'user_id' => $output->id,
                    'usuario' => $output->usuario,
                    'email' => $output->email,
                    'token' => $user_token,
                ];

                $message = [
                    'status' => true,
                    'data' => $return_data,
                    'message' => "User login successful" //message data
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }else
            {
                //Login Error
                $message = [
                    'status' => FALSE,
                    'message' => "Invalid Username or Password"
                ];

                $this->response($message, REST_Controller::HTTP_NOT_FOUND);

            }
        }
    }

}