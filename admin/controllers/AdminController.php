<?php

namespace admin\controllers;

use common\models\Error;
use common\models\search\ErrorSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * AdminController implements the CRUD actions for Error model.
 */
class AdminController extends BaseController
{
	public function actionErrorsList()
	{
		$this->can('developer');
		
		$searchModel = new ErrorSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('list', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionFixErrors($id = null)
	{
		$this->can('developer');
		
		if ($id) {
			$error = Error::findOne($id);
			if (!$error) {
				throw new NotFoundHttpException('Ошибка не найдена');
			}
			$error->status = Error::STATUS_FIXED;
			if (!$error->save()) {
				return var_dump($error->errors);
			}
		} else {
			Error::updateAll(['status' => Error::STATUS_FIXED], ['status' => Error::STATUS_NEW]);
		}
		
		return $this->redirect(['errors-list']);
	}
}
