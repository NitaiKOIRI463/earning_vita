<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');
   class DailyClosing_model extends CI_Model
   {
       public function __construct() 
      {
           parent::__construct();
           
      }

      public function get_memers_m()
      {
        $qry = $this->db->query("select member_id from tbl_registration_master where activation_status = 1 ");
        return $result = $qry->result_array();

      }

      public function getMemberBusiness($parent_id,$side,$date)
        {
            $qry = $this->db->query("SELECT IF(sum(Effect_Amount) Is NULL,0,sum(Effect_Amount)) as total FROM `tbl_binary_income` where Member_id = '$parent_id' and Effect_Side = '$side' and laps_status = 1 and date(Effect_date_time) <=date('$date') and status = 1");
           $result = $qry->result_array();
           return $result[0]['total'];
        }
    public function getLetestPackageDetails_m($member_id)
      {
        if($member_id!="")
                $this->db->where('p.member_id',$member_id);
        return $this->db->select("p.member_id,p.package_id,p.sponsor_income_perc,p.matching_perc,roi_amount,days,total_return,n.name,p.expiry_date,p.activation_date")
            ->from('tbl_users_package_details p')
            ->join('tbl_registration_master n','n.member_id=p.member_id','left')
            ->where(['p.status'=>1,'p.current_status'=>'active'])->order_by('p.activation_date','desc')->limit(1)->get()->result_array();
            
      }

     public function getSponserTotal_m($member_id,$date)
      {
        $result = $this->db->query("SELECT IF(SUM(Sponsor_income) is NULL,0,SUM(Sponsor_income)) as total FROM `tbl_sponsor_income` where laps_status = 1 and MemberId = '$member_id' and date(Sponsor_date) = date('$date')")->result_array();
         return $result[0]['total'];
            
      }

      public function getRoiTotal_m($member_id)
      {
        $result = $this->db->query("SELECT IF(SUM(roi_income) is NULL,0,SUM(roi_income)) as total FROM `tbl_daily_income_ledger` where status = 1 and member_id = '$member_id'")->result_array();
         return $result[0]['total'];
            
      }

      public function getROIPackageListDetails_m($member_id)
      {
        if($member_id!="")
                $this->db->where('p.member_id',$member_id);
        return $this->db->select("p.member_id,p.package_id,p.sponsor_income_perc,p.matching_perc,roi_amount,days,total_return,n.name,p.remaining_amount,p.release_amount,p.activation_date,p.expiry_date")
            ->from('tbl_users_package_details p')
            ->join('tbl_registration_master n','n.member_id=p.member_id','left')
            ->where(['p.status'=>1,'p.current_status'=>'active'])->order_by('p.activation_date','desc')->get()->result_array();
            
      }
    

}