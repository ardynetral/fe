<?php
namespace Modules\Param\Controllers;

use GuzzleHttp\Client;
class Param extends \CodeIgniter\Controller
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
		$response = $this->client->request('GET','params/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json'=>[
				'start'=>0,
				'rows'=>10
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Param\Views\index',$data);
	}

	public function view($code)
	{
		$response = $this->client->request('GET','params/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'seqid' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Param\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$code = $this->request->getPost('code');
	    	$tab = $this->request->getPost('tab');
	    	$desc = $this->request->getPost('desc');
	    	$prm= $this->request->getPost('prm');

			$validate = $this->validate([
	            'code' 	=> 'required',
	            'tab'  => 'required',
	            'desc'  => 'required',
	            'prm'  => 'required',
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','params/create',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'paramId' =>$code,
						'desc' =>$desc,
						'tab' =>$tab,
						'prm' =>$prm,
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Param Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();
			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		

		return view('Modules\Param\Views\add',$data);		
	}	

	public function edit($seqid)
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	// $seqid = $this->request->getPost('seqid');
	    	$code = $this->request->getPost('code');
	    	$tab = $this->request->getPost('tab');
	    	$desc = $this->request->getPost('desc');
	    	$prm= $this->request->getPost('prm');

			$validate = $this->validate([
	            'code' 	=> 'required',
	            'tab'  => 'required',
	            'desc'  => 'required',
	            'prm'  => 'required',
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','params/update',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'seqid' =>(isset($seqid) ? $seqid : ''),
						'paramId' =>$code,
						'desc' =>$desc,
						'tab' =>$tab,
						'prm' =>$prm,
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Param Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		

		$response = $this->client->request('GET','params/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'seqid' => $seqid,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Param\Views\edit',$data);		
	}	

	public function delete($seqid)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','params/delete',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'seqid' => (isset($seqid) ? $seqid : ''),
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Param '.$seqid.' Deleted.');
			$data['message'] = "success";
			echo json_encode($data);die();
		}
	}		
}