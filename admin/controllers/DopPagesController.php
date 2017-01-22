<?php

namespace admin\controllers;

use common\models\HelpModel;
use Yii;
use common\models\DopPage;
use common\models\search\DopPageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DopPageController implements the CRUD actions for DopPage model.
 */
class DopPagesController extends BaseController
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
     * Lists all DopPage models.
     * @return mixed
     */
    public function actionIndex()
    {
	    $this->can('admin');
        $searchModel = new DopPageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DopPage model.
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
     * Creates a new DopPage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
	    $this->can('admin');
        $model = new DopPage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            HelpModel::saveOtherPhoto($model, 'dop_pages', 'picture', 'pictureFile');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DopPage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
	    $this->can('admin');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            HelpModel::saveOtherPhoto($model, 'dop_pages', 'picture', 'pictureFile');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DopPage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	    $this->can('admin');
        $model = $this->findModel($id);
        if ($model->picture) {
            HelpModel::deleteFile($model->picture);
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DopPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DopPage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DopPage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
