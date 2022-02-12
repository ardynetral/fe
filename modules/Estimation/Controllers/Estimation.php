<?php
namespace Modules\Estimation\Controllers;

use App\Libraries\MyPaging;

class Estimation extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	function list_data(){		
		$module = service('uri')->getSegment(1);
		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "")?$this->request->getPost('search'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        // $sort_dir = $this->get_sort_dir();		
		// PULL data from API
		$response = $this->client->request('GET','dataListReports/listAllEstimations',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'offset' => (int)$offset,
					'limit'	=> (int)$limit
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
			
			// $btn_list .= '<a href="#" id="" class="btn btn-xs btn-primary btn-tbl" data-praid="">view</a>';	
			if(has_privilege_check($module, '_update')==true):
				$btn_list .= '<a href="'.site_url('estimation/edit/').$v['RPCRNO'].'" id="editPraIn" class="btn btn-xs btn-success btn-tbl">edit</a>';
			endif;
			
			// if(has_privilege_check($module, '_printpdf')==true):
			// 	$btn_list .= '<a href="#" class="btn btn-xs btn-info btn-tbl" data-praid="">print</a>';
			//  endif;

			// if(has_privilege_check($module, '_delete')==true):
			// 	$btn_list .= '<a href="#" id="deletePraIn" class="btn btn-xs btn-danger btn-tbl">delete</a>';
			// endif;

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
		// $prcode = $token['prcode'];

		$data = [];
		

		$data['page_title'] = "Estimation";
		$data['page_subtitle'] = "Estimation Page";
		return view('Modules\Estimation\Views\index',$data);
	}

	public function add()
	{
		check_exp_time();
		$data = [];
		$data['data'] = "";
		$form_params = [];
		if($this->request->isAjax()) {
			// echo var_dump($this->request->getPost());die();
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
			
			// echo var_dump($result);die();
			
			if(isset($result['status']) && $result['status']=="Failled") {
				$data['status'] = "Failled";	
				$data['message'] = "Gagal menyimpan data" . $result['status'];
				echo json_encode($data);
				die();	
			}	
			$data['status'] = "success";	
			$data['message'] = "Berhasil menyimpan data";
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
		return view('Modules\Estimation\Views\add',$data);
	}

	// public function add_detail()
	// {
	// 	$data = [];
	// 	$data['data'] = "";
	// 	$data['act'] = "Add Detail";
	// 	$data['page_title'] = "Estimation";
	// 	$data['page_subtitle'] = "Estimation Page";
	// 	return view('Modules\Estimation\Views\add_detail',$data);
	// }	
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
			$data['data'] = $this->fill_table_detail($data_detail);
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
		return view('Modules\Estimation\Views\add',$data);
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
		$data['page_title'] = "Estimation";
		$data['page_subtitle'] = "Estimation Page";
		$data['lccode_dropdown'] = $this->lccode_dropdown();
		$data['cmcode_dropdown'] = $this->cmcode_dropdown();
		$data['dycode_dropdown'] = $this->dycode_dropdown();
		$data['rmcode_dropdown'] = $this->rmcode_dropdown();
		return view('Modules\Estimation\Views\edit',$data);
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
				$html .= '<td>
						<a href="#" class="btn btn-primary btn-xs view">view</a>
						<a href="#" class="btn btn-danger btn-xs delete" data-svid="'.$row['svid'].'" data-rpid="'.$row['rpid'].'" data-rdno="'.$row['rdno'].'">delete</a></td>';
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
				$html .= '<td>
						<a href="#" class="btn btn-primary btn-xs edit">view</a>
						<a href="#" class="btn btn-danger btn-xs delete" data-svid="'.$row['svid'].'" data-rpid="'.$row['rpid'].'" data-rdno="'.$row['rdno'].'">delete</a></td>';
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
			echo json_encode($data);
			die();
		}
	}

	public function print($crno) 
	{
		$data = $this->getOneEstimasi($crno);
		$header = $data['dataOne'][0];
		$detail = $data['dataTwo'];
		$html = '';
		$html .= '
		<html>
			<head>
				<title>Order PraIn</title>

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
			<body>
		';

		$html .= '<table class="t_header" width="100%">';
		$html .= '<tr><td>
				 <img src="" style="height:100px;">
				 </td>
				 <td width="20%" class="t-right">ESTIMATE OF REPAIRS</td></tr>';

		$html .= '<tr><td colspan="2"></td></tr>';
		$html .= '</table>';

		$html .= '<table class="t_body">';
		$html .= '</table>';		

		$html .= '<table class="t_footer">';
		$html .= '</table>';		

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
}

