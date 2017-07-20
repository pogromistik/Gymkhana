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
 * @var bool                                    $needClarification
 */

$this->title = 'Участники';
$championship = $stage->championship;
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$championship->groupId], 'url' => ['/competitions/championships/index', 'groupId' => $championship->groupId]];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/championships/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['/competitions/stages/view', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<h3>Зарегистрировать участника на этап</h3>
<div class="alert alert-info">
    Если участник ещё не зарегистрирован в системе - сперва необходимо создать его в разделе
    <a href="/competitions/athlete/create" target="_blank">"спортсмены"</a>
</div>
<a href="#" class="freeNumbersList btn btn-info" data-id="<?= $stage->id ?>">Посмотреть список свободных номеров</a>
<div class="free-numbers" style="display: none">
    <hr>
    <div class="list"></div>
    <hr>
</div>
<?= $this->render('_form', [
	'model'             => $participant,
	'championship'      => $championship,
	'needClarification' => $needClarification
]) ?>

<?php if ($error) { ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<h3>Список участников</h3>
<?= Html::a('Изменить порядок выступления спортсменов', ['/competitions/participants/sort', 'stageId' => $stage->id],
	['class' => 'btn btn-info']) ?>

<div class="small">
    <div class="color-div need-clarification-participant"></div>
    - заявки, требующие модерации;
    <div class="color-div inactive-participant"></div>
    - отклоненные заявки;
    <div class="color-div inactive-participant2"></div>
    - заявки, отмененные участником;
    <div class="color-div out-participant"></div>
    - участники вне зачета.
</div>

<div class="participant-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'rowOptions'   => function (\common\models\Participant $item) {
			if ($item->status === \common\models\Participant::STATUS_ACTIVE) {
				return ['class' => 'active-participant'];
			} elseif ($item->status === \common\models\Participant::STATUS_NEED_CLARIFICATION) {
				return ['class' => 'need-clarification-participant'];
			} elseif ($item->status === \common\models\Participant::STATUS_CANCEL_ATHLETE) {
				return ['class' => 'inactive-participant2'];
			} elseif ($item->status === \common\models\Participant::STATUS_OUT_COMPETITION) {
				return ['class' => 'out-participant'];
			} else {
				return ['class' => 'inactive-participant'];
			}
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
					$title = $athlete->getFullName() . ', ' . $athlete->city->title;
					$html = Html::a($title,
							['/competitions/athlete/view', 'id' => $item->athleteId]) . '<br>' . $item->motorcycle->getFullTitle();
					if (!$item->bestTime && $item->stage->status != \common\models\Stage::STATUS_PAST) {
						$html .= '<div class="small">
<a href="#" class="deleteParticipant red-button" data-id="' . $item->id . '" data-name="' . $athlete->getFullName() . ', ' . $athlete->city->title . '">
Полностью удалить заявку
</a></div>';
					}
					
					return $html;
				}
			],
			/*[
				'attribute' => 'motorcycleId',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\Participant $item) {
					return $item->motorcycle->getFullTitle();
				}
			],*/
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
							]) . '<div class="pt-5">' . Html::a('Вне зачёта', ['change-status', 'id' => $item->id], [
								'class'       => 'btn btn-info changeParticipantStatus',
								'data-status' => \common\models\Participant::STATUS_OUT_COMPETITION,
								'data-id'     => $item->id,
								'title'       => 'Допустить до участия вне зачёта'
							]) . '</div>';
					} elseif ($item->status !== \common\models\Participant::STATUS_NEED_CLARIFICATION && $item->status !== \common\models\Participant::STATUS_OUT_COMPETITION) {
						return Html::a('<span class="fa fa-check"></span>', ['change-status', 'id' => $item->id], [
								'class'       => 'btn btn-success changeParticipantStatus',
								'data-status' => \common\models\Participant::STATUS_ACTIVE,
								'data-id'     => $item->id,
								'title'       => 'Возобновить заявку'
							]) . '<div class="pt-5">' . Html::a('Вне зачёта', ['change-status', 'id' => $item->id], [
								'class'       => 'btn btn-info changeParticipantStatus',
								'data-status' => \common\models\Participant::STATUS_OUT_COMPETITION,
								'data-id'     => $item->id,
								'title'       => 'Допустить до участия вне зачёта'
							]) . '</div>';
					}
					
					$html = Html::a('<span class="fa fa-remove"></span>', ['change-status', 'id' => $item->id], [
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
					if ($item->status !== \common\models\Participant::STATUS_OUT_COMPETITION) {
						$html .= '<div class="pt-5">' . Html::a('Вне зачёта', ['change-status', 'id' => $item->id], [
								'class'       => 'btn btn-info changeParticipantStatus',
								'data-status' => \common\models\Participant::STATUS_OUT_COMPETITION,
								'data-id'     => $item->id,
								'title'       => 'Допустить до участия вне зачёта'
							]) . '</div>';
					}
					
					return $html;
				}
			],
			[
				'attribute' => 'isArrived',
				'visible'   => !($stage->status == \common\models\Stage::STATUS_PAST),
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\Participant $item) {
					if ($item->status == \common\models\Participant::STATUS_ACTIVE
						|| $item->status == \common\models\Participant::STATUS_OUT_COMPETITION
					) {
						$html = '<div class="checkbox"><label for="isArrived-' . $item->id . '">' .
							Html::checkbox('isArrived', $item->isArrived, [
								'id'      => 'isArrived-' . $item->id,
								'class'   => 'participantIsArrived',
								'data-id' => $item->id
							])
							. ' Участник приехал на этап</label></div>';
						
						return $html;
					}
					
					return '';
				}
			]
		],
	]); ?>
</div>
