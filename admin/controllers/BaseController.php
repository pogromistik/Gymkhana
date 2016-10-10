<?php
namespace backend\controllers;

use common\models\HelpModel;
use common\models\MainPhoto;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
	public function can($role)
	{
		return true;
	}

	public function actionUploadPictures($type, $modelName)
	{
		$this->can('admin');

		$fileName = 'attachment_48';
		if (!file_exists(\Yii::getAlias('@files') . '/' . MainPhoto::$filePath[$type])) {
			mkdir(\Yii::getAlias('@files') . '/' . MainPhoto::$filePath[$type]);
		}
		$uploadPath = \Yii::getAlias('@files') . '/' . MainPhoto::$filePath[$type];
		//echo \yii\helpers\Json::encode($_FILES);
		//return var_dump(json_encode($_FILES));
		if (isset($_FILES[$fileName])) {

			$file = \yii\web\UploadedFile::getInstancesByName($fileName);

			$file = $file[0];

			$path_parts = pathinfo($file->name);
			$fileName = microtime(true) . '.' . $path_parts['extension'];
			if ($file->saveAs($uploadPath . '/' . $fileName)) {
				switch ($modelName) {
					case HelpModel::MODEL_MAIN_PHOTO:
						$model = new MainPhoto();
				}
				$model->type = $type;
				$model->fileName = '/' . MainPhoto::$filePath[$type] . '/' . $fileName;
				$model->save();

				//echo \yii\helpers\Json::encode($_FILES);
				return true;
			} else {
				\yii\helpers\Json::encode($file->error);

				return false;
			}
		} else {
			return 'Ошибка при загрузке файлов';
		}

		return true;
	}

	public function actionDownload($id, $dir, $name)
	{
		$file = Yii::getAlias($dir . '' . $id);

		return Yii::$app->response->sendFile($file, $name);
	}
}
