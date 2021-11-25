<?php
namespace Modules\PraIn\Controllers;

class PraIn extends \CodeIgniter\Controller
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
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view");
		define("has_insert", has_privilege_check($module, '_insert'));
		define("has_approval", has_privilege_check($module, '_approval'));
		define("has_edit", has_privilege_check($module, '_update'));
		define("has_delete", has_privilege_check($module, '_delete'));
		define("has_print", has_privilege_check($module, '_printpdf'));

		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		$data = [];
		$offset=0;
		$limit=100;
		// order pra
		if($group_id == 4) {
			$response1 = $this->client->request('GET','orderPras/getAllData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'offset' => $offset,
					'limit'	=> $limit
				]
			]);

			$result_pra = json_decode($response1->getBody()->getContents(),true);	

			$data['data_pra'] = isset($result_pra['data']['datas'])?$result_pra['data']['datas']:"";
		} else {
			$response1 = $this->client->request('GET','orderPras/getAllData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'offset' => $offset,
					'limit'	=> $limit,
					// 'userId'	=> $user_id
				]
			]);		
			$result_pra = json_decode($response1->getBody()->getContents(),true);	
			$datas = isset($result_pra['data']['datas'])?$result_pra['data']['datas']:"";

			// Jika EMKL (User_group==1)
			$data_pra = array();
			if(($datas !="") && ($group_id==1)) {
				foreach($datas as $dt) {
					if($user_id==$dt['crtby']) {
						$data_pra[] = $dt;
					}
				}
			// jika Principal
			} else if(($datas !="") && ($group_id==2)) {
				foreach($datas as $dt) {
					if($prcode==$dt['cpopr']) {
						$data_pra[] = $dt;
					}
				}
			}	

			$data['data_pra'] = $data_pra;
		}

		$data['prcode'] = $prcode;
		$data['cucode'] = $prcode;
		// echo dd($data);
		// return view('Modules\PraIn\Views\tab_base',$data);
		return view('Modules\PraIn\Views\index',$data);
	}

	public function view($code)
	{
		check_exp_time();
		$data['status'] = "";
		$data['message'] = "";
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view");
		// define("has_approval", has_privilege_check($module, '_approval'));

		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		$response = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'praid' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	

		if(isset($result['data']['count']) && ($result['data']['count']==0))
		{
			$data['status'] = "nodata";
			$data['message'] = "Data not found.";
			// echo json_encode($data);die();				
		}
		$data['prcode'] = $prcode;
		$data['cucode'] = $prcode;
		// $data['data'] = $result['data']['datas'];
		$data['header'] = $result['data']['datas'];
		$data['detail'] = $data['header'][0]['orderPraContainers'];		
		return view('Modules\PraIn\Views\view',$data);
	}	

	public function ajax_view($praid)
	{
		if ($this->request->isAJAX()) 
		{
			check_exp_time();
			$response = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'praid' => $praid,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);

			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			$data['status'] = "success";
			$dt_pra = $result['data']['datas'];
			$data['dt_header'] = $dt_pra[0];
			$data['dt_detail'] = $dt_pra[0]['orderPraContainers'];
			$data['hc20'] = ((isset($data['dt_detail'])&&($data['dt_detail']!=null)) ? $this->hitungHCSTD($data['dt_detail'])['hc20'] : 0);
			$data['hc40'] = ((isset($data['dt_detail'])&&($data['dt_detail']!=null)) ? $this->hitungHCSTD($data['dt_detail'])['hc40'] : 0);
			$data['hc45'] = ((isset($data['dt_detail'])&&($data['dt_detail']!=null)) ? $this->hitungHCSTD($data['dt_detail'])['hc45'] : 0);
			$data['std20'] = ((isset($data['dt_detail'])&&($data['dt_detail']!=null)) ? $this->hitungHCSTD($data['dt_detail'])['std20'] : 0);
			$data['std40'] = ((isset($data['dt_detail'])&&($data['dt_detail']!=null)) ? $this->hitungHCSTD($data['dt_detail'])['std40'] : 0);

			return json_encode($data);die();
		}		
	}

	public function hitungHCSTD($datas) 
	{
		$dt = [];
		$hc20 = 0;
		$hc40 = 0;
		$hc45 = 0;
		$std20 = 0;
		$std40 = 0;
		foreach($datas as $data) {
	
			if((floatval($data['ccheight'])>8.5) && (floatval($data['cclength'])<=20)) {
				$hc20=$hc20+1;
			} else if((floatval($data['ccheight'])>8.5) && (floatval($data['cclength'])==40)) {
				$hc40=$hc40+1;
			} else if((floatval($data['ccheight']))>8.5 && (floatval($data['cclength'])==45)) {
				$hc45=$hc45+1;
			}

			if((floatval($data['ccheight'])==8.5) && (floatval($data['cclength'])<=20)) {
				$std20=$std20+1;
			} else if((floatval($data['ccheight'])==8.5) && (floatval($data['cclength'])==40)) {
				$std40=$std40+1;
			}

			$dt['hc20'] = $hc20;
			$dt['hc40'] = $hc40;
			$dt['hc45'] = $hc45;
			$dt['std20'] = $std20;
			$dt['std40'] = $std40;

		}
		return $dt;
	}

	public function add()
	{
		check_exp_time();
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		$offset=0;
		$limit=10;

		if ($this->request->isAJAX()) 
		{
			$cpidisdat = date_format(date_create($_POST['cpidisdat']),"Y-m-d");
			$cpipratgl = date_format(date_create($_POST['cpipratgl']),"Y-m-d");

			$post_arr = [];

			$post_arr[] = [
				'name'		=> 'cpiorderno',
				'contents'	=> $this->get_prain_number(),
			];
			$post_arr[] = [
				'name'		=> 'cpidish',
				'contents'	=> $_POST['cpidish']
			];
			$post_arr[] = [
				'name'		=> 'cpidisdat',
				'contents'	=> $cpidisdat
			];
			$post_arr[] = [
				'name'		=> 'liftoffcharge',
				'contents'	=> $_POST['liftoffcharge']
			];
			$post_arr[] = [
				'name'		=> 'cpdepo',
				'contents'	=> $_POST['cpdepo']
			];
			$post_arr[] = [
				'name'		=> 'cpipratgl',
				'contents'	=> $cpipratgl
			];
			$post_arr[] = [
				'name'		=> 'cpirefin',
				'contents'	=> $_POST['cpirefin']
			];
			$post_arr[] = [
				'name'		=> 'cpijam',
				'contents'	=> $_POST['cpijam']
			];
			$post_arr[] = [
				'name'		=> 'cpives',
				'contents'	=> $_POST['cpives']
			];
			$post_arr[] = [
				'name'		=> 'cpivoyid',
				'contents'	=> $_POST['cpivoyid']
			];
			$post_arr[] = [
				'name'		=> 'cpicargo',
				'contents'	=> $_POST['cpicargo']
			];
			$post_arr[] = [
				'name'		=> 'cpideliver',
				'contents'	=> $_POST['cpideliver']
			];
			$post_arr[] = [
				'name'		=> 'totalcharge',
				'contents'	=> 0
			];

			if($_FILES["files"] !="") {
				foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name) {
					if(is_array($_FILES["files"]["tmp_name"])) {
						$post_arr[] = array(
							'name' => 'files',
							'contents'	=> fopen($_FILES["files"]['tmp_name'][$key],'r'),
							'filename'	=> $_FILES["files"]['name'][$key],
						);
						$post_arr[] = [
							'name'		=> 'flag',
							'contents'	=> 2
						];
						continue;
					}
				}
			}

			$validate = $this->validate([
	            'cpiorderno' 	=> 'required',
	            'cpives' 	=> 'required',
	            'cpivoyid' 	=> 'required',
	        ]);			

		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','orderPras/createNewData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'multipart' => $post_arr,
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Order Pra Saved.');
				$data['message'] = "success";
				$data['praid'] = $result['data']['praid'];
				echo json_encode($data);die();
			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		

		// order pra container
		$response2 = $this->client->request('GET','orderPraContainers/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => $offset,
				'limit'	=> $limit
			]
		]);

		$data['act'] = "add";
		$data['prcode'] = $prcode;
		$data['cucode'] = $prcode;
		$data['prain_no'] = $this->get_prain_number();
		$result_prac = json_decode($response2->getBody()->getContents(),true);	
		$data['data_prac'] = isset($result_prac['data']['datas'])?$result_prac['data']['datas']:"";

		return view('Modules\PraIn\Views\add',$data);		
	}	

	public function edit($id)
	{
		check_exp_time();
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if ($this->request->isAJAX()) 
		{
			$cpidisdat = date_format(date_create($_POST['cpidisdat']),"Y-m-d");
			$cpipratgl = date_format(date_create($_POST['cpipratgl']),"Y-m-d");

			$post_arr = [];

			$post_arr[] = [
				'name'		=> 'praid',
				'contents'	=> $_POST['praid']
			];
			$post_arr[] = [
				'name'		=> 'cpiorderno',
				'contents'	=> $_POST['cpiorderno']
			];
			$post_arr[] = [
				'name'		=> 'cpidish',
				'contents'	=> $_POST['cpidish']
			];
			$post_arr[] = [
				'name'		=> 'cpidisdat',
				'contents'	=> $cpidisdat
			];
			$post_arr[] = [
				'name'		=> 'liftoffcharge',
				'contents'	=> $_POST['liftoffcharge']
			];
			$post_arr[] = [
				'name'		=> 'cpdepo',
				'contents'	=> $_POST['cpdepo']
			];
			$post_arr[] = [
				'name'		=> 'cpipratgl',
				'contents'	=> $cpipratgl
			];
			$post_arr[] = [
				'name'		=> 'cpirefin',
				'contents'	=> $_POST['cpirefin']
			];
			$post_arr[] = [
				'name'		=> 'cpijam',
				'contents'	=> $_POST['cpijam']
			];
			$post_arr[] = [
				'name'		=> 'cpives',
				'contents'	=> $_POST['cpives']
			];
			$post_arr[] = [
				'name'		=> 'cpivoyid',
				'contents'	=> $_POST['cpivoyid']
			];
			$post_arr[] = [
				'name'		=> 'cpicargo',
				'contents'	=> $_POST['cpicargo']
			];
			$post_arr[] = [
				'name'		=> 'cpideliver',
				'contents'	=> $_POST['cpideliver']
			];
			$post_arr[] = [
				'name'		=> 'totalcharge',
				'contents'	=> 0
			];

			if($_FILES["files"]['name'][0]!=="") {
				foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name) {
					if(is_array($_FILES["files"]["tmp_name"])) {
						$post_arr[] = array(
							'name' => 'files',
							'contents'	=> fopen($_FILES["files"]['tmp_name'][$key],'r'),
							'filename'	=> $_FILES["files"]['name'][$key],
						);

						continue;
					}
				}
			}

			$validate = $this->validate([
	            'praid' 	=> 'required',
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('PUT','orderPras/updateData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'multipart' => $post_arr,
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				$data['message'] = "success";
				$data['msgdata'] = $result['data'];
				echo json_encode($data);die();
			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}	
		
		$response = $this->client->request('GET','orderPras/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => ['praid' => $id],
		]);

		$data['act'] = "edit";
		$data['group_id'] = $group_id;
		$result = json_decode($response->getBody()->getContents(), true);
		$data['data'] = $result['data'];
		$data['contract'] = $this->get_contract($data['data']['cpopr']);	

		$data['hc20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc20'] : 0);
		$data['hc40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc40'] : 0);
		$data['hc45'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc45'] : 0);
		$data['std20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std20'] : 0);
		$data['std40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std40'] : 0);	

		return view('Modules\PraIn\Views\edit',$data);		
	}	
	
	public function delete($code)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','city/delete',[
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

	// Order Pra Container
	public function addcontainer()
	{
		// $data = [];
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if ($this->request->isAJAX()) 
		{
			$form_params = [
				'praid' => $_POST['praid'],
				'cpopr' => $_POST['cpopr'],
				'cpcust' => $_POST['cpcust'],				
				'crno' => $_POST['crno'],
				'cccode' => $_POST['ccode'],
				'ctcode' => $_POST['ctcode'],
				'cclength' => $_POST['cclength'],
				'ccheight' => $_POST['ccheight'],
				'cpife' => $_POST['cpife'],
				'cpishold' => $_POST['cpishold'],
				'cpiremark' => $_POST['cpiremark']
			];


			$validate = $this->validate([
	            'crno' 	=> 'required',
	            'ccode' 	=> 'required',
	            'ctcode' 	=> 'required',
	            'cclength' 	=> 'required',
	            'ccheight' 	=> 'required',
	            'cpife' 	=> 'required',
	            'cpishold' 	=> 'required',
	            'cpiremark' => 'required',
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','orderPraContainers/createNewData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params,
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Containers Saved.');
				$data['message'] = "success";
				// $data['praid'] = $result['data']['praid'];
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
	}	
	public function get_one_container($id) 
	{
		if ($this->request->isAJAX())  {
			$response = $this->client->request('GET','orderPraContainers/getDetailData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'pracrnoid' => $id,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	

			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}	

			$data['message'] = 'success';
			$data['cr'] = $result['data'];
			echo json_encode($data);die();	
		}	

	}
	public function edit_container()
	{
		// $data = [];
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if ($this->request->isAJAX()) 
		{
			$form_params = [
				'pracrnoid' => $_POST['pracrnoid'],
				'praid' => $_POST['praid'],
				'crno' => $_POST['crno'],
				'cccode' => $_POST['ccode'],
				'ctcode' => $_POST['ctcode'],
				'cclength' => $_POST['cclength'],
				'ccheight' => $_POST['ccheight'],
				'cpife' => $_POST['cpife'],
				'cpishold' => $_POST['cpishold'],
				'cpiremark' => $_POST['cpiremark']
			];

			// if(($_POST['cpopr'] !='')||($_POST['cpcust'] !='')) {
			// 	$dt_principal = [
			// 		'cpopr' => $_POST['cpopr'],
			// 		'cpcust' => $_POST['cpcust'],
			// 	];
			// 	array_push($form_params, $dt_principal);
			// }

			$validate = $this->validate([
	            'pracrnoid' 	=> 'required',
	            'crno' 	=> 'required',
	            'ccode' 	=> 'required',
	            'ctcode' 	=> 'required',
	            'cclength' 	=> 'required',
	            'ccheight' 	=> 'required',
	            'cpife' 	=> 'required',
	            'cpishold' 	=> 'required',
	            'cpiremark' => 'required',
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('PUT','orderPraContainers/updateData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params,
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Containers Saved.');
				$data['message'] = "success";
				// $data['praid'] = $result['data']['praid'];
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
	}	

	public function delete_container($id)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','orderPraContainers/deleteData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'pracrnoid' => $id,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Container Deleted.');
			$data['message'] = "success";
			echo json_encode($data);die();
		}
	}		

	// Approval-1
	public function approve_order($id) 
	{
		check_exp_time();
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_approval");

		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if ($this->request->isAJAX()) 
		{
			// Hitung TOTAL CHARGE
			$get_order = $this->client->request('GET','orderPras/getDetailData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => ['praid' => $id],
			]);

			$dt_order = json_decode($get_order->getBody()->getContents(), true);

			// contract
			// utk sementara PRCODE/CPOPR diisi manual(ada perubahan proses bisnis)
			$contract = $this->get_contract("CTP");

			if($contract==""){
				$data['message'] = "Failled";
				$data['message_body'] = "Approval gagal. Data Contract tidak ditemukan untuk Operator ".$dt_order['data']['cpopr'];
				echo json_encode($data);die();	
			}


			$hc20 = ((isset($dt_order['data']['orderPraContainers'])&&($dt_order['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['orderPraContainers'])['hc20'] : 0);
			$hc40 = ((isset($dt_order['data']['orderPraContainers'])&&($dt_order['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['orderPraContainers'])['hc40'] : 0);
			$hc45 = ((isset($dt_order['data']['orderPraContainers'])&&($dt_order['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['orderPraContainers'])['hc45'] : 0);
			$std20 = ((isset($dt_order['data']['orderPraContainers'])&&($dt_order['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['orderPraContainers'])['std20'] : 0);
			$std40 = ((isset($dt_order['data']['orderPraContainers'])&&($dt_order['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['orderPraContainers'])['std40'] : 0);

			// dd($contract);die();

			// admin_tarif: coadmv
			// cleaning by: coadmm (1=by_order, 0=by_container)
			if($contract['coadmm']==0) {
				// liftOn
				$lon20 = $contract['colonmty20'];
				$lon40 = $contract['colonmty40'];
				$lon45 = $contract['colonmty45'];
				// liftOff
				$lof20 = $contract['colofmty20'];
				$lof40 = $contract['colofmty40'];
				$lof45 = $contract['colofmty45'];	


			} else if($contract['coadmm']==1) {
				// liftOn
				$lon20 = $contract['coloncy20'];
				$lon40 = $contract['coloncy40'];
				$lon45 = $contract['coloncy45'];
				// liftOff
				$lof20 = $contract['colofcy20'];
				$lof40 = $contract['colofcy40'];
				$lof45 = $contract['colofcy45'];
			}

			// hitung billing
			$lon_hc20 = $hc20 * $lon20; 
			$lon_hc40 = $hc40 * $lon40; 
			$lon_hc45 = $hc45 * $lon45; 			
			$lon_std20 = $std20 * $lon20; 
			$lon_std40 = $std40 * $lon40;			

			$lof_hc20 = $hc20 * $lof20; 
			$lof_hc40 = $hc40 * $lof40; 
			$lof_hc45 = $hc45 * $lof45; 			
			$lof_std20 = $std20 * $lof20; 
			$lof_std40 = $std40 * $lof40; 

			$subtotal = $lon_hc20+$lon_hc40+$lon_hc45+$lon_std20+$lon_std40;
			$pajak = $contract['cotax'] * $subtotal;
			$adm_tarif = $contract['coadmv'];
			$totalcharge = $subtotal + $pajak + $adm_tarif;

			$response = $this->client->request('PUT','orderPras/updateData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'praid' => $id,
					'appv' => 1,
					'totalcharge' => $totalcharge
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	


			if((isset($result['status'])) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}			

			// $result2 = json_decode($response->getBody()->getContents(), true);	
			// $data['lon_hc20'] = $lon_hc20;
			// $data['lon_hc40'] = $lon_hc40;
			// $data['lon_hc45'] = $lon_hc45;
			// $data['lon_std20'] = $lon_std20;
			// $data['lon_std40'] = $lon_std40;
			// $data['subtotal_charge'] = $subtotal;
			// $data['pajak'] = $pajak;
			// $data['adm_tarif'] = $adm_tarif;
			// $data['totalcharge'] = $totalcharge;

			$data['message'] = "success";
			echo json_encode($data);die();			
		}

		$response = $this->client->request('GET','orderPras/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => ['praid' => $id],
		]);

		$data['act'] = "approval1";
		$data['group_id'] = $group_id;
		$result = json_decode($response->getBody()->getContents(), true);
		$data['data'] = $result['data'];
		$data['contract'] = $this->get_contract($data['data']['cpopr']);	

		$data['hc20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc20'] : 0);
		$data['hc40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc40'] : 0);
		$data['hc45'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc45'] : 0);
		$data['std20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std20'] : 0);
		$data['std40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std40'] : 0);	

		return view('Modules\PraIn\Views\approval1',$data);

	}

	// set principal & customer to oder pra container
	public function appv1_update_container() 
	{
		// UPDATE CONTAINER (PRINCIPAL & CSTOMER)
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('PUT','orderPraContainers/updateData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'pracrnoid' => $_POST['pracrnoid'],
					'cpopr' => $_POST['cpopr'],
					'cpcust' => $_POST['cpcust'],
				],
			]);	

			$result = json_decode($response->getBody()->getContents(), true);

			if((isset($result['status'])) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}
			
			$data['message'] = 'success';
			$data['message_body'] = "Principal berhasil ditambahkan";
			echo json_encode($data);die();				
		}
	}

	public function approval2($id)
	{
		check_exp_time();
		// $data = [];
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];
		$data['act'] = "approval2";
		$data['group_id'] = $group_id;

		// GET DETAIL ORDER
		$response = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => ['praid' => $id],
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		$dt_order  = $result['data']['datas']['0']; 
		$dt_company = $this->get_company();

		if ($this->request->isAJAX()) 
		{
			$cprocess_params = [];
			$containers = $dt_order['orderPraContainers'];
			// echo var_dump($containers);die();
			if($containers!="") {
				$no=0;
				foreach ($containers as $c) {
					$cprocess_params[$no] = [
						"CRNO" => $c['crno'],
					    "CPOPR" => $c['cpopr'],
					    "CPCUST" => $c['cpcust'],					    
					    "CPDEPO" => $dt_order['cpdepo'],
					    "SPDEPO" => $dt_company['sdcode'],
					    "CPIFE" => $c['cpife'],
					    "CPICARGO" => $dt_order['cpicargo'],
					    "CPIPRATGL" => $dt_order['cpipratgl'],
					    "CPIREFIN" => $dt_order['cpirefin'],
					    "CPIVES" => $dt_order['cpives'],
					    "CPIDISH" => $dt_order['cpidish'],
					    "CPIDISDAT" => $dt_order['cpidisdat'],
					    "CPIJAM" => $dt_order['cpijam'],
					    "CPICHRGBB" => 1,
					    "CPIDELIVER" => $dt_order['cpideliver'],
					    "CPIORDERNO" => $dt_order['cpiorderno'],
					    "CPISHOLD" => $c['cpishold'],
					    "CPIREMARK" => $c['cpiremark'],
					    "CPIVOYID" => $dt_order['cpivoyid'],
					    "CPIVOY" => $dt_order['voyages']['voyno'],
					    "CPISTATUS" => 0,
					];	

					// get One Container
					$cr_req = $this->client->request('GET','containers/listOne',[
						'headers' => [
							'Accept' => 'application/json',
							'Authorization' => session()->get('login_token')
						],
						'form_params' => ['crNo' => $c['crno']],
					]);

					$cr_result = json_decode($cr_req->getBody()->getContents(), true);

					if(isset($cr_result['data'])) {
						$mtcode_cccode = [
							'MTCODE' => $cr_result['data']['mtcode'],
							'CCCODE' => $cr_result['data']['cccode']
						];
						array_push($cprocess_params[$no], $mtcode_cccode);
					}		

					// Container Process
					$cp_response = $this->client->request('POST','praIns/createNewData',[
						'headers' => [
							'Accept' => 'application/json',
							'Authorization' => session()->get('login_token')
						],
						'form_params' => $cprocess_params[$no]
					]);

					$result = json_decode($cp_response->getBody()->getContents(), true);	

					if(isset($result['status']) && ($result['status']=="Failled"))
					{
						$data['message'] = $result['message'];
						echo json_encode($data);die();				
					}
		
					$no++;
				} //endforeach

				// echo var_dump($result);die();

				// UPDATE ORDER PRA appv=2
				$op_response = $this->client->request('PUT','orderPras/updateData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'praid' => $id,
						'appv' => 2
					]
				]);

				$opresult = json_decode($op_response->getBody()->getContents(), true);
				if(isset($opresult['status']) && ($opresult['status']=="Failled"))
				{
					$data['message'] = $opresult['message'];
					echo json_encode($data);die();				
				}

				$data['message'] = "success";
				$data['msgdata'] = $opresult['data'];
				echo json_encode($data);die();
			}		
		}		

		$data['data'] = $dt_order;
		$data['contract'] = $this->get_contract($data['data']['cpopr']);
		$data['hc20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc20'] : 0);
		$data['hc40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc40'] : 0);
		$data['hc45'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc45'] : 0);
		$data['std20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std20'] : 0);
		$data['std40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std40'] : 0);	

		return view('Modules\PraIn\Views\approval2',$data);		
	}	

	public function proforma($id) 
	{
		$get_order = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => ['praid' => $id],
		]);

		$dt_order = json_decode($get_order->getBody()->getContents(), true);
		// dd($dt_order);
		$contract = $this->get_contract("CTP");

		$cont_std20 = $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['std20'];
		$cont_std40 = $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['std40'];
		$cont_hc20 = $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc20'];
		$cont_hc40 = $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc40'];
		$cont_hc45 = $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc45'];


		$hc20 = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $cont_hc20 : 0);
		$hc40 = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $cont_hc40 : 0);
		$hc45 = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $cont_hc45 : 0);
		$std20 = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $cont_std20 : 0);
		$std40 = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $cont_std40 : 0);	
		
		if($contract['coadmm']==0) {
				// liftOn
				$lon20 = $contract['colonmty20'];
				$lon40 = $contract['colonmty40'];
				$lon45 = $contract['colonmty45'];
				// liftOff
				$lof20 = $contract['colofmty20'];
				$lof40 = $contract['colofmty40'];
				$lof45 = $contract['colofmty45'];	

				$coadmm = "By Order";

			} else if($contract['coadmm']==1) {
				// liftOn
				$lon20 = $contract['coloncy20'];
				$lon40 = $contract['coloncy40'];
				$lon45 = $contract['coloncy45'];
				// liftOff
				$lof20 = $contract['colofcy20'];
				$lof40 = $contract['colofcy40'];
				$lof45 = $contract['colofcy45'];
				
				$coadmm = "By Container";
			}

			// hitung billing
			$lon_hc20 = $hc20 * $lon20; 
			$lon_hc40 = $hc40 * $lon40; 
			$lon_hc45 = $hc45 * $lon45; 			
			$lon_std20 = $std20 * $lon20; 
			$lon_std40 = $std40 * $lon40;			

			$lof_hc20 = $hc20 * $lof20; 
			$lof_hc40 = $hc40 * $lof40; 
			$lof_hc45 = $hc45 * $lof45; 			
			$lof_std20 = $std20 * $lof20; 
			$lof_std40 = $std40 * $lof40; 

			$subtotal = $lon_hc20+$lon_hc40+$lon_hc45+$lon_std20+$lon_std40;
			$pajak = $contract['cotax'] * $subtotal;
			$adm_tarif = $contract['coadmv'];
			$totalcharge = $subtotal + $pajak + $adm_tarif;

			// hitungHCSTD
			$data['std20'] = $std20;
			$data['std40'] = $std40;
			$data['hc20'] = $hc20;
			$data['hc40'] = $hc40;
			$data['hc45'] = $hc45;

			//tarif liftOn
			$data['lon20'] = $lon20;
			$data['lon40'] = $lon40;
			$data['lon45'] = $lon45;
			// Hitung subtotal
			$data['lon_hc20'] = $lon_hc20;
			$data['lon_hc40'] = $lon_hc40;
			$data['lon_hc45'] = $lon_hc45;
			$data['lon_std20'] = $lon_std20;
			$data['lon_std40'] = $lon_std40;

			$data['subtotal_charge'] = $subtotal;
			$data['pajak'] = $pajak;
			$data['adm_tarif'] = $adm_tarif;
			$data['totalcharge'] = $totalcharge;

			$data['message'] = "success";
			$data['coadmm'] = $coadmm;
			$data['data'] = $dt_order['data']['datas'][0];
// dd($data['data']);
			return view('Modules\PraIn\Views\proforma',$data);		
	}

	public function get_contract($id) {
		$data = "";
		$response = $this->client->request('GET','contracts/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params'=>[
				'idContract' => $id,
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);

		if(isset($result['success']) && ($result['success']==true))
		{
			$data = $result['data'];
		} else {
			$data = ""; 		
		}

		return $data;
	}

	// Upload Bukti pembayaran
	// Table order_pra_recept
	public function bukti_bayar() 
	{
		check_exp_time();
		$data = [];
		$post_arr = [];
		
		if($this->request->isAjax()) {
			$post_arr[] = [
				'name'		=> 'praid',
				'contents'	=> $_POST['praid']
			];
			$post_arr[] = [
				'name'		=> 'cpireceptno',
				'contents'	=> $_POST['cpireceptno']
			];
			$post_arr[] = [
				'name'		=> 'cpicurr',
				'contents'	=> $_POST['cpicurr']
			];
			$post_arr[] = [
				'name'		=> 'cpirate',
				'contents'	=> $_POST['cpirate']
			];	

			if($_FILES["files"] !="") {
				$post_arr[] = array(
					'name' => 'file',
					'contents'	=> fopen($_FILES["files"]['tmp_name'],'r'),
					'filename'	=> $_FILES["files"]['name'],
				);
				$post_arr[] = [
					'name'		=> 'flag',
					'contents'	=> 1
				];
			}

			$response = $this->client->request('POST','orderPraRecepts/createNewData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'multipart' => $post_arr,
			]);

			$result = json_decode($response->getBody()->getContents(),true);
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['status'] = "0";
				$data['message'] = "Gagal upload data";
				echo json_encode($data);die();				
			}

			$data['status'] = "1";
			$data['message'] = "Berhasil upload bukti pembayaran";
			echo json_encode($data);die();			
		}
	} 

	// Print detail order
	public function print_order($id) 
	{
		check_exp_time();
		$mpdf = new \Mpdf\Mpdf();

		$data = [];
		$response = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query'=>[
				'praid' => $id,
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		
		$header = $result['data']['datas'];
		$detail = $header[0]['orderPraContainers'];

		if(isset($result['status']) && ($result['status']=="Failled"))
		{
			$data['status'] = "Failled";
			$data['message'] = $result['message'];
			echo json_encode($data);die();				
		}
		

		$html = '';

		$html .= '
		<html>
			<head>
				<title>Order PraIn</title>

				<style>
					body{font-family: Arial;font-size:12px;}
					.page-header{display:block;border-bottom:2px solid #aaa;padding:0;min-height:30px;margin-bottom:30px;}
					.head-left{float:left;width:200px;padding:0px;}
					.head-right{float:left;padding:0px;margin-left:200px;text-align: right;}

					.tbl_head_prain, .tbl_det_prain{border-spacing: 0;border-collapse: collapse;}
					.tbl_head_prain td{border-collapse: collapse;}
					.t-right{text-align:right;}
					.t-left{text-align:left;}

					.tbl_det_prain th,.tbl_det_prain td {
						border:1px solid #666666!important;
						border-spacing: 0;
						border-collapse: collapse;
						padding:5px;

					}
					.line-space{border-bottom:1px solid #dddddd;margin:30px 0;}
				</style>
			</head>
		';

		$html .= '<body>
			<div class="page-header">
				<div class="head-left">
					<h4>PT. CONTINDO RAYA</h4>
				</div>
				<div class="head-right">
					<p>PADANG, '.date('d/m/Y').'</p>		
				</div>
			</div>
		';
		$html .='
			<table class="tbl_head_prain" width="100%">
				<tbody>
					<tr>
						<td class="t-right" width="180">Principal</td>
						<td width="200">&nbsp;:&nbsp;'.$header[0]['cpopr'].'</td>
						<td class="t-right" width="120">Pra In Reff</td>
						<td>&nbsp;:&nbsp;'.$header[0]['cpiorderno'].'</td>
					</tr>
					<tr>
						<td class="t-right">Customer</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpcust'].' </td>
						<td class="t-right">Pra In Date</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpipratgl'].' </td>
					</tr>
					<tr>
						<td class="t-right">Discharge Port</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpidish'].'  </td>
						<td class="t-right">Ref In N0 #</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpirefin'].'  </td>
					</tr>
					<tr>
						<td class="t-right">Discharge Date</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpidisdat'].'  </td>
						<td class="t-right">Time In</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpijam'].'  </td>
					</tr>
					<tr>
						<td class="t-right">LiftOff Charges In Depot</td>
						<td class="t-left">&nbsp;:&nbsp; '.((isset($header[0]['liftoffcharge'])&&$header[0]['liftoffcharge']==1)?"yes" : "no").'</td>
						<td class="t-right">Vessel</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpives'].' </td>
					</tr>
					<tr>
						<td class="t-right">Depot</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpdepo'].' </td>
						<td class="t-right">Voyage</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['voyages']['voyno'].' </td>
					</tr>
					<tr>
						<td class="t-right">&nbsp;</td>
						<td class="t-left">&nbsp;</td>
						<td class="t-right">Vessel Operator</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['vessels']['vesopr'].' </td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td class="t-right">Ex Cargo</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpicargo'].' </td>
					</tr>	
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td class="t-right">Redeliverer</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpideliver'].' </td>
					</tr>							
				</tbody>
			</table>
		';

		$html .='
			<div class="line-space"></div>
			<h4>Detail Container</h4>
			<table class="tbl_det_prain">
				<thead>
					<tr>
						<th>NO</th>
						<th>Container No.</th>
						<th>ID Code</th>
						<th>Type</th>
						<th>Length</th>
						<th>Height</th>
						<th>F/E</th>
						<th>Gate In Date</th>
					</tr>
				</thead>
				<tbody>';
				$no=1;
				foreach($detail as $row){
					$html .='
					<tr>
						<td>'.$no.'</td>
						<td>'.$row['crno'].'</td>
						<td>'.$row['cccode'].'</td>
						<td>'.$row['ctcode'].'</td>
						<td>'.$row['cclength'].'</td>
						<td>'.$row['ccheight'].'</td>
						<td>'.((isset($row['cpife'])&&$row['cpife']==1) ? "full" : "Empty").'</td>
						<td>'.$row['cpigatedate'].'</td>
					</tr>';

					$no++;
				}
		$html .='
				</tbody>
			</table>

		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		//echo $html;
		die();		
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

	public function checkContainerNumber() 
	{
		/*
		return:
		- 
		*/
		$data = [];
		
		if($this->request->isAjax()) {

			$ccode = $_POST['ccode'];
			// $ccode = "APZU";
			$validate = $this->validate([
	            'ccode' => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {
				$response = $this->client->request('GET','containers/checkcCode',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'query'=>[
						'cContainer' => $ccode, 
					]
				]);

				$result = json_decode($response->getBody()->getContents(),true);
				// echo var_dump($result);die();
				if(isset($result['success']) && ($result['success']==false))
				{
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				$data['status'] = "Success";
				$data['message'] = $result['message'];
				echo json_encode($data);die();

		    } else {

				$data['status'] = "Failled";
				$data['message'] = \Config\Services::validation()->listErrors();;
				echo json_encode($data);die();				    	
		    }
		}
	}	

	public function ajax_ccode_listOne($code)
	{
		$data = [];
		if($this->request->isAjax()) {
			$response = $this->client->request('GET','containercode/listOne',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params'=>[
					'ccCode' => $code, 
				]
			]);

			$result = json_decode($response->getBody()->getContents(),true);

			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			$data['status'] = "Success";
			$data['data'] = $result['data'];
			echo json_encode($data);die();						
		}
	}

	public function ajax_prcode_listOne($code)
	{
		$data = [];
		if($this->request->isAjax()) {
			$response = $this->client->request('GET','principals/listOne',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params'=>[
					'id' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(),true);

			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			$data['status'] = "Success";
			$data['data'] = $result['data'];
			echo json_encode($data);die();
		}
	}	

	public function ajax_vessel_listOne($code)
	{
		$data = [];
		if($this->request->isAjax()) {
			$response = $this->client->request('GET','vessels/listOne',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params'=>[
					'id' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(),true);

			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			$data['status'] = "Success";
			$data['data'] = $result['data'];
			echo json_encode($data);die();						
		}
	}	

	public function get_prain_number()
	{
		$data = [];
		$response = $this->client->request('GET','orderPras/createPrainNo',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		if(isset($result['success']) && ($result['success']==true))
		{
			$data['status'] = "success";
			return $result['data'];		
		}
	}

	// ajax list voyage
	function ajax_voyage_list()
	{
		$api = api_connect();
		$response = $api->request('GET','voyages/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		$vessel = $result['data']['datas'];

		$data = [];
		$items = [];

		foreach($vessel as $v) {
			$items[]= [
				'id'=>$v['voyid'],
				'no'=>$v['voyno']
			];

		}
		$data['items'] = $items;
		$data['total_count'] = count($items);
		return json_encode($data); 
		die();			
	}	

	function get_company() 
	{
		$response = $this->client->request('GET','companies/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);	
		$result = json_decode($response->getBody()->getContents(),true);
		$company = $result['data']['datas'][0];

		// $data = [];

		return $company; 
		die();					
	}
}