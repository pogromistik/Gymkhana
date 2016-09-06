<?php
namespace backend\controllers;

use Yii;

/**
 * Site controller
 */
class MainController extends BaseController
{
	public function actionIndex()
	{
		return $this->render('index');
	}
}
