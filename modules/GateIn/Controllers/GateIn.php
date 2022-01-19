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
				'crlastact1'=>'WE',
				'crlastact2'=>'WS',
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
			$btn_list .='<a href="#" class="btn btn-xs btn-info btn-table print_eir" data-crno="'.$v['crno'].'">print</a>';	
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
		// echo var_dump($_POST);die();
		// last act jadi WS
		if($this->request->isAjax()) {
			$form_params = [
				"crno" => $_POST['crno'],
			    "cpdepo" => "000",
			    "spdepo" => "000",
			    "cpitgl" => $_POST['cpipratgl'],
			    "cpiefin" => "1",
			    "cpichrgbb" => "1",
			    "cpipaidbb" => (isset($_POST['cpipaidbb'])?$_POST['cpipaidbb']:'0'),
			    "cpieir" => (int)(isset($_POST['cpieir'])?$_POST['cpieir']:'0'),
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
			    "cpipaidbb" => $_POST['cpipaidbb'],
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

	public function print_eir($crno) 
	{
		check_exp_time();
		$mpdf = new \Mpdf\Mpdf();

		$data = [];
		$gateIn = $this->get_data_gatein2($crno);
		$params = [
		    "cpdepo" => $gateIn['cpdepo'],
		    "spdepo" => $gateIn['spdepo'],
		    "cpitgl" => $gateIn['cpitgl'],
		    "cpiefin" => $gateIn['cpirefin'],
		    "cpichrgbb" => $gateIn['cpichrgbb'],
		    "cpipaidbb" => $gateIn['cpipaidbb'],
		    "cpieir" => $gateIn['cpieir'],
		    "cpinopol" => $gateIn['cpinopol'],
		    "cpidriver" => $gateIn['cpidriver'],
		    "cpicargo" => $gateIn['cpicargo'],
		    "cpiseal" => $gateIn['cpiseal'],
		    "cpiremark" => $gateIn['cpiremark'],
		    "cpiremark1" => $gateIn['cpiremark1'],
		    "cpidpp" => $gateIn['cpidpp'],
		    "cpireceptno" => $gateIn['cpireceptno'],
		    "cpideliver" => $gateIn['cpideliver'],
		    "cpitruck" => $gateIn['cpitruck'],
		    "cpiorderno" => $gateIn['cpiorderno']
		];

		// dd($gateIn);

		// $response = $this->client->request('GET','containerProcess/printEIRIns',[
		// 	'headers' => [
		// 		'Accept' => 'application/json',
		// 		'Authorization' => session()->get('login_token')
		// 	],
		// 	'json'=>$params
		// ]);

		// $result = json_decode($response->getBody()->getContents(),true);	
		// $header = $result['data']['datas'][0];
		$header = $gateIn;

		// $pratgl = $header['cpipratgl'];
		// $recept = recept_by_praid($header['praid']);

		// if($recept==""){
		// 	$invoice_number ="-";	
		// } else {
		// 	$invoice_number = "INV." . date("Ymd",strtotime($pratgl)) . ".000000" . $recept['prareceptid'];
		// }
				
		// $detail = $header['orderPraContainers'];
		// $depo = $this->get_depo($header['cpdepo']);
		// if(isset($result['status']) && ($result['status']=="Failled"))
		// {
		// 	$data['status'] = "Failled";
		// 	$data['message'] = $result['message'];
		// 	echo json_encode($data);die();				
		// }
		

		$html = '';

		$html .= '
		<html>
			<head>
				<title>Tanda Terima Container</title>

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
				<td><h4>Tanda Terima Container</h4></td>
				<td class="t-left"><b>PT. CONTINDO RAYA</b></td>
				<td class="t-right" width="5%"><p style="border:1px solid #000000; padding:5px;"><b>'.$header['cpieir'].'</b></p></td>
				</tr>
				</table>
			</div>
		';

		$html .='<div>
			<table width="100%">
			<tbody>
				<tr><td></td><td class="t-right"><img src="'.base_url().'/public/media/repoin/container_map.jpg" alt=""></td>
				</tr>
			</tbody>
			</table>
		</div>';

		$html .='<div>
		Kepada Yth :
		<h4></h4>
		</div>';
		$html .='
			<p>Dengan ini kami beritahukan bahwa:</p>
			<table class="tbl_head_prain" width="100%">
				<tbody>
					<tr>
						<td class="t-right" width="180">Nomor Container</td>
						<td width="200">&nbsp;:&nbsp;<b>'.$header['crno'].'</b> </td>
						<td class="t-right" width="120">Type</td>
						<td>&nbsp;:&nbsp;</td>
					</tr>
					<tr>
						<td class="t-right">Size</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cclength'].'/'.$header['ccheight'].' </td>
						<td class="t-right">Load Status</td>
						<td class="t-left">&nbsp;:&nbsp;</td>
					</tr>
					<tr>
						<td class="t-right">Principal</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpopr'].'  </td>
						<td class="t-right">Ex. Vessel</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['vesid'].'</td>
					</tr>	
					<tr>
						<td class="t-right">No. Polisi</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpinopol'].'  </td>
						<td class="t-right">Trucker</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['cpitruck'].'</td>
					</tr>		
					<tr>
						<td class="t-right">Condition</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header['crlastcond'].'  </td>
						<td class="t-right">Cleaning</td>
						<td class="t-left">&nbsp;:&nbsp;</td>
					</tr>			
					<tr>
						<td class="t-right">Telah kami terima dan survey</td>
						<td class="t-left">&nbsp;:&nbsp;Y/T</td>
						<td class="t-right">&nbsp;</td>
						<td class="t-left">&nbsp;</td>
					</tr>														
				</tbody>
			</table>
		';

		$html .='
			<p class="t-right">Padang, '.date('d/m/Y').'&nbsp;&nbsp;'.date('H:i:s').'</p>
			<table class="" width="100%">
				<tbody>
					<tr>
						<td class="t-center" width="33%">Trucker</td>
						<td class="t-center" width="33%">Surveyor</td>
						<td class="t-center" width="33%">Petugas Gate In</td>
					</tr>
					<tr>
						<td class="t-center" width="33%" style="padding-top:60px;">(________________)</td>
						<td class="t-center" width="33%" style="padding-top:60px;">(________________)</td>
						<td class="t-center" width="33%" style="padding-top:60px;">(________________)</td>
					</tr>					
				</tbody>
			</table>
		';

		$html .='
		</body>
		</html>
		';
		// $mpdf->WriteHTML($html);
		// $mpdf->Output();
		echo $html;
		die();		
	}
		
}