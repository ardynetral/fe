<?php

namespace Modules\Edi1\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Edi1 extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url', 'form', 'app']);
		$this->client = api_connect();
	}

	public function index()
	{
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view");

		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if (($group_id == 1) || ($group_id == 2)) {
			$data['prcode'] = $prcode;
			$data['cucode'] = $prcode;
		} else {
			$data['prcode'] = '0';
			$data['cucode'] = '0';
		}
		$data['group_id'] = $group_id;
		//print_r($data);
		//die();

		return view('Modules\Edi1\Views\add', $data);
	}

	public function view($code)
	{
		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if (($group_id == 1) || ($group_id == 2)) {
			$data['prcode'] = $prcode;
			$data['cucode'] = $prcode;
		} else {
			$data['prcode'] = '0';
			$data['cucode'] = '0';
		}
		$data['group_id'] = $group_id;
		return view('Modules\Edi1\Views\view', $data);
	}


	public function printEDI()
	{
		helper("filesystem");

		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		// GET PRINCIPAL
		$principal = $this->get_principal($_POST['prcode']);
		// echo var_dump(isset($principal["DEPO"]) ? $principal["DEPO"] : "");die();

		$depo 		= (isset($principal["DEPO"])		? $principal["DEPO"] 		: 'IDPDG31');
		$codepr 	= (isset($principal["CODEPR"])	? $principal["CODEPR"] 		: 'ONEY');
		$loc 		= (isset($principal["LOC"])		? $principal["LOC"] 		: 'IDPDG');
		$city 		= (isset($principal["CITY"])		? $principal["CITY"] 		: 'PDG31');

		$g_cabang 	= (isset($principal["CABANG"])	? $principal["CABANG"] 		: 'PDG');
		$damageL1 	= (isset($principal["DAMAGEL1"])	? $principal["DAMAGEL1"] 	: "GID+1'");
		$damageL2 	= (isset($principal["DAMAGEL2"])	? $principal["DAMAGEL2"] 	: "HAN+2'");
		$shopcode 	= (isset($principal["SHOPCODE"])	? $principal["SHOPCODE"] 	: "I30");
		$mhr 		= (isset($principal["MHR"])<>0) ? $principal["MHR"] 	: 1;

		$subDate			= date('his');
		$dextrax	 		= date('ydmhi');		

		list($day,$month,$year)=explode("/", $_POST["startDate"]);
		$Cdate = $_POST["startDate"];
		$Vdate = "$year-$month-$day";
		$Hdate = "$year$month$day";

		If ($_POST["startHour"] == "" && $_POST["endHour"] == "") {
			$StartHour = "0000";
			$EndHour = "2359";
		} elseif ($_POST["startHour"] <> "" && $_POST["endHour"] == "") {
			$StartHour = $_POST["startHour"];
			$EndHour = "2359";
		} elseif ($_POST["startHour"] == "" && $_POST["endHour"] <> "") {
			$StartHour = "0000";
			$EndHour = $_POST["endHour"];
		} elseif ($_POST["startHour"] <> "" && $_POST["endHour"] <> "") {
			$StartHour = $_POST["startHour"];
			$EndHour = $_POST["endHour"];
		}

		$filename = trim($_POST['prcode'])."_CODECO" . "-" . $loc . "-" . $Hdate . "-" . $subDate . ".txt";
		$tmpFile 	= FCPATH . 'public/media/codeco/' . $filename;
		
		$tmpText ="";

		if($this->request->isAJAX())
		{
			/*******************
				Extracting IN
			*******************/

			$mea=array(20=>'2200', 40=>'4300');

			$req1 = $this->client->request('GET','edi/codecoin',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'prcode' => $_POST['prcode'],
					'vdate' => date('Y-m-d', strtotime($_POST['startDate'])),
					'starthour' => $_POST['startHour'],
					'endhour' => $_POST['endHour']
				]
			]);
			
			$res1 = json_decode($req1->getBody()->getContents(), true);

			if(isset($res1['data']) && $res1['data']!=NULL) 
			{
				$i=1;
				foreach($res1['data'] as $row) {
					$i++;
			//		$tmpText .= "=========>IN<=============='\r\n";
					$seq = substr("0000", 4 - strlen($i)).$i;
					$JG = "34";
					$tmpText .= "UNH+" . $row["cpitgl"] . $seq . "+CODECO:D:95B:UN:ITG12'\r\n";
					$tmpText .= "BGM+" . $JG . "+000001+9'\r\n";
					$tmpText .= "NAD+CF+$codepr:160:166'\r\n";
					if ($row["svcond"]<>"") {
						If (trim($row["svcond"]) == "DN" || trim($row["svcond"]) == "DJ") {
							$tmpText .= $damageL1."\r\n";
							$tmpText .= $damageL2."\r\n";
						}
					}
					
					$tmpText .= "EQD+CN+" . $row["crno"] ."+". $row["cccode"]. ":102:5+++4'\r\n";
					//$tmpText .= "RFF+BN:".$row["CPIREFIN"]."'\r\n"; //re HAPAG
					$tmpText .= "DTM+7:".$row["cpitgl"].@$this->jam(trim($row["cpijam"])) . ":203'\r\n";
					$tmpText .= "LOC+165+$loc:139:6+$city:TER:ZZZ'\r\n";
					//$tmpText .= "SEL+IDHLCL098106+CA'\r\n";  //re HAPAG
					$tmpText .= "MEA+AAE+MW+KGM:".$mea[number_format($row["cclength"])]."'\r\n";
					$tmpText .= "FTX+AAI+++'\r\n";
					$tmpText .= "CNT+16:1'\r\n";
					$tmpText .= "UNT+7+".$row["cpitgl"].$seq."'\r\n";
				}
			}

			/*******************
				Extracting PTI
			*******************/
			$req2 = $this->client->request('GET','edi/codecopti',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'prcode' => $_POST['prcode'],
					'vdate' => date('Y-m-d', strtotime($_POST['startDate']))
				]
			]);
			$res2 = json_decode($req2->getBody()->getContents(), true);
			// echo var_dump($res2);die();
			if(isset($res2['data']) && $res2['data']!=NULL) 
			{
				$i=1;
				foreach($res2['data'] as $row) {
					$i++;
			//		$tmpText .= "=========>PTI<=============='\r\n";
					$seq = substr("0000",0, 4 - strlen(trim("$i")) ) . trim("$i");
					$JG = "266";
					$tmpText .= "UNH+" . $row["svsurdat"] . $seq . "+CODECO:D:95B:UN:ITG12'"."\r\n";
					$tmpText .= "BGM+" . $JG . "+000001+9'"."\r\n";
					$tmpText .= "NAD+CF+$codepr:160:166'"."\r\n";
					$tmpText .= "EQD+CN+" . $row["crno"] ."+". $row["cccode"]. ":102:5+++4'"."\r\n";
					$tmpText .= "DTM+379:" . $row["svsurdat"] . ":203'"."\r\n";
					$tmpText .= "LOC+165+$loc:139:6+$city:TER:ZZZ'\r\n";
					$tmpText .= "MEA+AAE+MW+KGM:".$mea[number_format($row["cclength"])]."'\r\n";
					$tmpText .= "FTX+AAI+++'\r\n";
					$tmpText .= "CNT+16:1'"."\r\n";
					$tmpText .= "UNT+8+" . $row["svsurdat"] . $seq . "'"."\r\n";
				}
			} else {
				$tmpText .= "";
			}

			/*******************
				Extracting UPDATE
			*******************/
			$req3 = $this->client->request('GET','edi/codecoupdate',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'prcode' => $_POST['prcode'],
					'vdate' => date('Y-m-d', strtotime($_POST['startDate'])),
					'starthour' => $_POST['startHour'],
					'endhour' => $_POST['endHour']
				]
			]);
			$res3 = json_decode($req3->getBody()->getContents(), true);
			if(isset($res3['data']) && $res3['data']!=NULL) 
			{
				$i=1;
				foreach($res3['data'] as $row) {
					$i++;
					$seq = substr("0000",0, 4 - strlen(trim("$i")) ) . trim("$i");
				//	$tmpText .= "=========>UPDATE<=============='\r\n";
					$tmpText .= "UNH+" . $row["rptglwroke"] . $seq . "+CODECO:D:95B:UN:ITG12'" ."\r\n";
					$tmpText .= "BGM+" . $JG . "+000001+9'" ."\r\n";
					$tmpText .= "NAD+CF+$codepr:160:166'" ."\r\n";
					$tmpText .= "GID+1'" ."\r\n";
					$tmpText .= "HAN+6'" ."\r\n";
					$tmpText .= "EQD+CN+" . $row["crno"] ."+". $row["cccode"]. ":102:5+++4'" ."\r\n";
					$tmpText .= "DTM+7:" . $row["rptglwroke"] . ":203'" ."\r\n";
					$tmpText .= "LOC+165+$loc:139:6+$city:TER:ZZZ'\r\n";
					$tmpText .= "MEA+AAE+MW+KGM:".$mea[number_format($row["cclength"])]."'\r\n";
					$tmpText .= "FTX+AAI+++'\r\n";
					$tmpText .= "CNT+16:1'" ."\r\n";
					$tmpText .= "UNT+9+" . $row["rptglwroke"] . $seq . "'" ."\r\n";
				}
			}

			/******************
				Extracting OUT
			*******************/
			$req4 = $this->client->request('GET','edi/codecoout',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'prcode' => $_POST['prcode'],
					'vdate' => date('Y-m-d', strtotime($_POST['startDate']))
				]
			]);	
			$res4 = json_decode($req4->getBody()->getContents(), true);
			if(isset($res4['data']) && $res4['data']!=NULL) 
			{
				$i=1;
				foreach($res4['data'] as $row) {
					$i++;
					$seq = substr("0000", 4 - strlen(trim($i))). trim($i);
					$JG = "36";
					
					if ($row["cpoves"] <> '') {
						// 				$VOYAGENUM = substr(trim($row["CPOVES"]),strlen($row["CPOVES"])-4, 4);
						// 				$VESSELNAME = trim(substr($row["CPOVES"], 0, strlen($row["CPOVES"]) - 4));
						$VOYAGENUM = trim($row["cpovoy"]);
						$VESSELNAME = trim($row["cpoves"]);
					}else{
						$VOYAGENUM = "0000";
						$VESSELNAME = trim($row["cpoves"]);
					}
					
					//		 $tmpText .= "=========>OUT<=============='\r\n";
					
					$tmpText .= "UNH+" . $row["cpotgl"]. $seq . "+CODECO:D:95B:UN:ITG12'\r\n";
					$tmpText .= "BGM+" . $JG . "+000001+9'"; 
					$tmpText .= "TDT+20+" . $VOYAGENUM . "+1++$codepr:172:166+++:::" . $VESSELNAME . "'\r\n";
					$tmpText .= "NAD+CF+$codepr:160:166'\r\n";
					$tmpText .= "EQD+CN+" . $row["crno"] ."+". $row["cccode"]. ":102:5+++4'\r\n";
					$tmpText .= "RFF+BN:" . $depo . trim($row["cporefout"]) . "'\r\n";
					$tmpText .= "DTM+7:" . $row["cpotgl"] . @$this->jam(trim($row["cpojam"])) . ":203'\r\n";
					$tmpText .= "LOC+165+$loc:139:6+$loc:TER:ZZZ'\r\n";
					$tmpText .= "MEA+AAE+MW+KGM:".@$mea[number_format($row["cclength"])]."'\r\n";
					$tmpText .= "FTX+AAI+++'\r\n";
					$tmpText .= "SEL+ID" . trim($row["cposeal"]) . "+CA'\r\n";
					$tmpText .= "CNT+16:1'\r\n";
					$tmpText .= "UNT+10+" . $row["cpotgl"] . $seq . "'\r\n";
				}					
			}	

			//write to file
			if (trim($tmpText)){
				$tmpText = "UNA:+.?'\r\n". 
							"UNB+UNOA:1+" . $depo . "+$codepr+" . date("ymd:hi") . "+" . $dextrax . "'\r\n". $tmpText;

				$tmpText .= "UNZ+" . $i . "+" . $dextrax . "'\r\n";

				if(write_file($tmpFile, $tmpText)) {
					$data['status'] = "success";
					$data['message'] = "success";
					$data['data'] = $filename;
					echo json_encode($data);die();
				} else {
					$data['status'] = "Failled";
					$data['message'] = "Filed to write File !!!";
					$data['data'] = "";
					echo json_encode($data);die();
				}

			} else {
				$data['status'] = "Failled";
				$data['message'] = "Data doesn\'t exist !!!";
				$data['data'] = "";
				echo json_encode($data);die();				
			}

		}

	}

	function get_principal($prcode)
	{

		$response = $this->client->request('GET','principals/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'id' => $prcode,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data = isset($result['data'])?$result['data']:"";
		return $data;
	}	
	function jam($t){
		$t=explode(":",trim($t));
		return $t[0].$t[1];
	}		
}
