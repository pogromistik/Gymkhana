<?php
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

/**
 * @var \common\models\Athlete[]    $athletes
 * @var \yii\web\View               $this
 * @var \common\models\ClassHistory $history
 * @var int                         $success
 */
$this->title = 'Изменение класса спортсмену';
$length = 255;
?>

<?php if ($success) { ?>
    <div class="alert alert-success">Класс успешно изменён</div>
<?php } ?>

<?php $form = ActiveForm::begin(['id' => 'changeAthleteClassForm']); ?>

<?= $form->field($history, 'athleteId')->widget(Select2::classname(), [
	'name'    => 'kv-type-01',
	'data'    => $athletes,
	'options' => [
		'placeholder' => 'Выберите спортсмена...',
		'id'          => 'athlete-id',
	],
	/*'pluginOptions' => [
		'ajax' => [
			'url' => \yii\helpers\Url::to('/competitions/athlete/get-list'),
			'dataType' => 'json',
			'data' => new \yii\web\JsExpression('function(params) { return {title:params.term}; }')
		],
	],*/
]) ?>

<?= $form->field($history, 'newClassId')->widget(\kartik\widgets\DepDrop::className(), [
	'options'       => ['id' => 'motorcycle-id'],
	'pluginOptions' => [
		'depends'     => ['athlete-id'],
		'placeholder' => 'Выберите новый класс...',
		'url'         => \yii\helpers\Url::to('/competitions/athlete/classes-category')
	]
]) ?>

<?= $form->field($history, 'event',
	['inputTemplate' => '<div class="input-with-description">{input}</div><div class="text-right color-green" id="length">осталось символов: ' . $length . '</div>'])->textarea(['rows'        => 3,
                                               'placeholder' => 'Текст события, обязательное поле',
                                               'id'          => 'smallText']) ?>

<div class="alert alert-danger" style="display: none"></div>

<?= \yii\helpers\Html::submitButton('Изменить класс', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

