<?php
namespace site\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
	public $description = '';
	public $pageTitle = '';
	public $keywords = '';
	public $url = '';
	
	public function can($role)
	{
		return true;
	}
}
