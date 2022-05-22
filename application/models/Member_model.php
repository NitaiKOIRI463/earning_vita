<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');

   class Member_model extends CI_Model
   {
        public function __construct() 
        {
           parent::__construct();
           
        }
        public function verifyRegisterExist($mobile_no,$email_id)
        {
            $qry = $this->db->query("select count(id) as total from tbl_registration_master where (mobile_no = ? OR email_id = ?) and status = 1",[$mobile_no,$email_id]);
            $result = $qry->result_array();
            if(!empty($result))
            {
                return $result[0]['total'];
            }else
            {
                return 0;
            }
        }


         public function verifyRegisterMobileExist($mobile_no)
        {
            $qry = $this->db->query("select count(id) as total from tbl_registration_master where (mobile_no = ?) and status = 1",[$mobile_no]);
            $result = $qry->result_array();
            if(!empty($result))
            {
                return $result[0]['total'];
            }else
            {
                return 0;
            }
        }

        public function verifyRegisterEmailExist($email_id)
        {
            $qry = $this->db->query("select count(id) as total from tbl_registration_master where (email_id = ?) and status = 1",[$email_id]);
            $result = $qry->result_array();
            if(!empty($result))
            {
                return $result[0]['total'];
            }else
            {
                return 0;
            }
        }


        public function verifyRegisterMemberExist($member_id)
        {
            $qry = $this->db->query("select count(id) as total from tbl_registration_master where (member_id = ?) and status = 1",[$member_id]);
            $result = $qry->result_array();
            if(!empty($result))
            {
                return $result[0]['total'];
            }else
            {
                return 0;
            }
        }

        public function verifyRegisterMobileUpdateExist($mobile_no,$member_id)
        {
            $qry = $this->db->query("select count(id) as total from tbl_registration_master where member_id!= ? and mobile_no = ? and status = 1 ",[$member_id,$mobile_no]);
            $result = $qry->result_array();
            if(!empty($result))
            {
                return $result[0]['total'];
            }else
            {
                return 0;
            }
        }


        public function getNewId()
        {
            $lastid="";
            $this->db->select('member_id')->from('tbl_registration_master')->order_by('id','desc');
             $qry = $this->db->get()->result_array();
            if($qry[0]["member_id"]!="")
            {
               $lastid= "EV".substr($qry[0]["member_id"], 2)+1;
               
            }
            else{
                $lastid="EV"."1000";
            }
            return $lastid;
        }
        public function countSponser_Id($sponsor_id)
        {
        $qry = $this->db->query("select count(id) as total from tbl_registration_master where member_id = '".$sponsor_id."' and status = 1")->result_array();
            if(empty($qry[0]['total']))
            {
               return 0;
            }else
            {
               return $qry[0]['total'];
            }
        }

        public function countParentId_Id($sponsor_id)
        {
            $qry = $this->db->query("select member_id from tbl_registration_master where sponsor_id = '".$sponsor_id."' and side = 'L' and status = 1")->result_array();
                if(empty($qry[0]['member_id']))
                {
                   return $sponsor_id;
                }else
                {
                   $this->countSponser_Id($qry[0]['member_id']);
                }
        }

        public function addUserData($data)
        {
            $this->db->insert('tbl_registration_master',$data);
            $id = $this->db->insert_id();
           return $id;

        }
        
        public function verifyMobileExist($id,$mobile_no,$email_id)
        {
            return $this->db->select('id')->from('tbl_registration_master')->where(['id!='=>$id,'mobile_no'=>$mobile_no,'email_id'=>$email_id,'status'=>1])->get()->num_rows();
        }
        
   
       public function updateUser($data,$where)
       {
           return $this->db->update('tbl_registration_master',$data,$where);
           // echo $this->db->last_query();die;
       }

       public function getRegisterData($member_id,$page=1,$limit=10)
       {

            $base_url = base_url().'all-uploaded-img/img/';
            if($member_id!="")
                    $this->db->where('member_id',$member_id);
            $offset = $page*$limit - $limit; 
            
            return $this->db->select("member_id,parent_id,side,sponsor_id,title,name,gender,f_h_name,country,state,city,address,pin,mobile_no,email_id,kyc_status,registration_date,CONCAT('$base_url',photo) as photo,role_type,block_status,transaction_pin,password,country_code")
                ->from('tbl_registration_master')
                ->where(['status'=>1])
                ->limit($limit,$offset)->get()->result_array();
       }

       public function getRegisterDataCount($member_id)
       {
            if($member_id!="")
                    $this->db->where('member_id',$member_id);
            $result = $this->db->select("count(id) as total")
                ->from('tbl_registration_master')
                ->where(['status'=>1])
                ->get()->result_array();
            return $result[0]['total'];
       }


       public function modifyRegister($data,$where)
        {
            return $this->db->update('tbl_registration_master',$data,$where);
        }

       public function getparentId($sponser_id,$side)
        {
            $data = $this->db->select('member_id')->from('tbl_registration_master')->where(['parent_id'=>$sponser_id,'side'=>$side])->limit(1)->order_by('id','desc')->get()->result_array();
            if(!empty($data))
            {
                return $data[0]['member_id'];
            }else
            {
                return 200;
            }
        }

    }
 ?>