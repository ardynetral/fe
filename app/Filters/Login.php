<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Login implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		// helper(['app']);

		if(session()->get('logged_in')==false)
		{
			return redirect()->to(site_url('login'));	
		}
		

	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		//do something...
	}
}