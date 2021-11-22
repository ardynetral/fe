<?php
namespace Modules\Groups\Controllers;

use GuzzleHttp\Client;
class Groups extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = new Client([
			'base_uri'=>'http://202.157.185.83:4000/api/v1/',
			'timeout'=>0,
			'http_errors' => false
		]);
	}

	// api => groups/allGroups
	public function index()
	{
		$response = $this->client->request('GET','groups/allGroups',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['ugroup'] = $result['data'];
		return view('Modules\Groups\Views\index',$data);
	}
}