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