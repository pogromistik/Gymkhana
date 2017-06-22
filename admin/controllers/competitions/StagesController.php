<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use admin\models\FigureTimeForStage;
use common\models\Athlete;
use common\models\AthletesClass;
use common\models\Championship;
use common\models\ClassHistory;
use common\models\Figure;
use common\models\FigureTime;
use common\models\MoscowPoint;
use common\models\Participant;
use common\models\Year;
use Yii;
use common\models\Stage;
use common\models\search\StageSearch;
use yii\base\UserException;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * StagesController implements the CRUD actions for Stage model.
 */
class StagesController extends BaseController
{
	public function actionView($id)
	{
		$this->can('refereeOfCompetitions');
		$model = $this->findModel($id);
		
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($model->regionId != \Yii::$app->user->identity->regionId) {
				throw new ForbiddenHttpException('Доступ запрещен');
			}
		}
		
		return $this->render('view', [
			'model' => $model,
		]);
	}
	
	public function actionCreate($championshipId, $errorCity = null, $success = null)
	{
		$this->can('projectAdmin');
		
		$championship = Championship::findOne($championshipId);
		if (!$championship) {
			throw new NotFoundHttpException('Чемпионат не найден');
		}
		if ($championship->status == Championship::STATUS_PAST) {
			throw new UserException('Чемпионат завершен, добавление этапов невозможно');
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($championship->regionId && $championship->regionId != \Yii::$app->user->identity->regionId) {
				throw new ForbiddenHttpException('Доступ запрещен');
			}
		}
		
		$model = new Stage();
		$model->championshipId = $championshipId;
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model'     => $model,
				'errorCity' => $errorCity,
				'success'   => $success
			]);
		}
	}
	
	/**
	 * Updates an existing Stage model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws ForbiddenHttpException
	 */
	public function actionUpdate($id)
	{
		$this->can('projectAdmin');
		
		$model = $this->findModel($id);
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($model->regionId != \Yii::$app->user->identity->regionId) {
				throw new ForbiddenHttpException('Доступ запрещен');
			}
		}
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Finds the Stage model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Stage the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 * @throws ForbiddenHttpException
	 */
	protected function findModel($id)
	{
		$this->can('competitions');
		
		if (($model = Stage::findOne($id)) !== null) {
			if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
				if ($model->regionId != \Yii::$app->user->identity->regionId) {
					throw new ForbiddenHttpException('Доступ запрещен');
				}
			}
			
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionResult($stageId)
	{
		$this->can('competitions');
		
		$stage = $this->findModel($stageId);
		$participants = $stage->getParticipants()
			->andWhere(['status' => [Participant::STATUS_ACTIVE, Participant::STATUS_DISQUALIFICATION, Participant::STATUS_OUT_COMPETITION]])
			->orderBy(['status' => SORT_ASC, 'bestTime' => SORT_ASC])->all();
		
		return $this->render('result', [
			'stage'        => $stage,
			'participants' => $participants
		]);
	}
	
	public function actionCalculationResult($id)
	{
		$this->can('competitions');
		
		$stage = Stage::findOne($id);
		if (!$stage) {
			return 'Этап не найден';
		}
		if (!$stage->class) {
			return 'Не установлен класс соревнований';
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещен';
			}
		}
		
		return $stage->placesCalculate();
	}
	
	public function actionAddFiguresResults($stageId)
	{
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				throw new NotFoundHttpException('Доступ запрещен');
			}
		}
		if ($stage->status == Stage::STATUS_CALCULATE_RESULTS || $stage->status == Stage::STATUS_PAST) {
			throw new UserException('Этап близится к завершению или завершен, 
			добавление результатов по фигурам невозможно');
		}
		
		$participants = $stage->activeParticipants;
		$figures = Figure::find()->where(['useForClassesCalculate' => 1])->orderBy(['title' => SORT_ASC])->all();
		
		$figureTime = new FigureTimeForStage();
		$figureTime->stageId = $stage->id;
		$figureTime->date = time();
		
		return $this->render('add-figures-result', [
			'participants' => $participants,
			'figures'      => $figures,
			'stage'        => $stage,
			'figureTime'   => $figureTime
		]);
	}
	
	public function actionCheckFigureTime()
	{
		$error = false;
		$figureTime = new FigureTimeForStage();
		$athlete = null;
		if ($figureTime->load(\Yii::$app->request->post())) {
			if (!$figureTime->validate()) {
				return '<div class="alert alert-danger">Необходимо заполнить все поля</div>';
			}
			$stage = Stage::findOne($figureTime->stageId);
			if (!$stage) {
				$error = 'Этап не найден';
			} elseif (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
				if ($stage->regionId != \Yii::$app->user->identity->regionId) {
					$error = 'Доступ запрещен';
				}
			} elseif ($stage->status == Stage::STATUS_CALCULATE_RESULTS || $stage->status == Stage::STATUS_PAST) {
				$error = 'Этап близится к завершению или завершен, 
			добавление результатов по фигурам невозможно';
			}
			
			if (!$error) {
				$figure = Figure::findOne($figureTime->figureId);
				if (!$figure) {
					$error = 'Фигура не найдена';
				} else {
					$participant = Participant::findOne($figureTime->participantId);
					if (!$participant) {
						$error = 'Участник не найден';
					} elseif ($participant->stageId != $stage->id) {
						$error = 'Участник зарегистрирован на другой этап';
					}
				}
				
				if (!$error) {
					list($min, $secs) = explode(':', $figureTime->timeForHuman);
					$time = ($min * 60000) + $secs * 1000;
					$figureTime->resultTime = $time + $figureTime->fine * 1000;
					
					//процент
					if ($figure->bestTime) {
						$figureTime->percent = round($figureTime->resultTime / $figure->bestTime * 100, 2);
					} else {
						$figureTime->percent = 100;
					}
					
					//новый класс
					$athlete = $participant->athlete;
					/** @var AthletesClass $newClass */
					$newClass = AthletesClass::find()->where(['>=', 'percent', $figureTime->percent])
						->andWhere(['status' => AthletesClass::STATUS_ACTIVE])
						->orderBy(['percent' => SORT_ASC, 'title' => SORT_DESC])->one();
					if ($newClass && $newClass->id != $athlete->athleteClassId) {
						if ($athlete->athleteClassId) {
							$oldClass = $athlete->athleteClass;
							if ($oldClass->id != $newClass->id && $oldClass->percent > $newClass->percent) {
								$figureTime->newClassId = $newClass->id;
								$figureTime->newClassTitle = $newClass->title;
							}
						} else {
							$figureTime->newClassId = $newClass->id;
							$figureTime->newClassTitle = $newClass->title;
						}
					}
					if (!$figureTime->newClassId && $newClass && $newClass->id != $participant->athleteClassId) {
						if ($participant->athleteClassId) {
							$oldClass = $participant->athleteClass;
							if ($oldClass->id != $newClass->id && $oldClass->percent > $newClass->percent) {
								$figureTime->newClassForParticipant = $newClass->id;
								$figureTime->newClassTitle = $newClass->title;
							}
						}
						else {
							$figureTime->newClassForParticipant = $newClass->id;
							$figureTime->newClassTitle = $newClass->title;
						}
					}
				}
			}
		} else {
			$error = 'Произошла ошибка при отправке данных';
		}
		
		return $this->renderAjax('check-figure-time', [
			'figureTime' => $figureTime,
			'error'      => $error,
			'athlete'    => $athlete
		]);
	}
	
	public function actionAddFigureTime()
	{
		$error = false;
		$figureTime = new FigureTimeForStage();
		$athlete = null;
		if ($figureTime->load(\Yii::$app->request->post())) {
			$correctNewClass = true;
			$stage = Stage::findOne($figureTime->stageId);
			if (!$stage) {
				$error = 'Этап не найден';
			} elseif (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
				if ($stage->regionId != \Yii::$app->user->identity->regionId) {
					$error = 'Доступ запрещен';
				}
			} elseif ($stage->status == Stage::STATUS_CALCULATE_RESULTS || $stage->status == Stage::STATUS_PAST) {
				$error = 'Этап близится к завершению или завершен, 
			добавление результатов по фигурам невозможно';
			}
			
			if (!$error) {
				$figure = Figure::findOne($figureTime->figureId);
				if (!$figure) {
					$error = 'Фигура не найдена';
				} else {
					$participant = Participant::findOne($figureTime->participantId);
					if (!$participant) {
						$error = 'Участник не найден';
					} elseif ($participant->stageId != $stage->id) {
						$error = 'Участник зарегистрирован на другой этап';
					}
				}
				
				if (!$error) {
					//проверка нового класса
					$athlete = $participant->athlete;
					if (!$figureTime->newClassId) {
						$correctNewClass = false;
					}
					$oldClass = $athlete->athleteClass;
					$oldClassId = $oldClass->id;
					if ($athlete->athleteClassId && $correctNewClass) {
						$oldClass = $athlete->athleteClass;
						$newClass = AthletesClass::findOne($figureTime->newClassId);
						if ($oldClass->percent < $newClass->percent) {
							$correctNewClass = false;
						}
					}
					
					$newTime = new FigureTime();
					$newTime->athleteClassId = $oldClassId;
					$newTime->timeForHuman = $figureTime->timeForHuman;
					$newTime->fine = $figureTime->fine;
					$newTime->date = $figureTime->date;
					$newTime->figureId = $figureTime->figureId;
					$newTime->athleteId = $athlete->id;
					$newTime->motorcycleId = $figureTime->motorcycleId;
					$newTime->percent = $figureTime->percent;
					$newTime->resultTime = $figureTime->resultTime;
					$newTime->needClassCalculate = false;
					$transaction = \Yii::$app->db->beginTransaction();
					if ($correctNewClass) {
						$newTime->newAthleteClassId = $figureTime->newClassId;
						$newTime->newAthleteClassStatus = FigureTime::NEW_CLASS_STATUS_APPROVE;
					}
					if (!$newTime->save()) {
						$transaction->rollBack();
						
						return var_dump($newTime->errors);
					}
					
					if ($correctNewClass) {
						$history = ClassHistory::create($athlete->id, $newTime->motorcycleId,
							$oldClassId, $newTime->newAthleteClassId, $figure->title,
							$newTime->resultTime, $figure->bestTime, $newTime->percent);
						if (!$history) {
							$transaction->rollBack();
							
							return var_dump($history->errors);
						}
						
						$athlete->athleteClassId = $newTime->newAthleteClassId;
						if (!$athlete->save()) {
							$transaction->rollBack();
							
							return var_dump($athlete->errors);
						}
						
						$participant->athleteClassId = $newTime->newAthleteClassId;
						if (!$participant->save(false)) {
							$transaction->rollBack();
							
							return var_dump($participant->errors);
						}
					} elseif ($figureTime->newClassForParticipant) {
						$athlete = $participant->athlete;
						$newClass = AthletesClass::findOne($figureTime->newClassForParticipant);
						$athleteClass = $athlete->athleteClass;
						if ($athleteClass && $athleteClass->percent > $newClass->percent) {
							$transaction->rollBack();
							return '<div class="alert alert-error">Класс спортсмена не может быть ниже класса участника</div>';
						}
						$participantClass = $participant->athleteClass;
						if ($participantClass->percent < $newClass->percent) {
							$transaction->rollBack();
							return '<div class="alert alert-error">Нельзя понизить класс участника</div>';
						}
						if ($participant->athleteClassId != $newClass->id) {
							$participant->athleteClassId = $newClass->id;
							if (!$participant->save(false)) {
								$transaction->rollBack();
								
								return var_dump($participant->errors);
							}
						}
					}
					$transaction->commit();
					
					return '<div class="alert alert-success">Результат спортсмену ' . $athlete->getFullName()
						. ' успешно добавлен.</div>';
				}
			}
		}
		$error = 'Произошла ошибка при отправке данных';
		
		return '<div class="alert alert-error">' . $error . '</div>';
	}
	
	public function actionAccruePoints($stageId)
	{
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				throw new NotFoundHttpException('Доступ запрещен');
			}
		}
		if (!$stage->championship->useMoscowPoints) {
			throw new UserException('Функция доступна только для чемпионатов, использующих Московскую схему начисления баллов');
		}
		if ($stage->calculatePoints()) {
			return true;
		}
		return 'При начислении баллов за этап возникла ошибка. Свяжитесь с разработчиком.';
	}
}
