<?php

namespace common\components;

use common\models\Work;
use yii\web\NotFoundHttpException;

trait WorkTrait
{
	public function actionPage()
	{
		$this->layout = 'work-page';
		/** @var Work $model */
		$model = Work::findOne(['status' => 1]);
		if (!$model) {
			return $this->redirect(['/']);
		}
		$end = $model->dateStart + $model->time * 3600;
		$now = time();
		if ($now > $end) {
			$model->dateStart = time();
			$model->save();
			$end = $model->dateStart + $model->time * 3600;
			$now = time();
		}
		$time = $end - $now;
		$hours = floor($time / 3600);
		$mins = floor(($time - $hours * 3600) / 60);
		$secs = $time - $hours * 3600 - $mins * 60;
		
		$endHours = date('H', $end);
		$endMins = date('i', $end);
		$endSecs = date('s', $end);
		
		return $this->render('@common/views/work/page', [
			'model'    => $model,
			'hours'    => $hours,
			'mins'     => $mins,
			'secs'     => $secs,
			'endHours' => $endHours,
			'endMins'  => $endMins,
			'endSecs'  => $endSecs
		]);
	}
}