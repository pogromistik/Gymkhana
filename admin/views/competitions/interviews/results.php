<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \yii\web\View            $this
 * @var \common\models\Interview $model
 * @var \common\models\Vote      $vote
 */

$this->title = 'Результаты для опроса: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Результаты опроса';
?>

    <table class="table">
        <thead>
        <tr>
            <th>Вариант ответа</th>
            <th>Количество голосов</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($model->interviewAnswers as $answer) { ?>
            <tr>
                <td><?= $answer->text ?></td>
                <td><?= $answer->getVotesCount() ?></td>
            </tr>
		<?php } ?>
        </tbody>
    </table>

    <h3>Оставить голос от имени спортсмена:</h3>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($vote, 'interviewId')->hiddenInput()->error(false)->label(false) ?>
<?= $form->field($vote, 'athleteId')->widget(Select2::classname(), [
	'name'    => 'kv-type-01',
	'data'    => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function (\common\models\Athlete $item) {
		return $item->lastName . ' ' . $item->firstName . ' (' . $item->city->title . ')';
	}),
	'options' => [
		'placeholder' => 'Выберите спортсмена...',
		'id'          => 'athlete-id',
	],
]) ?>
<?= $form->field($vote, 'answerId')->dropDownList(ArrayHelper::map($model->interviewAnswers, 'id', 'text')) ?>

    <div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-my-style btn-green']) ?>
    </div>

<?php ActiveForm::end(); ?>