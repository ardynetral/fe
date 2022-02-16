<?php

namespace Modules\ContainerSearch\Controllers;

class ContainerSearch extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url', 'form', 'app']);
		$this->client = api_connect();
	}

	public function index()
	{
		check_exp_time();
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view");
		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];
		$data = [];
		$offset = 0;
		$limit = 100;

		if (($group_id == 1) || ($group_id == 2)) {
			$data['prcode'] = $prcode;
			$data['cucode'] = $prcode;
		} else {
			$data['prcode'] = '0';
			$data['cucode'] = '0';
		}
		$data['group_id'] = $group_id;

		$data['data'] = "";
		$data['page_title'] = "Container Search";
		$data['page_subtitle'] = "Container Search";
		return view('Modules\ContainerSearch\Views\index', $data);
	}

	public function getContainer()
	{
		$crno = $_POST['crno'];


		$getContainer = $this->client->request('GET', 'containers/containerSearch', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'crno' => $crno
			]
		]);
		$result = json_decode($getContainer->getBody()->getContents(), true);
		//print_r($result);
		//die();
		echo json_encode($result['data'][0]);
	}


	public function reportPdf()
	{

		$crno = $_POST['crno'];


		$getContainer = $this->client->request('GET', 'containers/containerSearch', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'crno' => $crno
			]
		]);
		$result = json_decode($getContainer->getBody()->getContents(), true);
		$hasil =  json_encode($result['data'][0]);
		print_r($hasil);
		die();
	}

	public function history()
	{
		check_exp_time();
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view");
		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];
		$data = [];
		$offset = 0;
		$limit = 100;

		if (($group_id == 1) || ($group_id == 2)) {
			$data['prcode'] = $prcode;
			$data['cucode'] = $prcode;
		} else {
			$data['prcode'] = '0';
			$data['cucode'] = '0';
		}
		$data['group_id'] = $group_id;

		$data['data'] = "";
		$data['page_title'] = "Container Search";
		$data['page_subtitle'] = "Container Search";
		return view('Modules\ContainerSearch\Views\history', $data);
	}
}
