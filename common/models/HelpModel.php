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
	const MODEL_NEWS_SLIDER = 2;

	public static function savePreviewPhoto($model, $folder)
	{
		$dir = \Yii::getAlias('@pictures') . '/' . $folder . '/' . $model->id;
		if (!file_exists($dir)) {
			mkdir($dir);
		}
		$file = UploadedFile::getInstance($model, 'file');
		if ($file) {
			$fileName = microtime(true) . '.' . $file->extension;
			$file->saveAs($dir . '/' . $fileName);
			$model->previewImage = '/' . $folder . '/' . $model->id . '/' . $fileName;
			$model->save(false);
		}
	}

	public static function saveSliderPhotos($model, $folder, $folderId, $typeModel)
	{
		$dir = \Yii::getAlias('@pictures') . '/' . $folder . '/' . $folderId . '/slider';
		if (!file_exists($dir)) {
			mkdir($dir);
		}

		$files = UploadedFile::getInstances($model, 'slider');
		if ($files) {
			foreach ($files as $file) {
				switch ($typeModel) {
					case self::MODEL_NEWS_SLIDER:
						$slider = new NewsSlider();
						$slider->newsId = $model->newsId;
						$slider->blockId = $model->id;
				}

				$fileName = microtime(true) . '.' . $file->extension;
				$file->saveAs($dir . '/' . $fileName);
				$slider->picture = '/' . $folder . '/' . $folderId . '/slider/' . $fileName;
				$slider->save(false);
			}
		}
	}
	
	public static function deletePhoto($picture, $fileName)
	{
		$filePath = Yii::getAlias('@pictures') . '/' . $fileName;
		if (file_exists($filePath)) {
			unlink($filePath);
			$picture->delete();
			
			return true;
		}
		return false;
	}
}
