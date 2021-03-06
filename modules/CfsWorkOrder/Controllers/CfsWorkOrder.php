<?php
namespace Modules\CfsWorkOrder\Controllers;

class CfsWorkOrder extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	function list_data(){		
		$module = service('uri')->getSegment(1);
		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        // $sort_dir = $this->get_sort_dir();		
		// PULL data from API
		$response = $this->client->request('GET','otherwo/getAll',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => (int)$offset,
				'limit'	=> (int)$limit,
				'search'	=> $search
			]
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		// dd($result);
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
            $record[] = $v['wono'];
            $record[] = $v['woopr'];
            $record[] = date('d-m-Y', strtotime($v['wodate']));		

			$btn_list .= '<a href="'.site_url('cfswo/edit/').$v['wonoid'].'" id="" class="btn btn-xs btn-success btn-tbl">edit</a>&nbsp;';
			// $btn_list .= '
			// 	<div class="btn-group">
			// 		<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Print <span class="caret"></span></button>
			// 		<ul class="dropdown-menu" role="menu">
			// 			<li><a href="#" class="print" data-wono="'.$v['wono'].'">Proforma</a></li>
			// 			<li><a href="#" class="print" data-wono="'.$v['wono'].'">RAB</a></li>
			// 			<li><a href="#" class="print" data-wono="'.$v['wono'].'">Surat Jalan</a></li>
			// 		</ul>
			// 	</div>
			// ';
			$btn_list .= '
				<div class="btn-group">
					<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Print <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#" class="" data-wono="'.$v['wono'].'">Proforma</a></li>
						<li><a href="#" class="" data-wono="'.$v['wono'].'">RAB</a></li>
						<li><a href="#" class="" data-wono="'.$v['wono'].'">Surat Jalan</a></li>
					</ul>
				</div>
			';			
			$btn_list .= '<a href="#" id="deleteWo" class="btn btn-xs btn-danger btn-tbl delete" data-wono="'.$v['wono'].'">delete</a>';

            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        
        echo json_encode($output);
		
	}

	public function index()
	{
		check_exp_time();
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view");
		define("has_insert", has_privilege_check($module, '_insert'));
		define("has_WorkOrder", has_privilege_check($module, '_approval'));
		define("has_edit", has_privilege_check($module, '_update'));
		define("has_delete", has_privilege_check($module, '_delete'));
		define("has_print", has_privilege_check($module, '_printpdf'));

		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		// $prcode = $token['prcode'];

		$data['data'] = "";

		$data['page_title'] = "CFS Work Order";
		$data['page_subtitle'] = "CFS Work Order Page";
		return view('Modules\CfsWorkOrder\Views\index',$data);
	}

	public function add()
	{
		$token = get_token_item();
		$uname = $token['username'];		
		$data = [];

		if($this->request->isAjax()) {
			$form_params = [];
			$reformat = [
				"wono" => $this->generateWONumber(),
				"wodate" => date('Y-m-d'),
				"wocrton" => date('Y-m-d'),
				"wocrtby" => $uname, 
				"womdfon" => date('Y-m-d'), 
				"womdfby" => $uname,
				"woopr" => $_POST['prcode']
			];
			$form_params = array_replace($_POST, $reformat);
			// echo var_dump($form_params);die();
			$validate = $this->validate([
	            'wotype'	=> 'required',
	            'prcode'	=> 'required',
	        	],
	            [
	            'wotype'	=> ['required' => 'WO TYPE field required'],
	            'prcode'	=> ['required' => 'PRINCIPAL field required']
		        ]
	    	);	
			// print_r($form_params);die();
	    	if(!$validate) {
	    		$data["status"] = "Failled";
	    		$data["message"] = \Config\Services::validation()->listErrors();
	    		echo json_encode($data);die();
	    	}		

			$response = $this->client->request('POST','otherwo/insertData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $form_params
			]);		

			$result = json_decode($response->getBody()->getContents(), true);


			if(isset($result['status']) && $result['status']=="Failled") {
	    		$data["status"] = "Failled";
	    		$data["message"] = $result['message'];
	    		echo json_encode($data);die();
			}

			// Insert RECEIPT
			// $this->insertReceipt($result['data']['wonoid']);

			$data["status"] = "success";
    		$data["message"] = "Data Saved";
    		$data["data"] = $result['data'];
    		echo json_encode($data);die();

		}

		$data['act'] = "Add";
		$data["select_ccode"] = $this->select_ccode("");
		$data['page_title'] = "CFS Work Order";
		$data['page_subtitle'] = "CFS Work Order Page";		
		$data['wo_number'] = $this->generateWONumber();		

		return view('Modules\CfsWorkOrder\Views\add',$data);
	}	

	public function get_data_detail($wonoid) {	
		$response = $this->client->request('GET','otherwo/detailWo',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'wonoid' => $wonoid
			]
		]);		

		$result = json_decode($response->getBody()->getContents(), true);
		// echo var_dump($result);die();
		$html="";
		if($result['data']['allData']!=NULL) {
			$no = 1;
			// echo var_dump($result['data']['crno']);
			foreach($result['data']['allData'] as $row) {
			$html .= '<tr>';
			$html .= '<td><input type="checkbox" name="checked_cr" class="checked_cr" value="0" ></td>';
			$html .= '<td>'.$no.'</td>';
			$html .= '<td>'.$row['crno'].'</td>';
			$html .= '<td>'.$row['ctcode'].'</td>';
			$html .= '<td>'.$row['cclength'].'</td>';
			$html .= '<td>'.$row['ccheight'].'</td>';
			$html .= '<td style="display:none;">'.$row['svid'].'</td>';
			$html .= '<td><a href="#" class="btn btn-xs btn-danger delete" data-wono="">delete</a></td>';
			$html .= '</tr>';

			$no++;
			}
			// die();
		}	
		else {
			$html .="<tr><td colspan='6'>No results found</td></tr>";
		}	

		echo json_encode($html);die();
	}

	public function save_all_detail()
	{
		$response = $this->client->request('PUT','workorder/updateAllWO',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'wono' => $_POST['WONO'],
				'crno' => $_POST['CRNOS']
			]
		]);		

		$result = json_decode($response->getBody()->getContents(), true);

		if(isset($result['status']) && $result['status']=="Failled") {
    		$data["status"] = "Failled";
    		$data["message"] = $result['message'];
    		echo json_encode($data);die();
		}

		$data["status"] = "success";
		$data["message"] = "Data Saved";
		echo json_encode($data);die();
	}

	public function save_one_detail()
	{
		// echo var_dump($_POST);die();
		$response = $this->client->request('PUT','workorder/updateWO',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'wono' => $_POST['WONO'], 
				'rpcrno' => $_POST['CRNO'], 
				'svid' => $_POST['SVID'] 
			]
		]);		

		$result = json_decode($response->getBody()->getContents(), true);

		if(isset($result['status']) && $result['status']=="Failled") {
    		$data["status"] = "Failled";
    		$data["message"] = $result['message'];
    		echo json_encode($data);die();
		}

		$data["status"] = "success";
		$data["message"] = "Data Saved";
		echo json_encode($data);die();
	}


	// EDIT
	public function edit($wonoid) 
	{
		$token = get_token_item();
		$uname = $token['username'];

		if($this->request->isAjax()) {
			$form_params = [];
			$reformat = [
				"wono" => $this->generateWONumber(),
				"wodate" => date('Y-m-d'),
				"wocrton" => date('Y-m-d'),
				"wocrtby" => $uname, 
				"womdfon" => date('Y-m-d'), 
				"womdfby" => $uname,
				"woopr" => $_POST['prcode']
			];
			$form_params = array_replace($_POST, $reformat);
			// echo var_dump($form_params);die();
			$validate = $this->validate([
	            'wotype'	=> 'required',
	            'prcode'	=> 'required',
	        	],
	            [
	            'wotype'	=> ['required' => 'WO TYPE field required'],
	            'prcode'	=> ['required' => 'PRINCIPAL field required']
		        ]
	    	);	

	    	if(!$validate) {
	    		$data["status"] = "Failled";
	    		$data["message"] = \Config\Services::validation()->listErrors();
	    		echo json_encode($data);die();
	    	}

			$response = $this->client->request('PUT','otherwo/updateWO',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $form_params
			]);		

			$result = json_decode($response->getBody()->getContents(), true);

			if(isset($result['status']) && $result['status']=="Failled") {
	    		$data["status"] = "Failled";
	    		$data["message"] = $result['message'];
	    		echo json_encode($data);die();
			}

			$data["status"] = "success";
			$data["message"] = "Success Update Data";
			$data['data'] = $result['data'];		
			echo json_encode($data);die();

		}

		$response = $this->client->request('GET','otherwo/detailWo',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'wonoid' => $wonoid
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		// dd($result['data'][0][0]);
		$data['act'] = "Edit";
		$data['page_title'] = "CFS Work Order";
		$data['page_subtitle'] = "CFS Work Order Page";		
		$data['header'] = $result['data'][0][0];	
		// data proforma
		$data["wonoid"] = $wonoid;		
		$data['proforma'] = $this->get_data_receipt2($wonoid);
		// data rab
		$data['rab'] = $this->get_data_rab($wonoid);
		//data container 
		$data_container = $this->getContainerByWonoId($wonoid);
		// dd($data_container);
		$data['dataContainer'] = $data_container;		
		// data barang
		$data["select_ccode"] = $this->select_ccode("");		

		return view('Modules\CfsWorkOrder\Views\edit',$data);
	}



	// PROFORMA
	public function insertReceipt($wonoid) 
	{
		$totalcharge = $_POST['wobiaya1']+$_POST['wobiaya2']+$_POST['wobiaya3']+$_POST['wobiaya4']+$_POST['wobiaya5']+$_POST['wobiaya6']+$_POST['wobiaya7']+$_POST['wobiaya_adm']+$_POST['wototal_pajak']+$_POST['womaterai']+$_POST['wototbiaya_lain']+$_POST['wototpph23'];
		$form_params = [
		"wonoid" => $wonoid,
		"woreceptdate" => date("Y-m-d"), 
		"woreceptno" => "", 
		"wocurr" => "IDR", 
		"worate" => "1", 
		"wodescbiaya1" => $_POST['wodescbiaya1'], 
		"wobiaya1" => $_POST['wobiaya1'],
		"wodescbiaya2" => $_POST['wodescbiaya2'], 
		"wobiaya2" => $_POST['wobiaya2'], 
		"wodescbiaya3" => $_POST['wodescbiaya3'], 
		"wobiaya3" =>$_POST['wobiaya3'], 
		"wodescbiaya4" => $_POST['wodescbiaya4'], 
		"wobiaya4" => $_POST['wobiaya4'], 
		"wodescbiaya5" => $_POST['wodescbiaya5'], 
		"wobiaya5" => $_POST['wobiaya5'],
		"wodescbiaya6" => $_POST['wodescbiaya6'], 
		"wobiaya6" => $_POST['wobiaya6'], 
		"wodescbiaya7" => $_POST['wodescbiaya7'], 
		"wobiaya7" => $_POST['wobiaya7'], 
		"wobiaya_adm" => $_POST['wobiaya_adm'],
		"wototal_pajak" => $_POST['wototal_pajak'], 
		"womaterai" => $_POST['womaterai'], 
		"wototbiaya_lain" => $_POST['wototbiaya_lain'], 
		"wototpph23" => $_POST['wototpph23'],
		"wototal_tagihan" => $totalcharge 
		];

		$validate = $this->validate([
            'wototal_pajak'	=> 'required',
            'wobiaya_adm'	=> 'required',
            'womaterai'		=> 'required',
            'wototbiaya_lain'	=> 'required',
            'wototpph23'	=> 'required',
        	],
            [
            'wototal_pajak'	=> ['required' => 'PPH field required'],
            'wobiaya_adm'	=> ['required' => 'ADMINISTRATION field required'],
            'womaterai'		=> ['required' => 'MATERAI field required'],
            'wototbiaya_lain'	=> ['required' => 'BIAYA LAIN field required'],
            'wototpph23'	=> ['required' => 'BIAYA LAIN field required'],
	        ]
    	);	

    	if(!$validate) {
    		$data["status"] = "Failled";
    		$data["message"] = \Config\Services::validation()->listErrors();
    		echo json_encode($data);die();
    	}

		$response = $this->client->request('POST','worecept/insertData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => $form_params
		]);		

		$result = json_decode($response->getBody()->getContents(), true);		
		if(isset($result['status']) && $result['status']=="Failled") {
    		$data["status"] = "Failled";
    		$data["message"] = $result['message'];
    		echo json_encode($data);die();
		} else {
			$data["status"] = "success";
			$data["message"] = "Data proforma saved";	
			echo json_encode($data);
			die();
		}

	}

	public function update_receipt() 
	{
		// $form_params = []; woreceptid
		$validate = $this->validate([
            'woreceptid'		=> 'required',
        	]
    	);	

    	if(!$validate) {
    		$data["status"] = "Failled";
    		$data["message"] = \Config\Services::validation()->listErrors();
    		echo json_encode($data);die();
    	}	
		$response = $this->client->request('PUT','worecept/updateWO',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => $_POST
		]);		

		$result = json_decode($response->getBody()->getContents(), true);
		// echo var_dump($result);die();
		if(isset($result['status']) && $result['status']=="Failled") {
    		$data["status"] = "Failled";
    		$data["message"] = $result['message'];
    		echo json_encode($data);die();
		}

		$data["status"] = "success";
		$data["message"] = "Data Work Order";
		$data['data'] = $result['data'];		
		echo json_encode($data);			
	}

	public function get_data_receipt() {
		$response = $this->client->request('GET','worecept/detailWoRecept',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'wonoid' => $_POST['wonoid'],
			]
		]);		

		$result = json_decode($response->getBody()->getContents(), true);
		// echo var_dump($result);die();
		echo json_encode($result['data'][0][0]);
	}

	public function get_data_receipt2($wonoid) {
		$response = $this->client->request('GET','worecept/detailWoRecept',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'wonoid' => $wonoid,
			]
		]);		

		$result = json_decode($response->getBody()->getContents(), true);
		// echo var_dump($result);die();
		if(isset($result['data'][0][0])){
			$data = $result['data'][0][0];
		} else {
			$data = "";
		}
		return $data;
	}
	public function generateWONumber() {
		$response = $this->client->request('GET','otherwo/getWODnumber',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);		

		$result = json_decode($response->getBody()->getContents(), true);
		return $result['data'];	
	}
	
	/*****************
	 TAB RAB
	*****************/	
	public function insertRab($wonoid) 
	{
		$totalcharge = $_POST['wobiaya1']+$_POST['wobiaya2']+$_POST['wobiaya3']+$_POST['wobiaya4']+$_POST['wobiaya5']+$_POST['wobiaya6']+$_POST['wobiaya7']+$_POST['wobiaya8']+$_POST['wobiaya9']+$_POST['wobiaya10']+$_POST['wobiaya11']+$_POST['wobiaya12']+$_POST['wobiaya13']+$_POST['wobiaya14']+$_POST['wobiaya15']+$_POST['wobiaya16']+$_POST['wobiaya17']+$_POST['wobiaya18']+$_POST['wobiaya19']+$_POST['wobiaya20']+$_POST['wobiaya_adm']+$_POST['wototal_pajak']+$_POST['womaterai']+$_POST['wototbiaya_lain']+$_POST['wototpph23'];
		$form_params = [
		"wonoid" => $wonoid,
		"worabdate" => date("Y-m-d"), 
		"worabno" => "", 
		"wocurr" => "IDR", 
		"worate" => "1", 
		"wodescrab1" => $_POST['wodescbiaya1'], 
		"wonilairab1" => $_POST['wobiaya1'],
		"wodescrab2" => $_POST['wodescbiaya2'], 
		"wonilairab2" => $_POST['wobiaya2'], 
		"wodescrab3" => $_POST['wodescbiaya3'], 
		"wonilairab3" =>$_POST['wobiaya3'], 
		"wodescrab4" => $_POST['wodescbiaya4'], 
		"wonilairab4" => $_POST['wobiaya4'], 
		"wodescrab5" => $_POST['wodescbiaya5'], 
		"wonilairab5" => $_POST['wobiaya5'],
		"wodescrab6" => $_POST['wodescbiaya6'], 
		"wonilairab6" => $_POST['wobiaya6'], 
		"wodescrab7" => $_POST['wodescbiaya7'], 
		"wonilairab7" => $_POST['wobiaya7'], 
		"wodescrab8" => $_POST['wodescbiaya8'], 
		"wonilairab8" => $_POST['wobiaya8'],
		"wodescrab9" => $_POST['wodescbiaya9'], 
		"wonilairab9" => $_POST['wobiaya9'], 
		"wodescrab10" => $_POST['wodescbiaya10'], 
		"wonilairab10" =>$_POST['wobiaya10'], 
		"wodescrab11" => $_POST['wodescbiaya11'], 
		"wonilairab11" => $_POST['wobiaya11'], 
		"wodescrab12" => $_POST['wodescbiaya12'], 
		"wonilairab12" => $_POST['wobiaya12'],
		"wodescrab13" => $_POST['wodescbiaya13'], 
		"wonilairab13" => $_POST['wobiaya13'], 
		"wodescrab14" => $_POST['wodescbiaya14'], 
		"wonilairab14" => $_POST['wobiaya14'], 
		"wodescrab15" => $_POST['wodescbiaya15'], 
		"wonilairab15" => $_POST['wobiaya15'],
		"wodescrab16" => $_POST['wodescbiaya16'], 
		"wonilairab16" => $_POST['wobiaya16'], 
		"wodescrab17" => $_POST['wodescbiaya17'], 
		"wonilairab17" => $_POST['wobiaya17'], 
		"wodescrab18" => $_POST['wodescbiaya18'], 
		"wonilairab18" => $_POST['wobiaya18'],
		"wodescrab19" => $_POST['wodescbiaya19'], 
		"wonilairab19" => $_POST['wobiaya19'], 		
		"wodescrab20" => $_POST['wodescbiaya20'], 
		"wonilairab20" => $_POST['wobiaya20'], 
		"wonilairab_adm" => $_POST['wobiaya_adm'],
		"wototal_pajak" => $_POST['wototal_pajak'], 
		"womaterai" => $_POST['womaterai'], 
		"wototbiaya_lain" => $_POST['wototbiaya_lain'], 
		"wototpph23" => $_POST['wototpph23'],
		"wototal_tagihan" => $totalcharge 
		];

		$response = $this->client->request('POST','worab/insertData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => $form_params
		]);		

		$result = json_decode($response->getBody()->getContents(), true);		
		if(isset($result['status']) && $result['status']=="Failled") {
    		$data["status"] = "Failled";
    		$data["message"] = $result['message'];
    		echo json_encode($data);die();
		} else {
			$data["status"] = "success";
			$data["message"] = "Data RAB saved";	
			echo json_encode($data);
			die();
		}

	}

	public function update_rab() 
	{
		// $form_params = []; woreceptid
		$validate = $this->validate([
            'worabid'	=> 'required',
        	]
    	);	

    	if(!$validate) {
    		$data["status"] = "Failled";
    		$data["message"] = \Config\Services::validation()->listErrors();
    		echo json_encode($data);die();
    	}	
		$response = $this->client->request('PUT','worab/updateWO',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => $_POST
		]);		

		$result = json_decode($response->getBody()->getContents(), true);
		// echo var_dump($result);die();
		if(isset($result['status']) && $result['status']=="Failled") {
    		$data["status"] = "Failled";
    		$data["message"] = $result['message'];
    		echo json_encode($data);die();
		}

		$data["status"] = "success";
		$data["message"] = "Data Work Order";
		$data['data'] = $result['data'];		
		echo json_encode($data);			
	}

	// masih kurang endpoint getRabByWonoID
	public function get_data_rab($wonoid) {
		$response = $this->client->request('GET','worab/getRabByWonoID',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'wonoid' => $wonoid,
			]
		]);		

		$result = json_decode($response->getBody()->getContents(), true);
		// echo var_dump($result);die();
		if(isset($result['data'][0][0])){
			$data = $result['data'][0][0];
		} else {
			$data = "";
		}
		return $data;
	}


	/*****************
	 TAB CONTAINER
	*****************/
	public function insertContainer()
	{
		/*
		/wocontainer/insertData
		wonoid, ordertype, cpopr, cpcust, crno, cccode, ctcode, cclength, ccheight,
		fe, remark, gatedate, sealno, wotype, cpiremark1, cpovoyid, cponotes,
		cpiorderno, cpireceptno, cpoorderno, cporeceptno, cpopratgl, cpocargo,
		cpitruck, cpinopol, cpinotes, wostok		
		*/
		if($_POST['wo_stok']=='1') {
			$wostok = true;
		} else if($_POST['wo_stok']=='0'){
			$wostok = false;
		}
		$form_params = [
			'wonoid' => $_POST['wono_id'], 
			'ordertype' => $_POST['wo_stok'], 
			'cpopr' => "", 
			'cpcust' => "", 
			'crno' => $_POST['crno'], 
			'cccode' => $_POST['cccode'], 
			'ctcode' => $_POST['ctcode'], 
			'cclength' => $_POST['cclength'], 
			'ccheight' => $_POST['ccheight'],
			'fe' => $_POST['cpife'], 
			'remark' => $_POST['remark'], 
			'gatedate' => date('Y-m-d'), 
			'sealno' => $_POST['sealno'], 
			'wotype' => $_POST['wo_type'], 
			'cpiremark1' => $_POST['remark'], 
			'cpovoyid' => "", 
			'cpovoy' => null, 
			'cponotes' => $_POST['remark'],
			// 'cpiorderno' => $_POST['wo_no'], 
			'cpoorderno' => $_POST['wo_no'], 
			'cpireceptno' => "", 
			'cporeceptno' => "", 
			'cpopratgl' => date('Y-m-d'), 
			'cpocargo' => "", 
			'cpitruck' => "", 
			'cpinopol' => "", 
			'cpinotes' => $_POST['remark'],
			'wostok' => $wostok, 
		];	

		if($this->request->isAjax()) {

			// jika Container baru=>insert ke tabel container
			if(isset($_POST['statusContainer']) && $_POST['statusContainer']=="new") {
				$dataContainer = [
		            "crno" 		=> $_POST['crno'],
		            "mtcode" 	=> $_POST['ctcode'],
		            "cccode" 	=> $_POST['cccode']					
				];
				$this->insertTableContainer($dataContainer);
			}

			$response = $this->client->request('POST','wocontainer/insertData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $form_params
			]);	

			$result = json_decode($response->getBody()->getContents(), true);
			// print_r($result);die();
			$data_container = $this->getContainerByWonoId($_POST['wono_id']);
			if(isset($result['status']) && $result['status']=="Failled") {
				$data['status'] = "Failled";
				$data['message'] = "Gagal menyimpan data.";
				// $data['dataContainer'] = $this->fill_table_container($data_container);				
				echo json_encode($data);
				die();
			} else {
				$data['status'] = "success";
				$data['message'] = "Berhasil menyimpan data.";
				$data['dataContainer'] = $this->fill_table_container($data_container);				
				echo json_encode($data);
				die();			
			}
		}
	}

	public function fill_table_container($data) {
		$html = "";
		if($data=="") {
			$html="";
		} else {
			$no=1;
			foreach($data[0] as $row) {		
				$html .= '<tr>';
				$html .= '<td>
					<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#myModal">edit</a>
					<a href="#" class="btn btn-danger btn-xs delete" data-wocid="'.$row['wocid'].'">delete</a>
				</td>';
				// $html .= '<td>
				// 	<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#myModal">edit</a>
				// 	<a href="#" class="btn btn-danger btn-xs delete" data-wocid="">delete</a>
				// </td>';				
				$html .= '<td class="no">'.$no.'</td>';
				// $html .= '<td class="wocid" style="display:none">'.$row['wocid'].'</td>';
				$html .= '<td class="crno">'.$row['crno'].'</td>';
				$html .= '<td class="cccode">'.$row['cccode'].'</td>';
				$html .= '<td class="ctcode">'.$row['ctcode'].'</td>';
				$html .= '<td class="cclength">'.$row['cclength'].'</td>';
				$html .= '<td class="ccheight">'.$row['ccheight'].'</td>';
				$html .= '<td class="ccheight">'.((isset($row['fe'])&&$row['fe']=="1")?'Full':'Empty').'</td>';
				$html .= '<td class="sealno">'.$row['sealno'].'</td>';
				$html .= '<td class="remark">'.$row['remark'].'</td>';
				$html .= '</tr>';
				$no++;
			}
		}

		return $html;
	}
	public function fill_table_edit_container($data) {
		$html = "";
		if($data=="") {
			$html="";
		} else {
			$no=1;
			foreach($data as $row) {
				if($row['rdaccount']=='user') {$rdaccount="U";}
				else if($row['rdaccount']=='owner') {$rdaccount="O";}
				else {$rdaccount="i";}				
				$html .= '<tr>';
				$html .= '<td>
						<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#myModal">edit</a>
					<a href="#" class="btn btn-danger btn-xs delete" data-wocid="'.$row['wocid'].'">delete</a>';
				// $html .= '<td>
				// 		<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#myModal">edit</a>
				// 	<a href="#" class="btn btn-danger btn-xs delete" data-wocid="">delete</a>';				
				$html .= '<td class="no">'.$no.'</td>';
				$html .= '<td class="wocid" style="display:none">'.$row['wocid'].'</td>';
				$html .= '<td class="crno">'.$row['crno'].'</td>';
				$html .= '<td class="cccode">'.$row['cccode'].'</td>';
				$html .= '<td class="ctcode">'.$row['ctcode'].'</td>';
				$html .= '<td class="cclength">'.$row['cclength'].'</td>';
				$html .= '<td class="ccheight">'.$row['ccheight'].'</td>';
				$html .= '<td class="fe">'.((isset($row['fe'])&&$row['fe']=="1")?'Full':'Empty').'</td>';
				$html .= '<td class="sealno">'.$row['sealno'].'</td>';
				$html .= '<td class="remark">'.$row['remark'].'</td>';
				$html .= '</tr>';
				$no++;
			}
		}

		return $html;
	}
	// public function delete_container() {
	// 	if($this->request->isAjax()) {
	// 		$CRNO = $_POST['CRNO'];
	// 		$response = $this->client->request('PUT','workorder/deleteWO',[
	// 			'headers' => [
	// 				'Accept' => 'application/json',
	// 				'Authorization' => session()->get('login_token')
	// 			],
	// 			'form_params' => [
	// 				'crno' => $CRNO,
	// 			]
	// 		]);	
	// 		$result = json_decode($response->getBody()->getContents(), true);
	// 		// echo var_dump($result);die();
	// 		if(isset($result['status']) && $result['status']=="Failled") {
	//     		$data["status"] = "Failled";
	//     		$data["message"] = $result['message'];
	//     		echo json_encode($data);die();
	// 		}

	// 		$data["status"] = "success";
	// 		$data["message"] = "Data Work Order";
	// 		$data['data'] = $result['data'];		
	// 		echo json_encode($data);	

	// 	}
	// }

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

	// dipakai jika "Use Container Outs"=true/checked
	public function checkContainerIn() 
	{
		/*
		return:
		- jika kontainer baru {"success":true,"message":"Data Empty","data":false}
		- jika salah format {"success":false,"message":"Invalid code"}
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
			// echo var_dump($result);die();
			if(isset($result['success']) && ($result['success']==false))
			{
				$data['status'] = "invalid";
				$data['message'] = "Invalid Container";
			} 
			else 
			{
				if(isset($result['data']) && ($result['data']==false)) 
				{
					$data['status'] = "new";
					// container baru (offstock container)
				} 
				else 
				{
					$cont = $result['data']['rows'][0];
					$data['status'] = "invalid";
					$data['message'] = "Invalid Container";
					$data['data'] = $cont;
				}
			}

		    // return $status;

			echo json_encode($data);die();
		}
	}	

	// dipakai jik "Use Container In"==true/checked
	public function checkContainerOut() 
	{
		/*
		return:
		- jika kontainer baru {"success":true,"message":"Data Empty","data":false}
		- jika salah format {"success":false,"message":"Invalid code"}
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
			// echo var_dump($result);die();
			if(isset($result['success']) && ($result['success']==false))
			{
				$data['status'] = "invalid";	
				$data['message'] = "Invalid Container";			
			} 
			else 
			{
				if(isset($result['data']) && ($result['data']==false)) 
				{
					$data['status'] = "invalid";
					$data['message'] = "Container not Found";
				} 
				else 
				{
					$data['status'] = "exists";
					$cont = $result['data']['rows'][0];
					if($cont['lastact'] == "HC") 
					{
						$data['status'] = "invalid";
						$data['message'] = "Invalid Container";
					} 
					else 
					{
						if(($cont['crlastact']=="CO" && $cont['crlastcond']=="AC") || ($cont['lastact']=="AC")) 
						{
							$data['status'] = "valid";
							$data['data'] = $cont;
						} 
						else 
						{
							$data['status'] = "invalid";
							// $data['message'] = "LASTACT: " . $cont['lastact'] . "LASTCOND: " . $cont['crlastcond'];
							$data['message'] = "Invalid Container";
						}
					}

				}
			}

		    // return $status;

			echo json_encode($data);die();
		}
	}	

	public function getContainerByWonoId($wonoid) 
	{
		$response = $this->client->request('GET', 'wocontainer/detailByWonoid', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'wonoid' => $wonoid
			]
		]);	
		
		$result = json_decode($response->getBody()->getContents(), true);
		
		if($result['data']==null) {
			$data = "";
		} else {
			$data = $result['data'];
		}

		return $data;
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
		if(isset($result['status'])&&($result['status']=="Failled")) {
			$status = 0;
		} else {
			$status = 1;
		}
		return $status;
	}

	public function insertTableContainer($data)
	{
		$body = [
            "crno" 		=> $data['crno'],
            "mtcode" 	=> $data['mtcode'],
            "cccode" 	=> $data['cccode'],
            "crowner" 	=> 0,
            "crcdp" 	=> 0,
            "crcsc" 	=> 0,
            "cracep" 	=> 0,
            "crmmyy" 	=> 0,
            "crweightk" => 0,
            "crweightl" => 0,
            "crtarak" 	=> 0,
            "crtaral" 	=> 0,
            "crnetk" 	=> 0,
            "crnetl" 	=> 0,
            "crvol" 	=> 0,
            "crmanuf" 	=> 0,
            "crmandat" 	=> 0,
            "crlastact" => "BI"
        ];


		$this->client->request('POST','containers/create',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'crNo' => $data['crno'],
				'dset' => $body
			]
		]);			
	}

	public function print($wono)
	{
		check_exp_time();
		$mpdf = new \Mpdf\Mpdf();	
		$data = [];

		$response = $this->client->request('GET','workorder/detailWoHeader',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'wono' => $wono
			]
		]);		

		$result = json_decode($response->getBody()->getContents(), true);

		$header = $result['data']['dataOne'][0];		
		$detail = $result['data']['dataTwo'];
		// dd($header);
		$html = '';

		$html .= '
		<html>
			<head>
				<title>WORK ORDER</title>
				<link href="' . base_url() . '/public/themes/smartdepo/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>
					body{font-family: Arial;}
					.page-header{display:block;padding:0;min-height:30px;margin-bottom:30px;}
					.head-left{float:left;width:200px;padding:0px;}
					.head-center{margin-left:200px;width:200px;padding:0px;text-align:center;}
					.head-right{float:left;padding:0px;margin-right:0px;text-align: right;}

					.tbl_header, .tbl_head_prain, .tbl_det_prain{border-spacing: 0;border-collapse: collapse;}
					.tbl_header td, .tbl_head_prain td{border-spacing: 0;border-collapse: collapse;}
					.t-right{text-align:right;}
					.t-left{text-align:left;}
					.t-center{text-align:center;}

					.tbl_det_prain th,.tbl_det_prain td {
						border:1px solid #666666!important;
						border-spacing: 0;
						border-collapse: collapse;
						padding:5px;

					}
					.line-space{border-bottom:1px solid #333;margin:30px 0;}
				</style>
			</head>
		';

		$html .= '<body>
			<div class="page-header">
				<table width="100%" class="tbl-header">
				<tr><td colspan="2"><h2>WORK ORDER</h2></td></tr>
				<tr><td colspan="2"><h2>&nbsp;</h2></td></tr>
				<tr>
				<td class="t-left">Principal : ' . $header['woopr'] . '</td>
				<td class="t-right">WO Number : ' . $header['wono'] . '</td>
				</tr>
				<tr>
				<td class="t-left">To : ' . $header['woto'] . '</td>
				<td class="t-right">Date : ' . $header['wodate'] . '</td>
				</tr>
				<tr>
				<td class="t-left">From : ' . $header['wofrom'] . '</td>
				<td class="t-right"></td>
				</tr>
				<tr>
				<td class="t-left">CC : ' . $header['wocc'] . '</td>
				<td class="t-right"></td>
				</tr>
				</table>
			</div>
		';
		$html .='
		<p>Dengan hormat,</p>
		<p>Terlampir daftar container waiting repair/cleaning, mohon segera dilakukan pemindahan ke blok damage sehingga bisa dilakukan repair/cleaning sesuai standard yang berlaku.</p>
		<p>Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
		<p>Hormat kami,</p>
		<p style="margin-top:40px;">MARKETING</p>
		';

		$html .='
		<table class="tbl_det_prain" width="100%">
			<thead>
				<tr>
					<th>NO</th>
					<th>CONTAINER No</th>
					<th>Type</th>
					<th>Length</th>
					<th>Date In</th>
					<th>Cost(USD)</th>
					<th>App. Date</th>
					<th>Remarks</th>			
				</tr>
			</thead>
			<tbody>';
			if($detail!=null){
				$i=1;
			foreach($detail as $d){
			$html .='<tr>
				<td class="t-center">' . $i . '</td>
				<td class="t-center">' .  $d['crno'] . '</td>
				<td class="t-center">' .  $d['ctcode'] . '</td>
				<td class="t-center">' .  $d['cclength'] . '</td>
				<td class="t-center"></td>
				<td class="t-center"></td>
				<td class="t-center"></td>
				<td class="t-center"></td>
			</tr>';	
			$i++;	
			}
			}
		$html .='
				</tbody>
			</table>
			<p style="margin-top:40px;">Notes : ' . @$header['wonotes'] . '</p>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		// echo $html;
		die();				
	}


	public function select_ccode($selected="")
	{
		$response = $this->client->request('GET','containercode/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => 0,
				'rows'	=> 1000,
				'search'=> "",
				'orderColumn' => "cccode",
				'orderType' => "ASC"
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$ccode = $result['data']['datas'];
		$option = "";
		$option .= '<select name="cccode" id="cccode" class="select-cccode">';
		$option .= '<option value="">-select-</option>';
		foreach($ccode as $cc) {
			$option .= "
			<option value='".$cc['cccode'] ."'". ((isset($selected) && $selected==$cc['cccode']) ? ' selected' : '').">".strtoupper($cc['cccode'])."
			</option>";
			// $ctcode[] = 
			// $ctdesc[] =  
		}
		$option .="</select>";
		return $option; 
		die();			
	}	
}