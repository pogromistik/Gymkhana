<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use Yii;
use common\models\AssocNews;
use common\models\search\AssocNewsSearch;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for AssocNews model.
 */
class NewsController extends BaseController
{
	public function init()
	{
		parent::init();
		$this->can('projectAdmin');
	}
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}
	
	/**
	 * Lists all AssocNews models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new AssocNewsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['status' => AssocNews::STATUS_ACTIVE]);
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if (\Yii::$app->user->can('projectOrganizer')) {
				$dataProvider->query->andWhere(['or',
					['creatorUserId' => \Yii::$app->user->id],
					['canEditRegionId' => \Yii::$app->user->identity->regionId],
					['canEditRegionId' => null]
				]);
			} elseif (Yii::$app->user->can('projectAdmin')) {
				$dataProvider->query->andWhere(['or',
					['creatorUserId' => \Yii::$app->user->id],
					['canEditRegionId' => \Yii::$app->user->identity->regionId]
				]);
			}
		}
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionModeration()
	{
		$this->can('projectAdmin');
		$searchModel = new AssocNewsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['status' => AssocNews::STATUS_MODERATION]);
		
		return $this->render('moderation', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Creates a new AssocNews model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new AssocNews();
		$model->datePublishHuman = date('d.m.Y', time());
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing AssocNews model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 * @param bool    $success
	 *
	 * @return mixed
	 */
	public function actionUpdate($id, $success = false)
	{
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
	 * Deletes an existing AssocNews model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}
	
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$model->status = AssocNews::STATUS_ACTIVE;
		$model->datePublish = time();
		$model->save();
		
		return $this->redirect(['index']);
	}
	
	public function actionView($id)
	{
		$model = $this->findModel($id);
		
		return $this->render('view', ['model' => $model]);
	}
	
	/**
	 * Finds the AssocNews model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return AssocNews the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 * @throws ForbiddenHttpException
	 */
	protected function findModel($id)
	{
		if (($model = AssocNews::findOne($id)) !== null) {
			if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
				if (\Yii::$app->user->can('projectOrganizer')) {
					if ($model->creatorUserId != \Yii::$app->user->id && $model->canEditRegionId != null
						&& $model->canEditRegionId != \Yii::$app->user->identity->regionId
					) {
						throw new ForbiddenHttpException('Доступ запрещён.');
					}
				} elseif (Yii::$app->user->can('projectAdmin')) {
					if ($model->creatorUserId != \Yii::$app->user->id
						&& $model->canEditRegionId != \Yii::$app->user->identity->regionId
					) {
						throw new ForbiddenHttpException('Доступ запрещён.');
					}
				}
			}
			
			return $model;
		} else {
			throw new NotFoundHttpException('Новость не найдена.');
		}
	}
}
