<?php
namespace Modules\WorkOrder\Controllers;

class WorkOrder extends \CodeIgniter\Controller
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
		$response = $this->client->request('GET','workorder/getAll',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'start' => (int)$offset,
					'rows'	=> (int)$limit
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
            $record[] = $v['WONO'];
            $record[] = $v['WOOPR'];
            $record[] = $v['WODATE'];
			
			$btn_list .= '<a href="#" id="" class="btn btn-xs btn-primary btn-tbl" data-praid="'.$v['praid'].'">view</a>';

			if(has_privilege_check($module, '_update'==true):
				$btn_list .= '<a href="#" id="editPraIn" class="btn btn-xs btn-success btn-tbl">edit</a>';
			endif;
			
			if(has_privilege_check($module, '_printpdf')==true):
				$btn_list .= '<a href="#" class="btn btn-xs btn-info btn-tbl" data-praid="'.$v['praid'].'">print</a>';
			endif;

			if(has_privilege_check($module, '_delete')==true):
				$btn_list .= '<a href="#" id="deletePraIn" class="btn btn-xs btn-danger btn-tbl">delete</a>';
			endif;

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
		define("has_WorkOrder", has_privilege_check($module, '_approval'));
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

		$response1 = $this->client->request('GET','workorder/getAll',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => $offset,
				'limit'	=> $limit
			]
		]);

		$result_pra = json_decode($response1->getBody()->getContents(),true);	

		$data['data'] = isset($result_pra['data']['datas'])?$result_pra['data']['datas']:"";

		$data['page_title'] = "Work Order";
		$data['page_subtitle'] = "Work Order Page";
		return view('Modules\WorkOrder\Views\index',$data);
	}

	public function add()
	{
		$data = [];
		$data['act'] = "Add";
		$data['page_title'] = "Work Order";
		$data['page_subtitle'] = "Work Order Page";		
		return view('Modules\WorkOrder\Views\add',$data);
	}	
}