<?php

namespace admin\controllers\competitions;

use common\models\ClassesRequest;
use common\models\ClassHistory;
use common\models\Notice;
use Yii;
use common\models\search\ClassesRequestSearch;
use admin\controllers\BaseController;
use yii\helpers\Url;

/**
 * ClassesRequestController implements the CRUD actions for ClassesRequest model.
 */
class ClassesRequestController extends BaseController
{
    public function init()
    {
    	$this->can('canChangeClass');
	    parent::init();
    }
	
	/**
     * Lists all ClassesRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClassesRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['status' => ClassesRequest::STATUS_NEW]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionProcess()
    {
	    $id = \Yii::$app->request->post('id');
	    if (!$id) {
		    return 'Запрос не найден';
	    }
	    $text = \Yii::$app->request->post('reason');
	    if (!$text) {
		    return 'Необходимо ввести текст';
	    }
	    $status = \Yii::$app->request->post('status');
	    if (!$status) {
	    	return 'Внутренняя ошибка. Свяжитесь с разработчиком';
	    }
	    if (mb_strlen($text) > 255) {
		    return 'Текст должен содержать не более 255 символов';
	    }
	    $request = ClassesRequest::findOne($id);
	    if (!$request) {
		    return 'Запрос не найден';
	    }
	    if ($request->status != ClassesRequest::STATUS_NEW) {
		    return 'Запрос уже был обработан';
	    }
	
	    if (\Yii::$app->mutex->acquire('ClassesRequest-' . $request->id, 10)) {
	    	switch ($status) {
			    case ClassesRequest::STATUS_APPROVE:
			    	$athlete = $request->athlete;
				    $history = new ClassHistory();
				    $history->athleteId = $request->athleteId;
				    $history->oldClassId = $athlete->athleteClassId;
				    $history->newClassId = $request->newClassId;
				    $history->event = $text;
				    $transaction = \Yii::$app->db->beginTransaction();
				    if (!$history->save()) {
					    $transaction->rollBack();
					
					    return var_dump($history->errors);
				    }
				    $athlete->athleteClassId = $history->newClassId;
				    if (!$athlete->save(false)) {
					    $transaction->rollBack();
					
					    return var_dump($athlete->errors);
				    }
				    $request->feedback = $text;
				    $request->status = ClassesRequest::STATUS_APPROVE;
				    $request->save();
				    
				    $text = 'Ваш запрос на изменение класса подтверждён.';
				
				    Notice::add($request->athleteId, $text);
				    $transaction->commit();
			    	break;
			    case ClassesRequest::STATUS_CANCEL:
			    	$request->feedback = $text;
			    	$request->status = ClassesRequest::STATUS_CANCEL;
			    	$request->save();
				    $link = Url::to(['/profile/history-classes-request']);
				    $text = 'Ваш запрос на изменение класса отклонён. Чтобы узнать подробности, перейдите по ссылке.';
				
				    Notice::add($request->athleteId, $text, $link);
			    	break;
		    }
		    \Yii::$app->mutex->release('ClassesRequest-' . $request->id);
	    } else {
		    \Yii::$app->mutex->release('ClassesRequest-' . $request->id);
		    return 'Информация устарела. Пожалуйста, перезагрузите страницу';
	    }
	
	    return true;
    }
}
