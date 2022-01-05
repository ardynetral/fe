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
			'rows'	=> 100,
			'search'=> "",
			'orderColumn' => "cccode",
			'orderType' => "ASC"
		]
	]);

	$result = json_decode($response->getBody()->getContents(),true);	
	$ccode = $result['data']['datas'];
	$option = "";
	$option .= '<select name="ccode[]" id="ccode" class="select-ccode">';
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