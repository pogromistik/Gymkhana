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
		return $this->render('index', [
			'sliders' => $sliders
		]);
	}

	public function actionDeletePicture($id)
	{
		$this->can('admin');

		$picture = MainPhoto::findOne($id);
		if (!$picture) {
			throw new NotFoundHttpException('Изображение не найдено');
		}
		$filePath = Yii::getAlias('@pictures').'/'.MainPhoto::$filePath[$picture->type].'/'.$picture->fileName;
		if (file_exists($filePath)) {
			unlink($filePath);
		}
		$picture->delete();

		return $this->redirect(['/main/index']);
	}
}
