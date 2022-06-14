<?php
namespace Modules\ChangeContainer\Controllers;

use App\Libraries\Ciqrcode;

use function bin2hex;
use function file_exists;
use function mkdir;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ChangeContainer extends \CodeIgniter\Controller
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
		$prcode = $token['prcode'];

		$data['prcode'] = $prcode;
		$data['cucode'] = $prcode;
		$data['group_id'] = $group_id;
		return view('Modules\ChangeContainer\Views\index',$data);
	}

	public function list_data()
	{
		$token = get_token_item();
		$user_id = $token['id'];
		if($token['groupId']=='1') {
			$group_id = $token['groupId'];
		} else {
			$group_id = "";
		}
		$prcode = $token['prcode'];

		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        	
		$form_params = [

		];		
		$response = $this->client->request('GET','orderPras/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => $offset,
				'limit'	=> $limit,
				'search'	=> $search,
				'pracode' => 'PO',
				'userId'	=> $user_id,
				'groupId' => $group_id
			]
		]);
		$result = json_decode($response->getBody()->getContents(),true);
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => @$result['data']['count'],
            "recordsFiltered" => @$result['data']['count'],
            "data" => array()
        );
		$no = ($offset !=0)?$offset+1 :1;

		$data_pra = $result['data']['datas'];

		foreach ($data_pra as $k=>$v) {
			$btn_list="";
            $record = array(); 
			if($v['appv']==2):
				$btn_list .='<a href="'.site_url('changecontainer/final_order/'.$v['praid']).'" class="btn btn-xs btn-primary">View</a>';

			endif;
			
            $record[] = '<div>'.$btn_list.'</div>';
            $record[] = $no;
            $record[] = $v['cpiorderno'];
            $record[] = $v['cpipratgl'];
            $record[] = $v['cpives'];
            $record[] = $v['cpivoyid'];
			$record[] = $v['cpirefin'];

			if((check_bukti_bayar2($v['praid'])=="exist")) {
				$record[] = 'KW' . date("Ymd", strtotime($v['cpipratgl'])) . str_repeat("0", 8 - strlen($v['praid'])) . $v['praid'];
			} else {
				$record[] = '-';
			}

            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);		
	}

	public function get_one_container($id) 
	{
		$token = get_token_item();
		$prcode = $token['prcode'];

		if ($this->request->isAJAX())  {
			$response = $this->client->request('GET','orderPraContainers/getDetailData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'pracrnoid' => $id,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	

			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}	


			$data['message'] = 'success';
			$data['cr'] = $result['data'];
			$data['prcode'] = $prcode;
			$data['contract'] = $this->get_contract($result['data']['cpopr']);
			echo json_encode($data);die();	
		}	

	}
	public function change_container()
	{
		// $data = [];
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if ($this->request->isAJAX()) 
		{
			$validate = $this->validate([
	            'crno1' 	=> 'required',
	            'crno2' 	=> 'required',
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','containers/change',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'json' => [
						'orderno'=>$_POST['orderno'],
						'crno1'=>$_POST['crno1'],
						'crno2'=>$_POST['crno2']
					],
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
	}	
		
	// Page siap cetak kitir order 
	public function final_order($id)
	{
		check_exp_time();
		// $data = [];
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];
		$data['act'] = "approval2";
		$data['group_id'] = $group_id;

		// GET DETAIL ORDER
		$response = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => ['praid' => $id],
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		$dt_order  = $result['data']['datas']['0']; 
		$dt_company = $this->get_company();

		// bukti_bayar
		$recept = $dt_order['orderPraRecept'][0];
		$data['recept'] = $recept;		
		if(check_bukti_bayar($dt_order['praid'])==true) {
			$recept_files = $dt_order['orderPraRecept'][1];
			$response_bukti = $this->client->request('GET','orderPraRecepts/getDetailData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => [
					'prareceptid' => $recept_files['prareceptid']
				]
			]);			
			$result_bukti = json_decode($response_bukti->getBody()->getContents(), true);
			$bukti_bayar = $result_bukti['data'];
		} else {
			$bukti_bayar = "";
		}

		if ($this->request->isAJAX()) 
		{
			$cprocess_params = [];
			$containers = $dt_order['orderPraContainers'];
			// echo var_dump($containers);die();
			if($containers!="") {
				$no=0;
				foreach ($containers as $c) {
					$cprocess_params[$no] = [
						"CRNO" => $c['crno'],
					    "CPOPR" => $c['cpopr'],
					    "CPCUST" => $c['cpcust'],					    
					    "CPDEPO" => $dt_order['cpdepo'],
					    "SPDEPO" => $dt_company['sdcode'],
					    "CPIFE" => $c['cpife'],
					    // "CPICARGO" => $dt_order['cpicargo'],
					    "CPIPRATGL" => $dt_order['cpipratgl'],
					    "CPIREFIN" => $dt_order['cpirefin'],
					    "CPIVES" => $dt_order['cpives'],
					    "CPIDISH" => $dt_order['cpidish'],
					    "CPIDISDAT" => $dt_order['cpidisdat'],
					    "CPIJAM" => $dt_order['cpijam'],
					    "CPICHRGBB" => 1,
					    "CPIDELIVER" => $dt_order['cpideliver'],
					    "CPIORDERNO" => $dt_order['cpiorderno'],
					    "CPISHOLD" => $c['cpishold'],
					    "CPIREMARK" => $c['cpiremark'],
					    "CPIVOYID" => $dt_order['cpivoyid'],
					    "CPIVOY" => $dt_order['voyages']['voyno'],
					    "CPISTATUS" => 0,
					];	

					// get One Container
					$cr_req = $this->client->request('GET','containers/listOne',[
						'headers' => [
							'Accept' => 'application/json',
							'Authorization' => session()->get('login_token')
						],
						'form_params' => ['crNo' => $c['crno']],
					]);

					$cr_result = json_decode($cr_req->getBody()->getContents(), true);

					if(isset($cr_result['data'])) {
						$mtcode_cccode = [
							'MTCODE' => $cr_result['data']['mtcode'],
							'CCCODE' => $cr_result['data']['cccode']
						];
						array_push($cprocess_params[$no], $mtcode_cccode);
					}		

					// Container Process
					$cp_response = $this->client->request('POST','praIns/createNewData',[
						'headers' => [
							'Accept' => 'application/json',
							'Authorization' => session()->get('login_token')
						],
						'form_params' => $cprocess_params[$no]
					]);

					$result = json_decode($cp_response->getBody()->getContents(), true);	

					if(isset($result['status']) && ($result['status']=="Failled"))
					{
						$data['message'] = $result['message'];
						echo json_encode($data);die();				
					}
		
					$no++;
				} //endforeach

				// echo var_dump($result);die();

				// UPDATE ORDER PRA appv=2
				$op_response = $this->client->request('PUT','orderPras/updateData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'praid' => $id,
						'appv' => 2
					]
				]);

				$opresult = json_decode($op_response->getBody()->getContents(), true);
				if(isset($opresult['status']) && ($opresult['status']=="Failled"))
				{
					$data['message'] = $opresult['message'];
					echo json_encode($data);die();				
				}

				$data['message'] = "success";
				$data['msgdata'] = $opresult['data'];
				echo json_encode($data);die();
			}		
		}		

		$contract = $this->get_contract($dt_order['orderPraContainers'][0]['cpopr']);

		$pratgl = $dt_order['cpipratgl'];
		if($pratgl < "2022-04-01") {
			$tax = 10;
		} else {
			$tax = (isset($contract['cotax'])?$contract['cotax']:0);
		}
		
		$subtotal = 0;
		$pajak = $tax/100;
		$adm_tarif = (isset($contract['coadmv'])?$contract['coadmv']:0);
		$materai = $contract['comaterai'];
		$totalcharge = $subtotal + $pajak + $adm_tarif + $materai;

		$data['subtotal_charge'] = $subtotal;
		$data['pajak'] = $pajak;
		$data['adm_tarif'] = $adm_tarif;
		$data['materai'] = $materai;
		$data['totalcharge'] = $totalcharge;

		$data['data'] = $dt_order;
		$data['bukti_bayar'] = $bukti_bayar;
		$data['depo'] = $this->get_depo($data['data']['cpdepo']);
		$data['contract'] = $this->get_contract($data['data']['cpopr']);
		$data['hc20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc20'] : 0);
		$data['hc40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc40'] : 0);
		$data['hc45'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['hc45'] : 0);
		$data['std20'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std20'] : 0);
		$data['std40'] = ((isset($data['data']['orderPraContainers'])&&($data['data']['orderPraContainers']!=null)) ? $this->hitungHCSTD($data['data']['orderPraContainers'])['std40'] : 0);	
		return view('Modules\ChangeContainer\Views\cetak_kitir',$data);		
	}

	public function get_container_by_praid($praid) 
	{
		$response = $this->client->request('GET','orderPraContainers/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'praid' => $praid,
				'offset' => 0,
				'limit' => 500,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);			
		$i=1; 
		$html="";
		foreach($result['data']['datas'] as $row){
			$pracrid=$row['pracrnoid'];
			$html .= "<tr>
				<td><a href='#' id='editContainer' class='btn btn-xs btn-danger delete' data-crid='".$row['pracrnoid']."' data-act='add'>delete</a></td>
				<td>".$i."</td>
				<td>".$row['crno']."</td>
				<td>".$row['cccode']."</td>
				<td>".$row['ctcode']."</td>
				<td>".$row['cclength']."</td>
				<td>".$row['ccheight']."</td>
				<td>".((isset($row['cpopr'])&&$row['cpopr']!="")?$row['cpopr']:'-')."</td>
				<td>".((isset($row['cpife'])&&$row['cpife']==1)?'Full':'Empty')."</td>
				<td>".$row['cpiremark']."</td>
				<td>".$row['sealno']."</td>
				";
			$html .= "
				</tr>";
			$i++; 
		}

		echo json_encode($html);
		die();
	}

	public function edit_get_container($praid) 
	{
		$response = $this->client->request('GET','orderPraContainers/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'praid' => $praid,
				'offset' => 0,
				'limit' => 500,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);			
		$i=1; 
		$html="";
		foreach($result['data']['datas'] as $row){
			$pracrid=$row['pracrnoid'];
			$html .= "<tr>
				<td>
				<a href='#' id='editContainer' class='btn btn-xs btn-primary edit' data-crid='".$row['pracrnoid']."' data-toggle='modal' data-target='#myModal'>edit</a>
				<a href='#' id='editContainer' class='btn btn-xs btn-danger delete' data-crid='".$row['pracrnoid']."' data-act='edit'>delete</a>
				</td>";

			$html .= "<td>".$i."</td>
				<td>".$row['crno']."</td>
				<td>".$row['cccode']."</td>
				<td>".$row['ctcode']."</td>
				<td>".$row['cclength']."</td>
				<td>".$row['ccheight']."</td>
				<td>".((isset($row['cpopr'])&&$row['cpopr']!="")?$row['cpopr']:'-')."</td>
				<td>".((isset($row['cpife'])&&$row['cpife']==1)?'Full':'Empty')."</td>
				<td>".$row['cpiremark']."</td>
				<td>".$row['sealno']."</td>";
			$html .= "</tr>";
			$i++; 
		}

		echo json_encode($html);
		die();
	}	

	public function checkContainerNumber() 
	{
		/*
		return:
		- 
		*/
		$data = [];
		
		if($this->request->isAjax()) {

			$crno = $_POST['crno'];
			$praid = $_POST['praid'];
			$checkFormat = $this->checkContainerFormatNumber($crno);

			if(isset($checkFormat['success']) && ($checkFormat['success']==false))
			{
				$data['status'] = "invalid";	
				$data['message'] = "Invalid Container";	
				echo json_encode($data);die();			
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
					$container = $this->get_container($crno);
					if($container=="" || $container['lastact'] == "HC") {
						$data['status'] = "Failled";
						$data['message'] = "Invalid Container";
						echo json_encode($data);die();
					}

					if((($container['crlastact'] == "CO") && ($container['crlastcond'] == "AC")) || ($container['lastact'] == "AC")) {

				    	// check table order_pra_Container
						$reqPraContainer = $this->client->request('GET','orderPraContainers/getAllData',[
							'headers' => [
								'Accept' => 'application/json',
								'Authorization' => session()->get('login_token')
							],
							'query' => [
								'praid' => $praid,
								'offset' => 0,
								'limit' => 500,
							]
						]);

						$resPraContainer= json_decode($reqPraContainer->getBody()->getContents(), true);		
						$orderPraContainers = $resPraContainer['data']['datas'];
						if(isset($orderPraContainers) && ($orderPraContainers!=null)) {
							foreach($orderPraContainers as $opc) {
								$crnos[] = $opc['crno'];
							}
							if(in_array($crno,$crnos)) {
								$data['status'] = "Failled";
								$data['message'] = "Gunakan Container yang lain";
								echo json_encode($data);die();					
							}
						}	

						$data['status'] = "success";
						$data['message'] = "Valid Container";
						$data['data'] = $container;
						$data['container_code'] = $data['data']['container_code'];
						echo json_encode($data);die();

					} else {		

						$data['status'] = "Failled";
						$data['message'] = "Invalid Container";
						echo json_encode($data);die();					
					}
				}
			}
		}
	}	

	public function checkContainerFormatNumber($crno) 
	{
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
		return $result;
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
		$data  = (isset($result['data']) && $result['data'] != null)?$result['data']:"";
		return $data;
	}

	public function check_container($crno) {
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

	public function hitungHCSTD($datas) 
	{
		$dt = [];
		$hc20 = 0;
		$hc40 = 0;
		$hc45 = 0;
		$std20 = 0;
		$std40 = 0;
		foreach($datas as $data) {
	
			if((floatval($data['ccheight'])>8.6) && (floatval($data['cclength'])<=20)) {
				$hc20=$hc20+1;
			} else if((floatval($data['ccheight'])>8.6) && (floatval($data['ccheight'])<9.5) && (floatval($data['cclength'])==40)) {
				$hc40=$hc40+1;
			} else if((floatval($data['ccheight']))>=9.5 && (floatval($data['cclength'])==40)) {
				$hc45=$hc45+1;
			}

			if((floatval($data['ccheight'])<=8.6) && (floatval($data['cclength'])<=20)) {
				$std20=$std20+1;
			} else if((floatval($data['ccheight'])<=8.6) && (floatval($data['cclength'])==40)) {
				$std40=$std40+1;
			}

			$dt['hc20'] = $hc20;
			$dt['hc40'] = $hc40;
			$dt['hc45'] = $hc45;
			$dt['std20'] = $std20;
			$dt['std40'] = $std40;

		}
		return $dt;
	}

	function get_company() 
	{
		$response = $this->client->request('GET','companies/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);	
		$result = json_decode($response->getBody()->getContents(),true);
		$company = $result['data']['datas'][0];

		// $data = [];

		return $company; 
		die();					
	}

	public function get_depo($code) 
	{
		$response = $this->client->request('GET','depos/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'dpcode' => $code
			]
		]);	
		$result = json_decode($response->getBody()->getContents(),true);
		$depo = $result['data'];

		return $depo; 
		die();
	}

	public function get_contract($id) {
		$data = "";
		$response = $this->client->request('GET','contracts/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params'=>[
				'idContract' => $id,
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);

		if(isset($result['success']) && ($result['success']==true))
		{
			$data = $result['data'];
		} else {
			$data = ""; 		
		}

		return $data;
	}	 
}