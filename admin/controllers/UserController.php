<?php

namespace admin\controllers;

use admin\models\SignupForm;
use Yii;
use common\models\User;

/**
 * PagesController implements the CRUD actions for Page model.
 */
class UserController extends BaseController
{
	public function actionSignup()
	{
		$this->can('developer');
		
		$signup = new SignupForm();
		if ($signup->load(\Yii::$app->request->post())) {
			$user = new User();
			$user->username = $signup->username;
			$user->setPassword($signup->password);
			$user->status = User::STATUS_ACTIVE;
			$user->generateAuthKey();

			if ($user->save()) {
				$auth = \Yii::$app->authManager;
				$role = $auth->getRole('admin');
				$auth->assign($role, $user->getId());

				return $this->redirect('signup');
			} else {
				return var_dump($user->errors);
			}
		}
		
		return $this->render('signup', ['model' => $signup]);
	}
	
	public function actionHelp()
	{
		$this->can('admin');
		
		return $this->render('help');
	}
}
