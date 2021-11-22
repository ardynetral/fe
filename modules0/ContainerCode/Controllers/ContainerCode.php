<?php
namespace Modules\ContainerCode\Controllers;

use GuzzleHttp\Client;
class ContainerCode extends \CodeIgniter\Controller
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

	public function index()
	{
		$data = [];
		$response = $this->client->request('GET','containercode/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			// 'json'=>[
			// 	'start'=>0,
			// 	'rows'=>10
			// ]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$data['ccode'] = isset($result['data'])?$result['data']:"";
		return view('Modules\ContainerCode\Views\index',$data);
	}

	public function view($cccode)
	{
		$response = $this->client->request('GET','containercode/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'ccCode' => $cccode,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['ccode'] = isset($result['data'])?$result['data']:"";
		return view('Modules\ContainerCode\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$cccode = $this->request->getPost('cccode');
	    	$ctcode = $this->request->getPost('ctype');
	    	$height = $this->request->getPost('height');
	    	$length = $this->request->getPost('length');
	    	$alias1 = $this->request->getPost('alias1');
	    	$alias2 = $this->request->getPost('alias2');

			$validate = $this->validate([
	            'cccode' 	=> 'required',
	            'ctype'  => 'required',
	            'height'  => 'required',
	            'length'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {
				$user = $this->get_admin();
				$response = $this->client->request('POST','containercode/create',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'ccCode' =>$cccode,
						'ctCode' =>$ctcode,
						'ccLength' =>$length,
						'ccHeight' =>$height,
						'ccAlias1' =>$alias1,
						'ccAlias2' =>$alias2,
						'idUser' =>$user['user_id']
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				$dt_err="";

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Container Code Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
		$data['ctype'] = $this->ctype_dropdown();
		return view('Modules\ContainerCode\Views\add',$data);		
	}	

	public function ctype_dropdown()
	{
		$data = [];
		$response = $this->client->request('GET','containertype/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$ctype = $result['data'];
		$option = "";
		$option .= '<select name="ctype" id="ctype" class="select-ctype">';
		foreach($ctype as $ct) {
			$option .= "<option value=".$ct['ctcode'].">".$ct['ctcode']."</option>"; 
		}
		$option .="</select>";
		return $option; 
		die();
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

	public function delete($cccode)
	{
		$response = $this->client->request('DELETE','containercode/delete',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'ccCode' => $cccode
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$success = $result['success'];
		if($success){
			session()->setFlashdata('sukses','Success, Container Type : <b>'.$ctcode.'<b> Deleted.');
			return $this->index();
		}
	}	
	public function get_admin()
	{
		//  [user_id] [username] [email]

		$response = $this->client->request('GET','users/profile',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody(), true);
		return $result;		
	}
}