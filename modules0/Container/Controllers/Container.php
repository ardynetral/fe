<?php
namespace Modules\Container\Controllers;

use GuzzleHttp\Client;
class Container extends \CodeIgniter\Controller
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
		$response = $this->client->request('GET','containers/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => 90,
				'rows'	=> 100
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['container'] = $result['data'];
		return view('Modules\Container\Views\index',$data);
	}

	// public function index()
	// {
	// 	$data = [];
	// 	$offset = (isset($_GET['offset']) ? $_GET['offset'] : 0);
	// 	// echo $_GET['offset'];die();
	// 	$limit = 15;
	// 	$totalrows = 100; 
	// 	if ($this->request->isAJAX()) 
	// 	{	
	// 		$response = $this->client->request('GET','containers/list',[
	// 			'headers' => [
	// 				'Accept' => 'application/json',
	// 				'Authorization' => session()->get('login_token')
	// 			],
	// 			'json' => [
	// 				'start' => $offset,
	// 				'rows'	=> $limit
	// 			]
	// 		]);
	// 		$result = json_decode($response->getBody()->getContents(), true);	
	// 		$data['pager'] = $this->pager($offset,$limit,$totalrows);
	// 		$data['container'] = $result['data'];
	// 		echo json_encode($result['data']);
	// 	}

	// 	$response = $this->client->request('GET','containers/list',[
	// 		'headers' => [
	// 			'Accept' => 'application/json',
	// 			'Authorization' => session()->get('login_token')
	// 		],
	// 		'json' => [
	// 			'start' => 90,
	// 			'rows'	=> 100
	// 		]
	// 	]);

	// 	$result = json_decode($response->getBody()->getContents(), true);	
	// 	// dd($result);
	// 	$data['pager'] = $this->pager($offset,$limit,$totalrows);
	// 	$data['container'] = $result['data'];
	// 	// echo json_encode($result['data']);
	// 	return view('Modules\Container\Views\index_ajax',$data);
	// }

	public function pager($offset,$limit,$total_rows) {
		$page_total = ((int)$total_rows+(int)$limit-1)/(int)$limit; 
		// $totalage = (imagesFound.Length + PageSize - 1) / PageSize;
		$html = "";
		for($i=1;$i<=$page_total;$i++) {
			if($i>1){
				$offset = $offset+15;
			}else {
				$offset=0;
			}
			$uri= site_url('container/p/index?offset='.$offset.'&limit='.$limit.'');
			$html .= '<a href="'.$uri.'">'.$i.'</a>&nbsp;'; 
		}

		return $html;
	}

	public function view($crno)
	{
		$response = $this->client->request('GET','containers/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'crNo' => $crno,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['container'] = isset($result['data']) ? $result['data'] : '';
		return view('Modules\Container\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if($this->request->getPost()) {
			$body = [
	            "crno" 		=> $this->request->getPost('crno'),
	            "mtcode" 	=> $this->request->getPost('mtcode'),
	            "cccode" 	=> $this->request->getPost('cccode'),
	            "crowner" 	=> $this->request->getPost('crowner'),
	            "crcdp" 	=> (double)$this->request->getPost('crcdp'),
	            "crcsc" 	=> (double)$this->request->getPost('crcsc'),
	            "cracep" 	=> (double)$this->request->getPost('cracep'),
	            "crmmyy" 	=> $this->request->getPost('crmmyy'),
	            "crweightk" => (double)$this->request->getPost('crweightk'),
	            "crweightl" => (double)$this->request->getPost('crweightl'),
	            "crtarak" 	=> (double)$this->request->getPost('crtarak'),
	            "crtaral" 	=> (double)$this->request->getPost('crtaral'),
	            "crnetk" 	=> (double)$this->request->getPost('crnetk'),
	            "crnetl" 	=> (double)$this->request->getPost('crnetl'),
	            "crvol" 	=> (double)$this->request->getPost('crvol'),
	            "crmanuf" 	=> $this->request->getPost('crmanuf'),
	            "crmandat" 	=> $this->request->getPost('crmandat')
	        ];


			$response = $this->client->request('POST','containers/create',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'crNo' => $this->request->getPost('crno'),
					'dset' => $body
				]
			]);
	
			$result = json_decode($response->getBody()->getContents(), true);	

			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Container Saved.');
			$data['message'] = "success";
			echo json_encode($data);die();
		}

		$data['container_code'] = $this->ccode_dropdown();
		$data['material'] = $this->material_dropdown();
		return view('Modules\Container\Views\add',$data);
	}	

	public function edit($crno)
	{
		$data = [];

		if($this->request->getPost()) {
			$body = [
	            "crno" 		=> $this->request->getPost('crno'),
	            "mtcode" 	=> $this->request->getPost('mtcode'),
	            "cccode" 	=> $this->request->getPost('cccode'),
	            "crowner" 	=> $this->request->getPost('crowner'),
	            "crcdp" 	=> (double)$this->request->getPost('crcdp'),
	            "crcsc" 	=> (double)$this->request->getPost('crcsc'),
	            "cracep" 	=> (double)$this->request->getPost('cracep'),
	            "crmmyy" 	=> $this->request->getPost('crmmyy'),
	            "crweightk" => (double)$this->request->getPost('crweightk'),
	            "crweightl" => (double)$this->request->getPost('crweightl'),
	            "crtarak" 	=> (double)$this->request->getPost('crtarak'),
	            "crtaral" 	=> (double)$this->request->getPost('crtaral'),
	            "crnetk" 	=> (double)$this->request->getPost('crnetk'),
	            "crnetl" 	=> (double)$this->request->getPost('crnetl'),
	            "crvol" 	=> (double)$this->request->getPost('crvol'),
	            "crmanuf" 	=> $this->request->getPost('crmanuf'),
	            "crmandat" 	=> $this->request->getPost('crmandat')
	        ];


			$response = $this->client->request('POST','containers/update',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'crNo' => $this->request->getPost('crno'),
					'dset' => $body
				]
			]);
	
			$result = json_decode($response->getBody()->getContents(), true);	

			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Container Saved.');
			$data['message'] = "success";
			echo json_encode($data);die();
		}

		$response = $this->client->request('GET','containers/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'crNo' => $crno,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['container'] = $result['data'];
		$data['ccode'] = $this->ajax_ccode($result['data']['cccode']);
		$data['ccode_dropdown'] = $this->ccode_dropdown($result['data']['cccode']);
		$data['material'] = $this->material_dropdown();
		return view('Modules\Container\Views\edit',$data);
	}	

	public function delete($crno)
	{
		if ($this->request->isAJAX())
		{ 
			$response = $this->client->request('DELETE','containers/delete',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'crNo' => $crno,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Container '.$code.' Deleted.');
			$data['message'] = "success";
			echo json_encode($data);die();
		}
	}	

	public function ccode_dropdown($selected="")
	{
		$data = [];
		$response = $this->client->request('GET','containercode/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$ccode = $result['data'];
		$option = "";
		$option .= '<select name="cccode" id="cccode" class="select-ccode">';
		$option .= '<option value="">-select-</option>';
		foreach($ccode as $cc) {
			$option .= "<option value='".$cc['cccode'] ."'". ((isset($selected) && $selected==$cc['cccode']) ? ' selected' : '').">".$cc['cccode']."</option>"; 
		}
		$option .="</select>";
		return $option; 
		die();
	}	

	public function material_dropdown()
	{
		$data = [];
		$response = $this->client->request('GET','materials/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' =>0,
				'limit' =>0
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$material = $result['data'];
		$option = "";
		$option .= '<select name="mtcode" id="mtcode" class="select-material">';
		$option .= '<option value="">-select-</option>';
		foreach($material as $mt) {
			$option .= "<option value=".$mt['mtcode'].">".$mt['mtcode']."</option>"; 
		}
		$option .="</select>";
		return $option; 
		die();
	}

	public function ajax_ccode($cccode) 
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

		if($this->request->isAJAX()) {
			echo json_encode($result['data']);
			die();		
		}

		return $result['data'];
	}	

}