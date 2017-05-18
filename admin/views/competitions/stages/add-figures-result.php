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
    <div class="col-sm-3">
		<?= $form->field($figureTime, 'participantId')->widget(Select2::classname(), [
			'name'    => 'kv-type-01',
			'data'    => ArrayHelper::map($participants, 'id', function (\common\models\Participant $item) {
				$athlete = $item->athlete;
				
				return $athlete->getFullName() . '  (' . $athlete->city->title . ')';
			}),
			'options' => [
				'placeholder' => 'Выберите участника...',
				'id'          => 'athlete-id',
			],
		])->label(false) ?>
    </div>
    <div class="col-sm-3">
		<?= $form->field($figureTime, 'motorcycleId')->widget(\kartik\widgets\DepDrop::className(), [
			'options'       => ['id' => 'motorcycle-id'],
			'pluginOptions' => [
				'depends'     => ['athlete-id'],
				'placeholder' => 'Выберите мотоцикл...',
				'url'         => \yii\helpers\Url::to('/competitions/participants/motorcycle-category-for-participants')
			]
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
        <button type="submit" class="btn btn-primary btn-circle fa fa-save" title="Сохранить"></button>
    </div>
</div>

<?php $form->end() ?>

<div class="calculate-class">

</div>
