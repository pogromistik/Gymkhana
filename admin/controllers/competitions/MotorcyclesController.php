<?php

namespace admin\controllers\competitions;

use common\helpers\UserHelper;
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
}
