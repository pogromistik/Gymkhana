<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\InternalClass;
use common\models\RegionalGroup;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\Championship;
use common\models\search\ChampionshipSearch;
use yii\web\NotFoundHttpException;

/**
 * ChampionshipsController implements the CRUD actions for Championship model.
 */
class ChampionshipsController extends BaseController
{
	public function actions()
	{
		return [
			'update-group' => [
				'class'       => EditableAction::className(),
				'modelClass'  => RegionalGroup::className(),
				'forceCreate' => false
			],
			'update-class' => [
				'class'       => EditableAction::className(),
				'modelClass'  => InternalClass::className(),
				'forceCreate' => false
			]
		];
	}
	
	public function actionIndex($groupId = null)
	{
		if (!$groupId) {
			return $this->render('select-group');
		}
		$searchModel = new ChampionshipSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['groupId' => $groupId]);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'groupId'      => $groupId
		]);
	}
	
	/**
	 * Displays a single Championship model.
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
	
	public function actionCreate($groupId)
	{
		$model = new Championship();
		$model->groupId = $groupId;
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model'   => $model,
				'groupId' => $groupId
			]);
		}
	}
	
	public function actionUpdate($id, $success = false)
	{
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		} else {
			return $this->render('update', [
				'model'   => $model,
				'success' => $success
			]);
		}
	}
	
	/**
	 * Deletes an existing Championship model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the Championship model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Championship the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Championship::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionAddGroup()
	{
		$group = new RegionalGroup();
		if ($group->load(\Yii::$app->request->post()) && $group->save()) {
			return true;
		} else {
			return 'Возникла ошибка при добавлении группы';
		}
	}
	
	public function actionAddClass()
	{
		$this->can('competitions');
		
		$class = new InternalClass();
		if ($class->load(\Yii::$app->request->post()) && $class->save()) {
			return true;
		}
		
		return 'Возникла ошибка при добавлении класса';
	}
	
	public function actionChangeClassStatus($id, $status)
	{
		$this->can('competitions');
		
		$class = InternalClass::findOne($id);
		if (!$class) {
			return 'Класс не найден';
		}
		if (!array_key_exists($status, InternalClass::$statusesTitle)) {
			return 'Статус не существует';
		}
		$class->status = $status;
		if ($class->save()) {
			return true;
		}
		
		return 'Возникла ошибка при добавлении класса';
	}
}
