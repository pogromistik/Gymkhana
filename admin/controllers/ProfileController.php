<?php

namespace admin\controllers;

use admin\models\PasswordForm;
use common\models\User;

/**
 * NoticeController implements the CRUD actions for Notice model.
 */
class ProfileController extends BaseController
{
	public function actionIndex($success = false)
	{
		$user = User::findOne(\Yii::$app->user->identity->id);
		if ($user->load(\Yii::$app->request->post()) && $user->save()) {
			return $this->redirect(['index', 'success' => true]);
		}
		$errors = null;
		$password = new PasswordForm();
		if ($password->load(\Yii::$app->request->post())) {
			$errors = $password->checkPassword();
			if (!$errors && $password->saveForAdmins()) {
				return $this->redirect(['index', 'success' => true]);
			}
		}
		
		
		return $this->render('index', [
			'user'     => $user,
			'success'  => $success,
			'password' => $password,
			'errors' => $errors
		]);
	}
}