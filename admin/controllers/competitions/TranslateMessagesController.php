<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\TranslateMessage;
use Yii;
use common\models\TranslateMessageSource;
use common\models\search\TranslateMessageSourceSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessagesController implements the CRUD actions for SourceMessage model.
 */
class TranslateMessagesController extends BaseController
{
	/**
	 * Lists all SourceMessage models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->can('developer');
		$searchModel = new TranslateMessageSourceSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionTranslate($letter = null)
	{
		$this->can('translate');
		$searchModel = new TranslateMessageSourceSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['status' => TranslateMessageSource::STATUS_ACTIVE]);
		if ($letter) {
			$dataProvider->query->andWhere(['like', 'upper("message")', $letter.'%', false]);
		}
		
		return $this->render('translate', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'letter'       => $letter
		]);
	}
	
	public function actionAddTranslate()
	{
		$this->can('translate');
		$params = \Yii::$app->request->getBodyParams();
		$id = $params['TranslateMessage']['id'] ? $params['TranslateMessage']['id'] : null;
		if (!$id) {
			return 'Запись не найдена';
		}
		$language = $params['TranslateMessage']['language'] ? $params['TranslateMessage']['language'] : null;
		if (!$id) {
			return 'Укажите язык';
		}
		
		/**
		 * @var TranslateMessage $message
		 */
		$message = TranslateMessage::findOne(['id' => $id, 'language' => $language]);
		if (!$message) {
			$message = new TranslateMessage();
			$message->id = $id;
		}
		if ($message->load(\Yii::$app->request->post())) {
			if ($message->save()) {
				return true;
			} else {
				return var_dump($message->errors);
			}
		}
		
		return false;
	}
	
	/**
	 * Creates a new SourceMessage model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$this->can('developer');
		$model = new TranslateMessageSource();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	public function actionUpdate()
	{
		$this->can('translate');
		$params = \Yii::$app->request->getBodyParams();
		$id = $params['TranslateMessageSource']['id'] ? $params['TranslateMessageSource']['id'] : null;
		if (!$id) {
			return 'Запись не найдена';
		}
		
		/**
		 * @var TranslateMessageSource $message
		 */
		$message = $this->findModel($id);
		if ($message->load(\Yii::$app->request->post())) {
			if ($message->save()) {
				return true;
			} else {
				return var_dump($message->errors);
			}
		}
		
		return false;
	}
	
	public function actionChangeStatus($id)
	{
		$this->can('translate');
		$message = $this->findModel($id);
		if ($message->status == TranslateMessageSource::STATUS_WAIT) {
			$message->status = TranslateMessageSource::STATUS_ACTIVE;
		} else {
			$message->status = TranslateMessageSource::STATUS_WAIT;
		}
		if ($message->save()) {
			return true;
		} else {
			return var_dump($message->errors);
		}
	}
	
	public function actionDelete($id)
	{
		$this->can('developer');
		$message = $this->findModel($id);
		$message->delete();
		return true;
	}
	
	/**
	 * Finds the SourceMessage model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return TranslateMessageSource the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$this->can('translate');
		if (($model = TranslateMessageSource::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
