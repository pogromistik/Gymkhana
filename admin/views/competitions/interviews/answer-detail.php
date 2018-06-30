<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \yii\web\View                  $this
 * @var \common\models\InterviewAnswer $answer
 */

$this->title = 'Подробности для ответа: ' . $answer->text;
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $answer->interview->title, 'url' => ['update', 'id' => $answer->interviewId]];
$this->params['breadcrumbs'][] = ['label' => 'Результаты опроса', 'url' => ['results', 'id' => $answer->interviewId]];
$this->params['breadcrumbs'][] = 'Подробности';
?>
<?php foreach ($answer->votes as $vote) { ?>
    <div>
		<?= $vote->athlete->getFullName() ?>
    </div>
<?php } ?>