<?php

namespace admin\controllers;

use common\models\HelpModel;
use Yii;
use common\models\Marshal;
use common\models\search\MarshalSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MarshalController implements the CRUD actions for Marshal model.
 */
class MarshalsController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Marshal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->can('admin');
        
        $searchModel = new MarshalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Marshal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->can('admin');
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Marshal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->can('admin');
        
        $model = new Marshal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            HelpModel::saveOtherPhoto($model, 'marshals', 'photo', 'photoFile');
            HelpModel::saveOtherPhoto($model, 'marshals', 'motorcyclePhoto', 'motorFile');
            HelpModel::saveOtherPhoto($model, 'marshals', 'gif', 'gifFile');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Marshal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->can('admin');
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            HelpModel::saveOtherPhoto($model, 'marshals', 'photo', 'photoFile');
            HelpModel::saveOtherPhoto($model, 'marshals', 'motorcycle', 'motorFile');
            HelpModel::saveOtherPhoto($model, 'marshals', 'gif', 'gifFile');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Marshal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->can('admin');
        
        $model = $this->findModel($id);
        if ($model->photo) {
            HelpModel::deleteFile($model->photo);
        }
        if ($model->motorcyclePhoto) {
            HelpModel::deleteFile($model->motorcyclePhoto);
        }
        if ($model->gif) {
            HelpModel::deleteFile($model->gif);
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Marshal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Marshal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Marshal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
