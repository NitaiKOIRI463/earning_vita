<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Package extends REST_Controller 
{
   public function __construct()
   {
       parent::__construct();
     $this->load->model('Package_model');
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

   


    


}
?>