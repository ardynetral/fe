<?php

use GuzzleHttp\Client;

function api_connect()
{

	// check_exp_time();

	$client = new Client([
		'base_uri' => 'http://202.157.185.83:4000/api/v1/',
		'timeout' => 0,
		'http_errors' => false
	]);

	return $client;
}

function is_admin()
{
	$token = substr(session()->get('login_token'), 7);
	$result = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))), true);
	if ($result['groupId'] == 4) {
		return true;
	} else {
		return false;
	}
}

// extract token login
//  ["id"]["username"]["email"]["groupId"]["iat"]["exp"])
function get_token_item()
{
	$token = substr(session()->get('login_token'), 7);
	$isitoken = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))), true);

	// ambil expiration time
	// $exp = date('m/d/Y H:i:s', $isitoken['exp']);	die();	

	return $isitoken;
}

function check_exp_time()
{
	if (session()->get('login_token') != "") {
		// $token = get_token_item();
		// $exp = $token['exp'];
		// $date = new DateTime();
		// $date->setTimestamp($exp);
		// $token_exp = $date->format('Y-m-d H:i:s');
		$exp_time = session()->get('exp_time');
		$now = date("Y-m-d H:i:s");
		if ($now >= $exp_time) {
			if (session()->destroy()) {
				session()->setFlashdata('session_expired', 'Your Login Session has Expired. Please re-Login');
				return redirect()->to(site_url('logout'));
			}
		}
	}

	return false;
}

