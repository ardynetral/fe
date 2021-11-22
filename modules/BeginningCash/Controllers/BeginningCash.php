<?php
namespace Modules\BeginningCash\Controllers;

class BeginningCash extends \CodeIgniter\Controller
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
		// order pra
		// if($group_id == 4) {
		// 	$response1 = $this->client->request('GET','orderPras/getAllData',[
		// 		'headers' => [
		// 			'Accept' => 'application/json',
		// 			'Authorization' => session()->get('login_token')
		// 		],
		// 		'query' => [
		// 			'offset' => $offset,
		// 			'limit'	=> $limit
		// 		]
		// 	]);

		// 	$result_pra = json_decode($response1->getBody()->getContents(),true);	

		// 	$data['data'] = isset($result_pra['data']['datas'])?$result_pra['data']['datas']:"";
		// } else {
		// 	$response1 = $this->client->request('GET','orderPras/getAllData',[
		// 		'headers' => [
		// 			'Accept' => 'application/json',
		// 			'Authorization' => session()->get('login_token')
		// 		],
		// 		'query' => [
		// 			'offset' => $offset,
		// 			'limit'	=> $limit,
		// 			// 'userId'	=> $user_id
		// 		]
		// 	]);		
		// 	$result_pra = json_decode($response1->getBody()->getContents(),true);	
		// 	$datas = isset($result_pra['data']['datas'])?$result_pra['data']['datas']:"";

		// 	// Jika EMKL (User_group==1)
		// 	$data_pra = array();
		// 	if(($datas !="") && ($group_id==1)) {
		// 		foreach($datas as $dt) {
		// 			if($user_id==$dt['crtby']) {
		// 				$data_pra[] = $dt;
		// 			}
		// 		}
		// 	// jika Principal
		// 	} else if(($datas !="") && ($group_id==2)) {
		// 		foreach($datas as $dt) {
		// 			if($prcode==$dt['cpopr']) {
		// 				$data_pra[] = $dt;
		// 			}
		// 		}
		// 	}	

		// 	$data['data'] = $data_pra;
		// }
		$data['data'] = "";

		// $data['prcode'] = $prcode;
		// $data['cucode'] = $prcode;
		// echo dd($data);
		// return view('Modules\PraIn\Views\tab_base',$data);
		$data['page_title'] = "Beginning Cash";
		$data['page_subtitle'] = "Beginning Cash Page";
		return view('Modules\BeginningCash\Views\index',$data);
	}
}