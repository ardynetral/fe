<?php
namespace Modules\PraOut\Controllers;

use App\Libraries\Ciqrcode;

use function bin2hex;
use function file_exists;
use function mkdir;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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

		$data['prcode'] = $prcode;
		$data['cucode'] = $prcode;
		$data['group_id'] = $group_id;
		return view('Modules\PraOut\Views\index',$data);
	}

	public function list_data()
	{
		$token = get_token_item();
		$user_id = $token['id'];
		if($token['groupId']=='1') {
			$group_id = $token['groupId'];
		} else {
			$group_id = "";
		}
		$prcode = $token['prcode'];

		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        	
		$form_params = [

		];		
		$response = $this->client->request('GET','orderPras/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => $offset,
				'limit'	=> $limit,
				'search'	=> $search,
				'pracode' => 'PO',
				'userId'	=> $user_id,
				'groupId' => $group_id
			]
		]);
		$result = json_decode($response->getBody()->getContents(),true);
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => @$result['data']['count'],
            "recordsFiltered" => @$result['data']['count'],
            "data" => array()
        );
		$no = ($offset !=0)?$offset+1 :1;

		$data_pra = $result['data']['datas'];

		foreach ($data_pra as $k=>$v) {
			$btn_list="";
            $record = array(); 
			if($v['appv']==0):

				$btn_list .='<a href="'.site_url('praout/edit/'.$v['praid']).'" id="editPraIn" class="btn btn-xs btn-warning">edit</a>&nbsp;';

				if($group_id!=1):
				$btn_list .='<a href="'.site_url('praout/approve_order/'.$v['praid']).'" id="" class="btn btn-xs btn-primary" data-praid="'.$v['praid'].'">Approval</a>&nbsp;';
				endif;
					
				$btn_list .='<a href="#" id="" class="btn btn-xs btn-danger delete" data-kode="'.$v['praid'].'">delete</a>';
					
			elseif($v['appv']==1):

				$btn_list .='<a href="'.site_url('praout/proforma/'.$v['praid']).'" id="" class="btn btn-xs btn-primary" data-praid="'.$v['praid'].'">Proforma</a>&nbsp;';
				
				if((check_bukti_bayar2($v['praid'])=="exist")&&($group_id!=1)):
				$btn_list .='<a href="'.site_url('praout/approval2/'.$v['praid']).'" id="" class="btn btn-xs btn-success approve" data-praid="'.$v['praid'].'">Approval 2</a>&nbsp;';
				endif;

			elseif($v['appv']==2):
				$btn_list .='<a href="'.site_url('praout/view/'.$v['praid']).'" id="" class="btn btn-xs btn-default" data-praid="'.$v['praid'].'">view</a>&nbsp;';
				$btn_list .='<a href="'.site_url('praout/final_order/'.$v['praid']).'" class="btn btn-xs btn-info">Cetak kitir</a>';

			endif;
			
            $record[] = '<div>'.$btn_list.'</div>';
            $record[] = $no;
            $record[] = $v['cpiorderno'];
            $record[] = $v['cpipratgl'];
            $record[] = $v['cpives'];
            $record[] = $v['cpivoyid'];
			$record[] = $v['cpirefin'];

			if((check_bukti_bayar2($v['praid'])=="exist")) {
				$record[] = 'KW' . date("Ymd", strtotime($v['cpipratgl'])) . str_repeat("0", 8 - strlen($v['praid'])) . $v['praid'];
			} else {
				$record[] = '-';
			}

            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);		
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
	
			if((floatval($data['ccheight'])>8.6) && (floatval($data['cclength'])<=20)) {
				$hc20=$hc20+1;
			} else if((floatval($data['ccheight'])>8.6) && (floatval($data['ccheight'])<9.5) && (floatval($data['cclength'])==40)) {
				$hc40=$hc40+1;
			} else if((floatval($data['ccheight']))>=9.5 && (floatval($data['cclength'])==40)) {
				$hc45=$hc45+1;
			}

			if((floatval($data['ccheight'])<=8.6) && (floatval($data['cclength'])<=20)) {
				$std20=$std20+1;
			} else if((floatval($data['ccheight'])<=8.6) && (floatval($data['cclength'])==40)) {
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
		$limit=500;

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
	            'cpidish' 	=> 'required',
	            'cpidisdat' => 'required',
	            'cpirefin' 	=> 'required'
	        ],
            [
	            'cpidish' 	=> ['required' => 'ORIGIN PORT field required'],
	            'cpidisdat' => ['required' => 'DISCHARGE DATE field required'],
	            'cpirefin' 	=> ['required' => 'DO NUMBER field required']
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

			// VALIDASI VESSEL & VOYAGE di APPROVAL-1
			if(isset($_POST['act']) && $_POST['act']=='apv1') {
				$validate = $this->validate([
		            'cpives'		=> 'required',
		            'cpivoyid'	=> 'required'
		        	],
		            [
		            'cpives'		=> ['required' => 'VESSEL field required'],
		            'cpivoyid'	=> ['required' => 'VOYAGE field required']
			        ]
			    );	
			} else {
				$validate = $this->validate(['praid' 	=> 'required']);
			}
		
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
				'limit' => 500,
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
		   //  	$container = $this->check_container($_POST['crno']);
		   //  	echo $container;die()
		   //  	if($container==0) {
		   //  		$data['status'] = "Failled";
		   //  		$data['message'] = "Container Isn't exsist";
					// echo json_encode($data);die();
		   //  	}
				
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
		    		$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Containers Saved.');
				$data['status'] = "success";
				$data['message'] = "Data Saved";
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
		$token = get_token_item();
		$prcode = $token['prcode'];

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
			$data['prcode'] = $prcode;
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

				// UPDATE TABEL CONTAINER 
				$resp_ct = $this->client->request('POST','containers/update',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'json' => [
						'crNo' => $this->request->getPost('crno'),
						'dset' =>  [
							"cccode" => $_POST['ccode'],
							"mtcode" => $_POST['ctcode']
						]
					]
				]);
		
				$result_ct = json_decode($resp_ct->getBody()->getContents(), true);	
				
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
				$data['message_body'] = "Approval gagal. Data Contract tidak ditemukan";
				echo json_encode($data);die();	
			}


			// admin_tarif: coadmv
			// cleaning by: coadmm (1=by_order, 0=by_container)
			// hitung billing

			if($dt_order['data']['datas'][0]['cpipratgl'] < "2022-04-01") {
				$tax = 10;
			} else {
				$tax = (isset($contract['cotax'])?$contract['cotax']:0);
			}		

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
				'limit' => 500,
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
			if($contract == ""){
				$data['message'] = "Data Contract ".$_POST['cpopr']." tidak ditemukan";
				echo json_encode($data);die();	
			}			
			$arr_pracrnoid = explode(',', $_POST['pracrnoid']);

			foreach($arr_pracrnoid as $pracrnoid) {
				// Get order_pra_container
				$response_cr = $this->client->request('GET','orderPraContainers/getDetailData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'query' => [
						'pracrnoid' => $pracrnoid,
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

				//UPDATE ORDER PRA CONTAINER 
				$response = $this->client->request('PUT','orderPraContainers/updateData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'pracrnoid' => $pracrnoid,
						'cpopr' => $_POST['cpopr'],
						'cpcust' => $_POST['cpcust'],
						'biaya_clean' => 0,
						'biaya_lolo' => $biaya_lolo,
						'cleaning_type' => $_POST['cleaning_type'],
						'cpiremark' => $_POST['cpiremark'],
						'sealno' => $_POST['sealno']						
					],
				]);	
			}

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

					$cr_result = $this->get_container($c['crno']);
					
					$INVOICE_NUMBER  = 'KW' . date("Ymd", strtotime($data['data']['cpipratgl'])) . str_repeat("0", 8 - strlen($data['data']['praid'])) . $data['data']['praid'];

					$cprocess_params[$no] = [
						"crno" => $c['crno'],
					    "cpopr1" => $c['cpopr'],				    
					    "cpcust1" => $c['cpcust'],					    
					    "cpofe" => $c['cpife'],
					    "cpocargo" => '',
					    "cpopratgl" => $data['data']['cpipratgl'],
					    "cporefout" => $data['data']['cpirefin'],
					    "cpoves" => $data['data']['cpives'],
					    "cporeceiv" => $data['data']['cpideliver'],
					    "cpoorderno" => $data['data']['cpiorderno'],
					    "cporemark" => $c['cpiremark'],
					    "cpovoyid" => $data['data']['cpivoyid'],
					    "cpovoy" => $data['data']['cpivoyid'],
						"cpid" => $cr_result['crcpid'],
						"cposeal" => $c['sealno'],
						"cpoterm" => "MTY",
						"cporeceptno" => $INVOICE_NUMBER
					];	

					// Container Process
					$cp_response = $this->client->request('PUT','gateout/repoOutUpdateCP',[
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
				'limit' => 500,
			]
		]);

		$resPraContainer= json_decode($reqPraContainer->getBody()->getContents(), true);		
		$orderPraContainers = $resPraContainer['data']['datas'];

		$contract = $this->get_contract($data['data']['orderPraContainers'][0]['cpopr']);

		$pratgl = $data['data']['cpipratgl'];
		if($pratgl < "2022-04-01") {
			$tax = 10;
		} else {
			$tax = (isset($contract['cotax'])?$contract['cotax']:0);
		}

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
		if(check_bukti_bayar2($dt_order['data']['datas'][0]['praid'])=="exist") {
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

		$pratgl = $dt_order['data']['datas'][0]['cpipratgl'];
		if($pratgl < "2022-04-01") {
			$tax = 10;
		} else {
			$tax = (isset($contract['cotax'])?$contract['cotax']:0);
		}

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
		$data['recept'] = (isset($dt_order['data']['datas'][0]['orderPraRecept'][1])?$dt_order['data']['datas'][0]['orderPraRecept'][1]:$dt_order['data']['datas'][0]['orderPraRecept'][0]);
		// dd($data['recept']);
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

		$pratgl = $dt_order['cpipratgl'];
		if($pratgl < "2022-04-01") {
			$tax = 10;
		} else {
			$tax = (isset($contract['cotax'])?$contract['cotax']:0);
		}
		
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
			
			if((check_bukti_bayar2($_POST['praid'])=="insert")) {
				$response = $this->client->request('POST','orderPraRecepts/createNewData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'multipart' => $post_arr,
				]);
			} else if((check_bukti_bayar2($_POST['praid'])=="update")){
				$post_arr[] = [
					'name'		=> 'prareceptid',
					'contents'	=> $_POST['prareceptid']
				];				
				$response = $this->client->request('POST','orderPraRecepts/updateData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'multipart' => $post_arr,
				]);				
			}

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
				'limit' => 500,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);			
		$i=1; 
		$html="";
		foreach($result['data']['datas'] as $row){
			$pracrid=$row['pracrnoid'];
			$html .= "<tr>
				<td><a href='#' id='editContainer' class='btn btn-xs btn-danger delete' data-crid='".$row['pracrnoid']."' data-act='add'>delete</a></td>
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
				";
			$html .= "
				</tr>";
			$i++; 
		}

		echo json_encode($html);
		die();
	}

