<?php
namespace Modules\GateOut\Controllers;

use App\Libraries\Ciqrcode;

use function bin2hex;
use function file_exists;
use function mkdir;

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
		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
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
					'limit'	=> $limit,
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
            $record[] = $v['CPOTGL'];
            $record[] = $v['CPOPR1'];
            $record[] = $v['CPOORDERNO'];
			$record[] = $v['CPOEIR'];
									
			$btn_list .='<a href="#" id="editPraIn" class="btn btn-xs btn-success btn-table edit" data-crno="'.$v['CRNO'].'">edit</a>';
			$btn_list .='<a href="#" class="btn btn-xs btn-info btn-table print" data-orderno="'.$v['CPOORDERNO'].'" data-cpid="'.$v['CPID'].'">print</a>';				
            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);
		
	}

	public function add()
	{
		check_exp_time();
		$data = [];
		if($this->request->isAjax()) {
			// echo var_dump($_POST);die();
			$form_params = [
			// "cpotgl"		=> $_POST['cpotgl'],
			// "cpopr"			=> $_POST['cpopr'],
			"cpopr1"		=> $_POST['cpopr1'],
			// "cpcust"		=> $_POST['cpcust'],
			"cpcust1"		=> $_POST['cpcust1'],
			// "cpotruck"		=> $_POST['cpotruck'],
			"cporeceptno"	=> $_POST['cporeceptno'],
			"svsurdat"		=> date('Y-m-d', strtotime($_POST['svsurdat'])),
			"syid"			=> $_POST['syid'],
			"cpoorderno"	=> $_POST['cpoorderno'],
			"cpoeir"		=> $_POST['cpoeir'],
			"cporefout"		=> $_POST['cporefout'],
			"cpopratgl"		=> date('Y-m-d', strtotime($_POST['cpopratgl'])),
			"cpochrgbm"		=> isset($_POST['cpochrgbm'])?$_POST['cpochrgbm']:0,
			"cpopaidbm"		=> isset($_POST['cpopaidbm'])?$_POST['cpopaidbm']:0,
			"cpofe"			=> $_POST['cpofe'],
			"cpoterm"		=> $_POST['cpoterm'],
			"cpoload"		=> $_POST['cpoload'],
			// "cpoloaddat"	=> $_POST['cpoloaddat'],
			"cpojam"		=> $_POST['cpojam'],
			"cpocargo"		=> $_POST['cpocargo'],
			"cposeal"		=> $_POST['cposeal'],
			// "cpovoy"		=> $_POST['cpovoy'],
			"cpoves"		=> $_POST['vesid'],
			"cporeceiv"		=> $_POST['cporeceiv'],
			// "cpodpp"		=> $_POST['cpodpp'],
			"cpodriver"		=> $_POST['cpodriver'],
			"cponopol"		=> $_POST['cponopol'],
			// "cporemark"		=> $_POST['cporemark'],
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
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}			
			
			$data['status'] = "success";
			$data['message'] = $result['message'];
			echo json_encode($data);die();				
		}

		$data['act'] = "Add";
		$data['page_title'] = "Gate Out";
		$data['page_subtitle'] = "Gate Out Page";		
		$data['surveyor'] = $this->surveyor_dropdown();		
		return view('Modules\GateOut\Views\add',$data);
	}	

	public function edit($crno)
	{
		$data = [];
		$form_params = [];
		$data['act'] = "Edit";
		$data['page_title'] = "Gate Out";
		$data['page_subtitle'] = "Gate Out Page";

		if($this->request->isAjax()) {

		}	

		$responses = $this->client->request('GET','gateout/getByCrno',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				"crno" => $crno
			]
		]);
		
		$result = json_decode($responses->getBody()->getContents(), true);
		// echo var_dump($result);die();
		if($result['data']==null) {
			$data['data'] = "";
		}
		$data['data'] = $result['data'][0];
		$data['surveyor'] = $this->surveyor_dropdown($data['data']['syid']);
		return view('Modules\GateOut\Views\edit',$data);
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
				
				if($container['lastact'] == "HC") {
					$data['status'] = "Failled";
					$data['message'] = "Invalid Container";
					echo json_encode($data);die();
				}

				if ((($container['crlastact'] == "CO") && ($container['crlastcond'] == "AC")) || ($container['lastact'] == "AC")) {

					// periksa Query getByCrno di backend
					// getKitirRepoGateOut tdk bisa dipakai karne pakai 2 param (crno & cpoorderno) 
					$responses = $this->client->request('GET','gateout/getByCrno',[
						'headers' => [
							'Accept' => 'application/json',
							'Authorization' => session()->get('login_token')
						],
						'query' => [
							"crno" => $_POST['crno']
						]
					]);
					
					$res_gateout = json_decode($responses->getBody()->getContents(), true);
					// echo var_dump($res_gateout);die();	
					if(isset($res_gateout['status']) && ($res_gateout['status']=="Failled"))
					{
						$data['status'] = "Failled";
						$data['message'] = $res_gateout['message'];
						echo json_encode($data);die();						
					}

					if(isset($res_gateout['data']) &&($res_gateout['data']==null)) {
						$data['status'] = "Failled";
						$data['message'] = "Data Container tidak ditemukan.";
						echo json_encode($data);die();					
					}	

					$data['message'] = 'success';
					$data['data'] = $res_gateout['data'];
					echo json_encode($data);die();					    		

		    	} else {

					$data['status'] = "Failled";
					$data['message'] = "Invalid. Status Container(".$container['crlastact'].")";	    		
					echo json_encode($data);die();					    		
		    	}	
			}		
		}
	}

	public function surveyor_dropdown($selected="") 
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

	public function get_data_print($cpoorderno,$cpid)
	{
		$responses = $this->client->request('GET','containerProcess/getKitirRepoGateOutByCpid',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				"cpoorderno" => $cpoorderno,
				"cpid" => $cpid
			]
		]);
		
		$result = json_decode($responses->getBody()->getContents(), true);
		if(isset($result['status'])&&($result['status']=="Failled")) {
			$data="";
		} else {
			$data = $result['data'][0]; 
		}
		// echo var_dump($data);die();
		return $data;
	}

	public function print_eir_out($cpoorderno, $cpid)
	{
		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 236]]);
		$header = $this->get_data_print($cpoorderno, $cpid);
		// echo var_dump($header);
		$CPIEIR  = str_repeat("0", 8 - strlen($header['cpoeir'])) . $header['cpoeir'];
		$QR_FORMAT = $header['crno'] . $CPIEIR;
		$QRCODE = $this->generate_qrcode($QR_FORMAT);
		// print_r($header);die();
		$QRCODE_IMG = ROOTPATH . '/public/media/qrcode/' . $QRCODE['content'] . '.png';

		$html = '';

		$html .= '
		<html>
			<head>
				<title>Gate In | Print EIR-OUT</title>
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
		$html .= '<body>
			<div class="wrapper">

			<div class="page-header t-center">
				<h5 style="line-height:1.2;font-weight:bold;padding-top:20px;">TANDA TERIMA<br>CONTAINER</h5>
				<img src="' . $QRCODE_IMG . '" style="height:120px;">
				<h5 style="text-decoration: underline;line-height:0.5;">EIR OUT.' . $CPIEIR . '</h5>
			</div>
		';
		$html .= '
			<table border-spacing: 0; border-collapse: collapse; width="100%">	
					<tr>
						<td>PRINCIPAL</td>
						<td colspan="3">:&nbsp;' . $header['cpopr1'] . '</td>
					</tr>
					<tr>
						<td style="width:40%;">CONTAINER NO.</td>
						<td colspan="3"> <h5 style="margin:0;padding:0;font-weight:bold;">:&nbsp;' . $header['crno'] . '</h5></td>
					</tr>	

					<tr>
						<td style="width:40%;">DATE/TIME</td>
						<td colspan="3">:&nbsp;' . date('d-m-Y', strtotime($header['cpopratgl'])) . '/' . $header['cpojam'] . '</td>
					</tr>				

					<tr>
						<td>TYPE</td>
						<td colspan="3">:&nbsp;' . $header['ctcode'] . '/' . $header['cccode'] . '</td>
					</tr>					
					<tr>
						<td>SIZE</td>
						<td colspan="3">:&nbsp;' . $header['cclength'] . '/' . $header['ccheight'] . '</td>
					</tr>

					<tr>
						<td>TARA</td>
						<td colspan="3">:&nbsp;' . $header['crtarak'] . '/' . $header['crtaral'] . ' </td>
					</tr>

					<tr>
						<td>MAN.DATE </td>
						<td colspan="3">:&nbsp;' . $header['crmandat'] . ' </td>
					</tr>
					<tr>
						<td>CONDITION</td>
						<td colspan="3">:&nbsp;' . $header['crlastcond'] . '</td>
					</tr>
					<tr>
						<td style="width:40%;">SURVEY DATE</td>
						<td colspan="3">:&nbsp;' . date('d-m-Y', strtotime($header['svsurdat'])) . '</td>
					</tr>	

					<tr>
						<td>RECEIVER</td>
						<td colspan="3">:&nbsp;' . $header['cporeceiv'] . '</td>
					</tr>

					<tr>
						<td style="width:40%;">SEAL NO.</td>
						<td colspan="3">:&nbsp;' . $header['cposeal'] . '</td>
					</tr>					
					<tr>
						<td>VESSEL</td>
						<td colspan="3">:&nbsp;' . $header['cpoves'] . '/' . $header['cpovoyid'] . '</td>
					</tr>
					<tr>
						<td>LOAD STATUS</td>
						<td colspan="3">:&nbsp;</td>
					</tr>
					

					<tr>
						<td>NO POLISI</td>
						<td colspan="3">:&nbsp;' . $header['cponopol'] . '</td>
					</tr>
					<tr>
						<td>DRIVER</td>
						<td colspan="3">:&nbsp;' . $header['cpodriver'] . '</td>
					</tr>				
					
					<tr>
						<td>REMARK</td>
						<td colspan="3">:&nbsp;' . $header['cporemark'] . '</td>
					</tr>
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
		die();
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
}