<?php

namespace admin\controllers;

use admin\models\SignupForm;
use common\models\TranslateMessage;
use common\models\TranslateMessageSource;
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
	
	public function actionDownloadTranslate()
	{
		$this->can('developer');
		
		$res = '';
		/** @var TranslateMessageSource[] $items */
		$items = TranslateMessageSource::find()->all();
		foreach ($items as $item) {
			$message =  TranslateMessage::findOne(['id' => $item->id]);
			$res .= $item->message . ';';
			if ($message && $message->translation) {
				$res .= $message->translation;
			}
			$res .= PHP_EOL;
		}
		return $res;
	}
}
