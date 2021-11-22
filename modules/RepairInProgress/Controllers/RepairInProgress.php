<?php
namespace Modules\RepairInProgress\Controllers;

use App\Libraries\MyPaging;

class RepairInProgress extends \CodeIgniter\Controller
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
		// $prcode = $token['prcode'];

		$data = [];
		$paging = new MyPaging();
		$limit = 10;
		$endpoint = 'dataListReports/listAllMnRs';
		$data['data'] = $paging->paginate($endpoint,$limit,'mnr');
		$data['pager'] = $paging->pager;
		$data['nomor'] = $paging->nomor($this->request->getVar('page_mnr'), $limit);
		$data['page_title'] = "Repair In Progress";
		$data['page_subtitle'] = "Repair In Progress Page";
		return view('Modules\RepairInProgress\Views\index',$data);
	}

	public function add()
	{
		$data = [];
		$data['act'] = "Add";
		$data['page_title'] = "Repair In Progress";
		$data['page_subtitle'] = "Repair In Progress Page";		
		return view('Modules\RepairInProgress\Views\add',$data);
	}		
}