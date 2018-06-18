<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Athlete;
use common\models\AthletesClass;
use common\models\Championship;
use common\models\CheScheme;
use common\models\ClassesRequest;
use common\models\Message;
use common\models\Participant;
use common\models\Region;
use common\models\RequestForSpecialStage;
use common\models\search\CheSchemeSearch;
use common\models\search\ClassesRequestSearch;
use common\models\search\MessagesSearch;
use common\models\search\TmpAthletesSearch;
use common\models\search\TmpFigureResultSearch;
use common\models\search\TmpParticipantSearch;
use common\models\SpecialStage;
use common\models\Stage;
use common\models\TmpAthlete;
use common\models\User;
use Yii;
use common\models\Point;
use common\models\search\PointSearch;
use yii\db\Expression;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AdditionalController implements the CRUD actions for Point model.
 */
class AdditionalController extends BaseController
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
	 * Lists all Point models.
	 *
	 * @return mixed
	 */
	public function actionPoints()
	{
		$this->can('globalWorkWithCompetitions');
		
		$searchModel = new PointSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('points', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Creates a new Point model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreatePoints()
	{
		$this->can('globalWorkWithCompetitions');
		
		$model = new Point();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['points', 'id' => $model->id]);
		} else {
			return $this->render('create-points', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing Point model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdatePoints($id)
	{
		$this->can('globalWorkWithCompetitions');
		
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['points']);
		} else {
			return $this->render('update-points', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Deletes an existing Point model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDeletePoints($id)
	{
		$this->can('globalWorkWithCompetitions');
		
		$this->findModel($id)->delete();
		
		return $this->redirect(['points']);
	}
	
	/**
	 * Finds the Point model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Point the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$this->can('globalWorkWithCompetitions');
		
		if (($model = Point::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionCheScheme()
	{
		$this->can('developer');
		$searchModel = new CheSchemeSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('che-scheme', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionCreateClass()
	{
		$this->can('developer');
		
		$model = new CheScheme();
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['che-scheme']);
		} else {
			return $this->render('create-class', [
				'model' => $model,
			]);
		}
	}
	
	public function actionUpdateClass($id)
	{
		$this->can('developer');
		
		$model = CheScheme::findOne($id);
		if (!$model) {
			throw new NotFoundHttpException('Класс не найден');
		}
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['che-scheme']);
		} else {
			return $this->render('update-class', [
				'model' => $model,
			]);
		}
	}
	
	public function actionLKRequests()
	{
		$this->can('globalWorkWithCompetitions');
		$searchModel = new TmpAthletesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['not', ['status' => TmpAthlete::STATUS_NEW]]);
		$dataProvider->query->orderBy(['dateUpdated' => SORT_DESC]);
		
		return $this->render('l-k-requests', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionFiguresRequests()
	{
		$this->can('globalWorkWithCompetitions');
		$searchModel = new TmpFigureResultSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['isNew' => 0]);
		$dataProvider->query->orderBy(['dateUpdated' => SORT_DESC]);
		
		return $this->render('figures-requests', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionStagesRequests()
	{
		$this->can('globalWorkWithCompetitions');
		$searchModel = new TmpParticipantSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['not', ['status' => TmpAthlete::STATUS_NEW]]);
		$dataProvider->query->orderBy(['dateUpdated' => SORT_DESC]);
		
		return $this->render('stages-requests', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionClassesRequest()
	{
		$this->can('globalWorkWithCompetitions');
		$searchModel = new ClassesRequestSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['not', ['status' => ClassesRequest::STATUS_NEW]]);
		$dataProvider->query->orderBy(['dateAdded' => SORT_DESC]);
		
		return $this->render('classes-request', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionMessages()
	{
		$this->can('globalWorkWithCompetitions');
		$searchModel = new MessagesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->orderBy(['dateAdded' => SORT_DESC]);
		
		return $this->render('messages', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionStats()
	{
		$this->can('competitions');
		$query = new Query();
		$query->from(['a' => Athlete::tableName(), 'b' => Region::tableName(), 'c' => AthletesClass::tableName()]);
		$query->select(['count("a"."id")', '"b"."title" as "region"', '"c"."title" as "class"']);
		$query->where(new Expression('"a"."athleteClassId" = "c"."id"'))
			->andWhere(new Expression('"a"."regionId" = "b"."id"'));
		$query->orderBy(['"b"."title"' => SORT_ASC, '"c"."title"' => SORT_ASC]);
		$query->groupBy(['"b"."title"', '"c"."title"']);
		$items = $query->all();
		
		$stats = [];
		$totalClasses = [];
		$totalClasses['total'] = 0;
		$classes = AthletesClass::find()->select('title')->orderBy(['percent' => SORT_ASC, 'title' => SORT_ASC])
			->asArray()->column();
		foreach ($items as $item) {
			if (!isset($stats[$item['region']])) {
				$stats[$item['region']] = [
					'total'  => 0,
					'groups' => []
				];
				foreach ($classes as $class) {
					$stats[$item['region']]['groups'][$class] = 0;
				}
			}
			if (!isset($totalClasses[$item['class']])) {
				$totalClasses[$item['class']] = 0;
			}
			$totalClasses[$item['class']] += $item['count'];
			$totalClasses['total'] += $item['count'];
			$stats[$item['region']]['groups'][$item['class']] = $item['count'];
			$stats[$item['region']]['total'] += $item['count'];
		}
		
		
		return $this->render('stats', ['stats' => $stats, 'classes' => $classes, 'totalClasses' => $totalClasses]);
	}
	
	public function actionMessage($type)
	{
		$this->can('canSendMessages');
		$message = new Message();
		
		$stages = null;
		$athletes = null;
		if ($type == Message::TYPE_TO_PARTICIPANTS) {
			if (\Yii::$app->user->can('globalWorkWithCompetitions')) {
				$stages = Stage::find()->orderBy(['dateOfThe' => SORT_DESC])->all();
			} else {
				$stages = Stage::find()->where(['regionId' => \Yii::$app->user->identity->regionId])
					->orderBy(['dateOfThe' => SORT_DESC])->all();
			}
		} else {
			$athletes = Athlete::find()->where(['not', ['email' => null]])->orderBy(['lastName' => SORT_ASC])->all();
		}
		
		return $this->render('send-message', [
			'message'  => $message,
			'type'     => $type,
			'stages'   => $stages,
			'athletes' => $athletes
		]);
	}
	
	public function actionSendMessage()
	{
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$this->can('canSendMessages');
		$result = [
			'success' => false,
			'error'   => false,
			'text'    => ''
		];
		$message = new Message();
		$message->load(\Yii::$app->request->post());
		if (!$message->athleteIds && !$message->stageId) {
			$result['error'] = true;
			$result['text'] = 'Не найдено спортсменов, которым нужно отправить сообщение';
			
			return $result;
		}
		if (!$message->text) {
			$result['error'] = true;
			$result['text'] = 'Введите текст';
			
			return $result;
		}
		if (!$message->title) {
			$result['error'] = true;
			$result['text'] = 'Укажите заголовок';
			
			return $result;
		}
		$emails = [];
		if ($message->stageId) {
			$stage = Stage::findOne(['id' => $message->stageId]);
			if (!$stage) {
				$result['error'] = true;
				$result['text'] = 'Этап не найден';
				
				return $result;
			}
			if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
				if ($stage->regionId != \Yii::$app->user->identity->regionId) {
					$result['error'] = true;
					$result['text'] = 'Access denied';
					
					return $result;
				}
			}
			$emails = (new Query())->select('a.email')
				->from(['a' => Athlete::tableName(), 'b' => Participant::tableName(), 'c' => Stage::tableName()])
				->where(new Expression('"a"."id"="b"."athleteId"'))
				->andWhere(new Expression('"b"."stageId"="c"."id"'))
				->andWhere(['c.id' => $stage->id])
				->distinct()
				->column();
		} else {
			$emails = Athlete::find()->select('email')->where(['id' => $message->athleteIds])
				->andWhere(['not', ['email' => null]])->asArray()->column();
		}
		if (!$emails) {
			$result['error'] = true;
			$result['text'] = 'Не найдено email для отправки';
			
			return $result;
		}
		$message->save();
		$count = count($emails);
		if (YII_ENV == 'prod') {
			$count = 0;
			foreach ($emails as $email) {
				if (mb_stripos($email, '@', null, 'UTF-8')) {
					\Yii::$app->mailer->compose('@common/mail/text', ['text' => $message->text])
						->setTo($email)
						->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
						->setSubject($message->title)
						->send();
					$count++;
				}
			}
		}
		
		$result['success'] = true;
		$result['text'] = 'Сообщение успешно отправлено. Количество человек, которые его получат: ' . $count;
		
		return $result;
	}
	
	public function actionCloseHint()
	{
		$this->can('competitions');
		$user = User::findOne(\Yii::$app->user->identity->id);
		if ($user) {
			$user->showHint = 0;
			$user->save();
		}
		
		return true;
	}
	
	public function actionMails()
	{
		$this->can('competitions');
		
		return $this->render('mails');
	}
	
	/**
	 * @param null $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 * @throws \yii\web\ForbiddenHttpException
	 */
	public function actionSpecialStageStats($id = null)
	{
		$this->can('globalWorkWithCompetitions');
		$stats = RequestForSpecialStage::find()->select(["COUNT(id)", "status"])->groupBy('status');
		$cancelRequests = RequestForSpecialStage::find()->where(['status' => RequestForSpecialStage::STATUS_CANCEL]);
		$stage = null;
		if ($id) {
			$stage = SpecialStage::findOne($id);
			if (!$stage) {
				throw new NotFoundHttpException("Stage not found");
			}
			$stats->andWhere(['stageId' => $id]);
			$cancelRequests->andWhere(['stageId' => $id]);
		}
		$stats = $stats->orderBy('status')->indexBy('status')->asArray()->all();
		$cancelRequests = $cancelRequests->orderBy('cityId')->all();
		
		
		return $this->render('special-stage-stats', ['stats' => $stats, 'cancelRequests' => $cancelRequests, 'stage' => $stage]);
	}
}
