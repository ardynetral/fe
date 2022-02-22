<?php
namespace Modules\WorkOrder\Controllers;

class WorkOrder extends \CodeIgniter\Controller
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
		$response = $this->client->request('GET','workorder/getAll',[
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

			$btn_list .= '<a href="'.site_url('wo/edit/').$v['wono'].'" id="editWo" class="btn btn-xs btn-success btn-tbl edit">edit</a>';
			// $btn_list .= '<a href="#" class="btn btn-xs btn-info btn-tbl print" data-wono="'.$v['wono'].'">print</a>';
			// $btn_list .= '<a href="#" id="deleteWo" class="btn btn-xs btn-danger btn-tbl delete">delete</a>';

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

		$data['page_title'] = "Work Order";
		$data['page_subtitle'] = "Work Order Page";
		return view('Modules\WorkOrder\Views\index',$data);
	}

	public function add()
	{
		$token = get_token_item();
		$uname = $token['username'];		
		$data = [];

		if($this->request->isAjax()) {
			$form_params = [
				"wono" => $this->generateWONumber(),
				"wodate" => date('Y-m-d'),
				"wocomp" => 0,
				"woto" => $this->request->getPost('woto'),
				"wocc" => $this->request->getPost('wocc'),
				"wofrom" => $this->request->getPost('wofrom'),
				"woopr" => $this->request->getPost('prcode'),
				"wonotes" => $this->request->getPost('wonotes'),
				"wotype" => $this->request->getPost('wotype'),
				"wocrton" => date('Y-m-d'), 
				"wocrtby" => $uname, 
				"womdfon" => date('Y-m-d'), 
				"womdfby" => $uname
			];
			$validate = $this->validate([
	            'wono'		=> 'required',
	            'woto'		=> 'required',
	            'wofrom'	=> 'required',
	            'wocc'		=> 'required',
	            'prcode'	=> 'required',
	            'wotype'	=> 'required'
	        	],
	            [
	            'wono'		=> ['required' => 'WO NUMBER field required'],
	            'woto'		=> ['required' => 'TO field required'],
	            'wofrom'	=> ['required' => 'FROM field required'],
	            'wocc'		=> ['required' => 'CC field required'],
	            'prcode'	=> ['required' => 'PRINCIPAL field required'],
	            'wotype'	=> ['required' => 'CONDITION BOX field required']
		        ]
	    	);	

	    	if(!$validate) {
	    		$data["status"] = "Failled";
	    		$data["message"] = \Config\Services::validation()->listErrors();
	    		echo json_encode($data);die();
	    	}		

			$response = $this->client->request('POST','workorder/insertData',[
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
    		$data["message"] = "Data Saved";
    		$data["data"] = $result['data'];
    		echo json_encode($data);die();

		}

		$data['act'] = "Add";
		$data['page_title'] = "Work Order";
		$data['page_subtitle'] = "Work Order Page";		
		$data['wo_number'] = $this->generateWONumber();		

		return view('Modules\WorkOrder\Views\add',$data);
	}	

	public function get_data_detail() {
		// listComboBox?wotype=DN&cpopr=CMA		
		$response = $this->client->request('GET','workorder/listComboBox',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'wotype' => $_POST['wotype'],
				'cpopr' => $_POST['woopr']
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
	public function edit($wono) 
	{
		if($this->request->isAjax()) {

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
		// dd($result['data']);
		$data['act'] = "Add";
		$data['page_title'] = "Work Order";
		$data['page_subtitle'] = "Work Order Page";		
		$data['header'] = $result['data']['dataOne'][0];		
		$data['detail'] = $result['data']['dataTwo'];		

		return view('Modules\WorkOrder\Views\edit',$data);
	}

	public function delete_container() {
		if($this->request->isAjax()) {
			$SVID = $_POST['SVID'];
			$CRNO = $_POST['CRNO'];
			$response = $this->client->request('PUT','workorder/deleteWO',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'svid' => $SVID,
					'rpcrno' => $CRNO,
				]
			]);	
			$result = json_decode($response->getBody()->getContents(), true);
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
	}

	public function generateWONumber() {
		$response = $this->client->request('GET','workorder/getWOnumber',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);		

		$result = json_decode($response->getBody()->getContents(), true);
		return $result['data'];	
	}
}