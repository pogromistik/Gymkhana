<?php
namespace admin\controllers;

use common\helpers\UserHelper;
use common\models\HelpModel;
use common\models\MainPhoto;
use common\models\User;
use common\models\Work;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class BaseController extends Controller
{
	public $description = '';
	public $pageTitle = '';
	public $keywords = '';
	public $url = '';
	
	public function can($role)
	{
		if (!\Yii::$app->user->can($role)) {
			throw new ForbiddenHttpException('Доступ запрещён');
		}
		return true;
	}
	
	public function canRegion($region)
	{
		if (!UserHelper::fromRegion($region)) {
			throw new ForbiddenHttpException('Доступ запрещён');
		}
		return true;
	}

	public function init()
	{
		parent::init();

		if (\Yii::$app->user->isGuest) {
			$this->redirect(['/user/login']);
			\Yii::$app->end();
		} elseif (\Yii::$app->user->identity->isBlocked) {
			\Yii::$app->getUser()->logout();
			\Yii::$app->getSession()->setFlash('error', 'Ваш аккаунт заблокирован');
			return $this->goHome();
		} else {
			$isBlockedSite = Work::findOne(['status' => 1]);
			if ($isBlockedSite && !\Yii::$app->user->can('developer')) {
				return $this->redirect(['/work/page']);
			}
			$user = User::findOne(\Yii::$app->user->id);
			$user->last_login_at = time();
			$user->save();
		}
	}

	public function actionUploadPictures($type, $modelName)
	{
		$this->can('admin');

		$fileName = 'attachment_48';
		$array = explode('/', MainPhoto::$filePath[$type]);
		$uploadPath = \Yii::getAlias('@files');
		foreach ($array as $item) {
			$uploadPath .= '/' . $item;
			if (!file_exists($uploadPath)) {
				mkdir($uploadPath);
			}
		}
		//echo \yii\helpers\Json::encode($_FILES);
		//return var_dump(json_encode($_FILES));
		if (isset($_FILES[$fileName])) {

			$file = \yii\web\UploadedFile::getInstancesByName($fileName);

			$file = $file[0];

			$path_parts = pathinfo($file->name);
			$fileName = round(microtime(true)*1000) . '.' . $path_parts['extension'];
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
	
	public function actionUploadAlbumPictures($folder)
	{
		$this->can('admin');
		
		$fileName = 'albums_photo';
		
		$uploadPath = \Yii::getAlias('@files') . '/' . $folder;
		
		if (isset($_FILES[$fileName])) {
			
			$file = \yii\web\UploadedFile::getInstancesByName($fileName);
			
			$file = $file[0];
			
			$path_parts = pathinfo($file->name);
			$fileName = round(microtime(true)*1000) . '.' . $path_parts['extension'];
			if ($file->saveAs($uploadPath . '/' . $fileName)) {
				return true;
			} else {
				\yii\helpers\Json::encode($file->error);
				
				return false;
			}
		} else {
			return 'Ошибка при загрузке файлов';
		}
	}

	public function actionDownload($id, $dir, $name)
	{
		$file = Yii::getAlias($dir . '' . $id);

		return Yii::$app->response->sendFile($file, $name);
	}
}
