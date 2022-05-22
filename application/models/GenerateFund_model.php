<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');

class GenerateFund_model extends CI_Model
{
  public function __construct() 
  {
     parent::__construct();
     
  }


  public function verifyPackageAlreadyGeneareted_m($package_id)
  {
      $result = $this->db->select('count(id) as total ')->from('tbl_package_available_fund')->where(['status'=>1,'package_id'=>$package_id])->get()->result_array();
      if(!empty($result))
      {
         return $result[0]['total'];
      }else
      {
         return 0;
      }
  }


  public function verifyPackageExist_m($package_id)
  {
      $result = $this->db->select('count(id) as total ')->from('tbl_plan_master')->where(['status'=>1,'package_id'=>$package_id])->get()->result_array();
      if(!empty($result))
      {
         return $result[0]['total'];
      }else
      {
         return 0;
      }
  }

  public function getPackageAmount_m($package_id)
  {
      $result = $this->db->select('package_amount')->from('tbl_plan_master')->where(['status'=>1,'package_id'=>$package_id])->get()->result_array();
      if(!empty($result))
      {
         return $result[0]['package_amount'];
      }else
      {
         return 0;
      }
      
  }

  public function getPackageFund_m()
  {
      $result = $this->db->select('total_fund')->from('tbl_main_wallets')->where(['status'=>1])->get()->result_array();
      if(!empty($result))
      {
         return $result[0]['total_fund'];
      }else
      {
         return 0;
      }
      
  }

  public function generateFundHistories_m($data)
  {
      return $this->db->insert('tbl_main_wallet_histories',$data);
  }

  public function updateGenerateFundAvailable_m($data,$where)
  {
      return $this->db->update('tbl_main_wallets',$data,$where);
  }

  public function insertGenerateFundAvailable_m($data)
  {
      return $this->db->insert('tbl_main_wallets',$data);
  }

  public function getGeneratedFundHistory_m()
  {
         return $this->db->select('fund,transaction_type,ref_no,DATE_FORMAT(c_date,"%d-%m-%Y %H:%i:%s") as gen_date,')
         ->from('tbl_main_wallet_histories')
         ->where(['status'=>1])->get()->result_array();
  }

  public function getAvailableFunds_m()
  {
         return $this->db->select('total_fund,DATE_FORMAT(d_date,"%d-%m-%Y %H:%i:%s") as last_update_date,')
         ->from('tbl_main_wallets')
         ->where(['status'=>1,'main_type'=>'main'])->get()->result_array();
  }




}
   
?>