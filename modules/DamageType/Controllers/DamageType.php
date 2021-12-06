<?php
namespace Modules\DamageType\Controllers;

class DamageType extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	function list_data(){		
		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        $sort_column = $this->request->getPost('order[0][column]');	
        $sort_type = $this->request->getPost('order[0][dir]');	
        $orderColumn = '';
        if ($sort_column == '0') {
        	$orderColumn = '';
        } elseif ($sort_column == '1') {
        	$orderColumn = 'dycode';
        } elseif ($sort_column == '2') {
        	$orderColumn = 'dydesc';
        } 	
		// PULL data from API
		$response = $this->client->request('GET','damagetype/list',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => [
					'start' => (int)$offset,
					'rows'	=> (int)$limit,
					'search'=> (string)$search,
					'orderColumn' => (string)$orderColumn,
					'orderType' => (string)$sort_type
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
            $record[] = $v['dycode'];
            $record[] = $v['dydesc'];
			
			$btn_list .= '<a href="'.site_url().'/damagetype/view/'.$v['dycode'].'" class="btn btn-xs btn-primary btn-tbl">View</a>';	
			$btn_list .= '<a href="'.site_url().'/damagetype/edit/'.$v['dycode'].'" class="btn btn-xs btn-success btn-tbl">Edit</a>';
			$btn_list .= '<a href="'.site_url().'/damagetype/delete/'.$v['dycode'].'" class="btn btn-xs btn-danger btn-tbl">Delete</a>';
            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        
        echo json_encode($output);
		
	}

	public function index()
	{
		$data = [];
		$response = $this->client->request('GET','damagetype/list',[
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
		$data['damagetype'] = isset($result['data']['datas'])?$result['data']['datas']:"";
		return view('Modules\DamageType\Views\index',$data);
	}

	public function view($dycode)
	{
		$response = $this->client->request('GET','damagetype/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'dyCode' => $dycode,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['damagetype'] = isset($result['data'])?$result['data']:"";
		return view('Modules\DamageType\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$dycode = $this->request->getPost('dycode');
	    	$dydesc = $this->request->getPost('dydesc');

			$validate = $this->validate([
	            'dycode' 	=> 'required',
	            'dydesc'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','damagetype/create',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'dyCode' =>$dycode,
						'dyDesc' =>$dydesc,
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				$dt_err="";

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				$msg = $result['data'];
				$dataFlash['sukses'] = [
					'dycode'	=> $msg['dycode'],
					'dydesc'		=> $msg['dydesc']
				];

				session()->setFlashdata('sukses','Success, Damage Type Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		
		return view('Modules\DamageType\Views\add',$data);		
	}	

	public function edit($dycode)
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$dycode = $this->request->getPost('dycode');
	    	$dydesc = $this->request->getPost('dydesc');

			$validate = $this->validate([
	            'dycode' 	=> 'required',
	            'dydesc'  => 'required'
	        ]);			

		    if ($this->request->getMethod() === 'post' && $validate)
		    {
				$response = $this->client->request('POST','damagetype/update',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'json' => [
						'dyCode' => $dycode,
						'dyDesc' => $dydesc
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				$dt_err="";

				if(isset($result['errors']))
				{
					foreach($result['errors'] as $err) {
						foreach($err as $er) {
							$dt_err .= $er ."<br>";	
						}
					}

					$data['message'] = $dt_err;
					echo json_encode($data);die();				
				}

				$msg = $result['message'];

				session()->setFlashdata('sukses', $msg);
				$data['message'] = "success";
				echo json_encode($data);die();
			}
			else
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();					
			}
		} 

		$response = $this->client->request('GET','damagetype/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'dyCode' => $dycode,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['damagetype'] = $result['data'];
		return view('Modules\DamageType\Views\edit',$data);
	}	

	public function delete($dycode)
	{
		$response = $this->client->request('DELETE','damagetype/delete',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'dyCode' => $dycode
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$success = $result['success'];
		if($success){
			session()->setFlashdata('sukses','Success, Damage Type : <b>'.$dycode.'<b> Deleted.');
			return redirect()->to('damagetype');
		}
		// echo json_encode($data);die();
		// dd($data['ctype']);
		// return view('Modules\ContainerType\Views\index',$data);
	}	

}