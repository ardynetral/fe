<?php
namespace Modules\DamageType\Controllers;

use GuzzleHttp\Client;
class DamageType extends \CodeIgniter\Controller
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
		$response = $this->client->request('GET','damagetype/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json'=>[
				'start'=>0,
				'rows'=>100
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$data['damagetype'] = isset($result['data'])?$result['data']:"";
		return view('Modules\DamageType\Views\index',$data);
	}

	public function view($dycode)
	{
		$response = $this->client->request('GET','damagetype/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'dyCode' => $dycode,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['damagetype'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Damagetype\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$dycode = $this->request->getPost('dycode');
	    	$dydesc = $this->request->getPost('dydesc');

			$validate = $this->validate([
	            'dycode' 	=> 'required',
	            'dydesc'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','damagetype/create',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'dyCode' =>$dycode,
						'dyDesc' =>$dydesc,
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				$dt_err="";

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				$msg = $result['data'];
				$dataFlash['sukses'] = [
					'dycode'	=> $msg['dycode'],
					'dydesc'		=> $msg['dydesc']
				];

				session()->setFlashdata('sukses','Success, Damage Type Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
		return view('Modules\Damagetype\Views\add',$data);		
	}	

	public function edit($dycode)
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$dycode = $this->request->getPost('dycode');
	    	$dydesc = $this->request->getPost('dydesc');

			$validate = $this->validate([
	            'dycode' 	=> 'required',
	            'dydesc'  => 'required'
	        ]);			

		    if ($this->request->getMethod() === 'post' && $validate)
		    {
				$response = $this->client->request('POST','damagetype/update',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'json' => [
						'dyCode' => $dycode,
						'dyDesc' => $dydesc
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				$dt_err="";

				if(isset($result['errors']))
				{
					foreach($result['errors'] as $err) {
						foreach($err as $er) {
							$dt_err .= $er ."<br>";	
						}
					}

					$data['message'] = $dt_err;
					echo json_encode($data);die();				
				}

				$msg = $result['message'];

				session()->setFlashdata('sukses', $msg);
				$data['message'] = "success";
				echo json_encode($data);die();
			}
			else
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();					
			}
		} 

		$response = $this->client->request('GET','damagetype/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'dyCode' => $dycode,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['damagetype'] = $result['data'];
		return view('Modules\DamageType\Views\edit',$data);
	}	

	public function delete($dycode)
	{
		$response = $this->client->request('DELETE','damagetype/delete',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'dyCode' => $dycode
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$success = $result['success'];
		if($success){
			session()->setFlashdata('sukses','Success, Damage Type : <b>'.$dycode.'<b> Deleted.');
			return redirect()->to('damagetype');
		}
		// echo json_encode($data);die();
		// dd($data['ctype']);
		// return view('Modules\ContainerType\Views\index',$data);
	}	

}