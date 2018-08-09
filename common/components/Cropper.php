<?php
namespace common\components;

use Imagine\Image\Box;
use Imagine\Image\Point;
use sadovojav\cutter\behaviors\CutterBehavior;
use yii\base\UserException;
use yii\helpers\Json;
use yii\imagine\Image;
use yii\web\UploadedFile;

class Cropper extends CutterBehavior {
	public function upload($attribute)
	{
		$class = \yii\helpers\StringHelper::basename(get_class($this->owner)) . 'Cutter';
		
		if ($uploadImage = UploadedFile::getInstance($this->owner, $attribute)) {
			if ($uploadImage->size > 1500000) {
				return false;
			}

			if ($uploadImage->type != 'image/jpeg' && $uploadImage->type != 'image/png') {
				return false;
			}
			
			if (!$this->owner->isNewRecord) {
				$this->delete($attribute);
			}
			
			$cropping = $_POST[$class][$attribute . '-cropping'];
			$croppingFileName = md5(uniqid() . '_' . $uploadImage->name . $this->quality . Json::encode($cropping));
			$croppingFileExt = strrchr($uploadImage->name, '.');
			
			$croppingFileBasePath = \Yii::getAlias($this->basePath) . DIRECTORY_SEPARATOR . $this->baseDir;
			
			if (!is_dir($croppingFileBasePath)) {
				mkdir($croppingFileBasePath, 0755, true);
			}
			
			$croppingFilePath = \Yii::getAlias($this->basePath) . DIRECTORY_SEPARATOR . $this->baseDir . DIRECTORY_SEPARATOR;
			if (!is_dir($croppingFilePath)) {
				mkdir($croppingFilePath, 0755, true);
			}
			
			$fileSavePath = $croppingFilePath . DIRECTORY_SEPARATOR . $croppingFileName . $croppingFileExt;
			
			$point = new Point($cropping['dataX'], $cropping['dataY']);
			$box = new Box($cropping['dataWidth'], $cropping['dataHeight']);
			
			$palette = new \Imagine\Image\Palette\RGB();
			$color = $palette->color('fff', 0);
			
			Image::frame($uploadImage->tempName, 0, 'fff', 0)
				->rotate($cropping['dataRotate'], $color)
				->crop($point, $box)
				->save($fileSavePath, ['quality' => $this->quality]);
			
			$this->owner->{$attribute} = '/' . $this->baseDir . '/' . $croppingFileName . $croppingFileExt;
		} elseif (isset($_POST[$class][$attribute . '-remove']) && $_POST[$class][$attribute . '-remove']) {
			$this->delete($attribute);
		} elseif (!empty($_POST[$class][$attribute])) {
			$this->owner->{$attribute} = $_POST[$class][$attribute];
		} elseif (isset($this->owner->oldAttributes[$attribute])) {
			$this->owner->{$attribute} = $this->owner->oldAttributes[$attribute];
		}
	}
}