<?php
namespace Modules\Dailymovementout\Controllers;

class Dailymovementout extends \CodeIgniter\Controller
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
		return view('Modules\Dailymovementout\Views\add',$data);		
	}

	public function view($code)
	{
		$response = $this->client->request('GET','principals/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'id' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Dailymovementout\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$prcode = $this->request->getPost('prcode');
	    	$cucode = $this->request->getPost('cucode');
	    	$cncode = $this->request->getPost('cncode');
	    	$prname = $this->request->getPost('prname');
	    	$prremark = $this->request->getPost('prremark');
	    	$praddr = $this->request->getPost('praddr');

			$validate = $this->validate([
	            'prcode' 	=> 'required',
	            'cucode'  => 'required',
	            'cncode'  => 'required',
	            'prname'  => 'required',
	            'prremark'  => 'required',
	            'praddr'  => 'required',
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','principals/create',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'prCode' =>$prcode,
						'dset' => [
							'cucode' =>$cucode,
							'cncode' =>$cncode,
							'prname' =>$prname,
							'prremark' =>$prremark,
							'praddr' =>$praddr,
						]
					]

				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Principal Saved.');
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
		return view('Modules\Dailymovementout\Views\add',$data);		
	}	

	public function edit($code)
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$prcode = $this->request->getPost('prcode');
	    	$cucode = $this->request->getPost('cucode');
	    	$cncode = $this->request->getPost('cncode');
	    	$prname = $this->request->getPost('prname');
	    	$prremark = $this->request->getPost('prremark');
	    	$praddr = $this->request->getPost('praddr');

			$validate = $this->validate([
	            'prcode' 	=> 'required',
	            'cucode'  => 'required',
	            'cncode'  => 'required',
	            'prname'  => 'required',
	            'prremark'  => 'required',
	            'praddr'  => 'required',
	        ]);				
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','principals/update',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'id' =>$prcode,
						'dset' => [
							'cucode' =>$cucode,
							'cncode' =>$cncode,
							'prname' =>$prname,
							'prremark' =>$prremark,
							'praddr' =>$praddr,
						]
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Principal Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		

		$response = $this->client->request('GET','principals/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'id' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		$data['country_dropdown'] = $this->country_dropdown($result['data']['cncode']);
		return view('Modules\Dailymovementout\Views\edit',$data);		
	}	

	public function delete($code)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','principals/delete',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'id' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Principal '.$code.' Deleted.');
			$data['message'] = "success";
			echo json_encode($data);die();
		}
	}		

	public function ajax_country() 
	{
		$response = $this->client->request('GET','country/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'cityId' => $cccode,
			]
		]);
		$result = json_decode($response->getBody()->getContents(), true);	

		if($this->request->isAJAX()) {
			echo json_encode($result['data']);
			die();		
		}

		return $result['data'];
	}

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