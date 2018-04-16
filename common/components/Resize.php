<?php
namespace common\components;

use Imagine\Image\Box;
use yii\imagine\Image;

class Resize {
	const NEW_SIZE = 1000; //px
	
	public static function resizeImage($srcFile)
	{
		$img = Image::getImagine()->open($srcFile);

		$currentWidth = $newWidth = $img->getSize()->getWidth();
		$currentHeight = $newHeight = $img->getSize()->getHeight();
		//$r = $currentWidth / $currentHeight;
		
		if ($currentWidth > $currentHeight) {
			//$newWidth = self::NEW_SIZE;
			//$newHeight = $newWidth / $r;
			$currentMax = $currentWidth;
		} else {
			//$newHeight = self::NEW_SIZE;
			//$newWidth = $newHeight * $r;
			$currentMax = $currentHeight;
		}
		
		if ($currentMax <= self::NEW_SIZE) { //если изображение итак нормального размера - выходим
			return true;
		}
		
		$img->thumbnail(new Box(self::NEW_SIZE, self::NEW_SIZE))->save($srcFile, ['quality' => 90]);
		
		/*Image::thumbnail($srcFile, $newWidth, $newHeight)
			->save($srcFile);*/
		
		return true;
	}
}