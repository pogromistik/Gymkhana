<?php
namespace champ\controllers;

use champ\models\LoginForm;
use common\models\AssocNews;
use common\models\DocumentSection;
use common\models\Feedback;
use Yii;
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
		$this->pageTitle = 'Ассоциация мото джимханы России';
		$this->description = 'Сайт, посвященный соревнованиям по мото джимхане в России. Новости мото джимханы.';
		$this->keywords = 'мото джимхана, мотоджимхана, motogymkhana, moto gymkhana, новости мото джимханы, ассоциация мото джимханы';
		
		$news = AssocNews::find()->where(['<=', 'datePublish', time()]);
		$pagination = new Pagination([
			'defaultPageSize' => 10,
			'totalCount'      => $news->count(),
		]);
		$news = $news->orderBy(['secure' => SORT_DESC, 'datePublish' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
		
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
		мото джимхана классы, классы мото джимханы';
		
		$sections = DocumentSection::findAll(['status' => 1]);
		
		return $this->render('documents', [
			'sections' => $sections
		]);
	}
	
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}
		
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->redirect(['index']);
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
		if (!$form->email && !$form->phone) {
			return 'Укажите корректные контакты для связи!';
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
}
