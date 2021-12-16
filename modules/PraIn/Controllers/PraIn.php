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
		$data['group_id'] = $group_id;
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
			$cpidisdat = date("Y-m-d", strtotime($_POST['cpidisdat']));
			$cpipratgl = date("Y-m-d", strtotime($_POST['cpipratgl']));
			// echo var_dump($cpipratgl);die();

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
				'contents'	=> (isset($_POST['liftoffcharge'])?$_POST['liftoffcharge']:0)
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
				'contents'	=> 0
			];
			$post_arr[] = [
				'name'		=> 'cpivoyid',
				'contents'	=> 0
			];
			$post_arr[] = [
				'name'		=> 'cpicargo',
				'contents'	=> $_POST['cpicargo']
			];
			$post_arr[] = [
				'name'		=> 'cpideliver',
				'contents'	=> ''
			];
			$post_arr[] = [
				'name'		=> 'totalcharge',
				'contents'	=> 0
			];

			$post_arr[] = [
				'name'		=> 'flag',
				'contents'	=> 2
			];	

			if($_FILES["files"] !="") {
				foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name) {
					if(is_array($_FILES["files"]["tmp_name"])) {
						$post_arr[] = [
							'name' => 'file',
							'contents'	=> fopen($_FILES["files"]['tmp_name'][$key],'r'),
							'filename'	=> $_FILES["files"]['name'][$key],
						];
						continue;
					}
				}
			}

			

			$validate = $this->validate([
	            'cpiorderno' 	=> 'required',
	            // 'cpives' 	=> 'required',
	            // 'cpivoyid' 	=> 'required',
	        ]);			

			// echo var_dump($post_arr);die();
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
				// echo var_dump($result);die();
				if(isset($result['message']) && ($result['message']!="succes created order Pra"))
				{
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Order Pra Saved.');
				session()->setFlashdata('notif_insert_container','Silahkan mengisi data Container.');
				$data['status'] = "success";
				$data['message'] = "Berasil menyimpan data";
				$data['praid'] = $result['data']["data order Pra"]['praid'];
				echo json_encode($data);die();
			}
			else 
			{
				$data['status'] = "Failled";
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
				'contents'	=> (isset($_POST['liftoffcharge'])?$_POST['liftoffcharge']:0)
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
				'contents'	=> 0
			];
			$post_arr[] = [
				'name'		=> 'cpivoyid',
				'contents'	=> 0
			];
			$post_arr[] = [
				'name'		=> 'cpicargo',
				'contents'	=> $_POST['cpicargo']
			];
			$post_arr[] = [
				'name'		=> 'cpideliver',
				'contents'	=> ''
			];
			$post_arr[] = [
				'name'		=> 'totalcharge',
				'contents'	=> 0
			];

			$post_arr[] = [
				'name'		=> 'flag',
				'contents'	=> 2
			];

			if($_FILES["files"]["name"][0]!="") {
				foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name) {
					if(is_array($_FILES["files"]["tmp_name"])) {
						$post_arr[] = array(
							'name' => 'file',
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
			$response = $this->client->request('DELETE','orderPras/deleteData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'praid' => (int)$code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Berhasil menghapus data.');
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
				'cpopr' => "-",
				'cpcust' => "-",				
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
	            'ccheight' 	=> 'required'
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
			$data['contract'] = $this->get_contract($result['data']['cpopr']);
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
	            'ccheight' 	=> 'required'
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
			$get_order = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => ['praid' => $id],
			]);

			$dt_order = json_decode($get_order->getBody()->getContents(), true);

			// contract
			// utk sementara PRCODE/CPOPR diisi manual(ada perubahan proses bisnis)
			$contract = $this->get_contract($dt_order['data']['datas'][0]['orderPraContainers'][0]['cpopr']);

			if($contract==""){
				$data['message'] = "Failled";
				$data['message_body'] = "Approval gagal. Data Contract tidak ditemukan untuk Operator ".$dt_order['data']['cpopr'];
				echo json_encode($data);die();	
			}


			// admin_tarif: coadmv
			// cleaning by: coadmm (1=by_order, 0=by_container)
			// hitung billing

			$tax = (isset($contract['cotax'])?$contract['cotax']:0);
			$total_lolo = (int)$_POST['total_lolo'];
			$total_cleaning = (int)$_POST['total_cleaning'];
			$subtotal = (int)$_POST['subtotal_bill'];
			$pajak = ($tax/100);
			$nilai_pajak = $pajak*$subtotal;
			$adm_tarif = (isset($contract['coadmv'])?$contract['coadmv']:0);
			if($total_lolo > 5000000) {
				$biaya_materai = $contract['comaterai'];
			} else {
				$biaya_materai = 0;
			}

			$totalcharge = $subtotal + $nilai_pajak + $adm_tarif + $biaya_materai;

			$data['subtotal_charge'] = $subtotal;
			$data['pajak'] = $pajak;
			$data['adm_tarif'] = $adm_tarif;
			$data['materai'] = $biaya_materai;
			$data['totalcharge'] = $totalcharge;

			// update orer pra
			$response_pra = $this->client->request('PUT','orderPras/updateData',[
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

			$result_pra = json_decode($response_pra->getBody()->getContents(), true);	

			if((isset($result_pra['status'])) && ($result_pra['status']=="Failled"))
			{
				$data['message'] = $result_pra['message'];
				echo json_encode($data);die();				
			}

			// Update pra recept
			$recept = $dt_order['orderPraRecept'];
			$response = $this->client->request('PUT','orderPras/updateData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
				    "prareceptid" => $recept['prareceptid'],
				    "praid" => $dt_order['data']['datas']['praid'],
				    "cpireceptno" => $recept['cpireceptno'],
				    "cpicurr" => "IDR",
				    "cpirate" => 1,
				    "biaya_cleaning" => $total_cleaning,
				    "tot_lolo" => $total_lolo,
				    "biaya_adm" => $adm_tarif,
				    "total_pajak" => $nilai_pajak,
				    "materai" => $biaya_materai,
				    "total_tagihan" => $totalcharge
				]
			]);
			$result_recept = json_decode($response_recept->getBody()->getContents(), true);	

			if((isset($result_recept['status'])) && ($result_recept['status']=="Failled"))
			{
				$data['message'] = $result_recept['message'];
				echo json_encode($data);die();				
			}
			

			$data['message'] = "success";
			echo json_encode($data);die();			
		}

		$get_order = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => ['praid' => $id],
		]);

		$dt_order = json_decode($get_order->getBody()->getContents(), true);
		// dd($dt_order)
		$data['hc20'] = ((isset($dt_order['data']['orderPraContainers'])&&($dt_order['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['orderPraContainers'])['hc20'] : 0);
		$data['hc40'] = ((isset($dt_order['data']['orderPraContainers'])&&($dt_order['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['orderPraContainers'])['hc40'] : 0);
		$data['hc45'] = ((isset($dt_order['data']['orderPraContainers'])&&($dt_order['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['orderPraContainers'])['hc45'] : 0);
		$data['std20'] = ((isset($dt_order['data']['orderPraContainers'])&&($dt_order['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['orderPraContainers'])['std20'] : 0);
		$data['std40'] = ((isset($dt_order['data']['orderPraContainers'])&&($dt_order['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['orderPraContainers'])['std40'] : 0);	

		// get OrderPraContainer
		$reqPraContainer = $this->client->request('GET','orderPraContainers/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'praid' => $id,
				'offset' => 0,
				'limit' => 100,
			]
		]);

		$resPraContainer= json_decode($reqPraContainer->getBody()->getContents(), true);		
		$orderPraContainers = $resPraContainer['data']['datas'];

		$data['act'] = "approval1";
		$data['group_id'] = $group_id;
		$data['data'] = $dt_order['data']['datas'][0];
		$data['orderPraContainers'] = $orderPraContainers;

		return view('Modules\PraIn\Views\approval1',$data);

	}

	// set principal,customer, biaya cleaning tabel oder pra container
	public function appv1_update_container() 
	{
		// UPDATE CONTAINER (PRINCIPAL & CSTOMER)
		if ($this->request->isAJAX()) 
		{
			$contract = $this->get_contract($_POST['cpopr']);
			$response = $this->client->request('PUT','orderPraContainers/updateData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'pracrnoid' => $_POST['pracrnoid'],
					'cpopr' => $_POST['cpopr'],
					'cpcust' => $_POST['cpcust'],
					'biaya_clean' => $_POST['biaya_clean'],
					'biaya_lolo' => $_POST['biaya_lolo'],
					'cleaning_type' => $_POST['cleaning_type']
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
		$dt_order = json_decode($response->getBody()->getContents(), true);
		// dd($dt_order);
		$data['data'] = $dt_order;
		$dt_company = $this->get_company();
		
		if ($this->request->isAJAX()) 
		{
			$dt_order  = $dt_order['data']['datas']['0']; 
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
		$contract = $this->get_contract($dt_order['data']['datas'][0]['orderPraContainers'][0]['cpopr']);
		// dd($contract);


		if($contract['coadmm']==0) {

			$coadmm = "By Order";

		} else if($contract['coadmm']==1) {
			
			$coadmm = "By Container";
		}
			$tax = (isset($contract['cotax'])?$contract['cotax']:0);
			$subtotal = 0;
			$pajak = $tax/100;
			$adm_tarif = (isset($contract['coadmv'])?$contract['coadmv']:0);
			$materai = $contract['comaterai'];
			$totalcharge = $subtotal + $pajak + $adm_tarif + $materai;

			$data['subtotal_charge'] = $subtotal;
			$data['pajak'] = $pajak;
			$data['adm_tarif'] = $adm_tarif;
			$data['materai'] = $materai;
			$data['totalcharge'] = $totalcharge;

			$data['message'] = "success";
			$data['coadmm'] = $coadmm;
			$data['data'] = $dt_order['data']['datas'][0];
			// dd($data);
			return view('Modules\PraIn\Views\proforma',$data);		
	}

	// Page siap cetak kitir order 
	public function final_order($id)
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
		// dd($dt_order);

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

		return view('Modules\PraIn\Views\cetak_kitir',$data);		
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

			$post_arr[] = [
				'name'		=> 'flag',
				'contents'	=> 1
			];

			if($_FILES["files"] !="") {
				$post_arr[] = array(
					'name' => 'file',
					'contents'	=> fopen($_FILES["files"]['tmp_name'],'r'),
					'filename'	=> $_FILES["files"]['name'],
				);
			}

			$response = $this->client->request('POST','orderPraRecepts/createNewData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'multipart' => $post_arr,
			]);

			$result = json_decode($response->getBody()->getContents(),true);
			// echo var_dump($result['message']);die();
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				if($result['message'][0]=="Data too long for column 'cpireceptno' at row 1") {
					$msg = "'Reff Tran No' Maksimal 30 karakter.";
				}
				$data['status'] = "0";
				$data['message'] = $msg;
				echo json_encode($data);die();				
			}

			$data['status'] = "1";
			$data['message'] = "Berhasil upload bukti pembayaran";
			echo json_encode($data);die();			
		}
	} 

	public function get_container_by_praid($praid) 
	{
		$response = $this->client->request('GET','orderPraContainers/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'praid' => $praid,
				'offset' => 0,
				'limit' => 100,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);			
		$i=1; 
		$html="";
		foreach($result['data']['datas'] as $row){
			$pracrid=$row['pracrnoid'];
			$html .= "<tr>
				<td>".$i."</td>
				<td>".$row['crno']."</td>
				<td>".$row['cccode']."</td>
				<td>".$row['ctcode']."</td>
				<td>".$row['cclength']."</td>
				<td>".$row['ccheight']."</td>
				<td>".((isset($row['cpopr'])&&$row['cpopr']!="")?$row['cpopr']:'-')."</td>
				<td>".((isset($row['cpife'])&&$row['cpife']==1)?'Full':'Empty')."</td>
				<td>".((isset($row['cpishold'])&&$row['cpishold']==1)?'Hold':'Release')."</td>
				<td>".$row['cpiremark']."</td>
				<td></td>
				<td><a href='#'' id='editContainer' class='btn btn-xs btn-primary edit' data-crid='".$pracrid."'>edit</a></td>
				</tr>";
			$i++; 
		}

		echo json_encode($html);
		die();
	}

	// Get Container list Approval-1
	public function appv1_containers($praid) 
	{
		$response = $this->client->request('GET','orderPraContainers/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'praid' => $praid,
				'offset' => 0,
				'limit' => 100,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);			
		$i=1; 
		$html="";
		foreach($result['data']['datas'] as $row){
			$pracrid=$row['pracrnoid'];
			$html .= "<tr>
				<td>".$i."</td>
				<td>".$row['crno']."</td>
				<td>".$row['cccode']."</td>
				<td>".$row['ctcode']."</td>
				<td>".$row['cclength']."</td>
				<td>".$row['ccheight']."</td>
				<td>".((isset($row['cpopr'])&&$row['cpopr']!="")?$row['cpopr']:'-')."</td>
				<td>".$row['biaya_lolo']."</td>
				<td>".$row['biaya_clean']."</td>
				<td>".$row['cpiremark']."</td>
				<td></td>
				<td><a href='#'' id='editContainer' class='btn btn-xs btn-primary edit' data-crid='".$pracrid."'>edit</a></td>
				</tr>";
			$i++; 
		}

		echo json_encode($html);
		die();
	}
	public function cetak_kitir($crno="")
	{

		$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
		$mpdf = new \Mpdf\Mpdf();

		$query_params = [
			"cpife1" => 1,
			"cpife2" => 0,
			"retype1" => 21,
			"retype2" => 22,
			"retype3" => 23,
			"crlastact1" => "od",
			"crlastact2" => "bi",
			"crno" => $crno
		];

		$response = $this->client->request('GET','containerProcess/getDataGateIN',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $query_params,
		]);
		
		$result = json_decode($response->getBody()->getContents(),true);
		// dd($result['data']);
		if(isset($result['data'][0])&&(count($result['data'][0])) > 0){
			$CRNO = $result['data'][0]['crno'];
			$LENGTH = $result['data'][0]['cclength'];
			$HEIGTH = $result['data'][0]['ccheight'];
			$CPIORDERNO = $result['data'][0]['cpiorderno'];
			$TYPE = $result['data'][0]['cccode'];
			$PRINCIPAL = $result['data'][0]['prcode'];
			$SHIPPER = $result['data'][0]['cpideliver'];
			$VESSEL = $result['data'][0]['vesid'];
			$VOY = $result['data'][0]['cpivoy'];
			$DATE = $result['data'][0]['cpidisdat'];
			$DESTINATION = "";
			$REMARK = $result['data'][0]['cpiremark'];
			$NOPOL = $result['data'][0]['cpinopol'];
		} else {
			$CRNO = "";
			$LENGTH = "";
			$HEIGTH = "";
			$CPIORDERNO = "";
			$TYPE = "";
			$PRINCIPAL = "";
			$SHIPPER = "";
			$VESSEL = "";
			$VOY = "";
			$DATE = "";
			$DESTINATION = "";
			$REMARK = "";
			$NOPOL = "";			
		}

		$result = json_decode($response->getBody()->getContents(), true);
			
		$barcode = $generator->getBarcode($crno, $generator::TYPE_CODE_128);		
		
		$html = '';

		$html .= '
		<html>
			<head>
				<title>Order PraIn</title>

				<style>
					body{font-family: Arial;font-size:12px;}
					.page-header{display:block;padding:0;min-height:30px;margin-bottom:30px;}
					.head-left{float:left;width:200px;padding:0px;}
					.head-right{float:left;padding:0px;margin-left:200px;text-align: right;}

					.tbl_head_prain, .tbl_det_prain{border-spacing: 0;border-collapse: collapse;}
					.tbl_head_prain td{border-collapse: collapse;padding-top:5px;}
					.t-right{text-align:right;}
					.t-left{text-align:left;}
					.t-center{text-align:center;}

					.tbl_det_prain th,.tbl_det_prain td {
						border:1px solid #666666!important;
						border-spacing: 0;
						border-collapse: collapse;
						padding:5px;
					}
					.kotak{border:1px solid #000000;padding:5px!important;}
					.line-space{border-bottom:1px solid #dddddd;margin:30px 0;}
				</style>
			</head>
		';
		$html .= '<body>
			<div class="page-header">
				<h2 align="center">PT. CONTINDO RAYA<br>
				CONTAINER RELEASE ORDER</h2>
			</div>
		';		
		$html .='
			<table class="tbl_head_prain" width="100%">
				<tbody>		
					<tr>
						<td width="100">NO.</td>
						<td>: '.$CPIORDERNO.'</td>
						<td colspan="2" class="t-center" style="width:220px;">
							<input type="text" value="" size="10" style="" value="'.$LENGTH.'">&nbsp;
							<input type="text" value="" size="10" style="" value="'.$HEIGTH.'">&nbsp;
							<input type="text" value="ST" size="5" style="">
							<input type="text" value="HC" size="5" style="">
						</td>
						<td>TYPE</td>
						<td>: '.$TYPE.'</td>
					</tr>
					<tr>
						<td class="" width="">SHIPPER</td>
						<td width="">: '.$SHIPPER.'</td>
						<td></td>
						<td></td>						
						<td class="" width="">PRINCIPAL</td>
						<td>: '.$PRINCIPAL.'</td>
					</tr>
					<tr>
						<td class="" width="">DATE</td>
						<td width="">: '.$DATE.'</td>
						<td></td>
						<td></td>						
						<td class="" width="">VESSEL</td>
						<td style="width:20%;">: '.$VESSEL.' Voy.'.$VOY.'</td>
					</tr>					
					<tr>
						<td class="" width="">CONDITION</td>
						<td width="">: 
						<input type="text" value="AV" size="7">
						<input type="text" value="DMG" size="10">
						</td>
						<td></td>
						<td></td>						
						<td class="" width="">DESTINATION</td>
						<td>:</td>
					</tr>
					<tr>
						<td class="" width="">REMARKS</td>
						<td width="">: '.$REMARK.'</td>
						<td></td>
						<td></td>						
						<td class="" width="">NO. MOBIL</td>
						<td>: '.$NOPOL.'</td>
					</tr>
					<tr>
						<td class="" width="">CONTAINER NO.</td>
						<td width="">: '.$CRNO.'</td>
						<td></td>
						<td></td>						
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td class="" width=""></td>
						<td width=""></td>
						<td></td>
						<td></td>
						<td class="" width=""></td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<div style="margin-top:100px;display:block;">
			<div style="float:left;display:inline-block;text-align:bottom;width:30%;"><i>PRINTED ON : '.date("d/m/Y H:i:s").'</i>
			</div>
			<div class="t-right" style="float:right;display:inline-block;width:250px;">
				<img src="data:image/png;base64,' . base64_encode($barcode) . '" style="height:40px;">
			</div>
			</div>
		';		
		$html .='
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		//echo $html;
		die();			
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
						<td class="t-right" width="180">Discharge Port</td>
						<td width="200">&nbsp;:&nbsp;'.$header[0]['cpidish'].'  </td>
						<td class="t-right" width="120">Pra In Reff</td>
						<td>&nbsp;:&nbsp;'.$header[0]['cpiorderno'].'</td>
					</tr>
					<tr>
						<td class="t-right">Discharge Date</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpidisdat'].'  </td>
						<td class="t-right">Pra In Date</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpipratgl'].' </td>
					</tr>
					<tr>
						<td class="t-right">Lift Off Charges In Depot</td>
						<td class="t-left">&nbsp;:&nbsp; '.((isset($header[0]['liftoffcharge'])&&$header[0]['liftoffcharge']==1)?"yes" : "no").'</td>
						<td class="t-right">Ref In N0 #</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpirefin'].'  </td>
					</tr>
					<tr>
						<td class="t-right">Depot</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpdepo'].' </td>
						<td class="t-right">Time In</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpijam'].'  </td>
					</tr>
					<tr>
						<td class="t-right"></td>
						<td class="t-left"></td>
						<td class="t-right">Vessel</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpives'].' </td>
					</tr>
					<tr>
						<td class="t-right"></td>
						<td class="t-left"></td>
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
						<th>Principal</th>
						<th>Lift Off</th>
						<th>Cleaning</th>
						<th>Subtotal</th>						
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
						<td>'.$row['cpopr'].'</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>';

					$no++;
				}
		$html .='
				</tbody>
			</table>
			<br>
			<table class="tbl-form" width="100%">
				<tbody>
					<tr>
						<td class="t-right">Pajak</td>
						<td width="130">: </td>
					</tr>		
					<tr>
						<td class="t-right">Adm Tarif</td>
						<td width="130">: </td>
					</tr>	
					<tr>
						<td class="t-right">Materai</td>
						<td width="130">: </td>
					</tr>			
					<tr>
						<th class="t-right">Total Charge</th>
						<td width="130">: </td>
					</tr>
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
				$data['data'] = $result['data']['rows'][0];
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

			// get principal
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

			$principal = $result['data'];

			$contract = $this->get_contract($code);

			// Get order_pra_container
			$response_cr = $this->client->request('GET','orderPraContainers/getDetailData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'pracrnoid' => $_POST['pracrnoid'],
				]
			]);

			$result_cr = json_decode($response_cr->getBody()->getContents(), true);	
			$dt_cr = $result_cr['data'];

			// Biaya_LOLO
			if(floatval($dt_cr['cclength'])<=20) {
				$biaya_lolo=$contract['colofmty20'];
			} else if(floatval($dt_cr['cclength'])==40) {
				$biaya_lolo=$contract['colofmty40'];
			} else if(floatval($dt_cr['cclength'])==45) {
				$biaya_lolo=$contract['colofmty45'];
			}

			$data['status'] = "Success";
			$data['data'] = $principal;
			$data['biaya_clean'] = (isset($contract['deposit'])?$contract['deposit']:0);
			$data['biaya_lolo'] = $biaya_lolo;
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

	// function generate_barcode($str="") 
	// {
	// 	$generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
	// 	$barcode = $generator->getBarcode($str, $generator::TYPE_CODE_128);
	// 	return $barcode;
	// }

}