<?php
namespace Modules\RepoIn\Controllers;

//require_once ROOTPATH . 'vendor/autoload.php';
//use \Mpdf\Mpdf;

use App\Libraries\MyPaging;

class RepoIn extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		helper(['Modules\Repoin\Helpers\repoin']);
		$this->client = api_connect();
	}

	function list_data(){		
		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "")?$this->request->getPost('search'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        // $sort_dir = $this->get_sort_dir();		
		// PULL data from API
		$response = $this->client->request('GET','orderContainerRepos/getAllData',[
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
		// print_r($result);die();
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => @$result['data']['count'],
            "recordsFiltered" => @$result['data']['count'],
            "data" => array()
        );
		$no = ($offset !=0)?$offset+1 :1;
		foreach ($result['data']['datas'] as $k=>$v) {
			$reorderno = $v['reorderno'];
			$btn_list="";
            $record = array(); 
            $record[] = $no;
            $record[] = $v['reorderno'];
            $record[] = date('d-m-Y', strtotime($v['redate']));
            $record[] = $v['cpopr'];
			
			// $btn_list .= '<a href="'.site_url().'/repoin/view/'.$reorderno.'" class="btn btn-xs btn-primary btn-tbl">View</a>';	
			$btn_list .= '<a href="#" class="btn btn-xs btn-primary btn-tbl">View</a>';	
			// $btn_list .= '<a href="#" data-repoid="'.$v['reorderno'].'" class="btn btn-xs btn-info print_order btn-tbl">Print</a>';
			$btn_list .= '<a href="#" id="" class="btn btn-xs btn-danger btn-tbl delete" data-kode="'.$reorderno.'">Delete</a>';
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
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];
		$data = [];
		$data['prcode'] = $prcode;
		$data['cucode'] = $prcode;
		$data['page_title'] = "Order Repo Pra In";
		$data['page_subtitle'] = "Order Repo Pra In Page";		
		return view('Modules\RepoIn\Views\index',$data);
	}

	public function view($code)
	{
		check_exp_time();
		$data['status'] = "";
		$data['message'] = "";

		$response = $this->client->request('GET','orderContainerRepos/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'RI000D100000001' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	

		if(isset($result['status']) && ($result['status']=="Failled"))
		{
			$data['data'] = "";
			// echo json_encode($data);die();				
		}

		$data['containers'] = $this->getRepoContainers($result['data']['repoid']);

		$data['data'] = $result['data']['datas'];
		return view('Modules\RepoIn\Views\view',$data);
	}	

	public function add()
	{
		check_exp_time();

		$offset=0;
		$limit=100;		
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if ($this->request->isAJAX()) 
		{
			// echo var_dump($_POST);die();

			$reformat = [
				'reorderno' => $this->get_repoin_number(),
				'cpopr'		=> $_POST['prcode'],
				'redate' => date('Y-m-d',strtotime($_POST['redate'])),
				'redline' => date('Y-m-d',strtotime($_POST['redline']))  
			];
			
			// $form_params = [
			// 	'cpiorderno' => $_POST['cpiorderno'],
			// ];		
		
		    if ($this->request->getMethod() === 'post')
		    {

				$response = $this->client->request('POST','orderContainerRepos/createNewData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => array_replace($_POST,$reformat),
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}
				// echo var_dump($result);die();
				session()->setFlashdata('sukses','Success, Order Repo Saved.');
				$data['message'] = "success";
				$data['repoid'] = $result['data']['repoid']; 
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = "Lengkapi form";
		    	echo json_encode($data);die();			
			}			
		}

		// order pra container
		$response2 = $this->client->request('GET','orderContainerRepos/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => $offset,
				'limit'	=> $limit
			]
		]);

		$data['act'] = "add";
		$data['prcode'] = $prcode;
		$data['cucode'] = $prcode;
		$data['repoin_no'] = $this->get_repoin_number();
		$result_container = json_decode($response2->getBody()->getContents(),true);	
		// $data['data_container'] = isset($result_container['data']['datas'])?$result_container['data']['datas']:"";

		return view('Modules\RepoIn\Views\add',$data);						
	}	
	public function update_new_data() 
	{
		check_exp_time();
		// print_r($_POST);die();
		if ($this->request->isAJAX()) 
		{
			$response = $this->client->request('PUT','orderContainerRepos/updateData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $_POST,
			]);
			
			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}
			session()->setFlashdata('sukses','Success, Order Repo Saved.');
			$data['message'] = "success";
			echo json_encode($data);die();
		}

	}

	public function addcontainer()
	{
		check_exp_time();
		// $data = [];
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if ($this->request->isAJAX()) 
		{
			$form_params = [
				// repoid belum ditambahkan diendpoint orderContainerRepos/*
				'repoid' => $_POST['repoid'],
				'crno' => $_POST['crno'],
				'cccode' => $_POST['ccode'],
				'ctcode' => $_POST['ctcode'],
				'cclength' => $_POST['cclength'],
				'ccheight' => $_POST['ccheight'],
				'cpife' => $_POST['cpife'],
				'cpishold' => $_POST['cpishold'],
				'reporemark' => $_POST['reporemark']
			];

			$validate = $this->validate([
	            'crno' 	=> 'required'
	        ]);	

		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','orderRepoContainer/createNewData',[
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

				session()->setFlashdata('sukses','Success, Containers Saved.');
				$data['message'] = "success";
				// $data['praid'] = $result['data']['praid'];
				$data['QTY'] = hitungHCSTD($this->getRepoContainers($_POST['repoid']));
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
	}	

	public function getRepoContainers($repoid) {
		// get OrderPraContainer
		$response = $this->client->request('GET','orderRepoContainer/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'repoid' => $repoid,
				'offset' => 0,
				'limit' => 100,
			]
		]);

		$result= json_decode($response->getBody()->getContents(), true);
		if($result['data']['count']==0) {
			$datas = "";
		} else {
			$datas = $result['data']['datas'];		
		}
		return $datas;
	}

	public function edit($code)
	{
		
	}	

	public function delete($code)
	{
		check_exp_time();
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','orderContainerRepos/deleteData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'reorderno' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Data berhasil dihapus');
			$data['message'] = "success";
			echo json_encode($data);die();
		}
	}		

	public function ajax_country() 
	{
		$response = $this->client->request('GET','country/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'cityId' => $cccode,
			]
		]);
		$result = json_decode($response->getBody()->getContents(), true);	

		if($this->request->isAJAX()) {
			echo json_encode($result['data']);
			die();		
		}

		return $result['data'];
	}

	public function country_dropdown($selected="")
	{
		$data = [];
		$response = $this->client->request('GET','countries/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json'=>[
				'start'=>0,
				'rows'=>100
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$res = $result['data'];
		$option = "";
		$option .= '<select name="cncode" id="cncode" class="select-cncode">';
		$option .= '<option value="">-select-</option>';
		foreach($res as $r) {
			$option .= "<option value='".$r['cncode'] ."'". ((isset($selected) && $selected==$r['cncode']) ? ' selected' : '').">".$r['cndesc']."</option>"; 
		}
		$option .="</select>";
		return $option; 
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

	public function ajax_prcode_listOne($code)
	{
		$data = [];
		if($this->request->isAjax()) {
			$response = $this->client->request('GET','principals/listOne',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params'=>[
					'id' => $code,
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

	public function ajax_vessel_listOne($code)
	{
		$data = [];
		if($this->request->isAjax()) {
			$response = $this->client->request('GET','vessels/listOne',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params'=>[
					'id' => $code,
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

	public function get_repoin_number()
	{
		$data = [];
		$response = $this->client->request('GET','orderContainerRepos/createOrderRepoNumber',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'repoCode' =>'RI'
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		if(isset($result['success']) && ($result['success']==true))
		{
			$data['status'] = "success";
			return $result['data'];		
		}
	}

	// ajax list voyage
	function ajax_voyage_list()
	{
		$api = api_connect();
		$response = $api->request('GET','voyages/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		$vessel = $result['data']['datas'];

		$data = [];
		$items = [];

		foreach($vessel as $v) {
			$items[]= [
				'id'=>$v['voyid'],
				'no'=>$v['voyno']
			];

		}
		$data['items'] = $items;
		$data['total_count'] = count($items);
		return json_encode($data); 
		die();			
	}	

	public function get_repo_tariff_detail() 
	{
		$session = session();

		$contract = get_contract($_POST['prcode']);

		$response = $this->client->request('GET','repo_tariff_details/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'prcode' => $_POST['prcode'],
				'rttype' => $_POST['retype']
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		if(isset($result['success']) && ($result['success']==true))
		{
			$data['status'] = "success";
			$data['data'] =  $result['data'];		
			$data['contract'] =  $contract;
		} else {
			$data['status'] = "Failled";
			$data['data'] = "";
		}

		echo json_encode($data);die();
	}

	public function ajax_repo_containers() 
	{
		$response = $this->client->request('GET','orderRepoContainer/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'repoid' => $_POST['repoid'],
				'offset' => 0,
				'limit' => 100,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);			
		$i=1; 
		$html="";
		foreach($result['data']['datas'] as $row){
			$repocrnoid=$row['repocrnoid'];
			$html .= "<tr>
				<td>".$i."</td>
				<td>".$row['crno']."</td>
				<td>".$row['cccode']."</td>
				<td>".$row['ctcode']."</td>
				<td>".$row['cclength']."</td>
				<td>".$row['ccheight']."</td>
				<td>".((isset($row['repofe'])&&$row['repofe']==1)?'Full':'Empty')."</td>
				<td>".((isset($row['reposhold'])&&$row['reposhold']==1)?'Hold':'Release')."</td>
				<td>".$row['reporemark']."</td>";
			$html .= "</tr>";
			$i++; 
		}

		echo json_encode($html);
		die();
	}

	// Print detail order
	public function print_order($id) {
		check_exp_time();
		//$mpdf = new Mpdf();

		$data = [];
		$response = $this->client->request('GET','orderPras/printOrderByPraOrderId',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query'=>[
				'praid' => $id,
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		
		$header = $result['data']['datas'];
		$detail = $header[0]['orderPraContainers'];

		if(isset($result['status']) && ($result['status']=="Failled"))
		{
			$data['status'] = "Failled";
			$data['message'] = $result['message'];
			echo json_encode($data);die();				
		}
		
		$html = '';

		$html .= '
		<html>
			<head>
				<title>Order PraIn</title>

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
				<div class="head-left">
					<h4>PT. CONTINDO RAYA</h4>
				</div>
				<div class="head-right">
					<p>PADANG, '.date('d/m/Y').'</p>		
				</div>
			</div>
		';
		$html .='
			<table class="tbl_head_prain" width="100%">
				<tbody>
					<tr>
						<td class="t-right" width="180">Principal</td>
						<td width="200">&nbsp;:&nbsp;'.$header[0]['cpopr'].'</td>
						<td class="t-right" width="120">Pra In Reff</td>
						<td>&nbsp;:&nbsp;'.$header[0]['cpiorderno'].'</td>
					</tr>
					<tr>
						<td class="t-right">Customer</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpcust'].' </td>
						<td class="t-right">Pra In Date</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpipratgl'].' </td>
					</tr>
					<tr>
						<td class="t-right">Discharge Port</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpidish'].'  </td>
						<td class="t-right">Ref In N0 #</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpirefin'].'  </td>
					</tr>
					<tr>
						<td class="t-right">Discharge Date</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpidisdat'].'  </td>
						<td class="t-right">Time In</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpijam'].'  </td>
					</tr>
					<tr>
						<td class="t-right">LiftOff Charges In Depot</td>
						<td class="t-left">&nbsp;:&nbsp; '.((isset($header[0]['liftoffcharge'])&&$header[0]['liftoffcharge']==1)?"yes" : "no").'</td>
						<td class="t-right">Vessel</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpives'].' </td>
					</tr>
					<tr>
						<td class="t-right">Depot</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpdepo'].' </td>
						<td class="t-right">Voyage</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['voyages']['voyno'].' </td>
					</tr>
					<tr>
						<td class="t-right">&nbsp;</td>
						<td class="t-left">&nbsp;</td>
						<td class="t-right">Vessel Operator</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpives'].' </td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td class="t-right">Ex Cargo</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpicargo'].' </td>
					</tr>	
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td class="t-right">Redeliverer</td>
						<td class="t-left">&nbsp;:&nbsp;'.$header[0]['cpideliver'].' </td>
					</tr>							
				</tbody>
			</table>
		';

		$html .='
			<div class="line-space"></div>
			<h4>Detail Container</h4>
			<table class="tbl_det_prain">
				<thead>
					<tr>
						<th>NO</th>
						<th>Container No.</th>
						<th>ID Code</th>
						<th>Type</th>
						<th>Length</th>
						<th>Height</th>
						<th>F/E</th>
						<th>Gate In Date</th>
					</tr>
				</thead>
				<tbody>';
				$no=1;
				foreach($detail as $row){
					$html .='
					<tr>
						<td>'.$no.'</td>
						<td>'.$row['crno'].'</td>
						<td>'.$row['cccode'].'</td>
						<td>'.$row['ctcode'].'</td>
						<td>'.$row['cclength'].'</td>
						<td>'.$row['ccheight'].'</td>
						<td>'.((isset($row['cpife'])&&$row['cpife']==1) ? "full" : "Empty").'</td>
						<td>'.$row['cpigatedate'].'</td>
					</tr>';

					$no++;
				}
		$html .='
				</tbody>
			</table>

		</body>
		</html>
		';
		// $mpdf->WriteHTML($html);
		// $mpdf->Output();
		echo $html;
		die();		
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

		return $result['data'];
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
}