<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\HelpModel;
use Yii;
use common\models\TrainingTrack;
use common\models\search\TrainingTrackSearch;
use yii\web\NotFoundHttpException;

/**
 * TrainingTracksController implements the CRUD actions for TrainingTrack model.
 */
class TrainingTracksController extends BaseController
{
    /**
     * @return \yii\web\Response
     * @throws \yii\web\ForbiddenHttpException
     */
    public function init()
    {
        $this->can('globalWorkWithCompetitions');

        return parent::init();
    }

    /**
     * Lists all TrainingTrack models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrainingTrackSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrainingTrack model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TrainingTrack model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TrainingTrack();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TrainingTrack model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TrainingTrack model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        HelpModel::deleteFile($model->imgPath);
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TrainingTrack model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return TrainingTrack the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrainingTrack::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionApprove($id)
    {
        $model = $this->findModel($id);
        if (!$model->minHeight || !$model->minWidth || !$model->conesCount) {
            return 'Сначала необходимо указать все параметры';
        }
        $model->status = TrainingTrack::STATUS_ACTIVE;
        $model->save();

        return true;
    }
}
