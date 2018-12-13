<?php defined('BASEPATH') OR exit('No direct script access allowed');


class User_model extends My_Model
{


    public function users(){
        $this->apply_filters();
        $this->response = $this->db->get($this->tables['users']);
        return $this;
    }

    public function user($id){
        $this->limit(1);
        $this->order_by($this->tables['users'].'.id', 'desc');
        $this->where($this->tables['users'].'.id',$id);
        $this->users();
        return $this;
    }

    public function search($query){
        $this->like('nombre',$query);
        $this->like('paterno', $query);
        $this->like('materno', $query);
        $this->like('email', $query);
        $this->like('usuario', $query);
    }



    public function login($username, $password)
    {
        if(empty($username) || empty($password))
        {
            $this->set_error('Inicio de sesion fallado');
            return FALSE;
        }

        $query = $this->db->select('usuario, email, id, clave, fecha_creacion')
                          ->where('usuario', $username)
                          ->limit(1)
                          ->order_by('id','desc')
                          ->get($this->tables['users']);

        if($query->num_rows()){
            $user = $query->row();

            $password = $this->verify_password_db($user->id, $password);

            if($password === TRUE)
            {
                $this->set_message('Inicio de sesion exitoso');
                return $user;
            }
        }

        $this->set_error('Inicion de sesion fallido');
        return FALSE;
    }


    private function verify_password_db($id, $password){
        if(empty($id) || empty($password))
        {
            return FALSE;
        }

        $query = $this->db->select('clave')
                          ->where('id',$id)
                          ->limit(1)
                          ->order_by('id','desc')
                          ->get('usuarios');

        $hash_password_db = $query->row();

        if($query->num_rows() !== 1)
        {
            //No encontro nungun resultado
            return FALSE;
        }

        if(password_verify($password, $hash_password_db->clave))
        {
            return TRUE;
        }else
        {
            return FALSE;
        }
    }

    private function hash_password($password){

    }
}