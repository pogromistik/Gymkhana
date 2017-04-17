<?php
use yii\widgets\MaskedInput;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * @var \common\models\FigureTime $figureTime
 * @var \common\models\Figure     $figure
 * @var \yii\web\View             $this
 * @var integer                   $date
 * @var integer                   $success
 */

$this->title = 'Добавление результатов: ' . $figure->title;
$this->params['breadcrumbs'][] = ['label' => 'Фигуры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $figure->title, 'url' => ['update', 'id' => $figure->id]];
$this->params['breadcrumbs'][] = 'Добавление результатов';
?>

<h3><?= date("d.m.Y", $date) ?></h3>

<div class="alert alert-info">
    После добавления результатов зайдите на
	<?= \yii\bootstrap\Html::a('страницу фигуры', ['update', 'id' => $figure->id], ['target' => '_blank']) ?>
    и в случае необходимости подтвердите новые классы и рекорды.
</div>

<?php if ($success) { ?>
    <div class="alert alert-success">Результат добавлен</div>
<?php } ?>

<?php $form = ActiveForm::begin(['id'      => 'figureTimeForm',
                                 'options' => [
	                                 'data-id'   => $figure->id,
	                                 'data-date' => date("d.m.Y", $date)
                                 ]]); ?>

<?= $form->field($figureTime, 'figureId')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($figureTime, 'date')->hiddenInput()->label(false)->error(false) ?>

<div class="row">
    <div class="col-sm-3">
        <label>Спортсмен</label>
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
    <div class="col-sm-3">
		<?= $form->field($figureTime, 'athleteId')->widget(Select2::classname(), [
			'name'    => 'kv-type-01',
			'data'    => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function (\common\models\Athlete $item) {
				return $item->lastName . ' ' . $item->firstName . '  (' . $item->city->title . ')';
			}),
			'options' => [
				'placeholder' => 'Выберите спортсмена...',
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
				'url'         => \yii\helpers\Url::to('/competitions/participants/motorcycle-category')
			]
		])->label(false) ?>
    </div>
    <div class="col-sm-2">
		<?= $form->field($figureTime, 'timeForHuman')->widget(MaskedInput::classname(), [
			'mask'    => '99:99.99',
			'options' => [
				'id'    => 'setTime',
				'class' => 'form-control',
				'type'  => 'tel'
			]
		])->label(false) ?>
    </div>
    <div class="col-sm-1"><?= $form->field($figureTime, 'fine')->textInput()->label(false) ?></div>
    <div class="col-sm-1">
        <button type="submit" class="btn btn-primary btn-circle fa fa-save" title="Сохранить"></button>
    </div>
</div>

<?php $form->end() ?>
