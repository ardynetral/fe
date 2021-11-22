<?php
namespace Modules\Blog\Models;

class PostModel extends \CodeIgniter\Model
{
	protected $table = 'users';
	protected $allowedFields = ['username','password','level'];

}