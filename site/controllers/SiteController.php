<?php
namespace site\controllers;

use common\models\AboutBlock;
use common\models\Album;
use common\models\City;
use common\models\Contacts;
use common\models\Files;
use common\models\HelpProject;
use common\models\Link;
use common\models\MainMenu;
use common\models\MainPhoto;
use common\models\Marshal;
use common\models\News;
use common\models\Page;
use common\models\Regular;
use common\models\Track;
use common\models\Year;
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
	
	public function actionError()
	{
		$this->pageTitle = 'Страница не найдена';
		return $this->render('error');
	}
	
	public function actionIndex()
	{
		$page = Page::findOne(['layoutId' => 'main']);
		$this->pageTitle = $page->title;
		$this->description = $page->description;
		$this->keywords = $page->keywords;
		$this->layout = 'main-page';
		
		$slider = MainPhoto::find()->where(['type' => MainPhoto::PICTURES_SLIDER])->orderBy(['sort' => SORT_ASC])->all();
		$rightMenu = MainPhoto::find()->where(['type' => MainPhoto::PICTURES_RIGHT_MENU])->select('fileName')
			->orderBy(['sort' => SORT_ASC])->asArray()->column();
		$bottomMenu = MainPhoto::find()->where(['type' => MainPhoto::PICTURES_BOTTOM_MENU])->select('fileName')
			->orderBy(['sort' => SORT_ASC])->asArray()->column();
		shuffle($bottomMenu);
		$social = Link::find()->all();
		
		$news = News::find()->where(['isPublish' => 1])->orderBy(['secure' => SORT_DESC, 'datePublish' => SORT_DESC])->one();
		
		$menuItems = [
			'green'         => MainMenu::find()->where(['type' => MainMenu::TYPE_GREEN_ITEMS])->orderBy(['sort' => SORT_ASC])->all(),
			'animateSquare' => MainMenu::find()->where(['type' => MainMenu::TYPE_ANIMATE_SQUARE])->orderBy(['sort' => SORT_ASC])->all(),
			'main'          => MainMenu::find()->where(['type' => MainMenu::TYPE_MAIN_ITEMS])->orderBy(['sort' => SORT_ASC])->all(),
			'graySquare'    => MainMenu::find()->where(['type' => MainMenu::TYPE_BIG_GRAY_SQUARE])->orderBy(['sort' => SORT_ASC])->all(),
		];
		
		$years = Year::find()->where(['status' => Year::STATUS_ACTIVE])->orderBy(['year' => SORT_DESC])->limit(4)->all();
		
		return $this->render('main-page', [
			'slider'     => $slider,
			'rightMenu'  => $rightMenu,
			'bottomMenu' => $bottomMenu,
			'social'     => $social,
			'news'       => $news,
			'menuItems'  => $menuItems,
			'years'      => $years
		]);
	}
	
	public function actionShow($url = null)
	{
		$page = Page::findOne(['url' => $url]);
		if (!$page) {
			$this->pageTitle = 'Страница не найдена';
			throw new NotFoundHttpException('Страница не найдена');
		}
		$this->pageTitle = $page->title;
		$this->description = $page->description;
		$this->keywords = $page->keywords;
		
		$data = [];
		switch ($page->layoutId) {
			case 'main':
				return $this->redirect('/');
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
				$news = News::find()->where(['isPublish' => 1]);
				$pagination = new Pagination([
					'defaultPageSize' => 10,
					'totalCount'      => $news->count(),
				]);
				$data['news'] = $news->orderBy(['secure' => SORT_DESC, 'datePublish' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
				$data['pagination'] = $pagination;
				break;
			case 'news':
				$data['page'] = $page;
				if (!$page->news || !$page->news->isPublish) {
					throw new NotFoundHttpException();
				}
				$data['oldNews'] = News::find()->where(['isPublish' => 1])
					->orderBy(['secure' => SORT_DESC, 'datePublish' => SORT_DESC])->limit(6)->all();
				break;
			case 'russia':
				$data['cities'] = City::find()->where(['showInRussiaPage' => 1])->orderBy(['title' => SORT_ASC])->all();
				break;
			case 'helpProject':
				/** @var HelpProject $model */
				$model = HelpProject::find()->one();
				$data['model'] = $model;
				$data['card'] = Contacts::find()->one();
				$photos = $model->getPhotos();
				shuffle($photos);
				$data['photos'] = $photos;
				break;
			case 'photoGallery':
				$years = Year::find()->where(['status' => Year::STATUS_ACTIVE]);
				$pagination = new Pagination([
					'defaultPageSize' => 10,
					'totalCount'      => $years->count(),
				]);
				$data['years'] = $years->orderBy(['year' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
				$data['pagination'] = $pagination;
				break;
			case 'tracks':
				$data['tracks'] = Track::find()->orderBy(['sort' => SORT_ASC])->all();
				break;
			case 'competitions':
				break;
			case 'sitemap':
				$pages = Page::find()->where(['not', ['layoutId' => 'news']])->all();
				/** @var Page $mapPage */
				foreach ($pages as $mapPage) {
					switch ($mapPage->layoutId) {
						case 'sitemap':
							break;
						case 'photoGallery':
							$years = Year::findAll(['status' => Year::STATUS_ACTIVE]);
							$data['map'][$mapPage->layoutId] = [
								'title' => $mapPage->title,
								'url'   => $mapPage->url
							];
							foreach ($years as $year) {
								$data['map'][$mapPage->layoutId]['children'][] = [
									'title' => $year->year,
									'url'   => '/photogallery/' . $year->year
								];
							}
							break;
						case 'tracks':
							$tracks = Track::find()->orderBy(['sort' => SORT_ASC])->all();
							$data['map'][$mapPage->layoutId] = [
								'title' => $mapPage->title,
								'url'   => $mapPage->url
							];
							/** @var Track $track */
							foreach ($tracks as $track) {
								$data['map'][$mapPage->layoutId]['children'][] = [
									'title' => $track->title,
									'url'   => $mapPage->url
								];
							}
							break;
						default:
							$data['map'][$mapPage->layoutId] = [
								'title' => $mapPage->title,
								'url'   => $mapPage->url
							];
							break;
					}
				}
				break;
			default:
				$this->pageTitle = 'Страница не найдена';
				throw new NotFoundHttpException('Страница не найдена');
				break;
		}
		
		return $this->render($page->layoutId, ['data' => $data, 'page' => $page]);
	}
	
	public function actionAlbums($year, $album = null)
	{
		$year = Year::findOne(['year' => $year]);
		if (!$year) {
			throw new NotFoundHttpException('Страница не найдена');
		}
		$albums = Album::find()->where(['yearId' => $year->id])->orderBy(['dateAdded' => SORT_DESC])->all();
		if (!$albums) {
			throw new NotFoundHttpException('Страница не найдена');
		}
		if ($album) {
			$album = Album::findOne($album);
			if (!$album) {
				throw new NotFoundHttpException('Страница не найдена');
			}
			$otherAlbums = Album::find()->where(['yearId' => $album->yearId])->all();
			$this->layout = 'album';
			$this->pageTitle = $album->title;
			
			return $this->render('album', [
				'album'       => $album,
				'otherAlbums' => $otherAlbums
			]);
		}
		$this->pageTitle = 'Фотогалерея ' . $year->year;
		$otherYears = Year::find()->where(['status' => Year::STATUS_ACTIVE])->all();
		
		return $this->render('albums', [
			'year'       => $year,
			'albums'     => $albums,
			'otherYears' => $otherYears
		]);
	}
	
	public function actionDownload($id)
	{
		$file = Files::findOne($id);
		if (!$file) {
			return 'не найден файл';
		}
		
		return \Yii::$app->response->sendFile(\Yii::getAlias('@files') . '/' . $file->folder, $file->originalTitle);
	}
}
