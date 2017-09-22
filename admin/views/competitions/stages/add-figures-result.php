<?php
use common\models\Championship;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;

/**
 * @var \common\models\Participant[]     $participants
 * @var \common\models\Figure[]          $figures
 * @var \common\models\Stage             $stage
 * @var \admin\models\FigureTimeForStage $figureTime
 * @var \yii\web\View                    $this
 */

$championship = $stage->championship;

$this->title = 'Добавить время по фигуре для этапа: "' . $stage->title . '"';
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$championship->groupId], 'url' => ['/competitions/championships/index', 'groupId' => $championship->groupId]];
$this->params['breadcrumbs'][] = ['label' => $championship->title, 'url' => ['/competitions/championships/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['view', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert help-alert alert-info">
    <div class="text-right">
        <span class="fa fa-remove closeHintBtn"></span>
    </div>
    <ul>
        <li>
            Формат времени: мин:сек.мсек. Необходимо обязательно указывать все 6 цифр.
        </li>
        <li>
            Добавление результата идёт в 2 действия:<br>
            1. Выберите фигуру, участника, введите время и штраф (если есть), после чего нажмите кнопку сохранения.<br>
            2. Система покажет рассчитанный результат и запросит подтверждение.
        </li>
        <li>
            Если класс спортсмена не изменится, можете не добавлять результат.
        </li>
    </ul>
</div>

<?php if ($stage->fastenClassFor && $stage->fastenClassFor > 0) { ?>
    <div class="alert required-alert-info">
        <b>Обратите внимание!</b> При добавлении результата с этой
        страницы (в случае повышения класса), класс участника в этапе будет изменен, не смотря на то,
        что у вас стоит отметка "закрепить класс участника". Если вы хотите добавить результат так, чтобы
        он не влиял на класс в этом этапе - сделайте это со страницы "Фигуры".
    </div>
<?php } ?>

<?php $form = ActiveForm::begin(['id' => 'figureTimeForStage']); ?>

<?= $form->field($figureTime, 'date')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($figureTime, 'stageId')->hiddenInput()->label(false)->error(false) ?>

<div class="row">
    <div class="col-sm-2">
        <label>Фигура</label>
    </div>
    <div class="col-sm-3">
        <label>Участник</label>
    </div>
    <div class="col-sm-3">
        <label>Мотоцикл</label>
    </div>
    <div class="col-sm-2">
        <label>Время</label>
    </div>
    <div class="col-sm-1">
        <label>Штраф</label>
    </div>
</div>
<div class="row">
    <div class="col-sm-2">
		<?= $form->field($figureTime, 'figureId')->dropDownList(ArrayHelper::map($figures, 'id', 'title'))
			->label(false) ?>
    </div>
    <div class="col-sm-6">
		<?= $form->field($figureTime, 'participantId')->widget(Select2::classname(), [
			'name'    => 'kv-type-01',
			'data'    => ArrayHelper::map($participants, 'id', function (\common\models\Participant $item) {
				$athlete = $item->athlete;
				
				return $item->number . ' ' . $athlete->getFullName() . '  (' . $athlete->city->title . '), ' . $item->motorcycle->getFullTitle();
			}),
			'options' => [
				'placeholder' => 'Выберите участника...',
				'id'          => 'athlete-id',
			],
		])->label(false) ?>
    </div>
    <div class="col-sm-2">
		<?= $form->field($figureTime, 'timeForHuman')->widget(MaskedInput::classname(), [
			'mask'    => '99:99.99',
			'options' => [
				'id'    => 'setTime',
				'class' => 'form-control'
			]
		])->label(false) ?>
    </div>
    <div class="col-sm-1"><?= $form->field($figureTime, 'fine')->textInput()->label(false) ?></div>
    <div class="col-sm-1">
        <button type="submit" class="btn btn-my-style btn-green btn-circle fa fa-save" title="Сохранить"></button>
    </div>
</div>

<?php $form->end() ?>

<div class="calculate-class">

</div>
