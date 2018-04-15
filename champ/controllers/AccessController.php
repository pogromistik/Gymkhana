<?php
namespace champ\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class AccessController extends BaseController
{
	public function can($role)
	{
		if (!\Yii::$app->user->can($role)) {
			throw new ForbiddenHttpException(\Yii::t('app', 'Доступ запрещён'));
		}
		return true;
	}
	
	public function init()
	{
		parent::init();
		
		if (\Yii::$app->user->isGuest) {
			$this->redirect(['/user/login']);
			\Yii::$app->end();
		}
	}
}
