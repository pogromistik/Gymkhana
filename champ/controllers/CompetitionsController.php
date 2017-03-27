<?php
namespace champ\controllers;

use common\models\Athlete;
use common\models\AthletesClass;
use common\models\Championship;
use common\models\City;
use common\models\Figure;
use common\models\FigureTime;
use common\models\Participant;
use common\models\Region;
use common\models\RegionalGroup;
use common\models\Stage;
use common\models\TmpParticipant;
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
	
	public function actionResults($active = null)
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
							'stages' => $championship->stages,
							'status' => $championship->status,
							'id'     => $championship->id
						];
					}
					if (isset($results[$group])) {
						krsort($results[$group]);
					}
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
								->orderBy(['dateOfThe' => SORT_ASC, 'dateAdded' => SORT_ASC])->all(),
							'status' => $item['status'],
							'id'     => $item['id']
						];
						
						if (isset($results[$group])) {
							krsort($results[$group][$item['regionGroupId']]['years']);
							ksort($results[$group]);
						}
					}
					break;
			}
		}
		
		/** @var Figure[] $figures */
		$figures = Figure::find()->orderBy(['title' => SORT_ASC])->all();
		$figuresArray = [];
		foreach ($figures as $figure) {
			$yearIds = $figure->getResults()->select('yearId')->asArray()->column();
			$years = Year::find()->where(['id' => $yearIds])->orderBy(['year' => SORT_DESC])->all();
			$figuresArray[] = [
				'figure' => $figure,
				'years'  => $years
			];
		}
		
		$this->layout = 'main-with-img';
		$this->background = 'background3.png';
		
		return $this->render('results', [
			'results'      => $results,
			'figuresArray' => $figuresArray,
			'active'       => $active
		]);
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
		$this->layout = 'full-content';
		
		$participantsQuery = Participant::find();
		$participantsQuery->select('b.*');
		$participantsQuery->from(['b' => Participant::tableName(), 'c' => AthletesClass::tableName()]);
		$participantsQuery->where(['b.stageId' => $stage->id]);
		$participantsQuery->andWhere(new Expression('"b"."athleteClassId" = "c"."id"'));
		$participantsQuery->andWhere(['b.status' => Participant::STATUS_ACTIVE]);
		$participantsByJapan = $participantsQuery
			->orderBy([
				'c."percent"'  => SORT_ASC,
				'b."bestTime"' => SORT_ASC,
				'b."sort"'     => SORT_ASC,
				'b."id"'       => SORT_ASC
			])
			->all();
		
		if ($stage->championship->internalClasses) {
			$participantsByInternalClasses = Participant::find()->where(['stageId' => $stage->id])->andWhere(['status' => Participant::STATUS_ACTIVE])
				->orderBy(['internalClassId' => SORT_ASC, 'bestTime' => SORT_ASC, 'sort' => SORT_ASC, 'id' => SORT_ASC])->all();
		}
		
		return $this->render('stage', [
			'stage'                         => $stage,
			'participantsByJapan'           => $participantsByJapan,
			'participantsByInternalClasses' => $participantsByInternalClasses
		]);
	}
	
	public function actionFigure($id, $year = null, $showAll = false)
	{
		$figure = Figure::findOne($id);
		if (!$figure) {
			throw new NotFoundHttpException('Фигура не найдена');
		}
		
		$yearModel = null;
		if ($year) {
			$yearModel = Year::findOne(['year' => $year]);
			if (!$yearModel) {
				throw new NotFoundHttpException('Год не найден');
			}
		}
		
		if ($yearModel) {
			$results = $figure->getResults();
			$results = $results->andWhere(['yearId' => $yearModel->id]);
			$results = $results
				->orderBy(['yearId' => SORT_DESC, 'resultTime' => SORT_ASC, 'date' => SORT_DESC, 'dateAdded' => SORT_DESC]);
			if (!$showAll) {
				$results = $results->limit(30);
			}
			$results = $results->all();
		} else {
			$results = FigureTime::find();
			$results->from(new Expression('Athletes, (SELECT *, rank() over (partition by "athleteId" order by "resultTime" asc, "dateAdded" asc) n 
			from "FigureTimes" where "figureId" = ' . $id . ') A'));
			$results->select('*');
			$results->where(new Expression('n=1'));
			$results->andWhere(new Expression('"Athletes"."id"="athleteId"'));
			$results->orderBy(['a."resultTime"' => SORT_ASC]);
			if (!$showAll) {
				$results = $results->limit(30);
			}
			$results = $results->all();
		}
		
		$this->pageTitle = $figure->title;
		$this->description = '';
		$this->keywords = '';
		$this->layout = 'full-content';
		
		return $this->render('figure', [
			'figure'  => $figure,
			'results' => $results,
			'year'    => $yearModel,
			'showAll' => $showAll
		]);
	}
	
	public function actionFigureResultsWithFilters()
	{
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$response = [
			'error' => false,
			'data'  => null
		];
		$figureId = \Yii::$app->request->post('figureId');
		if (!$figureId) {
			$response['error'] = 'Фигура не найдена';
			
			return $response;
		}
		$figure = Figure::findOne($figureId);
		if (!$figure) {
			$response['error'] = 'Фигура не найдена';
			
			return $response;
		}
		$countryId = \Yii::$app->request->post('countryId');
		$regionIds = \Yii::$app->request->post('regionIds');
		$classIds = \Yii::$app->request->post('classIds');
		$yearId = \Yii::$app->request->post('yearId');
		$showAll = \Yii::$app->request->post('showAll');
		$year = null;
		if ($countryId && !$regionIds) {
			$regionIds = Region::find()->select('id')->where(['countryId' => $countryId])->asArray()->column();
		}
		if ($yearId) {
			$year = Year::findOne($yearId);
			if (!$year) {
				$response['error'] = 'Год не найден';
				
				return $response;
			}
		}
		$results = FigureTime::find();
		$subQuery = new Query();
		if ($year) {
			$results->from(['Athletes', 'FigureTimes']);
			$results->select('*');
			$results->andWhere(new Expression('"Athletes"."id"="FigureTimes"."athleteId"'));
			$results->andWhere(['"FigureTimes"."figureId"' => $figureId]);
			if ($regionIds) {
				$results->andWhere(['"Athletes"."regionId"' => $regionIds]);
			}
			if ($classIds) {
				$results->andWhere(['"FigureTimes"."athleteClassId"' => $classIds]);
			}
			$results->orderBy(['"FigureTimes"."resultTime"' => SORT_ASC]);
		} else {
			$subQuery->select('*, rank() over (partition by "athleteId" order by "resultTime" asc, "dateAdded" asc) n');
			$subQuery->from(FigureTime::tableName());
			if ($classIds) {
				$subQuery->where(['athleteClassId' => $classIds]);
			}
			$subQuery->andWhere(['figureId' => $figureId]);
			$results->from(['Athletes',
				'(' . $subQuery->createCommand()->rawSql . ') A']);
			
			$results->select('*');
			$results->where(new Expression('n=1'));
			$results->andWhere(new Expression('"Athletes"."id"="athleteId"'));
			if ($regionIds) {
				$results->andWhere(['"Athletes"."regionId"' => $regionIds]);
			}
			$results->orderBy(['a."resultTime"' => SORT_ASC]);
		}
		
		if (!$showAll) {
			$results = $results->limit(30);
		}
		$results = $results->all();
		
		$response['data'] = $this->renderAjax('_figure-result', ['results' => $results]);
		
		return $response;
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
			$count = ceil(count($numbers) / 3);
			foreach (array_chunk($numbers, $count) as $numbersChunk) {
				$result['numbers'] .= '<div class="col-xs-3">';
				foreach ($numbersChunk as $number) {
					$result['numbers'] .= $number . '<br>';
				}
				$result['numbers'] .= '</div>';
			}
			$result['numbers'] .= '</div>';
		}
		
		return $result;
	}
	
	public function actionAddAuthorizedRegistration()
	{
		if (\Yii::$app->user->isGuest) {
			return 'Сначала войдите в личный кабинет';
		}
		
		$form = new Participant();
		$form->load(\Yii::$app->request->post());
		
		$stage = $form->stage;
		if (!$stage->startRegistration) {
			return 'Регистрация на этап ещё не началась';
		}
		
		if (time() < $stage->startRegistration) {
			return 'Регистрация на этап начнётся ' . $stage->startRegistrationHuman;
		}
		
		if ($stage->endRegistration && time() > $stage->endRegistration) {
			return 'Регистрация на этап завершилась.';
		}
		
		$old = Participant::findOne(['athleteId' => $form->athleteId, 'motorcycleId' => $form->motorcycleId,
		                             'stageId'   => $form->stageId]);
		$championship = $stage->championship;
		$athlete = Athlete::findOne($form->athleteId);
		if ($old) {
			if ($old->status == Participant::STATUS_DISQUALIFICATION) {
				return 'Вы были дисквалифицированы. Повторная регистрация невозможна';
			}
			if ($old->status != Participant::STATUS_ACTIVE) {
				if ($old->number != $form->number) {
					if ($form->number) {
						$freeNumbers = Championship::getFreeNumbers($stage, $form->athleteId);
						if (!in_array($form->number, $freeNumbers)) {
							return 'Номер занят. Выберите другой или оставьте поле пустым.';
						}
						$old->number = $form->number;
					} elseif ($athlete->number && $championship->regionId && $athlete->regionId == $championship->regionId) {
						$old->number = $athlete->number;
					}
				}
				$old->status = Participant::STATUS_ACTIVE;
				if ($old->save()) {
					return true;
				}
				
				return var_dump($old->errors);
			}
			
			return 'Вы уже зарегистрированы на этот этап на этом мотоцикле.';
		}
		
		if (\Yii::$app->mutex->acquire('setNumber' . $stage->id, 10)) {
			if ($form->number) {
				$freeNumbers = Championship::getFreeNumbers($stage, $form->athleteId);
				if (!in_array($form->number, $freeNumbers)) {
					return 'Номер занят. Выберите другой или оставьте поле пустым.';
				}
			} elseif ($athlete->number && $championship->regionId && $athlete->city->regionId == $championship->regionId) {
				$form->number = $athlete->number;
			} else {
				$freeNumbers = Championship::getFreeNumbers($stage);
				if ($freeNumbers) {
					//$form->number = $freeNumbers[0]; //присвоение случайного номера
				}
			}
			\Yii::$app->mutex->release('setNumber' . $stage->id);
			if ($form->save()) {
				\Yii::$app->mutex->release('setNumber' . $stage->id);
				
				return true;
			} else {
				\Yii::$app->mutex->release('setNumber' . $stage->id);
				
				return var_dump($form->errors);
			}
		}
		\Yii::$app->mutex->release('setNumber' . $stage->id);
		
		return 'Внутренняя ошибка. Пожалуйста, попробуйте позже.';
	}
	
	public function actionAddUnauthorizedRegistration()
	{
		$form = new TmpParticipant();
		$form->load(\Yii::$app->request->post());
		
		$stage = $form->stage;
		if (!$stage->startRegistration) {
			return 'Регистрация на этап ещё не началась';
		}
		
		if (time() < $stage->startRegistration) {
			return 'Регистрация на этап начнётся ' . $stage->startRegistrationHuman;
		}
		
		if ($stage->endRegistration && time() > $stage->endRegistration) {
			return 'Регистрация на этап завершилась.';
		}
		
		if (!$form->city && !$form->cityId) {
			return 'Необходимо указать город';
		}
		
		if (\Yii::$app->mutex->acquire('setNumber' . $stage->id, 10)) {
			if ($form->number) {
				$freeNumbers = Championship::getFreeNumbers($stage);
				if (!in_array($form->number, $freeNumbers)) {
					return 'Номер занят. Выберите другой или оставьте поле пустым.';
				}
			} else {
				$freeNumbers = Championship::getFreeNumbers($stage);
				if ($freeNumbers) {
					//$form->number = $freeNumbers[0]; //присвоение случайного номера
				}
			}
			if (!$form->validate()) {
				return 'Необходимо указать имя, фамилию, город, марку и модель мотоцикла.';
			}
			if ($form->save()) {
				\Yii::$app->mutex->release('setNumber' . $stage->id);
				
				return true;
			} else {
				\Yii::$app->mutex->release('setNumber' . $stage->id);
				
				return var_dump($form->errors);
			}
		}
		\Yii::$app->mutex->release('setNumber' . $stage->id);
		
		return 'Внутренняя ошибка. Пожалуйста, попробуйте позже.';
	}
	
	public function actionChampionshipResult($championshipId, $showAll = null)
	{
		$this->pageTitle = 'Итоги чемпионата';
		
		$championship = Championship::findOne($championshipId);
		if (!$championship) {
			throw new NotFoundHttpException('Чемпионат не найден');
		}
		$stages = $championship->stages;
		$results = $championship->getResults($showAll);
		
		return $this->render('championship-results', [
			'championship' => $championship,
			'results'      => $results,
			'stages'       => $stages,
			'showAll'      => $showAll
		]);
	}
}
