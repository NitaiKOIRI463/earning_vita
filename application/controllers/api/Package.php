<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Package extends REST_Controller 
{
   public function __construct()
   {
         parent::__construct();
         $this->load->model('Package_model');
         $this->load->model('GenerateFund_model');
         $this->load->model('Package_model');
         $this->load->model('Member_model');
   }

   public function BuyPackage_post()
   {
     if($this->input->post('package_id',true)=='')
      {
         $this->response(['status'=>false,'data'=>[],'msg'=>'package_id required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('member_id',true)=='')
      {
         $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('required_btc',true)=='')
      {
         $this->response(['status'=>false,'data'=>[],'msg'=>'required_btc required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }else{

         try{
                $verify = $this->Package_model->verify_package_id_m($this->input->post('package_id',true));
                if($verify>0)
                {

                    $verify_member = $this->Package_model->verify_member_id_m($this->input->post('member_id',true));
                    if($verify_member>0)
                    {
                        $verify_requested = $this->Package_model->verify_package_request_m($this->input->post('member_id',true));
                        $packageDetails = $this->Package_model->get_package_details_m($this->input->post('package_id',true));
                        if($verify_requested==0)
                        {
                            $mainData = [];
                            $mainData['member_id'] = $this->input->post('member_id',true);
                            $mainData['package_id'] = $this->input->post('package_id',true);
                            $mainData['package_name'] = $packageDetails[0]['package_name'];
                            $mainData['package_amount'] = $packageDetails[0]['package_amount'];
                            $mainData['profit_perc'] = $packageDetails[0]['profit_perc'];
                            $mainData['roi_perc'] = $packageDetails[0]['roi_perc'];
                            $mainData['roi_amount'] = $packageDetails[0]['roi_amount'];
                            $mainData['total_return'] = $packageDetails[0]['total_return'];
                            $mainData['days'] = $packageDetails[0]['days'];
                            $mainData['sponsor_income_perc'] = $packageDetails[0]['sponsor_income_perc'];
                            $mainData['required_btc'] = $this->input->post('required_btc',true);
                            $mainData['payment_type'] = $this->input->post('payment_type',true);
                            $mainData['matching_perc'] = $packageDetails[0]['matching_perc'];
                            $mainData['capping'] = $packageDetails[0]['capping'];
                            $mainData['c_by'] = $this->input->post('member_id',true);
                            $mainData['c_date'] = date('Y-m-d H:i:s');
                            $mainData['status'] = 1;
                            $mainData['current_status'] = 'pending';
                            $this->Package_model->buyPackage_m($mainData);
                            $this->response(['status'=>true,'data'=>[],'msg'=>'Successfully Requested !','response_code'=>REST_Controller::HTTP_OK]); 
                        }else
                        {
                            $this->response(['status'=>false,'data'=>[],'msg'=>'already a package request is pending plesae wait 30 mins !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
                        }
                        
                    }else
                    {
                        $this->response(['status'=>false,'data'=>[],'msg'=>'member not valid !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
                    }

                }else
                {
                    $this->response(['status'=>false,'data'=>[],'msg'=>'package not valid !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
                }


            }catch(Exception $e)
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
            }
            

        }      
   }

   public function get_bitcoin_address_post()
   {
     $result = $this->Package_model->getbitcoinaddress_m();
     $this->response(['status'=>true,'data'=>$result,'msg'=>'Successfully Requested !','response_code'=>REST_Controller::HTTP_OK]); 
   }

   public function get_pending_packages_post()
   {
        if($this->input->post('member_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $result = $this->Package_model->getPandingPackage_m($this->input->post('member_id',true));
            $this->response(['status'=>true,'data'=>$result,'msg'=>'Successfully Requested !','response_code'=>REST_Controller::HTTP_OK]); 
        }
     
   }


   public function expiry_package_request_post()
   {
        if($this->input->post('member_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
        }if($this->input->post('id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'request_id required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $mainData = [];
            $mainData['current_status'] = 'expired';
            $mainData['d_by'] = $this->input->post('member_id');
            $mainData['d_date'] = date('Y-m-d H:i:s');
            $result = $this->Package_model->update_package_purchase_m($mainData,['id'=>$this->input->post('id',true),'member_id'=>$this->input->post('member_id',true),'status'=>1]);
            $this->response(['status'=>true,'data'=>$result,'msg'=>'Expired !','response_code'=>REST_Controller::HTTP_OK]); 
        }
     
   }


   public function update_package_payment_details_post()
   {
        if($this->input->post('member_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
        }if($this->input->post('id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'request_id required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
        }if($this->input->post('hash_code',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'hash_code required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
        }if($this->input->post('screenshot',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'screenshot required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $mainData = [];
            $mainData['transaction_no'] = $this->input->post('hash_code',true);
            if(isset($_POST['screenshot']) && !empty($_POST['screenshot'])) 
              {
                       $screenshot_incoded = $this->input->post('screenshot',true);
                       $inside_image = str_replace(' ', '+', $screenshot_incoded);
                       $imageData = base64_decode($inside_image);
                       $screenshot = uniqid() . '.jpg';

                 $screenshot_file = '../all-uploaded-img/screenshot/' . $screenshot;
                 $success = file_put_contents(APPPATH . $screenshot_file, $imageData);
                 $mainData['screenshot'] = $screenshot;
              }
            $mainData['current_status'] = 'requested';
            $mainData['d_by'] = $this->input->post('member_id');
            $mainData['d_date'] = date('Y-m-d H:i:s');
            $result = $this->Package_model->update_package_purchase_m($mainData,['id'=>$this->input->post('id',true),'member_id'=>$this->input->post('member_id',true),'status'=>1]);

            $this->response(['status'=>true,'data'=>$result,'msg'=>'Updated Successfully !','response_code'=>REST_Controller::HTTP_OK]); 
        }
     
   }


   public function get_my_packages_post()
   {
        if($this->input->post('member_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $result = $this->Package_model->getMyPackage_m($this->input->post('member_id',true));
            $this->response(['status'=>true,'data'=>$result,'msg'=>'Successfully Fetched !','response_code'=>REST_Controller::HTTP_OK]); 
        }
     
   }


   public function get_requested_packages_post()
   {
        $member_id = $this->input->post('member_id',true)!=''?$this->input->post('member_id',true):"";
        $current_status = $this->input->post('current_status',true)!=''?$this->input->post('current_status',true):"";

        $result = $this->Package_model->getRequestPackage_m($member_id,$current_status);
        $this->response(['status'=>true,'data'=>$result,'msg'=>'Successfully Fetched !','response_code'=>REST_Controller::HTTP_OK]); 
     
   }

   public function activate_requested_packages_post()
   {
        if($this->input->post('user_request_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'user_request_id required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $pkg_details = $this->Package_model->getRequestPackageDetails_m($this->input->post('user_request_id',true));
            if(empty($pkg_details))
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'Invalid package request !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
            }else
            {
                // This is cuurent package request details
                $sponser_id = $pkg_details[0]['sponsor_id'];
                $member_id = $pkg_details[0]['member_id'];
                $package_amount = $pkg_details[0]['package_amount'];
                $current_status = $pkg_details[0]['current_status'];
                $package_days = $pkg_details[0]['days'];
                if($current_status=='requested')
                {
                    $pkg_sponserActivedetails = $this->Package_model->get_member_details_m($sponser_id);
                    $sponserArray = [];
                    if(isset($pkg_sponserActivedetails[0]['current_status']) && $pkg_sponserActivedetails[0]['current_status']=='active')
                    {
                        $pkg_SponserDetails = $this->Package_model->getLetestPackageDetails_m($sponser_id);
                        $sponsor_income_perc = $pkg_SponserDetails[0]['sponsor_income_perc'];
                        $sponser_name = $pkg_SponserDetails[0]['name'];
                        $sponserArray['sponser_status'] = "active";
                        $sponserArray['pkg_amount'] = $package_amount;
                        $sponserArray['sponser_name'] = $sponser_name;
                        $sponserArray['sponser_member_id'] = $sponser_id;
                        $sponserArray['sponser_perc'] = $sponsor_income_perc;
                        $sponserArray['sponser_income_perc'] = $sponsor_income_perc;
                        $sponserArray['total_commisson'] = ($package_amount/100)*$sponsor_income_perc;

                    }elseif(isset($pkg_sponserActivedetails[0]['current_status']))
                    {
                        $sponserArray['sponser_status'] = "inactive";
                        $sponserArray['pkg_amount'] = $package_amount;
                        $sponserArray['sponser_name'] = $pkg_sponserActivedetails[0]['name'];
                        $sponserArray['sponser_member_id'] = $sponser_id;
                        $sponserArray['sponser_income_perc'] = 0;
                        $sponserArray['total_commisson'] = 0;
                    } 
                    
                   $activate_date = date('Y-m-d H:i:s');
                   $expiry_date = $this->get_expiry_date($activate_date,$package_days);
                   $pkgArray = [];
                   $pkgArray['package_id'] = $pkg_details[0]['package_id'];
                   $pkgArray['roi_perc'] = $pkg_details[0]['roi_perc'];
                   $pkgArray['reg_id'] = $pkg_details[0]['reg_id'];
                   $pkgArray['roi_amount'] = $pkg_details[0]['roi_amount'];
                   $pkgArray['package_amount'] = $package_amount;
                   $pkgArray['member_id'] = $member_id;
                   $pkgArray['name'] = $pkg_details[0]['name'];
                   $pkgArray['activate_date'] = date('d-m-Y H:i:s',strtotime($activate_date));
                   $pkgArray['expiry_date'] = date('d-m-Y H:i:s',strtotime($expiry_date));
                   $pkgArray['roi_days'] = $pkg_details[0]['days'];
                   $pkgArray['total_return'] = $pkg_details[0]['total_return'];

                   $sevenLevelParents = $this->Package_model->get7Levelparents($member_id);
                   $machingIncomeArray = [];
                   if(!empty($sevenLevelParents))
                   {
                        foreach ($sevenLevelParents as $key => $value) {

                            $directSponserLeft = $this->Package_model->getTwoDirectRefral($value['parent_id'],'L');
                            $directSponserRight = $this->Package_model->getTwoDirectRefral($value['parent_id'],'R');

                            $pkg_parentActivedetails = $this->Package_model->get_member_details_m($value['parent_id']);

                             $moreAmountPkgLeft = $this->verifyPackageMoreThan($directSponserLeft,$pkg_parentActivedetails[0]['package_amount']);
                             $moreAmountPkgRight = $this->verifyPackageMoreThan($directSponserRight,$pkg_parentActivedetails[0]['package_amount']);
                             $leftBusiness = $this->Package_model->getMemberBusiness($value['parent_id'],"L");
                             $RightBusiness = $this->Package_model->getMemberBusiness($value['parent_id'],"L");

                             
                             if($value['side']=='L')
                             {
                                $leftBusiness = $leftBusiness+$package_amount;
                             }elseif($value['side']=='R')
                             {
                                $RightBusiness = $RightBusiness+$package_amount;
                             }
                             $maching_business_amount = 0;
                             if($leftBusiness<=$RightBusiness)
                             {
                                $maching_business_amount = $leftBusiness;
                             }elseif($RightBusiness<=$leftBusiness)
                             {
                                $maching_business_amount = $RightBusiness;
                             }
                             if($maching_business_amount>0)
                             {
                                if($moreAmountPkgLeft>0 && $moreAmountPkgRight>0)
                                {
                                    
                                    $machingIncomeArray[$key]['parent_id']  = $value['parent_id'];
                                    $machingIncomeArray[$key]['parent_name']  = $value['name'];
                                    $machingIncomeArray[$key]['matching_perc']  = $value['name'];
                                    $machingIncomeArray[$key]['matching_amount']  = $maching_business_amount;

                                    $machingIncomeArray[$key]['pkg_amount']  = $package_amount;
                                    $machingIncomeArray[$key]['side']  = $value['side'];
                                    $machingIncomeArray[$key]['matching_perc']  = $pkg_parentActivedetails[0]['matching_perc'];
                                    $machingIncomeArray[$key]['parent_level']  = $value['m_level'];
                                    $machingIncomeArray[$key]['commission']  = ($maching_business_amount/100)*$pkg_parentActivedetails[0]['matching_perc'];

                                    $machingIncomeArray[$key]['maching_status']  = 'success';
                                    $machingIncomeArray[$key]['remarks']  = 'NA';

                                }else
                                {
                                    $machingIncomeArray[$key]['parent_id']  = $value['parent_id'];
                                    $machingIncomeArray[$key]['parent_name']  = $value['name'];
                                    $machingIncomeArray[$key]['matching_perc']  = $value['name'];
                                    $machingIncomeArray[$key]['parent_level']  = $value['m_level'];
                                    $machingIncomeArray[$key]['pkg_amount']  = $package_amount;
                                    $machingIncomeArray[$key]['side']  = $value['side'];
                                    $machingIncomeArray[$key]['matching_perc']  = $pkg_parentActivedetails[0]['matching_perc'];
                                    $machingIncomeArray[$key]['matching_amount']  = $maching_business_amount;
                                    $machingIncomeArray[$key]['commission']  = ($maching_business_amount/100)*$pkg_parentActivedetails[0]['matching_perc'];

                                    $machingIncomeArray[$key]['maching_status']  = 'lapse';
                                    $machingIncomeArray[$key]['remarks']  = 'Due To Two Direct Sponser Not Found';
                                }

                             }
                            
                        }
                   }
                   
                  
                   $this->response(['status'=>true,'data'=>['sponser_income'=>$sponserArray,'matching_income'=>$machingIncomeArray,'roi'=>$pkgArray],'msg'=>'Success','response_code'=>REST_Controller::HTTP_OK]);

                }else
                {
                    $this->response(['status'=>false,'data'=>[],'msg'=>'package status not requested !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
                }
            }

            
            
        }
     
   }



   function update_activation_commison_post()
   {
        $availableFund = $this->Package_model->getFundAvailable();
        $package_amount = $this->input->post('package_amount',true);
        if($package_amount<=$availableFund)
        {
            $sponserParent = json_decode($_POST['sponserParent'],true);
            $machingParent = json_decode($_POST['machingParent'],true);

            if(isset($_POST['sponser']) && !empty($_POST['sponser']) && json_decode($_POST['sponser'],true)!=null)
            {

                $sponserData = json_decode($_POST['sponser'],true);
                $sponserData = explode("|", $sponserData);

                $spData['MemberId'] = $sponserData[0];
                $spData['Effect_Member_Id'] = $sponserData[1];
                $spData['Sponsor_income'] = $sponserData[2];
                $spData['Sponsor_date'] = date('Y-m-d H:i:s');
                $spData['Package_Amount'] = $sponserData[3];
                $spData['sponser_perc'] = $sponserData[4];
                if($sponserParent==$sponserData[0])
                {
                    $spData['laps_status'] = 1;
                    $spData['laps_remarks'] = 'NA';
                }else
                {
                    $spData['laps_status'] = 0;
                    $spData['laps_remarks'] = 'Lapse By Admin';
                }
                $spData['status'] = 1;
                $spData['c_by'] = $_POST['c_by'];
                $spData['c_date'] = date('Y-m-d H:i:s');

                $this->Package_model->insertSponserAdminIncome($spData);
            }
            if(isset($_POST['maching']) && !empty($_POST['maching']) && json_decode($_POST['maching'],true)!=NULL)
            {
                $machingData = json_decode($_POST['maching'],true);
                foreach($machingData as $key=>$value)
                {
                    $value = explode("|", $value);
                    $mcData['Member_id'] = $value[0];
                    $mcData['Effect_By_Id'] = $value[7];
                    $mcData['m_level'] = $value[1];
                    $mcData['Effect_Amount'] = $value[4];
                    $mcData['Matching_Amount'] = $value[2];
                    $mcData['Effect_Side'] = $value[8];
                    $mcData['Effect_date_time'] = date('Y-m-d H:i:s');
                    if(in_array($value[0],$machingParent))
                    {
                       $mcData['laps_status'] = 1;
                       $mcData['laps_remarks'] = 'NA'; 
                    }else
                    {
                        $mcData['laps_status'] = 0;
                        $mcData['laps_remarks'] = $value[6];
                    }
                    $mcData['status'] = 1;
                    $mcData['c_by'] = $_POST['c_by'];
                    $mcData['c_date'] = date('Y-m-d H:i:s');
                    $this->Package_model->insertBinaryAdminIncome($mcData);
                }   
            }

            $updateData = [];
            $whereData['id'] = $this->input->post('reg_id',true);
            $updateData['activated_by'] = $this->input->post('c_by',true);
            $updateData['activation_date'] = date('Y-m-d H:i:s',strtotime($this->input->post('activate_date',true)));
            $updateData['remaining_amount'] = $package_amount;
            $updateData['current_status'] = 'active';
            $updateData['expiry_date'] = date('Y-m-d H:i:s',strtotime($this->input->post('expiry_date',true)));
            $this->Package_model->ActivateUserPackage($updateData,$whereData);

            $walletHistoryData = [];
            $walletHistoryData['fund'] = $package_amount;
            $walletHistoryData['c_date'] = date('Y-m-d H:i:s');
            $walletHistoryData['c_by'] = $this->input->post('c_by',true);
            $walletHistoryData['status'] = 1;
            $walletHistoryData['transaction_type'] = "OUT";
            $walletHistoryData['ref_no'] = "PkgACTID_".$this->input->post('reg_id',true);
            $this->Package_model->MainWalletHistory($walletHistoryData);
            $availableFund = $availableFund - $package_amount;
            $this->Package_model->updateFund(['total_fund'=>$availableFund,'d_by'=>$this->input->post('c_by',true),'d_date'=>date('Y-m-d H:i:s')],['main_type'=>'main']);
            $this->response(['status'=>true,'data'=>[],'msg'=>'Successfully Activate !','response_code'=>REST_Controller::HTTP_OK]);
        }else
        {
             $this->response(['status'=>false,'data'=>[],'msg'=>'Insufficent Fund !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
        }
        
   }

   function verifyPackageMoreThan($dataArray,$parent_pkg_amt)
   {
        $status = 0;
        foreach($dataArray as $key=>$value)
        {
            if($parent_pkg_amt<=$value['package_amount'])
            {
                $status++;
            }
        }
        return $status;
   }


   public function get_levels_of_parents($member_id,$level)
    {
        $tmp_member_id = $member_id;
        $members = [];
        $i = 0; 
        $lvl = 1;
        while($i<=$level)
        {
            $result = $this->Package_model->getsevenLevelparentId($tmp_member_id);
            // print_r($result);die;
            if($result==200)
            {
                $tmp_member_id = $member_id;
                break;
            }else
            {
                $tmp_member_id = $result['parent_id'];
                $smallArray = [];
                if($tmp_member_id!="NULL" && $result['member_id']!=$member_id)
                {
                  $package_d = $this->Package_model->getLetestPackageDetails_m($tmp_member_id);
                  $smallArray = ['member_id'=>$result['member_id'],'level'=>$lvl,'name'=>$result['name'],'side'=>$result['side'],'package'=>$package_d];
                  array_push($members,$smallArray);
                  $lvl++;  
                }elseif($tmp_member_id=="NULL" && $result['member_id']!=$member_id){
                    $package_d = $this->Package_model->getLetestPackageDetails_m($result['member_id']);
                    $smallArray = ['member_id'=>$result['member_id'],'level'=>$lvl,'name'=>$result['name'],'side'=>$result['side'],'package'=>$package_d];
                  array_push($members,$smallArray);
                  $lvl++;  
                }
            }
            $i++;
        }
        
        return $members;
    }


   public function get_genology_post()
   {
        $parent_id = $this->input->post('parent_id',true)!=''?$this->input->post('parent_id',true):"";
        $result = $this->Package_model->getChildGenology_m($parent_id);
        $this->response(['status'=>true,'data'=>$result,'msg'=>'Successfully Fetched !','response_code'=>REST_Controller::HTTP_OK]); 
     
   }
   

   function get_expiry_date($days,$addDays)
   {
        
        $i = 1;
        $current_date = $days;
        $tmp_date = $days;
        while(true)
        {
           $tmp_date = date('Y-m-d H:i:s',strtotime($tmp_date.' +1 day'));
           if(!(date('D',strtotime($tmp_date))=='Sat' || date('D',strtotime($tmp_date)) =='Sun'))
           {
               $current_date = date('Y-m-d H:i:s',strtotime($current_date.' +1 day'));
               $current_date = $tmp_date;
               $i++;
           }
           if($i>=$addDays)
           {
                break;
           }
           
        }
        return $current_date;    
   }

    


}
?>