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
         $query = $this->db->query("SELECT count(l.member_id) AS Total FROM tbl_parent_level l left join tbl_registration_master r on r.member_id = l.member_id where l.side = 'L' and l.parent_id = 'EARNINGVISTA1000' and l.status = 1 and r.activation_status = 1 ");
            return $query->result();
        }
        
        public function Right_Activation()
        {
         $query = $this->db->query("SELECT count(l.member_id) AS Total FROM tbl_parent_level l left join tbl_registration_master r on r.member_id = l.member_id where l.side = 'R' and l.parent_id = 'EARNINGVISTA1000' and l.status = 1 and r.activation_status = 1");
            return $query->result();
        }
        public function Total_Activation()
        {
         $query = $this->db->query("SELECT count(l.member_id) AS Total FROM tbl_parent_level l left join tbl_registration_master r on r.member_id = l.member_id where  l.parent_id = 'EARNINGVISTA1000' and l.status = 1 and r.activation_status = 1");
            return $query->result();
        }
        public function total_sponsor()
        {
         $query = $this->db->query("SELECT IF(sum(Sponsor_income) IS NULL,0,sum(Sponsor_income)) AS Total FROM tbl_sponsor_income where status = 1 and laps_status = 1");
            return $query->result();
        }
        public function total_roi()
        {
         $query = $this->db->query("SELECT IF(sum(roi_income) is NULL,0,sum(roi_income)) AS Total FROM tbl_daily_income_ledger where status = 1");
            return $query->result();
        }
   }