public function edit_get_container($praid) 
	{
		$response = $this->client->request('GET','orderPraContainers/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'praid' => $praid,
				'offset' => 0,
				'limit' => 500,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);			
		$i=1; 
		$html="";
		foreach($result['data']['datas'] as $row){
			$pracrid=$row['pracrnoid'];
			$html .= "<tr>
				<td>
				<a href='#' id='editContainer' class='btn btn-xs btn-primary edit' data-crid='".$row['pracrnoid']."' data-toggle='modal' data-target='#myModal'>edit</a>
				<a href='#' id='editContainer' class='btn btn-xs btn-danger delete' data-crid='".$row['pracrnoid']."' data-act='edit'>delete</a>
				</td>";

			$html .= "<td>".$i."</td>
				<td>".$row['crno']."</td>
				<td>".$row['cccode']."</td>
				<td>".$row['ctcode']."</td>
				<td>".$row['cclength']."</td>
				<td>".$row['ccheight']."</td>
				<td>".((isset($row['cpopr'])&&$row['cpopr']!="")?$row['cpopr']:'-')."</td>
				<td>".((isset($row['cpife'])&&$row['cpife']==1)?'Full':'Empty')."</td>
				<td>".$row['cpiremark']."</td>
				<td>".$row['sealno']."</td>";
			$html .= "</tr>";
			$i++; 
		}

		echo json_encode($html);
		die();
	}
	public function get_debitur($cucode) {
		$response = $this->client->request('GET','debiturs/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'cucode' => $cucode,
			]
		]);	
		$result = json_decode($response->getBody()->getContents(), true);	
		return $result['data'];
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
				'limit' => 500,
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
				<td class='text-center'>
					<label class='control-inline fancy-checkbox custom-color-green'>
						<input type='checkbox' name='container[]' value='".$row['pracrnoid']."'>
						<span>".$i."</span>
					</label>
				</td>
				<td>".$row['crno']."</td>
				<td>".$row['cccode']."</td>
				<td>".$row['ctcode']."</td>
				<td>".$row['cclength']."</td>
				<td>".$row['ccheight']."</td>
				<td>".((isset($row['cpopr'])&&$row['cpopr']!="")?$row['cpopr']:'-')."</td>
				<td>".$row['biaya_lolo']."</td>
				<td>".$row['cpiremark']."</td>
				<td>".$row['sealno']."</td>
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

	public function cetak_kitir($crno = "", $cpiorderno = "", $praid = "")
	{

		$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 236]]);
		// $mpdf->showImageErrors = true;
		$query_params = [
			"crno" => $crno,
			"cpoorderno" => $cpiorderno
		];

		$response = $this->client->request('GET', 'containerProcess/getKitirPraOut', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $query_params,
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		// print_r($result);die();
		//die();
		$recept = recept_by_praid($praid);

		if (isset($result['data'][0]) && (count($result['data'][0])) > 0) {
			$INVOICE_NUMBER  = 'KW' . date("Ymd", strtotime($result['data'][0]['cpopratgl'])) . str_repeat("0", 8 - strlen($recept['praid'])) . $recept['praid'];
			$qrcode = $this->generate_qrcode($result['data'][0]['cpid']);
			$CRNO = $result['data'][0]['crno'];
			$REFIN = $result['data'][0]['cporefout'];
			$CPID = $result['data'][0]['cpid'];
			$LENGTH = $result['data'][0]['cclength'];
			$HEIGHT = $result['data'][0]['ccheight'];
			$CPIORDERNO = $result['data'][0]['cpoorderno'];
			$TYPE = $result['data'][0]['cccode'];
			$CODE = $result['data'][0]['ctcode'];
			$PRINCIPAL = $result['data'][0]['cpopr1'];
			$SHIPPER = $result['data'][0]['cporeceiv'];
			$VESSEL = $result['data'][0]['cpoves'];
			$VOY = $result['data'][0]['cpovoy'];
			$DATE = $result['data'][0]['cpoloaddat'];
			$DESTINATION = "";
			$REMARK = $result['data'][0]['cporemark'];
			$NOPOL = $result['data'][0]['cponopol'];
			$QRCODE_IMG = ROOTPATH . '/public/media/qrcode/' . $qrcode['content'] . '.png';
			$CPIPRATGL = $result['data'][0]['cpopratgl'];
			$CPIRECEPTNO = $result['data'][0]['cporeceptno'];
			// $QRCODE_CONTENT = $qrcode['content'];
			$CRTARAK = $result['data'][0]['crtarak'];
			$CRTARAL = $result['data'][0]['crtaral'];
			$CRMANUF = $result['data'][0]['crmanuf'];
			$CRMANDAT = $result['data'][0]['crmandat'];
			$CRLASTCOND = $result['data'][0]['crlastcond'];
			$CPIDRIVER = $result['data'][0]['cpodriver'];
			$SEALNO = @$result['data'][0]['cposeal'];
		} else {
			$INVOICE_NUMBER = "";
			$CRNO = "";
			$CODE = "";
			$CPID = "";
			$REFIN = "";
			$LENGTH = "";
			$HEIGHT = "";
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
			$QRCODE_IMG = "";
			$QRCODE_CONTENT = "";
			$CPIRECEPTNO = "";
			$CPIPRATGL = "";
			$CRTARAK  = "";
			$CRTARAL  = "";
			$CRMANUF  = "";
			$CRMANDAT = "";
			$CRLASTCOND = "";
			$CPIDRIVER = "";
			$SEALNO = "";
		}



		$result = json_decode($response->getBody()->getContents(), true);

		$barcode = $generator->getBarcode($crno, $generator::TYPE_CODE_128);

		$html = '';

		$html .= '
		<html>
			<head>
				<title>Order PraOut | Print Kitir</title>
				<link href="' . base_url() . '/public/themes/smartdepo/css/bootstrap.min.css" rel="stylesheet" type="text/css">
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
				<h5 style="line-height:0.5;font-weight:bold;padding-top:20px;">PT.CONTINDO RAYA</h5>
				<h5 style="line-height:0.5;font-weight:bold;padding-top:20px;">KITIR MUAT</h5>
				<h5 style="text-decoration: underline;line-height:0.5;">' . $REFIN . '</h5>
				<img src="' . $QRCODE_IMG . '" style="height:120px;">
				<h4 style="text-decoration: underline;line-height:0.5;">' . $CPID . '</h4>
			</div>
		';

		$html .= '
				<table border-spacing: 0; border-collapse: collapse; width="100%">	
					<tr>
						<td>PRINCIPAL</td>
						<td colspan="3">:&nbsp;' . $PRINCIPAL . '</td>
					</tr>
					<tr>
						<td style="width:40%;">CONTAINER NO.</td>
						<td colspan="3"><h5 style="line-height:1.2;font-weight:bold;padding-top:20px;">:&nbsp;' . $CRNO . '</h5></td>
					</tr>
					<tr>
						<td style="width:40%;">DATE PRAOUT</td>
						<td colspan="3">:&nbsp;' . date('d-m-Y', strtotime($CPIPRATGL)) . '</td>
					</tr>
					<tr>
						<td>TIPE</td>
						<td colspan="3">:&nbsp;' . $CODE . '/' . $TYPE . '</td>
					</tr>					
					<tr>
						<td>SIZE</td>
						<td colspan="3">:&nbsp;' . $LENGTH  . '/' . $HEIGHT . ' </td>
					</tr>
					<tr>
						<td>TARA</td>
						<td colspan="3">:&nbsp;' . $CRTARAK . '/' . $CRTARAL . ' </td>
					</tr>

					<tr>
						<td>MAN.DATE </td>
						<td colspan="3">:&nbsp;' . $CRMANDAT . ' </td>
					</tr>
					<tr>
						<td>CONDITION</td>
						<td colspan="3">:&nbsp;' . $CRLASTCOND . '</td>
					</tr>
					<tr>
						<td>EMKL</td>
						<td colspan="3" style="font-weight:normal">:&nbsp;' . $SHIPPER . '</td>
					</tr>
					<tr>
						<td>INVOICE NUM.</td>
						<td colspan="3" style="font-weight:normal">:&nbsp;' . $INVOICE_NUMBER . '</td>
					</tr>

					
					<tr>
						<td>LOAD STATUS</td>
						<td colspan="3">:&nbsp;</td>
					</tr>					
					<tr>
						<td>LOADING VESSEL</td>
						<td colspan="3"  style="font-weight:normal">:&nbsp;' . $VESSEL . '/' . $VOY . '</td>
					</tr>
					<tr>
						<td>NO POLISI</td>
						<td colspan="3">:&nbsp;' . $NOPOL . '</td>
					</tr>
					<tr>
						<td>DRIVER</td>
						<td colspan="3">:&nbsp;' . $CPIDRIVER . '</td>
					</tr>
					<tr>
						<td>SEAL NO.</td>
						<td colspan="3">:&nbsp;' . $SEALNO . '</td>
					</tr>								
					<tr>
						<td>REMARK</td>
						<td colspan="3"  style="font-weight:normal">:&nbsp;' . $REMARK . '</td>
					</tr>			


					<tr rowspan="3">&nbsp;</tr>

				</table>
				<br>
				<table border-spacing: 0; border-collapse: collapse; width="100%">	
					<tr>
						<td width="33%">TRUCKER</td>
						<td width="33%" class="t-center">SURVEYOR</td>
						<td width="33%">PETUGAS</td>
					</tr>
					
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>	
					<tr>
						<td>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
						<td class="t-center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
						<td>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
					</tr>	
				</table>
				</div>
		';


		$html .= '
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		// echo $html;
		die();
	}	

	public function print_proforma($id) 
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
		// dd($header);

		$pratgl = $header['cpipratgl'];
		$recept = $header['orderPraRecept'][0];
		$debitur = $this->get_debitur($header['cpideliver']);
		$detail = $header['orderPraContainers'];
		$depo = $this->get_depo($header['cpdepo']);
		if($recept==""){
			$invoice_number ="-";	
		} else {
			// $invoice_number = "KW." . date("Ymd",strtotime($pratgl)) . ".000000" . $recept['prareceptid'];
			$invoice_number  = 'KW' . date("Ymd", strtotime($pratgl)) . str_repeat("0", 8 - strlen($recept['praid'])) . $recept['praid'];
		}
				
		$contract = $this->get_contract($detail[0]['cpopr']);
		if($pratgl < "2022-04-01") {
			$ppn = 10;
		} else {
			$ppn = (isset($contract['cotax'])?$contract['cotax']:0);
		}
		
		$qty=0;
		(int)$qty20 = 0;
		(int)$qty40 = 0;
		(int)$qty45 = 0;		
		(int)$price20 = 0;
		(int)$price40 = 0;
		(int)$price45 = 0;		
		(int)$subtot20 = 0;
		(int)$subtot40 = 0;
		(int)$subtot45 = 0;		
		(int)$price_cleaning = 0;
		(int)$subtot_cleaning = 0;
		(int)$subtotal = 0;
		(int)$total = 0;
		
		foreach($detail as $det) {			
			$qty = $qty+1;
			$price_cleaning = (int)$det['biaya_lain'];
			$subtot_cleaning = $subtot_cleaning+(int)$det['biaya_lain'];

			if($det['cclength']<=20) {
				$qty20 = $qty20 + 1;  
				$price20 = $det['biaya_lolo'];
				$subtot20 = $subtot20 + (int)$det['biaya_lolo'];
			} 
			if ($det['cclength']==40) {
				$qty40 = $qty40 + 1;
				$price40 = $det['biaya_lolo'];
				$subtot40 = $subtot40 + (int)$det['biaya_lolo'];
			}
			if($det['cclength']>40) {
				$qty45 = $qty45 + 1;
				$price45 = $det['biaya_lolo'];
				$subtot45 = $subtot45 + (int)$det['biaya_lolo'];
			}
		}

		// $total=$subtot_cleaning+$subtot20+$subtot40+$subtot45+$recept['biaya_adm']+$recept['total_pajak'];
		$total = $recept['total_tagihan'];
		$terbilang = strtoupper(toEnglish($total)) . ' IDR';
		$materai = ($recept['total_tagihan'] > 5000000)?+$recept['materai']:"0";
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
					.page-header{display:block;padding:0;min-height:30px;}
					h2{font-weight:normal;line-height:.5;}
					.head-left{float:left;width:200px;padding:0px;}
					.head-right{float:left;padding:0px;margin-left:200px;text-align: right;}

					.tbl_head, .tbl_det_prain, .tbl-borderless{border-spacing: 0;border-collapse: collapse;}
					.tbl_head_prain td{border-collapse: collapse;}
					.t-right{text-align:right;}
					.t-left{text-align:left;}
					.t-center{text-align:center;}

					.tbl_head td, .tbl_det_prain th,.tbl_det_prain td {
						border:1px solid #000000!important;
						border-spacing: 0;
						border-collapse: collapse;
						padding:5px;

					}					
					.tbl-borderless td {
						border:none!important;
						border-spacing: 0;
						border-collapse: collapse;
					}
					.line-space{border-bottom:1px solid #000000;margin:30px 0;}
				</style>
			</head>
		';

		$html .= '<body>
			<div class="page-header">
				<table width="100%">
				<tr>
				<td>
					<h4>PT. CONTINDO RAYA</h4>
				</td>
				<td class="t-center" width="40%"><h2>PROFORMA</h2></td>
				<td class="t-right"><p>&nbsp;</p></td>
				</tr>
				</table>
			</div>
		';
		$html .='
			<table class="tbl_head" width="100%">
				<tbody>
					<tr>
						<td width="60%" style="vertical-align:baseline!important;">
							<h2>'.strtoupper($debitur['cuname']).'</h2>
							<table class="tbl-borderless">
								<tr><td>NPWP</td><td>:&nbsp; '.$debitur['cunpwp'].'</td></tr>
							</table>
						</td>
						<td width="40%" style="vertical-align:baseline!important;">
							<p>
							ADDRESS :<br>
							'.$debitur['cuaddr'].'
							</p>
						</td>
					</tr>		
				</tbody>
			</table>
		';

		$html .='
		<br>
		<table class="tbl-borderless">
			<tr>
				<td style="border-botom:1px solid #000;">BANYAK UANG</td>
				<td rowspan="3" class="t-center" style="vertical-align:baseline!important;"><br><h2>'.$terbilang.'</h2>
			</td></tr>
			<tr><td>----------------------</td></tr>
			<tr><td><i>THE SUM OF</i></td></tr>
		</table>
		<br>
		';

		$html .='
			<table class="tbl_det_prain" width="100%">
				<thead>
					<tr>
						<th>NO</th>
						<th>URAIAN / PEMBAYARAN</th>
						<th>LATTER NO</th>
						<th>QTY</th>
						<th>SIZE</th>
						<th COLSPAN="2">PRICE</th>
						<th>JUMLAH</th>
					</tr>
				</thead>
				<tbody>
				';
				if($qty20>0) {
					$html .='<tr>
						<td>001</td>
						<td>LIFT ON - 20 FT</td>
						<td></td>
						<td class="t-center">'.$qty20.'</td>
						<td class="t-center">20</td>
						<td class="t-center">IDR</td>
						<td class="t-right">'.number_format($price20,2).'</td>
						<td class="t-right">'.number_format($subtot20,2).'</td>
					</tr>';
				}
				if($qty40>0) {
					$html .='<tr>
						<td>002</td>
						<td>LIFT ON - 40 FT</td>
						<td></td>
						<td class="t-center">'.$qty40.'</td>
						<td class="t-center">40</td>
						<td class="t-center">IDR</td>
						<td class="t-right">'.number_format($price40,2).'</td>
						<td class="t-right">'.number_format($subtot40,2).'</td>
					</tr>';
				}
				if($qty45>0) {
					$html .='<tr>
						<td>003</td>
						<td>LIFT ON - 45 FT</td>
						<td></td>
						<td class="t-center">'.$qty45.'</td>
						<td class="t-center">45</td>
						<td class="t-center">IDR</td>
						<td class="t-right">'.number_format($price45,2).'</td>
						<td class="t-right">'.number_format($subtot45,2).'</td>
					</tr>';
				}

				$html .='
				<tr>
					<td>004</td>
					<td>CLEANING</td>
					<td></td>
					<td class="t-center">'.$qty.'</td>
					<td class="t-center"></td>
					<td class="t-center">IDR</td>
					<td class="t-right">'.number_format($price_cleaning,2).'</td>
					<td class="t-right">'.number_format($subtot_cleaning,2).'</td>
				</tr>	
				<tr>
					<td>997</td>
					<td>PPN ' . (int)$ppn . '% </td>
					<td></td>
					<td class="t-center">1</td>
					<td class="t-center"></td>
					<td class="t-center">IDR</td>
					<td class="t-right">'.number_format($recept['total_pajak'],2).'</td>
					<td class="t-right">'.number_format($recept['total_pajak'],2).'</td>
				</tr>
				<tr>
					<td>998</td>
					<td>ADMINISTRATION</td>
					<td></td>
					<td class="t-center">1</td>
					<td class="t-center"></td>
					<td class="t-center">IDR</td>
					<td class="t-right">'.number_format($recept['biaya_adm'],2).'</td>
					<td class="t-right">'.number_format($recept['biaya_adm'],2).'</td>
				</tr>
				<tr>
					<td>999</td>
					<td>MATERAI</td>
					<td></td>
					<td class="t-center">1</td>
					<td class="t-center"></td>
					<td class="t-center">IDR</td>
					<td class="t-right">'.number_format($materai,2).'</td>
					<td class="t-right">'.number_format($materai,2).'</td>
				</tr>
				<tr><th colspan="7" class="t-right">Total</th>
					<th class="t-right">'.number_format($total,2).'</th></tr>

				</tbody>
			</table>';

		$html .='
		<br>
			<table class="tbl-borderless" width="100%">
				<tbody>			
	
					<tr>
						<td width="60%">REMARK : </td>
						<td class="t-center">PADANG, '.date('d-M-Y').'</td>
					</tr>
					<tr>
						<td><b>'.$header['cpirefin'].'</b></td>
						<td></td>
					</tr>
					<tr>
						<td width="60%"><div style="width:600px;word-break: break-all;">
						</div></td>
						<td class="t-center" style="vertical-align:baseline!important;"  width="25%"></td>
					</tr>
				</tbody>
			</table>	
			<div style="width:300px;">';
			foreach($detail as $dt):
				$html .= '<span>'.$dt['crno'] . '</span>,&nbsp;';
			endforeach;			
			$html .= '</p>		
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
		$DEPO = $this->get_company();
		$QR_COMPANY = $this->generate_qrcode($DEPO['pagroup']);
		$QRCODE = ROOTPATH . '/public/media/qrcode/' . $QR_COMPANY['content'] . '.png';
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
		// dd($header);

		$pratgl = $header['cpipratgl'];
		$recept = recept_by_praid($header['praid']);
		$debitur = $this->get_debitur($header['cpideliver']);
		$detail = $header['orderPraContainers'];
		$depo = $this->get_depo($header['cpdepo']);
		if($recept==""){
			$invoice_number ="-";
			$RECEPT_DATE = "-";	
		} else {
			// $invoice_number = "KW." . date("Ymd",strtotime($pratgl)) . ".000000" . $recept['prareceptid'];
			$invoice_number  = 'KW' . date("Ymd", strtotime($pratgl)) . str_repeat("0", 8 - strlen($recept['praid'])) . $recept['praid'];
			$RECEPT_DATE = $recept['receptdate'];
		}

		$contract = $this->get_contract($detail[0]['cpopr']);
		if($pratgl < "2022-04-01") {
			$ppn = 10;
		} else {
			$ppn = (isset($contract['cotax'])?$contract['cotax']:0);

		}				

		$qty=0;
		(int)$qty20 = 0;
		(int)$qty40 = 0;
		(int)$qty45 = 0;		
		(int)$price20 = 0;
		(int)$price40 = 0;
		(int)$price45 = 0;		
		(int)$subtot20 = 0;
		(int)$subtot40 = 0;
		(int)$subtot45 = 0;		
		(int)$price_cleaning = 0;
		(int)$subtot_cleaning = 0;
		(int)$subtotal = 0;
		(int)$total = 0;
		
		foreach($detail as $det) {			
			$qty = $qty+1;
			$price_cleaning = (int)$det['biaya_lain'];
			$subtot_cleaning = $subtot_cleaning+(int)$det['biaya_lain'];

			if($det['cclength']<=20) {
				$qty20 = $qty20 + 1;  
				$price20 = $det['biaya_lolo'];
				$subtot20 = $subtot20 + (int)$det['biaya_lolo'];
			} 
			if ($det['cclength']==40) {
				$qty40 = $qty40 + 1;
				$price40 = $det['biaya_lolo'];
				$subtot40 = $subtot40 + (int)$det['biaya_lolo'];
			}
			if($det['cclength']>40) {
				$qty45 = $qty45 + 1;
				$price45 = $det['biaya_lolo'];
				$subtot45 = $subtot45 + (int)$det['biaya_lolo'];
			}
		}

		$total=$subtot_cleaning+$subtot20+$subtot40+$subtot45+$recept['biaya_adm']+$recept['total_pajak']+$recept['materai'];
		// $total = $recept['total_tagihan'];
		$terbilang = strtoupper(toEnglish($total)) . ' IDR';
		$materai = ($recept['total_tagihan'] > 5000000)?+$recept['materai']:"0";

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
					.page-header{display:block;padding:0;min-height:30px;}
					h2{font-weight:normal;line-height:.5;}
					.head-left{float:left;width:200px;padding:0px;}
					.head-right{float:left;padding:0px;margin-left:200px;text-align: right;}

					.tbl_head, .tbl_det_prain, .tbl-borderless{border-spacing: 0;border-collapse: collapse;}
					.tbl_head_prain td{border-collapse: collapse;}
					.t-right{text-align:right;}
					.t-left{text-align:left;}
					.t-center{text-align:center;}

					.tbl_head td, .tbl_det_prain th,.tbl_det_prain td {
						border:1px solid #000000!important;
						border-spacing: 0;
						border-collapse: collapse;
						padding:5px;

					}					
					.tbl-borderless td {
						border:none!important;
						border-spacing: 0;
						border-collapse: collapse;
					}
					.line-space{border-bottom:1px solid #000000;margin:30px 0;}
				</style>
			</head>
		';

		$html .= '<body>
			<div class="page-header">
				<table width="100%">
				<tr>
				<td>
					<h4>PT. CONTINDO RAYA</h4>
				</td>
				<td class="t-center" width="40%"><h2>KWITANSI / RECEIPT</h2></td>
				<td class="t-right"><p>'.$invoice_number.'</p></td>
				</tr>
				</table>
			</div>
		';
		$html .='
			<table class="tbl_head" width="100%">
				<tbody>
					<tr>
						<td width="60%" style="vertical-align:baseline!important;">
							SUDAH TERIMA DARI / RECEIVED FROM
							<h2>'.strtoupper($debitur['cuname']).'</h2>
							<table class="tbl-borderless">
								<tr><td>NPWP</td><td>:&nbsp; '.$debitur['cunpwp'].'</td></tr>
							</table>
						</td>
						<td width="40%" style="vertical-align:baseline!important;">
							<p>
							ADDRESS :<br>
							'.$debitur['cuaddr'].'
							</p>
						</td>
					</tr>		
				</tbody>
			</table>
		';

		$html .='
		<br>
		<table class="tbl-borderless">
			<tr>
				<td style="border-botom:1px solid #000;">BANYAK UANG</td>
				<td rowspan="3" class="t-center" style="vertical-align:baseline!important;"><br><h2>'.$terbilang.'</h2>
			</td></tr>
			<tr><td>----------------------</td></tr>
			<tr><td><i>THE SUM OF</i></td></tr>
		</table>
		<br>
		';

		$html .='
			<table class="tbl_det_prain" width="100%">
				<thead>
					<tr>
						<th>NO</th>
						<th>URAIAN / PEMBAYARAN</th>
						<th>LATTER NO</th>
						<th>QTY</th>
						<th>SIZE</th>
						<th COLSPAN="2">PRICE</th>
						<th>JUMLAH</th>
					</tr>
				</thead>
				<tbody>
				';
				if($qty20>0) {
					$html .='<tr>
						<td>001</td>
						<td>LIFT ON - 20 FT</td>
						<td></td>
						<td class="t-center">'.$qty20.'</td>
						<td class="t-center">20</td>
						<td class="t-center">IDR</td>
						<td class="t-right">'.number_format($price20,2).'</td>
						<td class="t-right">'.number_format($subtot20,2).'</td>
					</tr>';
				}
				if($qty40>0) {
					$html .='<tr>
						<td>002</td>
						<td>LIFT ON - 40 FT</td>
						<td></td>
						<td class="t-center">'.$qty40.'</td>
						<td class="t-center">40</td>
						<td class="t-center">IDR</td>
						<td class="t-right">'.number_format($price40,2).'</td>
						<td class="t-right">'.number_format($subtot40,2).'</td>
					</tr>';
				}
				if($qty45>0) {
					$html .='<tr>
						<td>003</td>
						<td>LIFT ON - 45 FT</td>
						<td></td>
						<td class="t-center">'.$qty45.'</td>
						<td class="t-center">45</td>
						<td class="t-center">IDR</td>
						<td class="t-right">'.number_format($price45,2).'</td>
						<td class="t-right">'.number_format($subtot45,2).'</td>
					</tr>';
				}

				$html .='
				<tr>
					<td>004</td>
					<td>CLEANING</td>
					<td></td>
					<td class="t-center">'.$qty.'</td>
					<td class="t-center"></td>
					<td class="t-center">IDR</td>
					<td class="t-right">'.number_format($price_cleaning,2).'</td>
					<td class="t-right">'.number_format($subtot_cleaning,2).'</td>
				</tr>	
				<tr>
					<td>997</td>
					<td>PPN ' . (int)$ppn . '% </td>
					<td></td>
					<td class="t-center">1</td>
					<td class="t-center"></td>
					<td class="t-center">IDR</td>
					<td class="t-right">'.number_format($recept['total_pajak'],2).'</td>
					<td class="t-right">'.number_format($recept['total_pajak'],2).'</td>
				</tr>							
				<tr>
					<td>998</td>
					<td>ADMINISTRATION</td>
					<td></td>
					<td class="t-center">1</td>
					<td class="t-center"></td>
					<td class="t-center">IDR</td>
					<td class="t-right">'.number_format($recept['biaya_adm'],2).'</td>
					<td class="t-right">'.number_format($recept['biaya_adm'],2).'</td>
				</tr>	
				<tr>
					<td>999</td>
					<td>MATERAI</td>
					<td></td>
					<td class="t-center">1</td>
					<td class="t-center"></td>
					<td class="t-center">IDR</td>
					<td class="t-right">'.number_format($materai,2).'</td>
					<td class="t-right">'.number_format($materai,2).'</td>
				</tr>					
				<tr><th colspan="7" class="t-right">Total</th>
					<th class="t-right">'.number_format($total,2).'</th></tr>

				</tbody>
			</table>';

		$html .='
		<br>
			<table class="tbl-borderless" width="100%">
				<tbody>			
	
					<tr>
						<td width="80%">REMARK : </td>
						<td class="t-center">PADANG, '.$RECEPT_DATE.'</td>
					</tr>
					<tr>
						<td><b>'.$header['cpirefin'].'</b></td>
						<td></td>
					</tr>
					<tr>
						<td width="80%"><div style="width:600px;word-break: break-all;">
						</div></td>
						<td class="t-center" style="vertical-align:baseline!important;"  width="25%">( KASIR )</td>
					</tr>
				</tbody>
			</table>	
			<div style="width:75%;float:left;">';
			foreach($detail as $dt):
				$html .= '<span>'.$dt['crno'] . '</span>,&nbsp;';
			endforeach;			
			$html .= '</div>	
			<div class="t-center" style="vertical-align:baseline!important;float:right;right:0;">
				<img src="' . $QRCODE . '" style="height:120px;margin-top:40px;">
			</div>		
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		// echo $html;
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
			$RECEPT_DATE = "-";	
		} else {
			$invoice_number = "INV." . date("Ymd",strtotime($pratgl)) . ".000000" . $recept['prareceptid'];
			$RECEPT_DATE = $recept['receptdate'];
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
				<td class="t-right"><p>PADANG, '.$RECEPT_DATE.'</p></td>
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
						<td class="t-right">Lift On Charges In Depot</td>
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
						<th>Gate Out Date</th>
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

			$crno = $_POST['crno'];
			$praid = $_POST['praid'];

			if(strlen($crno)<11){
				$data['status'] = "Failled";
				$data['message'] = "Invalid Container";
				echo json_encode($data);die();					
			}

			// $checkFormatNumber = $this->checkContainerFormatNumber($crno);
			$container = $this->get_container($crno);
			// echo var_dump($container);die();
			if($container=="" || $container['lastact'] == "HC") {
				$data['status'] = "Failled";
				$data['message'] = "Invalid Container";
				echo json_encode($data);die();
			}

			if((($container['crlastact'] == "CO") && ($container['crlastcond'] == "AC")) || ($container['lastact'] == "AC")) {

		    	// check table order_pra_Container
				$reqPraContainer = $this->client->request('GET','orderPraContainers/getAllData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'query' => [
						'praid' => $praid,
						'offset' => 0,
						'limit' => 500,
					]
				]);

				$resPraContainer= json_decode($reqPraContainer->getBody()->getContents(), true);		
				$orderPraContainers = $resPraContainer['data']['datas'];
				if(isset($orderPraContainers) && ($orderPraContainers!=null)) {
					foreach($orderPraContainers as $opc) {
						$crnos[] = $opc['crno'];
					}
					if(in_array($crno,$crnos)) {
						$data['status'] = "Failled";
						$data['message'] = "Container ini sudah diinput";
						echo json_encode($data);die();					
					}
				}	

				$data['status'] = "success";
				$data['message'] = "Valid Container";
				$data['data'] = $container;
				$data['container_code'] = $data['data']['container_code'];
				echo json_encode($data);die();

			} else {		

				$data['status'] = "Failled";
				$data['message'] = "Invalid Container";
				echo json_encode($data);die();					
			}
		}
	}	

	public function checkContainerFormatNumber($crno) 
	{
		/*
		return:
		- 
		*/
		$data = [];
		
		if($this->request->isAjax()) {

			$crno = $_POST['crno'];
			$response = $this->client->request('GET','containers/checkcCode',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query'=>[
					'cContainer' => $crno, 
				]
			]);

			$result = json_decode($response->getBody()->getContents(),true);
			return $result;
			// if(isset($result['success']) && ($result['success']==false))
			// {
			// 	$status = "invalid";				
			// } else {
			// 	$status = "valid";
			// }
		 //    return $status;
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
		$data  = (isset($result['data']) && $result['data'] != null)?$result['data']:"";
		return $data;
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

    // INSERT CONTAINER FROM FILE 
    public function import_xls_pra()
    {
    	if($this->request->isAjax()){
	    	$sheetname = "CONTAINERS";
	    	$praid = $_POST['praid'];
	    	$uploadFile = $_FILES['file_xls']['tmp_name'];
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$reader->setLoadSheetsOnly($sheetname);
			$spreadsheet = $reader->load($uploadFile);
			$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

			$data_arr = [];
			$status = "";
			$message = "";
			$i=1;
			$html = '';

			// check table order_pra_Container
			$reqPraContainer = $this->client->request('GET','orderPraContainers/getAllData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'praid' => $praid,
					'offset' => 0,
					'limit' => 500,
				]
			]);

			$resPraContainer= json_decode($reqPraContainer->getBody()->getContents(), true);
			$orderPraContainers = $resPraContainer['data']['datas'];			

			if($sheetData[1]["A"]=="") {
				$data['status'] = "Failled";
				$data['message'] = "Pastikan format data sudah benar.";
				echo json_encode($data);die();				
			}

			foreach($sheetData as $k=>$v) {
				if($k>1 && $v["A"]!="") {

					if($v["E"]=="20") {$code = "22G1";}
					else if($v["E"]=="40") {$code = "40G1";}
					else if($v["E"]=="45") {$code = "45G1";}

					// check table Container
					$response = $this->client->request('GET','containers/checkcCode',[
						'headers' => [
							'Accept' => 'application/json',
							'Authorization' => session()->get('login_token')
						],
						'query'=>[
							'cContainer' => $v["A"], 
						]
					]);

					$result = json_decode($response->getBody()->getContents(),true);

					if(isset($result['success']) && ($result['success']==false))
					{
						$status = "invalid";	
						$message = "Format CRNO salah";	
					} else {

						// check table order_pra_Container
						if(isset($orderPraContainers) && ($orderPraContainers!=null)) {
							foreach($orderPraContainers as $opc) {
								$crnos[] = $opc['crno'];
							}

							if(in_array($v["A"],$crnos)==true) {
								$status= "invalid";
								$message = "Container in order";
							}else {						
								$container = $this->get_container($v["A"]);	
								if(($container['crlastact'] == "CO" && $container['crlastcond'] == "AC") || $container['lastact'] == "AC") {
									$status= "valid";
									$message = "";	
								} else{
									$status= "invalid";
									$message = "Status=" . $container['crlastact'] . ', Cond='.$container['crlastcond'] . ', LastACT' . $container['lastact'];
								}					
												
							} 
						} else {
							if($this->check_container($v["A"])==0) {
								$status= "new";
								$message = "Not Found";
							} else {							
								$container = $this->get_container($v["A"]);
								// echo var_dump($container);	
								if(($container['crlastact'] == "CO" && $container['crlastcond'] == "AC") || $container['lastact'] == "AC") {
									$status= "valid";
									$message = "";	
								} else{
									$status= "invalid";
									$message = "Invalid Container";
								}					
							}
						}

					}

					$html .='<tr>
					<td></td>
					<td>'.$i.'</td>
					<td><input type="text" class="form-control col-sm-2 crno" name="crno[]" value="'.$v['A'].'" readonly></td>
					<td><input type="text" class="form-control col-sm-2 ccode" name="ccode[]" value="'.$v['B'].'" readonly></td>
					<td><input type="text" class="form-control col-sm-2 ctcode" name="ctcode[]" value="'.$v['C'].'" readonly></td>
					<td><input type="text" class="form-control col-sm-2 ccheight" name="ccheight[]" value="'.$v['D'].'" readonly></td>
					<td><input type="text" class="form-control col-sm-2 cclength" name="cclength[]" value="'.$v['E'].'" readonly></td>
					<td></td>
					<td></td>
					<td>'.$message.'</td>
					<td style="display:none;"><input type="text" class="form-control col-sm-2 status" name="status[]" value="'.$status.'">
						<input type="text" class="form-control col-sm-2 message" name="message[]" value="'.$message.'">
					</td>
					</tr>'; 

					$i++;
				}
			}

			$data['status'] = "success";
			$data['data'] = $html;
			echo json_encode($data);die();
    	}
    }

    public function insertContainerFromFile($praid)
    {
    	/* 
    		STATUS MESSAGE
    		- invalid Container: (CRLASTACT, CRLASTCOND, LASTACT) tidak memenuhi syarat
    		- Not Found : Container belum ada di tabel container
		*/

    	if($this->request->isAjax()) {
    		foreach($_POST['crno'] as $key=>$val) {
    			$params[$key] = [
					"cpopr" => "-",
					"cpcust" => "-",
					"praid" => $praid,
    				"crno" => $_POST['crno'][$key],
    				"cccode" => $_POST['ccode'][$key],
    				"ctcode" => $_POST['ctcode'][$key],
    				"ccheight" => $_POST['ccheight'][$key],
    				"cclength" => $_POST['cclength'][$key],
    				"cpiremark" => $_POST['message'][$key],
    				"cpife" => "0",
    				"cpishold" => 1,
    				"sealno" => "-",
    			]; 

				$this->client->request('POST','orderPraContainers/createNewData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $params[$key],
				]);

    		}

			$data['status'] = "success";
			$data['message'] = "Data Saved";
			echo json_encode($data);
			die();
    	}

    }    
}