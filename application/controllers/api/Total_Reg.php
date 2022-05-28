<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Total_Reg extends REST_Controller 
{
   public function __construct()
   {
       parent::__construct();
     $this->load->model('Total_reg_Model');
   }
   public function getTotalReg_post()
   {
      try{
         $total=[];        
         $total['Left'] = $this->Total_reg_Model->getTotalLeft();
         $total['Right'] = $this->Total_reg_Model->getTotalRight();
         $total['Sum']= $this->Total_reg_Model->getTotal();
            $this->response(['status'=>true,'data'=>$total,'msg'=>'successfully','response_code' => REST_Controller::HTTP_OK]);
         
      } catch (Exception $e) {
         $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
      }
   }
   public function get_bussiness_post(){
         try{
         $total=[];        
         $total['Left_bussiness'] = $this->Total_reg_Model->Left_bussiness($this->input->post('member_id',true));
         $total['Right_bussiness'] = $this->Total_reg_Model->Right_bussiness($this->input->post('member_id',true));
         $total['Total_bussiness']= $this->Total_reg_Model->Total_bussiness($this->input->post('member_id',true));
            $this->response(['status'=>true,'data'=>$total,'msg'=>'successfully','response_code' => REST_Controller::HTTP_OK]);
         
      } catch (Exception $e) {
         $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
      }
   }
}
