<?php

namespace admin\controllers;

use admin\models\SignupForm;
use Yii;
use common\models\User;

/**
 * PagesController implements the CRUD actions for Page model.
 */
class HelpController extends BaseController
{
	public function actionIndex()
	{
		$this->can('admin');
		
		return $this->render('help');
	}
}
