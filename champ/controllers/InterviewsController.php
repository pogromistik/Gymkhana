<?php

namespace champ\controllers;

use common\models\Interview;
use common\models\InterviewAnswer;
use common\models\Vote;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class InterviewsController extends BaseController
{
	/**
	 * @param $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionView($id)
	{
		$this->layout = 'full-content';
		$interview = Interview::findOne($id);
		if (!$interview || $interview->dateStart >= time()) {
			throw new NotFoundHttpException();
		}
		$this->pageTitle = \Yii::t('app', $interview->getTitle());
		
		return $this->render('view', ['interview' => $interview]);
	}
	
	public function actionAddVote($interviewId, $answerId)
	{
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$result = [
			'errors'  => null,
			'success' => false
		];
		if (\Yii::$app->user->isGuest) {
			$result['errors'] = \Yii::t('app', 'Голосовать могут только авторизованные пользователи.');
			
			return $result;
		}
		$interview = Interview::findOne($interviewId);
		if (!$interview) {
			$result['errors'] = \Yii::t('app', 'Опрос не найден');
			
			return $result;
		}
		if ($interview->dateStart > time()) {
			$result['errors'] = \Yii::t('app', 'Приём голосов ещё не начался');
			
			return $result;
		}
		if ($interview->dateEnd < time()) {
			$result['errors'] = \Yii::t('app', 'Приём голосов завершён');
			
			return $result;
		}
		
		$answer = InterviewAnswer::findOne(['interviewId' => $interview->id, 'id' => $answerId]);
		if (!$answer) {
			$result['errors'] = \Yii::t('app', 'Вариант голосования не найден');
			
			return $result;
		}
		
		$mutexName = 'addVote-' . $interview->id . '-athlete-' . \Yii::$app->user->id;
		if (\Yii::$app->mutex->acquire($mutexName, 10)) {
			if ($interview->getMyVote()) {
				$result['errors'] = \Yii::t('app', 'Вы уже проголосовали в этом опросе. Голосовать можно только один раз');
				
				return $result;
			}
			$vote = new Vote();
			$vote->interviewId = $interview->id;
			$vote->answerId = $answer->id;
			$vote->athleteId = \Yii::$app->user->id;
			if (!$vote->save()) {
				$result['errors'] = \Yii::t('app', 'Возникла ошибка');
				
				return $result;
			}
			$result['success'] = true;
			
			return $result;
		} else {
			\Yii::$app->mutex->release($mutexName);
			$result['errors'] = \Yii::t('app', 'Возникла ошибка при отправке данных. Попробуйте снова');
			
			return $result;
		}
		
		return $result;
	}
}