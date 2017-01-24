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
	public static $thanks = [
		'thankyou (английский)',
		'falenderim (албанский)',
		'дзякуй (белорусский)',
		'hvala (боснийский)',
		'diolch (валлийский)',
		'koszonom (венгерский)',
		'mahalo (гавайский)',
		'спасибо (русский)',
		'tak (датский)',
		'ngiyabonga (зулу)',
		'takk (исландский)',
		'gracias (испанский)',
		'grazie (итальянский)',
		'рахмет (казахский)',
		'ыракмат (киргизский)',
		'gras (креольский)',
		'sipas (курдский)',
		'umbulelo (кхоса)',
		'gratiasago (латынь)',
		'pateiciba (латышский)',
		'dekoju (литовский)',
		'whakawhetai (маори)',
		'баярлалаа (монгольский)',
		'vielendank (немецкий)',
		'bedankt (нидерландский)',
		'dzieki (польский)',
		'obrigado (португальский)',
		'спасибо (русский)',
		'pasalamat (себуанский)',
		'хвала (сербский)',
		'liteboho (сесото)',
		'vdaka (словацкий)',
		'mahad (сомали)',
		'shukrani (суахили)',
		'salamat (тагальский)',
		'ташаккур (таджикский)',
		'tesekkurler (турецкий)',
		'minnatdorchilik (узбекский)',
		'дякуємо (украинский)',
		'merci (французский)',
		'hvala (хорватский)',
		'diky (чешский)',
		'tack (шведский)',
		'tanan (эстонский)',
		'thankyou (английский)',
		'falenderim (албанский)',
		'дзякуй (белорусский)',
		'hvala (боснийский)',
		'diolch (валлийский)',
		'koszonom (венгерский)',
		'mahalo (гавайский)',
		'спасибо (русский)'
	];
	const MODEL_MAIN_PHOTO = 1;
	const MODEL_NEWS_SLIDER = 2;
	const MODEL_ABOUT_SLIDER = 3;
	
	public static function savePreviewPhoto($model, $folder)
	{
		$dir = \Yii::getAlias('@files') . '/' . $folder . '/' . $model->id;
		if (!file_exists($dir)) {
			mkdir($dir);
		}
		$file = UploadedFile::getInstance($model, 'file');
		if ($file) {
			$fileName = round(microtime(true) * 1000) . '.' . $file->extension;
			$file->saveAs($dir . '/' . $fileName);
			$model->previewImage = '/' . $folder . '/' . $model->id . '/' . $fileName;
			$model->save(false);
		}
	}
	
	public static function createFolder($folder)
	{
		$dir = \Yii::getAlias('@files') . '/' . $folder;
		if (!file_exists($dir)) {
			mkdir($dir);
		}
		
		return true;
	}
	
	public static function saveOtherPhoto($model, $folder, $varName, $fileVar, $isFullFolder = false)
	{
		if (!$isFullFolder) {
			$dir = \Yii::getAlias('@files') . '/' . $folder . '/' . $model->id;
			$saveDirName = '/' . $folder . '/' . $model->id;
			if (!file_exists($dir)) {
				self::createFolder($folder);
				mkdir($dir);
			}
		} else {
			$dir = \Yii::getAlias('@files') . '/' . $folder;
			$saveDirName = '/' . $folder;
			self::createFolder($folder);
		}
		
		$file = UploadedFile::getInstance($model, $fileVar);
		if ($file) {
			$fileName = round(microtime(true) * 1000) . '.' . $file->extension;
			$file->saveAs($dir . '/' . $fileName);
			$model->$varName = $saveDirName . '/' . $fileName;
			$model->save(false);
		}
	}
	
	public static function saveSliderPhotos($model, $folder, $folderId, $typeModel)
	{
		$dir = \Yii::getAlias('@files') . '/' . $folder . '/' . $folderId . '/slider';
		if (!file_exists($dir)) {
			if (!file_exists(\Yii::getAlias('@files') . '/' . $folder)) {
				mkdir(\Yii::getAlias('@files') . '/' . $folder);
			}
			if (!file_exists(\Yii::getAlias('@files') . '/' . $folder . '/' . $folderId)) {
				mkdir(\Yii::getAlias('@files') . '/' . $folder . '/' . $folderId);
			}
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
						break;
					case self::MODEL_ABOUT_SLIDER:
						$slider = new AboutSlider();
						$slider->blockId = $model->id;
						break;
				}
				
				$fileName = round(microtime(true) * 1000) . '.' . $file->extension;
				$file->saveAs($dir . '/' . $fileName);
				$slider->picture = '/' . $folder . '/' . $folderId . '/slider/' . $fileName;
				$slider->save();
			}
		}
	}
	
	public static function deletePhoto($picture, $fileName)
	{
		$filePath = Yii::getAlias('@files') . '/' . $fileName;
		if (file_exists($filePath)) {
			unlink($filePath);
			$picture->delete();
			
			return true;
		}
		
		return false;
	}
	
	public static function deleteFile($folder)
	{
		$filePath = Yii::getAlias('@files') . '/' . $folder;
		if (file_exists($filePath)) {
			unlink($filePath);
			
			return true;
		}
		
		return false;
	}
}
