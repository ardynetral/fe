<?php
namespace Modules\MnrTariff\Controllers;

class MnrTariff extends \CodeIgniter\Controller
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
		// has_privilege($module, "_view");
		// define("has_insert", has_privilege_check($module, '_insert'));
		// define("has_approval", has_privilege_check($module, '_approval'));
		// define("has_edit", has_privilege_check($module, '_update'));
		// define("has_delete", has_privilege_check($module, '_delete'));
		// define("has_print", has_privilege_check($module, '_printpdf'));

		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];

		$data['page_title'] = "MNR Tariff";
		$data['page_subtitle'] = "MNR Tariff page";
		return view('Modules\MnrTariff\Views\index',$data);
	}

	public function list_data() {

		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;

		$response = $this->client->request('GET','damage_tariffs/listIsoRepair',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => $offset,
				'limit' => $limit,
				'search' => $search
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		// print_r($result);die();
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
            $record[] = $v['mtcode'];
            $record[] = $v['comp_description'];
			$record[] = $v['repair_description'];
			$record[] = $v['material'];
			$record[] = $v['formula'];
			$record[] = $v['also_applies_to'];
			$record[] = $v['locations'];
			$record[] = $v['cccodes'];
			$record[] = $v['_limit'];
			$record[] = $v['_start'];
			$record[] = $v['_hours'];
			$record[] = $v['_mtrlcost'];
			$record[] = $v['_inc'];

			$btn_list .='<a href="'.site_url('mnrtariff/view/').$v["isoid"].'" id="" class="btn btn-xs btn-primary btn-table" data-praid="">view</a>&nbsp;';						
			$btn_list .='<a href="'.site_url('mnrtariff/edit/').$v["isoid"].'" class="btn btn-xs btn-success btn-table">edit</a>&nbsp;';
			$btn_list .='<a href="#" id="deleteRow_'.$no.'" class="btn btn-xs btn-danger btn-table delete" data-kode="'.$v['isoid'].'">delete</a>';	

            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);		
	}

	public function view($id)
	{
		$data = [];
		$data['page_title'] = "MNR Tariff";
		$data['page_subtitle'] = "View MNR Tariff";
		// print_r($this->get_data_mnr($id));die();
		// $ccodes = [];	
		// foreach($_POST['cccodes'] as $val) {
		// 	$ccodes[] = substr($val, strpos($val, "-") + 1);
		// }
		$data['data'] = $this->get_data_mnr($id);
		$data['component_dropdown'] = $this->component_dropdown($data['data']['comp_code']);
		$data['repair_dropdown'] = $this->repair_dropdown($data['data']['repair_code']);
		$data['cccodes_dropdown'] = $this->cccodes_dropdown($data['data']['cccodes']);
		return view('Modules\MnrTariff\Views\view',$data);
	}

	public function add()
	{
		/* mtcode, comp_code, comp_description, repair_code, repair_description, material, formula,
		also_applies_to, locations, cccodes, limit, start, hours,
		mtrlcost, inc, inchours, incmtrlcost*/

		$data = [];
		$data['page_title'] = "MNR Tariff";
		$data['page_subtitle'] = "Insert MNR Tariff";
		// $ccodes = [];


		if($this->request->isAJAX()) {
			// foreach($_POST['cccodes'] as $val) {
			// 	$ccodes[] = substr($val, strpos($val, "-") + 1);
			// }
			$reformat = [
				'cccodes' => implode(" ", $_POST['cccodes'])
			];
			$form_params = array_replace($_POST, $reformat);
			$response = $this->client->request('POST', 'damage_tariffs/createIsoRepair', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $form_params
			]);

			$result = json_decode($response->getBody()->getContents(), true);

			if (isset($result['status']) && ($result['status'] == "Failled")) {
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);
				die();
			}
			$data['status'] = "success";
			$data['message'] = "Data Saved";
			echo json_encode($data);
			die();			
		}

		$data['component_dropdown'] = $this->component_dropdown();
		$data['repair_dropdown'] = $this->repair_dropdown();
		$data['cccodes_dropdown'] = $this->cccodes_dropdown();
		return view('Modules\MnrTariff\Views\add',$data);
	}

	public function edit($id)
	{
		// $data = [];
		$data['page_title'] = "MNR Tariff";
		$data['page_subtitle'] = "Edit MNR Tariff";

		if($this->request->isAJAX()) {
			$reformat = [
				'cccodes' => implode(" ", $_POST['cccodes'])
			];
			$form_params = array_replace($_POST, $reformat);
			$response = $this->client->request('PUT', 'damage_tariffs/updateIsoRepair', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $form_params
			]);

			$result = json_decode($response->getBody()->getContents(), true);
			// echo print_r($result);die();
			if (isset($result['status']) && ($result['status'] == "Failled")) {
				$data['status'] = "Failled";
				$data['message'] = $result['message'];
				echo json_encode($data);
				die();
			}
			$data['status'] = "success";
			$data['message'] = "Data Saved";
			echo json_encode($data);
			die();			
		}

		$data['data'] = $this->get_data_mnr($id);

		$cccode = [];
		$data['cccode_arr'] = ($data['data']['cccodes']!="")?explode(" ", $data['data']['cccodes']):"";	
		$data['component_dropdown'] = $this->component_dropdown($data['data']['comp_code']);
		$data['repair_dropdown'] = $this->repair_dropdown($data['data']['repair_code']);
		$data['cccodes_dropdown'] = $this->cccodes_dropdown($data['data']['cccodes']);
		return view('Modules\MnrTariff\Views\edit',$data);
	}

	public function delete() 
	{
		if($this->request->isAjax()) {

			$response = $this->client->request('DELETE', 'damage_tariffs/deleteIsoRepair', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'isoid' => $_POST['isoid']
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);
			// echo var_dump($result);die();
			if(isset($result['status']) && ($result['status']=="Failled")) {
				$data['status'] = "Failled";
				$data['message'] = "Gagal hapus data";
				echo json_encode($data);
				die();
			}

			$data['status'] = "success";
			$data['message'] = "Berhasil hapus data";			
			echo json_encode($data);
			die();	
		}	
	}

	public function get_data_mnr($id)
	{
		$data="";
		$response = $this->client->request('GET', 'damage_tariffs/detailIsoRepair', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'isoid' => $id
			]
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		// return $result;
		if(isset($result['message']) && $result['message']=="Failled") {
			$data = "";
		} else {
			$data = $result['data'][0];
		}
		return $data;
	}

	public function component_dropdown($selected="")
	{
		$data = [];
		$response = $this->client->request('GET', 'components/listComponen', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		$res = $result['data']['datas'];
		$option = "";
		$option .= '<select name="comp_code" id="comp_code" class="select-compcode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['cmcode'] . "'" . ((isset($selected) && $selected == $r['cmcode']) ? ' selected' : '') . ">" . $r['cmcode'] . "</option>";
		}
		$option .= "</select>";
		return $option;
		die();
	}

	public function repair_dropdown($selected="")
	{
		$data = [];
		$response = $this->client->request('GET', 'repair_methods/listAllRepair', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		$res = $result['data']['datas'];
		$option = "";
		$option .= '<select name="repair_code" id="repair_code" class="select-repaircode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['rmcode'] . "'" . ((isset($selected) && $selected == $r['rmcode']) ? ' selected' : '') . ">".$r['rmcode']."</option>";
		}
		$option .= "</select>";
		return $option;
		die();		
	}

	public function cccodes_dropdown($selected="")
	{
		$data = [];
		$response = $this->client->request('GET', 'containercode/listContainerCode', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		$res = $result['data']['datas'];
		$option = "";
		$option .= '<select name="cccodes[]" id="repair_code" class="select-cccodes" multiple="multiple">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['cccode']."-".(int)$r['cclength'] . "'" . ((isset($selected) && $selected == $r['cccode']."-".(int)$r['cclength']) ? ' selected' : '') . ">".$r['cccode']."</option>";
		}
		$option .= "</select>";
		return $option;
		die();		
	}	

	public function get_component_desc()
	{
		$response = $this->client->request('GET', 'components/listOne', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'idComponent' => $_POST['comp_code']
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		echo json_encode($result['data']['cmdesc']);
		die();
	}

	public function get_repair_desc()
	{
		$response = $this->client->request('GET', 'repair_methods/getDetailData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'rmcode' => $_POST['repair_code']
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);		
		echo json_encode($result['data']['rmdesc']);
		die();
	}	
}