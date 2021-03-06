<?php

namespace champ\controllers;

use common\models\Athlete;
use common\models\HelpModel;
use common\models\MainPhoto;
use common\models\OverallFile;
use common\models\SpecialStage;
use common\models\Stage;
use common\models\TranslateMessage;
use common\models\Work;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class BaseController extends Controller
{
	public $description = '';
	public $pageTitle = '';
	public $keywords = '';
	public $url = '';
	public $background = 'background2.PNG';
	
	public function actionDownload($id)
	{
		$file = OverallFile::findOne($id);
		if (!$file) {
			throw new NotFoundHttpException(\Yii::t('app', 'Файл не найден'));
		}
		
		return \Yii::$app->response->sendFile(\Yii::getAlias('@files') . '/' . $file->filePath, $file->fileName);
	}
	
	public function init()
	{
		parent::init();
		$isBlockedSite = Work::findOne(['status' => 1]);
		if ($isBlockedSite) {
			return $this->redirect(['/work/page']);
		}
		
		$hostName = \Yii::$app->request->getHostName();
		switch ($hostName) {
			case 'gymkhana-cup.com':
				\Yii::$app->language = TranslateMessage::LANGUAGE_EN;
				break;
			default:
				\Yii::$app->language = TranslateMessage::LANGUAGE_RU;
		}
		
		if (mb_stripos(\Yii::$app->request->referrer, 'login') === false &&
			mb_stripos(\Yii::$app->request->referrer, 'logout') === false) {
			\Yii::$app->session->set('referrer', \Yii::$app->request->referrer);
		}
		
		if (!\Yii::$app->user->isGuest) {
			$user = Athlete::findOne(\Yii::$app->user->id);
			$user->lastActivityDate = time();
			$user->save();
			\Yii::$app->language = $user->language;
		}
		
		if (!\Yii::$app->cache->get('special_stage')) {
			$now = time();
			$specialStage = SpecialStage::find()->where(['<=', 'dateStart', $now])
				->andWhere(['>=', 'dateEnd', $now])->andWhere(['not', ['dateStart' => null]])->orderBy(['dateStart' => SORT_DESC])->one();
			if ($specialStage) {
				\Yii::$app->cache->set('special_stage', $specialStage, 3600);
			}
		}
	}
}
