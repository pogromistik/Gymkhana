<?php
namespace champ\controllers;

use common\models\Notice;
use common\models\search\NoticesSearch;
use yii\web\NotFoundHttpException;

class NoticesController extends AccessController
{
	public function actionCount()
	{
		$notices = Notice::getAll(Notice::STATUS_NEW);
		
		return count($notices);
	}
	
	public function actionAll()
	{
		$searchModel = new NoticesSearch();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['athleteId' => \Yii::$app->user->id]);
		
		$this->pageTitle = 'Уведомления';
		
		return $this->render('all', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider
		]);
	}
	
	public function actionFindNewNotices()
	{
		/** @var $notices $notices */
		$notices = Notice::find()->where(['athleteId' => \Yii::$app->user->id,
		'status' => Notice::STATUS_NEW])->orderBy(['dateAdded' => SORT_DESC])->all();
		if (!$notices) {
			return '<div class="text-center">Новых уведомлений нет</div>';
		}
		Notice::updateAll(['status' => Notice::STATUS_DONE],
			['athleteId' => \Yii::$app->user->id, 'status' => Notice::STATUS_NEW]);
		
		return $this->renderAjax('new-notices', ['notices' => $notices]);
	}
	
	public function actionChangeStatus($id)
	{
		$model = self::findModel($id);
		switch ($model->status) {
			case Notice::STATUS_NEW:
				$model->status = Notice::STATUS_DONE;
				break;
			case Notice::STATUS_DONE:
				$model->status = Notice::STATUS_NEW;
				break;
		}
		if ($model->save()) {
			return true;
		} else {
			return $model->errors;
		}
	}
	
	protected function findModel($id)
	{
		$model = Notice::findOne(['id' => $id, 'userId' => \Yii::$app->user->id]);
		if (!$model) {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		
		return $model;
	}
}