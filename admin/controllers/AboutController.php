<?php

namespace backend\controllers;

use common\models\AboutSlider;
use common\models\HelpModel;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\AboutBlock;
use common\models\search\AboutBlockSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AboutController implements the CRUD actions for AboutBlock model.
 */
class AboutController extends BaseController
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

    public function actions()
    {
        return [
            'update-slider' => [
                'class'       => EditableAction::className(),
                'modelClass'  => AboutSlider::className(),
                'forceCreate' => false
            ]
        ];
    }

    /**
     * Lists all AboutBlock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AboutBlockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AboutBlock model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AboutBlock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AboutBlock();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            HelpModel::saveSliderPhotos($model, 'about', $model->id, HelpModel::MODEL_ABOUT_SLIDER);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AboutBlock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            HelpModel::saveSliderPhotos($model, 'about', $model->id, HelpModel::MODEL_ABOUT_SLIDER);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AboutBlock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $sliderPictures = AboutSlider::findAll(['blockId' => $id]);
        foreach ($sliderPictures as $picture) {
            HelpModel::deletePhoto($picture, $picture->picture);
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AboutBlock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AboutBlock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AboutBlock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeleteSlider($id, $modelId)
    {
        $this->can('admin');

        $picture = AboutSlider::findOne($id);
        if (!$picture) {
            throw new NotFoundHttpException('Изображение не найдено');
        }
        HelpModel::deletePhoto($picture, $picture->picture);

        return $this->redirect(['update', 'id' => $modelId]);
    }
}
