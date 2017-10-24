<?php
/**
 * @var \common\models\Figure $model
 * @var string                $token
 */
?>

Установлен новый Российский рекорд для фигуры
<a href="//gymkhana-cup.ru/competitions/figure?id=<?= $model->id ?>" target="_blank" style="color: #56a025"><?= $model->title ?></a>!
<br>

Рекорд установлен спортсменом <?= $model->bestAthleteInRussia ?> и составляет <?= $model->bestTimeInRussiaForHuman ?>.