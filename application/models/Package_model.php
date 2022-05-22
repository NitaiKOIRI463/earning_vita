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
            return $this->db->select('id,current_status,c_date,required_btc,payment_type')->from('tbl_users_package_details')->where(['member_id'=>$member_id,'status'=>1,'current_status'=>'pending'])->get()->result_array();
            
      }


      public function update_package_purchase_m($data,$where)
      {
        return $this->db->update('tbl_users_package_details',$data,$where);
      }


      public function getMyPackage_m($member_id)
      {
            return $this->db->select('id,package_id,package_amount,total_return,days,sponsor_income_perc,matching_perc,capping,activation_date,expiry_date,activated_by,current_status,c_date,required_btc,payment_type,remaining_amount,release_amount')->from('tbl_users_package_details')->where(['member_id'=>$member_id,'status'=>1,'current_status!='=>'expired'])->get()->result_array();
            
      }

      public function getRequestPackage_m($member_id,$current_status)
      {
        if($member_id!="")
                $this->db->where('member_id',$member_id);
        if($current_status!="")
                $this->db->where('current_status',$current_status);
        $base_url = base_url().'all-uploaded-img/screenshot/';
            return $this->db->select("member_id,required_btc,id,package_id,package_amount,total_return,days,sponsor_income_perc,matching_perc,capping,activation_date,expiry_date,activated_by,current_status,c_date,required_btc,payment_type,transaction_no,CONCAT('$base_url',screenshot) as screenshot")->from('tbl_users_package_details')->where(['status'=>1,'current_status!='=>'expired'])->get()->result_array();
            
      }

      public function getRequestPackageDetails_m($req_id)
      {
        if($req_id!="")
                $this->db->where('p.id',$req_id);
        return $this->db->select("p.member_id,p.package_id,p.package_amount,r.sponsor_id,p.current_status")
            ->from('tbl_users_package_details p')
            ->join('tbl_registration_master r','r.member_id=p.member_id','left')
            ->where(['p.status'=>1,'p.current_status'=>'requested'])->get()->result_array();
            
      }

      public function getLetestPackageDetails_m($member_id)
      {
        if($member_id!="")
                $this->db->where('p.member_id',$member_id);
        return $this->db->select("p.member_id,p.package_id,p.sponsor_income_perc,p.matching_perc,roi_amount,days,total_return,n.name")
            ->from('tbl_users_package_details p')
            ->join('tbl_registration_master n','n.member_id=p.member_id','left')
            ->where(['p.status'=>1,'p.current_status'=>'activate'])->order_by('p.activation_date','desc')->limit(1)->get()->result_array();
            
      }

      public function getsevenLevelparentId($member_id)
        {
            $data = $this->db->select('parent_id,name,member_id')->from('tbl_registration_master')->where(['member_id'=>$member_id,'role_type'=>2])->limit(1)->order_by('id','desc')->get()->result_array();
            // echo $this->db->last_query();die;
            if(!empty($data))
            {
                return $data[0];
            }else
            {
                return 200;
            }
        }


   }
?>