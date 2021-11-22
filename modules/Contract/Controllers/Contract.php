<?php
namespace Modules\Contract\Controllers;

class Contract extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	public function index()
	{
		check_exp_time();
		$data = [];
		$response = $this->client->request('GET','contracts/list',[
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
		$data['data'] = isset($result['data']['datas'])?$result['data']['datas']:"";
		return view('Modules\Contract\Views\index',$data);
	}

	public function view($code)
	{
		// idContract => prcode

		check_exp_time();
		$response = $this->client->request('GET','contracts/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'idContract' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		$data['act'] = "view";
		return view('Modules\Contract\Views\add',$data);
	}	

	public function add()
	{
		check_exp_time();
		$data = [];

		if ($this->request->isAJAX()) 
		{
			// $form_params = [
		 //    	"cono" 		=> $this->request->getPost('cono'),
		 //    	"prcode" 	=> $this->request->getPost('prcode'),
			// ];

			// $validate = $this->validate([
	  //           'cono' 	=> 'required',
	  //           'prcode'  => 'required'
	  //       ]);			
		    if ($this->request->getMethod() === 'post')
		    {
				$response = $this->client->request('POST','contracts/create',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $_POST
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Contract Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = "Form Error";
		    	echo json_encode($data);die();			
			}			
		}		
		// $data['country_dropdown'] = $this->country_dropdown($selected='');
		$data['act'] = "add";
		return view('Modules\Contract\Views\add',$data);		
	}	

	public function edit($code)
	{
		check_exp_time();
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	// $cityId/ = $this->request->getPost('cityId/');
	    	$name = $this->request->getPost('name');
	    	$cncode = $this->request->getPost('cncode');

			$validate = $this->validate([
	            'name' 	=> 'required',
	            'cncode'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','contract/update',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'cityId' =>$code,
						'name' =>$name,
						'cncode' =>$cncode,
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, City Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		

		$response = $this->client->request('GET','contract/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'cityId' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		$data['country_dropdown'] = $this->country_dropdown($result['data']['cncode']);
		return view('Modules\Contract\Views\edit',$data);		
	}	

	public function delete($code)
	{
		check_exp_time();
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','contracts/delete',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'idContract' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Contract Deleted.');
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
		$res = $result['data'];
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