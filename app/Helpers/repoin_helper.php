<?php

// Quantity dari HC & STD
function hitungHCSTD($datas) 
{
	$dt = [];
	$hc20 = 0;
	$hc40 = 0;
	$hc45 = 0;
	$std20 = 0;
	$std40 = 0;
	if($datas =="") {
		$dt['hc20'] = 0;
		$dt['hc40'] = 0;
		$dt['hc45'] = 0;
		$dt['std20'] = 0;
		$dt['std40'] = 0;
		$dt['std45'] = 0;
	} else {

		foreach($datas as $data) {

			if((floatval($data['ccheight'])>8.5) && (floatval($data['cclength'])<=20)) {
				$hc20=$hc20+1;
			} else if((floatval($data['ccheight'])>8.5) && (floatval($data['ccheight'])<9.5) && (floatval($data['cclength'])==40)) {
				$hc40=$hc40+1;
			} else if((floatval($data['ccheight']))>=9.5 && (floatval($data['cclength'])==40)) {
				$hc45=$hc45+1;
			}

			if((floatval($data['ccheight'])<=8.5) && (floatval($data['cclength'])<=20)) {
				$std20=$std20+1;
			} else if((floatval($data['ccheight'])<=8.5) && (floatval($data['cclength'])==40)) {
				$std40=$std40+1;
			}
		}
		$dt['hc20'] = $hc20;
		$dt['hc40'] = $hc40;
		$dt['hc45'] = $hc45;
		$dt['std20'] = $std20;
		$dt['std40'] = $std40;
		$dt['std45'] = 0;
	}

	return $dt;
}

//Container Code
function select_ccode($selected="")
{
	$api = api_connect();
	$response = $api->request('GET','containercode/list',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'json' => [
			'start' => 0,
			'rows'	=> 1000,
			'search'=> "",
			'orderColumn' => "cccode",
			'orderType' => "ASC"
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);	
	$ccode = $result['data']['datas'];
	$option = "";
	$option .= '<select name="cccode" id="cccode" class="select-cccode">';
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

function get_contract($id) {
	$api = api_connect();
	$data = "";
	$response = $api->request('GET','contracts/listOne',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'form_params'=>[
			'idContract' => $id,
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);

	if(isset($result['success']) && ($result['success']==true))
	{
		$data = $result['data'];
	} else {
		$data = ""; 		
	}

	return $data;
}

function cpopr_dropdown($selected="")
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
	$option .= '<select name="cpopr" id="cpopr" class="select-pr">';
	$option .= '<option value="">-select-</option>';	
	foreach ($result['data']['datas'] as $row) {
		$option .= "<option value='".$row['prcode'] ."'". ((isset($selected) && $selected==$row['prcode']) ? ' selected' : '').">".strtoupper($row['prname'])."</option>"; 
	}		
	$option .="</select>";
	return $option; 
	die();	
}

function repoves_dropdown($selected="")
{
	$api = api_connect();
	$response = $api->request('GET','vessels/list',[
		'headers' => [
			'Accept' => 'application/json',
			'Authorization' => session()->get('login_token')
		],
		'json' => [
			'start' => 0,
          	'rows'  => 1000,
          	'search'=> "",
          	'orderColumn' => "vesid",
          	'orderType' => "ASC"
		]
	]);
	$result = json_decode($response->getBody()->getContents(),true);	
	$vessel = $result['data']['datas'];
	$option = "";
	$option .= '<select name="recpives" id="recpives" class="select-vessel">';
	$option .= '<option value="">-select-</option>';
	foreach($vessel as $v) {
		$option .= "<option value='".$v['vesid'] ."'". ((isset($selected) && $selected==$v['vesid']) ? ' selected' : '').">".$v['vesid']."</option>";
	}
	$option .="</select>";
	return $option; 
	die();			
}