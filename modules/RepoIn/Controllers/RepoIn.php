<?php

namespace Modules\RepoIn\Controllers;

use App\Libraries\Ciqrcode;
use function bin2hex;
use function file_exists;
use function mkdir;

class RepoIn extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url', 'form', 'app', 'repoin']);
		$this->client = api_connect();
	}

	function list_data()
	{
		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "") ? $this->request->getPost('search') : "";
		$offset = ($this->request->getPost('start') != 0) ? $this->request->getPost('start') : 0;
		$limit = ($this->request->getPost('rows') != "") ? $this->request->getPost('rows') : 10;
		// $sort_dir = $this->get_sort_dir();		
		// PULL data from API
		$response = $this->client->request('GET', 'orderContainerRepos/getAllData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => (int)$offset,
				'limit'	=> (int)$limit
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		// print_r($result);die();
		$output = array(
			"draw" => $this->request->getPost('draw'),
			"recordsTotal" => @$result['data']['count'],
			"recordsFiltered" => @$result['data']['count'],
			"data" => array()
		);
		$no = ($offset != 0) ? $offset + 1 : 1;
		foreach ($result['data']['datas'] as $k => $v) {
			$reorderno = $v['reorderno'];
			$btn_list = "";
			$record = array();
			$record[] = $no;
			$record[] = $v['reorderno'];
			$record[] = date('d-m-Y', strtotime($v['redate']));
			$record[] = $v['cpopr'];
			$record[] = $v['recpives'];
			$record[] = $v['recpivoyid'];

			$btn_list .= '<a href="' . site_url() . '/repoin/view/' . $reorderno . '" class="btn btn-xs btn-primary btn-tbl">Cetak kitir</a>';
			$btn_list .= '<a href="' . site_url() . '/repoin/edit/' . $reorderno . '" class="btn btn-xs btn-success btn-tbl">Edit</a>';
			// $btn_list .= '<a href="' . site_url() . '/repoin/proforma/' . $reorderno . '" class="btn btn-xs btn-success btn-tbl">Proforma</a>';
			// $btn_list .= '<a href="#" class="btn btn-xs btn-success btn-tbl">Invoice</a>';
			// $btn_list .= '<a href="#" data-repoid="'.$v['reorderno'].'" class="btn btn-xs btn-info print_order btn-tbl">Print Kitir</a>';
			$btn_list .= '<a href="#" id="" class="btn btn-xs btn-danger btn-tbl delete" data-kode="' . $reorderno . '">Delete</a>';
			$record[] = '<div>' . $btn_list . '</div>';
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
		define("has_approval", has_privilege_check($module, '_approval'));
		define("has_edit", has_privilege_check($module, '_update'));
		define("has_delete", has_privilege_check($module, '_delete'));
		define("has_print", has_privilege_check($module, '_printpdf'));

		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];
		$data = [];
		$data['prcode'] = $prcode;
		$data['cucode'] = $prcode;
		$data['page_title'] = "Order Repo Pra In";
		$data['page_subtitle'] = "Order Repo Pra In Page";
		return view('Modules\RepoIn\Views\index', $data);
	}

	public function view($reorderno)
	{
		check_exp_time();

		$datarepo = $this->getOneRepo($reorderno);
		$data['data'] = $datarepo['data'];
		$data['containers'] = $this->getRepoContainers($datarepo['data']['repoid']);
		// echo var_dump($data['data']);die();
		$data['from_depo_dropdown'] = $this->depo_dropdown("retfrom","DEPO CONTINDO");
		$data['from_port_dropdown'] = $this->port_dropdown("retfrom",$datarepo['data']['replace1']);
		$data['from_city_dropdown'] = $this->city_dropdown("retfrom",$datarepo['data']['replace1']);
		$data['to_depo_dropdown'] = $this->depo_dropdown("retto","DEPO CONTINDO");
		$data['to_port_dropdown'] = $this->port_dropdown("retto",$datarepo['data']['retto']);
		$data['to_city_dropdown'] = $this->city_dropdown("retto",$datarepo['data']['retto']);			
		$data['QTY'] = hitungHCSTD($this->getRepoContainers($datarepo['data']['repoid']));
		$data['recity'] = $this->getCity($datarepo['data']['recity']);
		return view('Modules\RepoIn\Views\view', $data);
	}

	public function add()
	{
		check_exp_time();
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view");
		define("has_insert", has_privilege_check($module, '_insert'));
		define("has_approval", has_privilege_check($module, '_approval'));
		define("has_edit", has_privilege_check($module, '_update'));
		define("has_delete", has_privilege_check($module, '_delete'));
		define("has_print", has_privilege_check($module, '_printpdf'));

		$offset = 0;
		$limit = 100;
		$token = get_token_item();
		$group_id = $token['groupId'];

		if ($this->request->isAJAX()) {

			$form_params = [];

			if($_POST['retype']=="21") {
				$retfrom = "DEPO";
				$retto = "DEPO";
			} else if($_POST['retype']=="22") {
				$retfrom = "PORT";
				$retto = "DEPO";
			} else if($_POST['retype']=="23") {
				$retfrom = "CITY";
				$retto = "DEPO";
			}
			$reformat = [
				'reorderno' => $this->get_repoin_number(),
				'redate' => date('Y-m-d', strtotime($_POST['redate'])),
				'redline' => date('Y-m-d', strtotime($_POST['redline'])),
				'retfrom' => $retfrom,
				'retto' => $retto,
				'replace1' => $_POST['retfrom']
			];
			$form_params = array_replace($_POST, $reformat);
			if ($this->request->getMethod() === 'post') {

				$response = $this->client->request('POST', 'orderContainerRepos/createNewData', [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params,
				]);

				$result = json_decode($response->getBody()->getContents(), true);
				if (isset($result['status']) && ($result['status'] == "Failled")) {
					$data['message'] = $result['message'];
					echo json_encode($data);
					die();
				}
				$datarepo = $result['data'];
				session()->setFlashdata('sukses', 'Success, Order Repo Saved.');
				$data['message'] = "success";
				$data['repoid'] = $datarepo['repoid'];
				echo json_encode($data);
				die();
			} else {
				$data['message'] = "Lengkapi form";
				echo json_encode($data);
				die();
			}
		}

		// order pra container
		$response2 = $this->client->request('GET', 'orderContainerRepos/getAllData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => $offset,
				'limit'	=> $limit
			]
		]);

		$data['act'] = "add";
		$data['repoin_no'] = $this->get_repoin_number();
		$result_container = json_decode($response2->getBody()->getContents(), true);
		// $data['data_container'] = isset($result_container['data']['datas'])?$result_container['data']['datas']:"";
		$data['from_depo_dropdown'] = $this->depo_dropdown("retfrom","DEPO CONTINDO");
		$data['from_port_dropdown'] = $this->port_dropdown("retfrom","");
		$data['from_city_dropdown'] = $this->city_dropdown("retfrom","");
		$data['to_depo_dropdown'] = $this->depo_dropdown("retto","DEPO CONTINDO");
		$data['to_port_dropdown'] = $this->port_dropdown("retto","");
		$data['to_city_dropdown'] = $this->city_dropdown("retto","");	
		return view('Modules\RepoIn\Views\add', $data);
	}
	public function update_new_data()
	{
		check_exp_time();
		// print_r($_POST);die();
		if ($this->request->isAJAX()) {
			$response = $this->client->request('PUT', 'orderContainerRepos/updateData', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $_POST,
			]);

			$result = json_decode($response->getBody()->getContents(), true);
			if (isset($result['status']) && ($result['status'] == "Failled")) {
				$data['message'] = $result['message'];
				echo json_encode($data);
				die();
			}
			session()->setFlashdata('sukses', 'Success, Order Repo Saved.');
			$data['message'] = "success";
			echo json_encode($data);
			die();
		}
	}

	public function addcontainer()
	{
		check_exp_time();
		// $data = [];
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		// echo var_dump( $_POST);die();
		if ($this->request->isAJAX()) {
			$form_params = [
				//Header
				'repoid' => $_POST['repoid'],
				'cpiorderno' => $_POST['cpiorderno'],
				'cpopr' => $_POST['cpopr'],
				'cpcust' => $_POST['cpcust'],
				'cpidish' => $_POST['cpidish'],
				'cpdepo' => $_POST['cpdepo'],
				'cpichrgbb' => $_POST['cpichrgbb'],
				'cpipratgl' => date('Y-m-d', strtotime($_POST['cpipratgl'])),
				'cpijam' => $_POST['cpijam'],
				'cpives' => $_POST['recpives'],
				'cpiremark' => $_POST['cpiremark'],
				'cpideliver' => $_POST['repovendor'],
				'cpivoyid' => $_POST['recpivoyid'],
				'cpivoy' => $_POST['cpivoy'],
				// detail
				'crno' => $_POST['crno'],
				'cccode' => $_POST['cccode'],
				'ctcode' => $_POST['ctcode'],
				'cclength' => $_POST['cclength'],
				'ccheight' => $_POST['ccheight'],
				'cpife' => $_POST['cpife'],
				'cpishold' => $_POST['cpishold'],
				// 'reporemark' => $_POST['reporemark']
			];

			// echo var_dump($_POST);die();

			$validate = $this->validate([
				'crno' 	=> 'required'
			]);

			if ($this->request->getMethod() === 'post' && $validate) {

				$response = $this->client->request('POST', 'repoin/insertDataPraRepoInDetails', [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params,
				]);

				$result = json_decode($response->getBody()->getContents(), true);
				// echo var_dump($result);die();
				if (isset($result['status']) && ($result['status'] == "Failled")) {
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);
					die();
				}
				// "message": "Data available!"
				if (isset($result['message']) && ($result['message'] == "Data available!")) {
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);
					die();
				}

				session()->setFlashdata('sukses', 'Success, Containers Saved.');
				$data['status'] = "success";
				$data['message'] = $result['message'];
				$data['containers'] = $this->getRepoContainers($_POST['repoid']);
				$data['QTY'] = hitungHCSTD($this->getRepoContainers($_POST['repoid']));
				echo json_encode($data);
				die();
			} else {
				$data['message'] = \Config\Services::validation()->listErrors();
				echo json_encode($data);
				die();
			}
		}
	}

	public function updatecontainer()
	{
		check_exp_time();
		// $data = [];
		$token = get_token_item();
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		// echo var_dump( $_POST);die();
		if ($this->request->isAJAX()) {
			$form_params = [
				//Header
				'repoid' => $_POST['repoid'],
				'cpiorderno' => $_POST['cpiorderno'],
				'cpopr' => $_POST['cpopr'],
				'cpcust' => $_POST['cpcust'],
				'cpidish' => $_POST['cpidish'],
				'cpdepo' => $_POST['cpdepo'],
				'cpichrgbb' => $_POST['cpichrgbb'],
				'cpipratgl' => date('Y-m-d', strtotime($_POST['cpipratgl'])),
				'cpijam' => $_POST['cpijam'],
				'cpives' => $_POST['recpives'],
				'cpiremark' => $_POST['cpiremark'],
				'cpideliver' => $_POST['cpideliver'],
				'cpivoyid' => $_POST['recpivoyid'],
				'cpivoy' => $_POST['cpivoy'],
				// detail
				'crno' => $_POST['crno'],
				'cccode' => $_POST['cccode'],
				'ctcode' => $_POST['ctcode'],
				'cclength' => $_POST['cclength'],
				'ccheight' => $_POST['ccheight'],
				'cpife' => $_POST['cpife'],
				'cpishold' => $_POST['cpishold'],
				'reporemark' => $_POST['reporemark']
			];

			// echo var_dump($_POST);die();

			$validate = $this->validate([
				'crno' 	=> 'required'
			]);

			if ($this->request->getMethod() === 'post' && $validate) {

				$response = $this->client->request('PUTT', 'repoin/updateDataRepoInDetails', [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params,
				]);

				$result = json_decode($response->getBody()->getContents(), true);
				// echo var_dump($result);die();
				if (isset($result['status']) && ($result['status'] == "Failled")) {
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);
					die();
				}
				// "message": "Data available!"
				if (isset($result['message']) && ($result['message'] == "Data available!")) {
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);
					die();
				}

				session()->setFlashdata('sukses', 'Success, Containers Saved.');
				$data['status'] = "success";
				$data['message'] = $result['message'];
				$data['containers'] = $this->getRepoContainers($_POST['repoid']);
				$data['QTY'] = hitungHCSTD($this->getRepoContainers($_POST['repoid']));
				echo json_encode($data);
				die();
			} else {
				$data['message'] = \Config\Services::validation()->listErrors();
				echo json_encode($data);
				die();
			}
		}
	}
	public function edit($reorderno)
	{
		check_exp_time();

		if ($this->request->isAJAX()) {

			$reformat = [
				'redate' => date('Y-m-d', strtotime($_POST['redate'])),
				'redline' => date('Y-m-d', strtotime($_POST['redline']))
			];

			if ($this->request->getMethod() === 'post') {

				$response = $this->client->request('PUT', 'orderContainerRepos/updateData', [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => array_replace($_POST, $reformat),
				]);

				$result = json_decode($response->getBody()->getContents(), true);
				if (isset($result['status']) && ($result['status'] == "Failled")) {
					$data['message'] = $result['message'];
					echo json_encode($data);
					die();
				}
				session()->setFlashdata('sukses', 'Success, Order Repo Updated.');
				$data['message'] = "success";
				// $data['repoid'] = $result['data']['datas']['repoid']; 
				echo json_encode($data);
				die();
			} else {
				$data['message'] = "Lengkapi form";
				echo json_encode($data);
				die();
			}
		}

		$datarepo = $this->getOneRepo($reorderno);
		$data['data'] = $datarepo['data'];
		$data['from_depo_dropdown'] = $this->depo_dropdown("retfrom","DEPO CONTINDO");
		$data['from_port_dropdown'] = $this->port_dropdown("retfrom",$datarepo['data']['replace1']);
		$data['from_city_dropdown'] = $this->city_dropdown("retfrom",$datarepo['data']['replace1']);
		$data['to_depo_dropdown'] = $this->depo_dropdown("retto","DEPO CONTINDO");
		$data['to_port_dropdown'] = $this->port_dropdown("retto",$datarepo['data']['retto']);
		$data['to_city_dropdown'] = $this->city_dropdown("retto",$datarepo['data']['retto']);		
		$data['repoid'] = $datarepo['data']['repoid'];
		$data['containers'] = $this->getRepoContainers($datarepo['data']['repoid']);
		$data['QTY'] = hitungHCSTD($this->getRepoContainers($datarepo['data']['repoid']));
		// echo var_dump($data['containers']);die();
		return view('Modules\RepoIn\Views\edit', $data);
	}

	public function proforma($reorderno)
	{
		check_exp_time();
		$datarepo = $this->getOneRepo($reorderno);
		$data['data'] = $datarepo['data'];
		$data['repoid'] = $datarepo['data']['repoid'];
		$data['containers'] = $this->getRepoContainers($datarepo['data']['repoid']);
		return view('Modules\RepoIn\Views\proforma', $data);
	}

	public function delete($code)
	{
		check_exp_time();
		if ($this->request->isAJAX()) {
			$response = $this->client->request('DELETE', 'orderContainerRepos/deleteData', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'reorderno' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);
			if (isset($result['status']) && ($result['status'] == "Failled")) {
				$data['message'] = $result['message'];
				echo json_encode($data);
				die();
			}

			session()->setFlashdata('sukses', 'Data berhasil dihapus');
			$data['message'] = "success";
			echo json_encode($data);
			die();
		}
	}

	public function getOneRepo($reorderno)
	{
		$response = $this->client->request('GET', 'orderContainerRepos/getDetailData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'reorderno' => $reorderno,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		if (isset($result['status']) && ($result['status'] == "Failled")) {
			$data['data'] = "";
		}

		$data['data'] = $result['data'];

		return $data;
	}

	public function delete_container($code)
	{
		check_exp_time();
		if ($this->request->isAJAX()) {
			$response = $this->client->request('DELETE', 'orderRepoContainer/deleteData', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'repocrnoid' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);
			if (isset($result['status']) && ($result['status'] == "Failled")) {
				$data['message'] = $result['message'];
				echo json_encode($data);
				die();
			}

			session()->setFlashdata('sukses', 'Data berhasil dihapus');
			$data['message'] = "success";
			$data['QTY'] = hitungHCSTD($this->getRepoContainers($_POST['repoid']));
			echo json_encode($data);
			die();
		}
	}

	public function getRepoContainers($repoid)
	{
		// get OrderPraContainer
		$response = $this->client->request('GET', 'orderRepoContainer/getAllData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'repoid' => $repoid,
				'offset' => 0,
				'limit' => 100,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		if (isset($result['data'])&& $result['data']['count'] > 0) {
			$datas = $result['data']['datas'];
		} else {
			$datas = "";
		}
		return $datas;
	}

	public function getOneRepoContainer($repocrnoid)
	{
		$response = $this->client->request('GET', 'orderRepoContainer/getDetailData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'repocrnoid' => $repocrnoid,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		if (isset($result['status']) && ($result['status'] == "Failled")) {
			$datas = "";
		} else {
			$datas = $result['data'];
		}

		echo json_encode($datas);
		die();
	}

	public function ajax_country()
	{
		$response = $this->client->request('GET', 'country/listOne', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'cityId' => $cccode,
			]
		]);
		$result = json_decode($response->getBody()->getContents(), true);

		if ($this->request->isAJAX()) {
			echo json_encode($result['data']);
			die();
		}

		return $result['data'];
	}

	public function country_dropdown($selected = "")
	{
		$data = [];
		$response = $this->client->request('GET', 'countries/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => 0,
				'rows' => 100
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		$res = $result['data'];
		$option = "";
		$option .= '<select name="cncode" id="cncode" class="select-cncode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['cncode'] . "'" . ((isset($selected) && $selected == $r['cncode']) ? ' selected' : '') . ">" . $r['cndesc'] . "</option>";
		}
		$option .= "</select>";
		return $option;
		die();
	}

	public function checkContainerNumber()
	{
		/*
		return:
		- 
		*/
		$data = [];

		if ($this->request->isAjax()) {

			$ccode = $_POST['ccode'];
			// $ccode = "APZU";
			$validate = $this->validate([
				'ccode' => 'required'
			]);

			if ($this->request->getMethod() === 'post' && $validate) {
				$response = $this->client->request('GET', 'containers/checkcCode', [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'query' => [
						'cContainer' => $ccode,
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);
				if (isset($result['success']) && ($result['success'] == false)) {
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);
					die();
				} else {

					$container = $result['data']['rows'][0];
					// echo var_dump($container['crlastact']);die();
					if (($container['crlastact'] == "BI") or ($container['crlastact'] == "OD") or ($container['crlastact'] == NULL)) {
						$data['status'] = "Success";
						$data['message'] = $result['message'];
						$data['data'] = $container;
						$data['container_code'] = $data['data']['container_code'];
						echo json_encode($data);
						die();
					} else {
						$data['status'] = "Failled";
						$data['message'] = "Container sudah masuk depo";
						echo json_encode($data);
						die();
					}
				}
			} else {

				$data['status'] = "Failled";
				$data['message'] = \Config\Services::validation()->listErrors();;
				echo json_encode($data);
				die();
			}
		}
	}

	public function ajax_ccode_listOne($code)
	{
		$data = [];
		if ($this->request->isAjax()) {
			$response = $this->client->request('GET', 'containercode/listOne', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'ccCode' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);

			if (isset($result['status']) && ($result['status'] == "Failled")) {
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);
				die();
			}

			$data['status'] = "Success";
			$data['data'] = $result['data'];
			echo json_encode($data);
			die();
		}
	}

	public function ajax_prcode_listOne($code)
	{
		$data = [];
		if ($this->request->isAjax()) {
			$response = $this->client->request('GET', 'principals/listOne', [
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
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);
				die();
			}

			$data['status'] = "Success";
			$data['data'] = $result['data'];
			echo json_encode($data);
			die();
		}
	}

	public function ajax_vessel_listOne($code)
	{
		$data = [];
		if ($this->request->isAjax()) {
			$response = $this->client->request('GET', 'vessels/listOne', [
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
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);
				die();
			}

			$data['status'] = "Success";
			$data['data'] = $result['data'];
			echo json_encode($data);
			die();
		}
	}

	public function get_repoin_number()
	{
		$data = [];
		$response = $this->client->request('GET', 'orderContainerRepos/createOrderRepoNumber', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'repoCode' => 'RI'
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		if (isset($result['success']) && ($result['success'] == true)) {
			$data['status'] = "success";
			return $result['data'];
		}
	}

	// ajax list voyage
	function ajax_voyage_list()
	{
		$api = api_connect();
		$response = $api->request('GET', 'voyages/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		$vessel = $result['data']['datas'];

		$data = [];
		$items = [];

		foreach ($vessel as $v) {
			$items[] = [
				'id' => $v['voyid'],
				'no' => $v['voyno']
			];
		}
		$data['items'] = $items;
		$data['total_count'] = count($items);
		return json_encode($data);
		die();
	}

	public function get_repo_tariff_detail()
	{
		$session = session();

		$contract = get_contract($_POST['prcode']);

		$response = $this->client->request('GET', 'repo_tariff_details/getDetailData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'prcode' => $_POST['prcode'],
				'rttype' => $_POST['retype']
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		if (isset($result['success']) && ($result['success'] == true)) {
			$data['status'] = "success";
			$data['data'] =  $result['data'];
			$data['contract'] =  $contract;
		} else {
			$data['status'] = "Failled";
			$data['data'] = "";
		}

		echo json_encode($data);
		die();
	}

	public function ajax_repo_containers()
	{
		$response = $this->client->request('GET', 'orderRepoContainer/getAllData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'repoid' => $_POST['repoid'],
				'offset' => 0,
				'limit' => 100,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		$i = 1;
		$html = "";
		foreach ($result['data']['datas'] as $row) {
			$repocrnoid = $row['repocrnoid'];
			$html .= "<tr>
				<td>" . $i . "</td>
				<td>" . $row['crno'] . "</td>
				<td>" . $row['cccode'] . "</td>
				<td>" . $row['ctcode'] . "</td>
				<td>" . $row['cclength'] . "</td>
				<td>" . $row['ccheight'] . "</td>
				<td>" . ((isset($row['repofe']) && $row['repofe'] == 1) ? 'Full' : 'Empty') . "</td>
				<td>" . ((isset($row['reposhold']) && $row['reposhold'] == 1) ? 'Hold' : 'Release') . "</td>
				<td>" . $row['reporemark'] . "</td>
				<td>
				<a href='#' class='btn btn-xs btn-danger delete' data-kode='" . $repocrnoid . "'>delete</a></td>";
			$html .= "</tr>";
			$i++;
		}

		echo json_encode($html);
		die();
	}

	public function get_container($crno)
	{
		$response = $this->client->request('GET', 'containers/listOne', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'crNo' => $crno,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		return $result['data'];
	}

	public function check_container($crno)
	{
		$response = $this->client->request('GET', 'containers/listOne', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'crNo' => $crno,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		if (isset($result['status']) && ($result['status'] == "Failled")) {
			$status = 0;
		} else {
			$status = 1;
		}
		return $status;
	}

	// public function cetak_kitir($crno = "", $reorderno = "", $praid = "")
	// {

	// 	$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
	// 	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 236]]);
	// 	// $mpdf->showImageErrors = true;
	// 	$query_params = [
	// 		"crno" => trim($crno),
	// 		"cpiorderno" => trim($reorderno)
	// 	];
	// 	//print_r($query_params);
	// 	$response = $this->client->request('GET', 'containerProcess/getKitirPepoIn', [
	// 		'headers' => [
	// 			'Accept' => 'application/json',
	// 			'Authorization' => session()->get('login_token')
	// 		],
	// 		'query' => $query_params,
	// 	]);

	// 	$result = json_decode($response->getBody()->getContents(), true);
	// 	//print_r($result);		die();
	// 	// $recept = recept_by_praid($praid);
	// 	if (isset($result['data'][0]) && (count($result['data'][0])) > 0) {
	// 		$qrcode = $this->generate_qrcode($result['data'][0]['cpid']);
	// 		$CRNO = $result['data'][0]['crno'];
	// 		$REFIN = $result['data'][0]['cpirefin'];
	// 		$CPID = $result['data'][0]['cpid'];
	// 		$LENGTH = $result['data'][0]['cclength'];
	// 		$HEIGHT = $result['data'][0]['ccheight'];
	// 		$CPIORDERNO = $result['data'][0]['cpiorderno'];
	// 		$TYPE = $result['data'][0]['cccode'];
	// 		$CODE = $result['data'][0]['ctcode'];
	// 		$PRINCIPAL = $result['data'][0]['prcode'];
	// 		$SHIPPER = $result['data'][0]['cpideliver'];
	// 		$VESSEL = $result['data'][0]['vesid'];
	// 		$VOY = $result['data'][0]['cpivoy'];
	// 		$DATE = $result['data'][0]['cpidisdat'];
	// 		$DESTINATION = "";
	// 		$REMARK = $result['data'][0]['cpiremark'];
	// 		$NOPOL = $result['data'][0]['cpinopol'];
	// 		$QRCODE_IMG = ROOTPATH . '/public/media/qrcode/' . $qrcode['content'] . '.png';
	// 		$CPIPRATGL = $result['data'][0]['cpipratgl'];
	// 		$CPIRECEPTNO = $result['data'][0]['cpireceptno'];
	// 		// $QRCODE_CONTENT = $qrcode['content'];
	// 	} else {
	// 		$CRNO = "";
	// 		$CODE = "";
	// 		$CPID = "";
	// 		$REFIN = "";
	// 		$LENGTH = "";
	// 		$HEIGHT = "";
	// 		$CPIORDERNO = "";
	// 		$TYPE = "";
	// 		$PRINCIPAL = "";
	// 		$SHIPPER = "";
	// 		$VESSEL = "";
	// 		$VOY = "";
	// 		$DATE = "";
	// 		$DESTINATION = "";
	// 		$REMARK = "";
	// 		$NOPOL = "";
	// 		$QRCODE_IMG = "";
	// 		$QRCODE_CONTENT = "";
	// 		$CPIRECEPTNO = "";
	// 		$CPIPRATGL = "";
	// 	}

	// 	$result = json_decode($response->getBody()->getContents(), true);

	// 	$barcode = $generator->getBarcode($crno, $generator::TYPE_CODE_128);

	// 	$html = '';

	// 	$html .= '
	// 	<html>
	// 		<head>
	// 			<title>Order PraIn | Print Kitir</title>
	// 			<link href="' . base_url() . '/public/themes/smartdepo/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	// 			<style>			
	// 				.page-header{display:block;margin-bottom:20px;line-height:0.3;}
	// 				table{line-height:1.75;display:block;}
	// 				table td{font-weight:bold;}
	// 				.t-right{text-align:right;}
	// 				.t-left{text-align:left;}
	// 				.t-center{text-align:center;}
	// 				.bordered {
	// 					border:1px solid #666666;
	// 					padding:3px;
	// 				}
	// 				.kotak1{border:1px solid #000000;padding:3px;width:20%;text-align:center;}
	// 				.kotak2{border:1px solid #ffffff;padding:3px;width:20%;text-align:center;}
	// 				.kotak3{border:1px solid #000000;padding:3px;width:20%;text-align:center;}
	// 		        @media print {
	// 		            @page {
	// 		                margin: 0 auto;
	// 		                sheet-size: 300px 250mm;
	// 		            }
	// 		            html {
	// 		                direction: rtl;
	// 		            }
	// 		            html,body{margin:0;padding:0}
	// 		            .wrapper {
	// 		                width: 250px;
	// 		                margin: auto;
	// 		                text-align: justify;
	// 		            }
	// 		           .t-center{text-align: center;}
	// 		           .t-right{text-align: right;}
	// 		        }						
	// 			</style>
	// 		</head>
	// 	';
	// 	$html .= '<body onload="window.print()">
	// 		<div class="wrapper">

	// 		<div class="page-header t-center">
	// 			<h5 style="line-height:0.5;font-weight:bold;padding-top:20px;">KITIR BONGKAR</h3>
	// 			<h4 style="text-decoration: underline;line-height:0.5;">' . $REFIN . '</h3>
	// 			<img src="' . $QRCODE_IMG . '" style="height:120px;">
	// 			<h5 style="text-decoration: underline;line-height:0.5;">' . $CPID . '</h4>
	// 		</div>
	// 	';
	// 	$html .= '
	// 		<table border-spacing: 0; border-collapse: collapse; width="100%">	
	// 			<tr>
	// 				<td colspan="2" style="font-weight:normal;">NO. ' . $CPIORDERNO . '
	// 				</td>
	// 				<td colspan="2" style="font-weight:normal;text-align:right;">( ' . date("d/m/Y", strtotime($CPIPRATGL)) . ' )</td>
	// 			</tr>
	// 			<tr>
	// 				<td style="width:40%;">CONTAINER NO.</td>
	// 				<td colspan="3"> <h5 style="margin:0;padding:0;font-weight:normal;">' . $CRNO . '</h5></td>
	// 			</tr>
	// 			<tr>
	// 				<td>PRINCIPAL</td>
	// 				<td colspan="3">' . $PRINCIPAL . '</td>
	// 			</tr>
	// 			<tr>
	// 				<td>L/OFF</td>
	// 				<td colspan="3">' . $CPIRECEPTNO . '</td>
	// 			</tr>
	// 			<tr>
	// 				<td>SIZE</td>
	// 				<td colspan="3">' . $CODE . ' ' . $LENGTH . '/' . $HEIGHT . '</td>
	// 			</tr>
	// 			<tr>
	// 				<td style="width:40%;">PARTY</td>
					
	// 				<td class="kotak1">20</td>
	// 				<td class="kotak1">40</td>
	// 				<td class="kotak1">45</td>
					
	// 			</tr>
	// 			<tr>
	// 				<td>GP STD</td>
	// 				<td class="kotak2"></td>
	// 				<td class="kotak2"></td>
	// 				<td class="kotak2"></td>					
	// 			</tr>
	// 			<tr>
	// 				<td>GP HC</td>
	// 				<td class="kotak2"></td>
	// 				<td class="kotak2"></td>
	// 				<td class="kotak2"></td>	
	// 			</tr>
	// 			<tr>
	// 				<td>NON GP STD</td>
	// 				<td class="kotak2"></td>
	// 				<td class="kotak2"></td>
	// 				<td class="kotak2"></td>	
	// 			</tr>
	// 			<tr>
	// 				<td>NON GP HC</td>
	// 				<td class="kotak2"></td>
	// 				<td class="kotak2"></td>
	// 				<td class="kotak2"></td>	
	// 			</tr>
	// 		</table>
	// 		<br>
	// 		<table style="border-spacing: 3px; border-collapse: separate;" width="100%">
	// 			<tr>
	// 				<td width="40%">CONDITION</td>
	// 				<td class="kotak3">AC</td>
	// 				<td class="kotak3">AU</td>
	// 				<td class="kotak3">DN</td>
	// 			</tr>
	// 			<tr>
	// 				<td>CLEANING</td>
	// 				<td class="kotak3">WW</td>
	// 				<td class="kotak3">SC</td>
	// 				<td class="kotak3">CC</td>
	// 			</tr>
	// 			<tr>
	// 				<td>REPAIR</td>
	// 				<td class="kotak3">Y</td>
	// 				<td class="kotak3">N</td>
	// 				<td class="">&nbsp;</td>
	// 			</tr>
	// 			<tr>
	// 				<td>VESSEL</td>
	// 				<td colspan="3">' . $VESSEL . '</td>
	// 			</tr>
	// 			<tr>
	// 				<td>EXPIRED</td>
	// 				<td colspan="3"></td>
	// 			</tr>
	// 			<tr>
	// 				<td>REMARK</td>
	// 				<td colspan="3">' . $REMARK . '</td>
	// 			</tr>
	// 			<tr>
	// 				<td>TRUCK ID</td>
	// 				<td colspan="3"></td>
	// 			</tr>		
	// 		</table>
	// 		<table width="100%">	
	// 			<tr>
	// 				<td width="33%">SURVEYOR</td>
	// 				<td width="33%" class="t-center">YARDMAN</td>
	// 				<td width="33%">INVENTORY</td>
	// 			</tr>
	// 			<tr><td colspan="3" height="15" width="100%"></td></tr>
	// 			<tr>
	// 				<td>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
	// 				<td class="t-center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
	// 				<td>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
	// 			</tr>	
	// 		</table>
	// 		</div>
	// 	';
	// 	$html .= '
	// 	</body>
	// 	</html>
	// 	';
	// 	$mpdf->WriteHTML($html);
	// 	$mpdf->Output();
	// 	// echo $html;
	// 	die();
	// }

	public function cetak_kitir_new($crno = "", $reorderno = "", $praid = "")
	{

		$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 236]]);
		// $mpdf->showImageErrors = true;
		$query_params = [
			"crno" => trim($crno),
			"cpiorderno" => trim($reorderno)
		];
		//print_r($query_params);
		$response = $this->client->request('GET', 'containerProcess/getKitirPepoIn', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $query_params,
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		// print_r($result);die();
		//die();
		// $recept = recept_by_praid($praid);
		if (isset($result['data'][0]) && (count($result['data'][0])) > 0) {
			$qrcode = $this->generate_qrcode($result['data'][0]['cpid']);
			$CRNO = $result['data'][0]['crno'];
			$REFIN = $result['data'][0]['cpirefin'];
			$CPID = $result['data'][0]['cpid'];
			$LENGTH = $result['data'][0]['cclength'];
			$HEIGHT = $result['data'][0]['ccheight'];
			$CPIORDERNO = $result['data'][0]['cpiorderno'];
			$TYPE = $result['data'][0]['cccode'];
			$CODE = $result['data'][0]['ctcode'];
			$PRINCIPAL = $result['data'][0]['prcode'];
			$EMKL = $result['data'][0]['repovendor'];
			$VESSEL = $result['data'][0]['vesid'];
			$VOY = $result['data'][0]['voyno'];
			$DATE = $result['data'][0]['cpidisdat'];
			$DESTINATION = "";
			$REMARK = $result['data'][0]['cpiremark'];
			$NOPOL = $result['data'][0]['cpinopol'];
			$QRCODE_IMG = ROOTPATH . '/public/media/qrcode/' . $qrcode['content'] . '.png';
			$CPIPRATGL = $result['data'][0]['cpipratgl'];
			$CPIRECEPTNO = $result['data'][0]['cpireceptno'];
			// $QRCODE_CONTENT = $qrcode['content'];
			$CRTARAK = $result['data'][0]['crtarak'];
			$CRTARAL = $result['data'][0]['crtaral'];
			$CRMANUF = $result['data'][0]['crmanuf'];
			$CRMANDAT = $result['data'][0]['crmandat'];
			$CRLASTCOND = $result['data'][0]['crlastcond'];
			$CPIDRIVER = $result['data'][0]['cpidriver'];
			$CPIDRIVER = $result['data'][0]['cpidriver'];
			$LOAD_STATUS = $result['data'][0]['cpife'];
		} else {
			$CRNO = "";
			$CODE = "";
			$CPID = "";
			$REFIN = "";
			$LENGTH = "";
			$HEIGHT = "";
			$CPIORDERNO = "";
			$TYPE = "";
			$PRINCIPAL = "";
			$EMKL = "";
			$VESSEL = "";
			$VOY = "";
			$DATE = "";
			$DESTINATION = "";
			$REMARK = "";
			$NOPOL = "";
			$QRCODE_IMG = "";
			$QRCODE_CONTENT = "";
			$CPIRECEPTNO = "";
			$CPIPRATGL = "";
			$CRTARAK  = "";
			$CRTARAL  = "";
			$CRMANUF  = "";
			$CRMANDAT = "";
			$CRLASTCOND = "";
			$CPIDRIVER = "";
			$LOAD_STATUS = "";
		}

		$result = json_decode($response->getBody()->getContents(), true);

		$barcode = $generator->getBarcode($crno, $generator::TYPE_CODE_128);

		$html = '';

		$html .= '
		<html>
			<head>
				<title>Gate In | Print EIR-IN</title>
				<link href="' . base_url() . '/public/themes/smartdepo/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>			
					.page-header{display:block;margin-bottom:20px;line-height:0.3;}
					table{line-height:1.75;display:block;}
					table td{font-weight:bold;font-size:12px;}
					.t-right{text-align:right;}
					.t-left{text-align:left;}
					.t-center{text-align:center;}
					.bordered {
						border:1px solid #666666;
						padding:3px;
					}
					.kotak1{border:1px solid #000000;padding:3px;width:20%;text-align:center;}
					.kotak2{border:1px solid #ffffff;padding:3px;width:20%;text-align:center;}
					.kotak3{border:1px solid #000000;padding:3px;width:20%;text-align:center;}
			        @media print {
			            @page {
			                margin: 0 auto;
			                sheet-size: 300px 275mm;
			            }
			            html {
			                direction: rtl;
			            }
			            html,body{margin:0;padding:0}
			            .wrapper {
			                width: 250px;
			                margin: auto;
			                text-align: justify;
			            }
			           .t-center{text-align: center;}
			           .t-right{text-align: right;}
			        }						
				</style>
			</head>
		';
		$html .= '<body onload="window.print()">
			<div class="wrapper">

			<div class="page-header t-center">
				<h5 style="line-height:1.2;font-weight:bold;padding-top:20px;">PT.CONTINDO RAYA</h5>
				<h5 style="line-height:0.5;font-weight:bold;padding-top:20px;">KITIR BONGKAR</h3>
				<h5 style="line-height:0.5;font-weight:bold;padding-top:20px;">REPO IN</h3>
				<h4 style="text-decoration: underline;line-height:0.5;">' . $REFIN . '</h3>
				<img src="' . $QRCODE_IMG . '" style="height:120px;">
				<h5 style="text-decoration: underline;line-height:0.5;">' . $CPID . '</h4>
			</div>
		';
		$html .= '
				<table border-spacing: 0; border-collapse: collapse; width="100%">	
					<tr>
						<td>PRINCIPAL</td>
						<td colspan="3">:&nbsp;' . $PRINCIPAL . '</td>
					</tr>
					<tr>
						<td style="width:40%;">CONTAINER NO.</td>
						<td colspan="3"><h5 style="line-height:1.2;font-weight:bold;padding-top:20px;">:&nbsp;' . $CRNO . '</h5></td>
					</tr>
					<tr>
						<td style="width:40%;">DATE</td>
						<td colspan="3">:&nbsp;' . date('d-m-Y', strtotime($CPIPRATGL)) . '</td>
					</tr>
					<tr>
						<td>TIPE</td>
						<td colspan="3">:&nbsp;' . $CODE . '/' . $TYPE . '</td>
					</tr>					
					<tr>
						<td>SIZE</td>
						<td colspan="3">:&nbsp;' . $LENGTH  . '/' . $HEIGHT . ' </td>
					</tr>
					<tr>
						<td>TARA</td>
						<td colspan="3">:&nbsp;' . $CRTARAK . '/' . $CRTARAL . ' </td>
					</tr>

					<tr>
						<td>MAN.DATE </td>
						<td colspan="3">:&nbsp;' . $CRMANDAT . ' </td>
					</tr>
					<tr>
						<td>CLEANING</td>
						<td colspan="3">:&nbsp;</td>
					</tr>
					<tr>
						<td>EMKL</td>
						<td colspan="3" style="font-weight:normal">:&nbsp;' . $EMKL . '</td>
					</tr>
					<tr>
						<td>LOAD STATUS</td>
						<td colspan="3">:&nbsp;' . $LOAD_STATUS . '</td>
					</tr>					
					<tr>
						<td>EX VESSEL</td>
						<td colspan="3">:&nbsp;' . $VESSEL . '/' . $VOY . '</td>
					</tr>
					<tr>
						<td>NO POLISI</td>
						<td colspan="3">:&nbsp;' . $NOPOL . '</td>
					</tr>
					<tr>
						<td>DRIVER</td>
						<td colspan="3">:&nbsp;' . $CPIDRIVER . '</td>
					</tr>		
					<tr>
						<td>REMARK</td>
						<td colspan="3">:&nbsp;' . $REMARK . '</td>
					</tr>			


					<tr rowspan="3">&nbsp;</tr>

				</table>
				<br><br>
				<table border-spacing: 0; border-collapse: collapse; width="100%">	
					<tr>
						<td width="30%">TRUCKER</td>
						<td width="" class="t-center">SURVEYOR</td>
						<td width="30%" class="t-right">GATE OFF</td>
					</tr>
					
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>	
					<tr>
						<td>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
						<td class="t-center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
						<td class="t-right">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
					</tr>	
				</table>
				</div>
			';


		$html .= '
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		// echo $html;
		die();
	}

	public function generate_qrcode($data)
	{
		/* Load QR Code Library */
		// $this->load->library('ciqrcode');
		$ciQrcode = new Ciqrcode();

		/* Data */
		$hex_data   = bin2hex($data);
		$save_name  = $data . '.png';

		/* QR Code File Directory Initialize */
		$dir = 'public/media/qrcode/';
		if (!file_exists($dir)) {
			mkdir($dir, 0775, true);
		}

		/* QR Configuration  */
		$config['cacheable']    = true;
		$config['imagedir']     = $dir;
		$config['quality']      = true;
		$config['size']         = '1024';
		$config['black']        = [255, 255, 255];
		$config['white']        = [255, 255, 255];
		$ciQrcode->initialize($config);

		/* QR Data  */
		$params['data']     = $data;
		$params['level']    = 'L';
		$params['size']     = 10;
		$params['savename'] = FCPATH . $config['imagedir'] . $save_name;

		$ciQrcode->generate($params);

		/* Return Data */
		return [
			'content' => $data,
			'file'    => $dir . $save_name,
		];
	}

	public function depo_dropdown($varname="",$selected="")
	{
		$response = $this->client->request('GET','depos/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$depo = $result['data']['datas'];
		$option = "";
		$option .= '<select name="'.$varname.'" id="'.$varname.'" class="select-depo">';
		$option .= '<option value="">-select-</option>';
		foreach($depo as $p) {
			$option .= "<option value='".$p['dpname'] ."'". ((isset($selected) && $selected==$p['dpname']) ? ' selected' : '').">".$p['dpname']."</option>";
		}
		$option .="</select>";
		return $option; 
		die();			
	}

	public function port_dropdown($varname="",$selected="")
	{
		$response = $this->client->request('GET','ports/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$port = $result['data']['datas'];
		$option = "";
		$option .= '<select name="'.$varname.'" id="'.$varname.'" class="select-port">';
		$option .= '<option value="">-select-</option>';
		foreach($port as $p) {
			$option .= "<option value='".$p['poid'] ."'". ((isset($selected) && $selected==$p['poid']) ? ' selected' : '').">".$p['podesc']."</option>";
		}
		$option .="</select>";
		return $option; 
		die();			
	}
	public function getCity($id) {
		$response = $this->client->request('GET','city/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json'=>[
				'cityId'=>$id
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		if(isset($result['data'])) {
			$city = $result['data']['city_name'];
		} else {
			$city = "";
		}

		return $city;
	}

	public function city_dropdown($varname,$selected="") 
	{
		$response = $this->client->request('GET','city/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json'=>[
				'start'=>0,
				'rows'=>10
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$data = $result['data']['datas'];
		$option = "";
		$option .= '<select name="'.$varname.'" id="'.$varname.'" class="select-city">';
		$option .= '<option value="">-select-</option>';
		foreach($data as $r) {
			$option .= "<option value='".$r['city_name'] ."'". ((isset($selected) && $selected==$r['city_name']) ? ' selected' : '').">".$r['city_name']."</option>"; 
		}
		$option .="</select>";
		return $option; 
		die();		
	}
}
