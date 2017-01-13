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
		
		$slider = MainPhoto::find()->where(['type' => MainPhoto::PICTURES_SLIDER])->orderBy(['sort' => SORT_ASC])->all();
		$leftMenu = MainPhoto::find()->where(['type' => MainPhoto::PICTURES_LEFT_MENU])->orderBy(['sort' => SORT_ASC])->all();
		$bottomMenu = MainPhoto::find()->where(['type' => MainPhoto::PICTURES_BOTTOM_MENU])->orderBy(['sort' => SORT_ASC])->all();
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
