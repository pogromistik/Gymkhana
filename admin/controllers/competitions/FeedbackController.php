<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use Yii;
use common\models\Feedback;
use common\models\search\FeedbackSearch;
use yii\web\NotFoundHttpException;

/**
 * FeedbackController implements the CRUD actions for Feedback model.
 */
class FeedbackController extends BaseController
{
    /**
     * Lists all Feedback models.
     * @return mixed
     */
    public function actionIndex()
    {
	    $this->can('competitions');
	    
        $searchModel = new FeedbackSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Feedback model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
	    $this->can('competitions');
	    
	    $model = $this->findModel($id);
	    if ($model->isNew) {
	    	$model->isNew = 0;
	    	$model->save();
	    }
	    
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing Feedback model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	    $this->can('admin');
	    
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Feedback model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Feedback the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feedback::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionChangeStatus($id)
    {
	    $this->can('competitions');
	    
	    $model = $this->findModel($id);
	    $model->isNew = 1;
	    $model->save();
	    return $this->redirect(['index']);
    }
}
