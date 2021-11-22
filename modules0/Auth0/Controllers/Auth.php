<?php
namespace Modules\Auth\Controllers;

// use App\Config\Database;

use GuzzleHttp\Client;

class Auth extends \CodeIgniter\Controller
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


	/*
	DECRIPT JWT 
	$token = "";
	print_r(json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1])))));
	*/

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
		return view('Modules\Auth\Views\forgot_password');
	}

	public function activate() 
	{
		$data = [];
		$activate_code = "5da59d3ca26adbfc33d8c15e8c2c1693825e3e12c49a1e4b17824f33a823788b43ed0aa99fe54b2eb54aa689a087e2adab202c4ef86e6d76d4b1de900a26e5db4fac870076dcad9bff017d65f01cc7875488b6bc292f81659d5dde9e5dacf66448c20b4d9c67fbe6bdd9c714baff";

		$response = $this->client->request('GET','users/auth/activate',[
			'headers' => [
				'Accept' => 'application/json'
			],
			'query' => [
				'alg_wc_ev_verify_email' => $activate_code
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		if(isset($result['status']) && ($result['status']=="Failled"))
		{
			$data['message'] = $result['message'];
			echo json_encode($data);die();				
		}
		$data['message'] = $result['message'];
		
		return view('Modules\Auth\Views\activated_success',$data);			

	}

	// by user
	public function change_password()
	{
		$data = [];
		return view('Modules\Auth\Views\change_password',$data);	
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

	// extract token login
	public function get_token_item($token)
	{
		$isitoken = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $result['data'])[1]))),true);

		// ambil expiration time
		$exp = date('m/d/Y H:i:s', $isitoken['exp']);	die();	
		return $exp;
	}


}
