<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\InternalClass;
use common\models\Participant;
use common\models\RegionalGroup;
use common\models\Stage;
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
		$this->can('competitions');
		
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
		$this->can('competitions');
		
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	public function actionCreate($groupId)
	{
		$this->can('competitions');
		
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
		$this->can('competitions');
		
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
		$this->can('competitions');
		
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
		$this->can('competitions');
		
		if (($model = Championship::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionAddGroup()
	{
		$this->can('competitions');
		
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
	
	public function actionResults($championshipId, $showAll = null)
	{
		$this->can('competitions');
		$championship = $this->findModel($championshipId);
		$stages = $championship->stages;
		$results = [];
		foreach ($stages as $stage) {
			/** @var Participant[] $participants */
			$participants = Participant::find()->where(['stageId' => $stage->id])->andWhere(['status' => Participant::STATUS_ACTIVE])
				->orderBy(['points' => SORT_DESC, 'sort' => SORT_ASC])->all();
			foreach ($participants as $participant) {
				if (!isset($results[$participant->athleteId])) {
					$results[$participant->athleteId] = [
						'athlete'        => $participant->athlete,
						'points'         => 0,
						'stages'         => [],
						'countStages'    => 0,
						'cityId'         => null,
						'severalRegions' => false
					];
				}
				if (!isset($results[$participant->athleteId]['stages'][$stage->id])) {
					$results[$participant->athleteId]['stages'][$stage->id] = $participant->points;
					$results[$participant->athleteId]['points'] += $participant->points;
					$results[$participant->athleteId]['countStages'] += 1;
					if (!$results[$participant->athleteId]['cityId']) {
						$results[$participant->athleteId]['cityId'] = $stage->cityId;
					} else {
						if ($stage->cityId != $results[$participant->athleteId]['cityId']) {
							$results[$participant->athleteId]['severalRegions'] = true;
						}
					}
				}
			}
		}
		
		if (!$showAll) {
			foreach ($results as $i => $result) {
				if ($result['countStages'] < $championship->amountForAthlete) {
					unset($results[$i]);
					continue;
				}
				if ($championship->requiredOtherRegions && !$result['severalRegions']) {
					unset($results[$i]);
					continue;
				}
				if (count($result['stages']) != $championship->estimatedAmount) {
					$allPoints = $result['stages'];
					arsort($allPoints);
					$count = 0;
					$result['points'] = 0;
					foreach ($allPoints as $stagePoint) {
						if ($count < $championship->estimatedAmount) {
							$result['points'] += $stagePoint;
							$count++;
						} else {
							break;
						}
					}
				}
			}
		}
		
		uasort($results, "self::cmpByRackPlaces");
		
		return $this->render('results', [
			'championship' => $championship,
			'results'      => $results,
			'stages'       => $stages,
			'showAll'      => $showAll
		]);
	}
	
	private function cmpByRackPlaces($a, $b)
	{
		return ($a['points'] > $b['points']) ? -1 : 1;
	}
}
