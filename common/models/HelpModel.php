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
	const DEFAULT_TIME_ZONE = 'Europe/Moscow';
	
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
	
	public static $month = [
		1  => 'Январь',
		2  => 'Февраль',
		3  => 'Март',
		4  => 'Апрель',
		5  => 'Май',
		6  => 'Июнь',
		7  => 'Июль',
		8  => 'Август',
		9  => 'Сентябрь',
		10 => 'Октябрь',
		11 => 'Ноябрь',
		12 => 'Декабрь'
	];
	
	public static function getFlagInfo($countryId, $lang)
	{
		$flag = null;
		$countryTitle = null;
		switch ($countryId) {
			case 1:
				$flag = 'rf.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Россия' : 'Russia';
				break;
			case 87:
				$flag = 'spain.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Испания' : 'Spain';
				break;
			case 3:
				$flag = 'rb.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Беларусь' : 'Belarus';
				break;
			case 12:
				$flag = 'latvia.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Латвия' : 'Latvia';
				break;
			case 109:
				$flag = 'livan.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Ливан' : 'Lebanon';
				break;
			case 139:
				$flag = 'niderland.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Нидерланды' : 'Netherlands';
				break;
			case 15:
				$flag = 'moldova.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Молдова' : 'Moldova';
				break;
			case 14:
				$flag = 'eesti.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Эстония' : 'Estonia';
				break;
			case 215:
				$flag = 'chej.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Чехия' : 'Czech Republic';
				break;
			case 4:
				$flag = 'kz.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Казахстан' : 'Kazakhstan';
				break;
			case 229:
				$flag = 'japan.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Япония' : 'Japan';
				break;
			case 65:
				$flag = 'germany.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Германия' : 'Germany';
				break;
			case 36:
				$flag = 'belgium.jpg';
				$countryTitle = $lang == TranslateMessage::LANGUAGE_RU ? 'Бельгия' : 'Belgium';
		}
		if ($flag) {
			return ['flag' => $flag, 'title' => $countryTitle];
		} else {
			return null;
		}
	}
	
	public static function getMonth($id)
	{
		return \Yii::t('app', self::$month[$id]);
	}
	
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
	
	public static function mb_ucfirst($name)
	{
		return mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($name, 1, null, 'UTF-8');
	}
	
	public static function convertTimeToHuman($time)
	{
		$min = str_pad(floor($time / 60000), 2, '0', STR_PAD_LEFT);
		$sec = str_pad(floor(($time - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
		$mls = str_pad(round(($time - $min * 60000 - $sec * 1000) / 10), 2, '0', STR_PAD_LEFT);
		
		return $min . ':' . $sec . '.' . $mls;
	}
	
	public static function convertTime($time)
	{
		list($min, $secs) = explode(':', $time);
		$result = ($min * 60000) + round($secs * 1000);
		if ($result > Time::FAIL_TIME || $result === (float)0) {
			$result = Time::FAIL_TIME;
		}
		
		return $result;
	}
	
	/**
	 * @param $time
	 *
	 * @return bool
	 */
	public static function checkTimeFormat($time)
	{
		$digits = preg_replace('~\D+~', '', $time);
		if (mb_strlen($digits) == 6) {
			return true;
		}
		
		return false;
	}
}
