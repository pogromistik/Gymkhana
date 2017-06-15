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
use yii\bootstrap\ActiveForm;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			$dataProvider->query->andWhere(['or',
				['regionId' => null],
				['regionId' => \Yii::$app->user->identity->regionId]
			]);
		}
		
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
		$this->can('projectAdmin');
		
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
		$this->can('projectAdmin');
		
		$model = $this->findModel($id);
		
		if (!\Yii::$app->user->can('globalWorkWithCompetitions') && $model->regionId
			&& $model->regionId != \Yii::$app->user->identity->regionId) {
			throw new ForbiddenHttpException('Доступ запрещён');
		}
		
		if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
			\Yii::$app->response->format = Response::FORMAT_JSON;
			
			return ActiveForm::validate($model);
		}
		
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
	 * Finds the Championship model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Championship the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 * @throws ForbiddenHttpException if access denied
	 */
	protected function findModel($id)
	{
		$this->can('competitions');
		
		if (($model = Championship::findOne($id)) !== null) {
			if (!\Yii::$app->user->can('globalWorkWithCompetitions') && $model->regionId
				&& $model->regionId != \Yii::$app->user->identity->regionId) {
				throw new ForbiddenHttpException('Доступ запрещён');
			}
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionAddGroup()
	{
		$this->can('projectAdmin');
		
		$group = new RegionalGroup();
		if ($group->load(\Yii::$app->request->post()) && $group->validate()) {
			$group->save(false);
			return true;
		} else {
			return 'Возникла ошибка при добавлении группы';
		}
	}
	
	public function actionAddClass()
	{
		$this->can('projectAdmin');
		
		$class = new InternalClass();
		if ($class->load(\Yii::$app->request->post()) && $class->validate()) {
			$championship = Championship::findOne($class->championshipId);
			if ($championship->regionId && $championship->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещен';
			}
			$class->save();
			return true;
		}
		
		return 'Возникла ошибка при добавлении класса';
	}
	
	public function actionChangeClassStatus($id, $status)
	{
		$this->can('projectAdmin');
		
		$class = InternalClass::findOne($id);
		if (!$class) {
			return 'Класс не найден';
		}
		if (!array_key_exists($status, InternalClass::$statusesTitle)) {
			return 'Статус не существует';
		}
		$championship = Championship::findOne($class->championshipId);
		if ($championship->regionId && $championship->regionId != \Yii::$app->user->identity->regionId) {
			return 'Доступ запрещен';
		}
		if ($status == InternalClass::STATUS_INACTIVE) {
			if (!Participant::findOne(['championshipId' => $class->championshipId, 'internalClassId' => $class->id])) {
				$class->delete();
				return true;
			}
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
					$results[$participant->athleteId]['stages'][$stage->id] = $championship->useMoscowPoints ? $participant->pointsByMoscow : $participant->points;
					$results[$participant->athleteId]['points'] += $championship->useMoscowPoints ? $participant->pointsByMoscow : $participant->points;
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
