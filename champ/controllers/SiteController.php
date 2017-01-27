<?php
namespace app\controllers;

use common\models\AssocNews;
use Yii;
use yii\data\Pagination;

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
		return $id;
	}
}
