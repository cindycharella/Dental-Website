<?php

class Admin_model extends CI_model{

	public function getAll(){
		return $this->db->get('admin')->result_array();
	}

	public function getadmin($username){
		$this->db->where('username',$username);
		return $this->db->get('admin')->result();
	}

	public function validate($username_or_email, $password){
		$valid = FALSE;

		foreach ($this->getAll() as $admin) {
			if((strcmp($admin['username'], $username_or_email) == 0 || strcmp($admin['email'], $username_or_email) == 0) && strcmp($admin['password'], $password) == 0){
				$valid = TRUE;
				break;
			}
		}

		return $valid;
	}
<<<<<<< HEAD

	public function tampil_data_pelanggan(){
        return $this->db->get('pelanggan');
	}

	public function tampil_data_pembayaran(){
        return $this->db->get('pembayaran');
	}

	public function tampil_data_service(){
		return $this->db->get('service');
	}

	public function update_data($where,$data,$table){
		$this->db->where($where);
		$this->db->update($table,$data);
	}

	public function input_data($data,$table){
		$this->db->insert($table,$data);
	}

	public function delete_all_service(){
		$result=$this->db->query("DELETE FROM 'service'");
        return $result;
	}
	
=======
>>>>>>> 57376f34ca8633d86477a66585d42ffc8e28a258
}

?>