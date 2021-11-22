<?php
namespace Modules\Container\Controllers;

use App\Libraries\MyPaging;

class Container extends \CodeIgniter\Controller
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
		$paging = new MyPaging();
		$limit = 10;
		$endpoint = 'containers/list';
		$data['container'] = $paging->paginate1($endpoint,$limit,'container');
		$data['pager'] = $paging->pager;
		$data['nomor'] = $paging->nomor($this->request->getVar('page_container'), $limit);
		return view('Modules\Container\Views\index',$data);
	}

	public function view($crno)
	{
		check_exp_time();
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
		check_exp_time();
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
		check_exp_time();
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
		check_exp_time();
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
		$ccode = $result['data']['datas'];
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
		$material = $result['data']['datas'];
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