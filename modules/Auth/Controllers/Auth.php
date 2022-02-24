<?php
namespace Modules\Auth\Controllers;

class Auth extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	public function login()
	{
		if(session()->get('logged_in')==true) {
			return redirect()->to(site_url('dashboard'));
		}

		$data=[];
		return view('Modules\Auth\Views\login',$data);
	}

	public function set_login()
	{
		$username = $this->request->getPost("username");
		$password = $this->request->getPost("password");

		$validate = $this->validate([
            'username' => 'required',
            'password'  => 'required',
        ]);

	    if ($this->request->getMethod() === 'post' && $validate)
	    {
			$body = [
				"username" => $username,
				"password" => $password
			];

			$response = $this->client->request('POST','users/auth/signin',[
				"headers" => [
					"Accept" => "application/json",
				],
				"form_params" => $body
			]);

			$result = json_decode($response->getBody(),true);

			if(isset($result['status']) && $result['status']=="Failled")
			{
				$data['message'] = $result['message'];
				return view('Modules\Auth\Views\login',$data);
			}
			
			$sess_data = [
		        'username'		=> $username,
		        'login_token'   => "Bearer " . $result['data'],
			'exp_time'		=> date("Y-m-d H:i:s", strtotime('now +50 minutes')),
		        'logged_in' 	=> TRUE
			];
			session()->set($sess_data);	
			return redirect()->to(site_url('dashboard'));
	    }
	    else
	    {
	    	$data['validasi'] = \Config\Services::validation()->listErrors();
			return view('Modules\Auth\Views\login',$data);
	    }
	}

	// public function register()
	// {
	// 	return view('Modules\Auth\Views\register');
	// }	

	// Register By Admin
	public function create_user()
	{		
		$data=[];
		return view('Modules\Auth\Views\admin_create_user',$data);
	}

	public function set_user()
	{
		// if ($this->request->is_ajax_request()) {

		$validate = $this->validate([
            'username' 	=> 'required',
            'password'  => 'required',
            'email'  	=> 'required',
            'group_id'  => 'required',
        ]);

	    if ($this->request->getMethod() === 'post' && $validate)
	    {
			$body = [
				"groupId"	=> $_POST["group_id"],
				"fullName"	=> $_POST["first_name"],
				"username"	=> $_POST["username"],
				"password"	=> $_POST["password"],
				"email"		=> $_POST["email"],
			];

			$response = $this->client->request('POST','users/auth/signup',[
				"headers" => [
			        'Accept' => 'application/json',
				],
				"form_params" => $body
			]);

			$result = json_decode($response->getBody()->getContents(),true);
			$dt_err="";
			if(isset($result['errors']))
			{
				foreach($result['errors'] as $err) {
					foreach($err as $er) {
						$dt_err .= $er ."<br>";	
					}
				}
				$data['message'] = $dt_err;
				// return view('Modules\Auth\Views\admin_create_user',$data);
				echo json_encode($data);die();
			}

			$msg = $result['success'];
			$dataFlash['sukses'] = [
				'username'	=> $msg['username'],
				'email'		=> $msg['email']
			];


			session()->setFlashdata('sukses','New user has success created.');
			$data['message'] = "success";
			echo json_encode($data);die();
			// return redirect()->to(site_url('dashboard'));  	
	    }
	    else
	    {
	    	$data['message'] = \Config\Services::validation()->listErrors();
	    	echo json_encode($data);die();
			// return view('Modules\Auth\Views\admin_create_user',$data);	    	
	    }

		// } //end json_req

	}

	public function forgot_password()
	{
		if($this->request->isAjax()) {
			// /auth/emailChangePassword
			$response = $this->client->request('POST','users/auth/emailChangePassword',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'email' => $this->request->getPost('email'),
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

		return view('Modules\Auth\Views\forgot_password');
	}

	// by user
	public function change_password()
	{
		$data = [];
		return view('Modules\Auth\Views\change_password',$data);	
	}

	function update_new_password() {

		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];

		$validate = $this->validate([
            'username' 	=> 'required',
            'password'  => 'required',
            'email'  	=> 'required'
        ]);

		if ($this->request->getMethod() === 'post' && $validate) {
			$response = $this->client->request('PUT','users/auth/changePassword',[
				'headers' => [
					'Accept' => 'application/json'
				],
				'form_params' => [
					'username' => $username,
					'userEmail' => $email,
					'newPassword' => $password
				]
			]);		

			$result = json_decode($response->getBody(), true);

			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['status'];
				session()->setFlashdata('msg',$result['status']['Failled']);
				return redirect('change_password');
				// echo json_encode($data);die();				
			}

			$data['message'] = 'success';
			$data['message_data'] = $result['message'];
			session()->setFlashdata('msg',$result['message']);
			return redirect('login');		
		}
	}
	
	public function user_profile()
	{
		$response = $this->client->request('GET','users/profile',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody(), true);
		$data['user'] = $result;
		return view('Modules\Auth\Views\user_profile',$data);
	}

	public function logout()
	{
		session()->destroy();
		return redirect()->to(site_url());
	}

	public function is_timeout()
	{
		if($this->request->isAjax()) 
		{
			$timeout = false;
			if(session()->get('login_token')!="") {
				// $token = get_token_item();
				// $exp = $token['exp'];
				// $date = new DateTime();
				// $date->setTimestamp($exp);
				// $token_exp = $date->format('Y-m-d H:i:s');
				$exp_time = session()->get('exp_time');
				$now = date("Y-m-d H:i:s");
				 if($now>=$exp_time) {
					$timeout = true;
				 }	
			}
			echo json_encode($timeout);die();
		}
	}


}
