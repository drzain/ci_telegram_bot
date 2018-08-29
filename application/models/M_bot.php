<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Bot extends CI_Model {

	public function register($id,$username,$email)
	{
		$query = $this->db->query("
			INSERT INTO tblUsers (
				userid,
				username,
				email
			)VALUES(
				'$id',
				'$username',
				'$email'
			);
		");

		return $query;
	}

}

/* End of file modelName.php */
/* Location: ./application/models/modelName.php */