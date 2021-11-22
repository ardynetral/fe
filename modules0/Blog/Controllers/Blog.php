<?php
namespace Modules\Blog\Controllers;

use App\Config\Database;

class Blog extends \CodeIgniter\Controller
{
	public function __construct()
	{
	}

	public function index()
	{
        // if (!session()->get('isLoggedIn')) {
        //     return redirect()->to(base_url() . '/home/login');
        // }
		$model = new \Modules\Blog\Models\PostModel();
		$data['user'] = $model->findAll();
		return view('Modules\Blog\Views\index');
	}
}