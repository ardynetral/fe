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

			$data['data'] = isset($result_pra['data']['datas'])?$result_pra['data']['datas']:"";
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

			$data['data'] = $data_pra;
		}

		// $data['prcode'] = $prcode;
		// $data['cucode'] = $prcode;
		// echo dd($data);
		// return view('Modules\PraIn\Views\tab_base',$data);
		$data['page_title'] = "Gate In";
		$data['page_subtitle'] = "Gate In Page";
		return view('Modules\GateIn\Views\index',$data);
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
			    "cpitgl" => $_POST['cpitgl'],
			    "cpiefin" => $_POST['cpiefin'],
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

	function list_data(){		
		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "")?$this->request->getPost('search'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        	
		// PULL data from API
		$response = $this->client->request('GET','dataListReports/listAllGateOuts',
			[
				'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
				'query' => [
				'offset' => $offset,
				'limit'	=> $limit
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		
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
            $record[] = $v['CRNO'];
            $record[] = $v['CPOTGL'];
            $record[] = $v['CPOPR1'];
            $record[] = $v['CPOORDERNO'];
			$record[] = $v['CPOEIR'];
			
			$btn_list .='<a href="#" id="" class="btn btn-xs btn-primary btn-table" data-praid="">view</a>';						
			$btn_list .='<a href="#" id="editPraIn" class="btn btn-xs btn-success btn-table">edit</a>';
			$btn_list .='<a href="#" class="btn btn-xs btn-info btn-table" data-praid="">print</a>';	
			$btn_list .='<a href="#" id="deleteRow_'.$no.'" class="btn btn-xs btn-danger btn-table">delete</a>';			
            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);
		
	}		
}