<?php

namespace console\controllers;

use common\models\Championship;
use common\models\Error;
use common\models\NewsSubscription;
use common\models\SpecialChamp;
use common\models\SpecialStage;
use common\models\Stage;
use common\models\SubscriptionQueue;
use common\models\Year;
use yii\console\Controller;

class CronController extends Controller
{
	public function actionChangeStagesStatus()
	{
		$time = time();
		$timeStart = time() - 3600;
		//открыта регистрация на этап
		Stage::updateAll(['status' => Stage::STATUS_START_REGISTRATION], [
			'and',
			['status' => Stage::STATUS_UPCOMING],
			['not', ['startRegistration' => null]],
			['<=', 'startRegistration', $time]
		]);
		
		//завершена регистрация на этап
		Stage::updateAll(['status' => Stage::STATUS_END_REGISTRATION], [
			'and',
			['status' => Stage::STATUS_START_REGISTRATION],
			['not', ['endRegistration' => null]],
			['<=', 'endRegistration', $time]
		]);
		
		//текущий этап
		Stage::updateAll(['status' => Stage::STATUS_PRESENT], [
			'and',
			['<=', 'dateOfThe', $time],
			['not', ['dateOfThe' => null]],
			['status' => [Stage::STATUS_UPCOMING, Stage::STATUS_END_REGISTRATION, Stage::STATUS_END_REGISTRATION]]
		]);
		
		//прошедший этап
		/** @var Stage[] $stages */
		$stages = Stage::find()->where(['not', ['status' => Stage::STATUS_PAST]])->all();
		foreach ($stages as $stage) {
			if ($stage->dateOfThe && ($stage->dateOfThe + 86400) <= $time) {
				$stage->status = Stage::STATUS_PAST;
				$stage->save();
			}
		}
		
		//Приём результатов
		SpecialStage::updateAll(['status' => SpecialStage::STATUS_START], [
			'and',
			['status' => SpecialStage::STATUS_UPCOMING],
			['not', ['dateStart' => null]],
			['<=', 'dateStart', $time]
		]);
		
		//Приём результатов завершен
		SpecialStage::updateAll(['status' => SpecialStage::STATUS_CALCULATE_RESULTS], [
			'and',
			['status' => SpecialStage::STATUS_START],
			['not', ['dateEnd' => null]],
			['<=', 'dateEnd', $time]
		]);
		
		//прошедший этап
		/** @var SpecialStage[] $stages */
		$stages = SpecialStage::find()->where(['not', ['status' => SpecialStage::STATUS_PAST]])->all();
		foreach ($stages as $stage) {
			if ($stage->dateEnd && ($stage->dateEnd + 86400) <= $time) {
				$stage->status = Stage::STATUS_PAST;
				$stage->save();
			}
		}
		
		//Добавить рассылку в очередь
		//открыта регистрация на обычные этапы
		$stages = Stage::find()->where(['status' => Stage::STATUS_START_REGISTRATION])
			->andWhere(['>=', 'startRegistration', $timeStart])->all();
		foreach ($stages as $stage) {
			SubscriptionQueue::addToQueue(NewsSubscription::TYPE_REGISTRATIONS,
				NewsSubscription::MSG_FOR_REGISTRATIONS, $stage->id);
		}
		//начался приём заявок на специальные этапы
		$stages = SpecialStage::find()->where(['status' => SpecialStage::STATUS_START])
			->andWhere(['>=', 'dateStart', $timeStart])->all();
		foreach ($stages as $stage) {
			SubscriptionQueue::addToQueue(NewsSubscription::TYPE_REGISTRATIONS,
				NewsSubscription::MSG_FOR_SPECIAL_REGISTRATIONS, $stage->id);
		}
		
		return true;
	}
	
