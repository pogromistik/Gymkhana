<?php
/**
 * @var \common\models\Figure $model
 * @var string                $token
 */
?>

Установлен новый Российский рекорд для фигуры
<a href="//gymkhana-cup.ru/competitions/figure?id=<?= $model->id ?>" target="_blank"><?= $model->title ?></a>!
<br>

Рекорд установлен спортсменом <?= $model->bestAthleteInRussia ?> и составляет <?= $model->bestTimeInRussiaForHuman ?>.


<br><br>
<hr>
<a href="//gymkhana-cup.ru/unsubscription?token=<?= $token ?>" target="_blank">нажмите, чтобы отписаться от рассылки</a>