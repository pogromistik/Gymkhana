<?php
namespace admin\controllers;

use common\models\TranslateMessage;
use common\models\TranslateMessageSource;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'auth';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/additional/layouts']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionDownloadTranslate()
    {
	    /** @var TranslateMessageSource[] $items */
	    $items = TranslateMessageSource::find()->all();
	    $res = '';
	    foreach ($items as $item) {
		    $message = TranslateMessage::findOne(['id' => $item->id]);
		    if ($message && $message->translation) {
			
		    } else {
			    $res .= $item->message . ';';
			    $res .= PHP_EOL;
		    }
	    }
	
	    return $res;
    }
}
