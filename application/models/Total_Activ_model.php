<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');

   class Total_Activ_model extends CI_Model
   {
        public function __construct() 
        {
           parent::__construct();
           
        }
        public function Left_Activation()
        {
         $query = $this->db->query("SELECT count(member_id) AS Total FROM tbl_registration_master where side = 'L' and activation_status = 1 and role_type = 1");
            return $query->result();
        }
        public function Right_Activation()
        {
         $query = $this->db->query("SELECT count(member_id) AS Total FROM tbl_registration_master where side = 'R' and activation_status = 1 and role_type = 1");
            return $query->result();
        }
        public function Total_Activation()
        {
         $query = $this->db->query("SELECT count(member_id) AS Total FROM tbl_registration_master where activation_status = 1 and role_type = 1");
            return $query->result();
        }
        public function total_sponsor()
        {
         $query = $this->db->query("SELECT sum(Sponsor_income) AS Total FROM tbl_sponsor_income where status = 1");
            return $query->result();
        }
        public function total_roi()
        {
         $query = $this->db->query("SELECT sum(roi_amount) AS Total FROM tbl_plan_master where status = 1");
            return $query->result();
        }
   }