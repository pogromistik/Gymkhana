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

$prevStages = \common\models\Stage::find()->where(['<', 'dateOfThe', $stage->dateOfThe])
	->andWhere(['championshipId' => $stage->championshipId])->one();
?>

<a href="#" class="freeNumbersList btn btn-my-style btn-light-gray" data-id="<?= $stage->id ?>">
    Посмотреть список свободных номеров</a>
<div class="free-numbers" style="display: none">
    <hr>
    <div class="list"></div>
    <hr>
</div>

<h3>Зарегистрировать участника на этап</h3>
<div class="alert help-alert alert-info">
    <div class="text-right">
        <span class="fa fa-remove closeHintBtn"></span>
    </div>
    <ul>
        <li>
            Если участник ещё не зарегистрирован в системе - сперва необходимо создать его в разделе
            <a href="/competitions/athlete/create" target="_blank">"спортсмены"</a>.
        </li>
        <li>
            Поля "класс награждения", "номер спортсмена" и "порядок выступления" необязательны для заполнения.
        </li>
        <li>
            Если вы попробуете зарегистрировать спортсмена, номер которого занят - регистрация не пройдёт, система
            выдаст ошибку.
        </li>
		<?php if ($prevStages) { ?>
            <li>
                При импорте по умолчанию на этап будут зарегистрированы спортсмены, принявшие участие хотя бы в одном
                этапе этого чемпионата, но при нажатии на кнопку импорта появится окно с предпросмотром, где можно сразу
                убрать лишних участников. Или же вы можете в дальнейшем просто удалить созданные заявки.
            </li>
            <li>
                Обратите внимание - классы награждения не импортируются, т.е. после импорта вам надо будет вручную
                заново проставить всем участникам класс награждения.
            </li>
		<?php } ?>
    </ul>
</div>
<?php if ($prevStages) { ?>
    <div>
        <a href="#" class="btn btn-my-style btn-orange" id="prepareParticipantsForImport"
           data-stage-id="<?= $stage->id ?>">
            Импортировать участников с предыдущих этапов</a>
        <div class="modalList"></div>
    </div>
<?php } ?>
<?= $this->render('_form', [
	'model'             => $participant,
	'championship'      => $championship,
	'needClarification' => $needClarification
]) ?>

