<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Athlete;
use Yii;
use common\models\Notice;
use common\models\search\NoticeSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NoticeController implements the CRUD actions for Notice model.
 */
class NoticeController extends BaseController
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
     * Lists all Notice models.
     * @return mixed
     */
    public function actionIndex()
    {
	    $this->can('competitions');
	    
        $searchModel = new NoticeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	    $dataProvider->query->select('text');
        $dataProvider->query->andWhere(['>', 'senderId', 0]);
        $dataProvider->query->distinct();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Notice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
	    $this->can('competitions');
	    
        $model = new Notice();

        if ($model->load(Yii::$app->request->post())) {
        	$regionIds = \Yii::$app->request->post('regionIds');
        	$athleteIds = Athlete::find()->select('id')->where(['hasAccount' => 1]);
        	if ($regionIds) {
        		$athleteIds = $athleteIds->andWhere(['regionId' => $regionIds]);
	        }
	        $athleteIds = $athleteIds->asArray()->column();
        	foreach ($athleteIds as $athleteId) {
        		$notice = new Notice();
        		$notice->athleteId = $athleteId;
        		$notice->text = $model->text;
        		if ($model->link) {
        			$notice->link = $model->link;
		        }
		        $notice->save();
	        }
        	
            return $this->redirect(['index']);
        }
	    return $this->render('create', [
		    'model' => $model,
	    ]);
    }

    /**
     * Finds the Notice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
	    $this->can('competitions');
	    
        if (($model = Notice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
