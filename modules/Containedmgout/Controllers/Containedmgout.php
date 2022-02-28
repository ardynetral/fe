<?php

namespace Modules\Containedmgout\Controllers;

class Containedmgout extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url', 'form', 'app']);
		$this->client = api_connect();
	}

	function list_data()
	{
		$search = ($this->request->getPost('search[value]') != "") ? $this->request->getPost('search[value]') : "";
		$offset = ($this->request->getPost('start') != 0) ? $this->request->getPost('start') : 0;
		$limit = ($this->request->getPost('rows') != "") ? $this->request->getPost('rows') : 10;
		$sort_column = $this->request->getPost('order[0][column]');
		$sort_type = $this->request->getPost('order[0][dir]');
		$orderColumn = '';
		/*
        if ($sort_column == '0') {
        	$orderColumn = '';
        } elseif ($sort_column == '1') {
        	$orderColumn = 'vesid';
        } elseif ($sort_column == '2') {
        	$orderColumn = 'vestitle';
        } elseif ($sort_column == '3') {
        	$orderColumn = 'vesopr';
        } 
		*/

		// PULL data from API
		$response = $this->client->request('GET', 'containerHold/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => (int)$offset,
				'rows'	=> (int)$limit,
				'search' => (string)$search,
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
		$no = ($offset != 0) ? $offset + 1 : 1;
		foreach ($result['data']['datas'] as $k => $v) {
			$btn_list = "";
			$record = array();
			$record[] = $no;
			$record[] = $v['vesid'];
			$record[] = $v['vestitle'];
			$record[] = $v['vesopr'];
			$record[] = $v['cncode'];
			$record[] = $v['prcode'];

			$btn_list .= '<a href="' . site_url() . '/Containedmgout/view/' . $v['vesid'] . '" class="btn btn-xs btn-primary btn-tbl">View</a>';
			$btn_list .= '<a href="' . site_url() . '/Containedmgout/edit/' . $v['vesid'] . '" class="btn btn-xs btn-success btn-tbl">Edit</a>';
			$btn_list .= '<a href="#" class="btn btn-xs btn-danger delete btn-tbl" id="delete" data-kode="' . $v['vesid'] . '">Delete</a>';
			$record[] = '<div>' . $btn_list . '</div>';
			$no++;

			$output['data'][] = $record;
		}

		echo json_encode($output);
	}

	public function index()
	{
		$data = [];
		$response = $this->client->request('GET', 'containerHold/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => 0,
				'rows'  => 500,
				'search' => "",
				'orderColumn' => "crno",
				'orderType' => "ASC"
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		// dd($result);
		$data['data'] = isset($result['data']['datas']) ? $result['data']['datas'] : "";
		return view('Modules\Containedmgout\Views\index', $data);
	}

	public function view($code)
	{
		/*
		$response = $this->client->request('GET','containerHold/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'id' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Containedmgout\Views\view',$data);
		*/
	}

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) {

			$chorderno = $this->request->getPost('chorderno');
			$crno = $this->request->getPost('crno');
			$chtype = $this->request->getPost('chtype');
			$chfrom = $this->request->getPost('chfrom');
			$chto = $this->request->getPost('chto');
			$chnote = $this->request->getPost('chnote');

			$validate = $this->validate([
				'chorderno' 	=> 'required',
				'crno'  => 'required'
			]);


			if ($this->request->getMethod() === 'post' && $validate) {

				$response = $this->client->request('POST', 'containerHold/insertData', [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [

						'chorderno' => $chorderno,
						'crno' => $crno,
						'chtype' => $chtype,
						'chfrom' => $chfrom,
						'chto' => $chto,
						'chnote' => $chnote,

					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);

				if (isset($result['status']) && ($result['status'] == "Failled")) {
					$data['message'] = $result['message'];
					echo json_encode($data);
					die();
				}

				session()->setFlashdata('sukses', 'Success,  Saved.');
				$data['message'] = "success";
				echo json_encode($data);
				die();
			} else {
				$data['message'] = \Config\Services::validation()->listErrors();
				echo json_encode($data);
				die();
			}
		}


		return view('Modules\Containedmgout\Views\add', $data);
	}

	public function delete($code)
	{
		if ($this->request->isAJAX()) {
			$response = $this->client->request('DELETE', 'Containedmgouts/deleteConHold', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'id' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);
			if (isset($result['status']) && ($result['status'] == "Failled")) {
				$data['message'] = $result['message'];
				echo json_encode($data);
				die();
			}

			session()->setFlashdata('sukses', 'Success, Containedmgout ' . $code . ' Deleted.');
			$data['message'] = "success";
			echo json_encode($data);
			die();
		}
	}
}
