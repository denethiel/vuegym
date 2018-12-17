<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Members extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('member_model','MemberModel');
    }

    public function members_get(){
        //AUTHORIZATION
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
            $limit = $this->input->get('limit');
            $offset = $this->input->get('offset');
            $select = [
                'usuarios.id',
                'usuarios.nombre',
                'usuarios.paterno',
                'usuarios.materno',
                'socios.edad',
                'socios.peso',
                'socios.estatura',
                'socios.genero'
            ];
            $this->MemberModel->select($select);
            if($id != NULL AND $search != null){
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            }
            if($id != NULL){
                $query = $this->MemberModel->member($id)->result();
            }else{
                if($search != null){
                    $this->MemberModel->search($search);
                }
                if($limit != NULL AND $offset != NULL){
                    $this->MemberModell->limit($limit);
                    $this->MemberModel->offset($offset);
                }
                $query = $this->MemberModel->members()->result();
                
            }
            
            

            $this->response($query, REST_Controller::HTTP_OK);
        }else
        {
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
    }


    public function create_post(){
        header("Access-Control-Allow-Origin: *");


        $this->load->library('Authorization_Token');
        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
        {
            $this->form_validation->set_rules('nombre','Nombre','trim|required');
            $this->form_validation->set_rules('paterno','Apellido Paterno','trim|required');
            $this->form_validation->set_rules('materno','Apellido Materno','trim|required');
            $this->form_validation->set_rules('email','Correo Electronico','trim|required|valid_email|is_unique[usuarios.email]');
            $this->form_validation->set_rules('edad','Edad','trim|required|numeric');
            $this->form_validation->set_rules('genero','Genero','trim|required');
            $this->form_validation->set_rules('peso','Peso','trim|required|numeric');
            $this->form_validation->set_rules('estatura','Estatura','trim|required|numeric');

            $this->form_validation->set_rules('mme','Masa Muscular Esquelética','trim|required|numeric');
            $this->form_validation->set_rules('mgc','Masa Grasa Corporal','trim|required|numeric');
            $this->form_validation->set_rules('act','Agua Corporal Total','trim|required|numeric');
            $this->form_validation->set_rules('imc','Índice de Masa Corporal','trim|required|numeric');
            $this->form_validation->set_rules('pmc','Porcentaje de Masa Corporal','trim|required|numeric');
            $this->form_validation->set_rules('rcc','Relación Cintura-Cadera','trim|required|numeric');
            $this->form_validation->set_rules('mb','Metabolismo Basal','trim|required|numeric');
            $this->form_validation->set_rules('password','Contraseña','required');
            $this->form_validation->set_rules('usuario','Usuario','trim|required');

            if($this->form_validation->run() === TRUE){
                $password = $this->input->post('password');
                $user_info = array(
                    'nombre' => $this->input->post('nombre'),
                    'paterno' => $this->input->post('paterno'),
                    'materno' => $this->input->post('materno'),
                    'usuario' => $this->input->post('usuario'),
                    'email' => $this->input->post('email')
                );

                $members_info = array(
                    'edad' => $this->input->post('edad'),
                    'genero' => $this->input->post('genero'),
                    'estatura' => (float) $this->input->post('estatura'),
                    'peso' => (float) $this->input->post('peso')
                );

                $reading_member = array(
                    'mme' => (float) $this->input->post('mme'),
                    'mgc' => (float) $this->input->post('mgc'),
                    'act' => (float) $this->input->post('act'),
                    'imc' => (float) $this->input->post('imc'),
                    'pmc' => (float) $this->input->post('pmc'),
                    'rcc' => (float) $this->input->post('rcc'),
                    'mb' => (float) $this->input->post('mb'),
                );

                if($this->MemberModel->create($password, $user_info, $members_info, $reading_member)){
                    $this->response(['status' => true, 'message' => $this->MemberModel->messages()], REST_Controller::HTTP_OK);

                }else{
                    $this->response(['status' => false, 'message' => $this->MemberModel->errors()], REST_Controller::HTTP_NOT_FOUND);
                }
            }else{
                $message = array(
                    'status' => false,
                    'error' => $this->form_validation->error_array(),
                    'message' => validation_errors()
                );
            }
        }else{
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);

        }
    }
}