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
                  $tarnsactionpass = mt_rand(111111111,99999999999);
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
                  $dataArray['transaction_pin'] = $tarnsactionpass;
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
                    $mail->Username = "nonreply@earningvista.com";
                    $mail->Password = "n@123456!";
                    $mail->IsHTML(true);
                    $mail->AddAddress($dataArray['email_id'],$dataArray['name']);
                    // $mail->AddAddress("kumarsamir812@gmail.com","Samir Singh");
                    $mail->SetFrom("info@cryptotrado.com", "crypto trado");
                    $mail->Subject = "WELCOME LETTER";
                      $content  = '<table style="width:100%;background: #462b13;font-family: system-ui;">';
                      $content .= '<div style="text-align:center;margin-top:100px;"><img style="width:200px;height:50px;" src="https://cryptotrado.com/client/images/crypto-trade-portal-logo-01.png"></div>';
                      $content  .= '<div style="width:400px; background: white;color:black; margin:10px auto;padding: 20px;font-size: 13px;margin-bottom:100px;border-radius: 6px;">';
                      $content  .= '<p style="font-size: 12px;"><strong>Dear </strong><br> &nbsp;  '.$dataArray['name'].',</p>';
                      $content  .= '<p>Now, you have made a well informed choice. You have chosen to fulfil your need and dreams with a quick and convenient investment solution and Grow your investment beyond the other Market.</p>';

                      $content  .= '<p>Today, we have taken this opportunity to welcome you into the EARNING VISTA family.</p>';
                      $content .='<p>Your Credentials are :</p>
                                    <p><strong>Username -</strong> '.$dataArray['member_id'].'</p>
                                    <p><strong>Password -</strong> '.$pass.'</p>
                                    <p><strong>Transaction Password -</strong> '.$tarnsactionpass.'<p>';

                      $content  .= '<p style="font-size: 10px;margin-bottom:50px;">This is an auto generated mail, there is no need to reply.</p>';
                      $content  .= '<strong>';
                      $content  .= '<address style="line-height: 10px;font-size: 12px;">';
                      $content  .= '<p><strong>Warm Regards,</strong></p>';
                      $content  .= '<p>EARN VISTA</p>';
                      $content  .= '<p>www.earningvista.com</p>';
                      $content  .= '<p>For more info - info@earningvista.com</p>';
                      $content  .= '</address>';
                      $content  .= '</strong>';
                      $content  .= '</div>';
                      $content  .= '</table>';
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


   public function main_now_get()
   {
        $content = '';
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPDebug = 1;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;
        $mail->Host = "smtpout.secureserver.net";
        $mail->Username = "nonreply@earningvista.com";
        $mail->Password = "n@123456!";
        $mail->IsHTML(true);
        // $mail->AddAddress($dataArray['email_id'],$dataArray['name']);
        $mail->AddAddress("nkkoiri111@gmail.com","Nitai Koiri");
        $mail->SetFrom("info@cryptotrado.com", "crypto trado");
        $mail->Subject = "WELCOME LETTER";
          $content  = '<table style="width:100%;background: #52dcd3;font-family: system-ui;">';
          $content .= '<div style="text-align:center;margin-top:100px;"><img style="width:200px;height:50px;" src="https://cryptotrado.com/client/images/crypto-trade-portal-logo-01.png"></div>';
          $content  .= '<div style="width:400px; background: white;color:black; margin:10px auto;padding: 20px;font-size: 13px;margin-bottom:100px;border-radius: 6px;">';
          // $content  .= '<p style="font-size: 12px;"><strong>Dear </strong><br> &nbsp;  '.$dataArray['name'].',</p>';
          $content  .= '<p style="font-weight: 500;background:#8383f9;width: 254px;color:white;">Your KYC has been submitted successfully. </p>';
          $content  .= '<p>Now, you have made a well informed choice. You have chosen to fulfil your need and dreams with a quick and convenient investment solution and Grow your investment beyond the other Market.</p>';

          $content  .= '<p>Today, we have taken this opportunity to welcome you into the EARNING VISTA family.</p>';
          // $content .='<p>Your Credentials are :</p>
          //               <p><strong>Username -</strong> '.$dataArray['member_id'].'</p>
          //               <p><strong>Password -</strong> '.$pass.'</p>
          //               <p><strong>Transaction Password -</strong> '.$tarnsactionpass.'<p>';

          $content  .= '<p style="font-size: 10px;margin-bottom:50px;">This is an auto generated mail, there is no need to reply.</p>';
          $content  .= '<strong>';
          $content  .= '<address style="line-height: 10px;font-size: 12px;">';
          $content  .= '<p><strong>Warm Regards,</strong></p>';
          $content  .= '<p>EARN VISTA</p>';
          $content  .= '<p>www.earningvista.com</p>';
          $content  .= '<p>For more info - info@earningvista.com</p>';
          $content  .= '</address>';
          $content  .= '</strong>';
          $content  .= '</div>';
          $content  .= '</table>';
          $mail->MsgHTML($content);
          print_r($mail->Send());
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


    public function get_direct_member_post()
    {
        if($this->input->post('sponser_id',true)=='')
        {
            $this->response(['status'=>false,'data'=>[],'msg'=>'sponser_id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
        }else
        {
            $result = $this->Member_model->get_direct_members($this->input->post('sponser_id',true));
            $this->response(['status'=>true,'data'=>$result,'msg'=>'Successfully !','response_code' => REST_Controller::HTTP_OK]);
        }
    }



    


}
?>