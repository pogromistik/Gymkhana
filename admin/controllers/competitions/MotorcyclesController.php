<?php

namespace admin\controllers\competitions;

use common\helpers\UserHelper;
use common\models\FigureTime;
use common\models\Motorcycle;
use common\models\Participant;
use common\models\RequestForSpecialStage;
use common\models\search\MotorcyclesSearch;
use Yii;
use admin\controllers\BaseController;
use yii\web\NotFoundHttpException;

/**
 * AthleteController implements the CRUD actions for Athlete model.
 */
class MotorcyclesController extends BaseController
{
	public function actionChangeStatus($id)
	{
		$this->can('competitions');
		
		$motorcycle = $this->findModel($id);
		if ($motorcycle->status) {
			if (!UserHelper::accessAverage($motorcycle->athlete->regionId, $motorcycle->creatorUserId)) {
				return 'У вас недостаточно прав для совершения данного действия';
			}
			if (!FigureTime::findOne(['motorcycleId' => $motorcycle->id]) && !Participant::findOne(['motorcycleId' => $motorcycle->id])
				&& !RequestForSpecialStage::findOne(['motorcycleId' => $motorcycle->id])) {
				$motorcycle->delete();
				return true;
			}
			$motorcycle->status = Motorcycle::STATUS_INACTIVE;
		} else {
			$motorcycle->status = Motorcycle::STATUS_ACTIVE;
		}
		
		if ($motorcycle->save()) {
			return true;
		}
		
		return 'Возникла ошибка при изменении данных';
	}
	
	public function findModel($id)
	{
		$this->can('competitions');
		
		if (($model = Motorcycle::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionIndex()
	{
		$this->can('developer');
		$searchModel = new MotorcyclesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionUpdate($id)
	{
		$this->can('developer');
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
}
