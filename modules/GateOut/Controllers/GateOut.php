<?php
namespace Modules\GateOut\Controllers;

use App\Libraries\MyPaging;

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
		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "")?$this->request->getPost('search'):"";
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
					'limit'	=> $limit
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
			
			$btn_list .='<a href="#" id="" class="btn btn-xs btn-primary btn-table" data-praid="">view</a>';						
			$btn_list .='<a href="#" id="editPraIn" class="btn btn-xs btn-success btn-table">edit</a>';
			$btn_list .='<a href="#" class="btn btn-xs btn-info btn-table" data-praid="">print</a>';	
			$btn_list .='<a href="#" id="deleteRow_'.$no.'" class="btn btn-xs btn-danger btn-table">delete</a>';			
            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);
		
	}

	public function add()
	{
		$data = [];
		$data['act'] = "Add";
		$data['page_title'] = "Gate Out";
		$data['page_subtitle'] = "Gate Out Page";		
		return view('Modules\GateOut\Views\add',$data);
	}	
}