function has_privilege($module_name, $priv = "_view")
{
	$api = api_connect();
	$token = get_token_item();
	$group_id = $token['groupId'];
	$akses = 'has' . $priv;
	$dt_cek = [$group_id, $module_name, 1];

	$response = $api->request('GET', 'privilege/list', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$dt_arr = [];
	$result = json_decode($response->getBody()->getContents(), true);

	foreach ($result['data'] as $row) {
		if (($row['group_id'] == $group_id) && ($row['modules']['module_url'] == $module_name) && ($row[$akses] == '1')) {
			$dt_arr[] = $row['group_id'];
			$dt_arr[] = $row['modules']['module_url'];
			$dt_arr[] = $row[$akses];
		}
	}

	if (array_diff($dt_cek, $dt_arr) != NULL) {
		echo "<p style='text-align:center;color:#ff0000;padding:100px;display:block;font-size:20px;'>You don't have permission to access this page!<br><br>
		<a href='dashboard'>BACK</a></p>";
		die();
	}
	// else {
	// return true;
	// }
}

function has_privilege_check($module_name, $priv = "_view")
{
	$api = api_connect();
	$token = get_token_item();
	$group_id = $token['groupId'];
	$akses = 'has' . $priv;
	$dt_cek = [$group_id, $module_name, 1];

	$response = $api->request('GET', 'privilege/list', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$dt_arr = [];
	$result = json_decode($response->getBody()->getContents(), true);

	foreach ($result['data'] as $row) {
		if (($row['group_id'] == $group_id) && ($row['modules']['module_url'] == $module_name) && ($row[$akses] == '1')) {
			$dt_arr[] = $row['group_id'];
			$dt_arr[] = $row['modules']['module_url'];
			$dt_arr[] = $row[$akses];
		}
	}

	if (array_diff($dt_cek, $dt_arr) != NULL) {
		return false;
	} else {
		return true;
	}
}

function list_menu()
{
	$api = api_connect();

	$response = $api->request('GET', 'privilege/listModule', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$dt_arr = [];
	$result = json_decode($response->getBody()->getContents(), true);
	$dt_arr = $result['data'];

	$groupId = get_token_item()['groupId'];

	echo "<ul class='main-menu'>";

	foreach ($dt_arr as $parent) {

		if (($parent['group_id'] == $groupId) && ($parent['has_view'] == 1) && ($parent['modules']['module_status'] == 1)) {

			if ($parent['modules']['module_parent'] == 0) {

				$mod_url = ((isset($parent['modules']['module_url']) && ($parent['modules']['module_url'] == "#")) ? site_url() . '/' . $parent['modules']['module_url'] : "#");

				$mod_icon_r = ((isset($parent['modules']['module_url']) && ($parent['modules']['module_url'] == "#")) ? '<i class="toggle-icon fa fa-angle-left"></i>' : "#");

				echo "<li>";
				echo '<a href="' . $mod_url . '" class="js-sub-menu-toggle"><i class="fa ' . $parent['modules']['module_icon'] . '"></i><span class="text">' . $parent['modules']['module_name'] . $mod_icon_r . '</a>';
				echo "<ul class='sub-menu'>";

				foreach ($dt_arr as $child) {
					if (($child['group_id'] == $groupId) && ($child['has_view'] == 1) && ($child['modules']['module_status'] == 1)) {
						if ($parent['modules']['module_id'] == $child['modules']['module_parent']) {
							if (service('uri')->getSegment(1) == $child['modules']['module_url']) {
								echo "<li class='active'>";
							} else {
								echo "<li>";
							}
							echo '<a href="' . site_url() . '/' . $child['modules']['module_url'] . '"><i class="fa ' . $child['modules']['module_icon'] . '"></i><span class="text">' . $child['modules']['module_name'] . '</span></a>';
							echo "</li>";
						}
					}
				}
				echo "</ul>";
				echo "</li>";
			}
		}
	}
	echo "</ul>";
}

function module_list_dropdown($selected = "")
{
	$api = api_connect();

	$response = $api->request('GET', 'privilege/listModule', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$dt_arr = [];
	$result = json_decode($response->getBody()->getContents(), true);
	$dt_arr = $result['data'];

	$groupId = get_token_item()['groupId'];

	echo '<select name="module_parent" id="module_parent" class="form-control">';
	echo '<option value="">-select-</option>';
	foreach ($dt_arr as $parent) {
		$parent_selected = ((isset($parent['modules']['module_id']) && ($parent['modules']['module_id'] == $selected)) ? "selected" : "");

		if (($parent['group_id'] == $groupId) && ($parent['has_view'] == 1)) {

			if ($parent['modules']['module_parent'] == 0) {

				echo '<option value="' . $parent['modules']['module_id'] . '" ' . $parent_selected . '>' . $parent['modules']['module_name'] . '</option>';

				foreach ($dt_arr as $child) {

					$child_selected = ((isset($child['modules']['module_id']) && ($child['modules']['module_id'] == $selected)) ? "selected" : "");

					if ($parent['modules']['module_id'] == $child['modules']['module_parent']) {

						echo '<option value="' . $child['modules']['module_id'] . '" ' . $child_selected . '>--- ' . $child['modules']['module_name'] . '</option>';
					}
				}
			}
		}
	}
	echo "<select>";
}

function module_dropdown($selected = "")
{
	$api = api_connect();

	$response = $api->request('GET', 'modules/getAllData', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$dt_arr = [];
	$result = json_decode($response->getBody()->getContents(), true);
	$dt_arr = $result['data']['datas'];

	// $groupId = get_token_item()['groupId'];

	echo '<select name="module_parent" id="module_parent" class="form-control">';
	echo '<option value="">-select-</option>';
	foreach ($dt_arr as $parent) {
		$parent_selected = ((isset($parent['module_id']) && ($parent['module_id'] == $selected)) ? "selected" : "");

		// if(($parent['group_id']==$groupId) && ($parent['has_view']==1)) {

		if ($parent['module_parent'] == 0) {

			echo '<option value="' . $parent['module_id'] . '" ' . $parent_selected . '>' . $parent['module_name'] . '</option>';

			foreach ($dt_arr as $child) {

				$child_selected = ((isset($child['module_id']) && ($child['module_id'] == $selected)) ? "selected" : "");

				if ($parent['module_id'] == $child['module_parent']) {

					echo '<option value="' . $child['module_id'] . '" ' . $child_selected . '>--- ' . $child['module_name'] . '</option>';
				}
			}
		}
		// }
	}
	echo "<select>";
}

function country_dropdown($selected = "")
{
	$client = api_connect();

	$response = $client->request('GET', 'countries/list', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$res = $result['data']['datas'];
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

function city_dropdown($varname, $selected = "")
{
	$client = api_connect();
	$response = $client->request('GET', 'city/list', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'json' => [
			'start' => 0,
			'rows' => 10
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$data = $result['data']['datas'];
	$option = "";
	$option .= '<select name="' . $varname . '" id="' . $varname . '" class="select-city">';
	$option .= '<option value="">-select-</option>';
	foreach ($data as $r) {
		$option .= "<option value='" . $r['city_id'] . "'" . ((isset($selected) && $selected == $r['city_id']) ? ' selected' : '') . ">" . $r['city_name'] . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

function principal_dropdown($selected = "")
{
	$api = api_connect();
	$response = $api->request('GET', 'principals/list', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$option = "";
	$option .= '<select name="prcode" id="prcode" class="select-pr">';
	$option .= '<option value="">-select-</option>';
	foreach ($result['data']['datas'] as $row) {
		$option .= "<option value='" . $row['prcode'] . "'" . ((isset($selected) && $selected == $row['prcode']) ? ' selected' : '') . ">" . strtoupper($row['prname']) . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

function debitur_dropdown($selected = "")
{
	$api = api_connect();
	$response = $api->request('GET', 'debiturs/getAllData', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$debitur = $result['data']['datas'];
	$option = "";
	$option .= '<select name="cucode" id="cucode" class="select-debitur">';
	$option .= '<option vslue="">-select-</option>';
	foreach ($debitur as $cu) {
		$option .= "<option value='" . $cu['cucode'] . "'" . ((isset($selected) && $selected == $cu['cucode']) ? ' selected' : '') . ">" . $cu['cuname'] . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

function port_dropdown($varname = "", $selected = "")
{
	$api = api_connect();
	$response = $api->request('GET', 'ports/list', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$port = $result['data']['datas'];
	$option = "";
	$option .= '<select name="' . $varname . '" id="' . $varname . '" class="select-port">';
	$option .= '<option value="">-select-</option>';
	foreach ($port as $p) {
		$option .= "<option value='" . $p['poid'] . "'" . ((isset($selected) && $selected == $p['poid']) ? ' selected' : '') . ">" . $p['poid'] . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

function depo_dropdown($selected = "")
{
	$api = api_connect();
	$response = $api->request('GET', 'depos/getAllData', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$depo = $result['data']['datas'];
	$option = "";
	$option .= '<select name="cpdepo" id="cpdepo" class="select-depo">';
	$option .= '<option value="">-select-</option>';
	foreach ($depo as $p) {
		$option .= "<option value='" . $p['dpcode'] . "'" . ((isset($selected) && $selected == $p['dpcode']) ? ' selected' : '') . ">" . $p['dpname'] . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

function depo_dropdown2($varname = "", $selected = "")
{
	$api = api_connect();
	$response = $api->request('GET', 'depos/getAllData', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$depo = $result['data']['datas'];
	$option = "";
	$option .= '<select name="' . $varname . '" id="' . $varname . '" class="select-depo">';
	$option .= '<option value="">-select-</option>';
	foreach ($depo as $p) {
		$option .= "<option value='" . $p['dpcode'] . "'" . ((isset($selected) && $selected == $p['dpcode']) ? ' selected' : '') . ">" . $p['dpname'] . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

function vessel_dropdown($selected = "")
{
	$api = api_connect();
	$response = $api->request('GET', 'vessels/list', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'json' => [
			'start' => 0,
			'rows'  => 1000,
			'search' => "",
			'orderColumn' => "vesid",
			'orderType' => "ASC"
		]
	]);
	$result = json_decode($response->getBody()->getContents(), true);
	$vessel = $result['data']['datas'];
	$option = "";
	$option .= '<select name="cpives" id="cpives" class="select-vessel">';
	$option .= '<option value="">-select-</option>';
	foreach ($vessel as $v) {
		$option .= "<option value='" . $v['vesid'] . "'" . ((isset($selected) && $selected == $v['vesid']) ? ' selected' : '') . ">" . $v['vesid'] . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

// Digunakan pada Order Pra
// endpoint  : voyages/list
function voyage_dropdown($selected = "")
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
	$option = "";
	$option .= '<select name="cpivoyid" id="cpivoyid" class="select-voyage">';
	$option .= '<option value="">-select-</option>';
	foreach ($vessel as $v) {
		$option .= "<option value='" . $v['voyid'] . "'" . ((isset($selected) && $selected == $v['voyid']) ? ' selected' : '') . ">" . $v['voyno'] . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

//Container Code
function ccode_dropdown($selected = "")
{
	$api = api_connect();
	$response = $api->request('GET', 'containercode/list', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'json' => [
			'start' => 0,
			'rows'	=> 100,
			'search' => "",
			'orderColumn' => "cccode",
			'orderType' => "ASC"
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$ccode = $result['data']['datas'];
	$option = "";
	$option .= '<select name="ccode" id="ccode" class="select-ccode">';
	$option .= '<option value="">-select-</option>';
	foreach ($ccode as $cc) {
		$option .= "
		<option value='" . $cc['cccode'] . "'" . ((isset($selected) && $selected == $cc['cccode']) ? ' selected' : '') . ">" . strtoupper($cc['cccode']) . "
		</option>";
		// $ctcode[] = 
		// $ctdesc[] =  
	}
	$option .= "</select>";
	return $option;
	die();
}

function currency_dropdown($selected = "")
{
	$client = api_connect();

	$response = $client->request('GET', 'currency/getAllData', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$res = $result['data']['datas'];
	$option = "";
	$option .= '<select name="tucode" id="tucode" class="form-control">';
	$option .= '<option value="">-select-</option>';
	foreach ($res as $r) {
		$option .= "<option value='" . $r['tucode'] . "'" . ((isset($selected) && $selected == $r['tucode']) ? ' selected' : '') . ">" . $r['curr_symbol'] . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

function currency_dropdown2($varname = "", $selected = "")
{
	$client = api_connect();

	$response = $client->request('GET', 'currency/getAllData', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$res = $result['data']['datas'];
	$option = "";
	$option .= '<select name="' . $varname . '" id="' . $varname . '" class="form-control">';
	foreach ($res as $r) {
		$option .= "<option value='" . $r['tucode'] . "'" . ((isset($selected) && $selected == $r['tucode']) ? ' selected' : '') . ">" . $r['curr_symbol'] . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

function cleaning_method($varname = "", $selected = "")
{
	$api = api_connect();
	$response = $api->request('GET', 'repair_methods/getCleanMethod', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'query' => [
			'offset' => 0,
			'limit' => 100
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$res = $result['data']['datas'];
	$option = "";
	$option .= '<select name="' . $varname . '" id="' . $varname . '" class="form-control">';
	foreach ($res as $r) {
		$option .= "<option value='" . $r['rmcode'] . "'" . ((isset($selected) && $selected == $r['rmcode']) ? ' selected' : '') . ">" . $r['rmdesc'] . "</option>";
	}
	$option .= "</select>";
	return $option;
	die();
}

function check_bukti_bayar($praid)
{
	$api = api_connect();
	$response = $api->request('GET', 'orderPras/printOrderByPraOrderId', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'query' => [
			'praid' => $praid,
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$datapra = $result['data']['datas'][0];

	// bukti_bayar
	if (isset($datapra['orderPraRecept'][1])) {
		$recept_files = $datapra['orderPraRecept'][1];
		$response_bukti = $api->request('GET', 'orderPraRecepts/getDetailData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'prareceptid' => $recept_files['prareceptid']
			]
		]);
		$result_bukti = json_decode($response_bukti->getBody()->getContents(), true);
		$bukti_bayar = $result_bukti['data'];

		if ($bukti_bayar != "") {
			return true;
		} else {
			return false;
		}
	}
	return false;
}

function check_bukti_bayar2($praid)
{
	$api = api_connect();
	$response = $api->request('GET', 'orderPras/printOrderByPraOrderId', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'query' => [
			'praid' => $praid,
		]
	]);

	$result = json_decode($response->getBody()->getContents(), true);
	$datapra = $result['data']['datas'][0];
	$data_bukti = "";
	// bukti_bayar
	if (isset($datapra['orderPraRecept'][1])) {
		$recept_files = $datapra['orderPraRecept'][1];
		$response_bukti = $api->request('GET', 'orderPraRecepts/getDetailData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'prareceptid' => $recept_files['prareceptid']
			]
		]);
		$result_bukti = json_decode($response_bukti->getBody()->getContents(), true);
		$bukti_bayar = $result_bukti['data'];
		$file_bukti = isset($bukti_bayar['files'][0])?$bukti_bayar['files'][0]:"";
		// dd($bukti_bayar);
		if($bukti_bayar['cpireceptno']=="-" || $file_bukti=="") {
			//return true; // update
			$data_bukti = "update";
		} else {
			$data_bukti = "exist";
		}
	} else {
		// return false; // insert
		$data_bukti = "insert";
	}
	return $data_bukti;
}

function recept_by_praid($praid)
{
	$api = api_connect();
	$data_recept = "";
	$pra_order = $api->request('GET', 'orderPras/printOrderByPraOrderId', [
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'query' => [
			'praid' => $praid,
		]
	]);

	$result_order = json_decode($pra_order->getBody()->getContents(), true);
	$dt_order = $result_order['data']['datas'][0];
	if (isset($dt_order['orderPraRecept'][1]['prareceptid'])) {
		// $prareceptid = $dt_order['orderPraRecept'][1]['prareceptid'];
		$prareceptid = $dt_order['orderPraRecept'][1]['prareceptid'];

		$response_bukti = $api->request('GET', 'orderPraRecepts/getDetailData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'prareceptid' => $prareceptid
			]
		]);
		$result_recept = json_decode($response_bukti->getBody()->getContents(), true);
		$data_recept = $result_recept['data'];
	}

	return $data_recept;
}

function get_company_profile() 
{
	$api = api_connect();
	$response = $api->request('GET','companies/list',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);	

	$data = isset($result['data']['datas'])?$result['data']['datas'][0]:"";
	$company = [];
	if($data == "") {
		$company['name'] = "";
		$company['manager'] = "";
		$company['address'] = "";
		$company['phone'] = "";
		$company['fax'] = "";
		$company['npwp'] = "";
	} else {
		$company['name'] = $data['pagroup'];
		$company['manager'] = $data['pamgr'];
		$company['address'] = trim($data['paaddr']);
		$company['phone'] = $data['paphone'];
		$company['fax'] = $data['pafax'];
		$company['npwp'] = $data['pakwtnpwp'];
	}	

	return $company;
}

// FUNGSI TERBILANG
function hundreds($number)
{
	$lasts = array('one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine');
	$teens = array('eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
	$tens = array('ten', 'twenty-', 'thirty-', 'forty-', 'fifty-', 'sixty-', 'seventy-', 'eighty-', 'ninety-');

	$string = "";
	$j = strlen($number);
	$done = false;
	for ($i = 0; $i < strlen($number); $i++) {
		if ($j == 2) {
			if (strlen($number) > 2) {
				if ($number[0] != 0) {
					$string .= ' hundred ';
				}
			}
			if ($number[$i] == 1) {
				if ($number[$i + 1] == 0) {
					$string .= $tens[$number[$i] - 1];
				} else {
					$string .= $teens[$number[$i + 1] - 1];
					$done = true;
				}
			} else {
				if (!empty($tens[$number[$i] - 1])) {
					$string .= $tens[$number[$i] - 1] . ' ';
				}
			}
		} elseif ($number[$i] != 0 && !$done) {
			$string .= $lasts[$number[$i] - 1];
		}
		$j--;
	}
	return $string;
}

// IDR fungsi terbilang in English 
function toEnglish($number)
{
	$hyphen      = '-';
	$conjunction = ' and ';
	$separator   = ', ';
	$negative    = 'negative ';
	$decimal     = ' point ';
	$dictionary  = array(
		0                   => 'zero',
		1                   => 'one',
		2                   => 'two',
		3                   => 'three',
		4                   => 'four',
		5                   => 'five',
		6                   => 'six',
		7                   => 'seven',
		8                   => 'eight',
		9                   => 'nine',
		10                  => 'ten',
		11                  => 'eleven',
		12                  => 'twelve',
		13                  => 'thirteen',
		14                  => 'fourteen',
		15                  => 'fifteen',
		16                  => 'sixteen',
		17                  => 'seventeen',
		18                  => 'eighteen',
		19                  => 'nineteen',
		20                  => 'twenty',
		30                  => 'thirty',
		40                  => 'fourty',
		50                  => 'fifty',
		60                  => 'sixty',
		70                  => 'seventy',
		80                  => 'eighty',
		90                  => 'ninety',
		100                 => 'hundred',
		1000                => 'thousand',
		1000000             => 'million',
		1000000000          => 'billion',
		1000000000000       => 'trillion',
		1000000000000000    => 'quadrillion',
		1000000000000000000 => 'quintillion'
	);

	if (!is_numeric($number)) {
		return false;
	}

	if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
		trigger_error(
			'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
			E_USER_WARNING
		);
		return false;
	}

	if ($number < 0) {
		return $negative . toEnglish(abs($number));
	}

	$string = $fraction = null;
	if (strpos($number, '.') !== false) {
		list($number, $fraction) = explode('.', $number);
	}

	switch (true) {
		case $number < 21:
			$string = $dictionary[$number];
			break;
		case $number < 100:
			$tens   = ((int) ($number / 10)) * 10;
			$units  = $number % 10;
			$string = $dictionary[$tens];
			if ($units) {
				$string .= $hyphen . $dictionary[$units];
			}
			break;
		case $number < 1000:
			$hundreds  = $number / 100;
			$remainder = $number % 100;
			$string    = $dictionary[$hundreds] . ' ' . $dictionary[100];
			if ($remainder) {
				$string .= $conjunction . toEnglish($remainder);
			}
			break;
		default:
			$baseUnit     = pow(1000, floor(log($number, 1000)));
			$numBaseUnits = (int) ($number / $baseUnit);
			$remainder    = $number % $baseUnit;
			$string       = toEnglish($numBaseUnits) . ' ' . $dictionary[$baseUnit];
			if ($remainder) {
				$string .= $remainder < 100 ? $conjunction : $separator;
				$string .= toEnglish($remainder);
			}
			break;
	}

	if (null !== $fraction && is_numeric($fraction)) {
		$string .= $decimal;
		$words = array();
		foreach (str_split((string) $fraction) as $number) {
			$words[] = $dictionary[$number];
		}
		$string .= implode(' ', $words);
	}

	return ucwords($string);
}

