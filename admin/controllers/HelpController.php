<?php

namespace admin\controllers;

use admin\models\SignupForm;
use Yii;
use common\models\User;
use yii\web\ForbiddenHttpException;

/**
 * PagesController implements the CRUD actions for Page model.
 */
class HelpController extends BaseController
{
	public function actionIndex()
	{
		if (\Yii::$app->user->can('admin') || \Yii::$app->user->can('competitions')) {
			return $this->render('help-competitions');
		} elseif (\Yii::$app->user->can('translate')) {
			return $this->redirect('/competitions/translate-messages/translate');
		}
		
		throw new ForbiddenHttpException();
	}
}
