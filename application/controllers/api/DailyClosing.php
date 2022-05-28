<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class DailyClosing extends REST_Controller 
{
   public function __construct()
   {
       parent::__construct();
     $this->load->model('DailyClosing_model');
   }

    // public function daily_closing_post()
    // { 
    //     $date = $this->input->post('date')!=""?$this->input->post('date'):date('Y-m-d');
    //     $result = $this->DailyClosing_model->get_memers_m();
    //     if(!empty($result))
    //     {
    //         $main_data = [];
    //         foreach($result as $key=>$value)
    //         {
    //             $member_id = $value['member_id'];
    //             $left = $this->DailyClosing_model->getMemberBusiness($member_id,"L",$date);
    //             $right = $this->DailyClosing_model->getMemberBusiness($member_id,"R",$date);
    //             $pkgDetails = $this->DailyClosing_model->getLetestPackageDetails_m($member_id);
    //             $main_data[$key]['member_id'] = $member_id;
    //             if(!empty($pkgDetails))
    //             {
    //                 $total_return = $pkgDetails[0]['total_return'];
    //                 $activation_date = $pkgDetails[0]['activation_date'];
    //                 $matching_perc = $pkgDetails[0]['matching_perc'];
    //                 $sponsor_income_perc = $pkgDetails[0]['sponsor_income_perc'];
    //                 $days = $pkgDetails[0]['days'];
    //                 $expiry_date = $pkgDetails[0]['expiry_date'];
    //                 $match_income = 0;
    //                 if($left<=$right)
    //                 {
    //                     $match_income = $left;
    //                 }elseif($right<=$left)
    //                 {
    //                     $match_income = $right;
    //                 }

    //                 if($match_income>0)
    //                 {
    //                     $matching_commision = ($match_income/100)*$matching_perc;
    //                 }else
    //                 {
    //                     $matching_commision = 0;
    //                 }
    //                 $main_data[$key]['matching_commision'] = $matching_commision;
    //                 $sponser_income = $this->DailyClosing_model->getSponserTotal_m($member_id,$date);
    //                 if($sponser_income>0)
    //                 {
    //                    $main_data[$key]['sponser_income'] = $sponser_income;

    //                 }else
    //                 {
    //                     $main_data[$key]['sponser_income'] = 0;
    //                 }

    //                 $allActive_packages = $this->DailyClosing_model->getROIPackageListDetails_m($member_id);
    //                 if(!empty($allActive_packages))
    //                 {
    //                     $roi_total = [];
    //                     $j = 0;
    //                     foreach ($allActive_packages as $key => $value) {
                            
    //                         $pkg_remaining_amount = $value['remaining_amount'];
    //                         $pkg_total_return = $value['total_return'];
    //                         $pkg_roi_amount = $value['roi_amount'];
    //                         $pkg_release_amount = $value['release_amount'];
    //                         $pkg_activation_date = $value['activation_date'];
    //                         $pkg_expiry_date = $value['expiry_date'];
    //                         if(date('Y-m-d',strtotime($pkg_activation_date))<=date('Y-m-d',strtotime($date)) && date('Y-m-d',strtotime($date))<=date('Y-m-d',strtotime($pkg_expiry_date)))
    //                         {

    //                             if(($pkg_release_amount+$pkg_roi_amount)<=$pkg_total_return)
    //                             {
    //                                 $roi_total += $pkg_roi_amount;
    //                             }elseif(($pkg_release_amount+$pkg_roi_amount)>$pkg_total_return)
    //                             {
    //                                 $rem_amount = $pkg_total_return-$pkg_release_amount;

    //                             }

    //                         }
                            


    //                     }
    //                 }

    //             }

    //         }
            
           
    //     }
    // }
 

 }
