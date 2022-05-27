<?php

class T_Pin_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        
    }
     public function ResetPin($member_id)
    {
        $this->db->select('transaction_pin');
        $this->db->from('tbl_registration_master'); 
        $this->db->where(['member_id'=>$member_id,'status'=>1]); 
        $query=$this->db->get();
        return $query->result_array();
       
    }
    public function updatePin($member_id,$transaction_pin)
    {
            return $this->db->update('tbl_registration_master', ['transaction_pin'=>$transaction_pin],['member_id'=>$member_id]);
        
    }
}
?>  