<?php
use GuzzleHttp\Client;

function api_connect() {

	// check_exp_time();

	$client = new Client([
		'base_uri'=>'http://202.157.185.83:4000/api/v1/',
		'timeout'=>0,
		'http_errors' => false
	]);

	return $client;
}

function is_admin()
{
	$token = substr(session()->get('login_token'),7);
	$result = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))),true);
	if($result['groupId']==4) {
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
	$isitoken = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))),true);

	// ambil expiration time
	// $exp = date('m/d/Y H:i:s', $isitoken['exp']);	die();	

	return $isitoken;
}

function check_exp_time()
{
	if(session()->get('login_token')!=NULL) {
		// $token = get_token_item();
		// $exp = $token['exp'];
		// $date = new DateTime();
		// $date->setTimestamp($exp);
		// $token_exp = $date->format('Y-m-d H:i:s');
		$exp_time = session()->get('exp_time');
		$now = date("Y-m-d H:i:s");
		 if($now>=$exp_time) {
		 	if(session()->destroy()) {
			 	session()->setFlashdata('session_expired','Your Login Session has Expired. Please re-Login');	
			 	return redirect()->to('login');
		 	}
		 }	
	}

	return false;
}

function has_privilege($module_name, $priv="_view") 
{
	$api = api_connect();
	$token = get_token_item();
	$group_id = $token['groupId'];
	$akses = 'has'.$priv;
	$dt_cek = [$group_id,$module_name,1];

	$response = $api->request('GET','privilege/list',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);
	
	$dt_arr = [];
	$result = json_decode($response->getBody()->getContents(),true);	
	
	foreach($result['data'] as $row) {
		if(($row['group_id']==$group_id) && ($row['modules']['module_url']==$module_name) && ($row[$akses]=='1'))
		{
			$dt_arr[] = $row['group_id'];
			$dt_arr[] = $row['modules']['module_url'];
			$dt_arr[] = $row[$akses];
		} 
	}
	
	if(array_diff($dt_cek, $dt_arr)!=NULL) {
		echo "<p style='text-align:center;color:#ff0000;padding:100px;display:block;font-size:20px;'>You don't have permission to access this page!<br><br>
		<a href='dashboard'>BACK</a></p>";
		die();
	} 
	// else {
		// return true;
	// }
}

function has_privilege_check($module_name, $priv="_view") {
	$api = api_connect();
	$token = get_token_item();
	$group_id = $token['groupId'];
	$akses = 'has'.$priv;
	$dt_cek = [$group_id,$module_name,1];

	$response = $api->request('GET','privilege/list',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);
	
	$dt_arr = [];
	$result = json_decode($response->getBody()->getContents(),true);	
	
	foreach($result['data'] as $row) {
		if(($row['group_id']==$group_id) && ($row['modules']['module_url']==$module_name) && ($row[$akses]=='1'))
		{
			$dt_arr[] = $row['group_id'];
			$dt_arr[] = $row['modules']['module_url'];
			$dt_arr[] = $row[$akses];
		} 
	}
	
	if(array_diff($dt_cek, $dt_arr)!=NULL) {
		return false;
	} else {
		return true;
	}
}

