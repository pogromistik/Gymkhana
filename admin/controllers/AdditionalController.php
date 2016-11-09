<?php

namespace admin\controllers;

use common\models\Layout;
use common\models\search\LayoutSearch;
use Yii;
use common\models\Year;
use common\models\search\YearSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdditionalController implements the CRUD actions for Years model.
 */
class AdditionalController extends BaseController
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all Years models.
	 * @return mixed
	 */
	public function actionYears()
	{
		$this->can('admin');

		$searchModel = new YearSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('years', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionYearView($yearId = null, $success = false)
	{
		$this->can('admin');

		if ($yearId) {
			$year = Year::findOne($yearId);
			if (!$year) {
				throw new NotFoundHttpException;
			}
		} else {
			$year = new Year();
		}

		if ($year->load(Yii::$app->request->post()) && $year->save()) {
			return $this->redirect(['year-view', 'yearId' => $year->id, 'success' => true]);
		}

		return $this->render('year-view', [
			'year'    => $year,
			'success' => $success
		]);
	}

	public function actionLayouts()
	{
		$this->can('developer');

		$searchModel = new LayoutSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('layouts', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionLayoutInfo($layoutId = null)
	{
		$this->can('developer');

		if ($layoutId) {
			$layout = Layout::findOne($layoutId);
			if (!$layout) {
				throw new NotFoundHttpException('Шаблон не найден');
			}
		} else {
			$layout = new Layout();
		}

		$success = null;

		if ($layout->load(\Yii::$app->request->post())) {
			if ($layout->save()) {
				$success = 'Шаблон ' . $layout->id . ' успешно сохранен';
			} else {
				return var_dump($layout->errors);
			}
		}

		return $this->render('layout-info', [
			'layout'  => $layout,
			'success' => $success
		]);
	}
}
