<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\File;
use yii\bootstrap\Modal;
use common\models\Client;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \dektrium\user\models\UserSearch */

$this->title = 'Пользователи';
?>
<div class="client-view">
	<?= Html::a('Добавить пользователя',
		['create'], ['class' => 'btn btn-success']) ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'username',
				'format'    => 'raw',
				'value'     => function (\common\models\User $user) {
					return Html::a($user->username, ['update', 'id' => $user->id]);
				}
			],
			'email:email',
			[
				'attribute' => 'created_at',
				'value'     => function ($model) {
					if (extension_loaded('intl')) {
						return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
					} else {
						return date('Y-m-d G:i:s', $model->created_at);
					}
				},
			],
			[
				'format' => 'raw',
				'value'  => function ($model) {
					if (!$model->blocked_at) {
						return Html::a('<span class="fa fa-remove"></span>', ['change-status', 'id' => $model->id],
							['class' => 'btn btn-danger', 'title' => 'Заблокировать']);
					} else {
						return Html::a('<span class="fa fa-check"></span>', ['change-status', 'id' => $model->id],
							['class' => 'btn btn-success', 'title' => 'Восстановить']);
					}
				},
			]
		],
	]); ?>

</div>
