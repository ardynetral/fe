<?php
namespace Modules\RepairInProgress\Controllers;

use App\Libraries\MyPaging;

class RepairInProgress extends \CodeIgniter\Controller
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

		$response = $this->client->request('GET','containerRepair/listMnr',[
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
            $record[] = $v['RPCRNO'];
            $record[] = $v['PRCODE'];
            $record[] = $v['CPITGL'];
            $record[] = $v['SVSURDAT'];
            $record[] = $v['SVCOND'];
            $record[] = $v['RPNOEST'];
            $record[] = $v['RPVER'];
			
			if(has_privilege_check($module, '_update')==true):
				// $btn_list .= '<a href="#" class="btn btn-xs btn-success btn-tbl">edit</a>';
				$btn_list .= '<a href="'.site_url('rip/edit/').$v['RPCRNO'].'" id="editPraIn" class="btn btn-xs btn-success btn-tbl">edit</a>';
			endif;
			
			$btn_list .= '
				<div class="btn-group">
					<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Print <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#" class="print" data-crno="'.$v['RPCRNO'].'" data-type="all">All</a></li>
						<li><a href="#" class="print" data-crno="'.$v['RPCRNO'].'" data-type="owner">Owner</a></li>
						<li><a href="#" class="print" data-crno="'.$v['RPCRNO'].'" data-type="user">User</a></li>
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

		$data['page_title'] = "Repair In Progress";
		$data['page_subtitle'] = "Repair In Progress Page";
		return view('Modules\RepairInProgress\Views\index',$data);
	}

	public function add()
	{
		check_exp_time();
		$data = [];
		$data['data'] = "";
		$form_params = [];

		if($this->request->isAjax()) {
			$form_params= [
			"svid" => $this->request->getPost('svid'),
			"rpver" => ($this->request->getPost('rpver')!=''?$this->request->getPost('rpver'):1),
			"rptglest" => date('Y-m-d',strtotime($this->request->getPost('rptglest'))),
			"rpnoest" => ($this->request->getPost('rpnoest')!=''?$this->request->getPost('rpnoest'):1),
			"rpcrno" => $this->request->getPost('rpcrno'),
			"rpcrton" => date('Y-m-d',strtotime($this->request->getPost('rpcrton'))),
			"rpcrtby" => $this->request->getPost('rpcrtby'),
			"syid" => $this->request->getPost('syid')
			];

			$response = $this->client->request('POST', 'estimasi/createHeader', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $form_params
			]);	

			$result = json_decode($response->getBody()->getContents(), true);	
			
			if(isset($result['status']) && $result['status']=="Failled") {
				$data['status'] = "Failled";	
				$data['message'] = "Gagal menyimpan data";
				echo json_encode($data);
				die();	
			}	
			$data['status'] = "success";	
			$data['message'] = "Berhasil menyimpan data";
			echo json_encode($data);
			die();
		}	

		$data['act'] = "Add";
		$data['page_title'] = "Repair In Progress";
		$data['page_subtitle'] = "Repair In Progress Page";
		return view('Modules\RepairInProgress\Views\add',$data);
	}	

	public function save_detail()
	{
		check_exp_time();
		$data = [];
		$data['data'] = "";
		$form_params = [];
		if($this->request->isAjax()) {

			/*
			estimasi/insertfilemnr
			param svid, rpid, flag, file --> POST
			*/

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
				"name" => "flag",
				"contents" => "2"
			];

			if($_FILES["files"] !="") {
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

			$response = $this->client->request('POST', 'estimasi/insertfilemnr', [
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
		$data['page_title'] = "Repair In Progress";
		$data['page_subtitle'] = "Repair In Progress Page";
		$data['lccode_dropdown'] = $this->lccode_dropdown();
		$data['cmcode_dropdown'] = $this->cmcode_dropdown();
		$data['dycode_dropdown'] = $this->dycode_dropdown();
		$data['rmcode_dropdown'] = $this->rmcode_dropdown();
		return view('Modules\RepairInProgress\Views\add',$data);
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
			
		// $data['lccode_dropdown'] = $this->lccode_dropdown();
		// $data['cmcode_dropdown'] = $this->cmcode_dropdown();
		// $data['dycode_dropdown'] = $this->dycode_dropdown();
		// $data['rmcode_dropdown'] = $this->rmcode_dropdown();

		$data['lccode_dropdown'] = "";
		$data['cmcode_dropdown'] = "";
		$data['dycode_dropdown'] = "";
		$data['rmcode_dropdown'] = "";		
		$data['act'] = "Add";
		$data['page_title'] = "Repair In Progress";
		$data['page_subtitle'] = "Repair In Progress Page";
		return view('Modules\RepairInProgress\Views\edit',$data);
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
				"name" => "flag",
				"contents" => "2"
			];
			// echo var_dump($_FILES);die();
			if($_FILES["files"]['name'][0] =="") {

				$form_params[] = [
					'name' => 'file',
					'contents'	=> fopen($_FILES["files"]['tmp_name'][$key],'r'),
					'filename'	=> $_FILES["files"]['name'][$key],
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

			$response = $this->client->request('POST', 'estimasi/insertfilemnr', [
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
		$response = $this->client->request('GET', 'containerRepair/listbycrno', [
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

	public function getDataEstimasi() 
	{
		$CRNO = $this->request->getPost('crno');
		if($this->request->isAjax()) {
			$response = $this->client->request('GET', 'containerRepair/listbycrno', [
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
			$data['data_estimasi'] = $this->checkHeaderEstimasi($CRNO);
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
	}
	public function getFileList($svid,$rpid) 
	{
		if($this->request->isAjax()) {
			$response = $this->client->request('GET', 'containerRepair/getFile', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'svid' => $svid,
					'rpid' => $rpid,
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
			$no=1;
			foreach($data as $row) {
			$html .= '<a href="'.$row['url'].'" class="" target="_blank">
				<img src="'.$row['url'].'" style="width: 50%;float: left;padding: 5px;">
			</a>&nbsp;';
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
					<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#myModal"><i class="fa fa-picture-o"></i>&nbsp;Add picture</a>
				</td>';				

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
						<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#myModal"><i class="fa fa-picture-o"></i>&nbsp;Add picture</a></td>';
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
		$response = $this->client->request('GET', 'containerRepair/getFileDetail', [
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

	public function checkHeaderEstimasi($CRNO)
	{
		$response = $this->client->request('GET', 'estimasi/checkValidasiHeader', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'rpcrno' => $CRNO
			]
		]);	
		$result = json_decode($response->getBody()->getContents(), true);
		if($result['data']==null) {
			$data = "";
		}
		$data = $result['data'];
		return $data;			
	}

	public function finish_repair() 
	{
		$CRNO = $_POST['crno'];
		$SVID = $_POST['svid'];
		$response = $this->client->request('POST', 'containerRepair/finisRepair', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'crno' => $CRNO,
				'svid' => $SVID
			]
		]);	
		$result = json_decode($response->getBody()->getContents(), true);

		if(isset($result['status']) && $result['status']=="Failled") {
			$data['status'] = "Failled";
			$data['message'] = "Finish Repair Gagal..";
			echo json_encode($data);die();
		}		

		$data['status'] = "success";
		$data['message'] = "Finish Repair Berhasil..";	
		echo json_encode($data);die();
	}

	public function finish_cleaning() 
	{
		$CRNO = $_POST['crno'];
		$SVID = $_POST['svid'];
		$response = $this->client->request('POST', 'containerRepair/finisCleaning', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'crno' => $CRNO,
				'svid' => $SVID
			]
		]);	
		$result = json_decode($response->getBody()->getContents(), true);

		if(isset($result['status']) && $result['status']=="Failled") {
			$data['status'] = "Failled";
			$data['message'] = "Finish Cleaning Gagal..";
			echo json_encode($data);die();
		}		

		$data['status'] = "success";
		$data['message'] = "Finish Cleaning Berhasil..";	
		echo json_encode($data);die();
	}

	public function print($crno,$type) 
	{
		$mpdf = new \Mpdf\Mpdf();

		$data = $this->getOneEstimasi($crno);
		$header = $data['dataOne'][0];
		$detail = $data['dataTwo'];
		$files = $this->getFileToPrint($crno);
		$ESTIMATE_DATE = ($header['rptglest']==null) ? "" : date('d-m-Y',strtotime($header['rptglest'])).' '.date('H:i:s',strtotime($header['rptglest']));
		// dd($header);
		$html = '';
		$html .= '
		<html>
			<head>
				<title>Estimation</title>

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
				 <b>ACCOUNT : '.strtoupper($type).'</b>
				 </td></tr>';

		$html .= '<tr><td colspan="2">JL. BY PASS KM 8, PADANG SUMATERA BARAT
				 +62 751 61600 &nbsp; +62 751 61097</td></tr>';
		$html .= '</table><h2>&nbsp;</h2>';

		$html .= '
		<table width="100%" style="padding:0;margin:0;border-spacing: 0;border-collapse: collapse;">
		<tr>
		<td style="vertical-align:bottom;">
			<table class="tbl_det">
			<tr><td width="130">EOR/EIR NUMBER</td><td width="130">'.$header['rpnoest'].'</td></tr>			
			<tr><td>ESTIMATE DATE</td><td>'.$ESTIMATE_DATE.'</td></tr>			
			<tr><td>RETURN DATE</td><td>'.$header['cpitgl'].'</td></tr>			
			<tr><td>TERM/ORIGINAL PORT</td><td></td></tr>			
			<tr><td>ORIGINAL PORT</td><td></td></tr>			
			<tr><td>TARA</td><td>' . $header['crtarak'] . '</td></tr>			
			</table>		
		</td>
		<td width="30%"></td>
		<td>
			<table class="tbl_det">
			<tr><td width="130">CONT. NUMBER </td><td width="130">'.$header['crno'].'</td></tr>			
			<tr><td>EQUIPMENT DESCRIP</td><td>'.$header['cclength'].' '.$header['ccheight'].' '.$header['ctcode'].'</td></tr>			
			<tr><td>INSPECTED AT</td><td>CONTINDO RAYA</td></tr>			
			<tr><td>CONDITION</td><td>'.$header['svcond'].'</td></tr>			
			<tr><td>CUSTOMER</td><td>'.$header['cpopr'].'</td></tr>	
			<tr><td>CONT. OPERATOR</td><td>'.$header['cpopr'].'</td></tr>			
			<tr><td>EX VESSEL/VOY</td><td>' . $header['cpives'] . "/" . $header['cpivoyid'] . '</td></tr>			
			<tr><td>ON HIRE DATE</td><td></td></tr>			
			<tr><td>MANUFACTURE DATE</td><td>' . $header['crmandat'] . '</td></tr>					
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
				$html .='
				<tr>
				<td></td><td></td>
				<td class="t-right">Total Owner Account(IDR)</td>
				<td class="t-right">'.number_format($tot_rdmhr_o,0).'</td><td class="t-right">'.number_format($tot_rdlab_o,0).'</td><td class="t-right">'.number_format($tot_rdmat_o,0).'</td><td class="t-right">'.number_format($tot_rdtotal_o,0).'</td><td></td>
				</tr>';				
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
				$html .='
				<tr>
				<td></td><td></td>
				<td class="t-right">Total User Account(IDR)</td>
				<td class="t-right">'.$tot_rdmhr_u.'</td><td class="t-right">'.number_format($tot_rdlab_u,0).'</td><td class="t-right">'.number_format($tot_rdmat_u,0).'</td><td class="t-right">'.number_format($tot_rdtotal_u,0).'</td><td></td>
				</tr>';						
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
				$html .='
				<tr>
				<td></td><td></td>
				<td class="t-right">Total Owner Account(IDR)</td>
				<td class="t-right"></td><td class="t-right"></td><td class="t-right"></td><td class="t-right">'.number_format($tot_rdtotal_o,0).'</td><td></td>
				</tr>
				<tr>
				<td></td><td></td>
				<td class="t-right">Total User Account(IDR)</td>
				<td class="t-right"></td><td class="t-right"></td><td class="t-right"></td><td class="t-right">'.number_format($tot_rdtotal_u,0).'</td><td></td>
				</tr>';				
		}			

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
		</table>';
		
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
}