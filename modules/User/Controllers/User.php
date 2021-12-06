<?php
namespace Modules\User\Controllers;

class User extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	public function index()
	{
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view"); 		
		$data = [];
	
		check_exp_time();
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view");
		define("has_insert", has_privilege_check($module, '_insert'));
		define("has_approval", has_privilege_check($module, '_approval'));
		define("has_edit", has_privilege_check($module, '_update'));
		define("has_delete", has_privilege_check($module, '_delete'));
		define("has_print", has_privilege_check($module, '_printpdf'));


		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];

		$data['page_title'] = "Users";
		$data['page_subtitle'] = "Users Page";		
		return view('Modules\User\Views\index',$data);
	}

	public function list_data() {

		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "")?$this->request->getPost('search'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
		// PULL data from API
			$response = $this->client->request('GET','users/allUser',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'offset' => $offset,
					'limit'	=> $limit
				]
			]);

		$result = json_decode($response->getBody()->getContents(), true);
		print_r($result); die;
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => @$result['data']['count'],
            "recordsFiltered" => @$result['data']['count'],
            "data" => array()
        );
        
		$no = ($offset !=0)?$offset+1 :1;
		foreach ($result['data']['datas'] as $k=>$v) {
				
			$btn_list="";
            $record = array(); 

            $record[] = $no;
            $record[] = $v['username'];
            $record[] = $v['fullname'];
            $record[] = $v['email'];
            $record[] = $v['groups']['group_name'];
            $uid = $v['user_id'];
			$btn_list .='<a href="'.site_url("users/view/".$uid).'" id="" class="btn btn-xs btn-primary btn-table" data-users="">view</a>&nbsp;';
			$btn_list .='<a href="'.site_url('users/edit/'.$uid).'" id="editUser" class="btn btn-xs btn-success btn-table">edit</a>&nbsp;';

			if($v['is_block']=="y"):
			$btn_list .= '<a href="#" class="btn btn-xs btn-success" id="sendEmail" data-uid="'.$uid.'">Send email</a>&nbsp;';
			else:
				$btn_list .= '<span class="btn btn-xs disabled">Activated</span>';
			endif;
		
            $record[] = '<div>'.$btn_list.'</div>';				
            $no++;
            $output['data'][] = $record;

        } 
        echo json_encode($output);
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
				// echo $result["success"];die();
				if(($result["success"]=="Failled")&&($result["message"]!="user created"))
				{
					$msg = array();
					$i=1;
					foreach($result["message"] as $results) {
						foreach($results as $row){
							$msg[] = "- ".$row."<br>";
						}
					}
					$data["status"] = "failled";
					$data["message"] = $msg;
				} else {
					$data['status'] = "success";
					$data['message'] = $result["message"];
				}

				session()->setFlashdata('sukses','Berhasil menyimpan data.');
				echo json_encode($data);die();

				// echo json_encode($data);die();
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
				"userId"	=> $_POST['userId'],
				"groupId"	=> $_POST["group_id"],
				"fullName"	=> $_POST["fullname"],
				"username"	=> $_POST["username"],
				"password"	=> $_POST["password"],
				"email"		=> $_POST["email"],
				"isBlock"	=> $_POST["isblock"],
				"prcode"	=> $_POST["prcode"]
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

				$response = $this->client->request('PUT','users/auth/upateDataUser',[
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

				session()->setFlashdata('sukses','Success, New User Updated.');
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
		$data['act'] = 'edit';
		$data['data'] = (isset($result['data']) ? $result['data'] : '');
		$data['group'] = $this->group_dropdown($result['data']['group_id']);
		$data['principal_dropdown'] = principal_dropdown($result['data']['prcode']);
		$data['debitur_dropdown'] = $this->debitur_dropdown($result['data']['prcode']);
		// dd($result['data']);
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
			$principal = $result['data']['datas'];
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

	public function ajax_debitur_dropdown($selected="")
	{
		$data = [];
		if ($this->request->isAJAX()) 
		{
			$response = $this->client->request('GET','debiturs/getAllData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
			]);

			$result = json_decode($response->getBody()->getContents(),true);	
			$debitur = $result['data']['datas'];
			$option = "";
			$option .= '<select name="prcode" id="prcode" class="select-debitur">';
			$option .= '<option vlue="">-select-</option>';
			foreach($debitur as $cu) {
				$option .= "<option value='".$cu['cucode'] ."'". ((isset($selected) && $selected==$cu['cucode']) ? ' selected' : '').">".$cu['cuname']."</option>";
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

	function debitur_dropdown($selected="")
{
	$api = api_connect();
	$response = $api->request('GET','debiturs/getAllData',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
	]);

	$result = json_decode($response->getBody()->getContents(),true);	
	$debitur = $result['data']['datas'];
	$option = "";
	$option .= '<select name="prcode" id="prcode" class="select-debitur">';
	$option .= '<option vslue="">-select-</option>';
	foreach($debitur as $cu) {
		$option .= "<option value='".$cu['cucode'] ."'". ((isset($selected) && $selected==$cu['cucode']) ? ' selected' : '').">".$cu['cuname']."</option>";
	}
	$option .="</select>";
	return $option; 
	die();			
}
}