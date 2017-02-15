<?php
namespace champ\controllers;

use common\models\Athlete;
use common\models\Championship;
use common\models\City;
use common\models\Participant;
use common\models\Region;
use common\models\RegionalGroup;
use common\models\Stage;
use common\models\Year;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class CompetitionsController extends BaseController
{
	public function actionSchedule()
	{
		$this->pageTitle = 'Расписание соревнований';
		$this->description = 'Расписание соревнований';
		$this->keywords = 'Расписание соревнований';
		
		$championships = [];
		/*foreach (Championship::$groupsTitle as $group => $title) {
			switch ($group) {
				case Championship::GROUPS_RUSSIA:
					$championships[$group] = Championship::find()->where(['groupId' => $group])->orderBy(['dateAdded' => SORT_DESC])->all();
					break;
				case Championship::GROUPS_REGIONAL:
					$query = Championship::find();
					$query->from(['a' => Championship::tableName(), 'b' => RegionalGroup::tableName()]);
					$query->select('"a".*, "b".title as "groupTitle"');
					$query->where(['a."groupId"' => $group]);
					$query->andWhere(new Expression('"a"."regionGroupId" = "b"."id"'));
					$query->orderBy(['a."regionGroupId"' => SORT_ASC, 'a."dateAdded"' => SORT_DESC]);
					$result = $query->asArray()->all();
					
					foreach ($result as $item) {
						if (!isset($championships[$group][$item['regionGroupId']])) {
							$championships[$group][$item['regionGroupId']] = [
								'title'         => $item['groupTitle'],
								'championships' => []
							];
						}
						$championships[$group][$item['regionGroupId']]['championships'][] = $item;
					}
					break;
			}
		}*/
		
		foreach (Championship::$groupsTitle as $group => $title) {
			$championships[$group] = Championship::find()->where(['groupId' => $group])
				->andWhere(['status' => Championship::$statusesForActual])->orderBy(['dateAdded' => SORT_DESC])->all();
		}
		
		return $this->render('schedule', ['championships' => $championships]);
	}
	
	public function actionResults()
	{
		$this->pageTitle = 'Расписание соревнований';
		$this->description = 'Расписание соревнований';
		$this->keywords = 'Расписание соревнований';
		
		$results = [];
		foreach (Championship::$groupsTitle as $group => $title) {
			switch ($group) {
				case Championship::GROUPS_RUSSIA:
					/** @var Championship[] $championships */
					$championships = Championship::find()->where(['groupId' => $group])->orderBy(['dateAdded' => SORT_DESC])->all();
					foreach ($championships as $championship) {
						$results[$group][$championship->yearId] = [
							'year'   => $championship->year->year,
							'stages' => $championship->stages
						];
					}
					krsort($results[$group]);
					break;
				case Championship::GROUPS_REGIONAL:
					$query = Championship::find();
					$query->from(['a' => Championship::tableName(), 'b' => RegionalGroup::tableName(), 'c' => Year::tableName()]);
					$query->select('"a".*, "b".title as "groupTitle", "c"."year"');
					$query->where(['a."groupId"' => $group]);
					$query->andWhere(new Expression('"a"."regionGroupId" = "b"."id"'));
					$query->andWhere(new Expression('"a"."yearId" = "c"."id"'));
					$query->orderBy(['a."regionGroupId"' => SORT_ASC, 'a."dateAdded"' => SORT_DESC]);
					$championships = $query->asArray()->all();
					
					foreach ($championships as $item) {
						if (!isset($results[$group][$item['regionGroupId']])) {
							$results[$group][$item['regionGroupId']] = [
								'title' => $item['groupTitle'],
								'years' => []
							];
						}
						$results[$group][$item['regionGroupId']]['years'][$item['yearId']] = [
							'year'   => $item['year'],
							'stages' => Stage::find()->where(['championshipId' => $item['id']])
								->orderBy(['dateOfThe' => SORT_DESC, 'dateAdded' => SORT_ASC])->all()
						];
						
						krsort($results[$group][$item['regionGroupId']]['years']);
						ksort($results[$group]);
					}
					break;
			}
		}
		
		return $this->render('results', ['results' => $results]);
	}
	
	public function actionStage($id)
	{
		$stage = Stage::findOne($id);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		$this->pageTitle = $stage->title;
		$this->description = '';
		$this->keywords = '';
		
		return $this->render('stage', [
			'stage' => $stage
		]);
	}
	
	public function actionGetFreeNumbers($stageId)
	{
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$result = [
			'success' => false,
			'error'   => false,
			'numbers' => null
		];
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			$result['error'] = 'Этап не найден';
			
			return $result;
		}
		$numbers = Championship::getFreeNumbers($stage);
		
		$result['success'] = true;
		if (!$numbers) {
			$result['numbers'] = 'Свободных номеров нет';
		} else {
			$result['numbers'] = '<div class="row">';
			$count = ceil(count($numbers)/3);
			foreach (array_chunk($numbers, $count) as $numbersChunk) {
				$result['numbers'] .= '<div class="col-md-3">';
				foreach ($numbersChunk as $number) {
					$result['numbers'] .= $number . '<br>';
				}
				$result['numbers'] .= '</div>';
			}
			$result['numbers'] .= '</div>';
		}
		
		return $result;
	}
}
