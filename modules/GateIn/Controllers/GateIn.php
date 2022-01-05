<?php
namespace Modules\GateIn\Controllers;

class GateIn extends \CodeIgniter\Controller
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
		// $prcode = $token['prcode'];

		$data = [];
		$offset=0;
		$limit=100;
		// order pra
		$form_params = [
			"cpife1"=>1,
			"cpife2"=>0,
			"retype1"=>21,
			"retype2"=>22,
			"retype3"=>23,
			"crlastact1"=>"od",
			"crlastact2"=>"bi",
			"limit"=>100,
			"offset"=>0
		];

		$response = $this->client->request('GET','containerProcess/getAllDataGateIN',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				$form_params
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	

		$data['data'] = isset($result['data']['datas'])?$result['data']['datas']:"";

		// $data['prcode'] = $prcode;
		// $data['cucode'] = $prcode;
		// echo dd($data);
		// return view('Modules\PraIn\Views\tab_base',$data);
		$data['page_title'] = "Gate In";
		$data['page_subtitle'] = "Gate In Page";
		return view('Modules\GateIn\Views\index',$data);
	}

	function list_data(){		
		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "")?$this->request->getPost('search'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        	
		// PULL data from API
		$form_params = [

		];		
		$response = $this->client->request('GET','containerProcess/getAllDataGateIN',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'cpife1'=>1,
				'cpife2'=>0,
				'retype1'=>21,
				'retype2'=>22,
				'retype3'=>23,
				'crlastact1'=>'od',
				'crlastact2'=>'bi',
				'limit'=>$limit,
				'offset'=>$offset
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		// print_r($result);
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => @$result['data']['Total'],
            "recordsFiltered" => @$result['data']['Total'],
            "data" => array()
        );
		$no = ($offset !=0)?$offset+1 :1;
		foreach ($result['data']['datas'] as $k=>$v) {
			$btn_list="";
            $record = array(); 
            $record[] = $no;
            $record[] = $v['crno'];
            $record[] = $v['cpitgl'];
            $record[] = $v['cpopr'];
            $record[] = $v['cpiorderno'];
			$record[] = $v['cpieir'];
			
			// $btn_list .='<a href="#" id="" class="btn btn-xs btn-primary btn-table" data-praid="">view</a>';						
			$btn_list .='<a href="#" id="editPraIn" class="btn btn-xs btn-success btn-table edit" data-crno="'.$v['crno'].'">edit</a>&nbsp;';
			$btn_list .='<a href="#" class="btn btn-xs btn-info btn-table" data-praid="">print</a>';	
			// $btn_list .='<a href="#" id="deleteRow_'.$no.'" class="btn btn-xs btn-danger btn-table">delete</a>';			
            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);
		
	}

	public function add()
	{
/*
"cpdepo": 1,
"spdepo": "1",
"cpitgl": "2021-11-11",
"cpiefin": "1",
"cpichrgbb": "1",
"cpipaidbb": "1",
"cpieir": "1",
"cpinopol": "1",
"cpidriver": "1",
"cpicargo": "1",
"cpiseal": "1",
"cpiremark": "1",
"cpiremark1": "1",
"cpidpp": "1",
"cpireceptno": "1",
"cpideliver": "1",
"cpitruck": "1",
"cpiorderno": "PI000D100000031"
*/		
		$data = [];
		$form_params = [];
		$data['act'] = "Add";
		$data['page_title'] = "Gate In";
		$data['page_subtitle'] = "Gate In Page";

		if($this->request->isAjax()) {
			$form_params = [
			    "cpdepo" => "000",
			    "spdepo" => "000",
			    "cpitgl" => $_POST['cpipratgl'],
			    "cpiefin" => "1",
			    "cpichrgbb" => "1",
			    "cpipaidbb" => "1",
			    "cpieir" => $_POST['cpieir'],
			    "cpinopol" => $_POST['cpinopol'],
			    "cpidriver" => $_POST['cpidriver'],
			    "cpicargo" => $_POST['cpicargo'],
			    "cpiseal" => "1",
			    "cpiremark" => "1",
			    "cpiremark1" => "1",
			    "cpidpp" => "1",
			    "cpireceptno" => $_POST['cpireceptno'],
			    "cpideliver" => $_POST['cpideliver'],
			    "cpitruck" => "1",
			    "cpiorderno" => $_POST['cpiorderno']			
			];			
			$response = $this->client->request('POST','containerProcess/gateIns',[
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

			// echo var_dump($form_params);
			$data['message'] = "success";
			echo json_encode($data);die();
		}	

		return view('Modules\GateIn\Views\add',$data);
	}

	public function edit($crno)
	{
/*
"cpdepo": 1,
"spdepo": "1",
"cpitgl": "2021-11-11",
"cpiefin": "1",
"cpichrgbb": "1",
"cpipaidbb": "1",
"cpieir": "1",
"cpinopol": "1",
"cpidriver": "1",
"cpicargo": "1",
"cpiseal": "1",
"cpiremark": "1",
"cpiremark1": "1",
"cpidpp": "1",
"cpireceptno": "1",
"cpideliver": "1",
"cpitruck": "1",
"cpiorderno": "PI000D100000031"
*/		
		$data = [];
		$form_params = [];
		$data['act'] = "Edit";
		$data['page_title'] = "Gate In";
		$data['page_subtitle'] = "Gate In Page";

		if($this->request->isAjax()) {
			$form_params = [
			    "cpdepo" => "000",
			    "spdepo" => "000",
			    "cpitgl" => $_POST['cpipratgl'],
			    "cpiefin" => "1",
			    "cpichrgbb" => "1",
			    "cpipaidbb" => "1",
			    "cpieir" => $_POST['cpieir'],
			    "cpinopol" => $_POST['cpinopol'],
			    "cpidriver" => $_POST['cpidriver'],
			    "cpicargo" => $_POST['cpicargo'],
			    "cpiseal" => "1",
			    "cpiremark" => "1",
			    "cpiremark1" => "1",
			    "cpidpp" => "1",
			    "cpireceptno" => $_POST['cpireceptno'],
			    "cpideliver" => $_POST['cpideliver'],
			    "cpitruck" => "1",
			    "cpiorderno" => $_POST['cpiorderno']			
			];			
			$response = $this->client->request('POST','containerProcess/gateIns',[
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

			// echo var_dump($form_params);
			$data['message'] = "success";
			echo json_encode($data);die();
		}	
		$data['data'] = $this->get_data_gatein2($crno);
		return view('Modules\GateIn\Views\edit',$data);
	}

	public function get_data_gatein() 
	{
		if($this->request->isAjax()) {
			$uri_query = [
				"cpife1" => 1,
				"cpife2" => 0,
				"retype1" => 21,
				"retype2" => 22,
				"retype3" => 23,
				"crlastact1" => 'od',
				"crlastact2" => 'bi',
				"crno" => $_POST['crno']		
			];

			$response = $this->client->request('GET','containerProcess/getDataGateIN',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => $uri_query,
			]);
			
			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);die();						
			}

			if(isset($result['data']) &&($result['data']==null)) {
				$data['status'] = "Failled";
				$data['message'] = "Data Container tidak ditemukan.";
				echo json_encode($data);die();					
			}

			$data['message'] = 'success';
			$data['data'] = $result['data'][0];
			echo json_encode($data);die();				
		}
	}

	public function get_data_gatein2($crno) 
	{
		$uri_query = [
			"cpife1" => 1,
			"cpife2" => 0,
			"retype1" => 21,
			"retype2" => 22,
			"retype3" => 23,
			"crlastact1" => 'od',
			"crlastact2" => 'bi',
			"crno" => $crno		
		];

		$response = $this->client->request('GET','containerProcess/getDataGateIN',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $uri_query,
		]);
		
		$result = json_decode($response->getBody()->getContents(), true);	
		if(isset($result['status']) && ($result['status']=="Failled"))
		{
			$data['status'] = "Failled";
			$data['message'] = $result['message'];
			echo json_encode($data);die();						
		}

		if(isset($result['data']) &&($result['data']==null)) {
			$data['status'] = "Failled";
			$data['message'] = "Data Container tidak ditemukan.";
			echo json_encode($data);die();					
		}

		$data['message'] = 'success';
		return $result['data'][0];
	}

	// Chack Container Number
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

		
}