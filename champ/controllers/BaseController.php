<?php
namespace app\controllers;

use common\models\HelpModel;
use common\models\MainPhoto;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class BaseController extends Controller
{
	public $description = '';
	public $pageTitle = '';
	public $keywords = '';
	public $url = '';

	public function actionDownload($id, $dir, $name)
	{
		$file = Yii::getAlias($dir . '' . $id);

		return Yii::$app->response->sendFile($file, $name);
	}
}
