<?php

namespace site\controllers;

use common\models\Work;
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
	
	public function init()
	{
		parent::init();
		$isBlockedSite = Work::findOne(['status' => 1]);
		if ($isBlockedSite) {
			return $this->redirect(['/work/page']);
		}
	}
}
