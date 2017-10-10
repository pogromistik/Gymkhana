<?php
use yii\widgets\MaskedInput;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\helpers\Html;

/**
 * @var \common\models\FigureTime $figureTime
 * @var \yii\web\View             $this
 */

$athlete = $figureTime->athlete;
$figure = $figureTime->figure;
$this->title = 'Редактирование результата: ' . $athlete->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Фигуры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $figure->title, 'url' => ['update', 'id' => $figure->id]];
$this->params['breadcrumbs'][] = 'Редактирование результата';
?>

<h3><?= $figureTime->dateForHuman ?></h3>

<?php $form = ActiveForm::begin(); ?>

<?= DetailView::widget([
	'model'      => $figureTime,
	'attributes' => [
		[
			'attribute' => 'athleteId',
			'value'     => $athlete->getFullName()
		],
		[
			'attribute' => 'motorcycleId',
			'value'     => $figureTime->motorcycle->getFullTitle()
		],
		[
			'attribute' => 'timeForHuman',
			'format'    => 'raw',
			'value'     => $form->field($figureTime, 'timeForHuman')->widget(MaskedInput::classname(), [
				'mask'    => '99:99.99',
				'options' => [
					'id'    => 'setTime',
					'class' => 'form-control',
					'type'  => 'tel'
				]
			])->label(false)
		],
		[
			'attribute' => 'fine',
			'format'    => 'raw',
			'value'     => $form->field($figureTime, 'fine')->textInput()->label(false)
		],
		[
			'attribute' => 'videoLink',
			'format'    => 'raw',
			'value'     => $form->field($figureTime, 'videoLink')->textInput()->label(false)
		],
		[
			'attribute' => '',
			'format'    => 'raw',
			'value'     => Html::submitButton('Сохранить', ['class' => 'btn btn-my-style btn-blue'])
		]
	],
]) ?>

<?php $form->end() ?>
