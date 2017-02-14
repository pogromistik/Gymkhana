<?php
namespace champ\controllers;

use common\models\Athlete;
use common\models\Motorcycle;
use yii\web\NotFoundHttpException;

class ProfileController extends AccessController
{
	public function actionIndex($success = false)
	{
		$this->pageTitle = 'Редактирование профиля';
		$athlete = Athlete::findOne(\Yii::$app->user->identity->id);
		if (!$athlete) {
			throw new NotFoundHttpException('Ошибка! Спортсмен не найден');
		}
		
		if ($athlete->load(\Yii::$app->request->post()) && $athlete->save()) {
			return $this->redirect(['index', 'success' => true]);
		}
		
		$motorcycle = new Motorcycle();
		if ($motorcycle->load(\Yii::$app->request->post()) && $motorcycle->save()) {
			return $this->redirect(['index', 'success' => true]);
		}
		
		return $this->render('index', ['athlete' => $athlete, 'success' => $success]);
	}
	
	public function actionChangeStatus($id)
	{
		$motorcycle = Motorcycle::findOne($id);
		if (!$motorcycle || $motorcycle->athleteId != \Yii::$app->user->identity->id) {
			return 'Мотоцикл не найден';
		}
		if ($motorcycle->status) {
			$motorcycle->status = Motorcycle::STATUS_INACTIVE;
		} else {
			$motorcycle->status = Motorcycle::STATUS_ACTIVE;
		}
		
		if ($motorcycle->save()) {
			return true;
		}
		
		return 'Возникла ошибка при изменении данных';
	}
}