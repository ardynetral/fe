<?php
namespace Modules\Contract\Controllers;

class Contract extends \CodeIgniter\Controller
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

		$data['page_title'] = "Contract";
		$data['page_subtitle'] = "Contract Page";
		return view('Modules\Contract\Views\index',$data);
	}

	public function list_data() 
	{
		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        $sort_column = $this->request->getPost('order[0][column]');	
        $sort_type = $this->request->getPost('order[0][dir]');		
        $orderColumn = '';
        if ($sort_column == '0') {
        	$orderColumn = '';
        } elseif ($sort_column == '1') {
        	$orderColumn = 'cono';
        } elseif ($sort_column == '2') {
        	$orderColumn = 'prcode';
        } elseif ($sort_column == '3') {
        	$orderColumn = 'codate';
        } elseif ($sort_column == '4') {
        	$orderColumn = 'coexpdate';
        }				
		$response = $this->client->request('GET','contracts/list',[
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
            $record[] = $v['cono'];
            $record[] = $v['prcode'];
            $record[] = $v['codate'];
            $record[] = $v['coexpdate'];
			$prcode = $v['prcode'];
			$btn_list .='<a href="'.site_url('contract/view/'.$prcode).'" id="" class="btn btn-xs btn-primary btn-table" data-praid="">view</a>&nbsp;';						
			$btn_list .='<a href="'.site_url('contract/edit/'.$prcode).'" id="edit" class="btn btn-xs btn-success btn-table">edit</a>&nbsp;';
			// $btn_list .='<a href="#" class="btn btn-xs btn-info btn-table" data-praid="">print</a>&nbsp;';	
			$btn_list .='<a href="#" id="deleteRow_'.$no.'" class="btn btn-xs btn-danger btn-table delete" data-kode="'.$prcode.'">delete</a>';			
            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);			
	}

	public function view($code)
	{
		// idContract => prcode

		check_exp_time();
		$data['page_title'] = "Contract";
		$data['page_subtitle'] = "Contract Page";		
		$response = $this->client->request('GET','contracts/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'idContract' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		$data['act'] = "view";
		return view('Modules\Contract\Views\add',$data);
	}	

	public function add()
	{
		check_exp_time();
		$data = [];

		if ($this->request->isAJAX()) 
		{		
		    if ($this->request->getMethod() === 'post')
		    {
				$format_date = [
					'codate' => date('Y-m-d',strtotime($_POST['codate'])),
					'coexpdate' => date('Y-m-d',strtotime($_POST['coexpdate']))  
				];

				$response = $this->client->request('POST','contracts/create',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => array_replace($_POST,$format_date)
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Berhasil menyimpan data');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = "Form Error";
		    	echo json_encode($data);die();			
			}			
		}		
		$data['act'] = "add";
		$data['page_title'] = "Contract";
		$data['page_subtitle'] = "Contract Page";
		return view('Modules\Contract\Views\add',$data);		
	}	

	public function edit($code)
	{
		check_exp_time();
		$data['page_title'] = "Contract";
		$data['page_subtitle'] = "Contract Page";

		if ($this->request->isAJAX()) 
		{

		    if ($this->request->getMethod() === 'post')
		    {
				$format_date = [
					'codate' => date('Y-m-d',strtotime($_POST['codate'])),
					'coexpdate' => date('Y-m-d',strtotime($_POST['coexpdate']))  
				];

				$response = $this->client->request('POST','contracts/update',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => array_replace($_POST,$format_date)
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
		    	$data['message'] = "Lengkapi data";
		    	echo json_encode($data);die();			
			}			
		}		

		$response = $this->client->request('GET','contracts/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'idContract' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	

		$data['act'] = "edit";
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Contract\Views\add',$data);		
	}	

	public function delete($code)
	{
		check_exp_time();
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','contracts/delete',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'idContract' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Contract Deleted.');
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
}