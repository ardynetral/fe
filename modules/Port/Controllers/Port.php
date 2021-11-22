<?php
namespace Modules\Port\Controllers;

class Port extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	public function index()
	{
		$data = [];
		$response = $this->client->request('GET','ports/list',[
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
		$data['data'] = isset($result['data']['datas'])?$result['data']['datas']:"";
		return view('Modules\Port\Views\index',$data);
	}

	public function view($code)
	{
		$response = $this->client->request('GET','ports/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'poport' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Port\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$code = $this->request->getPost('code');
	    	$poid = $this->request->getPost('poid');
	    	$cncode = $this->request->getPost('cncode');
	    	$desc = $this->request->getPost('desc');

			$validate = $this->validate([
	            'code' 	=> 'required',
	            'poid'  => 'required',
	            'cncode'  => 'required',
	            'desc'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','ports/create',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'poport' =>$code,
						'poid' =>$poid,
						'cncode' =>$cncode,
						'podesc' =>$desc,
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Port Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
		$data['country_dropdown'] = $this->country_dropdown($selected='');
		return view('Modules\Port\Views\add',$data);		
	}	

	public function edit($code)
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$code = $this->request->getPost('code');
	    	$poid = $this->request->getPost('poid');
	    	$cncode = $this->request->getPost('cncode');
	    	$desc = $this->request->getPost('desc');

			$validate = $this->validate([
	            'code' 	=> 'required',
	            'poid'  => 'required',
	            'cncode'  => 'required',
	            'desc'  => 'required'
	        ]);					
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','ports/update',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'poport' =>$code,
						'poid' =>$poid,
						'cncode' =>$cncode,
						'podesc' =>$desc,
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Port Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		

		$response = $this->client->request('GET','ports/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'poport' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		$data['country_dropdown'] = $this->country_dropdown($result['data']['cncode']);
		return view('Modules\Port\Views\edit',$data);		
	}	

	public function delete($code)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','ports/delete',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'poport' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Port Code '.$code.' Deleted.');
			$data['message'] = "success";
			echo json_encode($data);die();
		}
	}

	// public function ajax_country() 
	// {
	// 	$response = $this->client->request('GET','country/listOne',[
	// 		'headers' => [
	// 			'Accept' => 'application/json',
	// 			'Authorization' => session()->get('login_token')
	// 		],
	// 		'form_params' => [
	// 			'cityId' => $cccode,
	// 		]
	// 	]);
	// 	$result = json_decode($response->getBody()->getContents(), true);	

	// 	if($this->request->isAJAX()) {
	// 		echo json_encode($result['data']);
	// 		die();		
	// 	}

	// 	return $result['data'];
	// }

	public function country_dropdown($selected="")
	{
		$data = [];
		$response = $this->client->request('GET','countries/list',[
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
		$res = $result['data']['datas'];
		$option = "";
		$option .= '<select name="cncode" id="cncode" class="select-cncode">';
		$option .= '<option value="">-select-</option>';
		foreach($res as $r) {
			$option .= "<option value='".$r['cncode'] ."'". ((isset($selected) && $selected==$r['cncode']) ? ' selected' : '').">".$r['cndesc']."</option>"; 
		}
		$option .="</select>";
		return $option; 
		die();
	}		
}