<?php
/**
 * @var \common\models\Figure $model
 * @var string                $token
 * @var string                $language
 */
?>

<?= \Yii::t('app', 'Установлен новый Российский рекорд для фигуры {figure}!', [
	'figure' => '<a href="http://gymkhana-cup.ru/competitions/figure?id=' . $model->id . '" target="_blank" style="color: #56a025">'
		. $model->title . '</a>'
], $language) ?>
    <br>

<?= \Yii::t('app', 'Рекорд установлен спортсменом {rider} и составляет {time}.', [
	'rider' => $model->bestAthleteInRussia, 'time' => $model->bestTimeInRussiaForHuman
], $language) ?>