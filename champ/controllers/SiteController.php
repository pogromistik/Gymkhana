<?php

namespace champ\controllers;

use champ\models\LoginForm;
use champ\models\PasswordResetRequestForm;
use champ\models\ResetPasswordForm;
use common\models\AssocNews;
use common\models\Athlete;
use common\models\DocumentSection;
use common\models\Feedback;
use common\models\TmpAthlete;
use Yii;
use yii\base\InvalidParamException;
use yii\base\UserException;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends BaseController
{
	
	
	public function actionError()
	{
		$exception = Yii::$app->getErrorHandler()->exception;
		$statusCode = 404;
		$text = '';
		$title = 'not found';
		$this->pageTitle = 'Ошибка!';
		if ($exception && isset($exception->statusCode)) {
			$this->pageTitle = 'Ошибка: ' . $exception->statusCode;
			switch ($exception->statusCode) {
				case 404:
					$title = 'not found';
					$text = 'Монстр в недоумении, потому что не может найти нужную вам страницу. Но вы всегда можете ' .
						'<a href="/">вернуться на главную</a> или
<a href="#" data-toggle="modal" data-target="#feedbackForm">сообщить нам об ошибке</a>.';
					break;
				case 403:
					$title = 'forbidden';
					$text = 'Монстр расстроен, потому что вы пытаетесь зайти на запретную страницу. Но вы всегда можете ' .
						'<a href="/">вернуться на главную</a> или
<a href="#" data-toggle="modal" data-target="#feedbackForm">сообщить нам об ошибке</a>.';
					break;
				default:
					$title = 'not found';
			}
		}
		Yii::$app->getErrorHandler()->exception;
		
		return $this->render('error', [
			'title'      => $title,
			'statusCode' => $statusCode,
			'text'       => $text
		]);
	}
	
	public function actionIndex()
	{
		$this->layout = 'main-with-img';
		$this->pageTitle = \Yii::t('app', 'Мотоджимхана: события');
		$this->description = \Yii::t('app', 'Сайт, посвященный соревнованиям по мото джимхане в России. Новости мото джимханы.');
		$this->keywords = \Yii::t('app', 'мотоджимхана') .
			', motogymkhana, moto gymkhana, джимхана кап, gymkhana cup, '
			. \Yii::t('app', 'новости мото джимханы') . ', '
			. \Yii::t('app', 'события мото джимханы');
		
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
			throw new NotFoundHttpException(\Yii::t('app', 'Новость не найдена'));
		}
		$this->pageTitle = $news->title ? $news->title :
			\Yii::t('app', 'Новость от {date}', [
				'date' => date("d.m.Y", $news->datePublish)
				]);
		$this->layout = 'main-with-img';
		
		return $this->render('news', [
			'news' => $news
		]);
	}
	
	public function actionDocuments()
	{
		$this->pageTitle = \Yii::t('app', 'Документы');
		$this->description = \Yii::t('app', 'Документы, относящиеся к мото джимхане');
		$this->keywords = \Yii::t('app', 'регламент соревнований') . ', '
		. \Yii::t('app', 'правила проведения соревнований') . ', '
			. \Yii::t('app', 'классы мотоджимханы') . ', '
			. \Yii::t('app', 'мотоджимхана правила');
		
		$this->layout = 'main-with-img';
		$this->background = 'background4.png';
		
		$sections = DocumentSection::findAll(['status' => 1]);
		
		return $this->render('documents', [
			'sections' => $sections
		]);
	}
	
	public function actionLogin()
	{
		$this->pageTitle = \Yii::t('app', 'Вход в личный кабинет');
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
			return \Yii::t('app', 'Укажите корректные контакты для связи!');
		}
		if ($form->phoneOrMail && !$form->phone && !$form->email) {
			return \Yii::t('app', 'Указаны некорректные контакты для связи');
		}
		if (!$form->text) {
			return \Yii::t('app', 'Введите текст сообщения');
		}
		if (!$form->username) {
			return \Yii::t('app', 'Укажите ваше имя');
		}
		if ($form->save()) {
			return true;
		} else {
			return var_dump($form->errors);
		}
	}
	
	public function actionRegistration()
	{
		$this->pageTitle = \Yii::t('app', 'Регистрация в личном кабинете');
		
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
			if (!$marks) {
				return \Yii::t('app', 'Необходимо указать марку мотоцикла');
			}
			if (!$models) {
				return \Yii::t('app', 'Необходимо указать модель мотоцикла');
			}
			if (count($marks) != count($models)) {
				return \Yii::t('app', 'Для каждого мотоцикла необходимо указать марку и модель');
			}
			$motorcycles = [];
			foreach ($marks as $i => $mark) {
				if (!$mark && !$models[$i]) {
					continue;
				}
				if (!$mark || !$models[$i]) {
					return \Yii::t('app', 'Для каждого мотоцикла необходимо указать марку и модель');
				}
				$motorcycles[] = [
					'mark'  => (string)$mark,
					'model' => (string)$models[$i]
				];
			}
			if (!$motorcycles) {
				return \Yii::t('app', 'Необходимо указать минимум один мотоцикл');
			}
			$form->motorcycles = json_encode($motorcycles);
			if (!$form->cityId && !$form->city) {
				return \Yii::t('app', 'Необходимо указать город');
			}
			if (!$form->email) {
				return \Yii::t('app', 'Необходимо указать email');
			}
			if (Athlete::findOne(['upper("email")' => mb_strtoupper($form->email), 'hasAccount' => 1])
				|| TmpAthlete::find()->where(['upper("email")' => mb_strtoupper($form->email)])
					->andWhere(['status' => TmpAthlete::STATUS_NEW])->one()
			) {
				return \Yii::t('app', 'Указанный email занят');
			}
			if (!$form->validate()) {
				return \Yii::t('app', 'Необходимо заполнить все поля, кроме номера телефона');
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
			
			return \Yii::t('app', 'Возникла ошибка при сохранении данных');
		}
		
		return \Yii::t('app', 'Возникла ошибка при сохранении данных');
	}
	
	public function actionAppendMotorcycle($i)
	{
		return $this->renderAjax('_append', ['i' => $i + 1]);
	}
	
	public function actionResetPassword()
	{
		$this->pageTitle = \Yii::t('app', 'Восстановление пароля');
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
				
				return \Yii::t('app', 'Возникла ошибка при отправке данных. Попробуйте позже.');
			}
			
			return \Yii::t('app', 'Пользователь не найден. Проверьте правильность введённых данных.');
		}
		
		return \Yii::t('app', 'Возникла ошибка при сохранении данных');
	}
	
	public function actionNewPassword($token)
	{
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $e) {
			return $this->goHome();
		}
		
		$this->pageTitle = \Yii::t('app', 'Восстановление пароля');
		
		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			return $this->redirect(['/profile/info']);
		}
		
		return $this->render('set-new-password', [
			'model' => $model,
		]);
	}
}
