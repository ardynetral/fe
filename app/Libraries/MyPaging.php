<?php 
namespace App\Libraries;

/**
 * Class Pagination for api client.
 * Adopt from CI Pager
 * @_rzn
 */
use CodeIgniter\Pager\Pager;
use Config\Services;

class MyPaging
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}	

	// PAGER-v1
	public function paginate1($endpoint,int $perPage = null, string $group = 'default', int $page = null, int $segment = 0)
	{
		$pager = Services::pager(null, null, false);

		if ($segment)
		{
			$pager->setSegment($segment);
		}

		$page = $page >= 1 ? $page : $pager->getCurrentPage($group);
		$resultData = $this->findAll1($endpoint);
		$totalRows = $resultData['data']['count'];
		$this->pager = $pager->store($group, $page, $perPage, $totalRows, $segment);
		$perPage     = $this->pager->getPerPage($group);
		$offset      = ($page - 1) * $perPage;
		$resultData = $this->findAll1($endpoint,$perPage, $offset);

		return  $resultData['data']['datas'];
	}	

	public function findAll1($endpoint,$perPage = 0, $offset = 0) {
		$response = $this->client->request('GET',$endpoint,[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => (int) $offset,
				'rows'	=> (int) $perPage
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		return $result;			
	}

	// PAGER-v2
	// PAGER
	public function paginate($endpoint,int $perPage = null, string $group = 'default', int $page = null, int $segment = 0)
	{
		$pager = Services::pager(null, null, false);

		if ($segment)
		{
			$pager->setSegment($segment);
		}

		$page = $page >= 1 ? $page : $pager->getCurrentPage($group);
		$resultData = $this->findAll($endpoint);
		$totalRows = (isset($resultData['data']['count'])?$resultData['data']['count']:$resultData['data']['Total']);
		$this->pager = $pager->store($group, $page, $perPage, $totalRows, $segment);
		$perPage     = $this->pager->getPerPage($group);
		$offset      = ($page - 1) * $perPage;
		$resultData = $this->findAll($endpoint,$perPage, $offset);

		return  $resultData['data']['datas'];
	}	

	public function findAll($endpoint,$perPage = 0, $offset = 0) {
		$response = $this->client->request('GET',$endpoint,[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => (int) $offset,
				'limit'	=> (int) $perPage
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		return $result;			
	}
	// Table Rows Number
    public function nomor($currentPage, $perPage)
    {
        if (is_null($currentPage)) {
            $nomor = 1;
        } else {
            $nomor = 1 + ($perPage * ($currentPage - 1));
        }
        return $nomor;
    }	
}