<?php if ($error) { ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<div class="row">
    <div class="col-sm-6">
        <h3>Список участников</h3>
    </div>
    <div class="col-sm-6 text-right download">
        <div class="btn-group">
            <button type="button" class="btn btn-my-style btn-dirty-blue dropdown-toggle"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Скачать список участников
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
				<?php foreach (\admin\controllers\competitions\XlsController::$typesTitle as $type => $title) { ?>
                    <li><?= Html::a($title, ['/competitions/xls/get-xls', 'type' => $type, 'stageId' => $stage->id]) ?></li>
				<?php } ?>
            </ul>
        </div>
    </div>
</div>

<?php if ($stage->status != \common\models\Stage::STATUS_PAST && $stage->status != \common\models\Stage::STATUS_CALCULATE_RESULTS) { ?>
    <div>
		<?= Html::a('Изменить порядок выступления спортсменов', ['/competitions/participants/sort', 'stageId' => $stage->id],
			['class' => 'btn btn-my-style btn-light-aquamarine']) ?>
		<?= Html::a('Загрузить порядок выступления', ['/competitions/participants/sort-upload', 'stageId' => $stage->id],
			['class' => 'btn btn-my-style btn-light-aquamarine']) ?>
    </div>
<?php } ?>

<div class="alert help-alert alert-info">
    <div class="text-right">
        <span class="fa fa-remove closeHintBtn"></span>
    </div>
    <ul>
		<?php if ($stage->participantsLimit && $stage->participantsLimit > 0) { ?>
            <li>
                Т.к. у вас ограниченное количество участников, все заявки требуют предварительной модерации. Обратите
                внимание - при отклонении заявки участнику будет отправлено письмо на почту, указанную при регистрации;
                при подтверждении - уведомление в личный кабинет. Поэтому, пожалуйста, не нажимайте эти кнопки просто
                так.
            </li>
            <li>
                При необходимости вы можете зарегистрировать спортсменов больше, чем <?= $stage->participantsLimit ?>,
                просто
                система запросит подтверждение.
            </li>
		<?php } ?>
        <li>
            Все действия с кнопками "отклонить", "вернуть", "вне зачёта", "в зачёт" - обратимы.
        </li>
        <li>
            Полностью удалённую заявку нельзя вернуть, но вы всегда можете вновь зарегистрировать нужного человека.
        </li>
    </ul>
</div>

<?php if ($championship->internalClasses) { ?>
    <div class="alert required-alert-info">
        <b>Внимание!</b> Не забывайте проставлять классы награждения спортсменам. Не забывайте отметить "Участник
        приехал на этап".
    </div>
<?php } ?>

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
<a href="#" class="deleteParticipant btn-my-style red-button" data-id="' . $item->id . '" data-name="' . $athlete->getFullName() . ', ' . $athlete->city->title . '">
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
						return '<div>' . Html::a('Отклонить', ['change-status', 'id' => $item->id], [
								'class'       => 'btn btn-my-style btn-red small changeParticipantStatus',
								'data-status' => \common\models\Participant::STATUS_CANCEL_ADMINISTRATION,
								'title'       => 'Отменить заявку',
								'data-id'     => $item->id
							]) . '</div><div>' . Html::a('Вне зачёта', ['change-status', 'id' => $item->id], [
								'class'       => 'btn btn-my-style btn-peach small changeParticipantStatus',
								'data-status' => \common\models\Participant::STATUS_OUT_COMPETITION,
								'data-id'     => $item->id,
								'title'       => 'Допустить до участия вне зачёта'
							]) . '</div>';
					} elseif ($item->status !== \common\models\Participant::STATUS_NEED_CLARIFICATION && $item->status !== \common\models\Participant::STATUS_OUT_COMPETITION) {
						return '<div>' . Html::a('&nbsp;&nbsp;Вернуть&nbsp;&nbsp;', ['change-status', 'id' => $item->id], [
								'class'       => 'btn btn-my-style btn-boggy small changeParticipantStatus',
								'data-status' => \common\models\Participant::STATUS_ACTIVE,
								'data-id'     => $item->id,
								'title'       => 'Возобновить заявку'
							]) . '</div><div>' . Html::a('Вне зачёта', ['change-status', 'id' => $item->id], [
								'class'       => 'btn btn-my-style btn-peach small changeParticipantStatus',
								'data-status' => \common\models\Participant::STATUS_OUT_COMPETITION,
								'data-id'     => $item->id,
								'title'       => 'Допустить до участия вне зачёта'
							]) . '</div>';
					}
					
					$html = '<div>' . Html::a('Отклонить', ['change-status', 'id' => $item->id], [
							'class'       => 'btn btn-my-style btn-red small changeParticipantStatus',
							'data-status' => \common\models\Participant::STATUS_CANCEL_ADMINISTRATION,
							'title'       => 'Отменить заявку',
							'data-id'     => $item->id
						]) . '</div><div>' . Html::a('&nbsp;&nbsp;&nbsp;В зачёт&nbsp;&nbsp;&nbsp;', ['change-status', 'id' => $item->id], [
							'class'       => 'btn btn-my-style btn-boggy small changeParticipantStatus',
							'data-status' => \common\models\Participant::STATUS_ACTIVE,
							'data-id'     => $item->id,
							'title'       => 'Подтвердить заявку'
						]) . '</div>';
					if ($item->status !== \common\models\Participant::STATUS_OUT_COMPETITION) {
						$html .= '<div>' . Html::a('Вне зачёта', ['change-status', 'id' => $item->id], [
								'class'       => 'btn btn-my-style btn-peach small changeParticipantStatus',
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
			],
			[
				'visible' => \Yii::$app->user->can('developer'),
				'format'  => 'raw',
				'value'   => function (\common\models\Participant $item) {
					return Html::a('логи', ['/competitions/developer/logs',
						'modelClass' => \common\models\Participant::class,
						'modelId'    => $item->id
					], ['class' => 'dev-logs dev-logs-btn']);
				}
			]
		],
	]); ?>
</div>
