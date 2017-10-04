<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Athlete;
use common\models\AthletesClass;
use common\models\City;
use common\models\Motorcycle;
use common\models\TmpParticipant;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\TmpAthlete;
use common\models\search\TmpAthletesSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TmpAthletesController implements the CRUD actions for TmpAthlete model.
 */
class TmpAthletesController extends BaseController
{
	public function actions()
	{
		return [
			'update' => [
				'class'       => EditableAction::className(),
				'modelClass'  => TmpAthlete::className(),
				'forceCreate' => false
			]
		];
	}
	
	public function init()
	{
		parent::init();
		$this->can('refereeOfCompetitions');
	}
	
	/**
	 * Lists all TmpAthlete models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new TmpAthletesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['status' => TmpAthlete::STATUS_NEW]);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Finds the TmpAthlete model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return TmpAthlete the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TmpAthlete::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionRegistrationOldAthlete($tmpId, $athleteId)
	{
		$tmpAthlete = TmpAthlete::findOne($tmpId);
		if (!$tmpAthlete) {
			return 'Заявка не найдена';
		}
		if ($tmpAthlete->athleteId) {
			if ($tmpAthlete->status == TmpAthlete::STATUS_NEW) {
				$tmpAthlete->status = TmpAthlete::STATUS_ACCEPT;
				$tmpAthlete->save();
			}
			
			return 'Заявка уже была обработана, личный кабинет создан';
		}
		$oldAthlete = Athlete::findOne($athleteId);
		if (!$athleteId) {
			return 'Спортсмен не найден';
		}
		
		$busy = Athlete::find()->where(['email' => $tmpAthlete->email])->andWhere(['not', ['id' => $oldAthlete->id]])->one();
		if ($busy) {
			return 'Указанный email занят';
		}
		
		if (\Yii::$app->mutex->acquire('TmpAthletes-' . $tmpAthlete->id, 10)) {
			$oldAthlete->email = $tmpAthlete->email;
			if ($tmpAthlete->phone) {
				$oldAthlete->phone = $tmpAthlete->phone;
			}
			if ($tmpAthlete->cityId && $tmpAthlete->cityId != $oldAthlete->cityId) {
				$oldAthlete->cityId = $tmpAthlete->cityId;
			}
			if (!$oldAthlete->save()) {
				\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
				
				if (\Yii::$app->user->can('developer')) {
					return var_dump($oldAthlete->save());
				} else {
					return 'При создании личного кабинета возникла ошибка. Обратитесь к разработчику.';
				}
			}
			
			if (!$oldAthlete->createCabinet()) {
				\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
				
				return 'При создании личного кабинета возникла ошибка';
			}
			
			$tmpAthlete->status = TmpAthlete::STATUS_ACCEPT;
			$tmpAthlete->athleteId = $oldAthlete->id;
			$tmpAthlete->save();
			\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
		} else {
			\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
			
			return 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
		
		return true;
	}
	
	public function actionChangeMotorcycles($tmpId, $athleteId)
	{
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$result = [
			'error' => false,
			'page'  => null
		];
		$tmpAthlete = TmpAthlete::findOne($tmpId);
		if (!$tmpAthlete) {
			$result['error'] = 'Заявка не найдена';
			
			return $result;
		}
		if ($tmpAthlete->athleteId) {
			if ($tmpAthlete->status == TmpAthlete::STATUS_NEW) {
				$tmpAthlete->status = TmpAthlete::STATUS_ACCEPT;
				$tmpAthlete->save();
			}
			
			$result['error'] = 'Заявка уже была обработана, личный кабинет создан';
			
			return $result;
		}
		$oldAthlete = Athlete::findOne($athleteId);
		if (!$athleteId) {
			$result['error'] = 'Спортсмен не найден';
			
			return $result;
		}
		
		$busy = Athlete::find()->where(['email' => $tmpAthlete->email])->andWhere(['not', ['id' => $oldAthlete->id]])->one();
		if ($busy) {
			$result['error'] = 'Указанный email занят';
			
			return $result;
		}
		/*if ($oldAthlete->hasAccount) {
			$result['error'] = 'У спортсмена уже есть личный кабинет';
			
			return $result;
		}*/
		
