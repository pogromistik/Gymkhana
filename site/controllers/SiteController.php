<?php
namespace site\controllers;

use common\models\AboutBlock;
use common\models\Contacts;
use common\models\Link;
use common\models\MainPhoto;
use common\models\Marshal;
use common\models\News;
use common\models\Page;
use common\models\Regular;
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
		
		$data = [];
		switch ($page->layoutId) {
			case 'about':
				$data['blocks'] = AboutBlock::find()->orderBy(['sort' => SORT_ASC])->all();
				break;
			case 'regulars':
				$data['regulars'] = [
					1 => Regular::find()->orderBy(['sort' => SORT_ASC])->limit(3)->all(),
					2 => Regular::find()->orderBy(['sort' => SORT_ASC])->limit(1)->offset(3)->all(),
					3 => Regular::find()->orderBy(['sort' => SORT_ASC])->limit(2)->offset(4)->all(),
				];
				break;
			case  'marshals':
				$data['marshals'] = Marshal::find()->orderBy(['sort' => SORT_ASC])->all();
				break;
			case 'address':
				$data['contacts'] = Contacts::find()->one();
				$data['social'] = Link::find()->all();
				break;
			case 'allNews':
				$pages = Page::find()->where(['layoutId' => 'news']);
				$pagination = new Pagination([
					'defaultPageSize' => 10,
					'totalCount'      => $pages->count(),
				]);
				$data['pages'] = $pages->orderBy(['dateAdded' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
				$data['pagination'] = $pagination;
				break;
			case 'news':
				$data['page'] = $page;
				$data['oldNews'] = Page::find()->where(['layoutId' => 'news'])->andWhere(['not', ['id' => $page->id]])
					->orderBy(['dateAdded' => SORT_DESC])->limit(6)->all();
				break;
			default:
				throw new NotFoundHttpException('Страница не найдена');
				break;
		}
		
		return $this->render($page->layoutId, ['data' => $data]);
	}
}
