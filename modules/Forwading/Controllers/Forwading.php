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
		// $prcode = $token['prcode'];

		$data = [];
		$offset=0;
		$limit=100;

		// $response = $this->client->request('GET','companies/list',[
		// 	'headers' => [
		// 		'Accept' => 'application/json',
		// 		'Authorization' => session()->get('login_token')
		// 	]
		// ]);

		// $result = json_decode($response->getBody()->getContents(),true);	

		// $data['data'] = isset($result['data']['datas'])?$result['data']['datas']:"";


		// $data['prcode'] = $prcode;
		// $data['cucode'] = $prcode;
		// echo dd($data);
		// return view('Modules\PraIn\Views\tab_base',$data);
		$data['data'] = "";
		$data['page_title'] = "Forwading/EMKL";
		$data['page_subtitle'] = "Forwading/EMKL Page";
		return view('Modules\Forwading\Views\index',$data);
	}
}