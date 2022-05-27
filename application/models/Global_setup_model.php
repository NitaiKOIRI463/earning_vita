<?php 
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Global_setup_model extends CI_Model
	{
		public function __construct()
		{
			parent:: __construct();
		}

		public function getsetUpList($id)
		{
			if($id != '')
				$this->db->where('id',$id);
			return $this->db->select('*')->from('tbl_setup')->where(['status'=>1])->get()->result_array();
		}

		public function updateSetUp($id,$array1)
		{
			return $this->db->update('tbl_setup',$array1,['id'=>$id]);
		}

		public function insertSetUp($array2)
		{
			return $this->db->insert('tbl_setup',$array2);
		}
	}
?>