		if (\Yii::$app->mutex->acquire('TmpAthletes-' . $tmpAthlete->id, 10)) {
			$oldAthlete->email = $tmpAthlete->email;
			if ($tmpAthlete->phone) {
				$oldAthlete->phone = $tmpAthlete->phone;
			}
			if ($tmpAthlete->cityId && $tmpAthlete->cityId != $oldAthlete->cityId) {
				$oldAthlete->cityId = $tmpAthlete->cityId;
			}
			if (!$oldAthlete->save()) {
				\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
				$result['error'] = 'При создании личного кабинета возникла ошибка';
				
				return $result;
			}
			
			$notFoundMotorcycles = [];
			$i = 0;
			foreach ($tmpAthlete->getMotorcycles() as $motorcycle) {
				$has = $oldAthlete->getMotorcycles()
					->andWhere(['or',
						['and', ['upper("mark")' => mb_strtoupper($motorcycle['mark'], 'UTF-8')], ['upper("model")' => mb_strtoupper($motorcycle['model'], 'UTF-8')]],
						['and', ['upper("model")' => mb_strtoupper($motorcycle['mark'], 'UTF-8')], ['upper("mark")' => mb_strtoupper($motorcycle['model'], 'UTF-8')]],
					])->one();
				if (!$has) {
					$notFoundMotorcycles[$i] = $motorcycle['mark'] . ' ' . $motorcycle['model'];
				}
				$i++;
			}
			
			if (!$notFoundMotorcycles) {
				\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
				$result['error'] = 'В информации о спортсмене произошли изменения. Пожалуйста, перезагрузите страницу и, если 
			кабинет спортсмену ещё не создан - попробуйте снова';
				
				return $result;
			}
			
			$result['page'] = $this->renderAjax('_change', [
				'tmpAthlete'          => $tmpAthlete,
				'oldAthlete'          => $oldAthlete,
				'notFoundMotorcycles' => $notFoundMotorcycles
			]);
			\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
		} else {
			\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
			
			$result['error'] = 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
		
		
		return $result;
	}
	
