<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class GenerateFund extends REST_Controller 
{
   public function __construct()
   {
       parent::__construct();
     $this->load->model('GenerateFund_model');
   }


   public function generate_fund_post()
   {
        if($this->input->post('fund',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'fund required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }elseif($this->input->post('c_by',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'c_by required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $total_fund = $this->GenerateFund_model->getPackageFund_m();
            $mainData = [];
            $whereData = [];
            $mainData['total_fund'] = $total_fund+$this->input->post('fund',true);
            $mainData['d_by'] = $this->input->post('c_by',true);
            $mainData['d_date'] = date('Y-m-d H:i:s');
            $mainData['status'] = 1;
            $whereData['main_type'] = 'main';
            $whereData['status'] = 1;

            $this->GenerateFund_model->updateGenerateFundAvailable_m($mainData,$whereData);
            $mainHistory = [];
            $mainHistory['fund'] = $this->input->post('fund',true);
            $mainHistory['c_date'] = date('Y-m-d H:i:s');
            $mainHistory['c_by'] = $this->input->post('c_by',true);
            $mainHistory['status'] = 1;
            if($this->input->post('ref_no',true)!='')
            {
                $mainHistory['ref_no'] = $this->input->post('ref_no',true);
            }else
            {
                $mainHistory['ref_no'] = 'G';
            }
            
            $mainHistory['transaction_type'] = 'IN';
            $this->GenerateFund_model->generateFundHistories_m($mainHistory);
            $this->response(['status'=>false,'data'=>[],'msg'=>'Successfully Fund Added !','response_code' => REST_Controller::HTTP_OK]);
        }

   }

   public function get_generate_fund_history_post()
   {
        $resultData = $this->GenerateFund_model->getGeneratedFundHistory_m();
        if(!empty($resultData))
        {
            $this->response(['status'=>false,'data'=>['histories'=>$resultData],'msg'=>'Successfully Fetched !','response_code' => REST_Controller::HTTP_OK]);
        }else
        {
            $this->response(['status'=>false,'data'=>['histories'=>$resultData],'msg'=>'No Record Found !','response_code' => REST_Controller::HTTP_OK]);
        }
        
   }

   public function get_available_fund_post()
   {
        $resultData = $this->GenerateFund_model->getAvailableFunds_m();
        if(!empty($resultData))
        {
            $this->response(['status'=>false,'data'=>['package_fund'=>$resultData],'msg'=>'Successfully Fetched !','response_code' => REST_Controller::HTTP_OK]);
        }else
        {
            $this->response(['status'=>false,'data'=>['package_fund'=>$resultData],'msg'=>'No Record Found !','response_code' => REST_Controller::HTTP_OK]);
        }
        
   }


	
}
?>