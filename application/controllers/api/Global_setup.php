<?php 
	require APPPATH . 'libraries/REST_Controller.php';

	class Global_setup extends REST_Controller
	{
		public function __construct()
		{
			parent:: __construct();
			$this->load->model('Global_setup_model');
		}

		public function getsetUp_data_post()
		{
			try{
	            $id = $this->input->post('id',true)!=""?$this->input->post('id',true):"";
	            $result = $this->Global_setup_model->getsetUpList($id);
	            $this->response(['status'=>true,'data'=>$result,'msg'=>'Successfully Fetched','response_code' => REST_Controller::HTTP_OK]);
	        }catch(Exception $e)
	        {
	            $this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !','response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
	        }
		}

		public function update_globalSetUp_post()
		{
			if($this->input->post('value',true)=='')
		    {
		        $this->response(['status'=>false,'data'=>[],'msg'=>'value required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
		    }elseif($this->input->post('effect_from',true)==''){
		    	$this->response(['status'=>false,'data'=>[],'msg'=>'Effect from required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
		    }elseif($this->input->post('effect_to',true)==''){
		    	$this->response(['status'=>false,'data'=>[],'msg'=>'Effect to required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
		    }elseif($this->input->post('key',true)==''){
		    	$this->response(['status'=>false,'data'=>[],'msg'=>'key required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
		    }elseif($this->input->post('id',true)==''){
		    	$this->response(['status'=>false,'data'=>[],'msg'=>'Id required !','response_code'=>REST_Controller::HTTP_BAD_REQUEST]);
		    }else{
		    	try {
		    		$array1 = [];
		    		$array1['status'] = 0;
		    		$array1['d_by'] = $this->input->post('d_by',true);
		    		$array1['d_date'] = date("Y-m-d H:i:s");
		    		$this->Global_setup_model->updateSetUp($this->input->post('id',true),$array1);

		    		$array2 = [];
		    		$array2['value'] = $this->input->post('value',true);
		    		$array2['effect_from'] = $this->input->post('effect_from',true);
		    		$array2['effect_to'] = $this->input->post('effect_to',true);
		    		$array2['key'] = $this->input->post('key',true);
		    		$array2['c_by'] = $this->input->post('d_by',true);
		    		$array2['c_date'] = date("Y-m-d H:i:s");
		    		$array2['status'] = 1;
		    		$this->Global_setup_model->insertSetUp($array2);

		    		$this->response(['status'=>true,'data'=>[],'msg'=>'successfully Updated','response_code' => REST_Controller::HTTP_OK]);

		    	} catch (Exception $e) {
		    		$this->response(['status'=>false,'data'=>[],'msg'=>'something went wrong !'.$e->getMessage(),'response_code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR]);
		    	}
		    }
		}
	}
?>