function list_menu() {
	$api = api_connect();
	
	$response = $api->request('GET','privilege/listModule',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$dt_arr = [];
	$result = json_decode($response->getBody()->getContents(),true);	
	$dt_arr = $result['data'];

	$groupId = get_token_item()['groupId'];

	echo "<ul class='main-menu'>";

	foreach ($dt_arr as $parent) {

		if(($parent['group_id']==$groupId) && ($parent['has_view']==1) && ($parent['modules']['module_status']==1)) {

			if($parent['modules']['module_parent']==0) {

				$mod_url = ((isset($parent['modules']['module_url']) && ($parent['modules']['module_url']=="#"))? site_url() . '/' . $parent['modules']['module_url'] : "#");

				$mod_icon_r = ((isset($parent['modules']['module_url']) && ($parent['modules']['module_url']=="#"))? '<i class="toggle-icon fa fa-angle-left"></i>' : "#");

				echo "<li>";
				echo '<a href="' . $mod_url . '" class="js-sub-menu-toggle"><i class="fa ' .$parent['modules']['module_icon']. '"></i><span class="text">' . $parent['modules']['module_name'] . $mod_icon_r . '</a>';
					echo "<ul class='sub-menu'>";

					foreach($dt_arr as $child) {
						if(($child['group_id']==$groupId) && ($child['has_view']==1) && ($child['modules']['module_status']==1)) {
							if($parent['modules']['module_id']==$child['modules']['module_parent']) {
								if(service('uri')->getSegment(1)==$child['modules']['module_url']) {
									echo "<li class='active'>" ;
								}else{
								echo "<li>" ;
								}								
								echo '<a href="'.site_url().'/'.$child['modules']['module_url'].'"><i class="fa ' .$child['modules']['module_icon']. '"></i><span class="text">' . $child['modules']['module_name'] . '</span></a>';
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

function module_list_dropdown($selected="") 
{
	$api = api_connect();
	
	$response = $api->request('GET','privilege/listModule',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$dt_arr = [];
	$result = json_decode($response->getBody()->getContents(),true);	
	$dt_arr = $result['data'];

	$groupId = get_token_item()['groupId'];

	echo '<select name="module_parent" id="module_parent" class="form-control">';
	echo '<option value="">-select-</option>';
	foreach ($dt_arr as $parent) {
		$parent_selected = ((isset($parent['modules']['module_id']) && ($parent['modules']['module_id']==$selected)) ? "selected" : "");
		
		if(($parent['group_id']==$groupId) && ($parent['has_view']==1)) {

			if($parent['modules']['module_parent']==0) {

				echo '<option value="' . $parent['modules']['module_id'] . '" ' . $parent_selected . '>' . $parent['modules']['module_name'] . '</option>';

				foreach($dt_arr as $child) {

					$child_selected = ((isset($child['modules']['module_id']) && ($child['modules']['module_id']==$selected)) ? "selected" : "");		

					if($parent['modules']['module_id']==$child['modules']['module_parent']) {

						echo '<option value="' . $child['modules']['module_id'] . '" ' . $child_selected . '>--- ' . $child['modules']['module_name'] . '</option>';
					}
				}
			}
		}
	}
	echo "<select>";
}

function module_dropdown($selected="") 
{
	$api = api_connect();
	
	$response = $api->request('GET','modules/getAllData',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$dt_arr = [];
	$result = json_decode($response->getBody()->getContents(),true);	
	$dt_arr = $result['data']['datas'];

	// $groupId = get_token_item()['groupId'];

	echo '<select name="module_parent" id="module_parent" class="form-control">';
	echo '<option value="">-select-</option>';
	foreach ($dt_arr as $parent) {
		$parent_selected = ((isset($parent['module_id']) && ($parent['module_id']==$selected)) ? "selected" : "");
		
		// if(($parent['group_id']==$groupId) && ($parent['has_view']==1)) {

			if($parent['module_parent']==0) {

				echo '<option value="' . $parent['module_id'] . '" ' . $parent_selected . '>' . $parent['module_name'] . '</option>';

				foreach($dt_arr as $child) {

					$child_selected = ((isset($child['module_id']) && ($child['module_id']==$selected)) ? "selected" : "");		

					if($parent['module_id']==$child['module_parent']) {

						echo '<option value="' . $child['module_id'] . '" ' . $child_selected . '>--- ' . $child['module_name'] . '</option>';
					}
				}
			}
		// }
	}
	echo "<select>";
}

function country_dropdown($selected="")
{
	$client = api_connect();

	$response = $client->request('GET','countries/list',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);	
	$res = $result['data']['datas'];
	$option = "";
	$option .= '<select name="cncode" id="cncode" class="select-cncode">';
	$option .= '<option value="">-select-</option>';
	foreach($res as $r) {
		$option .= "<option value='".$r['cncode'] ."'". ((isset($selected) && $selected==$r['cncode']) ? ' selected' : '').">".$r['cndesc']."</option>"; 
	}
	$option .="</select>";
	return $option; 
	die();
}	

function principal_dropdown($selected="")
{
	$api = api_connect();
	$response = $api->request('GET','principals/list',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);
	$option = "";
	$option .= '<select name="prcode" id="prcode" class="select-pr">';
	$option .= '<option value="">-select-</option>';	
	foreach ($result['data']['datas'] as $row) {
		$option .= "<option value='".$row['prcode'] ."'". ((isset($selected) && $selected==$row['prcode']) ? ' selected' : '').">".strtoupper($row['prname'])."</option>"; 
	}		
	$option .="</select>";
	return $option; 
	die();	
}

function debitur_dropdown($selected="")
{
	$api = api_connect();
	$response = $api->request('GET','debiturs/getAllData',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
	]);

	$result = json_decode($response->getBody()->getContents(),true);	
	$debitur = $result['data']['datas'];
	$option = "";
	$option .= '<select name="cucode" id="cucode" class="select-debitur">';
	$option .= '<option vslue="">-select-</option>';
	foreach($debitur as $cu) {
		$option .= "<option value='".$cu['cucode'] ."'". ((isset($selected) && $selected==$cu['cucode']) ? ' selected' : '').">".$cu['cuname']."</option>";
	}
	$option .="</select>";
	return $option; 
	die();			
}

function port_dropdown($varname="",$selected="")
{
	$api = api_connect();
	$response = $api->request('GET','ports/list',[
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
		$option .= "<option value='".$p['poport'] ."'". ((isset($selected) && $selected==$p['poport']) ? ' selected' : '').">".$p['poport']." - ".$p['cncode']."</option>";
	}
	$option .="</select>";
	return $option; 
	die();			
}

function depo_dropdown($selected="")
{
	$api = api_connect();
	$response = $api->request('GET','depos/getAllData',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);	
	$depo = $result['data']['datas'];
	$option = "";
	$option .= '<select name="cpdepo" id="cpdepo" class="select-depo">';
	$option .= '<option value="">-select-</option>';
	foreach($depo as $p) {
		$option .= "<option value='".$p['dpcode'] ."'". ((isset($selected) && $selected==$p['dpcode']) ? ' selected' : '').">".$p['dpcode']." - ".$p['dpname']."</option>";
	}
	$option .="</select>";
	return $option; 
	die();			
}

function vessel_dropdown($selected="")
{
	$api = api_connect();
	$response = $api->request('GET','vessels/list',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'json' => [
			'start' => 0,
          	'rows'  => 100,
          	'search'=> "",
          	'orderColumn' => "vesid",
          	'orderType' => "ASC"
		]
	]);
	$result = json_decode($response->getBody()->getContents(),true);	
	$vessel = $result['data']['datas'];
	$option = "";
	$option .= '<select name="cpives" id="cpives" class="select-vessel">';
	$option .= '<option value="">-select-</option>';
	foreach($vessel as $v) {
		$option .= "<option value='".$v['vesid'] ."'". ((isset($selected) && $selected==$v['vesid']) ? ' selected' : '').">".$v['vesid']."</option>";
	}
	$option .="</select>";
	return $option; 
	die();			
}

// Digunakan pada Order Pra
// endpoint  : voyages/list
function voyage_dropdown($selected="")
{
	$api = api_connect();
	$response = $api->request('GET','voyages/list',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);
	$vessel = $result['data']['datas'];
	$option = "";
	$option .= '<select name="cpivoyid" id="cpivoyid" class="select-voyage">';
	$option .= '<option value="">-select-</option>';
	foreach($vessel as $v) {
		$option .= "<option value='".$v['voyid'] ."'". ((isset($selected) && $selected==$v['voyid']) ? ' selected' : '').">".$v['voyno']."</option>";
	}
	$option .="</select>";
	return $option; 
	die();			
}

//Container Code
function ccode_dropdown($selected="")
{
	$api = api_connect();
	$response = $api->request('GET','containercode/list',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'json' => [
			'start' => 0,
			'rows'	=> 100,
			'search'=> "",
			'orderColumn' => "cccode",
			'orderType' => "ASC"
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);	
	$ccode = $result['data']['datas'];
	$option = "";
	$option .= '<select name="ccode" id="ccode" class="select-ccode">';
	$option .= '<option value="">-select-</option>';
	foreach($ccode as $cc) {
		$option .= "
		<option value='".$cc['cccode'] ."'". ((isset($selected) && $selected==$cc['cccode']) ? ' selected' : '').">".strtoupper($cc['cccode'])."
		</option>";
		// $ctcode[] = 
		// $ctdesc[] =  
	}
	$option .="</select>";
	return $option; 
	die();			
}

function currency_dropdown($selected="")
{
	$client = api_connect();

	$response = $client->request('GET','currency/getAllData',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);	
	$res = $result['data']['datas'];
	$option = "";
	$option .= '<select name="tucode" id="tucode" class="form-control">';
	$option .= '<option value="">-select-</option>';
	foreach($res as $r) {
		$option .= "<option value='".$r['tucode'] ."'". ((isset($selected) && $selected==$r['tucode']) ? ' selected' : '').">".$r['curr_symbol']."</option>"; 
	}
	$option .="</select>";
	return $option; 
	die();
}

function currency_dropdown2($varname="",$selected="")
{
	$client = api_connect();

	$response = $client->request('GET','currency/getAllData',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);	
	$res = $result['data']['datas'];
	$option = "";
	$option .= '<select name="'.$varname.'" id="'.$varname.'" class="form-control">';
	foreach($res as $r) {
		$option .= "<option value='".$r['tucode'] ."'". ((isset($selected) && $selected==$r['tucode']) ? ' selected' : '').">".$r['curr_symbol']."</option>"; 
	}
	$option .="</select>";
	return $option; 
	die();
}
