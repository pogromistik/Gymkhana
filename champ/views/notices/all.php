<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Notice;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\NoticesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('app', 'Уведомления');
?>
<h2><?= \Yii::t('app', 'Уведомления') ?></h2>
<div class="notice-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			[
				'label'  => \Yii::t('app', 'Дата'),
				'format' => 'raw',
				'value'  => function (Notice $notice) {
					return date("d.m.Y, H:i", $notice->dateAdded);
				}
			],
			[
				'label'  => \Yii::t('app', 'Сообщение'),
				'format' => 'raw',
				'value'  => function (Notice $notice) {
					return $notice->text . '<br><a href="' . $notice->link . '">' . $notice->link . '</a>';
				}
			],
		],
	]); ?>
</div>
