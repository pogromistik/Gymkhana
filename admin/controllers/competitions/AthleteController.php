<?php

namespace admin\controllers\competitions;

use admin\models\PasswordForm;
use common\helpers\UserHelper;
use common\models\AthletesClass;
use common\models\City;
use common\models\ClassHistory;
use common\models\Country;
use common\models\Motorcycle;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\Athlete;
use common\models\search\AthleteSearch;
use admin\controllers\BaseController;
use yii\base\UserException;
use yii\bootstrap\ActiveForm;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
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
		
		$password = new PasswordForm();
		if (\Yii::$app->user->can('developer')) {
			if ($password->load(Yii::$app->request->post()) && $password->savePassw()) {
				return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
			}
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
			'motorcycle' => $motorcycle,
			'password'   => $password
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
		$this->can('refereeOfCompetitions');
		
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$model = new Athlete();
		$model->load(\Yii::$app->request->post());
		$oldAthletes = Athlete::find()->where([
			'or',
			['upper("firstName")' => mb_strtoupper($model->firstName, 'UTF-8'),
			 'upper("lastName")'  => mb_strtoupper($model->lastName, 'UTF-8')],
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
	
	public function actionGetList($title = null)
	{
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
		$query = new Query();
		$query->select('"Athletes"."id", ("Athletes"."lastName" || \' \' || "Athletes"."firstName"
		|| \' (\' || "Cities"."title" || \')\') AS text')
			->from([City::tableName(), Athlete::tableName()])
			->where(new Expression('"Athletes"."cityId" = "Cities"."id"'));
		if (!is_null($title)) {
			$query->andWhere(['or',
				['like', 'upper("Athletes"."lastName")', mb_strtoupper($title, 'UTF-8')],
				['like', 'upper("Athletes"."firstName")', mb_strtoupper($title, 'UTF-8')]
			]);
		}
		$query->orderBy(['"Athletes"."lastName"' => SORT_ASC]);
		$command = $query->createCommand();
		$data = $command->queryAll();
		$out['results'] = array_values($data);
		
		return $out;
		
	}
	
	public function actionChangeClass($success = false)
	{
		$this->can('projectOrganizer');
		$athletes = (new Query())->from(['a' => Athlete::tableName(), 'b' => AthletesClass::tableName(), 'c' => City::tableName()])
			->select(['a.id', '("a"."lastName" || \' \' || "a"."firstName" || \',\' || "c"."title" || \' \' || "b"."title") as "title"'])
			->where(new Expression('"a"."cityId"="c"."id"'))
			->andWhere(new Expression('"a"."athleteClassId"="b"."id"'))
			->all();
		if ($athletes) {
			$athletes = ArrayHelper::map($athletes, 'id', 'title');
		}
		$history = new ClassHistory();
		
		return $this->render('change-class', ['athletes' => $athletes, 'history' => $history, 'success' => $success]);
	}
	
	public function actionChangeAthleteClass()
	{
		$this->can('projectOrganizer');
		
		$history = new ClassHistory();
		$history->load(\Yii::$app->request->post());
		if (!$history->athleteId) {
			return 'Необходимо выбрать спортсмена';
		}
		if (!$history->newClassId) {
			return 'Необходимо указать новый класс';
		}
		if (!$history->event) {
			return 'Необходимо указать событие';
		}
		$athlete = Athlete::findOne($history->athleteId);
		if (!$athlete) {
			return 'Спортсмен не найден';
		}
		$newClass = AthletesClass::findOne($history->newClassId);
		if (!$newClass) {
			return 'Класс не найден';
		}
		if ($athlete->athleteClass->percent < $newClass->percent || $athlete->athleteClassId == $newClass->id) {
			return 'Данный функционал позволяет только повышать класс спортсмену';
		}
		$history->oldClassId = $athlete->athleteClassId;
		$transaction = \Yii::$app->db->beginTransaction();
		if (!$history->save()) {
			$transaction->rollBack();
			
			return var_dump($history->errors);
		}
		$athlete->athleteClassId = $history->newClassId;
		if (!$athlete->save(false)) {
			$transaction->rollBack();
			
			return var_dump($athlete->errors);
		}
		$transaction->commit();
		
		return true;
	}
	
	public function actionClassesCategory()
	{
		$this->can('competitions');
		
		if (isset($_POST['depdrop_parents'])) {
			$parent = $_POST['depdrop_parents'];
			if ($parent != null) {
				$athleteId = $parent[0];
				$out = self::getSubCatList($athleteId);
				echo Json::encode(['output' => $out, 'selected' => '']);
				
				return;
			}
		}
		echo Json::encode(['output' => '', 'selected' => '']);
	}
	
	private function getSubCatList($athleteId)
	{
		$this->can('competitions');
		
		$athlete = Athlete::findOne($athleteId);
		$class = $athlete->athleteClass;
		
		return AthletesClass::find()->select(['id', '"title" as "name"'])->where(['<=', 'percent', $class->percent])
			->andWhere(['not', ['id' => $class->id]])->asArray()->all();
	}
}
