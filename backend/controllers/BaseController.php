<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
	public function actionCan()
	{
		return true;
	}
}
