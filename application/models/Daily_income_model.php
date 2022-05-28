<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');
   class Daily_income_model extends CI_Model
   {
       public function __construct() 
      {
           parent::__construct();
           
      }
        public function getIncome($member_id)
       {
        if($member_id!=""){
            $this->db->where('member_id',$member_id);
            return $this->db->select('*')->from('tbl_daily_income_ledger')->where(['status'=>1])->get()->result_array();
            }
        }

        public function member_total_roi($member_id)
        {
           $result = $this->db->select('sum(roi_income) as total ')->from('tbl_daily_income_ledger')->where(['status'=>1,'member_id'=>$member_id])->get()->result_array();
            if(!empty($result))
            {
                    return $result[0]['total'];
            }else{
                    return 0;
                }
            
        }
        public function member_total_amount($member_id)
        {
           $result = $this->db->select('sum(total_amount) as total ')->from('tbl_daily_income_ledger')->where(['status'=>1,'member_id'=>$member_id])->get()->result_array();
            if(!empty($result))
            {
                    return $result[0]['total'];
            }else{
                    return 0;
                }
            
        }
        public function member_total_matching($member_id)
        {
            $result = $this->db->select('sum(matching_income) as total_matching')->from('tbl_daily_income_ledger')->where(['status'=>1,'member_id'=>$member_id])->get()->result_array();
            if (!empty($result)) {
                return $result[0]['total_matching'];
            }else{
                return 0;
            }
        }

        

        public function member_total_direct($member_id)
        {
            $result = $this->db->select('sum(sponser_income) as total_sponser')->from('tbl_daily_income_ledger')->where(['status'=>1,'member_id'=>$member_id])->get()->result_array();
            if (!empty($result)) {
                return $result[0]['total_sponser'];
            }else{
                return 0;
            }
        }

        public function member_withdrawal($member_id)
        {
            $result = $this->db->select('sum(fund) as total_fund')->from('tbl_user_wallets_histories')->where(['member_id'=>$member_id,'transaction_type'=>'OUT','status'=>1])->get()->result_array();
            if (!empty($result)) {
                return $result[0]['total_fund'];
            }else{
                return 0;
            }
        }


        public function member_details($member_id)
        {
            return  $this->db->select('member_id,name,email_id,mobile_no')->from('tbl_registration_master')->where(['member_id'=>$member_id,'status'=>1])->get()->result_array();
        }

}