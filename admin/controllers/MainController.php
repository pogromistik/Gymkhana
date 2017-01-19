<?php
namespace admin\controllers;

use common\models\HelpModel;
use common\models\Link;
use common\models\MainMenu;
use common\models\MainPhoto;
use common\models\Page;
use common\models\search\LinkSearch;
use common\models\search\MainMenuSearch;
use Yii;
use yii\web\NotFoundHttpException;
use dosamigos\editable\EditableAction;

/**
 * Site controller
 */
class MainController extends BaseController
{
	public function actions()
	{
		return [
			'update' => [
				'class'       => EditableAction::className(),
				'modelClass'  => MainPhoto::className(),
				'forceCreate' => false
			]
		];
	}
	
	public function actionIndex()
	{
		$this->can('admin');
		
		$page = Page::findOne(['layoutId' => 'main']);
		if (!$page) {
			throw new NotFoundHttpException('Страница не найдена');
		}
		
		$sliders = MainPhoto::find()->where(['type' => MainPhoto::PICTURES_SLIDER])->orderBy(['sort' => SORT_ASC])->all();
		$leftMenu = MainPhoto::find()->where(['type' => MainPhoto::PICTURES_LEFT_MENU])->orderBy(['sort' => SORT_ASC])->all();
		$bottomMenu = MainPhoto::find()->where(['type' => MainPhoto::PICTURES_BOTTOM_MENU])->orderBy(['sort' => SORT_ASC])->all();
		
		$searchModel = new MainMenuSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'sliders'      => $sliders,
			'leftMenu'     => $leftMenu,
			'bottomMenu'   => $bottomMenu,
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'page'         => $page
		]);
	}
	
	public function actionDeletePicture($id)
	{
		$this->can('admin');
		
		$picture = MainPhoto::findOne($id);
		if (!$picture) {
			throw new NotFoundHttpException('Изображение не найдено');
		}
		HelpModel::deletePhoto($picture, $picture->fileName);
		
		return $this->redirect(['/main/index']);
	}
	
	/**
	 * Displays a single Link model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionViewLink($id)
	{
		return $this->render('view-link', [
			'model' => $this->findModelLink($id),
		]);
	}
	
	/**
	 * Creates a new Link model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreateLink()
	{
		$model = new Link();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view-link', 'id' => $model->id]);
		} else {
			return $this->render('create-link', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing Link model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdateLink($id)
	{
		$model = $this->findModelLink($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view-link', 'id' => $model->id]);
		} else {
			return $this->render('update-link', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Deletes an existing Link model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDeleteLink($id)
	{
		$this->findModelLink($id)->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the Link model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Link the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModelLink($id)
	{
		if (($model = Link::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionViewMenu($id = null)
	{
		/** @var MainMenu $item */
		$item = null;
		if ($id) {
			$item = MainMenu::findOne($id);
			if (!$item) {
				throw new NotFoundHttpException('Пункт меню не найден');
			}
		} else {
			$item = new MainMenu();
		}
		
		if ($item->load(Yii::$app->request->post())) {
			if ($item->save()) {
				return $this->redirect(['index']);
			} else {
				return var_dump($item->errors);
			}
		}
		
		return $this->render('view-menu', ['item' => $item]);
	}
	
	public function actionDeleteMenu($id)
	{
		$item = MainMenu::findOne($id);
		if (!$item) {
			throw new NotFoundHttpException('Пункт меню не найден');
		}
		$item->delete();
		return $this->redirect(['index']);
	}
}
