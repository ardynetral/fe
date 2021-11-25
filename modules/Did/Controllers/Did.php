<?php
namespace Modules\Did\Controllers;

class Did extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	public function index()
	{
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view"); 
		$data = [];
		// $db = \Config\Database::connect();
		// $sql = "
		// select 
		// cp.cpopr as principal,
		// month(cp.cpitgl) as dinfo_month,
		// day(cp.cpitgl) as dinfo_daily,
		// count(cp.crno) as total, count(cp.cpopr)
		// from container_process cp
		// where year(cp.cpitgl)=year(now())
		// group by cp.cpopr, month(cp.cpitgl), day(cp.cpitgl)
		// ";
		// $dpreport = $db->query($sql)->getResult();
		return view('Modules\Did\Views\add',$data);		
	}

	public function view($code)
	{
		$response = $this->client->request('GET','principals/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'id' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Did\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$prcode = $this->request->getPost('prcode');
	    	$cucode = $this->request->getPost('cucode');
	    	$cncode = $this->request->getPost('cncode');
	    	$prname = $this->request->getPost('prname');
	    	$prremark = $this->request->getPost('prremark');
	    	$praddr = $this->request->getPost('praddr');

			$validate = $this->validate([
	            'prcode' 	=> 'required',
	            'cucode'  => 'required',
	            'cncode'  => 'required',
	            'prname'  => 'required',
	            'prremark'  => 'required',
	            'praddr'  => 'required',
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','principals/create',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'prCode' =>$prcode,
						'dset' => [
							'cucode' =>$cucode,
							'cncode' =>$cncode,
							'prname' =>$prname,
							'prremark' =>$prremark,
							'praddr' =>$praddr,
						]
					]

				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Principal Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
		$data['country_dropdown'] = $this->country_dropdown($selected='');
		return view('Modules\Did\Views\add',$data);		
	}	

	public function edit($code)
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$prcode = $this->request->getPost('prcode');
	    	$cucode = $this->request->getPost('cucode');
	    	$cncode = $this->request->getPost('cncode');
	    	$prname = $this->request->getPost('prname');
	    	$prremark = $this->request->getPost('prremark');
	    	$praddr = $this->request->getPost('praddr');

			$validate = $this->validate([
	            'prcode' 	=> 'required',
	            'cucode'  => 'required',
	            'cncode'  => 'required',
	            'prname'  => 'required',
	            'prremark'  => 'required',
	            'praddr'  => 'required',
	        ]);				
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','principals/update',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'id' =>$prcode,
						'dset' => [
							'cucode' =>$cucode,
							'cncode' =>$cncode,
							'prname' =>$prname,
							'prremark' =>$prremark,
							'praddr' =>$praddr,
						]
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Principal Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		

		$response = $this->client->request('GET','principals/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'id' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		$data['country_dropdown'] = $this->country_dropdown($result['data']['cncode']);
		return view('Modules\Did\Views\edit',$data);		
	}	

	public function delete($code)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','principals/delete',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'id' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Principal '.$code.' Deleted.');
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
		$res = $result['data']['datas'];
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

	public function reportPdf() {
		$db = \Config\Database::connect();
		$sql_body = "
		select 
		cp.cpopr as principal,
		month(cp.cpitgl) as dinfo_month,
		day(cp.cpitgl) as dinfo_daily,
		count(cp.crno) as total
		from container_process cp
		where year(cp.cpitgl)=year(now())
		group by cp.cpopr, month(cp.cpitgl), day(cp.cpitgl)
		";
		
		$data_body = $db->query($sql_body)->getResultArray();		

		$mpdf = new \Mpdf\Mpdf();
		$html = '';

		$html .= '
		<html>
			<head>
				<title>Report Depo Info Daily</title>

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
					
				</tbody>
			</table>
		';

		$html .='
			<h4>Repor Depo Info Daily</h4>
			<table class="tbl_det_prain">
				<thead>
					<tr>
						<th>NO</th>
						<th>Principal</th>
						<th>Info Daily</th>
						<th>Info Month</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>';
				$no=1;
				foreach($data_body as $row){
					$html .='
					<tr>
						<td>'.$no.'</td>
						<td>'.$row['principal'].'</td>
						<td>'.$row['dinfo_month'].'</td>
						<td>'.$row['dinfo_daily'].'</td>
						<td>'.$row['total'].'</td>
					</tr>';

					$no++;
				}
		$html .='
				</tbody>
			</table>

		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		die();				
	}
}