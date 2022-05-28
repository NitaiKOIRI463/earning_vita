<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Total_Activation extends REST_Controller 
{
   public function __construct()
   {
       parent::__construct();
     $this->load->model('Total_Activ_model');
   }
   public function Left_Activ_post()
   {
      try{
         $total=[];        
         $total['Left_Activ'] = $this->Total_Activ_model->Left_Activation();
         $total['Right_Activ'] = $this->Total_Activ_model->Right_Activation();
         $total['Total_Activ'] = $this->Total_Activ_model->Total_Activation();
            $this->response(['status'=>true,'data'=>$total,'msg'=>'successfully','response_code' => REST_Controller::HTTP_OK]);
         
      } catch (Exception $e) {
         $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
      }
   }
   public function total_sponsor_post()
   {
      try{
         $total=[];        
         $total['sponsor'] = $this->Total_Activ_model->total_sponsor();
         $total['roi'] = $this->Total_Activ_model->total_roi();
         // $total['Total_Activ'] = $this->Total_Activ_model->Total_Activation();
            $this->response(['status'=>true,'data'=>$total,'msg'=>'successfully','response_code' => REST_Controller::HTTP_OK]);
         
      } catch (Exception $e) {
         $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
      }
   }
}