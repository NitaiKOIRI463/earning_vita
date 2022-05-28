<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');

   class Total_reg_Model extends CI_Model
   {
        public function __construct() 
        {
           parent::__construct();
           
        }
        public function getTotalLeft(){
         $query = $this->db->query("SELECT count(member_id) AS Total_Left FROM tbl_parent_level where side = 'L' and parent_id = 'EARNINGVISTA1000' and status = 1");
            return $query->result();
         }

         public function getTotalRight(){
         $query = $this->db->query("SELECT count(member_id) AS Total_Right FROM tbl_parent_level where side = 'R' and parent_id = 'EARNINGVISTA1000' and status = 1");
            return $query->result();
         }

         public function getTotal(){
         $query = $this->db->query("SELECT count(member_id) AS Total FROM tbl_parent_level where status = 1 and parent_id = 'EARNINGVISTA1000'");
            return $query->result();
         }

         public function Left_bussiness($member_id){
          $query =$this->db->query("SELECT IF(sum(Effect_Amount) IS NULL,0,sum(Effect_Amount)) as total FROM `tbl_binary_income` where Member_id = 'EARNINGVISTA1000' and Effect_Side ='L' and laps_status = 1");
          return $query->result();
           
         }

         public function Right_bussiness($member_id){
          $query =$this->db->query("SELECT IF(sum(Effect_Amount) IS NULL,0,sum(Effect_Amount)) as total FROM `tbl_binary_income` where Member_id = 'EARNINGVISTA1000' and Effect_Side ='R' and laps_status = 1");
          return $query->result();
           
         }
         public function Total_bussiness($member_id)
         {
            $query =$this->db->query("SELECT IF(sum(Effect_Amount) IS NULL,0,sum(Effect_Amount)) as total FROM `tbl_binary_income` where Member_id = 'EARNINGVISTA1000' and laps_status = 1");
          return $query->result();
       }
}
            
    
