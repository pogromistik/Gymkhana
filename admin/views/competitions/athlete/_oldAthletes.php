<?php
/**
 * @var \common\models\Athlete[] $athletes
 */
?>

<div class="alert alert-warning">
    В системе уже есть люди с такими ФИО:<br>
    <ul>
		<?php foreach ($athletes as $athlete) { ?>
            <li><?= $athlete->lastName ?> <?= $athlete->firstName ?>, <?= $athlete->city->title ?></li>
		<?php } ?>
    </ul>
    Всё равно добавить?<br>
    <a href="/competitions/athlete/index" class = "btn btn-danger">Нет</a>
    <a href = "#" onclick="newAthleteConfirm()" class="btn btn-success">Да</a>
</div>
