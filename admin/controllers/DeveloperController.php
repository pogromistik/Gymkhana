<?php
/**
 * Created by PhpStorm.
 * User: nadia
 * Date: 25.09.2017
 * Time: 9:34
 */

namespace admin\controllers;


use common\models\Work;

class DeveloperController extends BaseController
{
	public function init()
	{
		\Yii::$app->user->can('developer');
		
		return parent::init();
	}
	
	public function actionWorkPage()
	{
		$model = Work::find()->one();
		if (!$model) {
			$model = new Work();
		}
		
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['work-page']);
		}
		
		return $this->render('work-page', ['model' => $model]);
	}
}