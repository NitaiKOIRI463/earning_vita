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
        return $this->db->select("p.id as reg_id,p.member_id,p.package_id,p.package_amount,r.sponsor_id,p.current_status,p.days,p.roi_perc,p.roi_amount,total_return,r.name")
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
            ->where(['p.status'=>1,'p.current_status'=>'active'])->order_by('p.activation_date','desc')->limit(1)->get()->result_array();
            
      }

      public function getsevenLevelparentId($member_id)
        {
            $data = $this->db->select('parent_id,name,member_id,side')->from('tbl_registration_master')->where(['member_id'=>$member_id,'role_type'=>2])->limit(1)->order_by('id','desc')->get()->result_array();
            // echo $this->db->last_query();die;
            if(!empty($data))
            {
                return $data[0];
            }else
            {
                return 200;
            }
        }

        public function userSideBussiness_m($member_id,$side)
          {
                $result = $this->db->select('fund as total')->from('tbl_users_business p')->where(['p.member_id'=>$member_id,'p.status'=>1,'p.side'=>$side])->get()->result_array();
                if(!empty($result))
                {
                    return $result[0]['total'];
                }else
                {
                    return 0;
                }
          }

      public function verify_member_side_business_m($member_id,$side)
      {
            $result = $this->db->select('count(p.id) as total')->from('tbl_users_business p')->where(['p.member_id'=>$member_id,'p.status'=>1,'p.side'=>$side])->get()->result_array();
            if(!empty($result))
            {
                return $result[0]['total'];
            }else
            {
                return 0;
            }
      }

    public function insert_member_business_m($data)
      {
        return $this->db->insert('tbl_users_business',$data);
      }


    public function getChildGenology_m($parent_id)
      {
            return $this->db->select("r.name,r.member_id,l.m_level,l.side,r.parent_id,r.side as p_side")->from('tbl_parent_level l')
            ->join('tbl_registration_master r','r.member_id=l.member_id','left')
            ->where(['l.status'=>1,'l.parent_id'=>$parent_id])->order_by('l.m_level','asc')->get()->result_array();
            
      }

    public function get_member_details_m($member_id)
      {
            return $this->db->select('u.name,u.member_id,p.current_status,p.matching_perc,p.package_amount')->from('tbl_users_package_details p')->join('tbl_registration_master u','u.member_id=p.member_id','left')->where(['p.member_id'=>$member_id,'p.status'=>1,'p.current_status' => 'active'])->order_by('p.activation_date','desc')->limit(1)->get()->result_array();
           
      }
    public function get7Levelparents($member_id)
    {
        $qry = $this->db->query("SELECT l.parent_id,l.side,l.m_level,r.name FROM tbl_parent_level l left join tbl_registration_master r on r.member_id = l.parent_id where l.member_id = '$member_id' and l.status = 1 and l.m_level <=7 and l.parent_id != 'EARNINGVISTA1000' order by l.m_level asc");
        return $qry->result_array();
    }

    

    public function getTwoDirectRefral($parent_id,$side)
    {
        $qry = $this->db->query("SELECT p.package_amount,r.sponsor_id,r.member_id FROM tbl_users_package_details p left join tbl_registration_master r on r.member_id = p.member_id where r.sponsor_id = '$parent_id' and p.status = 1 and p.current_status = 'active' and r.side = '$side'");
        return $qry->result_array();
    }

    public function getMemberBusiness($parent_id,$side)
    {
        $qry = $this->db->query("SELECT IF(sum(Effect_Amount) Is NULL,0,sum(Effect_Amount)) as total FROM `tbl_binary_income` where Member_id = '$parent_id' and Effect_Side = '$side' and laps_status = 1 and status = 1");
       $result = $qry->result_array();
       return $result[0]['total'];
    }

    public function insertBinaryAdminIncome($data)
    {   
        return $this->db->insert('tbl_binary_income_admin',$data);
    }
    public function insertSponserAdminIncome($data)
    {   
        return $this->db->insert('tbl_sponsor_income_admin',$data);
    }

    public function ActivateUserPackage($data,$where)
    {   
        return $this->db->update('tbl_users_package_details',$data,$where);
    }

    public function updateFund($data,$where)
    {   
        return $this->db->update('tbl_main_wallets',$data,$where);
    }

    public function MainWalletHistory($data)
    {   
        return $this->db->insert('tbl_main_wallet_histories',$data);
    }

    public function getFundAvailable()
    {
        $result = $this->db->select('total_fund')->from('tbl_main_wallets')->where(['main_type'=>'main','status'=>1])->get()->result_array();
        if(empty($result[0]['total_fund']))
        {
            return 0;
        }else
        {
            return $result[0]['total_fund'];
        }
    }



    




   }
?>