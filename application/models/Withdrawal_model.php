<?php 
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Withdrawal_model extends CI_Model
	{
		
		public function __construct()
		{
			parent:: __construct();
		}

		public function get_memberAvailable_fund_m($member_id)
		{
			// echo $member_id; die;
			if($member_id !='')
				$this->db->where('status',1);
			return $this->db->select('id,member_id,total_fund')->from('tbl_user_wallets')->where(['member_id'=>$member_id])->get()->result_array();
		}

		public function get_member_request_fundList_m($member_id,$status)
		{
			if($member_id !='')
				$this->db->where(['member_id'=>$member_id]);
			if($status !='')
				$this->db->where(['current_status'=>$status]);
			return $this->db->select('*')->from('tbl_user_withdrawal_request')->order_by('id','desc')->where(['status'=>1])->get()->result_array();
		}

		public function insertRequest($dataArray)
		{
			$this->db->insert('tbl_user_withdrawal_request',$dataArray);
			$id = $this->db->insert_id();
			$witdrawId = "WTDR0".$id;
			$this->db->update('tbl_user_withdrawal_request',['withdraw_id' => $witdrawId],['id'=>$id]);
			return 200;
		}

		public function update_wallet($member_id,$fund)
		{
			$this->db->update('tbl_user_wallets',['total_fund'=>$fund],['member_id'=>$member_id]);
		}

		public function updateRequest($withdraw_id,$array1)
		{
			$this->db->update('tbl_user_withdrawal_request',$array1,['withdraw_id'=>$withdraw_id]);
		}

		public function insertFundLog($arrayData)
		{
			return $this->db->insert('tbl_user_wallets_histories',$arrayData);
		}

		public function rejectFund_m($withdraw_id,$rjctArray)
		{
			return $this->db->update('tbl_user_withdrawal_request',$rjctArray,['withdraw_id'=>$withdraw_id]);
		}
	}
?>