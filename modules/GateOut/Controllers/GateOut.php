<?php
namespace Modules\GateOut\Controllers;

use App\Libraries\MyPaging;

class GateOut extends \CodeIgniter\Controller
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

		$data = [];
		// $paging = new MyPaging();
		// $limit = 25;
		// $endpoint = 'dataListReports/listAllGateOuts';
		// $data['data'] = $paging->paginate($endpoint,$limit,'gateout');
		// $data['pager'] = $paging->pager;
		// $data['nomor'] = $paging->nomor($this->request->getVar('page_gateout'), $limit);		
		$data['page_title'] = "Gate Out";
		$data['page_subtitle'] = "Gate Out Page";
		return view('Modules\GateOut\Views\index',$data);
	}

	function list_data(){		
		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "")?$this->request->getPost('search'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        // $sort_dir = $this->get_sort_dir();		
		// PULL data from API
		$response = $this->client->request('GET','dataListReports/listAllGateOuts',[
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

	public function add()
	{
		$data = [];
		if($this->request->isAjax()) {
			$form_params = [
			"cpotgl"		=> $_POST['cpotgl'],
			"cpopr"			=> $_POST['cpopr'],
			"cpopr1"		=> $_POST['cpopr1'],
			"cpcust"		=> $_POST['cpcust'],
			"cpcust1"		=> $_POST['cpcust1'],
			"cpotruck"		=> $_POST['cpotruck'],
			"cporeceptno"	=> $_POST['cporeceptno'],
			"svsurdat"		=> $_POST['svsurdat'],
			"syid"			=> $_POST['syid'],
			"cpoorderno"	=> $_POST['cpoorderno'],
			"cpoeir"		=> $_POST['cpoeir'],
			"cporefout"		=> $_POST['cporefout'],
			"cpopratgl"		=> $_POST['cpopratgl'],
			"cpochrgbm"		=> $_POST['cpochrgbm'],
			"cpopaidbm"		=> $_POST['cpopaidbm'],
			"cpofe"			=> $_POST['cpofe'],
			"cpoterm"		=> $_POST['cpoterm'],
			"cpoload"		=> $_POST['cpoload'],
			"cpoloaddat"	=> $_POST['cpoloaddat'],
			"cpojam"		=> $_POST['cpojam'],
			"cpocargo"		=> $_POST['cpocargo'],
			"cposeal"		=> $_POST['cposeal'],
			"cpovoy"		=> $_POST['cpovoy'],
			"cpoves"		=> $_POST['cpoves'],
			"cporeceiv"		=> $_POST['cporeceiv'],
			"cpodpp"		=> $_POST['cpodpp'],
			"cpodriver"		=> $_POST['cpodriver'],
			"cponopol"		=> $_POST['cponopol'],
			"cporemark"		=> $_POST['cporemark'],
			"cpid"			=> $_POST['cpid']
			];

			$cp_response = $this->client->request('PUT','gateout/updateGateOut',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $form_params
			]);

			$result = json_decode($cp_response->getBody()->getContents(), true);	

			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}			
		}

		$data['act'] = "Add";
		$data['page_title'] = "Gate Out";
		$data['page_subtitle'] = "Gate Out Page";		
		$data['surveyor'] = $this->surveyor_dropdown();		
		return view('Modules\GateOut\Views\add',$data);
	}	

	public function get_data_gateout() 
	{
		if($this->request->isAjax()) {

			$container = $this->get_container($_POST['crno']);
			if($container=="") {
				$data['status'] = "Failled";
				$data['message'] = "Data Container tidak ditemukan.";	
				echo json_encode($data);die();					
			} else{
				if (($container['crlastact'] == "CO" &&  $container['crlastcond'] == "AC") ||  $container['lastact'] == "AC") {

					// periksa Query getByCrno di backend
					// getKitirRepoGateOut tdk bisa dipakai karne pakai 2 param (crno & cpoorderno) 
					$response = $this->client->request('GET','gateout/getByCrno',[
						'headers' => [
							'Accept' => 'application/json',
							'Authorization' => session()->get('login_token')
						],
						'query' => $_POST['crno'],
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
					$data['data'] = $result['data'];
					echo json_encode($data);die();					    		

		    	} else {

					$data['status'] = "Failled";
					$data['message'] = "Invalid. Status Container(".$container['crlastact'].")";	    		
					echo json_encode($data);die();					    		
		    	}	
			}		
		}
	}

	public function surveyor_dropdown() 
	{
		$client = api_connect();

		$response = $client->request('GET','gateOut/getAllSurveyor',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		$res = $result['data'];
		$option = "";
		$option .= '<select name="syid" id="syid" class="select-syid">';
		$option .= '<option value="">-select-</option>';
		foreach($res as $r) {
			$option .= "<option value='".$r['syid'] ."'". ((isset($selected) && $selected==$r['syid']) ? ' selected' : '').">".$r['syname']."</option>"; 
		}
		$option .="</select>";
		return $option; 
		die();
	}

	public function get_container($crno)
	{
		$response = $this->client->request('GET', 'containers/listOne', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'crNo' => $crno,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		// echo var_dump($result);die();
		if(isset($result['status'])&&($result['status']=="Failled")) {
			$data="";
		} else {
			$data = $result['data']; 
		}
		return $data;
	}	
}