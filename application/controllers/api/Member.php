<?php
    use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;
   require_once APPPATH . 'third_party/PHPMailer/Exception.php';
   require_once APPPATH . 'third_party/PHPMailer/PHPMailer.php';
   require_once APPPATH . 'third_party/PHPMailer/SMTP.php';
require APPPATH . 'libraries/REST_Controller.php';
     
class Member extends REST_Controller 
{
   public function __construct()
   {
       parent::__construct();
     $this->load->model('Member_model');
   }

   public function addMember_post()
   {
     if($this->input->post('name',true)=='')
      {
         $this->response(['status'=>false,'data'=>[],'msg'=>'name required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('email_id',true)=='')
      {
         $this->response(['status'=>false,'data'=>[],'msg'=>'email_id required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('f_h_name',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'f_h_name required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('mobile_no',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'mobile_no required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('gender',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'gender required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('sponsor_id',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'sponsor_id required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('side',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'side required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('country_code',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'country_code required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('country',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'country required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('state',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'state required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('city',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'city required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('pin',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'pin required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('address',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'address required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }
      else{

         try{
            $num_rows = $this->Member_model->verifyRegisterExist($this->input->post('mobile',true),$this->input->post('email_id',true));
                if($num_rows>0)
               {
                  $this->response(['status'=>false,'data'=>[],'msg'=>'mobile or email already exist !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
                } 
                $number_rows = $this->Member_model->countSponser_Id($this->input->post('sponsor_id',true));
                
                if($number_rows==1)

                {
                  $dataArray=[];
                  $pass = mt_rand(111111,999999);
                  $dataArray['member_id']=$this->Member_model->getNewId();
                  $dataArray['parent_id'] = $this->get_parent_id($this->input->post('sponsor_id',true),$this->input->post('side',true));
                  $dataArray['name'] = $this->input->post('name',true);
                  $dataArray['email_id'] = $this->input->post('email_id',true);
                  $dataArray['f_h_name'] = $this->input->post('f_h_name',true);
                  $dataArray['mobile_no'] = $this->input->post('mobile_no',true);
                  $dataArray['gender'] = $this->input->post('gender',true);
                  $dataArray['sponsor_id'] = $this->input->post('sponsor_id',true);
                  $dataArray['password'] = $pass;
                  $dataArray['side'] = $this->input->post('side',true);
                  $dataArray['country_code'] = $this->input->post('country_code',true);
                  $dataArray['title'] = $this->input->post('title',true);
                  $dataArray['country'] = $this->input->post('country',true);
                  $dataArray['state'] = $this->input->post('state',true);
                  $dataArray['city'] = $this->input->post('city',true);
                  $dataArray['address'] = $this->input->post('address',true);
                  $dataArray['pin'] = $this->input->post('pin',true);
                  $dataArray['registration_date'] = date('Y-m-d H:i:s');
                  $dataArray['c_by'] = $this->input->post('c_by',true);
                  $dataArray['c_date'] = date('Y-m-d H:i:s');
                  $dataArray['status'] = 1;
                  $this->Member_model->addUserData($dataArray);

                  if(!empty($dataArray['member_id']))
                  {
                    $content = '';
                    $mail = new PHPMailer();
                    $mail->IsSMTP();
                    $mail->Mailer = "smtp";
                    $mail->SMTPDebug = 0;
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = "tls";
                    $mail->Port = 587;
                    $mail->Host = "smtpout.secureserver.net";
                    $mail->Username = "info@cryptotrado.com";
                    $mail->Password = "i@123456!";
                    $mail->IsHTML(true);
                    $mail->AddAddress($dataArray['email_id'],$dataArray['name']);
                    // $mail->AddAddress("kumarsamir812@gmail.com","Samir Singh");
                    $mail->SetFrom("info@cryptotrado.com", "crypto trado");
                    $mail->Subject = "REGISTERED SUCCESFULLY !!";
                    $content  .= '<h5> Dear '.$dataArray['name'].'</h5>';
                    $content  .= '<h6>You have successfully registerd with us</h6>';
                    $content  .= '<strong>Your Crendetials</strong>';
                    $content  .= '<p>Member Id : </p>';
                    $content  .= '<strong>'.$dataArray['member_id'].'</strong>';
                    $content  .= '<p>Password : </p>';
                    $content  .= '<strong>'.$pass.'</strong>';
                    $content  .= '<p>Thank You</p>';
                    $mail->MsgHTML($content);
                    $mail->Send();
                  }

                  $this->response(['status'=>true,'data'=>['password'=>$pass,'member_id'=>$dataArray['member_id']],'msg'=>'successfully registered','response_code' => REST_Controller::HTTP_OK]);

                }else
                  {
                   $this->response(['status'=>false,'data'=>[],'msg'=>'sponser_id not exist !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
                 }
                
            }catch(Exception $e)
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
            }
            

        }      
   }


   public function get_parent_id($parent_id,$side)
    {

        $tmp_parent_id = $parent_id;
        while(true)
        {
            $result = $this->Member_model->getparentId($tmp_parent_id,$side);
            if($result==200)
            {
                $parent_id = $parent_id;
                break;
            }else
            {
                $tmp_parent_id = $result;
            }
            
        }
        return $tmp_parent_id;
    }


   public function updateMember_post(){
    if($this->input->post('name',true)=='')
      {
         $this->response(['status'=>false,'data'=>[],'msg'=>'name required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('f_h_name',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'f_h_name required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('mobile_no',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'mobile_no required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('gender',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'gender required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('country_code',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'country_code required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('country',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'country required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('state',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'state required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('city',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'city required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]); 
      }elseif($this->input->post('pin',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'pin required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('address',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'address required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('member_id',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('d_by',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'d_by required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('transaction_pin',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'transaction_pin required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }elseif($this->input->post('password',true)=='')
      {
        $this->response(['status'=>false,'data'=>[],'msg'=>'password required !',
            'response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
      }
      else{

         try{
               $num_rows = $this->Member_model->verifyRegisterMobileUpdateExist($this->input->post('mobile',true),$this->input->post('member_id',true));
                if($num_rows>0)
               {
                  $this->response(['status'=>false,'data'=>[],'msg'=>'mobile already exist !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
               }else
               {
                  $dataArray=[];

                  if (isset($_POST['photo']) && !empty($_POST['photo'])) 
                  {
                           $photo_incoded = $this->input->post('photo',true);
                           // $inside_image = str_replace(' ', '+', $inside_image_incoded);
                           $imageData = base64_decode($photo_incoded);
                           $photo = uniqid() . '.jpg';

                     $photo_file = '../all-uploaded-img/img/' . $photo;
                     $success = file_put_contents(APPPATH . $photo_file, $imageData);
                     $dataArray['photo'] = $photo;
                  }

                  $dataArray['name'] = $this->input->post('name',true);
                  $dataArray['f_h_name'] = $this->input->post('f_h_name',true);
                  $dataArray['mobile_no'] = $this->input->post('mobile_no',true);
                  $dataArray['gender'] = $this->input->post('gender',true);
                  $dataArray['title'] = $this->input->post('title',true);
                  $dataArray['country_code'] = $this->input->post('country_code',true);
                  $dataArray['country'] = $this->input->post('country',true);
                  $dataArray['state'] = $this->input->post('state',true);
                  $dataArray['city'] = $this->input->post('city',true);
                  $dataArray['transaction_pin'] = $this->input->post('transaction_pin',true);
                  $dataArray['password'] = $this->input->post('password',true);
                  $dataArray['address'] = $this->input->post('address',true);
                  $dataArray['pin'] = $this->input->post('pin',true);
                  $dataArray['d_by'] = $this->input->post('d_by',true);
                  $dataArray['d_date'] = date('Y-m-d H:i:s');

                  $this->Member_model->updateUser($dataArray,['member_id'=>$this->input->post('member_id',true),'status'=>1]);

                  $this->response(['status'=>true,'data'=>[],'msg'=>'successfully Updated','response_code' => REST_Controller::HTTP_OK]); 
               } 
                
                
            }catch(Exception $e)
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
            }
            

        }    
   }
   
   public function getRegisterData_post()
    {
        try{
            $member_id = $this->input->post('member_id',true)!=""?$this->input->post('member_id',true):"";
            $limit = $this->input->post('limit',true)!=""?$this->input->post('limit',true):10;
            $page = $this->input->post('page',true)!=""?$this->input->post('page',true):1;
            $result = $this->Member_model->getRegisterData($member_id,$page,$limit);
            $total_rows = $this->Member_model->getRegisterDataCount($member_id);
            $pages = 1;
            if(($total_rows%$limit)==0)
            {
               $pages = ($total_rows/$limit);
            }else
            {
                $pages = intval($total_rows/$limit)+1;
            }

            ;
            $this->response(['status'=>true,'data'=>['total_pages'=>$pages,'total_record'=>$total_rows,'current_page'=>$page,'data'=>$result],'msg'=>'successfully','response_code' => REST_Controller::HTTP_OK]);
        }catch(Exception $e)
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
    public function deleteRegister_post()
    {
        if($this->input->post('member_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }if($this->input->post('d_by',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'d_by required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {

            try{
               $dataArray = [];
               $dataArray['d_by'] = $this->input->post('d_by');
               $dataArray['d_date'] = date('Y-m-d H:i:s');
               $dataArray['status'] = 0;
                $this->Member_model->modifyRegister($dataArray,['member_id'=>$this->input->post('member_id',true)]);
                $this->response(['status'=>true,'data'=>[],'msg'=>'successfully deleted','response_code' => REST_Controller::HTTP_OK]);
                
            }catch(Exception $e)
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
            }

        }
    }

    public function blockUnblockedRegister_post()
    {
        if($this->input->post('member_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }if($this->input->post('d_by',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'d_by required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }if($this->input->post('status',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'status required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {

            try{
               $dataArray = [];
               $dataArray['d_by'] = $this->input->post('d_by');
               $dataArray['d_date'] = date('Y-m-d H:i:s');
               $dataArray['block_status'] = $this->input->post('status',true);
                $this->Member_model->modifyRegister($dataArray,['member_id'=>$this->input->post('member_id',true)]);
                $this->response(['status'=>true,'data'=>[],'msg'=>'successfully updated','response_code' => REST_Controller::HTTP_OK]);
                
            }catch(Exception $e)
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
            }

        }
    }
    
    public function get_parent_id_post()
    {
      
      $result = $this->Member_model->countParentId_Id($this->input->post('sponsor_id',true));
      $this->response(['status'=>true,'data'=>$result,'msg'=>'successfully','response_code' => REST_Controller::HTTP_OK]);
    }


    public function verify_mobile_post()
    {
        if($this->input->post('mobile',true)=='')
        {
             $this->response(['status'=>false,'data'=>[],'msg'=>'mobile required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $num_rows = $this->Member_model->verifyRegisterMobileExist($this->input->post('mobile',true));
            if($num_rows>0)
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'mobile already exist !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
            }else
            {
                $this->response(['status'=>true,'data'=>[],'msg'=>'Successfully verified !','response_code' => REST_Controller::HTTP_OK]);
            } 
        }
        
    }

    public function verify_email_post()
    {
        if($this->input->post('email',true)=='')
        {
             $this->response(['status'=>false,'data'=>[],'msg'=>'email required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $num_rows = $this->Member_model->verifyRegisterEmailExist($this->input->post('email',true));
            if($num_rows>0)
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'email already exist !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
            }else
            {
                $this->response(['status'=>true,'data'=>[],'msg'=>'Successfully verified !','response_code' => REST_Controller::HTTP_OK]);
            } 
        }
        
    }

    public function verify_member_post()
    {
        if($this->input->post('member_id',true)=='')
        {
             $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $num_rows = $this->Member_model->verifyRegisterMemberExist($this->input->post('member_id',true));
            if($num_rows>0)
            {
                $this->response(['status'=>true,'data'=>[],'msg'=>'Successfully verified !','response_code' => REST_Controller::HTTP_OK]);
            }else
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'Invalid member Id !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
            } 
        }
        
    }

    public function verify_update_mobile_post()
    {
        if($this->input->post('mobile',true)=='')
        {
             $this->response(['status'=>false,'data'=>[],'msg'=>'mobile required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }if($this->input->post('member_id',true)=='')
        {
             $this->response(['status'=>false,'data'=>[],'msg'=>'member_id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $num_rows = $this->Member_model->verifyRegisterMobileUpdateExist($this->input->post('mobile',true),$this->input->post('member_id',true));
            if($num_rows>0)
            {
                $this->response(['status'=>false,'data'=>[],'msg'=>'mobile already exist !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
            }else
            {
                $this->response(['status'=>true,'data'=>[],'msg'=>'Successfully verified !','response_code' => REST_Controller::HTTP_OK]);
            } 
        }
        
    }



    


}
?>