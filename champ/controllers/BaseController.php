<?php
namespace champ\controllers;

use common\models\HelpModel;
use common\models\MainPhoto;
use common\models\OverallFile;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class BaseController extends Controller
{
	public $description = '';
	public $pageTitle = '';
	public $keywords = '';
	public $url = '';
	public $background = 'background2.PNG';
	
	public function actionDownload($id)
	{
		$file = OverallFile::findOne($id);
		if (!$file) {
			throw new NotFoundHttpException('Файл не найден');
		}
		
		return \Yii::$app->response->sendFile(\Yii::getAlias('@files') . '/' . $file->filePath, $file->fileName);
	}
}
