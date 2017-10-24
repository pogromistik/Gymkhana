<?php
/**
 * @var \common\models\Figure $model
 * @var string                $token
 */
?>

Установлен новый мировой рекорд для фигуры
<a href="//gymkhana-cup.ru/competitions/figure?id=<?= $model->id ?>" target="_blank" style="color: #56a025"><?= $model->title ?></a>!
<br>

Рекорд установлен спортсменом <?= $model->bestAthlete ?> и составляет <?= $model->bestTimeForHuman ?>.
<br><br>
<small>Обратите внимание - результаты, присланные ранее, НЕ пересчитываются; если спортсмен приехал, к примеру, в C1 -
    он
    останется в C1, даже если рейтинг от нового рекорда составляет более 110%.
</small>