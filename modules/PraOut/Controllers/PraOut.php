<?php
namespace Modules\PraOut\Controllers;

use App\Libraries\Ciqrcode;

use function bin2hex;
use function file_exists;
use function mkdir;

class PraOut extends \CodeIgniter\Controller
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
					'limit'	=> $limit,
					'pracode' => 'PO'
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
					'pracode' => 'PO'
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
		// return view('Modules\PraOut\Views\tab_base',$data);
		return view('Modules\PraOut\Views\index',$data);
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
		$data['header'] = $result['data']['datas'];

		$pratgl = $data['header'][0]['cpipratgl'];
		$recept = recept_by_praid($data['header'][0]['praid']);
		$invoice_number = "KW." . date("Ymd",strtotime($pratgl)) . ".000000" . $recept['prareceptid'];
		$data['prcode'] = $prcode;
		$data['cucode'] = $prcode;
		// $data['data'] = $result['data']['datas'];
		$data['detail'] = $data['header'][0]['orderPraContainers'];		
		$data['invoice_number'] = $invoice_number;
		return view('Modules\PraOut\Views\view',$data);
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
			} else if((floatval($data['ccheight'])>8.5) && (floatval($data['ccheight'])<9.5) && (floatval($data['cclength'])==40)) {
				$hc40=$hc40+1;
			} else if((floatval($data['ccheight']))>=9.5 && (floatval($data['cclength'])==40)) {
				$hc45=$hc45+1;
			}

			if((floatval($data['ccheight'])<=8.5) && (floatval($data['cclength'])<=20)) {
				$std20=$std20+1;
			} else if((floatval($data['ccheight'])<=8.5) && (floatval($data['cclength'])==40)) {
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
				'contents'	=> ''
			];
			$post_arr[] = [
				'name'		=> 'cpideliver',
				'contents'	=>  $_POST['cpideliver']
			];
			$post_arr[] = [
				'name'		=> 'totalcharge',
				'contents'	=> 0
			];
			$post_arr[] = [
				'name'		=> 'typedo',
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
				$data['message'] = "Berhasil menyimpan data";
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

		return view('Modules\PraOut\Views\add',$data);		
	}	

	public function edit($id)
	{
		check_exp_time();
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if ($this->request->isAJAX()) 
		{
			$cpidisdat = date('Y-m-d',strtotime($_POST['cpidisdat']));
			$cpipratgl = date('Y-m-d',strtotime($_POST['cpipratgl']));

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
				'contents'	=> (isset($_POST['cpives'])?$_POST['cpives']:"-")
			];
			$post_arr[] = [
				'name'		=> 'cpivoyid',
				'contents'	=> (isset($_POST['cpivoyid'])?(int)$_POST['cpivoyid']:0)
			];
			$post_arr[] = [
				'name'		=> 'cpicargo',
				'contents'	=> ''
			];
			$post_arr[] = [
				'name'		=> 'cpideliver',
				'contents'	=> $_POST['cpideliver']
			];
			$post_arr[] = [
				'name'		=> 'totalcharge',
				'contents'	=> 0
			];
			$post_arr[] = [
				'name'		=> 'typedo',
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
		
		$get_order = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => ['praid' => $id],
		]);

		$dt_order = json_decode($get_order->getBody()->getContents(), true);
		// dd($dt_order);
		$data['hc20'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc20'] : 0);
		$data['hc40'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc40'] : 0);
		$data['hc45'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc45'] : 0);
		$data['std20'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['std20'] : 0);
		$data['std40'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['std40'] : 0);	

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

		$data['act'] = "edit";
		$data['group_id'] = $group_id;
		$data['data'] = $dt_order['data']['datas'][0];
		$data['orderPraContainers'] = $orderPraContainers;
		$data['depo'] = $this->get_depo($data['data']['cpdepo']);
		return view('Modules\PraOut\Views\edit',$data);		
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
				'cpiremark' => $_POST['cpiremark'],
				'sealno' => $_POST['sealno']
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
		    	// jika kontaner sudah ada di depo
		    	$container = $this->get_container($_POST['crno']);
		    	if (($container['crlastact'] == "CO" &&  $container['crlastcond'] == "AC") ||  $container['lastact'] == "AC") {

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

		    	} else {

					$data['message'] = "Invalid Container.";
					echo json_encode($data);die();
		    	}

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
				'cpiremark' => $_POST['cpiremark'],
				'sealno' => $_POST['sealno']
			];

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
			// dd($dt_order);
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
			$total_cleaning = 0;
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
			// $recept = $dt_order['data']['datas'][0]['orderPraRecept'];
			$response_recept = $this->client->request('POST','orderPraRecepts/createNewData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
				    "praid" => $dt_order['data']['datas'][0]['praid'],
				    "cpireceptno" => '-',
				    "cpicurr" => "IDR",
				    "cpirate" => 1,
				    "biaya_cleaning" => 0,
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
		// dd($dt_order);
		$data['hc20'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc20'] : 0);
		$data['hc40'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc40'] : 0);
		$data['hc45'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc45'] : 0);
		$data['std20'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['std20'] : 0);
		$data['std40'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['std40'] : 0);	

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
		$data['depo'] = $this->get_depo($data['data']['cpdepo']);
		return view('Modules\PraOut\Views\approval1',$data);

	}

	// set principal,customer, biaya cleaning tabel oder pra container
	public function appv1_update_container() 
	{
		check_exp_time();
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
					'biaya_clean' => 0,
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
		$data['data'] = $dt_order['data']['datas'][0];
		$recept = $data['data']['orderPraRecept'][0];
		$dt_company = $this->get_company();
		$data['recept'] = $recept;
		$data['depo'] = $this->get_depo($data['data']['cpdepo']);

		// bukti_bayar
		if(check_bukti_bayar($dt_order['data']['datas'][0]['praid'])==true) {
			$recept_files = $dt_order['data']['datas'][0]['orderPraRecept'][1];
			$response_bukti = $this->client->request('GET','orderPraRecepts/getDetailData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => [
					'prareceptid' => $recept_files['prareceptid']
				]
			]);			
			$result_bukti = json_decode($response_bukti->getBody()->getContents(), true);
			$bukti_bayar = $result_bukti['data'];
		} else {
			$bukti_bayar = "";
		}


		
		if ($this->request->isAJAX()) 
		{
			// $dt_order  = $dt_order['data']['datas'][0]; 
			$containers = $data['data']['orderPraContainers'];
			$cprocess_params = [];
			
			if($containers!="") {
				$no=0;
				foreach ($containers as $c) {
				// echo var_dump($c);die();
/*
cpotgl,
// cpopr,
// cpopr1,
// cpcust,
// cpcust1,
// cporeceptno,
//cpoorderno,
//cporefout,
//cpopratgl,
// cpofe,
//cpocargo,
//cpoves,
//cpovoy,
//cpoloaddat,
//cpojam,
cpotruck,
svsurdat,
syid,
cpoeir,
cpochrgbm,
cpopaidbm,
cpoterm,
cpoload,
cposeal,
//cporeceiv,
cpodpp,
cpodriver,
cponopol,
cporemark,
cpid,		
*/			
					$cr_result = $this->get_container($c['crno']);
					$cprocess_params[$no] = [
						"crno" => $c['crno'],
					    // "CPOPR" => '',
					    "cpopr1" => $c['cpopr'],
					    // "CPCUST" => '',					    
					    "cpcust1" => $c['cpcust'],					    
					    // "CPDEPO" => $data['data']['cpdepo'],
					    // "SPDEPO" => $dt_company['sdcode'],
					    "cpofe" => $c['cpife'],
					    "cpocargo" => '',
					    "cpopratgl" => $data['data']['cpipratgl'],
					    "cporefout" => $data['data']['cpirefin'],
					    "cpoves" => $data['data']['cpives'],
					    // "cpodish" => $data['data']['cpidish'],
					    "cpoloaddat" => $data['data']['cpidisdat'],
					    "cpojam" => $data['data']['cpijam'],
					    // "CPOCHRGBB" => 1,
					    "cporeceiv" => $data['data']['cpideliver'],
					    "cpoorderno" => $data['data']['cpiorderno'],
					    // "CPOSHOLD" => $c['cpishold'],
					    "cporemark" => $c['cpiremark'],
					    // "CPOVOYID" => $data['data']['cpivoyid'],
					    "cpovoy" => $data['data']['voyages']['voyno'],
					    // "CPOSTATUS" => 0,
						"cpid" => $cr_result['crcpid'],
					];	

					// get One Container
					// $cr_req = $this->client->request('GET','containers/listOne',[
					// 	'headers' => [
					// 		'Accept' => 'application/json',
					// 		'Authorization' => session()->get('login_token')
					// 	],
					// 	'form_params' => ['crNo' => $c['crno']],
					// ]);

					// $cr_result = json_decode($cr_req->getBody()->getContents(), true);
					// if(isset($cr_result)) {
					// 	$CRCPID = [
					// 	];
					// 	array_push($cprocess_params[$no], $CRCPID);
					// }		

					// Container Process
					$cp_response = $this->client->request('PUT','gateout/updateGateOut',[
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
					// echo var_dump($cprocess_params[$no]);
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
						'appv' => 2,
						'cpilunas' => 1,
						'totalcharge' => $recept['total_tagihan']
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

		// $contract = $this->get_contract("CTP");
		// $recept = $this->get_recept($praid);

		// Quantity HC_STD
		$data['hc20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc20'] : 0);
		$data['hc40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc40'] : 0);
		$data['hc45'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc45'] : 0);
		$data['std20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std20'] : 0);
		$data['std40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std40'] : 0);	

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

		$contract = $this->get_contract($data['data']['orderPraContainers'][0]['cpopr']);

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
		$data['containers'] = $orderPraContainers;
		$data['bukti_bayar'] = $bukti_bayar;
		return view('Modules\PraOut\Views\approval2',$data);		
	}	

	public function proforma($id) 
	{
		check_exp_time();
		$get_order = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => ['praid' => $id],
		]);

		$dt_order = json_decode($get_order->getBody()->getContents(), true);
		$contract = $this->get_contract($dt_order['data']['datas'][0]['orderPraContainers'][0]['cpopr']);

		// Quantity HC_STD
		$data['hc20'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc20'] : 0);
		$data['hc40'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc40'] : 0);
		$data['hc45'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['hc45'] : 0);
		$data['std20'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['std20'] : 0);
		$data['std40'] = ((isset($dt_order['data']['datas'][0]['orderPraContainers'])&&($dt_order['data']['datas'][0]['orderPraContainers']!=null)) ? $this->hitungHCSTD($dt_order['data']['datas'][0]['orderPraContainers'])['std40'] : 0);

		// bukti_bayar
		if(check_bukti_bayar($dt_order['data']['datas'][0]['praid'])==true) {
			$recept_files = $dt_order['data']['datas'][0]['orderPraRecept'][1];
			$response_bukti = $this->client->request('GET','orderPraRecepts/getDetailData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => [
					'prareceptid' => $recept_files['prareceptid']
				]
			]);			
			$result_bukti = json_decode($response_bukti->getBody()->getContents(), true);
			$bukti_bayar = $result_bukti['data'];
		} else {
			$bukti_bayar = "";
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
		$data['data'] = $dt_order['data']['datas'][0];
		$data['recept'] = $dt_order['data']['datas'][0]['orderPraRecept'][0];
		$data['bukti_bayar'] = $bukti_bayar;
		$data['contract'] = $dt_order['data']['datas'][0];
		$data['depo'] = $this->get_depo($data['data']['cpdepo']);
		// dd($data);
		return view('Modules\PraOut\Views\proforma',$data);		
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

		// bukti_bayar
		$recept = $dt_order['orderPraRecept'][0];
		$data['recept'] = $recept;		
		if(check_bukti_bayar($dt_order['praid'])==true) {
			$recept_files = $dt_order['orderPraRecept'][1];
			$response_bukti = $this->client->request('GET','orderPraRecepts/getDetailData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => [
					'prareceptid' => $recept_files['prareceptid']
				]
			]);			
			$result_bukti = json_decode($response_bukti->getBody()->getContents(), true);
			$bukti_bayar = $result_bukti['data'];
		} else {
			$bukti_bayar = "";
		}

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
					    // "CPICARGO" => $dt_order['cpicargo'],
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

		$contract = $this->get_contract($dt_order['orderPraContainers'][0]['cpopr']);

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

		$data['data'] = $dt_order;
		$data['bukti_bayar'] = $bukti_bayar;
		$data['depo'] = $this->get_depo($data['data']['cpdepo']);
		$data['contract'] = $this->get_contract($data['data']['cpopr']);
		$data['hc20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc20'] : 0);
		$data['hc40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc40'] : 0);
		$data['hc45'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc45'] : 0);
		$data['std20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std20'] : 0);
		$data['std40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std40'] : 0);	
		return view('Modules\PraOut\Views\cetak_kitir',$data);		
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
				'contents'	=> (int)$_POST['praid']
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
				'contents'	=> (int)$_POST['cpirate']
			];			
			$post_arr[] = [
				'name'		=> 'biaya_cleaning',
				'contents'	=> 0
			];				
			$post_arr[] = [
				'name'		=> 'tot_lolo',
				'contents'	=> (int)$_POST['tot_lolo']
			];			
			$post_arr[] = [
				'name'		=> 'biaya_adm',
				'contents'	=> (int)$_POST['biaya_adm']
			];			
			$post_arr[] = [
				'name'		=> 'total_pajak',
				'contents'	=> (int)$_POST['total_pajak']
			];
			$post_arr[] = [
				'name'		=> 'materai',
				'contents'	=> (int)$_POST['materai']
			];
			$post_arr[] = [
				'name'		=> 'total_tagihan',
				'contents'	=> (int)$_POST['total_tagihan']
			];
			$post_arr[] = [
				'name'		=> 'flag',
				'contents'	=> 1
			];
			// echo var_dump($_FILES);die();
			if($_FILES["files"] !="") {
				$post_arr[] = array(
					'name' => 'file',
					'contents'	=> fopen($_FILES["files"]['tmp_name'],'r'),
					'filename'	=> $_FILES["files"]['name']
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

	public function get_bukti_bayar($prareceptid) {
		// $recept = $dt_order['data']['datas'][0]['orderPraRecept'];
		if(isset($arr_recept)) {
			$response = $this->client->request('GET','orderPraRecepts/getDetailData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => [
					'prareceptid' => $prareceptid
				]
			]);			
			$result = json_decode($response->getBody()->getContents(), true);
			return $result['data'];
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
				<td>".$row['cpiremark']."</td>
				<td>".$row['sealno']."</td>
				<td></td>
				<td>";

				if(isset($act)&&($act=='edit')):
				$html .= "<a href='#' id='editContainer' class='btn btn-xs btn-primary edit' data-crid='".$row['pracrnoid']."'>edit</a>";
				endif;

			$html .= "</td>
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
		// echo var_dump($result);			
		$i=1; 
		$total_lolo = 0;
		$total_cleaning = 0;
		$total_biaya_lain = 0;
		$total_pph23 = 0;
		$total = 0;			
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
				<td>".$row['cpiremark']."</td>
				<td>".$row['sealno']."</td>
				<td><a href='#'' id='editContainer' class='btn btn-xs btn-primary edit' data-crid='".$pracrid."'>edit</a></td>
				</tr>";
			$i++; 
			$total_lolo = $total_lolo+$row['biaya_lolo'];	
		}
		$total = $total_lolo+$total_biaya_lain+$total_cleaning;				
		$html .= "<input type='hidden' name='total_lolo' id='total_lolo' value='".$total_lolo."'>";
		$html .= "<input type='hidden' name='total_cleaning' id='total_cleaning' value='".$total_cleaning."'>";
		$html .= "<input type='hidden' name='total_biaya_lain' id='total_biaya_lain' value='".$total_biaya_lain."'>";
		$html .= "<input type='hidden' name='total_pph23' id='total_pph23' value='".$total_pph23."'>";
		$html .= "<input type='hidden' name='subtotal_bill' id='subtotal_bill' value='".$total."'>";
		echo json_encode($html);
		die();
	}
	public function cetak_kitir($crno="",$cpiorderno="",$praid="")
	{

		$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80,236]]);
		// $mpdf->showImageErrors = true;
		$query_params = [
			"crno" => $crno,
			"cpoorderno" => $cpiorderno
		];

		$response = $this->client->request('GET','containerProcess/getKitirPraOut',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $query_params,
		]);
		
		$result = json_decode($response->getBody()->getContents(),true);
		// dd($result);
		$recept = recept_by_praid($praid);

		if(isset($result['data'][0])&&(count($result['data'][0])) > 0){
			$qrcode = $this->generate_qrcode($result['data'][0]['cpid']);
			$CRNO = $result['data'][0]['crno'];
			$REFOUT = $result['data'][0]['cporefout'];
			$CPID = $result['data'][0]['cpid'];
			$LENGTH = $result['data'][0]['cclength'];
			$HEIGHT = $result['data'][0]['ccheight'];
			$CPOORDERNO = $result['data'][0]['cpoorderno'];
			$TYPE = $result['data'][0]['cccode'];
			$CODE = $result['data'][0]['ctcode'];
			$PRINCIPAL = $result['data'][0]['cpopr1'];
			$SHIPPER = $result['data'][0]['cporeceiv'];
			$VESSEL = $result['data'][0]['cpoves'];
			$VOY = $result['data'][0]['cpovoyid'];
			$DATE = $result['data'][0]['cpoloaddat'];
			$DESTINATION = "";
			$REMARK = $result['data'][0]['cporemark'];
			$NOPOL = $result['data'][0]['cponopol'];
			$QRCODE_IMG = ROOTPATH .'/public/media/qrcode/'.$qrcode['content'] . '.png';
			$CPOPRATGL = $result['data'][0]['cpopratgl'];
			$CPORECEPTNO = $result['data'][0]['cporeceptno'];
			$CPORECEIV = $result['data'][0]['cporeceiv'];
			// $QRCODE_CONTENT = $qrcode['content'];
		} else {
			$CODE = "";
			$CRNO = "";
			$CPID = "";
			$REFIN = "";
			$LENGTH = "";
			$HEIGHT = "";
			$CPOORDERNO = "";
			$TYPE = "";
			$PRINCIPAL = "";
			$SHIPPER = "";
			$VESSEL = "";
			$VOY = "";
			$DATE = "";
			$DESTINATION = "";
			$REMARK = "";
			$NOPOL = "";	
			$QRCODE_IMG = "";
			$QRCODE_CONTENT = ""; 
			$CPORECEPTNO = "";
			$CPOPRATGL = "";
			$CPORECEIV = "";
		}

		$result = json_decode($response->getBody()->getContents(), true);
			
		$barcode = $generator->getBarcode($crno, $generator::TYPE_CODE_128);		
		
		$html = '';

		$html .= '
		<html>
			<head>
				<title>Order PraOut | Print Kitir</title>
				<link href="'.base_url().'/public/themes/smartdepo/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>			
					.page-header{display:block;margin-bottom:20px;line-height:0.3;}
					table{line-height:1.75;display:block;}
					table td{font-weight:bold;font-size:12px;}
					.t-right{text-align:right;}
					.t-left{text-align:left;}
					.t-center{text-align:center;}
					.bordered {
						border:1px solid #666666;
						padding:3px;
					}
					.kotak1{border:1px solid #000000;padding:3px;width:20%;text-align:center;}
					.kotak2{border:1px solid #ffffff;padding:3px;width:20%;text-align:center;}
					.kotak3{border:1px solid #000000;padding:3px;width:20%;text-align:center;}
			        @media print {
			            @page {
			                margin: 0 auto;
			                sheet-size: 300px 250mm;
			            }
			            html {
			                direction: rtl;
			            }
			            html,body{margin:0;padding:0}
			            .wrapper {
			                width: 250px;
			                margin: auto;
			                text-align: justify;
			            }
			           .t-center{text-align: center;}
			           .t-right{text-align: right;}
			        }						
				</style>
			</head>
		';
		$html .= '<body onload="window.print()">
			<div class="wrapper">

			<div class="page-header t-center">
				<h5 style="line-height:0.5;font-weight:bold;padding-top:20px;">KITIR MUAT</h3>
				<h4 style="text-decoration: underline;line-height:0.5;">'.$REFOUT.'</h3>
				<img src="' . $QRCODE_IMG . '" style="height:120px;">
				<h5 style="text-decoration: underline;line-height:0.5;">'.$CPID.'</h4>
			</div>
		';		
		$html .='
			<table border-spacing: 0; border-collapse: collapse; width="100%">	
				<tr>
					<td colspan="2" style="font-weight:normal;">NO. '.$CPOORDERNO.'
					</td>
					<td colspan="2" style="font-weight:normal;text-align:right;">( '.date("d/m/Y",strtotime($CPOPRATGL)).' )</td>
				</tr>
				<tr>
					<td style="width:40%;">CONTAINER NO.</td>
					<td colspan="3"> <h5 style="margin:0;padding:0;font-weight:normal;">:&nbsp;'.$CRNO.'</h5></td>
				</tr>
				<tr>
					<td>PRINCIPAL</td>
					<td colspan="3">:&nbsp;'.$PRINCIPAL.'</td>
				</tr>
				<tr>
					<td>L/OFF RECEIPT</td>
					<td colspan="3">:&nbsp;'.$CPORECEPTNO.'</td>
				</tr>
				<tr>
					<td>DET RECEIPT</td>
					<td colspan="3">:</td>
				</tr>
				<tr>
					<td>SIZE</td>
					<td colspan="3">:&nbsp;'.$CODE.' '.$LENGTH.'/'.$HEIGHT.'</td>
				</tr>
				<tr>
					<td>DELIVERER</td>
					<td colspan="3">:</td>
				</tr>
				<tr>
					<td colspan="4" style="padding-bottom:10px;"><h5 style="font-weight:normal;">'.$CPORECEIV.'</h5></td>
				</tr>
				<tr  rowspan="4">
					<td colspan="4">&nbsp;</td>
				</tr>				
			</table>
			<table style="border-spacing: 3px; border-collapse: separate;" width="100%">
				<tr>
					<td width="40%">CONDITION</td>
					<td class="kotak3">AC</td>
					<td class="kotak3">AU</td>
					<td class="kotak3">DN</td>
				</tr>
				<tr>
					<td>CLEANING</td>
					<td class="kotak3">WW</td>
					<td class="kotak3">SC</td>
					<td class="kotak3">CC</td>
				</tr>
				<tr>
					<td>REPAIR</td>
					<td class="kotak3">Y</td>
					<td class="kotak3">N</td>
					<td class="">&nbsp;</td>
				</tr>
				<tr>
					<td>VESSEL</td>
					<td colspan="3">:&nbsp;'.$VESSEL.'</td>
				</tr>
				<tr>
					<td>EXPIRED</td>
					<td colspan="3">:&nbsp;</td>
				</tr>
				<tr>
					<td>REMARK</td>
					<td colspan="3">:&nbsp;'.$REMARK.'</td>
				</tr>
				<tr>
					<td>TRUCK ID</td>
					<td colspan="3">:&nbsp;'.$NOPOL.'</td>
				</tr>		
			</table>
			<table width="100%">	
				<tr>
					<td>SURVEYOR</td>
					<td colspan="3">&nbsp;&nbsp;( _____________ )</td>
				</tr>
				<tr  rowspan="4">
					<td colspan="4">&nbsp;</td>
				</tr>
				<tr>
					<td>KERANI</td>
					<td colspan="3">&nbsp;&nbsp;( _____________ )</td>
				</tr>
				<tr  rowspan="4">
					<td colspan="4">&nbsp;</td>
				</tr>
				<tr>
					<td>GATE OFFICER</td>
					<td colspan="3">&nbsp;&nbsp;( _____________ )</td>
				</tr>
			</table>
			</div>
		';		
		$html .='
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		// echo $html;
		die();			
	}

	// Proforma Print invoice 1
	public function print_invoice1($id) 
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
		$header = $result['data']['datas'][0];
		$pratgl = $header['cpipratgl'];
		$recept = recept_by_praid($header['praid']);

		if($recept==""){
			$invoice_number ="-";	
		} else {
			$invoice_number = "INV." . date("Ymd",strtotime($pratgl)) . ".000000" . $recept['prareceptid'];
		}

		$det_container = $header['orderPraContainers'];
		$contract = $this->get_contract($det_container[0]['cpopr']);
		$depo = $this->get_depo($header['cpdepo']);
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
				<title>Order PraOut</title>

				<style>
					body{font-family: Arial;font-size:12px;}
					.page-header{display:block;border-bottom:2px solid #aaa;padding:0;min-height:30px;margin-bottom:30px;}
					.head-left{float:left;width:200px;padding:0px;}
					.head-center{margin-left:200px;width:200px;padding:0px;text-align:center;}
					.head-right{float:left;padding:0px;margin-right:0px;text-align: right;}

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
				<table width="100%">
				<tr>
				<td><h4>PT. CONTINDO RAYA</h4></td>
				<td class="t-center"><b>'.$invoice_number.'</b></td>
				<td class="t-right"><p>PADANG, '.date('d/m/Y').'</p></td>
				</tr>
				</table>
			</div>
		';
		$html .='
			<table class="tbl_head_prain" width="100%">
				<tbody>
					<tr>
						<td class="t-right" width="180">Discharge Port</td>
						<td width="200">&nbsp;:&nbsp;'.$header['cpidish'].'  </td>
						<td class="t-right" width="120">Pra In Reff</td>
						<td>&nbsp;:&nbsp;'.$header['cpiorderno'].'</td>
					</tr>
					<tr>
						<td class="t-right">Discharge Date</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpidisdat'].'  </td>
						<td class="t-right">Pra In Date</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpipratgl'].' </td>
					</tr>
					<tr>
						<td class="t-right">Lift Off Charges In Depot</td>
						<td class="t-left">&nbsp;:&nbsp; '.((isset($header['liftoffcharge'])&&$header['liftoffcharge']==1)?"yes" : "no").'</td>
						<td class="t-right">Ref In N0 #</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpirefin'].'  </td>
					</tr>
					<tr>
						<td class="t-right">Depot</td>
						<td class="t-left">&nbsp;:&nbsp;'.$depo['dpname'].' </td>
						<td class="t-right">Time In</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpijam'].'  </td>
					</tr>
					<tr>
						<td class="t-right"></td>
						<td class="t-left"></td>
						<td class="t-right">Vessel</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpives'].' </td>
					</tr>
					<tr>
						<td class="t-right"></td>
						<td class="t-left"></td>
						<td class="t-right">Voyage</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['voyages']['voyno'].' </td>
					</tr>
					<tr>
						<td class="t-right">&nbsp;</td>
						<td class="t-left">&nbsp;</td>
						<td class="t-right">Vessel Operator</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['vessels']['vesopr'].' </td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td class="t-right">Redeliverer</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpideliver'].' </td>
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
						<th>Principal</th>
						<th>Gate In Date</th>
						<th>Lift Off</th>				
					</tr>
				</thead>
				<tbody>';
				$no=1;
				$biaya_lolo=0;
				$total_liftoff = 0;
				foreach($det_container as $row){
					$html .='
					<tr>
						<td>'.$no.'</td>
						<td>'.$row['crno'].'</td>
						<td>'.$row['cccode'].'</td>
						<td>'.$row['ctcode'].'</td>
						<td>'.$row['cclength'].'</td>
						<td>'.$row['ccheight'].'</td>
						<td>'.((isset($row['cpife'])&&$row['cpife']==1) ? "full" : "Empty").'</td>
						<td>'.$row['cpopr'].'</td>
						<td>'.$row['cpigatedate'].'</td>
						<td>'.number_format($row['biaya_lolo'],2).'</td>
					</tr>';

					$no++;
					$total_liftoff = $total_liftoff+$row['biaya_lolo'];
				}

				$tax = (isset($contract['cotax'])?$contract['cotax']:0);
				// $total_lolo = (int)$_POST['total_lolo'];
				// $total_cleaning = (int)$_POST['total_cleaning'];
				$subtotal = $total_liftoff;
				$pajak = ($tax/100);
				$nilai_pajak = $pajak*$subtotal;
				$adm_tarif = (isset($contract['coadmv'])?$contract['coadmv']:0);
				if($subtotal > 5000000) {
					$biaya_materai = $contract['comaterai'];
				} else {
					$biaya_materai = 0;
				}

				$totalcharge = $subtotal + $nilai_pajak + $adm_tarif + $biaya_materai;

				$html .='<tr><th colspan="9" class="t-right">Total Lift Off</th><th>'.number_format($subtotal,2).'</th></tr>';
		$html .='
				</tbody>
			</table>
			<br>
			<table class="tbl-form" width="100%">
				<tbody>			
					<tr><td></td><td></td></tr>		
					<tr>
						<td class="t-right">Pajak</td>
						<td width="130">: ' . number_format($nilai_pajak,2) . '</td>
					</tr>
					<tr>
						<td class="t-right">Adm Tarif</td>
						<td width="130">: ' . number_format($adm_tarif,2) . '</td>
					</tr>	
					<tr>
						<td class="t-right">Materai</td>
						<td width="130">: ' . number_format($biaya_materai,2) . '</td>
					</tr>			
					<tr>
						<th class="t-right">Total Charge</th>
						<td width="130">: ' . number_format($totalcharge,2) . '</td>
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

	// Proforma Print invoice 2 (Deposit)
	public function print_invoice2($id) 
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
		$header = $result['data']['datas'][0];

		$pratgl = $header['cpipratgl'];
		$recept = recept_by_praid($header['praid']);

		if($recept==""){
			$invoice_number ="-";	
		} else {
			$invoice_number = "INV." . date("Ymd",strtotime($pratgl)) . ".000000" . $recept['prareceptid'];
		}
				
		$detail = $header['orderPraContainers'];
		$depo = $this->get_depo($header['cpdepo']);
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
				<title>Order PraOut</title>

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
				<table width="100%">
				<tr>
				<td><h4>PT. CONTINDO RAYA</h4></td>
				<td class="t-center"><b>'.$invoice_number.'</b></td>
				<td class="t-right"><p>PADANG, '.date('d/m/Y').'</p></td>
				</tr>
				</table>
			</div>
		';
		$html .='
			<table class="tbl_head_prain" width="100%">
				<tbody>
					<tr>
						<td class="t-right" width="180">Discharge Port</td>
						<td width="200">&nbsp;:&nbsp;'.$header['cpidish'].'  </td>
						<td class="t-right" width="120">Pra In Reff</td>
						<td>&nbsp;:&nbsp;'.$header['cpiorderno'].'</td>
					</tr>
					<tr>
						<td class="t-right">Discharge Date</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpidisdat'].'  </td>
						<td class="t-right">Pra In Date</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpipratgl'].' </td>
					</tr>
					<tr>
						<td class="t-right">Lift Off Charges In Depot</td>
						<td class="t-left">&nbsp;:&nbsp; '.((isset($header['liftoffcharge'])&&$header['liftoffcharge']==1)?"yes" : "no").'</td>
						<td class="t-right">Ref In N0 #</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpirefin'].'  </td>
					</tr>
					<tr>
						<td class="t-right">Depot</td>
						<td class="t-left">&nbsp;:&nbsp;'.$depo['dpname'].' </td>
						<td class="t-right">Time In</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpijam'].'  </td>
					</tr>
					<tr>
						<td class="t-right"></td>
						<td class="t-left"></td>
						<td class="t-right">Vessel</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpives'].' </td>
					</tr>
					<tr>
						<td class="t-right"></td>
						<td class="t-left"></td>
						<td class="t-right">Voyage</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['voyages']['voyno'].' </td>
					</tr>
					<tr>
						<td class="t-right">&nbsp;</td>
						<td class="t-left">&nbsp;</td>
						<td class="t-right">Vessel Operator</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['vessels']['vesopr'].' </td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td class="t-right">Redeliverer</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpideliver'].' </td>
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
						<th>Deposit</th>					
					</tr>
				</thead>
				<tbody>';
				$no=1;
				$total_deposit=0;
				foreach($detail as $row){
					if($row['biaya_clean']!=0) {
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
							<td>'.$row['biaya_clean'].'</td>
						</tr>';
	
						$no++;
						$total_deposit=$total_deposit+$row['biaya_clean'];
					}
				}

				$html .='<tr><th colspan="9" class="t-right">Total</th><th>'.$total_deposit.'</th></tr>';

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
			$praid = $_POST['praid'];
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
				if(isset($result['success']) && ($result['success']==false))
				{
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

		    	// check table order_pra_Container
				$reqPraContainer = $this->client->request('GET','orderPraContainers/getAllData',[
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

				$resPraContainer= json_decode($reqPraContainer->getBody()->getContents(), true);		
				$orderPraContainers = $resPraContainer['data']['datas'];
				if(isset($orderPraContainers) && ($orderPraContainers!=null)) {
					foreach($orderPraContainers as $opc) {
						$crnos[] = $opc['crno'];
					}
					if(in_array($ccode,$crnos)) {
						$data['status'] = "Failled";
						$data['message'] = "Container ini sudah diinput";
						echo json_encode($data);die();
					}
				}	
							
				$container = $result['data']['rows'][0];
				// echo var_dump($container['crlastact']);die();
				// if(($container['crlastact']=="WE")OR$container['crlastact']=="WS") {
					$data['status'] = "Success";
					$data['message'] = $result['message'];
					$data['data'] = $container;
					$data['container_code'] = $data['data']['container_code'];
					echo json_encode($data);die();
				// } else {				
				// 	$data['status'] = "Failled";
				// 	$data['message'] = "Container Belum diproses";
				// 	echo json_encode($data);die();					
				// }

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
			$data['biaya_clean'] = 0;
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
			],
			'form_params' => [
				'pracode' => 'PO'
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

	public function get_depo($code) 
	{
		$response = $this->client->request('GET','depos/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'dpcode' => $code
			]
		]);	
		$result = json_decode($response->getBody()->getContents(),true);
		$depo = $result['data'];

		return $depo; 
		die();
	}

	public function get_container($crno) {
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

		return $result['data'];
	}

	public function check_container($crno) {
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
		if(isset($result['status'])&&($result['status']=="Failled")) {
			$status = 0;
		} else {
			$status = 1;
		}
		return $status;
	}

    public function generate_qrcode($data)
    {
        /* Load QR Code Library */
        // $this->load->library('ciqrcode');
        $ciQrcode = new Ciqrcode();

        /* Data */
        $hex_data   = bin2hex($data);
        $save_name  = $data . '.png';

        /* QR Code File Directory Initialize */
        $dir = 'public/media/qrcode/';
        if (! file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $config['cacheable']    = true;
        $config['imagedir']     = $dir;
        $config['quality']      = true;
        $config['size']         = '1024';
        $config['black']        = [255, 255, 255];
        $config['white']        = [255, 255, 255];
        $ciQrcode->initialize($config);

        /* QR Data  */
        $params['data']     = $data;
        $params['level']    = 'L';
        $params['size']     = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

        $ciQrcode->generate($params);

        /* Return Data */
        return [
            'content' => $data,
            'file'    => $dir . $save_name,
        ];
    }
}