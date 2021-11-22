<?php
namespace Modules\Survey\Controllers;

use App\Libraries\MyPaging;

class Survey extends \CodeIgniter\Controller
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
		$endpoint = 'dataListReports/listAllSurveis';
		$data['data'] = $paging->paginate($endpoint,$limit,'survey');
		$data['pager'] = $paging->pager;
		$data['nomor'] = $paging->nomor($this->request->getVar('page_survey'), $limit);		
		$data['page_title'] = "Survey";
		$data['page_subtitle'] = "Survey Page";
		return view('Modules\Survey\Views\index',$data);
	}

	public function add()
	{
		$data = [];
		$data['act'] = "Add";
		$data['page_title'] = "Survey";
		$data['page_subtitle'] = "Survey Page";		
		return view('Modules\Survey\Views\add',$data);
	}		
}