<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MessagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отправленные письма';
?>
<div class="message-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'dateAdded',
				'format'    => 'raw',
				'value'     => function (\common\models\Message $message) {
					return date('d.m.Y, H:i', $message->dateAdded);
				}
			],
			'title',
			[
				'attribute' => 'text',
				'format'    => 'raw'
			],
			[
				'attribute' => 'userId',
				'format'    => 'raw',
				'value'     => function (\common\models\Message $message) {
					return $message->sender->username;
				}
			]
		],
	]); ?>
</div>