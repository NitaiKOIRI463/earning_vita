<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Daily_income extends REST_Controller 
{
   public function __construct()
   {
       parent::__construct();
     $this->load->model('Daily_income_model');
   }
   public function getIncome_post()
    { 
        try{
            $member_id = $this->input->post('member_id',true)!=""?$this->input->post('member_id',true):"";
            $result = $this->Daily_income_model->getIncome($member_id);
            $this->response(['status'=>true,'data'=>$result,'msg'=>'successfully','response_code' => REST_Controller::HTTP_OK]);
        }catch(Exception $e)
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function getMember_roi_post()
    { 
        try{
            
            $member_id = $this->input->post('member_id',true)!=""?$this->input->post('member_id',true):"";
            $result=[];
            $result['total_roi'] = $this->Daily_income_model->member_total_roi($member_id);
            $result['total_income'] = $this->Daily_income_model->member_total_amount($member_id);
            $result['total_matching'] = $this->Daily_income_model->member_total_matching($member_id);
            $result['total_withdrawal'] = $this->Daily_income_model->member_withdrawal($member_id);
            $result['member_details'] = $this->Daily_income_model->member_details($member_id);
            $this->response(['status'=>true,'data'=>$result,'msg'=>'successfully','response_code' => REST_Controller::HTTP_OK]);
        }catch(Exception $e)
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
    // public function member_details_post()
    // {
    //     try{
    //         $member_id = $this->input->post('member_id',true)!=""?$this->input->post('member_id',true):"";
    //         $result = $this->Daily_income_model->member_details($member_id);
    //         $this->response(['status'=>true,'data'=>$result,'msg'=>'successfully','response_code' => REST_Controller::HTTP_OK]);
    //     }catch(Exception $e)
    //     {
    //         $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
    //     }
    // }
}
