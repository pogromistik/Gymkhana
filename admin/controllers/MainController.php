<?php
namespace backend\controllers;

use common\models\MainPhoto;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class MainController extends BaseController
{
	public function actionIndex()
	{
		$this->can('admin');

		$sliders = MainPhoto::findAll(['type' => MainPhoto::PICTURES_SLIDER]);
		$leftMenu = MainPhoto::findAll(['type' => MainPhoto::PICTURES_LEFT_MENU]);
		$bottomMenu = MainPhoto::findAll(['type' => MainPhoto::PICTURES_BOTTOM_MENU]);

		return $this->render('index', [
			'sliders'    => $sliders,
			'leftMenu'   => $leftMenu,
			'bottomMenu' => $bottomMenu
		]);
	}

	public function actionDeletePicture($id)
	{
		$this->can('admin');

		$picture = MainPhoto::findOne($id);
		if (!$picture) {
			throw new NotFoundHttpException('Изображение не найдено');
		}
		$filePath = Yii::getAlias('@pictures') . '/' . MainPhoto::$filePath[$picture->type] . '/' . $picture->fileName;
		if (file_exists($filePath)) {
			unlink($filePath);
		}
		$picture->delete();

		return $this->redirect(['/main/index']);
	}
}
