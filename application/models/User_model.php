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

    public function create_user($insert_data, $group_id){
        
        $password = $insert_data['clave'];
        $password2 = $this->hash_password('usuario');
        $insert_data['clave'] = password_hash($password,PASSWORD_BCRYPT);
        var_dump($insert_data);
        die();
        $this->db->insert($this->tables['users'], $insert_data);

        $user_id = $this->db->insert_id();

        $this->add_to_group($group_id, $user_id);

        $this->set_message('Usuario ' . $insert_data['usuario'] . ' agregado con exito');

        return $user_id;
    }

    public function update_user($id, $data)
    {
        $user = $this->user($id)->row();
        $this->db->trans_begin();

        if(array_key_exists('email', $data) && $this->email_check($data['email']) AND $user->email !== $data['email'])
        {
            $this->db->trans_rollback();
            $this->set_error('Email en uso o invalido');
            $this->set_error('No se ha podido actualizar la informacion de la cuenta');
            return FALSE;

        }

        if(array_key_exists('clave', $data))
        {
            if(!empty($data['clave']))
            {
                $data['clave'] = password_hash($data['clave'],PASSWORD_BCRYPT);
            }else
            {
                unset($data['clave']);
            }
        }

        $this->db->update($this->tables['users'], $data, array('id' => $user->id));

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->set_error('No se ha podido actualizar la informacion de la cuenta');
            return FALSE;

        }

        $this->db->trans_commit();
        $this->set_message('Informacion de la cuenta actualizada con éxito');

        return TRUE;
    }

    public function get_user_group($id)
    {
        return $this->db->select($this->tables['groups'].'.id, ' . $this->tables['groups'].'.nombre, '.$this->tables['groups'].'.descripcion')
            ->where($this->tables['users_groups'].'.'.$this->join['users'],$id)
            ->join($this->tables['groups'], $this->tables['users_groups'].'.'.$this->join['groups'].'='.$this->tables['groups'].'.id')
            ->get($this->tables['users_groups']);
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