	public function actionAddMotorcyclesAndRegistration()
	{
		$tmpId = \Yii::$app->request->post('tmpId');
		$athleteId = \Yii::$app->request->post('athleteId');
		$tmpAthlete = TmpAthlete::findOne($tmpId);
		if (!$tmpAthlete) {
			return 'Заявка не найдена';
		}
		if ($tmpAthlete->athleteId) {
			if ($tmpAthlete->status == TmpAthlete::STATUS_NEW) {
				$tmpAthlete->status = TmpAthlete::STATUS_ACCEPT;
				$tmpAthlete->save();
			}
			
			return 'Заявка уже была обработана, личный кабинет создан';
		}
		$oldAthlete = Athlete::findOne($athleteId);
		if (!$athleteId) {
			return 'Спортсмен не найден';
		}
		if ($oldAthlete->hasAccount) {
			return 'У спортсмена уже есть личный кабинет';
		}
		
		if (\Yii::$app->mutex->acquire('TmpAthletes-' . $tmpAthlete->id, 10)) {
			$motorcycles = \Yii::$app->request->post('motorcycles');
			if (!$motorcycles) {
				$oldAthlete->email = $tmpAthlete->email;
				if ($tmpAthlete->phone) {
					$oldAthlete->phone = $tmpAthlete->phone;
				}
				if (!$oldAthlete->save()) {
					\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
					
					return 'При создании личного кабинета возникла ошибка';
				}
				
				if (!$oldAthlete->createCabinet()) {
					\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
					
					return 'При создании личного кабинета возникла ошибка';
				}
				
				$tmpAthlete->status = TmpAthlete::STATUS_ACCEPT;
				$tmpAthlete->athleteId = $oldAthlete->id;
				$tmpAthlete->save();
				\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
				
				return true;
			}
			
			$allMotorcycles = $tmpAthlete->getMotorcycles();
			$transaction = \Yii::$app->db->beginTransaction();
			foreach ($motorcycles as $id) {
				if (!isset($allMotorcycles[$id])) {
					$transaction->rollBack();
					\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
					
					return 'Выбранный мотоцикл не найден';
				}
				$data = $allMotorcycles[$id];
				/** @var Motorcycle $old */
				$old = $oldAthlete->getMotorcycles()
					->andWhere(['or',
						['and', ['upper("mark")' => mb_strtoupper($data['mark'], 'UTF-8')], ['upper("model")' => mb_strtoupper($data['model'], 'UTF-8')]],
						['and', ['upper("model")' => mb_strtoupper($data['mark'], 'UTF-8')], ['upper("mark")' => mb_strtoupper($data['model'], 'UTF-8')]],
					])->one();
				if ($old) {
					$transaction->rollBack();
					\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
					
					return 'Мотоцикл ' . $old->getFullTitle() . ' уже есть в личном кабинете спортсмена';
				}
				
				$new = new Motorcycle();
				$new->athleteId = $oldAthlete->id;
				$new->mark = $data['mark'];
				$new->model = $data['model'];
				if (!$new->save()) {
					$transaction->rollBack();
					\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
					
					return 'Возникла ошибка при сохранении мотоцикла';
				}
			}
			
			$oldAthlete->email = $tmpAthlete->email;
			if ($tmpAthlete->phone) {
				$oldAthlete->phone = $tmpAthlete->phone;
			}
			if ($tmpAthlete->cityId && $tmpAthlete->cityId != $oldAthlete->cityId) {
				$oldAthlete->cityId = $tmpAthlete->cityId;
			}
			if (!$oldAthlete->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
				
				return 'При создании личного кабинета возникла ошибка';
			}
			
			if (!$oldAthlete->createCabinet()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
				
				return 'При создании личного кабинета возникла ошибка';
			}
			
			$tmpAthlete->status = TmpAthlete::STATUS_ACCEPT;
			$tmpAthlete->athleteId = $oldAthlete->id;
			$tmpAthlete->save();
			
			$transaction->commit();
			\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
		} else {
			\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
			
			return 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
		
		return true;
	}
	
	public function actionRegistrationNewAthlete($id)
	{
		$tmpAthlete = TmpAthlete::findOne($id);
		if (!$tmpAthlete) {
			return 'Заявка не найдена';
		}
		if ($tmpAthlete->athleteId) {
			if ($tmpAthlete->status == TmpAthlete::STATUS_NEW) {
				$tmpAthlete->status = TmpAthlete::STATUS_ACCEPT;
				$tmpAthlete->save();
			}
			
			return 'Заявка уже была обработана, личный кабинет создан';
		}
		if (!$tmpAthlete->cityId) {
			return 'Необходимо выбрать город из списка';
		}
		if ($tmpAthlete->email && $tmpAthlete->email != '') {
			if (Athlete::findOne(['email' => $tmpAthlete->email])) {
				return 'Указанный email занят';
			}
		}
		
		if (\Yii::$app->mutex->acquire('TmpAthletes-' . $tmpAthlete->id, 10)) {
			$athlete = new Athlete();
			$athlete->firstName = $tmpAthlete->firstName;
			$athlete->lastName = $tmpAthlete->lastName;
			$athlete->cityId = $tmpAthlete->cityId;
			if ($tmpAthlete->phone) {
				$athlete->phone = $tmpAthlete->phone;
			}
			$athlete->email = $tmpAthlete->email;
			$athlete->countryId = $tmpAthlete->countryId;
			$transaction = \Yii::$app->db->beginTransaction();
			if (!$athlete->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
				
				return 'Возникла ошибка при создании спортсмена';
			}
			
			$motorcycles = $tmpAthlete->getMotorcycles();
			foreach ($motorcycles as $motorcycle) {
				$new = new Motorcycle();
				$new->mark = $motorcycle['mark'];
				$new->model = $motorcycle['model'];
				$new->athleteId = $athlete->id;
				if (!$new->save()) {
					$transaction->rollBack();
					\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
					
					return 'Возникла ошибка при добавлении мотоцикла';
				}
			}
			
			$result = $athlete->createCabinet();
			if (!$result) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
				
				return 'Возникла ошибка при создании кабинета';
			}
			
			$tmpAthlete->status = TmpAthlete::STATUS_ACCEPT;
			$tmpAthlete->athleteId = $athlete->id;
			if (!$tmpAthlete->save()) {
				$transaction->rollBack();
			}
			
			$transaction->commit();
			\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
		} else {
			\Yii::$app->mutex->release('TmpAthletes-' . $tmpAthlete->id);
			
			return 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
		
		return $result;
	}
	
	public function actionSaveNewCity()
	{
		$id = \Yii::$app->request->post('id');
		$city = \Yii::$app->request->post('city');
		if (!$id || !$city) {
			return 'Неверные данные';
		}
		
		$tmp = TmpAthlete::findOne($id);
		if (!$tmp) {
			return 'Спортсмен не найден';
		}
		if ($tmp->cityId) {
			return 'Спортсмену уже установлен город';
		}
		
		$city = City::findOne(['countryId' => $tmp->countryId, 'id' => $city]);
		if (!$city) {
			return 'Город не найден';
		}
		
		$tmp->cityId = $city->id;
		$tmp->city = $city->title;
		if (!$tmp->save()) {
			return var_dump($tmp);
		}
		
		return true;
	}
	
	public function actionCancel($id)
	{
		$tmp = TmpAthlete::findOne($id);
		if (!$tmp) {
			return 'Заявка не найдена';
		}
		if ($tmp->status != TmpAthlete::STATUS_NEW) {
			return 'Заявка была обработана ранее';
		}
		
		if (\Yii::$app->mutex->acquire('TmpAthletes-' . $tmp->id, 10)) {
			$tmp->status = TmpAthlete::STATUS_CANCEL;
			$tmp->save();
			\Yii::$app->mutex->release('TmpAthletes-' . $tmp->id);
			
			return true;
		} else {
			\Yii::$app->mutex->release('TmpAthletes-' . $tmp->id);
			
			return 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
	}
}
