<?php 
	require APPPATH . 'libraries/REST_Controller.php';

	class Withdrawal extends REST_Controller
	{
		public function __construct()
		{
			parent:: __construct();
			$this->load->model('Withdrawal_model');
		}

		public function getTotalFund_post()
		{	
			try {
				$member_id = $this->input->post('member_id',true)!=""?$this->input->post('member_id',true):"";
				$result = $this->Withdrawal_model->get_memberAvailable_fund_m($member_id);
				$this->response(['status'=>true,'data'=>$result,'msg'=>'successfully Fetched!','response_code' => REST_Controller::HTTP_OK]);
			} 
			catch (Exception $e) {
				$this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);		
			}
		}

		public function getFundRequestedList_post()
		{	
			try {
				$member_id = $this->input->post('member_id',true)!=""?$this->input->post('member_id',true):"";
				$status = $this->input->post('current_status',true)!=""?$this->input->post('current_status',true):"";
				
				$result = $this->Withdrawal_model->get_member_request_fundList_m($member_id,$status);
				$this->response(['status'=>true,'data'=>$result,'msg'=>'successfully Fetched!','response_code' => REST_Controller::HTTP_OK]);
			} 
			catch (Exception $e) {
				$this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);		
			}
		}

		public function withdrawalRequest_post()
		{
			if($this->input->post('member_id',true)=='')
	        {
	            $this->response(['status'=>false,'data'=>[],'msg'=>'member id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        }
			elseif($this->input->post('fund',true)=='')
	        {
	            $this->response(['status'=>false,'data'=>[],'msg'=>'fund required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        }elseif($this->input->post('c_by',true)=='')
	        {
	            $this->response(['status'=>false,'data'=>[],'msg'=>'c_by required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        }else{
	        	$checkFund = $this->Withdrawal_model->get_memberAvailable_fund_m($this->input->post('member_id',true));
	        	$amount = $checkFund[0]['total_fund'];
	        	try {
	        		if($this->input->post('fund',true) <= $amount)
	        		{
	        			$dataArray = [];
	        			$dataArray['member_id'] = $this->input->post('member_id',true);
	        			$dataArray['fund'] = $this->input->post('fund',true);
	        			$dataArray['current_status'] = 'requested';
	        			$dataArray['c_by'] = 1;
	        			$dataArray['c_date'] = date("Y-m-d, H:i:s");
	        			$dataArray['status'] = 1;
	        			$this->Withdrawal_model->insertRequest($dataArray);

	        			$this->response(['status'=>true,'data'=>[],'msg'=>'Successfully Requested','response_code' => REST_Controller::HTTP_OK]);
	        			// print_r($dataArray); die;
	        		}
	        		else{
	        			$this->response(['status'=>false,'data'=>[],'msg'=>'Amount is greater than available balance!','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        		}
	        	} catch (Exception $e) {
	        		$this->response(['status'=>false,'data'=>[],'msg'=>'Something went wrong!','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        	}
	        	// print_r($amount); die;
	        }
		}

		public function approvedWithdrawalRequest_post()
		{
			if($this->input->post('member_id',true)=='')
	        {
	            $this->response(['status'=>false,'data'=>[],'msg'=>'member id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        }
			elseif($this->input->post('withdraw_id',true)=='')
	        {
	            $this->response(['status'=>false,'data'=>[],'msg'=>'withdraw id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        }elseif($this->input->post('fund',true)=='')
	        {
	            $this->response(['status'=>false,'data'=>[],'msg'=>'fund required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        }elseif($this->input->post('d_by',true)=='')
	        {
	            $this->response(['status'=>false,'data'=>[],'msg'=>'d_by required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        }
	        else{
	        	$checkFund = $this->Withdrawal_model->get_memberAvailable_fund_m($this->input->post('member_id',true));
	        	// print_r($checkFund); die;
	        	$amount = $checkFund[0]['total_fund'];
	        	try {
	        		if($this->input->post('fund',true) <= $amount)
	        		{
	        			$substract_amt = ($amount - $this->input->post('fund',true));

	        			$this->Withdrawal_model->update_wallet($this->input->post('member_id',true),$substract_amt);

	        			$array1 = [];
	        			$array1['current_status'] = 'approved';
	        			$array1['d_by'] = $this->input->post('d_by',true);
	        			$array1['d_date'] = date("Y-m-d, H:i:s");
	        			$this->Withdrawal_model->updateRequest($this->input->post('withdraw_id',true),$array1);

	        			$array2 = [];
	        			$array2['member_id'] = $this->input->post('member_id',true);
	        			$array2['transaction_type'] = "OUT";
	        			$array2['fund'] = $this->input->post('fund',true);
	        			$array2['reference_no'] = $this->input->post('withdraw_id',true);
	        			$array2['c_by'] = $this->input->post('c_by',true);
	        			$array2['c_date'] = date("Y-m-d, H:i:s");
	        			$array2['status'] = 1;
	        			$this->Withdrawal_model->insertFundLog($array2);

	        			$this->response(['status'=>true,'data'=>[],'msg'=>'Successfully Approved','response_code' => REST_Controller::HTTP_OK]);
	        		}
	        		else{
	        			$this->response(['status'=>false,'data'=>[],'msg'=>'Amount is greater than available balance!','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        		}
	        	} catch (Exception $e) {
	        		$this->response(['status'=>false,'data'=>[],'msg'=>'Something went wrong!','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        	}
	        }
		}

		public function rejectWithdrawalRequest_post()
		{
			if($this->input->post('member_id',true)=='')
	        {
	            $this->response(['status'=>false,'data'=>[],'msg'=>'member id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        }elseif($this->input->post('withdraw_id',true)=='')
	        {
	        	$this->response(['status'=>false,'data'=>[],'msg'=>'Withdrawal id required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        }elseif($this->input->post('d_by',true)=='')
	        {
	        	$this->response(['status'=>false,'data'=>[],'msg'=>'d_by required !','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        }else{
	        	try {
	        		$rjctArray = [];
	        		// $rjctArray['status'] = 0;
	        		$rjctArray['current_status'] = 'rejected';
	        		$rjctArray['d_by'] = $this->input->post('d_by',true);
	        		$rjctArray['d_date'] = date("Y-m-d, H:i:s");
	        		$this->Withdrawal_model->rejectFund_m($this->input->post('withdraw_id',true),$rjctArray);

	        		$this->response(['status'=>true,'data'=>[],'msg'=>'Successfully Rejected','response_code' => REST_Controller::HTTP_OK]);

	        	} catch (Exception $e) {
	        		$this->response(['status'=>false,'data'=>[],'msg'=>'Something went wrong!','response_code' => REST_Controller::HTTP_BAD_REQUEST]);
	        	}
	        }
		}
	}
?>