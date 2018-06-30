<?php
/**
 * @var \yii\web\View                  $this
 * @var \common\models\InterviewAnswer $answer
 */
$this->title = 'Редактирование: ' . $answer->text;
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $answer->interview->title, 'url' => ['update', 'id' => $answer->interviewId]];
$this->params['breadcrumbs'][] = ['label' => 'Варианты ответов', 'url' => ['answers', 'id' => $answer->interviewId]];
$this->params['breadcrumbs'][] = $answer->text;
?>

<?= $this->render('_answer_form', ['answer' => $answer]) ?>
