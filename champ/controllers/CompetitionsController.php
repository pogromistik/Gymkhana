<?php
namespace champ\controllers;

use common\models\Athlete;
use common\models\Championship;
use common\models\City;
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
								->orderBy(['dateOfThe' => SORT_DESC, 'dateAdded' => SORT_ASC])->all()
						];
						
						if (isset($results[$group])) {
							krsort($results[$group][$item['regionGroupId']]['years']);
							ksort($results[$group]);
						}
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
			$count = ceil(count($numbers) / 3);
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
	
	public function actionAddAuthorizedRegistration()
	{
		if (\Yii::$app->user->isGuest) {
			return 'Сначала войдите в личный кабинет';
		}
		
		$form = new Participant();
		$form->load(\Yii::$app->request->post());
		if (!$form->validate()) {
			return 'Необходимо указать имя, фамилию, город, марку и модель мотоцикла.';
		}
		
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
		if ($old) {
			if ($old->status != Participant::STATUS_ACTIVE) {
				$old->status = Participant::STATUS_ACTIVE;
				if ($old->save()) {
					return true;
				}
				
				return var_dump($old->errors);
			}
			
			return 'Вы уже зарегистрированы на этот этап на этом мотоцикле.';
		}
		
		$championship = $stage->championship;
		$athlete = Athlete::findOne($form->athleteId);
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
		if (!$form->validate()) {
			return 'Необходимо указать имя, фамилию, город, марку и модель мотоцикла.';
		}
		
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
}
