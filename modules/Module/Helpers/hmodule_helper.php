<?php 

// Checkbox Access (view,insert,edit,deleete)
function chk_approval($groupId,$opt) {
	if (($groupId==$opt['group_id']) && ($opt['has_approval']==1)){
		$data['checked'] = "checked";
		$data['val'] = 1;
	} else {
		$data['checked'] = "";
		$data['val'] = 0;
	}
	return $data;
}
function chk_view($groupId,$opt) {
	if (($groupId==$opt['group_id']) && ($opt['has_view']==1)){
		$data['checked'] = "checked";
		$data['val'] = 1;
	} else {
		$data['checked'] = "";
		$data['val'] = 0;
	}
	return $data;
}
function chk_insert($groupId,$opt) {
	if (($groupId==$opt['group_id']) && ($opt['has_insert']==1)){
		$data['checked'] = "checked";
		$data['val'] = 1;
	} else {
		$data['checked'] = "";
		$data['val'] = 0;
	}
	return $data;
}
function chk_update($groupId,$opt) {
	if (($groupId==$opt['group_id']) && ($opt['has_update']==1)){
		$data['checked'] = "checked";
		$data['val'] = 1;
	} else {
		$data['checked'] = "";
		$data['val'] = 0;
	}
	return $data;
}
function chk_delete($groupId,$opt) {
	if (($groupId==$opt['group_id']) && ($opt['has_delete']==1)){
		$data['checked'] = "checked";
		$data['val'] = 1;
	} else {
		$data['checked'] = "";
		$data['val'] = 0;
	}
	return $data;
}
function chk_printpdf($groupId,$opt) {
	if (($groupId==$opt['group_id']) && ($opt['has_printpdf']==1)){
		$data['checked'] = "checked";
		$data['val'] = 1;
	} else {
		$data['checked'] = "";
		$data['val'] = 0;
	}
	return $data;
}
function chk_printxls($groupId,$opt) {
	if (($groupId==$opt['group_id']) && ($opt['has_printxls']==1)){
		$data['checked'] = "checked";
		$data['val'] = 1;
	} else {
		$data['checked'] = "";
		$data['val'] = 0;
	}
	return $data;
}
// Get Checkbox val
// function chk_val($groupId,$opt) {
// 	$val = [];
// 	if ($groupId==$opt['group_id']) {
// 		$val['view']   = $opt['has_view'];
// 		$val['insert'] = $opt['has_insert'];
// 		$val['update'] = $opt['has_update'];
// 		$val['delete'] = $opt['has_delete'];
// 	} 
// 	return $val;
// }

// Get Privilege By GroupId
function privilegeByGroup($groupId) 
{
	$api = api_connect();
}