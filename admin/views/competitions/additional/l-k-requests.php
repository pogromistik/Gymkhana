<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpAthletesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обработанные заявки на регистрацию в личном кабинете';
?>
<div class="tmp-athlete-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'rowOptions'   => ['class' => 'gray'],
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'dateAdded',
				'format'    => 'raw',
				'value'     => function (\common\models\TmpAthlete $athlete) {
					return date('d.m.Y, H:i', $athlete->dateAdded);
				}
			],
			[
				'label'  => 'Спортсмен',
				'format' => 'raw',
				'value'  => function (\common\models\TmpAthlete $athlete) {
					$html = $athlete->getFullName() . ', ' . $athlete->country->title;
					
					return $html;
				}
			],
            'email',
            'phone',
			[
				'attribute' => 'city',
				'format'    => 'raw',
				'value'     => function (\common\models\TmpAthlete $athlete) {
					return $athlete->city;
				}
			],
			[
				'attribute' => 'motorcycles',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (\common\models\TmpAthlete $athlete) {
					$motorcycles = $athlete->getMotorcycles();
					$titles = [];
					foreach ($motorcycles as $motorcycle) {
						$titles[] = $motorcycle['mark'] . ' ' . $motorcycle['model'];
					}
					
					return implode('<br>', $titles);
				}
			],
			[
				'format'  => 'raw',
				'value'   => function (\common\models\TmpAthlete $athlete) {
					return \yii\helpers\Html::a('логи', ['/competitions/developer/logs',
						'modelClass' => \common\models\TmpAthlete::class,
						'modelId'    => $athlete->id
					]);
				}
			]
		],
	]); ?>
</div>