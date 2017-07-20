<?php

use yii\helpers\Html;
use dosamigos\editable\Editable;
use kartik\widgets\Select2;
use yii\grid\GridView;
use common\models\TmpParticipant;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpParticipantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обработанные заявки на участие в этапах';
?>

    <div class="tmp-participant-index">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel'  => $searchModel,
			'rowOptions'   => ['class' => 'gray'],
			'columns'      => [
				['class' => 'yii\grid\SerialColumn'],
				
				[
					'attribute' => 'dateAdded',
					'format'    => 'raw',
					'value'     => function (TmpParticipant $participant) {
						return date("d.m.Y, H:i", $participant->dateAdded);
					}
				],
				[
					'attribute' => 'championshipId',
					'format'    => 'raw',
					'filter'    => Select2::widget([
						'model'         => $searchModel,
						'attribute'     => 'championshipId',
						'data'          => \yii\helpers\ArrayHelper::map(\common\models\Championship::find()->orderBy(['dateAdded' => SORT_DESC])->all(), 'id', 'title'),
						'theme'         => Select2::THEME_BOOTSTRAP,
						'pluginOptions' => [
							'allowClear' => true
						],
						'options'       => [
							'placeholder' => 'Выберите чемпионат...',
						]
					]),
					'value'     => function (TmpParticipant $participant) {
						return $participant->championship->title;
					}
				],
				[
					'attribute' => 'stageId',
					'format'    => 'raw',
					'filter'    => false,
					'value'     => function (TmpParticipant $participant) {
						return $participant->stage->title;
					}
				],
				[
					'label'  => 'Данные о спортсмене',
					'format' => 'raw',
					'value'  => function (TmpParticipant $participant) {
						$result =
							$participant->lastName . ' ' . $participant->firstName . ($participant->number ? ', №' . $participant->number : '');
						$result .= '<br>';
						$result .= $participant->country->title;
						$result .= '<br>';
						$result .= '<small>' . ($participant->phone ? $participant->phone : '') . '</small>';
						if ($participant->email) {
							$result .= '<br>';
							$result .= '<small>' . $participant->email. '</small>';
						}
						
						return $result;
					}
				],
				[
					'label'  => 'Город',
					'format' => 'raw',
					'value'  => function (TmpParticipant $participant) {
						return $participant->city;
					}
				],
				[
					'label'  => 'Мотоцикл',
					'format' => 'raw',
					'value'  => function (TmpParticipant $participant) {
						return $participant->motorcycleMark . ' ' . $participant->motorcycleModel;
					}
				],
			],
		]); ?>
    </div>