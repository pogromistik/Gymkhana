<?php

namespace admin\components;

use admin\controllers\BaseController;
use common\models\Participant;
use common\models\Stage;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class BaseStageController extends BaseController
{
	public $stage;
	public $errors;
	public $criticalErrors;
	
	private function checkStage()
	{
		if ($this->stage->championship->internalClasses) {
			$participants = $this->stage->getParticipants()
				->andWhere(['status' => [Participant::STATUS_ACTIVE, Participant::STATUS_OUT_COMPETITION]])
				->andWhere(['internalClassId' => null])
				->count();
			if ($participants > 0) {
				$this->errors[] = 'Для некоторых спортсменов (' . $participants . ' чел.) не установлен класс
				награждения.';
			}
		}
		if ($this->stage->dateOfThe) {
			//день проведения этапа
			if (date('d.m.Y', $this->stage->dateOfThe) == date('d.m.Y')) {
				if (!$this->stage->class) {
					$this->errors[] = 'Не установлен класс соревнования';
				}
			}
		}
		if ($this->stage->class) {
			//этап ещё не прошел
			if ($this->stage->status != Stage::STATUS_CANCEL && $this->stage->status != Stage::STATUS_PAST) {
				$actualClass = $this->stage->classCalculate();
				if (!$actualClass) {
					$this->errors[] = 'Установленный класс соревнования отличается от актуального: ' .
						'установлен - ' . $this->stage->classModel->title . ', актуальный - невозможно посчитать';
				} elseif ($this->stage->class != $actualClass->id) {
					$this->errors[] = 'Установленный класс соревнования отличается от актуального: ' .
						'установлен - ' . $this->stage->classModel->title . ', актуальный - ' . $actualClass->title;
				}
			}
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
	protected function findStage($id)
	{
		$this->can('competitions');
		
		if (($model = Stage::findOne($id)) !== null) {
			if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
				if ($model->regionId != \Yii::$app->user->identity->regionId) {
					throw new ForbiddenHttpException('Доступ запрещен');
				}
			}
			$this->stage = $model;
			$this->checkStage();
			
			return $model;
		} else {
			throw new NotFoundHttpException('Страница не найдена. Возможно, ' .
				'она была съедена макаронным монстром.');
		}
	}
}
