<?php
namespace Modules\Approval\Controllers;

use App\Libraries\MyPaging;

class Approval extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	function list_data()
	{		
		$module = service('uri')->getSegment(1);
		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;

		$response = $this->client->request('GET','dataListReports/listAllApprovals',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'offset' => (int)$offset,
					'limit'	=> (int)$limit,
					'search' => $search
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
            $record[] = $v['PRCODE'];
            $record[] = $v['CPITGL'];
            $record[] = $v['SVSURDAT'];
            $record[] = $v['SVCOND'];
            $record[] = $v['RPNOEST'];
            $record[] = $v['RPVER'];
            $record[] = $v['RPTGLAPPVPR'];
			
			$btn_list .= '<a href="'.site_url('approval/view/').$v['CRNO'].'" id="" class="btn btn-xs btn-primary btn-tbl">view</a>';	
			
			// $btn_list .= '<a href="#" class="btn btn-xs btn-info btn-tbl print" data-crno="'.$v['CRNO'].'">print</a>';
			$btn_list .= '
				<div class="btn-group">
					<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Print <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#" class="print" data-crno="'.$v['CRNO'].'" data-type="all">All</a></li>
						<li><a href="#" class="print" data-crno="'.$v['CRNO'].'" data-type="owner">Owner</a></li>
						<li><a href="#" class="print" data-crno="'.$v['CRNO'].'" data-type="user">User</a></li>
					</ul>
				</div>';			

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
		define("has_approval", has_privilege_check($module, '_approval'));
		define("has_edit", has_privilege_check($module, '_update'));
		define("has_delete", has_privilege_check($module, '_delete'));
		define("has_print", has_privilege_check($module, '_printpdf'));

		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];

		$data = [];
		$data['page_title'] = "Approval";
		$data['page_subtitle'] = "Approval Page";
		return view('Modules\Approval\Views\index',$data);
	}

	public function view($CRNO) 
	{
		$dt_estimasi = $this->getOneEstimasi($CRNO);
		$header = $dt_estimasi['dataOne'][0];
		$detail = $dt_estimasi['dataTwo'];
		$data['status'] = "success";
		$data['header'] = $header;
		$data['detail'] = $detail;	
			
		$data['act'] = "view";
		$data['page_title'] = "Approval";
		$data['page_subtitle'] = "Approval Page";
		$data['lccode_dropdown'] = $this->lccode_dropdown();
		$data['cmcode_dropdown'] = $this->cmcode_dropdown();
		$data['dycode_dropdown'] = $this->dycode_dropdown();
		$data['rmcode_dropdown'] = $this->rmcode_dropdown();
		$data['emkl_dropdown'] = $this->emkl_dropdown();	
		return view('Modules\Approval\Views\view',$data);
	}

	public function add()
	{
		$data = [];
		$data['data'] = "";
		$data['act'] = "Add";
		$data['page_title'] = "Approval";
		$data['page_subtitle'] = "Approval Page";
		$data['lccode_dropdown'] = $this->lccode_dropdown();
		$data['cmcode_dropdown'] = $this->cmcode_dropdown();
		$data['dycode_dropdown'] = $this->dycode_dropdown();
		$data['rmcode_dropdown'] = $this->rmcode_dropdown();		
		$data['emkl_dropdown'] = $this->emkl_dropdown();		
		return view('Modules\Approval\Views\add',$data);
	}

	public function final_estimasi() 
	{
		$detail = $this->getDetListByCrno($_POST['final_crno']);
		$totalrmhr = 0;
		$totallab=0;
		$totalcost=0;
		$total=0;
		foreach($detail as $det) {
			$totalrmhr = $totalrmhr+(int)$det['rdmhr'];
			$totallab = $totallab+(int)$det['rdlab'];
			$totalcost = $totalcost+(int)$det['rdmat'];
			$total = $total+(int)$det['rdtotal'];
		}
		$form_params = [
			"crno" => $_POST['final_crno'],
			"svid" => $_POST['final_svid'],
			"autno" => $_POST['autno'],
			"rpnotesa" => $_POST['rpnotesa'],
			"rpbillon" => $_POST['rpbillon'],
			"totalrmhr" => $totalrmhr,
			"totallab" => $totallab,
			"totalcost" => $totalcost,
			"total" => $total
		];

		if($this->request->isAjax()) {
			$response = $this->client->request('POST', 'estimasi/finalEstimasi', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $form_params
			]);	

			$result = json_decode($response->getBody()->getContents(), true);
			// echo var_dump($result);die();
			if(isset($result['status']) && $result['status']=="Failled") {
				$data['status'] = "Failled";	
				$data['message'] = $result['message'][0];
				echo json_encode($data);
				die();	
			}	
			$data['status'] = "success";	
			$data['message'] = "Berhasil menyimpan data";
			echo json_encode($data);
			die();
		}
	}
	public function next_estimasi($svid) 
	{
		if($this->request->isAjax()) {
			$response = $this->client->request('POST', 'estimasi/nextEstimasi', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'svid' => $svid
				]
			]);	

			$result = json_decode($response->getBody()->getContents(), true);

			if(isset($result['status']) && $result['status']=="Failled") {
				$data['status'] = "Failled";	
				$data['message'] = "Gagal menyimpan data";
				echo json_encode($data);
				die();	
			}	
			$dt_estimasi = $this->getOneEstimasi($_POST['crno']);
			$data['status'] = "success";	
			$data['message'] = "Berhasil menyimpan data";
			$data['header'] = $dt_estimasi['dataOne'][0];
			echo json_encode($data);
			die();
		}
	}
	public function save_detail()
	{
		check_exp_time();
		$data = [];
		$data['data'] = "";
		$form_params = [];
		if($this->request->isAjax()) {
			// echo var_dump($this->request->getPost());die();

			$form_params[] = [
				"name" => "svid",
				"contents" => $this->request->getPost('det_svid')
			];
			// buat auto increment +1
			$form_params[] = [
				"name" => "rpid",
				"contents" => $this->request->getPost('rpid')
			];
			$form_params[] = [
				"name" => "rdteb",
				"contents" => ""
			];
			$form_params[] = [
				"name" => "rdloc",
				"contents" => $this->request->getPost('lccode')
			];
			$form_params[] = [
				"name" => "rdcom",
				"contents" => $this->request->getPost('cmcode')
			];
			$form_params[] = [
				"name" => "rddmtype",
				"contents" => $this->request->getPost('dycode')
			];
			$form_params[] = [
				"name" => "rdrepmtd",
				"contents" => $this->request->getPost('rmcode')
			];
			$form_params[] = [
				"name" => "rdcalmtd",
				"contents" => $this->request->getPost('rdcalmtd')
			];
			$form_params[] = [
				"name" => "rdsize",
				"contents" => $this->request->getPost('rdsize')
			];
			$form_params[] = [
				"name" => "muname",
				"contents" => $this->request->getPost('muname')
			];
			$form_params[] = [
				"name" => "rdqty",
				"contents" => $this->request->getPost('rdqtyact')
			];
			$form_params[] = [
				"name" => "rdmhr",
				"contents" => $this->request->getPost('rdmhr')
			];
			$form_params[] = [
				"name" => "rdcurr",
				"contents" => $this->request->getPost('tucode')
			];
			$form_params[] = [
				"name" => "rdmat",
				"contents" => $this->request->getPost('rdmat')
			];
			$form_params[] = [
				"name" => "rdtotal",
				"contents" => $this->request->getPost('rdtotal')
			];
			$form_params[] = [
				"name" => "rdaccount",
				"contents" => $this->request->getPost('rdaccount')
			];
			$form_params[] = [
				"name" => "rdno",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rdlab",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rddesc",
				"contents" => ""
			];
			$form_params[] = [
				"name" => "rdpic",
				"contents" => ""
			];
			$form_params[] = [
				"name" => "rdsizea",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rdqtya",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rdmhra",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rdmata",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rdlaba",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "flag",
				"contents" => "1"
			];

			if($_FILES["files"]['name'][0] =="") {

				$form_params[] = [
					'name' => 'file',
					'contents'	=> "",
					'filename'	=> "",
				];	

			} else {

				foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name) {
					if(is_array($_FILES["files"]["tmp_name"])) {
						$form_params[] = [
							'name' => 'file',
							'contents'	=> fopen($_FILES["files"]['tmp_name'][$key],'r'),
							'filename'	=> $_FILES["files"]['name'][$key],
						];
						continue;
					}
				}
			}
			
			$response = $this->client->request('POST', 'estimasi/createDetail', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'multipart' => $form_params
			]);	

			$result = json_decode($response->getBody()->getContents(), true);		
			
			if(isset($result['status']) && $result['status']=="Failled") {
				$data['status'] = "Failled";	
				$data['message'] = "Gagal menyimpan data";
				$data['data'] = "";
				echo json_encode($data);
				die();	
			}	
			$data_detail = $this->getDetListByCrno($this->request->getPost('det_crno'));
			$data['status'] = "success";	
			$data['message'] = "Berhasil menyimpan data";
			$data['data'] = ((isset($_POST['act'])&&$_POST['act']=="add")?$this->fill_table_edit_detail($data_detail):$this->fill_table_detail($data_detail));
			// $data['data'] = ;
			echo json_encode($data);
			die();
		}	

		$data['act'] = "Add";
		$data['page_title'] = "Estimation";
		$data['page_subtitle'] = "Estimation Page";
		$data['lccode_dropdown'] = $this->lccode_dropdown();
		$data['cmcode_dropdown'] = $this->cmcode_dropdown();
		$data['dycode_dropdown'] = $this->dycode_dropdown();
		$data['rmcode_dropdown'] = $this->rmcode_dropdown();
		return view('Modules\Approval\Views\add',$data);
	}

	public function edit($CRNO) 
	{
		if($this->request->isAjax()) {
		
		}

		$dt_estimasi = $this->getOneEstimasi($CRNO);
		$header = $dt_estimasi['dataOne'][0];
		$detail = $dt_estimasi['dataTwo'];
		$data['status'] = "success";
		$data['header'] = $header;
		$data['detail'] = $detail;	
			
		$data['act'] = "Add";
		$data['page_title'] = "Approval";
		$data['page_subtitle'] = "Approval Page";
		$data['lccode_dropdown'] = $this->lccode_dropdown();
		$data['cmcode_dropdown'] = $this->cmcode_dropdown();
		$data['dycode_dropdown'] = $this->dycode_dropdown();
		$data['rmcode_dropdown'] = $this->rmcode_dropdown();
		$data['emkl_dropdown'] = $this->emkl_dropdown();
		return view('Modules\Approval\Views\edit',$data);
	}

	public function update_detail()
	{
		if($this->request->isAjax())
		{
			$form_params[] = [
				"name" => "svid",
				"contents" => $this->request->getPost('det_svid')
			];
			// buat auto increment +1
			$form_params[] = [
				"name" => "rpid",
				"contents" => $this->request->getPost('rpid')
			];
			$form_params[] = [
				"name" => "rdteb",
				"contents" => ""
			];
			$form_params[] = [
				"name" => "rdloc",
				"contents" => $this->request->getPost('lccode')
			];
			$form_params[] = [
				"name" => "rdcom",
				"contents" => $this->request->getPost('cmcode')
			];
			$form_params[] = [
				"name" => "rddmtype",
				"contents" => $this->request->getPost('dycode')
			];
			$form_params[] = [
				"name" => "rdrepmtd",
				"contents" => $this->request->getPost('rmcode')
			];
			$form_params[] = [
				"name" => "rdcalmtd",
				"contents" => $this->request->getPost('rdcalmtd')
			];
			$form_params[] = [
				"name" => "rdsize",
				"contents" => $this->request->getPost('rdsize')
			];
			$form_params[] = [
				"name" => "muname",
				"contents" => $this->request->getPost('muname')
			];
			$form_params[] = [
				"name" => "rdqty",
				"contents" => $this->request->getPost('rdqtyact')
			];
			$form_params[] = [
				"name" => "rdmhr",
				"contents" => $this->request->getPost('rdmhr')
			];
			$form_params[] = [
				"name" => "rdcurr",
				"contents" => $this->request->getPost('tucode')
			];
			$form_params[] = [
				"name" => "rdmat",
				"contents" => $this->request->getPost('rdmat')
			];
			$form_params[] = [
				"name" => "rdtotal",
				"contents" => $this->request->getPost('rdtotal')
			];
			$form_params[] = [
				"name" => "rdaccount",
				"contents" => $this->request->getPost('rdaccount')
			];
			$form_params[] = [
				"name" => "rdno",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rdlab",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rddesc",
				"contents" => ""
			];
			$form_params[] = [
				"name" => "rdpic",
				"contents" => ""
			];
			$form_params[] = [
				"name" => "rdsizea",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rdqtya",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rdmhra",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rdmata",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "rdlaba",
				"contents" => "1"
			];
			$form_params[] = [
				"name" => "flag",
				"contents" => "1"
			];
			// echo var_dump($_FILES);die();
			if($_FILES["files"]['name'][0] =="") {

				$form_params[] = [
					'name' => 'file',
					'contents'	=> "",
					'filename'	=> "",
				];	

			} else {

				foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name) {
					if(is_array($_FILES["files"]["tmp_name"])) {
						$form_params[] = [
							'name' => 'file',
							'contents'	=> fopen($_FILES["files"]['tmp_name'][$key],'r'),
							'filename'	=> $_FILES["files"]['name'][$key],
						];
						continue;
					}
				}
			}

			$response = $this->client->request('PUT', 'estimasi/updateDetail', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'multipart' => $form_params
			]);	

			$result = json_decode($response->getBody()->getContents(), true);		
			// echo var_dump($result);die();
			if(isset($result['status']) && $result['status']=="Failled") {
				$data['status'] = "Failled";	
				$data['message'] = "Gagal menyimpan: '". $result['message'][0] ."'";
				$data['data'] = "";
				echo json_encode($data);
				die();	
			}	
			$data_detail = $this->getDetListByCrno($this->request->getPost('det_crno'));
			$data['status'] = "success";	
			$data['message'] = "Berhasil menyimpan data";
			$data['data'] = $this->fill_table_edit_detail($data_detail);
			echo json_encode($data);
			die();				
		}
	}
	// by param 
	public function getOneEstimasi($CRNO) 
	{
		$response = $this->client->request('GET', 'estimasi/listOneCrno', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'crno' => $CRNO
			]
		]);	
		$result = json_decode($response->getBody()->getContents(), true);
		if($result['data']==null) {
			$data = "";
		}
		$data = $result['data'];
		return $data;	
	}

	// ajax request
	public function getDataEstimasi() 
	{
		$CRNO = $this->request->getPost('crno');
		if($this->request->isAjax()) {
			$response = $this->client->request('GET', 'estimasi/listOneCrno', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'crno' => $CRNO
				]
			]);	
			$result = json_decode($response->getBody()->getContents(), true);
			if($result['data']['dataOne']==null) {
				$data['status'] = "Failled";
				$data['message'] = "Data tidak ditemukan";
				echo json_encode($data);
				die();
			}
			$data['status'] = "success";
			$data['header'] = $result['data']['dataOne'][0];
			$data['detail'] = $this->fill_table_detail($result['data']['dataTwo']);
			// echo var_dump($result);die();		
			echo json_encode($data);
			die();
		}
	}

	public function getDetListByCrno($CRNO) 
	{
		if($this->request->isAjax()) {
			$response = $this->client->request('GET', 'estimasi/listOneCrno', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'crno' => $CRNO
				]
			]);	
			$result = json_decode($response->getBody()->getContents(), true);
			if($result['data']['dataOne']==null) {
				$data = "";
			}
			$data = $result['data']['dataTwo'];
			return $data;
		}
		
		$response = $this->client->request('GET', 'estimasi/listOneCrno', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'crno' => $CRNO
			]
		]);	
		$result = json_decode($response->getBody()->getContents(), true);
		if($result['data']['dataOne']==null) {
			$data = "";
		}
		$data = $result['data']['dataTwo'];
		return $data;		
	}

	public function getFileList($crno) 
	{
		if($this->request->isAjax()) {
			$response = $this->client->request('GET', 'estimasi/getFileDetail', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'crno' => $crno
				]
			]);	

			$result = json_decode($response->getBody()->getContents(), true);
			// echo var_dump($result);die();
			if($result['data'] == NULL) {
				$html = "<i>No file exists</i>";
				echo json_encode($html);
				die();				
			} 
			$data = $result['data'];
			$html = "";
			foreach($data as $row) {
				$no=1;
			$html .= '<a href="'.$row['url'].'" class="btn btn-default btn-sm" target="_blank">File '.$no.'</a>&nbsp;';
			$no++;
			}
			echo json_encode($html);
			die();
		}
	}

	public function fill_table_detail($data) {
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
						<a href="#" class="btn btn-danger btn-xs delete" data-svid="'.$row['svid'].'" data-rpid="'.$row['rpid'].'" data-rdno="'.$row['rdno'].'" data-crno="'.$row['rpcrno'].'">delete</a></td>';
				$html .= '<td class="no">'.$no.'</td>';
				$html .= '<td class="lccode" style="display:none">'.$row['lccode'].'</td>';
				$html .= '<td class="cmcode">'.$row['cmcode'].'</td>';
				$html .= '<td class="dycode">'.$row['dycode'].'</td>';
				$html .= '<td class="rmcode">'.$row['rmcode'].'</td>';
				$html .= '<td class="rdcalmtd">'.$row['rdcalmtd'].'</td>';
				$html .= '<td class="rdmhr"></td>';
				$html .= '<td class="rdsize">'.$row['rdsize'].'</td>';
				$html .= '<td class="muname">'.$row['muname'].'</td>';
				$html .= '<td class="rdqty">'.$row['rdqty'].'</td>';
				$html .= '<td class="rdmhr">'.$row['rdmhr'].'</td>';
				$html .= '<td class="curr_symbol">'.$row['curr_symbol'].'</td>';
				$html .= '<td class="rddesc">'.$row['rddesc'].'</td>';
				$html .= '<td class="rdlab">'.number_format($row['rdlab'],2).'<span style="display:none;">'.$row['rdlab'].'</span></td>';
				$html .= '<td class="rdmat">'.number_format($row['rdmat'],2).'<span style="display:none;">'.$row['rdmat'].'</span></td>';
				$html .= '<td class="rdaccount" style="display:none;">'.$rdaccount.'</td>';
				$html .= '<td class="rdtotal" style="display:none;">'.$row['rdtotal'].'</td>';
				$html .= '</tr>';
				$no++;
			}
		}

		return $html;
	}
	public function fill_table_edit_detail($data) {
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
						<a href="#" class="btn btn-primary btn-xs edit">edit</a>
						<a href="#" class="btn btn-danger btn-xs delete" data-svid="'.$row['svid'].'" data-rpid="'.$row['rpid'].'" data-rdno="'.$row['rdno'].'" data-crno="'.$row['rpcrno'].'">delete</a></td>';
				$html .= '<td class="no">'.$no.'</td>';
				$html .= '<td class="crno" style="display:none">'.$row['rpcrno'].'</td>';
				$html .= '<td class="svid" style="display:none">'.$row['svid'].'</td>';
				$html .= '<td class="lccode" style="display:none">'.$row['lccode'].'</td>';
				$html .= '<td class="cmcode">'.$row['cmcode'].'</td>';
				$html .= '<td class="dycode">'.$row['dycode'].'</td>';
				$html .= '<td class="rmcode">'.$row['rmcode'].'</td>';
				$html .= '<td class="rdcalmtd">'.$row['rdcalmtd'].'</td>';
				$html .= '<td class="rdmhr"></td>';
				$html .= '<td class="rdsize">'.$row['rdsize'].'</td>';
				$html .= '<td class="muname">'.$row['muname'].'</td>';
				$html .= '<td class="rdqty">'.$row['rdqty'].'</td>';
				$html .= '<td class="rdmhr">'.$row['rdmhr'].'</td>';
				$html .= '<td class="curr_symbol">'.$row['curr_symbol'].'</td>';
				$html .= '<td class="rddesc">'.$row['rddesc'].'</td>';
				$html .= '<td class="rdlab">'.number_format($row['rdlab'],2).'<span style="display:none;">'.$row['rdlab'].'</span></td>';
				$html .= '<td class="rdmat">'.number_format($row['rdmat'],2).'<span style="display:none;">'.$row['rdmat'].'</span></td>';
				$html .= '<td class="rdaccount" style="display:none;">'.$rdaccount.'</td>';
				$html .= '<td class="rdtotal" style="display:none;">'.$row['rdtotal'].'</td>';
				$html .= '</tr>';
				$no++;
			}
		}

		return $html;
	}

	public function delete_detail() 
	{
		if($this->request->isAjax()) {

			$response = $this->client->request('DELETE', 'estimasi/delete', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => [
					'SVID' => $_POST['svid'],
					'RPID' => $_POST['rpid'],
					'RDNO' => $_POST['rdno']
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);
			// echo var_dump($result);die();
			if(isset($result['status']) && ($result['status']=="Failled")) {
				$data['status'] = "Failled";
				$data['message'] = "Gagal hapus data";
				echo json_encode($data);
				die();
			}

			$data['status'] = "success";
			$data['message'] = "Berhasil hapus data";
			$data_detail = $this->getDetListByCrno($this->request->getPost('crno'));
			$data['data'] = $this->fill_table_detail($data_detail);			
			echo json_encode($data);
			die();
		}
	}
	public function delete_detail_edit() 
	{
		if($this->request->isAjax()) {

			$response = $this->client->request('DELETE', 'estimasi/delete', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => [
					'SVID' => $_POST['svid'],
					'RPID' => $_POST['rpid'],
					'RDNO' => $_POST['rdno']
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);
			// echo var_dump($result);die();
			if(isset($result['status']) && ($result['status']=="Failled")) {
				$data['status'] = "Failled";
				$data['message'] = "Gagal hapus data";
				echo json_encode($data);
				die();
			}

			$data['status'] = "success";
			$data['message'] = "Berhasil hapus data";
			$data_detail = $this->getDetListByCrno($this->request->getPost('crno'));
			$data['data'] = $this->fill_table_edit_detail($data_detail);			
			echo json_encode($data);
			die();
		}
	}

	public function getFileToPrint($crno) 
	{
		$response = $this->client->request('GET', 'estimasi/getFileDetail', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'crno' => $crno
			]
		]);	

		$result = json_decode($response->getBody()->getContents(), true);
		if($result['data'] == NULL) {
			$data = "";			
		} 
		return $result['data'];

	}

	public function print($crno,$type) 
	{
		$mpdf = new \Mpdf\Mpdf();

		$data = $this->getOneEstimasi($crno);
		$header = $data['dataOne'][0];
		$detail = $data['dataTwo'];
		$files = $this->getFileToPrint($crno);
		// dd($files);
		$html = '';
		$html .= '
		<html>
			<head>
				<title>Approval</title>
				<link href="'.base_url().'/public/themes/smartdepo/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>
					body{font-family: Arial;font-size:12px;}
					h2{font-weight:normal;line-height:.5;}
					.tbl_head, .tbl_det, .tbl-borderless{border-spacing: 0;border-collapse: collapse;}
					.tbl_head_prain td{border-collapse: collapse;}
					.t-right{text-align:right;}
					.t-left{text-align:left;}
					.t-center{text-align:center;}

					.tbl_head td, .tbl_det th,.tbl_det td {
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
					.img-container{display:block;}
					.img-box{float:left;width:47%;padding:10px;}
					.img-box img{display:inline-block;}
				</style>
			</head>
			<body>
		';

		$html .= '<table class="t_header" width="100%">';
		$html .= '<tr><td>
				 <img src="'. ROOTPATH .'/public/media/img/LOGO_CONTINDO.png" style="height:60px;">
				 </td>
				 <td width="20%" class="t-right">ESTIMATE OF REPAIRS<br>
				 <b>ACCOUNT : '.strtoupper($type).'</b></td></tr>';

		$html .= '<tr><td colspan="2">JL. BY PASS KM 8, PADANG SUMATERA BARAT
				 +62 751 61600 &nbsp; +62 751 61097</td></tr>';
		$html .= '</table><h2>&nbsp;</h2>';

		$html .= '
		<table width="100%" style="padding:0;margin:0;border-spacing: 0;border-collapse: collapse;">
		<tr>
		<td style="vertical-align:bottom;">
			<table class="tbl_det">
			<tr><td width="130">EOR/EIR NUMBER</td><td width="130">'.$header['cpieir'].'</td></tr>			
			<tr><td>ESTIMATE DATE</td><td>'.date('d-m-Y',strtotime($header['rptglest'])).' '.date('H:i:s',strtotime($header['rptglest'])).'</td></tr>			
			<tr><td>RETURN DATE</td><td></td></tr>			
			<tr><td>TERM/ORIGINAL PORT</td><td></td></tr>			
			<tr><td>ORIGINAL PORT</td><td></td></tr>			
			<tr><td>TARA</td><td></td></tr>			
			</table>		
		</td>
		<td width="30%"></td>
		<td>
			<table class="tbl_det">
			<tr><td width="130">CONT. NUMBER </td><td width="130">'.$header['crno'].'</td></tr>			
			<tr><td>EQUIPMENT DESCRIP</td><td>'.$header['cclength'].' '.$header['ccheight'].' '.$header['ctcode'].'</td></tr>			
			<tr><td>INSPECTED AT</td><td></td></tr>			
			<tr><td>CONDITION</td><td>'.$header['svcond'].'</td></tr>			
			<tr><td>CUSTOMER</td><td>'.$header['cpopr'].'</td></tr>	
			<tr><td>CONT. OPERATOR</td><td>'.$header['cpopr'].'</td></tr>			
			<tr><td>EX VESSEL/VOY</td><td></td></tr>			
			<tr><td>ON HIRE DATE</td><td></td></tr>			
			<tr><td>MANUFACTURE DATE</td><td></td></tr>					
			</table>
		</td>
		</tr>
		</table>';		
		
		$html .= '<table class="tbl_det" width="100%">
		<tr>
		<th rowspan="2">No.</th>
		<th rowspan="2">LOCATION</th>
		<th rowspan="2">DESCRIPTION OF WORK</th>
		<th colspan="2">LABOUR</th>
		<th rowspan="2">MATERIAL COST</th>
		<th rowspan="2">TOTAL</th>
		<th rowspan="2">A/C</th>
		</tr>
		<tr>
		<th>HRS</th>
		<th>COST</th>
		</tr>';

		$no=1;
		$tot_rdmhr_o = 0;
		$tot_rdmhr_u = 0;
		$tot_rdlab_o = 0;
		$tot_rdlab_u = 0;	
		$tot_rdmat_o = 0;
		$tot_rdmat_u = 0;
		$tot_rdtotal_o = 0;
		$tot_rdtotal_u = 0;					
		switch($type) {
			case "owner" :
				foreach($detail as $det) {
					if($det['rdaccount']=="owner") {
						$account = "O";
						$tot_rdmhr_o = $tot_rdmhr_o+(double)$det['rdmhr'];
						$tot_rdlab_o = $tot_rdlab_o+(double)$det['rdlab'];	
						$tot_rdmat_o = $tot_rdmat_o+(int)$det['rdmat'];
						$tot_rdtotal_o = $tot_rdtotal_o+(int)$det['rdtotal'];
						
						$html .= '<tr>
						<td>'.$no.'</td>
						<td>'.$det['lccode'].'</td>
						<td>'.$det['rddesc'].'</td>
						<td class="t-right">'.$det['rdmhr'].'</td>
						<td class="t-right">'.number_format($det['rdlab'],0).'</td>
						<td class="t-right">'.number_format($det['rdmat'],0).'</td>
						<td class="t-right">'.number_format($det['rdtotal'],0).'</td>
						<td class="t-center">'.$account.'</td>
						</tr>';
						$no++;
					}
				}			
			break;
			
			case "user" :
				foreach($detail as $det) {
					if($det['rdaccount']=="user") {
						$account = "U";
						$tot_rdmhr_u = $tot_rdmhr_u+(double)$det['rdmhr'];
						$tot_rdlab_u = $tot_rdlab_u+(double)$det['rdlab'];	
						$tot_rdmat_u = $tot_rdmat_u+(int)$det['rdmat'];
						$tot_rdtotal_u = $tot_rdtotal_u+(int)$det['rdtotal'];					
						$html .= '<tr>
						<td>'.$no.'</td>
						<td>'.$det['lccode'].'</td>
						<td>'.$det['rddesc'].'</td>
						<td class="t-right">'.$det['rdmhr'].'</td>
						<td class="t-right">'.number_format($det['rdlab'],0).'</td>
						<td class="t-right">'.number_format($det['rdmat'],0).'</td>
						<td class="t-right">'.number_format($det['rdtotal'],0).'</td>
						<td class="t-center">'.$account.'</td>
						</tr>';
						$no++;
					}

				}			
			break;

			default :
				foreach($detail as $det) {
					if($det['rdaccount']=="user") {
						$account = "U";
						$tot_rdmhr_u = $tot_rdmhr_u+(double)$det['rdmhr'];
						$tot_rdlab_u = $tot_rdlab_u+(double)$det['rdlab'];	
						$tot_rdmat_u = $tot_rdmat_u+(int)$det['rdmat'];
						$tot_rdtotal_u = $tot_rdtotal_u+(int)$det['rdtotal'];					
					} else if($det['rdaccount']=="owner") {
						$account = "O";
						$tot_rdmhr_o = $tot_rdmhr_o+(double)$det['rdmhr'];
						$tot_rdlab_o = $tot_rdlab_o+(double)$det['rdlab'];	
						$tot_rdmat_o = $tot_rdmat_o+(int)$det['rdmat'];
						$tot_rdtotal_o = $tot_rdtotal_o+(int)$det['rdtotal'];
					} else {
						$account = "i";
					}

					$html .= '<tr>
					<td>'.$no.'</td>
					<td>'.$det['lccode'].'</td>
					<td>'.$det['rddesc'].'</td>
					<td class="t-right">'.$det['rdmhr'].'</td>
					<td class="t-right">'.number_format($det['rdlab'],0).'</td>
					<td class="t-right">'.number_format($det['rdmat'],0).'</td>
					<td class="t-right">'.number_format($det['rdtotal'],0).'</td>
					<td class="t-center">'.$account.'</td>
					</tr>';
					$no++;
				}
		}

		$html .='
		<tr>
		<td></td><td></td>
		<td class="t-right">Total Owner Account(IDR)</td>
		<td class="t-right">'.$tot_rdmhr_o.'</td><td class="t-right">'.$tot_rdlab_o.'</td><td class="t-right">'.$tot_rdmat_o.'</td><td class="t-right">'.$tot_rdtotal_o.'</td><td></td>
		</tr>
		<tr>
		<td></td><td></td>
		<td class="t-right">Total Owner Account(IDR)</td>
		<td class="t-right">'.$tot_rdmhr_u.'</td><td class="t-right">'.number_format($tot_rdlab_u,0).'</td><td class="t-right">'.number_format($tot_rdmat_u,0).'</td><td class="t-right">'.number_format($tot_rdtotal_u,0).'</td><td></td>
		</tr>';

		$html .='</table>';	

		$html .= '
		<table style="border-spacing: 0; border-collapse: collapse;width:100%; margin-top:40px;">	
			<tr>
				<td width="33%" class="t-center">For Receiving Party</td>
				<td width="33%" class="t-center">For Delivering Party</td>
				<td width="33%" class="t-center">Repair Approved By</td>
			</tr>
			
			<tr>
				<td height="60">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>	
			<tr>
				<td class="t-center">_______________________</td>
				<td class="t-center">_______________________</td>
				<td class="t-center">_______________________</td>
			</tr>	
		</table>
		<p>&nbsp;</p>
		';
		
		if($files != NULL) {
			$html .= '<p style="page-break-before: always;">&nbsp;</p>';
			$html .= '<h3>Files:</h3>';
			$html .= '<div class="img-container">';
			foreach($files as $row) {
			$html .= '<div class="img-box">';
			$html .= '<img src="'.$row['url'].'">';
			$html .= '</div>';
			}			
			$html .= '</div>';
		}

		$html .='
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		$mpdf->Output();
		// echo $html;
		die();	
	}		
	// DROPDOwN
	public function lccode_dropdown($selected = "")
	{

		$data = [];
		$response = $this->client->request('GET', 'locations/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => 0,
				'rows' => 2000,
				'search' => "",
				'orderColumn' => "lccode",
				'orderType' => "ASC"

			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		$res = $result['data']['datas'];
		$option = "";
		$option .= '<select name="lccode" id="lccode" class="select-lccode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['lccode'] . "'" . ((isset($selected) && $selected == $r['lccode']) ? ' selected' : '') . ">" . $r['lccode'] . "</option>";
		}
		$option .= "</select>";
		return $option;
		die();
	}

	public function cmcode_dropdown($selected = "")
	{
		$data = [];
		$response = $this->client->request('GET', 'components/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => 0,
				'rows' => 2000

			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		$option = "";
		// return $option;
		// die();
		$res = $result['data']['datas'];
		$option .= '<select name="cmcode" id="cmcode" class="select-cmcode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['cmcode'] . "'" . ((isset($selected) && $selected == $r['cmcode']) ? ' selected' : '') . ">" . $r['cmcode'] . "</option>";
		}
		$option .= "</select>";

		return $option;
		die();
	}

	public function dycode_dropdown($selected = "")
	{

		$data = [];
		$response = $this->client->request('GET', 'damagetype/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => 0,
				'rows' => 2000,
				'search' => "",
				'orderColumn' => "dycode",
				'orderType' => "ASC"

			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		$res = $result['data']['datas'];
		$option = "";
		$option .= '<select name="dycode" id="dycode" class="select-dycode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['dycode'] . "'" . ((isset($selected) && $selected == $r['dycode']) ? ' selected' : '') . ">" . $r['dycode'] . "</option>";
		}
		$option .= "</select>";
		return $option;
		die();
	}

	public function rmcode_dropdown($selected = "")
	{

		$data = [];
		$response = $this->client->request('GET', 'repair_methods/getAllData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => 0,
				'limit' => 2000

			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		$res = $result['data']['datas'];
		$option = "";
		$option .= '<select name="rmcode" id="rmcode" class="select-rmcode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['rmcode'] . "'" . ((isset($selected) && $selected == $r['rmcode']) ? ' selected' : '') . ">" . $r['rmcode'] . "</option>";
		}
		$option .= "</select>";
		return $option;
		die();
	}	

	function emkl_dropdown($selected="")
	{
		$response = $this->client->request('GET','debiturs/listCutype',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'cutype' =>1,
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$debitur = $result['data']['datas'];
		$option = "";
		$option .= '<select name="rpbillon" id="rpbillon" class="select-debitur">';
		$option .= '<option value="">-select-</option>';
		foreach($debitur as $cu) {
			$option .= "<option value='".$cu['cucode'] ."'". ((isset($selected) && $selected==$cu['cucode']) ? ' selected' : '').">".$cu['cuname']."</option>";
		}
		$option .="</select>";
		return $option; 
		die();			
	}

	public function calculateTotalCost() 
	{
		// rdloc,  rdcom,  rddmtype,  rdrepmtd, rdsize,  rdcalmtd, rdqty,  muname, prcode ,cccodes
		$token = get_token_item();
		$prcode = $token['prcode'];		
		if($this->request->isAjax()) {
			$response = $this->client->request('GET', 'estimasi/listcalculated', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					"rdloc" => $_POST['rdloc'],  
					"rdcom" => $_POST['rdcom'],  
					"rddmtype" => $_POST['rddmtype'],  
					"rdrepmtd" => $_POST['rdrepmtd'], 
					"rdsize" => $_POST['rdsize'],  
					"rdcalmtd" => $_POST['rdcalmtd'], 
					"rdqty" => $_POST['rdqty'],  
					"muname" => $_POST['muname'], 
					"prcode" =>"",
					"cccodes" => "",
					// "crno" => $_POST['crno']
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);
			if(isset($result['data'][0])){
				$data['status'] = "success";
				$data['data'] = $result['data'][0];
				echo json_encode($data);die();
			}
			$data['status'] = "Failled";
			$data['data'] = "";
			echo json_encode($data);die();
		}
	}		
}