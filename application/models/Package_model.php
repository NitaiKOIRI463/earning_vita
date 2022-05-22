<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');
   class Package_model extends CI_Model
   {
       public function __construct() 
      {
           parent::__construct();
           
      }

      public function verify_package_id_m($package_id)
      {
            $result = $this->db->select('count(p.id) as total')->from('tbl_plan_master p')->where(['p.package_id'=>$package_id,'p.status'=>1])->get()->result_array();
            if(!empty($result))
            {
                return $result[0]['total'];
            }else
            {
                return 0;
            }
      }

      public function verify_member_id_m($member_id)
      {
            $result = $this->db->select('count(p.id) as total')->from('tbl_registration_master p')->where(['p.member_id'=>$member_id,'p.status'=>1])->get()->result_array();
            if(!empty($result))
            {
                return $result[0]['total'];
            }else
            {
                return 0;
            }
      }

      public function get_package_details_m($package_id)
      {
            return  $this->db->select('package_id,package_name,package_amount,profit_perc,roi_perc,roi_amount,total_return,days,sponsor_income_perc,matching_perc,capping,effected_from,effected_to')->from('tbl_plan_master')->where(['package_id'=>$package_id,'status'=>1])->get()->result_array();
            
      }

      public function buyPackage_m($data)
      {
            return $this->db->insert('tbl_users_package_details',$data);
            
      }

      public function verify_package_request_m($member_id)
      {
            $result = $this->db->select('count(p.id) as total')->from('tbl_users_package_details p')->where(['p.member_id'=>$member_id,'p.status'=>1,'p.current_status'=>'pending'])->get()->result_array();
            if(!empty($result))
            {
                return $result[0]['total'];
            }else
            {
                return 0;
            }
      }

      public function getbitcoinaddress_m()
      {
            $result = $this->db->select('value')->from('tbl_setup p')->where(['key'=>'bitcoin_address','status'=>1])->get()->result_array();
            return $result[0]['value'];
      }
      
      public function getPandingPackage_m($member_id)
      {
            return $this->db->select('id,current_status,c_date')->from('tbl_users_package_details')->where(['member_id'=>$member_id,'status'=>1,'current_status'=>'pending'])->get()->result_array();
            
      }


      public function update_package_purchase_m($data,$where)
      {
        return $this->db->update('tbl_users_package_details',$data,$where);
      }


   }
?>