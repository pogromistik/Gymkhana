<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\User;
use dektrium\rbac\models\Assignment;
use Yii;
use dektrium\user\models\UserSearch;
use yii\web\NotFoundHttpException;

/**
 * AdminController implements the CRUD actions for Error model.
 */
class UsersController extends BaseController
{
	const ROLE_ADMIN = 'projectAdmin';
	const ROLE_REFEREE = 'refereeOfCompetitions';
	
	public static $rolesTitle = [
		self::ROLE_ADMIN   => 'Администратор',
		self::ROLE_REFEREE => 'Судья соревнований'
	];
	
	public function init()
	{
		parent::init();
		$this->can('projectOrganizer');
	}
	
	public function actionIndex()
	{
		$searchModel = Yii::createObject(UserSearch::className());
		$dataProvider = $searchModel->search(Yii::$app->request->get());
		$dataProvider->query->andWhere(['regionId' => \Yii::$app->user->identity->regionId]);
		$dataProvider->query->andWhere(['not', ['id' => \Yii::$app->user->id]]);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider
		]);
	}
	
	public function actionCreate()
	{
		$user = Yii::createObject([
			'class'    => \dektrium\user\models\User::className(),
			'scenario' => 'create',
		]);
		$assignment = Yii::createObject([
			'class'   => Assignment::className(),
			'user_id' => -10,
		]);
		
		if ($user->load(Yii::$app->request->post())) {
			$user->regionId = \Yii::$app->user->identity->regionId;
			if ($user->create()) {
				if ($assignment->load(\Yii::$app->request->post())) {
					$assignment->user_id = $user->id;
					$assignment->updateAssignments();
				}
				return $this->redirect(['index']);
			}
		}
		
		return $this->render('create', [
			'user'       => $user,
			'assignment' => $assignment
		]);
	}
	
	public function actionUpdate($id)
	{
		$user = User::findOne($id);
		if ($user->regionId != \Yii::$app->user->identity->regionId) {
			throw new NotFoundHttpException('Доступ запрещен');
		}
		$user->scenario = 'update';
		$assignment = Yii::createObject([
			'class'   => Assignment::className(),
			'user_id' => $user->id,
		]);
		if ($user->load(Yii::$app->request->post())) {
			$user->save();
			if ($assignment->load(\Yii::$app->request->post()) && $assignment->updateAssignments()) {
			}
			return $this->redirect(['index']);
		}
		
		return $this->render('update', [
			'user'       => $user,
			'assignment' => $assignment
		]);
	}
	
	public function actionChangeStatus($id)
	{
		$user = User::findOne($id);
		if ($user->regionId != \Yii::$app->user->identity->regionId) {
			throw new NotFoundHttpException('Доступ запрещен');
		}
		if ($user->blocked_at) {
			$user->blocked_at = null;
		} else {
			$user->blocked_at = time();
		}
		$user->save();
		
		return $this->redirect('index');
	}
}
