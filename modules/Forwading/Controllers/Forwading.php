<?php
namespace Modules\Forwading\Controllers;

class Forwading extends \CodeIgniter\Controller
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

		$data['page_title'] = "Forwading/EMKL";
		$data['page_subtitle'] = "Forwading/EMKL Page";
		return view('Modules\Forwading\Views\index',$data);
	}

	public function list_data() {

		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "")?$this->request->getPost('search'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;

		$response = $this->client->request('GET','debiturs/getAllDataByCutype',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => $offset,
				'limit' => $limit,
				'cutype' =>1,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => @$result['data']['count'],
            "recordsFiltered" => @$result['data']['count'],
            "data" => array()
        );
		$no = ($offset !=0)?$offset+1 :1;
		foreach ($result['data']['datas'] as $k=>$v) {
			$btn_list="";
            $record = array(); 
            $record[] = $no;
            $record[] = $v['cucode'];
            $record[] = $v['cuname'];
			
			$btn_list .='<a href="'.site_url('forwading/view/').$v["cucode"].'" id="" class="btn btn-xs btn-primary btn-table" data-praid="">view</a>&nbsp;';						
			$btn_list .='<a href="'.site_url('forwading/edit/').$v["cucode"].'" class="btn btn-xs btn-success btn-table">edit</a>&nbsp;';
			$btn_list .='<a href="#" class="btn btn-xs btn-info btn-table" data-praid="">print</a>&nbsp;';	
			$btn_list .='<a href="#" id="deleteRow_'.$no.'" class="btn btn-xs btn-danger btn-table delete" data-kode="'.$v['cucode'].'">delete</a>';			
            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);		
	}

	public function view($code)
	{
		$response = $this->client->request('GET','debiturs/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'cucode' => $code,
			]
		]);

		$data['page_title'] = "Forwading/EMKL";
		$data['page_subtitle'] = "Forwading/EMKL Page";
		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Forwading\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
			$headers = [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			];

			$form_params = [
			    "cucode" => $this->request->getPost("cucode"),
			    "cncode" => $this->request->getPost("cncode"),
				"cuname" => $this->request->getPost("cuname"),
			    "cuaddr" => $this->request->getPost("cuaddr"),
			    "cuzip" => $this->request->getPost("cuzip"),
			    "cuphone" => $this->request->getPost("cuphone"),
			    "cufax" => $this->request->getPost("cufax"),
			    "cucontact" => $this->request->getPost("cucontact"),
			    "cuemail" => $this->request->getPost("cuemail"),
			    "cunpwp" => $this->request->getPost("cunpwp"),
			    "cuskada" => $this->request->getPost("cuskada"),
			    "cudebtur" => $this->request->getPost("cudebtur"),
			    "cutype" => '1',
			    "cunppkp" => $this->request->getPost("cunppkp")		
			];

			$validate = $this->validate([
	            'cucode' 	=> 'required',
	            'cncode'  => 'required',
	            'cuname'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','debiturs/createNewData',[
					'headers' => $headers,
					'form_params' => $form_params
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Debitur Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}

		$data['page_title'] = "Forwading/EMKL";
		$data['page_subtitle'] = "Add Forwading/EMKL Page";				
		$data['country_dropdown'] = country_dropdown($selected='');
		return view('Modules\Forwading\Views\add',$data);		
	}	

	public function edit($code)
	{
		$data = [];
		$headers = [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		];

		if ($this->request->isAJAX()) 
		{
			$form_params = [
			    "cucode" => $this->request->getPost("cucode"),
			    "cncode" => $this->request->getPost("cncode"),
				"cuname" => $this->request->getPost("cuname"),
			    "cuaddr" => $this->request->getPost("cuaddr"),
			    "cuzip" => $this->request->getPost("cuzip"),
			    "cuphone" => $this->request->getPost("cuphone"),
			    "cufax" => $this->request->getPost("cufax"),
			    "cucontact" => $this->request->getPost("cucontact"),
			    "cuemail" => $this->request->getPost("cuemail"),
			    "cunpwp" => $this->request->getPost("cunpwp"),
			    "cuskada" => $this->request->getPost("cuskada"),
			    "cudebtur" => $this->request->getPost("cudebtur"),
			    "cutype" => '1',
			    "cunppkp" => $this->request->getPost("cunppkp")		
			];

			$validate = $this->validate([
	            'cucode' 	=> 'required',
	            'cncode'  => 'required',
	            'cuname'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('PUT','debiturs/updateData',[
					'headers' => $headers,
					'form_params' => $form_params
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Berhasil menyimpan data.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		

		$response = $this->client->request('GET','debiturs/getDetailData',[
			'headers' => $headers,
			'form_params' => [
				'cucode' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		// dd($result['data']['cncode']);

		$data['page_title'] = "Forwading/EMKL";
		$data['page_subtitle'] = "Forwading/EMKL Page";		
		$data['data'] = isset($result['data'])?$result['data']:"";
		$data['country_dropdown'] = country_dropdown($result['data']['cncode']);
		return view('Modules\Forwading\Views\edit',$data);		
	}	

	public function delete($code)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','debiturs/deleteData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'cucode' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Hapus data sukses.');
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

}