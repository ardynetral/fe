<?php
namespace Modules\Approval\Controllers;

use App\Libraries\MyPaging;

class Approval extends \CodeIgniter\Controller
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
		// $data = [];
		// $offset=0;
		// $limit=100;

		// $response1 = $this->client->request('GET','approval/getAll',[
		// 	'headers' => [
		// 		'Accept' => 'application/json',
		// 		'Authorization' => session()->get('login_token')
		// 	],
		// 	'query' => [
		// 		'offset' => $offset,
		// 		'limit'	=> $limit
		// 	]
		// ]);

		// $result_pra = json_decode($response1->getBody()->getContents(),true);	

		// $data['data'] = isset($result_pra['data']['datas'])?$result_pra['data']['datas']:"";

		$data = [];
		$paging = new MyPaging();
		$limit = 10;
		$endpoint = 'dataListReports/listAllApprovals';
		$data['data'] = $paging->paginate($endpoint,$limit,'approval');
		$data['pager'] = $paging->pager;
		$data['nomor'] = $paging->nomor($this->request->getVar('page_approval'), $limit);

		$data['page_title'] = "Approval";
		$data['page_subtitle'] = "Approval Page";
		return view('Modules\Approval\Views\index',$data);
	}

	public function add()
	{
		$data = [];
		$data['data'] = "";
		$data['act'] = "Add";
		$data['page_title'] = "Approval";
		$data['page_subtitle'] = "Approval Page";
		return view('Modules\Approval\Views\add',$data);
	}
}