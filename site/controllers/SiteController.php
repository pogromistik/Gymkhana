<?php
namespace site\controllers;

use common\models\Page;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends BaseController
{
	public function actions()
	{
		return [
			'error'   => [
				'class' => 'yii\web\ErrorAction',
			]
		];
	}
	
	public function actionIndex()
	{
		return 1;
	}
	
	public function actionShow($url = null)
	{
		$page = Page::findOne(['url' => $url]);
		if (!$page) {
			throw new NotFoundHttpException('Страница не найдена');
		}
		$this->pageTitle = $page->title;
		$this->description = $page->description;
		$this->keywords = $page->keywords;
		
		return $this->render('index');
	}
}
