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
		if (\Yii::$app->user->can('admin')) {
			return $this->render('help');
		} elseif (\Yii::$app->user->can('competitions')) {
			return $this->render('help-competitions');
		}
		
		throw new ForbiddenHttpException();
	}
}
