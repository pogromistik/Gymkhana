<?php
namespace backend\controllers;

use common\models\MainPhoto;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
	public function can($role)
	{
		return true;
	}

	public function actionUpload($type)
	{
		$fileName = 'file';
		if (!file_exists(\Yii::getAlias('@pictures') . '/' . MainPhoto::$filePath[$type])) {
			mkdir(\Yii::getAlias('@pictures') . '/' . MainPhoto::$filePath[$type]);
		}
		$uploadPath = \Yii::getAlias('@pictures') . '/' . MainPhoto::$filePath[$type];

		if (isset($_FILES[$fileName])) {
			$file = \yii\web\UploadedFile::getInstanceByName($fileName);

			$path_parts = pathinfo($file->name);
			$fileName = microtime(true).'.'.$path_parts['extension'];
			if ($file->saveAs($uploadPath . '/' . $fileName)) {
				$model = new MainPhoto();
				$model->type = $type;
				$model->fileName = $fileName;
				$model->save();
				echo \yii\helpers\Json::encode($file);
			}
		}

		return true;
	}

	public function actionDownload($id, $dir, $name)
	{
		$file = Yii::getAlias($dir.''.$id);
		return Yii::$app->response->sendFile($file, $name);
	}
}
