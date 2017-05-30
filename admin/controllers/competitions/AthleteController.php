<?php

namespace admin\controllers\competitions;

use common\helpers\UserHelper;
use common\models\City;
use common\models\Country;
use common\models\Motorcycle;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\Athlete;
use common\models\search\AthleteSearch;
use admin\controllers\BaseController;
use yii\base\UserException;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AthleteController implements the CRUD actions for Athlete model.
 */
class AthleteController extends BaseController
{
	public function init()
	{
		parent::init();
		$this->can('refereeOfCompetitions');
	}
	
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
		$searchModel = new AthleteSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionView($id)
	{
		$model = $this->findModel($id);
		
		$motorcycle = new Motorcycle();
		$motorcycle->athleteId = $id;
		if ($motorcycle->load(Yii::$app->request->post()) && $motorcycle->validate()) {
			if (!UserHelper::accessAverage($model->regionId, $motorcycle->creatorUserId)) {
				throw new UserException('Доступ запрещен');
			}
			$motorcycle->save(false);
			
			return $this->redirect(['view', 'id' => $model->id]);
		}
		
		return $this->render('view', [
			'model'      => $model,
			'motorcycle' => $motorcycle
		]);
	}
	
	public function actionCreate($errorCity = null, $success = null)
	{
		$model = new Athlete();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id]);
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
		$model = $this->findModel($id);
		if (!UserHelper::accessAverage($model->regionId, $model->creatorUserId)) {
			throw new UserException('Доступ запрещен');
		}
		
		$motorcycle = new Motorcycle();
		$motorcycle->athleteId = $id;
		if ($motorcycle->load(Yii::$app->request->post()) && $motorcycle->save()) {
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		}
		
		if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
			\Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
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
		$city = City::findOne(['upper(title)' => mb_strtoupper($cityTitle, 'UTF-8')]);
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
			['upper("firstName")' => mb_strtoupper($model->firstName, 'UTF-8'),
			 'upper("lastName")' => mb_strtoupper($model->lastName, 'UTF-8')],
			['upper("firstName")' => mb_strtoupper($model->lastName, 'UTF-8'), 'upper("lastName")' => mb_strtoupper($model->firstName, 'UTF-8')]
		])->all();
		$result = [
			'success' => false,
			'error'   => false,
			'warning' => false,
			'data'    => []
		];
		if (!$model->validate('number')) {
			$result['error'] = '<div class="alert alert-danger">Указанный номер занят. Укажите другой или
 оставьте поле пустым.</div>';
			
			return $result;
		}
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
			
			$result['warning'] = true;
			$result['data'] = $this->renderAjax('_oldAthletes', ['athletes' => $oldAthletes]);
			
			return $result;
		}
	}
	
	public function actionCreateCabinet($athleteId)
	{
		$this->can('competitions');
		
		$athlete = Athlete::findOne($athleteId);
		if (!$athlete) {
			return 'Спортсмен не найден';
		}
		if ($athlete->hasAccount) {
			return 'Кабинет был создан ранее';
		}
		if (!$athlete->email) {
			return 'Необходимо указать почту в профиле спортсмена';
		}
		
		if (!$athlete->createCabinet()) {
			return 'Возникла ошибка при сохранении данных';
		}
		
		return true;
	}
	
	public function actionDeleteCabinet($athleteId)
	{
		$this->can('projectOrganizer');
		
		$athlete = Athlete::findOne($athleteId);
		if (!$athlete) {
			return 'Спортсмен не найден';
		}
		if ($athlete->hasAccount) {
			if (!$athlete->deleteCabinet()) {
				return 'Возникла ошибка при сохранении данных';
			}
		}
		
		return true;
	}
}
