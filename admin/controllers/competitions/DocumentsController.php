<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\DocumentSection;
use common\models\OverallFile;
use common\models\search\DocumentSectionSearch;
use common\models\search\OverallFileSearch;
use Yii;
use common\models\AssocNews;
use common\models\search\AssocNewsSearch;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for AssocNews model.
 */
class DocumentsController extends BaseController
{
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
		$this->can('competitions');
		
		$searchModel = new DocumentSectionSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionCreate()
	{
		$this->can('competitions');
		
		$model = new DocumentSection();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$file = new OverallFile();
			$documentId = null;
			if ($file->load(Yii::$app->request->post())) {
				$documentId = $file->saveFile($model->id, DocumentSection::className());
				if ($documentId === 'error saveAs') {
					throw new Exception('Возникла ошибка при сохранении файла. Проверьте директиву upload_max_filesize');
				}
			}
			
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	public function actionUpdate($id, $success = false)
	{
		$this->can('competitions');
		
		$model = $this->findModel($id);
		$searchModel = new OverallFileSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['modelClass' => DocumentSection::className(), 'modelId' => $model->id]);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$file = new OverallFile();
			$documentId = null;
			if ($file->load(Yii::$app->request->post())) {
				$file->saveFile($model->id, OverallFile::className());
				if ($documentId == 'error saveAs') {
					throw new Exception('Возникла ошибка при сохранении файла. Проверьте директиву upload_max_filesize');
				}
			}
			
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		}
		
		return $this->render('update', [
			'model'        => $model,
			'success'      => $success,
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	protected function findModel($id)
	{
		if (($model = DocumentSection::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
