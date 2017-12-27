<?php

namespace champ\controllers;

use champ\models\LoginForm;
use champ\models\PasswordResetRequestForm;
use champ\models\ResetPasswordForm;
use common\models\AssocNews;
use common\models\Athlete;
use common\models\DocumentSection;
use common\models\Feedback;
use common\models\NewsSubscription;
use common\models\Participant;
use common\models\RequestForSpecialStage;
use common\models\SpecialStage;
use common\models\Stage;
use common\models\TmpAthlete;
use Yii;
use yii\base\InvalidParamException;
use yii\base\UserException;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends BaseController
{
	
	
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			]
		];
	}
	
	public function actionIndex()
	{
		$this->layout = 'main-with-img';
		$this->pageTitle = 'Мотоджимхана: события';
		$this->description = 'Сайт, посвященный соревнованиям по мото джимхане в России. Новости мото джимханы.';
		$this->keywords = 'мото джимхана, мотоджимхана, motogymkhana, moto gymkhana, джимхана кап, gymkhana cup, новости мото джимханы, события мото джимханы, новости, события';
		
		$news = AssocNews::find()->where(['<=', 'datePublish', time()]);
		$pagination = new Pagination([
			'defaultPageSize' => 10,
			'totalCount'      => $news->count(),
		]);
		$news = $news->orderBy(['secure' => SORT_DESC, 'datePublish' => SORT_DESC, 'dateAdded' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
		
		return $this->render('index', [
			'news'       => $news,
			'pagination' => $pagination
		]);
	}
	
	public function actionNews($id)
	{
		$news = AssocNews::findOne($id);
		if (!$news || $news->datePublish > time()) {
			throw new NotFoundHttpException('Новость не найдена');
		}
		$this->pageTitle = $news->title ? $news->title : 'Новость от ' . date("d.m.Y", $news->datePublish);
		$this->layout = 'main-with-img';
		
		return $this->render('news', [
			'news' => $news
		]);
	}
	
	public function actionTracks()
	{
		$this->pageTitle = 'Трассы соревнований';
		$this->description = 'Трассы соревнований по мотоджимхане';
		$this->keywords = 'джимхана трассы, трассы по мотоджимхане, трассы джимханы';
		$this->layout = 'full-content';
		
		/** @var Stage[] $stages */
		$stages = Stage::find()->where(['not', ['trackPhoto' => null]])->andWhere(['status' => Stage::STATUS_PAST])
			->andWhere(['trackPhotoStatus' => Stage::PHOTO_PUBLISH])->all();
		/** @var SpecialStage[] $specialStages */
		$specialStages = SpecialStage::find()->where(['not', ['photoPath' => null]])->all();
		
		$items = [];
		foreach ($stages as $stage) {
			$items[] = ['type' => 'stage', 'stage' => $stage, 'date' => $stage->dateOfThe ? $stage->dateOfThe : 0];
		}
		foreach ($specialStages as $stage) {
			$items[] = ['type' => 'specialStage', 'stage' => $stage, 'date' => $stage->dateStart ? $stage->dateStart : 0];
		}
		
		usort($items, function($a, $b){
			return ($a['date'] <= $b['date']);
		});
		
		$pages = new Pagination(['totalCount' => count($items), 'pageSize' => 18]);
		$items = array_slice($items, $pages->offset, $pages->limit);
		
		$data = [];
		foreach ($items as $item) {
			$stage = $item['stage'];
			switch ($item['type']) {
				case 'stage':
					/** @var Participant $bestItem */
					$bestItem = $stage->getActiveParticipants()->orderBy(['bestTime' => SORT_ASC, 'id' => SORT_ASC])->one();
					if (!$bestItem) {
						continue;
					}
					$date = $stage->dateOfThe ? $stage->dateOfThe : 0;
					if (!isset($data[$date])) {
						$data[$date] = [
							'year'  => $stage->dateOfThe ? date('Y', $stage->dateOfThe) : null,
							'items' => []
						];
					}
					$data[$date]['items'][] = [
						'photoPath'  => $stage->trackPhoto,
						'bestTime'   => $bestItem->humanBestTime,
						'athlete'    => $bestItem->athlete->getFullName(),
						'motorcycle' => $bestItem->motorcycle->getFullTitle(),
						'class'      => $bestItem->athleteClass->title,
						'stage'      => $stage->title,
						'url'        => Url::to(['/competitions/stage', 'id' => $stage->id])
					];
					break;
				case 'specialStage':
					/** @var RequestForSpecialStage $bestItem */
					$bestItem = RequestForSpecialStage::find()->where(['status' => RequestForSpecialStage::STATUS_APPROVE, 'stageId' => $stage->id])
						->orderBy(['time' => SORT_ASC, 'id' => SORT_ASC])->one();
					if (!$bestItem) {
						continue;
					}
					$date = $stage->dateStart ? $stage->dateStart : 0;
					if (!isset($data[$date])) {
						$data[$date] = [
							'year'  => $stage->dateStart ? date('Y', $stage->dateStart) : null,
							'items' => []
						];
					}
					$data[$date]['items'][] = [
						'photoPath'  => $stage->photoPath,
						'bestTime'   => $bestItem->resultTimeHuman,
						'athlete'    => $bestItem->athlete->getFullName(),
						'motorcycle' => $bestItem->motorcycle->getFullTitle(),
						'class'      => $bestItem->athleteClass->title,
						'stage'      => $stage->title,
						'url'        => Url::to(['/competitions/special-stage', 'id' => $stage->id])
					];
					break;
			}
		}
		
		krsort($data);
		
		return $this->render('tracks', ['data' => $data, 'pages' => $pages]);
	}
	
	public function actionDocuments()
	{
		$this->pageTitle = 'Документы';
		$this->description = 'Документы, относящиеся к мото джимхане';
		$this->keywords = 'регламент соревнований, регламент мото джимхана, правила проведения соревнований, мото джимхана правила, 
		мото джимхана классы, классы мото джимханы, мото джимхана регламент';
		
		$this->layout = 'main-with-img';
		$this->background = 'background4.png';
		
		$sections = DocumentSection::findAll(['status' => 1]);
		
		return $this->render('documents', [
			'sections' => $sections
		]);
	}
	
	public function actionLogin()
	{
		$this->pageTitle = 'Вход в личный кабинет';
		$this->keywords = 'джимхана кап, gymkhana cup';
		
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}
		
		$this->layout = 'main-with-img';
		$this->background = 'login.png';
		
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->redirect(['/profile/info']);
		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}
	
	public function actionLogout()
	{
		Yii::$app->user->logout();
		
		return $this->goHome();
	}
	
	public function actionAddFeedback()
	{
		/** @var Feedback $form */
		$form = new Feedback();
		$form->load(\Yii::$app->request->post());
		$form->validate();
		if (!$form->email && !$form->phone && !$form->athleteId) {
			return 'Укажите корректные контакты для связи!';
		}
		if ($form->phoneOrMail && !$form->phone && !$form->email) {
			return 'Указаны некорректные контакты для связи';
		}
		if (!$form->text) {
			return 'Укажите текст сообщения';
		}
		if (!$form->username) {
			return 'Укажите ваше имя';
		}
		if ($form->save()) {
			return true;
		} else {
			return var_dump($form->errors);
		}
	}
	
	public function actionRegistration()
	{
		$this->pageTitle = 'Регистрация в личном кабинете';
		
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}
		
		$this->layout = 'main-with-img';
		$this->background = 'register.png';
		
		$registration = new TmpAthlete();
		
		return $this->render('registration', [
			'registration' => $registration
		]);
	}
	
	public function actionAddRegistration()
	{
		$form = new TmpAthlete();
		if ($form->load(\Yii::$app->request->post())) {
			$marks = \Yii::$app->request->post('mark');
			$models = \Yii::$app->request->post('model');
			$cbm = \Yii::$app->request->post('cbm');
			$power = \Yii::$app->request->post('power');
			if (!$marks) {
				return 'Необходимо указать марку мотоцикла';
			}
			if (!$models) {
				return 'Необходимо указать модель мотоцикла';
			}
			if (!$cbm) {
				return 'Необходимо указать объём';
			}
			if (!$power) {
				return 'Необходимо указать мощность';
			}
			if (count($marks) != count($models)) {
				return 'Для каждого мотоцикла необходимо указать марку и модель';
			}
			if (count($cbm) != count($marks)) {
				return 'Для каждого мотоцикла необходимо указать объём двигателя';
			}
			if (count($power) != count($marks)) {
				return 'Для каждого мотоцикла необходимо указать мощность мотоцикла';
			}
			$motorcycles = [];
			foreach ($marks as $i => $mark) {
				if (!$mark && !$models[$i]) {
					continue;
				}
				if (!$mark || !$models[$i]) {
					return 'Для каждого мотоцикла необходимо указать марку и модель';
				}
				if (!isset($cbm[$i]) || !$cbm[$i] || !isset($power[$i]) || !$power[$i]) {
					return 'Для каждого мотоцикла необходимо указать мощность и объём';
				}
				$motorcycles[] = [
					'mark'  => (string)$mark,
					'model' => (string)$models[$i],
					'cbm'   => (int)$cbm[$i],
					'power' => $power[$i]
				];
			}
			if (!$motorcycles) {
				return 'Необходимо указать минимум один мотоцикл';
			}
			$form->motorcycles = json_encode($motorcycles);
			if (!$form->cityId && !$form->city) {
				return 'Необходимо указать город';
			}
			if (!$form->email) {
				return 'Необходимо указать email';
			}
			if (Athlete::findOne(['upper("email")' => mb_strtoupper($form->email), 'hasAccount' => 1])
				|| TmpAthlete::find()->where(['upper("email")' => mb_strtoupper($form->email)])
					->andWhere(['status' => TmpAthlete::STATUS_NEW])->one()
			) {
				return 'Указанный email занят';
			}
			if (!$form->validate()) {
				return 'Необходимо заполнить все поля, кроме номера телефона';
			}
			if ($form->save(false)) {
				if (YII_ENV == 'prod') {
					$text = 'Новый запрос на регистрацию в личном кабинете';
					$text .= '<br>';
					$text .= '<b>Фио: </b>' . $form->lastName . ' ' . $form->firstName;
					$text .= '<br>';
					$text .= '<b>Город: </b>' . $form->city;
					$text .= '<br>';
					$text .= '<b>Количество мотоциклов: </b>' . count($motorcycles);
					\Yii::$app->mailer->compose('text', ['text' => $text])
						->setTo('nadia__@bk.ru')
						->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
						->setSubject('gymkhana-cup: запрос на регистрацию')
						->send();
					/*\Yii::$app->mailer->compose('text', ['text' => 'Новый запрос на регистрацию в личном кабинете.'])
						->setTo('lyadetskaya.ns@yandex.ru')
						->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
						->setSubject('gymkhana-cup: запрос на регистрацию')
						->send();*/
				}
				
				return true;
			}
			
			return 'Возникла ошибка при сохранении данных';
		}
		
		return 'Возникла ошибка при регистрации';
	}
	
	public function actionAppendMotorcycle($i)
	{
		return $this->renderAjax('_append', ['i' => $i + 1]);
	}
	
	public function actionResetPassword()
	{
		$this->pageTitle = 'Восстановление пароля';
		$model = new PasswordResetRequestForm();
		
		$this->layout = 'main-with-img';
		$this->background = 'reset-psw.png';
		
		return $this->render('reset-password', ['model' => $model]);
	}
	
	public function actionSendMailForResetPassword()
	{
		$model = new PasswordResetRequestForm();
		if ($model->load(\Yii::$app->request->post())) {
			$athlete = Athlete::findOne(['upper("email")' => mb_strtoupper($model->login)]);
			if (!$athlete) {
				$login = preg_replace('~\D+~', '', $model->login);
				if ($login == $model->login) {
					$athlete = Athlete::findOne(['login' => $model->login]);
				}
			}
			if ($athlete && $athlete->hasAccount) {
				if ($athlete->resetPassword()) {
					return true;
				}
				
				return 'Возникла ошибка при отправке данных. Попробуйте позже.';
			}
			
			return 'Пользователь не найден. Проверьте правильность введённых данных.';
		}
		
		return 'Возникла ошибка при отправке данных.';
	}
	
	public function actionNewPassword($token)
	{
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $e) {
			return $this->goHome();
		}
		
		$this->pageTitle = 'Восстановление пароля';
		
		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			return $this->redirect(['/profile/info']);
		}
		
		return $this->render('set-new-password', [
			'model' => $model,
		]);
	}
	
	public function actionUnsubscription($token)
	{
		$this->pageTitle = 'Отмена рассылки';
		$subscription = NewsSubscription::findOne(['token' => $token]);
		$error = false;
		if ($subscription && $subscription->isActive == 1) {
			$subscription->isActive = 0;
			$subscription->dateEnd = time();
			if (!$subscription->save()) {
				$error = \Yii::t('app', 'Возникла ошибка при сохранении данных.');
			}
		} else {
			$error = \Yii::t('app', 'Подписка не найдена.');
		}
		
		return $this->render('unsubscription', ['error' => $error]);
	}
}
