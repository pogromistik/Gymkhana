<?php
/**
 * @var \common\models\Figure $model
 * @var string                $token
 * @var string                $language
 */
?>

<?= \Yii::t('app', 'Установлен новый мировой рекорд для фигуры {$figure}!', [
	'figure' => '<a href="http://gymkhana-cup.ru/competitions/figure?id=' . $model->id . '" target="_blank" style="color: #56a025">'
		. $model->title . '</a>'
], $language) ?>

<?= \Yii::t('app', 'Рекорд установлен спортсменом {rider} и составляет {time}.', [
	'rider' => $model->bestAthlete, 'time' => 'bestTimeForHuman'
], $language) ?>

<br><br>
<small>
	<?= \Yii::t('app', 'Обратите внимание - результаты, присланные ранее, НЕ пересчитываются; если спортсмен приехал, к примеру, в C1 - он останется в C1, даже если рейтинг от нового рекорда составляет более 110%.',
		[], $language) ?>
</small>