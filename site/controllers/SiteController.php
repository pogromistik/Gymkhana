<?php
namespace site\controllers;

use common\models\Link;
use common\models\MainPhoto;
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
			'error' => [
				'class' => 'yii\web\ErrorAction',
			]
		];
	}
	
	public function actionIndex()
	{
		$page = Page::findOne(['layoutId' => 'main']);
		$this->pageTitle = $page->title;
		$this->description = $page->description;
		$this->keywords = $page->keywords;
		$this->layout = 'main-page';
		
		$slider = MainPhoto::findAll(['type' => MainPhoto::PICTURES_SLIDER]);
		$leftMenu = MainPhoto::findAll(['type' => MainPhoto::PICTURES_LEFT_MENU]);
		$bottomMenu = MainPhoto::findAll(['type' => MainPhoto::PICTURES_BOTTOM_MENU]);
		$social = Link::find()->all();
		
		return $this->render('main-page', [
			'slider'     => $slider,
			'leftMenu'   => $leftMenu,
			'bottomMenu' => $bottomMenu,
			'social'     => $social
		]);
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
		
		switch ($page->layoutId) {
			case 'about':
				break;
			default:
				throw new NotFoundHttpException('Страница не найдена');
				break;
		}
		
		return $this->render($page->layoutId);
	}
}
