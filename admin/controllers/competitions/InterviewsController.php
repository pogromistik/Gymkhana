<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\HelpModel;
use common\models\InterviewAnswer;
use common\models\Vote;
use Yii;
use common\models\Interview;
use common\models\search\InterviewSearch;
use yii\base\UserException;
use yii\web\NotFoundHttpException;

/**
 * InterviewsController implements the CRUD actions for Interview model.
 */
class InterviewsController extends BaseController
{
	/**
	 * @return void|\yii\web\Response
	 * @throws \yii\web\ForbiddenHttpException
	 */
	public function init()
	{
		parent::init();
		$this->can('globalWorkWithCompetitions');
	}
	
	/**
	 * Lists all Interview models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new InterviewSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Creates a new Interview model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Interview();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['answers', 'id' => $model->id]);
		}
		
		return $this->render('create', [
			'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing Interview model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['answers', 'id' => $model->id]);
		}
		
		return $this->render('update', [
			'model' => $model,
		]);
	}
	
	/**
	 * Finds the Interview model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Interview the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Interview::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	/**
	 * @param $id
	 *
	 * @return string|\yii\web\Response
	 * @throws NotFoundHttpException
	 */
	public function actionAnswers($id)
	{
		$interview = $this->findModel($id);
		$answer = new InterviewAnswer();
		$answer->interviewId = $interview->id;
		if ($answer->load(\Yii::$app->request->post()) && $answer->save()) {
			return $this->redirect(['answers', 'id' => $id]);
		}
		
		return $this->render('answers', [
			'interview' => $interview,
			'answer'    => $answer
		]);
	}
	
	/**
	 * @param $id
	 *
	 * @return string|\yii\web\Response
	 * @throws NotFoundHttpException
	 */
	public function actionAnswerEdit($id)
	{
		$answer = InterviewAnswer::findOne($id);
		if (!$answer) {
			throw new NotFoundHttpException();
		}
		if ($answer->load(\Yii::$app->request->post()) && $answer->save()) {
			return $this->redirect(['answers', 'id' => $answer->interviewId]);
		}
		
		return $this->render('answers-edit', [
			'answer' => $answer
		]);
	}
	
	/**
	 * @param $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionAnswerDelete($id)
	{
		$answer = InterviewAnswer::findOne($id);
		if (!$answer) {
			throw new NotFoundHttpException();
		}
		if (count($answer->votes) > 0) {
			throw new UserException('Нельзя удалить вариант ответа, т.к. для него есть голоса');
		}
		if ($answer->imgPath) {
			HelpModel::deleteFile($answer->imgPath);
		}
		$answer->delete();
		
		return $this->render('answers', [
			'answer' => $answer
		]);
	}
	
	/**
	 * @param $id
	 *
	 * @return \yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws UserException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		if ($model->votes) {
			throw new UserException('Нельзя удалить опрос, т.к. за него уже есть голоса');
		}
		foreach ($model->interviewAnswers as $answer) {
			if ($answer->imgPath) {
				HelpModel::deleteFile($answer->imgPath);
			}
			$answer->delete();
		}
		$model->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * @param $id
	 *
	 * @return string|\yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws UserException
	 */
	public function actionResults($id)
	{
		$model = $this->findModel($id);
		$vote = new Vote();
		$vote->interviewId = $model->id;
		if ($vote->load(\Yii::$app->request->post())) {
			$mutexName = 'addVote-' . $model->id . '-athlete-' . $vote->athleteId;
			if (\Yii::$app->mutex->acquire($mutexName, 10)) {
				if (Vote::findOne(['interviewId' => $model->id, 'athleteId' => $vote->athleteId])) {
					\Yii::$app->mutex->release($mutexName);
					throw new UserException('Голос от этого спортсена уже есть');
				}
				if ($vote->save()) {
					return $this->redirect(['results', 'id' => $model->id]);
				}
			} else {
				\Yii::$app->mutex->release($mutexName);
				throw new UserException('Голос от этого спортсена уже есть');
			}
		}
		
		return $this->render('results', ['model' => $model, 'vote' => $vote]);
	}
	
	/**
	 * @param $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionAnswerDetail($id)
	{
		$answer = InterviewAnswer::findOne($id);
		if (!$answer) {
			throw new NotFoundHttpException();
		}
		return $this->render('answer-detail', ['answer' => $answer]);
	}
}
