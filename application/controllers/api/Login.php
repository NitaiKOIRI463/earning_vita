<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Login extends REST_Controller 
{
   public function __construct()
   {
       parent::__construct();
     $this->load->model('Login_model');
   }

   public function login_member_post()
   {
        if($this->input->post('member_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }elseif($this->input->post('password',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'password required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $verify = $this->Login_model->verifyUserIdExistOrNot($this->input->post('member_id',true));
            if($verify>0)
            {

                $userData = $this->Login_model->getUserIdDetails($this->input->post('member_id',true));
                if($userData['password'] == $this->input->post('password',true))
                {

                    if($userData['block_status']==1)
                    {
                        $this->response(['status'=>false,'data'=>[],'msg'=>'This account is inactive !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
                    }else
                    {
                        if($userData['role_type']==2)
                        {
                            $userInfo = [];
                            $userInfo['user_id'] = $userData['member_id'];
                            $userInfo['name'] = $userData['name'];
                            $userInfo['role_type'] = $userData['role_type'];
                            $userInfo['gender'] = $userData['gender'];

                            $this->response(['status'=>true,'data'=>['userData'=>$userInfo],'msg'=>'Auth Success !','response_code' => REST_Controller::HTTP_OK]);
                        }else
                        {
                            $this->response(['status'=>false,'data'=>[],'msg'=>'Invalid Credentials !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
                        }
                        

                    }

                }else
                {
                     $this->response(['status'=>false,'data'=>[],'msg'=>'UserId Or Password Not Match !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
                }

            }else
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'Invalid Id !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
            }
        }
   }

    public function login_admin_post()
   {
        if($this->input->post('user_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'user_id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }elseif($this->input->post('password',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'password required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $verify = $this->Login_model->verifyUserIdExistOrNot($this->input->post('user_id',true));
            if($verify>0)
            {

                $userData = $this->Login_model->getUserIdDetails($this->input->post('user_id',true));
                if($userData['password'] == $this->input->post('password',true))
                {

                    if($userData['block_status']==1)
                    {
                        $this->response(['status'=>false,'data'=>[],'msg'=>'This account is inactive !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
                    }else
                    {
                        
                        if($userData['role_type']==1)
                        {
                            $userInfo = [];
                            $userInfo['user_id'] = $userData['member_id'];
                            $userInfo['name'] = $userData['name'];
                            $userInfo['role_type'] = $userData['role_type'];
                            $userInfo['gender'] = $userData['gender'];

                            $this->response(['status'=>true,'data'=>['userData'=>$userInfo],'msg'=>'Auth Success !','response_code' => REST_Controller::HTTP_OK]);
                        }else
                        {
                            $this->response(['status'=>false,'data'=>[],'msg'=>'Invalid Credentials !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
                        }

                    }

                }else
                {
                     $this->response(['status'=>false,'data'=>[],'msg'=>'UserId Or Password Not Match !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
                }

            }else
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'Invalid Id !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
            }
        }
   }


	
}
?>