<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * ContactForm is the model behind the contact form.
 */
class HelpModel extends Model
{
	const MODEL_MAIN_PHOTO = 1;
	
	public static function savePreviewPhoto($model, $dir)
	{
		$dir = \Yii::getAlias('@pictures') . '/'.$dir.'/' . $model->id;
		if (!file_exists($dir)) {
			mkdir($dir);
		}
		$file = UploadedFile::getInstance($model, 'file');
		if ($file) {
			$fileName = microtime(true).'.'.$file->extension;
			$file->saveAs($dir . '/' . $fileName);
			$model->previewImage = $fileName;
			$model->save(false);
		}
	}
}
