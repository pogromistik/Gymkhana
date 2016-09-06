<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
	public function actionCan()
	{
		return true;
	}

	public function actionUpload($type)
	{
		$fileName = 'file';
		$uploadPath = \Yii::getAlias('@common').'/pictures';

		//$model = new Files();
		if (isset($_FILES[$fileName])) {
			$file = \yii\web\UploadedFile::getInstanceByName($fileName);

			/*$_SESSION['upload_files_new_name'][] = $name;
			$_SESSION['upload_files_name'][] = $file->name;*/
			if ($file->saveAs($uploadPath . '/' . $file->name)) {
				echo \yii\helpers\Json::encode($file);
			}
		}

		return true;
	}
}
