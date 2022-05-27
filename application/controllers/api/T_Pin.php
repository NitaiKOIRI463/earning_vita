<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class T_Pin extends REST_Controller 
{
   public function __construct()
   {
       parent::__construct();
     $this->load->model('T_Pin_Model');
   }

public function resetPin_post()
    {
        if($this->input->post('transaction_pin',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'transaction_pin required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }elseif($this->input->post('new_transaction_pin',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'new_transaction_pin required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }elseif($this->input->post('member_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else{
           try{
                    $transaction_pin = $this->T_Pin_Model->ResetPin($this->input->post('member_id'));
                    if(empty($transaction_pin))
                    {
                    $this->response(['status'=>false,'data'=>[],'msg'=>'Password Wrong !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
                    }else
                        {

                            if($transaction_pin[0]['transaction_pin']==($this->input->post('transaction_pin',true)))
                            {

                            $newtransaction_pin = $this->T_Pin_Model->updatePin($this->input->post('member_id'),$this->input->post('new_transaction_pin'));
                                $this->response(['status'=>true,'data'=>$newtransaction_pin,'msg'=>'successfully changed password','response_code' => REST_Controller::HTTP_OK]);
                            }else
                            {
                              $this->response(['status'=>false,'data'=>[],'msg'=>'old password not match !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);                        
                            }
                        }
                
                    }catch(Exception $e)
                    {
                        $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
                    }
                }
        
    }
}
   