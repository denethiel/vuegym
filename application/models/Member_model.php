<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends My_Model
{   
    public function members()
    {
        $this->apply_filters();
        $this->db->from($this->tables['members']);
        $this->db->join($this->tables['users'], $this->tables['users'].'.id = ' . $this->tables['members'].'.'.$this->join['users']);
        $this->response = $this->db->get();
		return $this;
    }

    public function search($query)
	{
		$this->like($this->tables['users'].'.nombre',$query);
		$this->like($this->tables['users'].'.paterno',$query);
		$this->like($this->tables['users'].'.materno', $query);
		$this->like($this->tables['members'].'.edad', $query);
		$this->like($this->tables['members'].'.genero', $query);
		$this->like($this->tables['members'].'.peso', $query);
		$this->like($this->tables['members'].'.estatura', $query);
    }
    
    public function member($id)
	{
		//$id = isset($id) ? $id : $this->session->userdata('user_id');

		$this->limit(1);
		$this->order_by($this->tables['members'].'.id','desc');
		$this->where($this->tables['users'].'.id', $id);

		$this->members();

		return $this;
    }
    
    public function update($id, array $data)
	{
		$this->db->trans_begin();

		$data = $this->_filter_data($this->tables['members'], $data);

		$this->db->update($this->tables['members'], $data, array($this->join['users'] => $id));

		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

            $this->set_error('No se ha podido actualizar la información del socio');

            return FALSE;
		}

		$this->db->trans_commit();

        $this->set_message('Información del socio actualizada con éxito');

        return TRUE;
    }
    

    public function delete($id)
	{
		$this->db->trans_begin();

		$this->remove_from_group(NULL, $id);

		$this->db->delete($this->tables['users'], array('id' => $id));

		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
            $this->set_error('No se ha podido eliminar el socio');
            return FALSE;
		}

		$this->db->trans_commit();

        $this->set_message('Socio eliminado');

        return TRUE;

	}


}