	public function actionChangeChampionshipsStatus()
	{
		$time = time();
		$count = 0;
		
		//чемпионат прошел
		$championships = Championship::findAll(['status' => Championship::STATUS_PRESENT]);
		foreach ($championships as $championship) {
			$stages = $championship->stages;
			/** @var Stage $stage */
			foreach ($stages as $stage) {
				if (!$stage->dateOfThe || $stage->dateOfThe > $time) {
					continue 2;
				}
			}
			$championship->status = Championship::STATUS_PAST;
			$championship->save();
			$count++;
		}
		
		$championships = SpecialChamp::findAll(['status' => SpecialChamp::STATUS_PRESENT]);
		foreach ($championships as $championship) {
			$stages = $championship->stages;
			/** @var SpecialStage $stage */
			foreach ($stages as $stage) {
				if (!$stage->dateEnd || $stage->dateEnd > $time) {
					continue 2;
				}
			}
			$championship->status = SpecialChamp::STATUS_PAST;
			$championship->save();
			$count++;
		}
		
		//чемпионат начался
		$championships = Championship::findAll(['status' => Championship::STATUS_UPCOMING]);
		foreach ($championships as $championship) {
			if (Stage::find()->where(['championshipId' => $championship->id])
				->andWhere(['<=', 'dateOfThe', $time])->one()
			) {
				$championship->status = Championship::STATUS_PRESENT;
				$championship->save();
				$count++;
			}
		}
		
		$championships = SpecialChamp::findAll(['status' => SpecialChamp::STATUS_UPCOMING]);
		foreach ($championships as $championship) {
			if (SpecialStage::find()->where(['championshipId' => $championship->id])
				->andWhere(['<=', 'dateEnd', $time])->one()
			) {
				$championship->status = SpecialChamp::STATUS_PRESENT;
				$championship->save();
				$count++;
			}
		}
		
		//чемпионат завершился и снова начался
		$year = Year::findOne(['year' => date('Y')]);
		if ($year) {
			$championships = Championship::findAll(['status' => Championship::STATUS_PAST, 'yearId' => $year->id]);
			foreach ($championships as $championship) {
				if (Stage::find()->where(['championshipId' => $championship->id])
					->andWhere(['>=', 'dateOfThe', $time])->one()
				) {
					$championship->status = Championship::STATUS_PRESENT;
					$championship->save();
					$count++;
				}
			}
			
			$championships = SpecialChamp::findAll(['status' => SpecialChamp::STATUS_PAST, 'yearId' => $year->id]);
			foreach ($championships as $championship) {
				if (SpecialStage::find()->where(['championshipId' => $championship->id])
					->andWhere(['>=', 'dateEnd', $time])->one()
				) {
					$championship->status = Championship::STATUS_PRESENT;
					$championship->save();
					$count++;
				}
			}
		}
		
		echo 'Change ' . $count . ' items';
		
		return true;
	}
	
	public function actionChangePhotoStatus()
	{
		Stage::updateAll(['trackPhotoStatus' => Stage::PHOTO_PUBLISH], [
			'and',
			['not', ['trackPhoto' => null]],
			['trackPhotoStatus' => Stage::PHOTO_NOT_PUBLISH],
			['status' => Stage::STATUS_PAST]
		]);
		
		return true;
	}
	
	public function actionCheckSize()
	{
		exec('df -h', $output, $return_var);
		if ($output) {
			if (!isset($output[1])) {
				$errors = new Error();
				$errors->text = 'Невозможно проверить остаток дискового пространства на хостинге';
				$errors->save();
				
				if (YII_ENV == 'prod') {
					$text = 'Невозможно проверить остаток дискового пространства на хостинге';
					\Yii::$app->mailer->compose('text', ['text' => $text])
						->setTo('nadia__@bk.ru')
						->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
						->setSubject('gymkhana-cup.ru: ошибка на сайте')
						->send();
				}
				
				return false;
			}
			$string = $output[1];
			$array = explode('G', $string);
			if (!isset($array[2])) {
				$errors = new Error();
				$errors->text = 'Невозможно проверить остаток дискового пространства на хостинге';
				$errors->save();
				
				if (YII_ENV == 'prod') {
					$text = 'Невозможно проверить остаток дискового пространства на хостинге';
					\Yii::$app->mailer->compose('text', ['text' => $text])
						->setTo('nadia__@bk.ru')
						->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
						->setSubject('gymkhana-cup.ru: ошибка на сайте')
						->send();
				}
				
				return false;
			}
			$size = trim($array[2]);
			echo $size . PHP_EOL;
			if ($size < 1) {
				$errors = new Error();
				$errors->type = Error::TYPE_CRITICAL_ERROR;
				$errors->text = 'На хостинге осталось менее 1GB свободного места';
				$errors->save();
			} elseif ($size <= 2) {
				$errors = new Error();
				$errors->text = 'На хостинге осталось ' . $size . 'GB свободного места';
				$errors->type = Error::TYPE_SIZE;
				$errors->save();
				
				if (YII_ENV == 'prod') {
					\Yii::$app->mailer->compose('text', ['text' => $errors->text])
						->setTo('nadia__@bk.ru')
						->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
						->setSubject('gymkhana-cup.ru: ошибка на сайте')
						->send();
				}
			}
		}
		
		return true;
	}
	
	public function actionSendSubscriptions()
	{
		$items = SubscriptionQueue::findAll(['isActual' => 1]);
		$countLetters = 0;
		foreach ($items as $item) {
			$count = NewsSubscription::sendMsg($item->messageType, $item->modelId);
			$item->countEmails = $count;
			$item->dateSend = time();
			$item->isActual = 0;
			$item->save();
			$countLetters += $count;
		}
		
		echo 'send ' . $countLetters . ' letters' . PHP_EOL;
		
		return true;
	}
}