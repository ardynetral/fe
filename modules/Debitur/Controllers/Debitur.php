<?php
namespace Modules\Debitur\Controllers;

class Debitur extends \CodeIgniter\Controller
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
		$offset = 0;
		$limit=10;
		$response = $this->client->request('GET','debiturs/getAllData?offset='.$offset.'&limit='.$limit,[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$data['data'] = isset($result['data']['datas'])?$result['data']['datas']:"";
		return view('Modules\Debitur\Views\index',$data);
	}

	public function view($code)
	{
		$response = $this->client->request('GET','debiturs/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'cucode' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Debitur\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
			$headers = [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			];

			$form_params = [
			    "cucode" => $this->request->getPost("cucode"),
			    "cncode" => $this->request->getPost("cncode"),
				"cuname" => $this->request->getPost("cuname"),
			    "cuaddr" => $this->request->getPost("cuaddr"),
			    "cuzip" => $this->request->getPost("cuzip"),
			    "cuphone" => $this->request->getPost("cuphone"),
			    "cufax" => $this->request->getPost("cufax"),
			    "cucontact" => $this->request->getPost("cucontact"),
			    "cuemail" => $this->request->getPost("cuemail"),
			    "cunpwp" => $this->request->getPost("cunpwp"),
			    "cuskada" => $this->request->getPost("cuskada"),
			    "cudebtur" => $this->request->getPost("cudebtur"),
			    "cutype" => $this->request->getPost("cutype"),
			    "cunppkp" => $this->request->getPost("cunppkp")		
			];

			$validate = $this->validate([
	            'cucode' 	=> 'required',
	            'cncode'  => 'required',
	            'cuname'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','debiturs/createNewData',[
					'headers' => $headers,
					'form_params' => $form_params
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Debitur Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
		$data['country_dropdown'] = country_dropdown($selected='');
		return view('Modules\Debitur\Views\add',$data);		
	}	

	public function edit($code)
	{
		$data = [];
		$headers = [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		];

		if ($this->request->isAJAX()) 
		{
			$form_params = [
			    "cucode" => $this->request->getPost("cucode"),
			    "cncode" => $this->request->getPost("cncode"),
				"cuname" => $this->request->getPost("cuname"),
			    "cuaddr" => $this->request->getPost("cuaddr"),
			    "cuzip" => $this->request->getPost("cuzip"),
			    "cuphone" => $this->request->getPost("cuphone"),
			    "cufax" => $this->request->getPost("cufax"),
			    "cucontact" => $this->request->getPost("cucontact"),
			    "cuemail" => $this->request->getPost("cuemail"),
			    "cunpwp" => $this->request->getPost("cunpwp"),
			    "cuskada" => $this->request->getPost("cuskada"),
			    "cudebtur" => $this->request->getPost("cudebtur"),
			    "cutype" => $this->request->getPost("cutype"),
			    "cunppkp" => $this->request->getPost("cunppkp")		
			];

			$validate = $this->validate([
	            'cucode' 	=> 'required',
	            'cncode'  => 'required',
	            'cuname'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('PUT','debiturs/updateData',[
					'headers' => $headers,
					'form_params' => $form_params
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

		$response = $this->client->request('GET','debiturs/getDetailData',[
			'headers' => $headers,
			'form_params' => [
				'cucode' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		// dd($result['data']['cncode']);
		$data['data'] = isset($result['data'])?$result['data']:"";
		$data['country_dropdown'] = country_dropdown($result['data']['cncode']);
		return view('Modules\Debitur\Views\edit',$data);		
	}	

	public function delete($code)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','debiturs/delete',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'cityId' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, City Code '.$code.' Deleted.');
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

}