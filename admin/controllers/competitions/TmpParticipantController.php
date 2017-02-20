<?php

namespace admin\controllers\competitions;

use dosamigos\editable\EditableAction;
use Yii;
use common\models\TmpParticipant;
use common\models\search\TmpParticipantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TmpParticipantController implements the CRUD actions for TmpParticipant model.
 */
class TmpParticipantController extends Controller
{
	public function actions()
	{
		return [
			'update' => [
				'class'       => EditableAction::className(),
				'modelClass'  => TmpParticipant::className(),
				'forceCreate' => false
			]
		];
	}
	
	/**
	 * Lists all TmpParticipant models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new TmpParticipantSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['status' => TmpParticipant::STATUS_NEW]);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single TmpParticipant model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Finds the TmpParticipant model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return TmpParticipant the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TmpParticipant::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
