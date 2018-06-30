<?php

use yii\helpers\Html;

/**
 * @var \common\models\InterviewAnswer $answer
 * @var \common\models\Interview       $interview
 * @var \yii\web\View                  $this
 */
$this->title = 'Варианты ответов для опроса: ' . $interview->title;
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $interview->title, 'url' => ['update', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = 'Варианты ответов';
?>

<h3>Добавить вариант</h3>
<?= $this->render('_answer_form', ['answer' => $answer]) ?>

<table class="table">
	<?php foreach ($interview->interviewAnswers as $interviewAnswer) { ?>
        <tr>
            <td><?= Html::img(\Yii::getAlias('@filesView') . '/' . $interviewAnswer->imgPath) ?></td>
            <td><?= $interviewAnswer->text ?></td>
            <td><?= $interviewAnswer->textEn ?></td>
            <td><?= Html::a('<span class="fa fa-edit"></span>', ['answer-edit', 'id' => $interviewAnswer->id],
					['class' => 'btn btn-my-style btn-blue btn-sm']) ?></td>
            <td><?= Html::a('<span class="fa fa-remove"></span>', ['answer-delete', 'id' => $interviewAnswer->id],
					['class' => 'btn btn-my-style btn-red btn-sm',
					 'data'  => [
						 'confirm' => 'Уверены, что хотите удалить этот ответ?'
					 ]]) ?></td>
        </tr>
	<?php } ?>
</table>

