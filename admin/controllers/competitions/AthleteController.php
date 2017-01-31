<?php

namespace admin\controllers\competitions;

use common\models\City;
use common\models\Motorcycle;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\Athlete;
use common\models\search\AthleteSearch;
use admin\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AthleteController implements the CRUD actions for Athlete model.
 */
class AthleteController extends BaseController
{
	public function actions()
	{
		return [
			'update-motorcycle' => [
				'class'       => EditableAction::className(),
				'modelClass'  => Motorcycle::className(),
				'forceCreate' => false
			]
		];
	}
	
	/**
	 * Lists all Athlete models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->can('competitions');
		
		$searchModel = new AthleteSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single Athlete model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionView($id)
	{
		$this->can('competitions');
		
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	public function actionCreate($errorCity = null, $success = null)
	{
		$this->can('competitions');
		
		$model = new Athlete();
		
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
	
	public function actionUpdate($id, $success = false)
	{
		$this->can('competitions');
		
		$model = $this->findModel($id);
		$motorcycle = new Motorcycle();
		$motorcycle->athleteId = $id;
		if ($motorcycle->load(Yii::$app->request->post()) && $motorcycle->save()) {
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		}
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		}
		
		return $this->render('update', [
			'model'      => $model,
			'success'    => $success,
			'motorcycle' => $motorcycle
		]);
	}
	
	/**
	 * Finds the Athlete model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Athlete the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$this->can('competitions');
		
		if (($model = Athlete::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionAddCity()
	{
		$this->can('competitions');
		
		$cityTitle = \Yii::$app->request->post('city');
		if (!$cityTitle) {
			return $this->redirect('create');
		}
		$cityTitle = trim($cityTitle);
		$city = City::findOne(['upper(title)' => mb_strtoupper($cityTitle)]);
		if ($city) {
			return $this->redirect(['create', 'errorCity' => true]);
		}
		$city = new City();
		$city->title = $cityTitle;
		if (!$city->save()) {
			return var_dump($city->errors);
		}
		
		return $this->redirect(['create', 'success' => true]);
	}
	
	public function actionAddAthlete()
	{
		$this->can('competitions');
		
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$model = new Athlete();
		$model->load(\Yii::$app->request->post());
		$oldAthletes = Athlete::find()->where([
			'or',
			['firstName' => $model->firstName, 'lastName' => $model->lastName],
			['firstName' => $model->lastName, 'lastName' => $model->firstName]
		])->all();
		$result = [
			'success' => false,
			'error'   => false,
			'data'    => []
		];
		if (!$oldAthletes) {
			$model->save();
			$result['success'] = true;
			$result['data'] = $model->id;
			
			return $result;
		} else {
			if (\Yii::$app->request->post('confirm')) {
				$model->save();
				
				$result['success'] = true;
				$result['data'] = $model->id;
				
				return $result;
			}
			
			$result['error'] = true;
			$result['data'] = $this->renderAjax('_oldAthletes', ['athletes' => $oldAthletes]);
			
			return $result;
		}
	}
}
