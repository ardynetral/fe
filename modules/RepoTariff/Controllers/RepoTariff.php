<?php
namespace Modules\RepoTariff\Controllers;

class RepoTariff extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	public function index()
	{
		$data = [];
		// $response = $this->client->request('GET','city/list',[
		// 	'headers' => [
		// 		'Accept' => 'application/json',
		// 		'Authorization' => session()->get('login_token')
		// 	],
		// 	'json'=>[
		// 		'start'=>0,
		// 		'rows'=>10
		// 	]
		// ]);

		// $result = json_decode($response->getBody()->getContents(),true);	
		// $data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\RepoTariff\Views\index',$data);
	}
	public function list_data() {

		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;

		$response = $this->client->request('GET','repotarif/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => $offset,
				'limit' => $limit,
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
            $record[] = $v['rtno'];
            $record[] = $v['prcode'];
			$record[] = $v['rtdate'];
			$record[] = $v['rtexpdate'];
			$record[] = $v['rtremarks'];

			// $btn_list .='<a href="'.site_url('repotariff/view/').$v["rtno"].'" id="" class="btn btn-xs btn-primary btn-table" data-praid="">view</a>&nbsp;';						
			$btn_list .='<a href="'.site_url('repotariff/edit/').$v["prcode"].'/'.$v["rtno"].'" class="btn btn-xs btn-success btn-table">edit</a>&nbsp;';
			// $btn_list .='<a href="#" id="deleteRow_'.$no.'" class="btn btn-xs btn-danger btn-table delete" data-kode="'.$v['rtno'].'">delete</a>';	

            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);		
	}
	public function view($code)
	{
		$response = $this->client->request('GET','city/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'cityId' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\RepoTariff\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
			$validate = $this->validate([
	            'rtno' 	=> 'required',
	            'prcode'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {
		    	$rtdate = date("Y-m-d", strtotime($_POST['rtdate']));
		    	$rtexpdate = date("Y-m-d", strtotime($_POST['rtexpdate']));
				$response = $this->client->request('POST','repotarif/createNewData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
					    'prcode'=>$_POST['prcode'],
					    'rtno'=>$_POST['rtno'],
						'rtdate'=> $rtdate,
						'rtexpdate'=> $rtexpdate,
						'rtremarks'=> $_POST['rtremarks']
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				// echo var_dump($result);die();
				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				$data['status'] = "success";
				$data['message'] = "Data Saved";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['status'] = "Failled";
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
		$data['from_depo_dropdown'] = $this->depo_dropdown("retfrom","DEPO CONTINDO");
		$data['from_port_dropdown'] = $this->port_dropdown("retfrom","");
		$data['from_city_dropdown'] = $this->city_dropdown("retfrom","");
		$data['to_depo_dropdown'] = $this->depo_dropdown("retto","DEPO CONTINDO");
		$data['to_port_dropdown'] = $this->port_dropdown("retto","");
		$data['to_city_dropdown'] = $this->city_dropdown("retto","");			
		return view('Modules\RepoTariff\Views\add',$data);		
	}	

	public function edit($prcode,$rtno)
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
			$validate = $this->validate([
	            'rtno' 	=> 'required',
	            'prcode'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {
		    	$rtdate = date("Y-m-d", strtotime($_POST['rtdate']));
		    	$rtexpdate = date("Y-m-d", strtotime($_POST['rtexpdate']));
				$response = $this->client->request('POST','repotarif/updateData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
					    'prcode'=>$_POST['prcode'],
					    'rtno'=>$_POST['rtno'],
						'rtdate'=> $rtdate,
						'rtexpdate'=> $rtexpdate,
						'rtremarks'=> $_POST['rtremarks']
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				// echo var_dump($result);die();
				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				$data['status'] = "success";
				$data['message'] = "Data Saved";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['status'] = "Failled";
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}				
		}		
		// header
		$response1 = $this->client->request('GET','repotarif/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'prcode' => $prcode,
				'rtno' => $rtno,
			]
		]);
		$result1 = json_decode($response1->getBody()->getContents(), true);

		// detail
		$response2 = $this->client->request('GET','repo_tariff_details/getDetailPrcode',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'prcode' => $prcode,
				'rtno' => $rtno,
			]
		]);
		$result2 = json_decode($response2->getBody()->getContents(), true);

		// set repotype on detail form

		$data['data'] = isset($result1['data'])?$result1['data']:"";
		$data['detail'] = isset($result2['data']['datas'])?$result2['data']['datas']:"";		
		return view('Modules\RepoTariff\Views\edit',$data);		
	}	

	public function delete($code)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','city/delete',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'cityId' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, City Code '.$code.' Deleted.');
			$data['message'] = "success";
			echo json_encode($data);die();
		}
	}		

	// DETAIL TARIFF
	public function add_detail()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
			$validate = $this->validate([
	            'rtid' 	=> 'required',
	            'retype' 	=> 'required',
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {
		    	$form_params = [];

				if($_POST['retype']=="21" || $_POST['retype']=="11") {
					$retfrom = "DEPOT";
					$retto = "DEPOT";
				} else if($_POST['retype']=="22") {
					$retfrom = "PORT";
					$retto = "DEPOT";
				} else if($_POST['retype']=="23") {
					$retfrom = "CITY";
					$retto = "DEPOT";
				}
				else if($_POST['retype']=="12") {
					$retfrom = "DEPOT";
					$retto = "PORT";
				} else if($_POST['retype']=="13") {
					$retfrom = "DEPOT";
					$retto = "CITY";
				}				
				$reformat = [
					'rttype' => $_POST['retype'],
					'rtfrom' => $retfrom,
					'rtto' => $retto
				];
				$form_params = array_replace($_POST, $reformat);
				$response = $this->client->request('POST','repo_tariff_details/createNewData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params
				]);	
				$result = json_decode($response->getBody()->getContents(), true);
				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				$data['status'] = "success";
				$data['message'] = "Data Saved";
				echo json_encode($data);die();							    	
			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
		return view('Modules\RepoTariff\Views\add_detail',$data);		
	}		

	public function edit_detail()
	{
		if ($this->request->isAJAX()) 
		{

			$validate = $this->validate([
	            'prcode' 	=> 'required',
	            'retype'  => 'required',
	            'rtno'  => 'required',
	            'rtef'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {
		    	$form_params = [];

				if($_POST['retype']=="21" || $_POST['retype']=="11") {
					$retfrom = "DEPOT";
					$retto = "DEPOT";
				} else if($_POST['retype']=="22") {
					$retfrom = "PORT";
					$retto = "DEPOT";
				} else if($_POST['retype']=="23") {
					$retfrom = "CITY";
					$retto = "DEPOT";
				}
				else if($_POST['retype']=="12") {
					$retfrom = "DEPOT";
					$retto = "PORT";
				} else if($_POST['retype']=="13") {
					$retfrom = "DEPOT";
					$retto = "CITY";
				}				
				$reformat = [
					'rttype' => $_POST['retype'],
					'rtfrom' => $retfrom,
					'rtto' => $retto
				];
				$form_params = array_replace($_POST, $reformat);
				$response = $this->client->request('PUT','repo_tariff_details/updateData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params
				]);	
				$result = json_decode($response->getBody()->getContents(), true);
				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				$data['status'] = "success";
				$data['message'] = "Data Saved";
				echo json_encode($data);die();							    	
			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}	
	}
	// Tampilkan From to berdasarkan repotype
	public function get_detail_repotype($retype) 
	{
		if($this->request->isAjax()) 
		{
			$html = "";
			// IN
			$html .='
			<div class="form-group">
				<label  class="col-sm-3 control-label text-right">From</label>
				<div id="fromDepoBlok" class="col-sm-9 hideBlock">
					'.$this->depo_dropdown("retfrom","DEPO CONTINDO").'
				</div>
				<div id="fromPortBlok" class="col-sm-9 hideBlock">
					'.$this->port_dropdown("retfrom","").'
				</div>
				<div id="fromCityBlok" class="col-sm-9 hideBlock">
					'.$this->city_dropdown("retfrom","").'
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label text-right">To</label>
				<div id="toDepoBlok" class="col-sm-9 hideBlock">
					'.$this->depo_dropdown("retto","DEPO CONTINDO").'
				</div>
				<div id="toPortBlok" class="col-sm-9 hideBlock">
					'.$this->port_dropdown("retto","").'
				</div>						
				<div id="toCityBlok" class="col-sm-9 hideBlock">
					'.$this->city_dropdown("retto","").'
				</div>
			</div>';
			echo json_encode($html);die();
		}
	}

	public function load_detail_table()
	{
		$html="";
		$response = $this->client->request('GET','repo_tariff_details/getDetailPrcode',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query'=>[
				'prcode'=>$_POST['prcode'],
				'rtno'=>$_POST['rtno']
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		// echo var_dump($result);die();	
		$data = $result['data']['datas'];	
		$in_out = "";
		$retype = "";
		$fe = "";
		foreach ($data as $row) { 
			// REPO IN/OUT
			if($row['rtid']==1) {$in_out="OUT";}
			else if($row['rtid']==2) {$in_out="IN";}
			// REPOTYPE
			if($row['rttype']==11) {$retype=="DEPOT to DEPOT(in)";}
			else if($row['rttype']==12) {$retype="DEPOT to PORT";}
			else if($row['rttype']==13) {$retype="DEPOT to INTERCITY";}
			else if($row['rttype']==21) {$retype="DEPOT to DEPOT(out)";}
			else if($row['rttype']==22) {$retype="PORT to DEPOT";}
			else if($row['rttype']==23) {$retype="INTERCITY to DEPOT";}
			// F/E
			if($row['rtef']=="1") {$fe = "Full";}
			if($row['rtef']=="0") {$fe = "Empty";}

			$html .='<tr>
			<td>
				<a href="#" class="btn btn-xs btn-primary edit" 
				data-prcode="'.$row['prcode'].'" 
				data-rtno="'.$row['rtno'].'" 
				data-rttype="'.$row['rttype'].'" 
				data-rtef="'.$row['rtef'].'">edit</a>

				&nbsp;<a href="#" class="btn btn-xs btn-danger delete"
				data-prcode="'.$row['prcode'].'" 
				data-rtno="'.$row['rtno'].'" 
				data-rttype="'.$row['rttype'].'" 
				data-rtef="'.$row['rtef'].'">delete</a>
			</td>';
			$html .='
			<td>'.$in_out.'</td>
			<td>'.$retype.'</td>
			<td>'.$row['rtno'].'</td>
			<td>'.$row['prcode'].'</td>
			<td>'.$fe.'</td>
			<td>'.$row['rtpackcurr'].'</td>
			<td>'.$row['rtpackv20'].'</td>
			<td>'.$row['rtpackv40'].'</td>
			<td>'.$row['rtpackv45'].'</td>
			<td>'.$row['rtdoccurr'].'</td>
			<td>'.$row['rtdocm'].'</td>
			<td>'.$row['rtdocv'].'</td>
			<td>'.$row['rthaulcurr'].'</td>
			<td>'.$row['rthaulv20'].'</td>
			<td>'.$row['rthaulv40'].'</td>
			<td>'.$row['rthaulv45'].'</td>	
			</tr>';								
		} 
		echo json_encode($html);die();
	}

	public function delete_detail($prcode,$rtno,$rttype,$rtef)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','repo_tariff_details/deleteData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'prcode' => $prcode,
					'rtno' => $rtno,
					'rttype' => $rttype,
					'rtef' => $rtef,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			$data['status'] = "success";
			$data['message'] = "Data Deleted";
			echo json_encode($data);die();
		}
	}	

	public function get_one_detail($prcode,$rtno,$rttype,$rtef)
	{
		// if($this->request->isAjax()) {
			$response = $this->client->request('GET','repo_tariff_details/detailRepoTarif',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'prcode' => $prcode,
					'rtno' => $rtno,
					'rttype' => $rttype,
					'rtef' => $rtef
				]
			]);
			$result = json_decode($response->getBody()->getContents(), true);
			if(isset($result['message'])&& $result['message']=="Failled"){
				$data['status'] = "Failled";
				$data['message'] = "data tidak ditemukan.";
			}
			$data['status'] = "success";
			$data['data'] = $result['data'];
			echo json_encode($data);die();			
		// }
	}
	public function depo_dropdown($varname="",$selected="")
	{
		$response = $this->client->request('GET','depos/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$depo = $result['data']['datas'];
		$option = "";
		$option .= '<select name="'.$varname.'" id="'.$varname.'" class="select-depo">';
		$option .= '<option value="">-select-</option>';
		foreach($depo as $p) {
			$option .= "<option value='".$p['dpname'] ."'". ((isset($selected) && $selected==$p['dpname']) ? ' selected' : '').">".$p['dpname']."</option>";
		}
		$option .="</select>";
		return $option; 
		die();			
	}

	public function port_dropdown($varname="",$selected="")
	{
		$response = $this->client->request('GET','ports/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$port = $result['data']['datas'];
		$option = "";
		$option .= '<select name="'.$varname.'" id="'.$varname.'" class="select-port">';
		$option .= '<option value="">-select-</option>';
		foreach($port as $p) {
			$option .= "<option value='".$p['poid'] ."'". ((isset($selected) && $selected==$p['poid']) ? ' selected' : '').">".$p['podesc']."</option>";
		}
		$option .="</select>";
		return $option; 
		die();			
	}
	public function getCity($id) {
		$response = $this->client->request('GET','city/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json'=>[
				'cityId'=>$id
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		if(isset($result['data'])) {
			$city = $result['data']['city_name'];
		} else {
			$city = "";
		}

		return $city;
	}

	public function city_dropdown($varname,$selected="") 
	{
		$response = $this->client->request('GET','city/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json'=>[
				'start'=>0,
				'rows'=>10
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$data = $result['data']['datas'];
		$option = "";
		$option .= '<select name="'.$varname.'" id="'.$varname.'" class="select-city">';
		$option .= '<option value="">-select-</option>';
		foreach($data as $r) {
			$option .= "<option value='".$r['city_name'] ."'". ((isset($selected) && $selected==$r['city_name']) ? ' selected' : '').">".$r['city_name']."</option>"; 
		}
		$option .="</select>";
		return $option; 
		die();		
	}	

}