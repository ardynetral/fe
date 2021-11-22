<?php
namespace Modules\User\Controllers;

use GuzzleHttp\Client;
class User extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = new Client([
			'base_uri'=>'http://202.157.185.83:4000/api/v1/',
			'timeout'=>0,
			'http_errors' => false
		]);
	}

	// public function index()
	// {
	// 	$data = [];
	// 	$offset=0;
	// 	$limit=10;
	// 	$response = $this->client->request('GET','users/allUser',[
	// 		'headers' => [
	// 			'Accept' => 'application/json',
	// 			'Authorization' => session()->get('login_token')
	// 		],
	// 		'query'=>[
	// 			'offset'=>$offset,
	// 			'limit'=>$limit
	// 		]
	// 	]);

	// 	$result = json_decode($response->getBody()->getContents(),true);	
	// 	$data['user'] = isset($result['data'])?$result['data']:"";
	// 	$data['total'] = count($result['data']);
	// 	return view('Modules\User\Views\index',$data);
	// }

	public function index()
	{
		$data = [];
	
		if ($this->request->isAJAX()) {
			$offset=0;
			$limit=100;			
			$response = $this->client->request('GET','users/allUser',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query'=>[
					'offset'=>$offset,
					'limit'=>$limit
				]
			]);

			$result = json_decode($response->getBody()->getContents(),true);	
			$data['user'] = isset($result['data'])?$result['data']:"";
		}		
		
		$response = $this->client->request('GET','users/allUser',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query'=>[
				'offset'=>0,
				'limit'=>100
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$data['user'] = isset($result['data'])?$result['data']:"";
		$data['total'] = count($result['data']);
		return view('Modules\User\Views\index',$data);
	}

	// admin only can view
	public function view($uid)
	{
		$response = $this->client->request('GET','users/auth/detailDataUser',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'userId' => $uid 
			]			
		]);

		$result = json_decode($response->getBody(), true);
		$data['data'] = (isset($result['data']) ? $result['data'] : '');
		return view('Modules\User\Views\view',$data);
	}	

	// all user can access
	public function user_profile()
	{
		$response = $this->client->request('GET','users/auth/detailDataUser',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody(), true);
		$data['user'] = $result;
		return view('Modules\User\Views\user_profile',$data);
	}

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
			$form_params = [
				"groupId"	=> $_POST["group_id"],
				"fullName"	=> $_POST["fullname"],
				"username"	=> $_POST["username"],
				"password"	=> $_POST["password"],
				"email"		=> $_POST["email"],
				"prcode"=>$_POST["prcode"]
			];

			$validate = $this->validate([
	            'fullname' 	=> 'required',
	            'username'  => 'required',
	            'password'  => 'required',
	            'repeat_password'  => 'required|matches[password]',
	            'email'  => 'required',
	            'group_id'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','users/auth/registers',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				$dt_err="";

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, New User Created.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}

		$data['group'] = $this->group_dropdown($selected="");
		// $data['principal'] = $this->principal_dropdown();
		return view('Modules\User\Views\add',$data);		
	}	

	public function edit($uid)
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
			$form_params = [
				"userId"	=> $uid,
				"groupId"	=> $_POST["group_id"],
				"fullName"	=> $_POST["fullname"],
				"username"	=> $_POST["username"],
				// "password"	=> $_POST["password"],
				"email"		=> $_POST["email"],
				"prcode"=>$_POST["prcode"]
			];

			$validate = $this->validate([
	            'fullname' 	=> 'required',
	            'username'  => 'required',
	            // 'password'  => 'required',
	            // 'repeat_password'  => 'required|matches[password]',
	            'email'  => 'required',
	            'group_id'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','users/auth/upateDataUser',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				$dt_err="";

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				// session()->setFlashdata('sukses','Success, New User Updated.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}


		$response = $this->client->request('GET','users/auth/detailDataUser',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'userId' => $uid 
			]			
		]);

		$result = json_decode($response->getBody(), true);
		$data['data'] = (isset($result['data']) ? $result['data'] : '');
		$data['group'] = $this->group_dropdown($result['data']['group_id']);
		$data['principal_dropdown'] = $this->pr_dropdown($result['data']['prcode']);

		return view('Modules\User\Views\edit',$data);		
	}	

	public function send_email($user_id)
	{
		$data = [];
		if ($this->request->isAJAX())
		{
			$response = $this->client->request('POST','users/auth/sendEmailActivated',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'userId' => (isset($user_id) ? $user_id : ''),
				]
			]);		

			$result = json_decode($response->getBody()->getContents(),true);
			
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			$data['message'] = 'success';
			$data['message_data'] = $result['message'];
			echo json_encode($data);die();			
		}
	
	}

	public function group_dropdown($selected="")
	{
		$data = [];
		$response = $this->client->request('GET','groups/allGroups',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$group = $result['data'];
		$option = "";
		$option .= '<select name="group_id" id="group_id" class="select-ctype">';
		$option .= '<option vlue="">-select-</option>';
		foreach($group as $gr) {
			if($gr['group_id']!=4) {
				$option .= "<option value='".$gr['group_id'] ."'". ((isset($selected) && $selected==$gr['group_id']) ? ' selected' : '').">".$gr['group_name']."</option>";
			}
		}
		$option .="</select>";
		return $option; 
		die();
	}

	public function ajax_pr_dropdown($selected="")
	{
		$data = [];
		if ($this->request->isAJAX()) 
		{
			$response = $this->client->request('GET','principals/list',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
			]);

			$result = json_decode($response->getBody()->getContents(),true);	
			$principal = $result['data'];
			$option = "";
			$option .= '<select name="prcode" id="prcode" class="select-pr">';
			$option .= '<option vlue="">-select-</option>';
			foreach($principal as $pr) {
				$option .= "<option value='".$pr['prcode'] ."'". ((isset($selected) && $selected==$pr['prcode']) ? ' selected' : '').">".$pr['prname']."</option>";
			}
			$option .="</select>";
			return $option; 
			die();					
		}

	}

	public function pr_dropdown($selected="")
	{
		$data = [];
		if ($this->request->isAJAX()) 
		{
			$response = $this->client->request('GET','principals/list',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
			]);

			$result = json_decode($response->getBody()->getContents(),true);	
			$principal = $result['data'];
			$option = "";
			$option .= '<select name="prcode" id="prcode" class="select-pr">';
			$option .= '<option vlue="">-select-</option>';
			foreach($principal as $pr) {
				$option .= "<option value='".$pr['prcode'] ."'". ((isset($selected) && $selected==$pr['prcode']) ? ' selected' : '').">".$pr['prname']."</option>";
			}
			$option .="</select>";
			return $option; 
			die();					
		}

	}	
	public function cek_cccode() 
	{
		if ($this->request->isAJAX()) {
			$cccode = $this->request->getPost('cccode');
			$response = $this->client->request('GET','containercode/listOne',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'idContainer' => $cccode,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	

			if(isset($result['data']['cccode'])) {
				$data['status'] = true;
				// $data['cccode'] = $result['data'];
			} else if($result['status']=="Failled"){
				$data['status'] = 'Failled';
				$data['message'] = $result['message'];
				// $data['cccode'] = "";
			} else {
				$data['status'] = false;
				$data['message'] = "code belum ada";
			}

			echo json_encode($data);
			die();
		}
	}
}