<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\editable\Editable;
use common\models\Championship;
use kartik\widgets\Select2;

/**
 * @var \common\models\Participant              $participant
 * @var \common\models\Stage                    $stage
 * @var \yii\data\ActiveDataProvider            $dataProvider
 * @var \common\models\search\ParticipantSearch $searchModel
 * @var \yii\web\View                           $this
 * @var string                                  $error
 * @var array                                   $forSearch
 */

$this->title = 'Участники';
$championship = $stage->championship;
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$championship->groupId], 'url' => ['/competitions/championships/index', 'groupId' => $championship->groupId]];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/championships/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['/competitions/stages/view', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model'        => $participant,
	'championship' => $championship
]) ?>

<?php if ($error) { ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<?= Html::a('Изменить порядок выступления спортсменов', ['/competitions/participants/sort', 'stageId' => $stage->id],
	['class' => 'btn btn-info']) ?>

<div class="small">
    <div class="color-div need-clarification-participant"></div>
    - заявки, требующие модерации;
    <div class="color-div inactive-participant"></div>
    - отклоненные заявки.
</div>

<div class="participant-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'rowOptions'   => function (\common\models\Participant $item) {
			return ['class' => ($item->status === \common\models\Participant::STATUS_ACTIVE) ? 'active-participant' :
				($item->status === \common\models\Participant::STATUS_NEED_CLARIFICATION ? 'need-clarification-participant' : 'inactive-participant')];
		},
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'athleteId',
				'format'    => 'raw',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'athleteId',
					'data'          => $forSearch,
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Укажите фамилию или имя...',
					]
				]),
				'value'     => function (\common\models\Participant $item) {
					$athlete = $item->athlete;
					
					return Html::a($athlete->getFullName() . ', ' . $athlete->city->title, ['/competitions/athlete/view', 'id' => $item->athleteId]);
				}
			],
			[
				'attribute' => 'motorcycleId',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\Participant $item) {
					return $item->motorcycle->getFullTitle();
				}
			],
			[
				'attribute' => 'athleteClassId',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\Participant $item) {
					return $item->athleteClassId ? $item->athleteClass->title : '';
				}
			],
			[
				'attribute' => 'internalClassId',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\Participant $item) {
					return Editable::widget([
						'name'          => 'internalClassId',
						'value'         => $item->internalClassId ? $item->internalClass->title : null,
						'url'           => 'update',
						'type'          => 'select',
						'mode'          => 'pop',
						'clientOptions' => [
							'pk'        => $item->id,
							'placement' => 'right',
							'select'    => [
								'width' => '124px'
							],
							'source'    => \yii\helpers\ArrayHelper::map(\common\models\InternalClass::getActiveClasses($item->championshipId), 'id', 'title'),
						]
					]);
				}
			],
			[
				'attribute' => 'number',
				'format'    => 'raw',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'number', ['class' => 'form-control', 'placeholder' => 'Номер участника...']) . '
</div>',
				'value'     => function (\common\models\Participant $item) {
					return Editable::widget([
						'name'          => 'number',
						'value'         => $item->number,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $item->id,
							'value'     => $item->number,
							'placement' => 'right',
						]
					]);
				}
			],
			[
				'attribute' => 'sort',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\Participant $item) {
					return Editable::widget([
						'name'          => 'sort',
						'value'         => $item->sort,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $item->id,
							'value'     => $item->sort,
							'placement' => 'right',
						]
					]);
				}
			],
			[
				'visible' => !($stage->status == \common\models\Stage::STATUS_PAST),
				'format'  => 'raw',
				'filter'  => false,
				'value'   => function (\common\models\Participant $item) {
					if ($item->status == \common\models\Participant::STATUS_ACTIVE) {
						return Html::a('<span class="fa fa-remove"></span>', ['change-status', 'id' => $item->id], [
							'class'       => 'btn btn-danger changeParticipantStatus',
							'data-status' => \common\models\Participant::STATUS_CANCEL_ADMINISTRATION,
							'title'       => 'Отменить заявку',
							'data-id'     => $item->id
						]);
					} elseif ($item->status !== \common\models\Participant::STATUS_NEED_CLARIFICATION) {
						return Html::a('<span class="fa fa-check"></span>', ['change-status', 'id' => $item->id], [
							'class'       => 'btn btn-success changeParticipantStatus',
							'data-status' => \common\models\Participant::STATUS_ACTIVE,
							'data-id'     => $item->id,
							'title'       => 'Возобновить заявку'
						]);
					}
					
					return Html::a('<span class="fa fa-remove"></span>', ['change-status', 'id' => $item->id], [
							'class'       => 'btn btn-danger changeParticipantStatus',
							'data-status' => \common\models\Participant::STATUS_CANCEL_ADMINISTRATION,
							'title'       => 'Отменить заявку',
							'data-id'     => $item->id
						]) . ' ' . Html::a('<span class="fa fa-check"></span>', ['change-status', 'id' => $item->id], [
							'class'       => 'btn btn-success changeParticipantStatus',
							'data-status' => \common\models\Participant::STATUS_ACTIVE,
							'data-id'     => $item->id,
							'title'       => 'Подтвердить заявку'
						]);
				}
			]
		],
	]); ?>
</div>
