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
		$this->pageTitle = 'События';
		$this->description = 'Сайт, посвященный соревнованиям по мото джимхане в России. Новости мото джимханы.';
		$this->keywords = 'мото джимхана, мотоджимхана, motogymkhana, moto gymkhana, новости мото джимханы, события мото джимханы, новости, события';
		
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
		
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}
		
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
				return 'Необходимо указать марку мотоцикла';
			}
			if (!$models) {
				return 'Необходимо указать модель мотоцикла';
			}
			if (count($marks) != count($models)) {
				return 'Для каждого мотоцикла необходимо указать марку и модель';
			}
			$motorcycles = [];
			foreach ($marks as $i => $mark) {
				if (!$mark && !$models[$i]) {
					continue;
				}
				if (!$mark || !$models[$i]) {
					return 'Для каждого мотоцикла необходимо указать марку и модель';
				}
				$motorcycles[] = [
					'mark'  => (string)$mark,
					'model' => (string)$models[$i]
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
			if (Athlete::findOne(['email' => $form->email]) || TmpAthlete::findOne(['email' => $form->email])) {
				return 'Указанный email занят';
			}
			if (!$form->validate()) {
				return 'Необходимо заполнить все поля, кроме номера телефона';
			}
			if ($form->save(false)) {
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
		
		return $this->render('reset-password', ['model' => $model]);
	}
	
	public function actionSendMailForResetPassword()
	{
		$model = new PasswordResetRequestForm();
		if ($model->load(\Yii::$app->request->post())) {
			$athlete = Athlete::findOne(['email' => $model->login]);
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
}
